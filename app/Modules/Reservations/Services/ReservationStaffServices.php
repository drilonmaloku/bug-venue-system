<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Services;

use App\Modules\Reservations\Models\ReservationStaff;

class ReservationStaffServices
{

    public function getAll()
    {
        return ReservationStaff::all();
    }

      /**
     * Get Comment by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return ReservationStaff::find($id);
    }


  /**
     * Get Comment by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return ReservationStaff::whereIn('id', $ids)->get();
    }


   public function deleteStaff(ReservationStaff $staff) {
       return $staff->delete();
   }
}
