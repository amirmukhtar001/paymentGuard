<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'uuid'             => $this->uuid,
            'category_type_id' => $this->category_type_id,
            'parent_id'        => $this->parent_id,
            'title'            => $this->title,
            'slug'             => $this->slug,
            'description'      => $this->description,
            'status'           => $this->status,
            'sort_order'       => $this->sort_order,
            'created_at'       => $this->created_at?->toIso8601String(),
            'updated_at'       => $this->updated_at?->toIso8601String(),

            'parent' => $this->whenLoaded('parent', function () {
                return [
                    'uuid'  => $this->parent->uuid,
                    'id'    => $this->parent->id,
                    'title' => $this->parent->title,
                ];
            }),
            'type' => $this->whenLoaded('type', function () {
                return [
                    'id'   => $this->type->id,
                    'name' => $this->type->name ?? null,
                ];
            }),
        ];
    }
}
