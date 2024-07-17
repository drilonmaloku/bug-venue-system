<?php namespace App\Modules\Reservations\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationStaff extends Model
{
    use HasFactory;

    protected $guarded =[];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

}
