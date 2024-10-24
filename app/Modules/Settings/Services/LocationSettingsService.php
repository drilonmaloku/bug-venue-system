<?php namespace App\Modules\Settings\Services;

use App\Modules\Settings\Models\LocationSettings;


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
