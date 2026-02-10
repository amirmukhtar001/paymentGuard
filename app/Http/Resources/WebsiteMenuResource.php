<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteMenuResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid'  => $this->uuid,
            'title' => $this->title,
            'slug'  => $this->slug,
            'status' => $this->status,
            'menu_type' => optional($this->menuType)->slug, // "header", "footer", etc.
            'items' => WebsiteMenuItemResource::collection(
                $this->items->whereNull('parent_id')->where('status', 'active')->sortBy('sort_order')
            ),
        ];
    }
}
