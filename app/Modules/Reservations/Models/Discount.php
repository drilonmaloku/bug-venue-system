<?php

namespace App\Modules\Reservations\Models;


use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

}
