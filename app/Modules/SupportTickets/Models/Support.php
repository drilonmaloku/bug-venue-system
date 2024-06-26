<?php

namespace App\Modules\SupportTickets\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;
    protected $table = 'support_tickets';
    protected $guarded = [];



    public function comments()
    {
        return $this->hasMany(SupportComment::class , 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
