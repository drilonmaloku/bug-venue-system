<?php

namespace App\Modules\Decors\Models;

use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'name', 
        'description', 
        'image', 
        'location_id', 
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }
}
