<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Payments\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends BaseApiController
{
    /**
     * Get dashboard statistics
     */
    public function stats(Request $request): JsonResponse
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        // Today's stats
        $todayReservations = Reservation::whereDate('date', $today)->count();
        $todayGuests = Reservation::whereDate('date', $today)->sum('number_of_guests');
        $todayRevenue = Payment::whereDate('date', $today)->sum('value');

        // Monthly stats
        $monthReservations = Reservation::whereBetween('date', [$monthStart, $monthEnd])->count();
        $monthRevenue = Payment::whereBetween('date', [$monthStart, $monthEnd])->sum('value');

        // Status counts
        $confirmedCount = Reservation::where('status', 1)->count();
        $pendingCount = Reservation::where('status', 2)->count();
        $cancelledCount = Reservation::where('status', 3)->count();

        // Total stats
        $totalReservations = Reservation::count();
        $totalRevenue = Payment::sum('value');

        return $this->successResponse([
            'today' => [
                'reservations' => $todayReservations,
                'guests' => $todayGuests,
                'revenue' => $todayRevenue,
            ],
            'month' => [
                'reservations' => $monthReservations,
                'revenue' => $monthRevenue,
            ],
            'status_counts' => [
                'confirmed' => $confirmedCount,
                'pending' => $pendingCount,
                'cancelled' => $cancelledCount,
                'total' => $totalReservations,
            ],
            'total_revenue' => $totalRevenue,
        ]);
    }

    /**
     * Get upcoming events
     */
    public function events(Request $request): JsonResponse
    {
        $days = $request->input('days', 7);
        $from = now()->toDateString();
        $to = now()->addDays($days)->toDateString();

        $events = Reservation::with(['client', 'venue'])
            ->whereBetween('date', [$from, $to])
            ->where('status', '!=', 3) // Exclude cancelled
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(20)
            ->get();

        return $this->successResponse($events->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'date' => $reservation->date,
                'time' => $reservation->start_time,
                'event_name' => $reservation->description ?? 'Reservation #' . $reservation->id,
                'venue' => $reservation->venue?->name,
                'client' => $reservation->client ? $reservation->client->first_name . ' ' . $reservation->client->last_name : null,
                'guests' => $reservation->number_of_guests,
                'status' => $reservation->status,
            ];
        }));
    }

    /**
     * Get kitchen dashboard data
     */
    public function kitchen(Request $request): JsonResponse
    {
        $date = $request->input('date', now()->toDateString());

        $reservations = Reservation::with(['client', 'menu'])
            ->whereDate('date', $date)
            ->where('status', '!=', 3)
            ->orderBy('start_time')
            ->get();

        $totalGuests = $reservations->sum('number_of_guests');
        
        // Group by menu
        $menuSummary = $reservations->groupBy('menu_id')->map(function ($group) {
            return [
                'menu_name' => $group->first()->menu?->name ?? 'No Menu',
                'count' => $group->count(),
                'guests' => $group->sum('number_of_guests'),
            ];
        })->values();

        return $this->successResponse([
            'date' => $date,
            'total_reservations' => $reservations->count(),
            'total_guests' => $totalGuests,
            'menu_summary' => $menuSummary,
            'reservations' => $reservations->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'time' => $reservation->start_time,
                    'guests' => $reservation->number_of_guests,
                    'menu' => $reservation->menu?->name,
                    'client' => $reservation->client ? $reservation->client->first_name . ' ' . $reservation->client->last_name : null,
                    'notes' => $reservation->notes,
                ];
            }),
        ]);
    }

    /**
     * Get KPI data
     */
    public function kpi(Request $request): JsonResponse
    {
        $period = $request->input('period', 'month'); // week, month, year

        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $endDate = match($period) {
            'week' => now()->endOfWeek(),
            'year' => now()->endOfYear(),
            default => now()->endOfMonth(),
        };

        // Reservations trend
        $reservationsByDay = Reservation::select(
                DB::raw('DATE(date) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue trend
        $revenueByDay = Payment::select(
                DB::raw('DATE(date) as date'),
                DB::raw('SUM(value) as total')
            )
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->successResponse([
            'period' => $period,
            'reservations_trend' => $reservationsByDay,
            'revenue_trend' => $revenueByDay,
        ]);
    }
}
