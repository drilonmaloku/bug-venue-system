<?php

declare(strict_types=1);

namespace App\Modules\SupportTickets\Services;

use App\Modules\SupportTickets\Models\SupportComment;

class SupportTicketsCommentServices
{

    public function getAll()
    {
        return SupportComment::all();
    }

      /**
     * Get Comment by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return SupportComment::find($id);
    }


  /**
     * Get Comment by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return SupportComment::whereIn('id', $ids)->get();
    }

      /**
     * Stores new Reservation Comment
     **/
    public function storeComment($request,$ticket)
    {
        return (new SupportComment())->create([
            "comment" => data_get($request, "comment"),
            "ticket_id" => $ticket->id,
            "user_id" => auth()->user()->id,
            "attachment" => data_get($request, "attachment"),

        ]);
    }

   public function deleteComment(SupportComment $comment) {
       return $comment->delete();
   }
}
