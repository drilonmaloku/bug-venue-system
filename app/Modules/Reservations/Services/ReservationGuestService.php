<?php namespace App\Modules\Reservations\Services;


use App\Modules\Reservations\Models\ReservationGuest;
use App\Modules\Users\Services\UsersService;
use App\Modules\Logs\Services\LogService;
use App\Modules\Clients\Services\ClientsService;




class ReservationGuestService
{
    private $logService;
    private $clientService;
    private $usersService;

    public function __construct()
    {
        $this->logService = new LogService();
        $this->clientService = new ClientsService();
        $this->usersService = app()->make(UsersService::class);

    }

    public function getByID($id){
        return ReservationGuest::find($id);
    }

    public function store($data, $reservation_id,$currentlocationId = null)
    {
        $locationId = $currentlocationId ? $currentlocationId : auth()->user()->getCurrentLocationId();
        $reservationGuest = ReservationGuest::create([
            "location_id" => $locationId,
            "reservation_id" => $reservation_id,
            "name" => data_get($data, "name"),
            "email" => data_get($data, "email"),
            "table_number" => data_get($data, "table_number"),
            "phone_number" => data_get($data, "phone_number"),
            "guest_count" => data_get($data, "guest_count"),
            "status" => 1,
        ]);

        return $reservationGuest;
    }

    public function update($guest, $data)
    {
        return $guest->update([
            'name' => $data['name'],
            'table_number' => $data['table_number'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'guest_count' => $data['guest_count'],
        ]);
    }

    public function delete(ReservationGuest $reservationGuest)
    {
        $previousData = $reservationGuest->attributesToArray();
        $reservationGuestDeleted = $reservationGuest->delete();

        return $reservationGuestDeleted;
    }

    public function updateStatus($request, ReservationGuest $reservationGuest)
    {
        $reservationGuest->status = $request->input('status');
        $reservationGuestSaved = $reservationGuest->save();
        return $reservationGuestSaved;
    }

    public function updateCheckInStatus($request, ReservationGuest $reservationGuest)
    {
        $reservationGuest->is_checked_in = $request->input('check_in_status');
        $reservationGuestSaved = $reservationGuest->save();
        return $reservationGuestSaved;
    }
}
