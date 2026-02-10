<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteMenuItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'label'  => $this->label,
            'url'    => $this->url,
            'target' => $this->target,
            'status' => $this->status,
            'children' => WebsiteMenuItemResource::collection(
                $this->children
                    ->where('status', 'active')
                    ->sortBy('sort_order')
            ),
        ];
    }
}
