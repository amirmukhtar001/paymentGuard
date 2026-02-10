<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\CommonMethods;

class DepartmentResource extends JsonResource
{
    use CommonMethods;
    public function toArray(Request $request): array
    {
        $media = $this->whenLoaded('media');
        $cover = $this->whenLoaded('coverMedia');
        $profileUrl = $this->getMediaUrl($media);
        $coverUrl = $this->getMediaUrl($cover);
        return [
            'uuid'            => $this->uuid,
            'name'            => $this->name,
            'department_code' => $this->department_code,
            'description'     => $this->description,
            'department_type' => $this->department_type ?: 'other',
            'status'          => $this->status,
            'has_website'     => $this->has_website,
            'external_url'    => $this->external_url,
            'prefix'          => $this->prefix,
            'department_logo'      => $profileUrl,
            'image_extension' => $media?->extension,
            'image_kind' => $media?->kind,
            'department_cover_image' => $coverUrl,
            'cover_image_extension' => $cover?->extension,
            'cover_image_kind' => $cover?->kind,
            // 'parent_id'       => $this->parent_id,
            // 'division_id'     => $this->division_id,
            // 'district_id'     => $this->district_id,
            // 'tehsil_id'       => $this->tehsil_id,

            // Relationships (only if loaded)
            'parent'   => $this->whenLoaded('parent', fn() => [
                'id' => $this->parent?->id,
                'name' => $this->parent?->name,
                'department_type' => $this->parent?->department_type ?: 'other',
            ]),
            'children' => $this->whenLoaded(
                'children',
                fn() =>
                DepartmentChildResource::collection($this->children)
            )

            // 'division' => $this->whenLoaded('division', fn() => [
            //     'id' => $this->division?->id,
            //     'name' => $this->division?->name ?? $this->division?->title,
            // ]),

            // 'district' => $this->whenLoaded('district', fn() => [
            //     'id' => $this->district?->id,
            //     'name' => $this->district?->name ?? $this->district?->title,
            // ]),

            // 'tehsil' => $this->whenLoaded('tehsil', fn() => [
            //     'id' => $this->tehsil?->id,
            //     'name' => $this->tehsil?->name ?? $this->tehsil?->title,
            // ]),

            // 'media' => $this->whenLoaded('media', fn() => [
            //     'id' => $media?->id,
            //     'url' => $media?->file_path ? asset('storage/' . $media->file_path) : null,
            //     'extension' => $media?->extension,
            //     'kind' => $media?->kind,
            // ]),

            // 'cover_media' => $this->whenLoaded('coverMedia', fn() => [
            //     'id' => $cover?->id,
            //     'url' => $cover?->file_path ? asset('storage/' . $cover->file_path) : null,
            //     'extension' => $cover?->extension,
            //     'kind' => $cover?->kind,
            // ])
        ];
    }
}
