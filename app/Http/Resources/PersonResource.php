<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\CommonMethods;

class PersonResource extends JsonResource
{
    use CommonMethods;
    public function toArray(Request $request): array
    {
        $profileUrl = $this->getMediaUrl($this->media);

        // If controller loaded a single company (filtered), it will be at companies[0]
        $companyRow = $this->companies->first() ?? null;
        $category = $this->whenLoaded('category');
        return [
            'uuid'        => $this->uuid,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'body'      => $this->description,
            'person_type' => $this->person_type,

            'short_bio'   => $this->short_bio,
            'biography'   => $this->biography,

            'birth_date'  => optional($this->birth_date)->format('d/m/Y'),
            'death_date'  => optional($this->death_date)->format('d/m/Y'),
            'image_path' => $profileUrl,

            // Base fields (when no company filter)
            'status'      => $this->status ?? null,
            'is_featured' => $this->is_featured ?? null,

            // Optional nested
            'category' => $category->name ?? $category->title ?? null,

        ];
    }
}
