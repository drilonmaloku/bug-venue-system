<?php namespace App\Modules\Settings\Models;



use App\Modules\Location\Models\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationSettings extends Model
{
    use HasFactory;

    protected $guarded =[];
    protected $table = 'locations_settings';


    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Function to get the decoded contract content
    public function getContractAttribute()
    {
        return json_decode($this->settings,true)['contract'];
    }
}
