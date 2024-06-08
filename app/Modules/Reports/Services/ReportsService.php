<?php namespace App\Modules\Reports\Services;


use App\Modules\Clients\Models\Client;
use App\Modules\Expenses\Models\Expense;
use App\Modules\Payments\Models\Payment;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Venues\Models\Venue;
use Illuminate\Support\Carbon;

class ReportsService
{
    public function generateGeneralReport($startDate, $endDate)
    {
        // Convert to Carbon instances for date comparison
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Calculate the number of days in the period
        $daysCount = $startDate->diffInDays($endDate) + 1; // +1 to include both start and end dates



        // Count the number of clients created during the period
        $clientsCount = Client::whereBetween('created_at', [$startDate, $endDate])->count();

        // Count the number of reservations created during the period
        $reservationsCount = Reservation::whereBetween('date', [$startDate->format('d-m-Y'), $endDate->format('d-m-Y')])->count();

        // Get reservation details along with their associated venues
        $reservations = Reservation::with('venue')
            ->whereBetween('date', [$startDate->format('d-m-Y'), $endDate->format('d-m-Y')])
            ->get();

        // Count the number of payments created during the period
        $paymentsCount = Payment::whereBetween('date', [$startDate->format('d-m-Y'), $endDate->format('d-m-Y')])->count();

        // Sum the value of payments created during the period
        $paymentsSum = Payment::whereBetween('date', [$startDate->format('d-m-Y'), $endDate->format('d-m-Y')])->sum('value');

        // Get all venues
        $allVenues = Venue::all();

        $expensesSum = Expense::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->sum('amount');


        // Get the venues with the number of reservations and total value of reservations
        $venues = $allVenues->map(function ($venue) use ($startDate, $endDate) {
            $reservations = $venue->reservations()->whereBetween('date', [$startDate->format('d-m-Y'), $endDate->format('d-m-Y')])->get();
            $reservationsCount = $reservations->count();
            $currentPaymentSum = $reservations->sum('current_payment');
            $totalPaymentSum = $reservations->sum('total_payment');
            $staffExpenses = $reservations->sum('staff_expenses');
            $paymentsDue = $totalPaymentSum - $currentPaymentSum;
            return [
                'name' => $venue->name,
                'reservations_count' => $reservationsCount,
                'current_payment_sum' => $currentPaymentSum,
                'total_payment_sum' => $totalPaymentSum,
                'staff_expenses' => $staffExpenses,
                'payments_due' => $paymentsDue,
            ];
        })
            ->sortByDesc('reservations_count')
            ->values()
            ->all();

        // Count the number of venues with reservations
        $venuesWithReservationsCount = collect($venues)->where('reservations_count', '>', 0)->count();

        // Sum the total payments due across all venues
        $totalPaymentsDue = collect($venues)->sum('payments_due');
        $staffExpenses = collect($venues)->sum('staff_expenses');

        // Prepare the report data
        $reportData = [
            'days_count' => $daysCount,
            'clients_count' => $clientsCount,
            'reservations_count' => $reservationsCount,
            'reservations' => $reservations,
            'payments_count' => $paymentsCount,
            'payments_sum' => $paymentsSum,
            'venues' => $venues,
            'total_venues' => $allVenues->count(),
            'venues_with_reservations_count' => $venuesWithReservationsCount,
            'total_payments_due' => $totalPaymentsDue,
            'expenses_sum' => $expensesSum,
            'staff_expenses' => $staffExpenses
        ];

        // Return or process the report data as needed
        return $reportData;
    }

}
