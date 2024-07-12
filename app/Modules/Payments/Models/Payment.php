<?php namespace App\Modules\Payments\Models;


use App\Modules\Clients\Models\Client;
use App\Modules\Reservations\Models\Reservation;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded =[];


    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
