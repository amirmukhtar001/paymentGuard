<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'uuid'        => $this->uuid,
            'company_id'  => $this->company_id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'status'      => $this->status,
            'sort_order'  => (int) $this->sort_order,
            'transition'  => $this->transition,
            'autoplay_ms' => $this->autoplay_ms,

            'company' => $this->whenLoaded('company', function () {
                return [
                    'id'    => $this->company->id,
                    'name'  => $this->company->name ?? $this->company->title,
                    'title' => $this->company->title ?? null,
                ];
            }),

            // nested slides
            'slides' => SliderSlideResource::collection(
                $this->whenLoaded('slides')
            ),
        ];
    }
}
