<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\CommonMethods;
use Illuminate\Support\Carbon;

class CabinetMemberResource extends JsonResource
{
    use CommonMethods;

    public function toArray($request)
    {
        $imageUrl = $this->media ? $this->getMediaUrl($this->media) : null;

        /**
         * Build FULL position history
         */
        $history = $this->positions->sortByDesc('working_from_date')->values() ->map(function ($pos) {

                $media = $pos->media;
                $mediaExt = strtolower($media->extension ?? '');
                $mediaUrl = $media && $media->file_path ? asset('storage/' . $media->file_path) : null;

                return [
                    'department' => optional($pos->department)->name,
                    'working_from_date' => $pos->working_from_date
                        ? Carbon::parse($pos->working_from_date)->format('d M Y') : null,

                    'working_till_date' => $pos->working_till_date ? Carbon::parse($pos->working_till_date)->format('d M Y')  : 'Present',

                    'position_type' => optional($pos->positionType)->name,
                    'party'         => optional($pos->party)->name,
                    'halqa'         => optional($pos->halqa)->code,
                    'notification' => [
                        'url'   => $mediaUrl,
                        'title' => $media->title ?? null,
                        'type'  => $mediaExt ? (in_array( $mediaExt,['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']
                            ) ? 'image' : 'file') : null,
                        'extension' => $mediaExt ?: null,
                    ],
                ];
            });

        return [
            'uuid'         => $this->uuid,
            'name'         => $this->name,
            'media_id'     => $this->media_id,
            'dob'          => $this->dob,
            'contact_no'   => $this->contact_no,
            'office_no'    => $this->office_no,
            'email'        => $this->email,
            'facebook_page' => $this->facebook_page,
            'twitter_page' => $this->twitter_page,
            'member_type'  => $this->member_type,
            'status'       => $this->status,

            'image_path' => $imageUrl,

            // 🔹 CURRENT POSITION SNAPSHOT
            'position_type' => optional($this->currentPosition->positionType)->name,
            'department'    => optional($this->currentPosition->department)->name,
            'party'         => optional($this->currentPosition->party)->name,
            'halqa'         => optional($this->currentPosition->halqa)->code,

            'working_from_date' => optional(
                optional($this->currentPosition)->working_from_date
            )?->format('d/m/Y'),

            'working_till_date' => optional($this->currentPosition)->working_till_date
                ? $this->currentPosition->working_till_date->format('d/m/Y')
                : 'Present',

            'notification_media' => optional($this->currentPosition)
                ? $this->getMediaUrl($this->currentPosition->media)
                : null,

            /**
             * 🟡 CURRENT POSITIONS (comma-separated departments)
             */
            'current_positions' => $this->positions
            ->whereNull('working_till_date')->sortByDesc('working_from_date')->pluck('department.name')->filter()->unique()->implode(', '),

            /**
             * 🔥 FULL POSITION HISTORY
             */
            'history' => $history,
        ];
    }
}
