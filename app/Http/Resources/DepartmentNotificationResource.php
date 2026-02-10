<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
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

            'notification_date' => optional($this->notification_date)->format('d/m/Y'),
            'order_number' => (int) ($this->order_number ?? 0),
            'status' => $status,

            'category_name' => $category ? $category->title : null,
            'department_name' => $department ? $department->title : null,

            'company_name' => $company ? $company->title : null,

            'image_path' => $media ? $documentUrl : null,
            'extension' => $media?->extension,    // ✅ formatted dates
            'kind'      => $media?->kind,         // ✅ media kind/type
            'created_at' => optional($this->created_at)->format('d/m/Y'),
            'updated_at' => optional($this->updated_at)->format('d/m/Y'),
        ];
    }
}
