<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [ 
            'file_path'  => $this->file_path ? asset('storage/' . $this->file_path) : null,
            'file_name'  => $this->file_name,
            'mime_type'  => $this->mime_type,
            'extension'  => $this->extension,
            'kind'       => $this->kind
        ];
    }
}
