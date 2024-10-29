<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Services;

use App\Modules\Reservations\Models\ReservationStaff;
use  App\Modules\Reservations\Notifications\StaffDeletedNotification;
use  App\Modules\Reservations\Notifications\StaffAddedNotification;
use Illuminate\Support\Facades\Notification;
use App\Modules\Users\Services\UsersService;

class ReservationStaffServices

{
    private $usersService;

    public function __construct()
    {
        
        $this->usersService = app()->make(UsersService::class);

    }

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

  public function addMember($reservation, $request) 
{
    $staff = ReservationStaff::create([
        "user_id" => $request->input('user_id'),
        "reservation_id" => $reservation,
    ]);

    if ($staff) {
        Notification::send(
            $this->usersService->getUsersForNotifications(),
            new StaffAddedNotification(
                $staff,
                auth()->user()
            )
        );
    }

    return $staff;
}


public function deleteStaff(ReservationStaff $staff)
{
    $staffDeleted = $staff->delete();

    if ($staffDeleted) {
        Notification::send(
            $this->usersService->getUsersForNotifications(),
            new StaffDeletedNotification(
                $staff,   
                auth()->user()
            )
        );
    }

    return $staff;
}
}
