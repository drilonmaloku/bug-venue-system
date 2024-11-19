<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Services;

use App\Modules\Reservations\Models\ReservationCollaborator;
use App\Modules\Users\Services\UsersService;

class ReservationCollaboratorServices

{
    private $usersService;

    public function __construct()
    {
        
        $this->usersService = app()->make(UsersService::class);

    }

    public function getAll()
    {
        return ReservationCollaborator::all();
    }

      /**
     * Get Comment by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return ReservationCollaborator::find($id);
    }


  /**
     * Get Comment by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return ReservationCollaborator::whereIn('id', $ids)->get();
    }

    public function addCollaborator($reservation, $request) 
    {
  $collaborator = ReservationCollaborator::create([
    "collaborator_id" => $request->input('collaborator_id'),
    "reservation_id" => $reservation,
  ]);
    
    return $collaborator;
}


public function deleteCollaborator(ReservationCollaborator $collaborator)
{
    $collaboratorDeleted = $collaborator->delete();

    return $collaborator;
}
}
