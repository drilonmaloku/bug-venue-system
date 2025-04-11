<?php namespace App\Modules\Location\Models;

use App\Models\User;
use App\Modules\Settings\Models\LocationSettings;
use App\Modules\LocationPayments\Models\LocationInvoice;
use App\Modules\LocationPayments\Models\LocationPayment;
use App\Modules\LocationPayments\Models\LocationCreditDeposit;
use App\Modules\LocationPayments\Models\LocationCreditTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $guarded =[];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($location) {
            // Create an empty LocationSettings for the newly created Location
            LocationSettings::create([
                'location_id' => $location->id,
                'settings' => json_encode([
                    "contract" => ""
                ])
            ]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'locations_users', 'location_id', 'user_id');
    }

    public function locationSettings()
    {
        return $this->hasOne(LocationSettings::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(LocationInvoice::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(LocationPayment::class, LocationInvoice::class);
    }

    public function creditDeposits(): HasMany
    {
        return $this->hasMany(LocationCreditDeposit::class);
    }
}
