<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class DocumentResource extends BaseResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            
            // Document Info
            'name' => $this->name,
            'filename' => $this->filename,
            'description' => $this->description,
            
            // Type
            'type' => $this->type,
            'mime_type' => $this->mime_type,
            
            // Size
            'size' => $this->size,
            'size_formatted' => $this->size ? $this->formatBytes($this->size) : null,
            
            // URL
            'url' => $this->url,
            
            // Relations
            'reservation_id' => $this->reservation_id,
            'uploaded_by' => $this->whenLoadedRelation('user', new UserResource($this->user)),
            
            // Timestamps
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            
            // Links
            'links' => [
                'download' => $this->url,
            ],
        ];
    }
    
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
