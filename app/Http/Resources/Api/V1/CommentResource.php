<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class CommentResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Content
            'content' => $this->comment,
            'comment' => $this->comment,
            'type' => $this->type ?? 'general',
            
            // Author
            'user_id' => $this->user_id,
            'user' => $this->whenLoadedRelation('user', new UserResource($this->user)),
            
            // Relations
            'reservation_id' => $this->reservation_id,
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
        ];
    }
}
