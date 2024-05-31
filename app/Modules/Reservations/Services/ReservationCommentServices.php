<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Services;

use App\Modules\Reservations\Models\ReservationComment;

class ReservationCommentServices
{

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
    public function storeComment($request,$reservation)
    {
        return (new ReservationComment())->create([
            "comment" => data_get($request, "comment"),
            "reservation_id" => $reservation->id,
            "user_id" => auth()->user()->id,
        ]);
    }

   public function deleteComment(ReservationComment $comment) {
       return $comment->delete();
   }
}
