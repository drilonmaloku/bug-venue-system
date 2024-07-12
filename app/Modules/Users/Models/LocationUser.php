<?php

declare(strict_types=1);

namespace App\Modules\Users\Models;

use App\Models\User;
use App\Modules\Location\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationUser extends Model
{
    use HasFactory;

    protected $table= 'locations_users';
    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo(Location::class,'location_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}