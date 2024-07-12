<?php

namespace App\Modules\SupportTickets\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportComment extends Model
{
    use HasFactory;
    protected $table = 'support_tickets_comments';
    protected $guarded = [];



    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
