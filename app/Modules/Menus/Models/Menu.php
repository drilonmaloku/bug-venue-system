<?php namespace App\Modules\Menus\Models;

use App\Modules\Reservations\Models\Reservation;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $guarded =[];

    public function reservations()
    {
        return [];
    }

    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }

//    public function reservations()
//    {
//        return $this->hasMany(Reservation::class);
//    }
}
