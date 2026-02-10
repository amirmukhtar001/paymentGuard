<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Traits\CommonMethods;
use Illuminate\Support\Carbon;

class CabinetMemberCollectionGalleryResource extends ResourceCollection
{
    use CommonMethods;

    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($member) {

                $profileUrl = $this->getMediaUrl($member->media);
                $notificationDocument = $this->getMediaUrl(
                    $member->currentPosition?->media
                );

                $partyMedia   = $this->getMediaUrl($member->currentPosition->party?->media);

                $fullPositions = $member->positions->sortByDesc('working_from_date')->values()->map(function ($pos) {

                    $media = $pos->media;
                    $mediaExt = strtolower($media?->extension ?? '');

                    return [
                        'department' => $pos->department?->name,

                        'working_from_date' => $pos->working_from_date
                            ? $pos->working_from_date->format('j F Y')
                            : null,

                        'working_till_date' => $pos->working_till_date
                            ? $pos->working_till_date->format('j F Y')
                            : null,
                        'working_duration' => $this->getDuration(
                            $pos->working_from_date,
                            $pos->working_till_date
                        ),


                        'position_type' => $pos->positionType?->name,
                        'party'         => $pos->party?->full_name,
                        'halqa'         => $pos->halqa?->code,

                        'notification' => [
                            'url'       => $media && $media->file_path
                                ? asset('storage/' . $media->file_path)
                                : null,
                            'title'     => $media?->title,
                            'type'      => $mediaExt
                                ? (in_array($mediaExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])
                                    ? 'image'
                                    : 'file')
                                : null,
                            'extension' => $mediaExt ?: null,
                        ],
                    ];
                });

                return [
                    'uuids'          => $member->uuid,
                    'name'           => $member->name,
                    'department'     => $member->currentPosition?->department?->name,
                    'position_type'  => $member->currentPosition?->positionType?->name,
                    'party'          => $member->currentPosition?->party?->full_name,

                    'party_symbol_path'     => $partyMedia,
                    'halqa'          => $member->currentPosition?->halqa?->code,
                    'image_path'     => $profileUrl,
                    'notification_document' => $notificationDocument,

                    'message'        => $member->message,
                    'dob'            => $member->dob,
                    'contact_no'     => $member->contact_no,
                    'office_no'      => $member->office_no,
                    'email'          => $member->email,
                    'facebook_page'  => $member->facebook_page,
                    'twitter_page'   => $member->twitter_page,
                    'member_type'    => $member->member_type,
                    'status'         => $member->status,

                    'working_from_date' =>
                    $member->currentPosition?->working_from_date?->format('j F Y'),

                    'working_till_date' =>
                    $member->currentPosition?->working_till_date?->format('j F Y'),
                    'working_duration' => $this->getDuration(
                        $member->currentPosition?->working_from_date,
                        $member->currentPosition?->working_till_date
                    ),


                    // Current departments (minimal)
                    'current_positions' => $member->positions
                        ->whereNull('working_till_date')
                        ->sortByDesc('working_from_date')
                        ->pluck('department.name')
                        ->filter()
                        ->unique()
                        ->values()
                        ->implode(', '),

                    // Full history
                    'history' => $fullPositions,
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
