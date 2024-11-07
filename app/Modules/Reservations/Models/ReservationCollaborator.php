<?php 

namespace App\Modules\Reservations\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationCollaborator extends Model
{
    use HasFactory;
    protected $table = 'collaborator_reservation';

    protected $guarded =[];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'collaborator_reservation', 'collaborator_id', 'reservation_id');
    }

}
