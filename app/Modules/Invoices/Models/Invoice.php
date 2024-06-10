<?php namespace App\Modules\Invoices\Models;

use App\Models\User;
use App\Modules\Reservations\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded =[];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
}
