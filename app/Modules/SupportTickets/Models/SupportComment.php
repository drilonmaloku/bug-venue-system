<?php

namespace App\Modules\SupportTickets\Models;

use App\Models\User;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportComment extends Model
{
    use HasFactory;
    protected $table = 'support_tickets_comments';
    protected $guarded = [];


    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }


    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
