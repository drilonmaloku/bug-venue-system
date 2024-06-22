<?php

namespace App\Modules\Reservations\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

}
