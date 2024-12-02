<?php

namespace App\Modules\Collaborators\Models;

use App\Modules\Reservations\Models\Reservation;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collaborator extends Model
{
     use HasFactory,
         SoftDeletes;

    protected $fillable = [
        'id', 
        'name', 
        'email', 
        'phone_number', 
        'typeof',
        'location_id', 
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }
 
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'collaborator_reservation', 'collaborator_id', 'reservation_id');
    }
}
