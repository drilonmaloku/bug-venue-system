<?php

namespace App\Modules\Reservations\Models;

use App\Models\User;
use App\Modules\Reservations\Models\Reservation;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Model;

class PricingStatusTracking extends Model
{
    protected $table = 'pricing_status_tracking';

    protected $fillable = [
        'price',
        'number_of_guests',
        'total_price',
        'menu_price',
        'reservation_id',
        'user_id',
        'total_discount_price',
        'total_invoice_price'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
