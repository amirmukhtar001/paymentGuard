<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryCategoryGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'category_id' => $this->category_id,
            'category' => $this->category ? [
                'id'   => $this->category->id,
                'name' => $this->category->name ?? $this->category->title,
                'type' => $this->category->type ?? null,
            ] : [
                'id' => 0,
                'name' => 'Uncategorized',
                'type' => null,
            ],
            'galleries' => GalleryResource::collection($this->galleries),
        ];
    }
}
