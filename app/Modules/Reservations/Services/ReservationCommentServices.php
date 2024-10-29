<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Services;
use App\Modules\Users\Services\UsersService;
use App\Modules\Logs\Services\LogService;
use App\Modules\Clients\Models\Client;
use App\Modules\Clients\Services\ClientsService;
use Illuminate\Support\Facades\Notification;
use App\Modules\Logs\Models\Log;
use App\Modules\Reservations\Notifications\CommentAddedNotification;
use App\Modules\Reservations\Notifications\CommentDeletedNotifiaction;
use App\Modules\Reservations\Models\ReservationComment;

class ReservationCommentServices
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


    public function getAll()
    {
        return ReservationComment::all();
    }

      /**
     * Get Comment by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return ReservationComment::find($id);
    }


  /**
     * Get Comment by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return ReservationComment::whereIn('id', $ids)->get();
    }

      /**
     * Stores new Reservation Comment
     **/
    public function storeComment($request, $reservation)
{
    $reservationComment = (new ReservationComment())->create([
        "location_id" => auth()->user()->getCurrentLocationId(),
        "comment" => data_get($request, "comment"),
        "reservation_id" => $reservation->id,
        "user_id" => auth()->user()->id,
    ]);

    Notification::send(
        $this->usersService->getUsersForNotifications(),
        new CommentAddedNotification(
            $reservationComment, 
            auth()->user()
        )
    );

    return $reservationComment;
}


   public function deleteComment(ReservationComment $reservationComment) {
       
       $reservationCommentDeleted = $reservationComment->delete();
        if($reservationCommentDeleted){
                Notification::send(
                $this->usersService->getUsersForNotifications(),
                new CommentDeletedNotifiaction(
                    $reservationComment,
                    auth()->user()
                ));
        } 
       
    
   }
   
}
