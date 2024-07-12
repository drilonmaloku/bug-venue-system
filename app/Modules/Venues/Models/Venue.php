<?php namespace App\Modules\Venues\Models;

use App\Modules\Events\Models\Event;
use App\Modules\Reservations\Models\Reservation;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
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

}
