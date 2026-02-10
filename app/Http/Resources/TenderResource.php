<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TenderResource extends JsonResource
{
    public function toArray($request): array
    {
        $rawStatus = $this->status?->value ?? $this->status;
        $tenderType = $this->tender_type?->value ?? $this->tender_type;

        // ✅ Status based on closing datetime
        if ($rawStatus === 'Active' && $this->closing_date && $this->closing_date->isFuture()) {
            $status = 'Open';
        } else {
            $status = 'Closed';
        }

        return [
            'uuid'           => $this->uuid,
            'tender_number'  => $this->tender_number,
            'title'          => $this->title,
            'description'    => $this->description,

            // normalized values
            'status'         => $status,
            'tender_type'    => $tenderType,

            // ✅ datetime-aware fields
            'date_of_advertisement_formatted' => $this->date_of_advertisement
                ? $this->date_of_advertisement->toDateTimeString()
                : null,

            'closing_date_formatted' => $this->closing_date
                ? $this->closing_date->toDateTimeString()
                : null,

            // optional: formatted dates for UI
            'date_of_advertisement' => $this->date_of_advertisement
                ? $this->date_of_advertisement->format('d/m/Y H:i A')
                : null,

            'closing_date' => $this->closing_date
                ? $this->closing_date->format('d/m/Y H:i A')
                : null,

            // company
            'company' => $this->whenLoaded('company', function () {
                return [
                    'title' => $this->company->title ?? null,
                ];
            }),

            // main media
            'media' => $this->whenLoaded('media', function () {
                return new MediaResource($this->media);
            }),

            // bidding document
            'bidding_document' => $this->whenLoaded('biddingDocumentMedia', function () {
                return new MediaResource($this->biddingDocumentMedia);
            }),
 
        ];
    }
}
