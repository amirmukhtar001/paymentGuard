<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\CommonMethods;
use Illuminate\Support\Carbon;

class EmployeeFullResource extends JsonResource
{
    use CommonMethods;

    public function toArray(Request $request): array
    {
        // ================= BASE RELATIONS =================
        $company      = $this->whenLoaded('company');
        $department   = $this->whenLoaded('department');
        $positionType = $this->whenLoaded('positionType');
        $designationModel = $this->relationLoaded('designation') ? $this->getRelation('designation') : null;


        $pictureMedia = $this->whenLoaded('media');
        $notification = $this->whenLoaded('notificationMedia');

        // ================= MEDIA =================
        $pictureUrl = ($pictureMedia)
            ? $this->getMediaUrl($pictureMedia)
            : null;

        $notificationUrl = ($notification)
            ? $this->getMediaUrl($notification)
            : null;

        // ================= ENUM SAFE VALUES =================
        $caderValue = is_object($this->cader)
            ? ($this->cader->value ?? null)
            : $this->cader;

        $bpsValue = is_object($this->bps)
            ? ($this->bps->value ?? null)
            : $this->bps;

        $displayOnHomeValue = is_object($this->display_on_home)
            ? ($this->display_on_home->value ?? null)
            : $this->display_on_home;

        // ================= FULL HISTORY (READABLE) =================
        $history = $this->whenLoaded('histories', function () {
            return $this->histories->map(function ($h) {

                $media = $h->notificationMedia;
                $mediaExt = strtolower($media->extension ?? '');
                $mediaUrl = ($media && $media->file_path)
                    ? asset('storage/' . $media->file_path)
                    : null;

                return [
                    'working_from_date' => $h->working_from
                        ? $h->working_from->format('d M Y')
                        : null,

                    'working_till_date' => $h->working_till
                        ? $h->working_till->format('d M Y')
                        : 'Present',

                    // 🔹 RELATION DATA
                    'department'        => optional($h->department)->name,
                    'position_type'     => optional($h->positionType)->title
                        ?? optional($h->positionType)->name,
                    'designation_title' => optional($h->serviceDesignation)->title,

                    // 🔒 KEEP RAW VALUES (BACKWARD SAFE) 
                    'designation'      => $h->designation,

                    'cader' => is_object($h->cader)
                        ? ($h->cader->value ?? null)
                        : $h->cader,

                    'bps' => is_object($h->bps)
                        ? ($h->bps->value ?? null)
                        : $h->bps,

                    // 🔔 NOTIFICATION MEDIA (RELATION)
                    'notification' => [
                        'url'   => $mediaUrl,
                        'title' => $media->title ?? null,
                        'type'  => $mediaExt
                            ? (in_array(
                                $mediaExt,
                                ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']
                            ) ? 'image' : 'file')
                            : null,
                        'extension' => $mediaExt ?: null,
                    ],
                ];
            });
        });

        return [
            // ================= BASIC INFO =================
      
            'name' => $this->name,

            'cader' => $caderValue,
            'bps'   => $bpsValue, 

            // 🔹 DESIGNATION
            'designation' => $designationModel?->title, 
            'message' => $this->message,
            // 🔹 POSITION TYPE 
            'position_type'    => $positionType?->title ?? $positionType?->name,
            // ================= DATES =================
            'working_from_date' => $this->working_since
                ? $this->working_since->format('d/m/Y')
                : null,

            'working_till_date' => $this->worked_till
                ? $this->worked_till->format('d/m/Y')
                : null,

            // ================= UI META =================
            'sort_order'      => (int) $this->sort_order, 
            // ================= MEDIA =================
            'image_path' => $pictureUrl,
            'notification' => $notificationUrl,

            // ================= CURRENT SNAPSHOT =================
            'department'    => $department?->name,      
 

            // ================= DERIVED =================
            /**
             * 🟡 Current departments (comma-separated)
             */
            'current_departments' => $this->whenLoaded('histories', function () {
                return $this->histories
                    ->whereNull('working_till')
                    ->pluck('department.name')
                    ->filter()
                    ->unique()
                    ->implode(', ');
            }),

            /**
             * 🔥 Readable history (frontend timeline)
             */
            'history' => $history,
        ];
    }
}
