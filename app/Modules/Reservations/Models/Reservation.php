<?php

namespace App\Modules\Reservations\Models;


use App\Models\User;
use App\Modules\Clients\Models\Client;
use App\Modules\Payments\Models\Payment;
use App\Modules\Venues\Models\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    const RESERVATION_TYPES = [
        1 => 'Ditë e Plotë',
        2 => 'Mëngjes',
        3 => 'Mbrëmje',
    ];

    public function getReservationTypeNameAttribute()
    {
        return self::RESERVATION_TYPES[$this->reservation_type] ?? 'Unknown';
    }

    // Append custom attribute to model's array and JSON representations
    protected $appends = ['reservation_type_name'];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }
    public function comments()
    {
        return $this->hasMany(ReservationComment::class);
    }

    public function pricingTracking()
    {
        return $this->hasMany(PricingStatusTracking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // Calculate total amount of invoices
    public function getTotalInvoiceAmountAttribute()
    {
        return $this->invoices->sum('amount');
    }

    // Calculate total discount
    public function getTotalDiscountAmountAttribute()
    {
        return $this->discounts->sum('amount');
    }


    public function updateReservationTracking($reservation)
    {
        $totalInvoiceSum = $reservation->invoices->sum('amount');
        $totalDiscountSum = $reservation->discounts->sum('amount');
        $numberOfGuests = $reservation->number_of_guests;
        return PricingStatusTracking::create([
            'user_id' => auth()->user()->id,
            'number_of_guests' => $numberOfGuests,
            'menu_price' => $reservation->menu_price,
            'price' => $reservation->menu_price,
            'total_price' => ($numberOfGuests * $reservation->menu_price) + $totalInvoiceSum - $totalDiscountSum,
            'total_invoice_price'=>$totalInvoiceSum,
            'total_discount_price'=>$totalDiscountSum,
            'reservation_id' => $reservation->id,
        ]);
    }

    public function updateTotalData()
    {
        $currentReservation = $this;
        $totalInvoiceSum = $currentReservation->invoices->sum('amount');
        $totalDiscountSum = $currentReservation->discounts->sum('amount');

        return $currentReservation->update(
            [
                'total_payment' => ($currentReservation->number_of_guests * $currentReservation->menu_price) + ($totalInvoiceSum - $totalDiscountSum)
            ]
        );

    }
}
