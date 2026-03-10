<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Observers;

use App\Modules\Logs\Services\LogService;
use App\Modules\Payments\Models\PaymentScheduleTemplate;
use App\Modules\Payments\Services\PaymentScheduleService;
use App\Modules\Reservations\Models\PricingStatusTracking;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Services\ReservationsService;
use Illuminate\Support\Facades\Log;

class ReservationObserver
{
    public $logService;
    public $reservationService;
    public $paymentScheduleService;


    public function __construct(
        LogService $logService, 
        ReservationsService $reservationService,
        PaymentScheduleService $paymentScheduleService
    )
    {
        $this->logService = $logService;
        $this->reservationService = $reservationService;
        $this->paymentScheduleService = $paymentScheduleService;
    }

    public function created(Reservation $reservation)
    {

        $price = $reservation->current_payment;
        $numberOfGuests = $reservation->number_of_guests;
        $totalPrice = $reservation->total_payment;
        $menuPrice = $reservation->menu_price;


        $pricingStatusTracking = new PricingStatusTracking();
        $pricingStatusTracking->location_id = auth()->user()->getCurrentLocationId();
        $pricingStatusTracking->price = $price;
        $pricingStatusTracking->number_of_guests = $numberOfGuests;
        $pricingStatusTracking->total_price = intval($numberOfGuests) * doubleval($menuPrice);
        $pricingStatusTracking->menu_price = $menuPrice;

        $pricingStatusTracking->reservation_id = $reservation->id;
        $pricingStatusTracking->user_id = auth()->user()->id;

        $pricingStatusTracking->save();

        // Auto-generate payment schedule if total payment > 0
        if ($reservation->total_payment > 0) {
            $this->createDefaultPaymentSchedule($reservation);
        }
    }

    public function updated(Reservation $reservation)
    {
        $oldNumberOfGuests = $reservation->getOriginal('number_of_guests');
        $newNumberOfGuests = $reservation->number_of_guests;
        $oldMenuPrice = $reservation->getOriginal('menu_price');
        $newMenuPrice = $reservation->menu_price;

        $totalDiscountSum = $reservation->discounts()->sum('amount');

        $totalInvoiceSum = $reservation->invoices()->sum('amount');

        $this->reservationService->storePricingTracking(
            $reservation,
            $newNumberOfGuests,
            $newMenuPrice,
            $totalInvoiceSum,
            $totalDiscountSum
        );

        // Check if total_payment was updated and no payment schedule exists
        if ($reservation->wasChanged('total_payment') && $reservation->total_payment > 0) {
            $existingSchedule = $reservation->paymentSchedule;
            if (!$existingSchedule) {
                $this->createDefaultPaymentSchedule($reservation);
            }
        }
    }

    public function deleted(Reservation $reservation)
    {
        // Payment schedules will be cascade deleted due to foreign key constraint
    }

    /**
     * Create a default payment schedule for a reservation.
     */
    protected function createDefaultPaymentSchedule(Reservation $reservation): void
    {
        try {
            $this->paymentScheduleService->createFromTemplate(
                $reservation,
                PaymentScheduleTemplate::TYPE_STANDARD_3_TIER,
                $reservation->total_payment,
                auth()->id()
            );

            Log::info('Auto-generated payment schedule for reservation', [
                'reservation_id' => $reservation->id,
                'client_id' => $reservation->client_id,
                'total_amount' => $reservation->total_payment,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to auto-generate payment schedule', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
