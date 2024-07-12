<?php

namespace App\Models;

use App\Modules\Expenses\Models\Expense;
use App\Modules\Location\Models\Location;
use App\Modules\Users\Models\LocationUser;
use App\Scopes\CurrentLocationScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, HasRoles,SoftDeletes;


    const ROLE_ADMIN = "admin";
    const ROLE_SUPERAMDIN = "super-admin";
    const ROLE_MANAGER = "manager";
    const SYSTEM_ADMIN = "system-admin";
    const ROLE_STAFF = "staff";




    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


      /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at']; // Add deleted_at to dates




    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the current location for the user.
     *
     * @return id|null
     */
    public function getCurrentLocationId()
    {
        $locationUser = $this->locationUsers->first();
        return $locationUser ? $locationUser->location_id : null;
    }

    /**
     * Define a many-to-many relationship with LocationUser.
     *
     */
    public function locationUsers()
    {
        return $this->hasMany(LocationUser::class, 'user_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'locations_users', 'user_id', 'location_id');
    }



    public function getCurrentLocationSlug()
    {
        $locationUser = $this->locationUsers->first();
        return $locationUser ? Location::find($locationUser->location_id)->slug : null;
    }
    
}
