<?php namespace App\Modules\Reservations\Models;


use App\Models\User;
use App\Modules\Clients\Models\Client;
use App\Modules\Invoices\Models\Invoice;
use App\Modules\Payments\Models\Payment;
use App\Modules\Venues\Models\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded =[];

    const RESERVATION_TYPES = [
        1 => 'Ditë e Plotë',
        2 => 'Mëngjes',
        3 => 'Mbrëmje',
    ];
    // Add a custom accessor for human-readable reservation type
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

}
