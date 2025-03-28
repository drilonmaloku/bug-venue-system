<?php

namespace App\Modules\SeatingPlans\Models;

use App\Modules\Files\Models\AppFile;
use App\Modules\Reservations\Models\Reservation;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class SeatingPlan extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'user_id', 
        'name', 
        'description', 
        'image_id',
        'location_id', 
    ];

    protected $appends = [
        'image_url'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function image()
    {
        return $this->hasOne(AppFile::class,'id','image_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return URL::route('files.getFile', ['path' => $this->image->file_path]);
        }

        return null;
    }
} 