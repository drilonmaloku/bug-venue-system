<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Observers;

use App\Modules\Logs\Models\Log;
use App\Modules\Groups\Models\Group;
use App\Modules\Logs\Services\LogService;
use App\Modules\Reservations\Models\PricingStatusTracking;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Services\ReservationsService;

class ReservationObserver
{
    public $logService;
    public $reservationService;


    public function __construct(LogService $logService, ReservationsService $reservationService)
    {
        $this->logService = $logService;
        $this->reservationService = $reservationService;
    }

    public function created(Reservation $reservation)
    {

        $price = $reservation->current_payment;
        $numberOfGuests = $reservation->number_of_guests;
        $totalPrice = $reservation->total_payment;
        $menuPrice = $reservation->menu_price;


        $pricingStatusTracking = new PricingStatusTracking();
        $pricingStatusTracking->price = $price;
        $pricingStatusTracking->number_of_guests = $numberOfGuests;
        $pricingStatusTracking->total_price = intval($numberOfGuests) * doubleval($menuPrice);
        $pricingStatusTracking->menu_price = $menuPrice;

        $pricingStatusTracking->reservation_id = $reservation->id;
        $pricingStatusTracking->user_id = auth()->user()->id;

        $pricingStatusTracking->save();
    }

    public function updated(Reservation $reservation)
    {
        $oldNumberOfGuests = $reservation->getOriginal('number_of_guests');
        $newNumberOfGuests = $reservation->number_of_guests;
        $oldMenuPrice = $reservation->getOriginal('menu_price');
        $newMenuPrice = $reservation->menu_price;

        if ($oldNumberOfGuests !== $newNumberOfGuests || $oldMenuPrice !== $newMenuPrice ) {
            $this->reservationService->storePricingTracking($reservation, $newNumberOfGuests,$newMenuPrice);
        }
    }

    public function deleted(Reservation $reservation)
    {

    }
}
