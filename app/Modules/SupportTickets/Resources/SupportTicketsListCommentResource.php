<?php

namespace App\Modules\SupportTickets\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketsListCommentResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id"                            => $this->id,
            "comment"                       => $this->comment,
            "ticket_id"                    => $this->ticket_id,
            "user_id"                       => $this->user->id,
            "created_at"                    => $this->created_at->format('d/m/Y H:i'),
            "updated_at"                    => $this->updated_at->format('d/m/Y H:i'),
        ];
    }

}
