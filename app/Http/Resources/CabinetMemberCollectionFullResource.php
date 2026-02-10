<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Traits\CommonMethods;
use Illuminate\Support\Carbon;

class CabinetMemberCollectionFullResource extends ResourceCollection
{
    use CommonMethods;

    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($member) {

                $profileUrl = $this->getMediaUrl($member->media);
                $notificationDocument = $this->getMediaUrl(
                    optional($member->currentPosition)->media
                );

                /**
                 * Build FULL position details once
                 */
                $fullPositions = $member->positions
                    ->sortByDesc('working_from_date')
                    ->values()
                    ->map(function ($pos) {

                        $media = $pos->media;
                        $mediaExt = strtolower($media->extension ?? '');
                        $mediaUrl = $media && $media->file_path
                            ? asset('storage/' . $media->file_path)
                            : null;

                        return [
                            'department' => optional($pos->department)->name,

                            'working_from_date' => $pos->working_from_date
                                ? Carbon::parse($pos->working_from_date)->format('d M Y')
                                : null,

                            'working_till_date' => $pos->working_till_date
                                ? Carbon::parse($pos->working_till_date)->format('d M Y')
                                : null,

                            'position_type' => optional($pos->positionType)->name,
                            'party'         => optional($pos->party)->name,
                            'halqa'         => optional($pos->halqa)->code,

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

                return [
                    'uuids'        => $member->uuid,
                    'name'         => $member->name,
                    'department'   => optional($member->currentPosition->department)->name,
                    'position_type' => optional($member->currentPosition->positionType)->name,
                    'party'        => optional($member->currentPosition->party)->name,
                    'halqa'        => optional($member->currentPosition->halqa)->code,
                    'image_path'   => $profileUrl,
                    'notification_document' => $notificationDocument,
                    'message'      => $member->message,
                    'dob'          => $member->dob,
                    'contact_no'   => $member->contact_no,
                    'office_no'    => $member->office_no,
                    'email'        => $member->email,
                    'facebook_page' => $member->facebook_page,
                    'twitter_page' => $member->twitter_page,
                    'member_type'  => $member->member_type,
                    'status'       => $member->status,

                    'working_from_date' => optional(
                        optional($member->currentPosition)->working_from_date
                    )?->format('d/m/Y'),

                    'working_till_date' => optional(
                        optional($member->currentPosition)->working_till_date
                    )?->format('d/m/Y'),

                    /**
                     * 🟡 MINIMAL: CURRENT POSITIONS
                     * (department + is_present)
                     */
                    'current_positions' => $member->positions
                        ->whereNull('working_till_date')
                        ->sortByDesc('working_from_date')
                        ->pluck('department.name')
                        ->filter()          // remove nulls
                        ->unique()          // avoid duplicates
                        ->values()
                        ->implode(', '),


                    /**
                     * 🔥 FULL DETAILS: PRESENT POSITIONS
                     */
                    'history' => $fullPositions->values(),
                ];
            }),

            'pagination' => [
                'total'        => $this->total(),
                'count'        => $this->count(),
                'per_page'     => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages'  => $this->lastPage(),
            ],
        ];
    }
}
