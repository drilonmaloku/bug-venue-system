<?php namespace App\Modules\Clients\Models;

use App\Modules\Payments\Models\Payment;
use App\Modules\Reservations\Models\Reservation;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded =[];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
