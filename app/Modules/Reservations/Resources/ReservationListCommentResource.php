<?php

namespace App\Modules\Reservations\Resources ;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationListCommentResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id"                            => $this->id,
            "comment"                       => $this->comment,
            "reservation_id"                => $this->reservation_id,
            "user_id"                       => $this->user->id,
            "created_at"                    => $this->created_at->format('d/m/Y H:i'),
            "updated_at"                    => $this->updated_at->format('d/m/Y H:i'),
        ];
    }

}
