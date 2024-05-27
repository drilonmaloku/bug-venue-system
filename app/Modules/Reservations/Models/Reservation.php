<?php namespace App\Modules\Reservations\Models;


use App\Models\User;
use App\Modules\Clients\Models\Client;
use App\Modules\Payments\Models\Payment;
use App\Modules\Venues\Models\Venue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded =[];

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
}
