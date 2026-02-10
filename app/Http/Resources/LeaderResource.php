<?php

namespace App\Http\Resources;

use App\Models\Web\CabinetMember;
use App\Models\Web\Employee;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderResource extends JsonResource
{
    public function toArray($request): array
    {
        $leader = $this->leaderable; // may be null

        $party         = null;
        $positionType  = null;
        $route         = null;
        $department    = $this->department ?: null;
        $designation   = null;
        $message       = null;
        $previewUrl    = null;

        if ($leader instanceof CabinetMember) {
            $party        = optional($leader->currentPosition?->party)->short_name;
            $positionType = optional($leader->currentPosition?->positionType)->name;
            $department   ??= optional($leader->currentPosition?->department)->name;
            $route        = 'cabinet-member';
            $message      = $leader->message;
        } elseif ($leader instanceof Employee) {
            $positionType = optional($leader->positionType)->name;
            $department   ??= optional($leader->department)->name;
            $route        = 'secretaries';
            $message      = $leader->message;

            if ($leader->relationLoaded('designation')) {
                $designation = optional($leader->designation)->title;
            }
        }

        // Media (shared behavior)
        if ($leader?->relationLoaded('media') && $leader->media?->file_path) {
            $previewUrl = asset('storage/' . $leader->media->file_path);
        }

        return [
            'party'       => $party,
            'position'    => $positionType,
            'designation' => $designation,
            'department'  => $department,
            'sort_order'  => (int) $this->sort_order,
            'is_active'   => (bool) $this->is_active,
            'image_path'  => $previewUrl,
            'message'     => $message,

            // flattened leader fields
            'name'  => $this->whenLoaded('leaderable', fn() => $leader?->name),
            'email' => $this->whenLoaded('leaderable', fn() => $leader?->email),
            'route' => $route,

            'additional_data' => $this->additional_data
                ? json_decode($this->additional_data, true)
                : null,
        ];
    }
}
