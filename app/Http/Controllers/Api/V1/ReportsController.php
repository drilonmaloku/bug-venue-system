<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Payments\Models\Payment;
use App\Modules\Clients\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends BaseApiController
{
    /**
     * Get available report types
     */
    public function types(): JsonResponse
    {
        return $this->successResponse([
            ['code' => 'reservations', 'label' => 'Reservations Report', 'description' => 'Detailed reservations listing'],
            ['code' => 'revenue', 'label' => 'Revenue Report', 'description' => 'Financial and revenue analysis'],
            ['code' => 'clients', 'label' => 'Clients Report', 'description' => 'Client activity and statistics'],
            ['code' => 'payments', 'label' => 'Payments Report', 'description' => 'Payment transactions report'],
        ]);
    }

    /**
     * Generate report
     */
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:reservations,revenue,clients,payments'],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
            'format' => ['nullable', 'in:json,csv,pdf'],
        ]);

        $type = $validated['type'];
        $from = $validated['date_from'];
        $to = $validated['date_to'];

        $report = match($type) {
            'reservations' => $this->generateReservationsReport($from, $to),
            'revenue' => $this->generateRevenueReport($from, $to),
            'clients' => $this->generateClientsReport($from, $to),
            'payments' => $this->generatePaymentsReport($from, $to),
            default => null,
        };

        return $this->successResponse([
            'type' => $type,
            'period' => ['from' => $from, 'to' => $to],
            'data' => $report,
        ]);
    }

    /**
     * Get reservations summary
     */
    public function reservationsSummary(Request $request): JsonResponse
    {
        $year = $request->input('year', now()->year);

        $monthly = Reservation::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(number_of_guests) as guests')
            )
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $this->successResponse([
            'year' => $year,
            'monthly' => $monthly,
            'total' => $monthly->sum('count'),
            'total_guests' => $monthly->sum('guests'),
        ]);
    }

    /**
     * Get financial summary
     */
    public function financialSummary(Request $request): JsonResponse
    {
        $year = $request->input('year', now()->year);

        $monthlyRevenue = Payment::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(value) as revenue')
            )
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyExpenses = DB::table('expenses')
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(price) as expenses')
            )
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $this->successResponse([
            'year' => $year,
            'revenue' => $monthlyRevenue,
            'expenses' => $monthlyExpenses,
        ]);
    }

    /**
     * Get clients summary
     */
    public function clientsSummary(Request $request): JsonResponse
    {
        $totalClients = Client::count();
        $newClientsThisMonth = Client::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $topClients = Client::withCount('reservations')
            ->orderByDesc('reservations_count')
            ->limit(10)
            ->get();

        return $this->successResponse([
            'total' => $totalClients,
            'new_this_month' => $newClientsThisMonth,
            'top_clients' => $topClients->map(function ($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->first_name . ' ' . $client->last_name,
                    'reservations_count' => $client->reservations_count,
                ];
            }),
        ]);
    }

    private function generateReservationsReport($from, $to): array
    {
        $reservations = Reservation::with(['client', 'venue'])
            ->whereBetween('date', [$from, $to])
            ->get();

        return [
            'total' => $reservations->count(),
            'by_status' => $reservations->groupBy('status')->map->count(),
            'by_venue' => $reservations->groupBy('venue_id')->map->count(),
            'total_guests' => $reservations->sum('number_of_guests'),
        ];
    }

    private function generateRevenueReport($from, $to): array
    {
        $payments = Payment::whereBetween('date', [$from, $to])->get();

        return [
            'total' => $payments->sum('value'),
            'count' => $payments->count(),
            'by_method' => $payments->groupBy('payment_method')->map->sum('value'),
            'average' => $payments->avg('value'),
        ];
    }

    private function generateClientsReport($from, $to): array
    {
        $clients = Client::withCount(['reservations' => function ($q) use ($from, $to) {
                $q->whereBetween('date', [$from, $to]);
            }])
            ->get();

        return [
            'total_active' => $clients->where('reservations_count', '>', 0)->count(),
            'total_new' => $clients->where('created_at', '>=', $from)->count(),
            'top_clients' => $clients->sortByDesc('reservations_count')->take(10)->values(),
        ];
    }

    private function generatePaymentsReport($from, $to): array
    {
        $payments = Payment::with(['client', 'reservation'])
            ->whereBetween('date', [$from, $to])
            ->get();

        return [
            'total_amount' => $payments->sum('value'),
            'total_count' => $payments->count(),
            'by_method' => $payments->groupBy('payment_method')->map->count(),
            'payments' => $payments,
        ];
    }
}
