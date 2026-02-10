<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderSlideResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'uuid'            => $this->uuid,
            'title'           => $this->title,
            'caption'         => $this->caption,
            'button_text'     => $this->button_text,
            'button_url'      => $this->button_url,
            'is_active'       => (bool) $this->is_active,
            'sort_order'      => (int) $this->sort_order,
            'schedule_start'  => $this->schedule_start,
            'schedule_end'    => $this->schedule_end,
            'overlay_opacity' => $this->overlay_opacity,
            'meta'            => $this->meta, // json column? you can json_decode if needed

            // basic media info (adjust fields to your table)
            'media' => $this->whenLoaded('media', function () {
                return [
                    'id'  => $this->media->id,
                    // change this to getUrl() / path / whatever you use
                    'url' => $this->media->file_path ?? null,
                ];
            }),
        ];
    }
}
