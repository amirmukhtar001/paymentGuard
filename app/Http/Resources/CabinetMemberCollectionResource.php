<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CabinetMemberCollectionResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($member) {
                return [
                    'uuid' => $member->uuid,
                    'name' => $member->name,
                    'department' => optional($member->currentPosition->department)->name,
                    'position_type' => optional($member->currentPosition->positionType)->name,
                    'media_url' => optional($member->media)->file_path,
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
