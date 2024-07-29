<?php namespace App\Modules\Reservations\Models;


use App\Models\User;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationComment extends Model
{

    protected $table = 'reservations_comments'; // Specify the table name

    protected $fillable = ['location_id','reservation_id', 'user_id', 'comment'];

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
        return $this->belongsTo(User::class,'user_id');
    }


}
