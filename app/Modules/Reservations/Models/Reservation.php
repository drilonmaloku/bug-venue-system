<?php

namespace App\Modules\Reservations\Models;

use App\Models\User;
use App\Modules\Clients\Models\Client;
use App\Modules\Collaborators\Models\Collaborator;
use App\Modules\Decors\Models\Decor;
use App\Modules\Menus\Models\Menu;
use App\Modules\Payments\Models\Payment;
use App\Modules\Venues\Models\Venue;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Modules\Reminders\Models\Reminder;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Append custom attribute to model's array and JSON representations
    protected $appends = ['reservation_type_name'];

    const RESERVATION_TYPES = [
        1 => 'Ditë e Plotë',
        2 => 'Mëngjes',
        3 => 'Mbrëmje',
    ];

    const RESERVATION_STATUS_CONFIRMED = 1;
    const RESERVATION_STATUS_ON_HOLD = 2;
    const RESERVATION_STATUS_CANCELED = 1;


    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
        
        // Add UUID generation on creation
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function getReservationTypeNameAttribute()
    {

        return self::RESERVATION_TYPES[$this->reservation_type] ?? 'Unknown';
    }

    public function getStatusLabelAttribute()
    {
        if($this->status == 1) {
            return __('reservations.status.confirmed');
        }
        if($this->status == 2) {
            return __('reservations.status.not_confirmed');
        }
        if($this->status == 3) {
            return __('reservations.status.canceled');
        }
    }

    public function getStatusClassAttribute()
    {
        if($this->status == 1) {
            return 'planned';
        }
        if($this->status == 2) {
            return 'finished';
        }
        if($this->status == 3) {
            return 'canceled';
        }
    }




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

    public function guests()
    {
        return $this->hasMany(ReservationGuest::class);
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

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'manager_id');
    }

    public function decor()
    {
        return $this->belongsTo(Decor::class,'decor_id');
    }
 
    public function collaborators()
    {
        return $this->belongsToMany(Collaborator::class, 'collaborator_reservation', 'reservation_id', 'collaborator_id');
    }

    public function getTotalInvoiceAmountAttribute()
    {
        return $this->invoices->sum('amount');
    }

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
            "location_id" => auth()->user()->getCurrentLocationId(),
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
        $newCurrentPayment = $currentReservation->payments()->sum('value');

        return $currentReservation->update(
            [
                'current_payment' => $newCurrentPayment,
                'total_payment' => ($currentReservation->number_of_guests * $currentReservation->menu_price) + ($totalInvoiceSum - $totalDiscountSum)
            ]
        );

    }



    public function reservationStaff()
    {
        return $this->hasMany(ReservationStaff::class, 'reservation_id');
    }

    /**
     * Get the reminders for the reservation.
     */
    public function reminders()
    {
        return $this->hasMany(Reminder::class, 'reservation_id');
    }
}
