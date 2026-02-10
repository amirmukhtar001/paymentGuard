<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // If media_url column not selected, it may be null/undefined
        $type = $this->type ?? null;

        // media_url output:
        // - if mixed: take DB media_url (if selected)
        // - else: build from relation (if loaded)
        $finalMediaUrl = null;

        if ($type === 'mixed') {
            $finalMediaUrl = $this->media_url ?? null;
        } else {
            $finalMediaUrl = $this->media?->file_path
                ? asset('storage/' . $this->media->file_path)
                : null;
        }

        return [
            'id'          => $this->when(isset($this->id), $this->id),
            'uuid'        => $this->when(isset($this->uuid), $this->uuid),
            'company_id'  => $this->when(isset($this->company_id), $this->company_id),
            'category_id' => $this->when(isset($this->category_id), (int)($this->category_id ?? 0)),

            'name'        => $this->when(isset($this->name), $this->name),
            'slug'        => $this->when(isset($this->slug), $this->slug),
            'description' => $this->when(isset($this->description), $this->description),

            'type'        => $this->when(isset($this->type), $this->type),
            'status'      => $this->when(isset($this->status), $this->status),
            'is_featured' => $this->when(isset($this->is_featured), $this->is_featured),

            'media_url'   => $finalMediaUrl,

            'published_at' => $this->when(isset($this->published_at) && $this->published_at, fn() => $this->published_at->toISOString()),
            'created_at'  => $this->when(isset($this->created_at) && $this->created_at, fn() => $this->created_at->toISOString()),
            'updated_at'  => $this->when(isset($this->updated_at) && $this->updated_at, fn() => $this->updated_at->toISOString()),

            'category' => $this->whenLoaded('category', function () {
                return [ 
                    'name' => $this->category->title ?? $this->category->title,
                    'type' => $this->category->type->title ?? null,
                ];
            }),
        ];
    }
}
