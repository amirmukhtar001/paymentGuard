<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $media = $this->whenLoaded('media');
        $documentUrl = null;

        $category = $this->whenLoaded('category');
        $department = $this->whenLoaded('department');
        $company = $this->whenLoaded('company');

        if ($media && !empty($media->file_path)) {
            $documentUrl = asset('storage/' . $media->file_path);
        }

        // enum-safe status
        $status = is_object($this->status) ? ($this->status->value ?? null) : $this->status;

        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'category_id' => $this->category_id,

            'promulgation_date' => optional($this->promulgation_date)->format('d/m/Y'),
            'description' => $this->description,
            'status' => $status,

            'sort_order' => (int) ($this->sort_order ?? 0),

            'category_name' => $category ? $category->title : null,
            'department_name' => $department ? ($department->name ?? $department->title) : null,
            'site' => $company ? $company->title : null,

            // keep same naming you used
            'image_path' => $media ? $documentUrl : null,
            'extension'  => $media?->extension,
            'kind'       => $media?->kind,

            'created_at' => optional($this->created_at)->format('d/m/Y'),
            'updated_at' => optional($this->updated_at)->format('d/m/Y'),
        ];
    }
}
