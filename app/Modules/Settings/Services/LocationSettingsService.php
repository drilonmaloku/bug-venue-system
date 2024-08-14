<?php namespace App\Modules\Settings\Services;

use App\Modules\Clients\Models\Client;
use App\Modules\Settings\Models\LocationSettings;
use App\Modules\Logs\Models\Log;

class LocationSettingsService
{


    /**
     * Get Location Settings by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return LocationSettings::find($id);
    }

}
