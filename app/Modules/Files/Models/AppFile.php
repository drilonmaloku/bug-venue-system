<?php namespace App\Modules\Files\Models;


use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Model;

class AppFile extends Model
{

    protected $table = 'files';
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);

        static::creating(function ($appFile) {
            if (is_null($appFile->location_id)) {
                $appFile->location_id = auth()->user()->getCurrentLocationId();
            }
        });
    }
}

