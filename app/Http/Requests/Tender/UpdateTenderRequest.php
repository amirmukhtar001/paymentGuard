<?php

namespace App\Http\Requests\Tender;

use App\Enums\StatuseEnum;
use App\Enums\TenderTypesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTenderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // hook to policies/permissions if needed
    }

    public function rules(): array
    {
        // we assume route model binding param is {tender}
        $tender = $this->route('tender');

        $tenderId = is_object($tender) ? $tender->id : $tender;

        return [
            'tender_number'           => 'nullable|string|max:255|unique:tenders,tender_number,' . $tenderId,
            'title'                   => 'required|string|max:255',
            'description'             => 'nullable|string',

            'status'                  => ['nullable', new Enum(StatuseEnum::class)],
            'tender_type'             => ['required', new Enum(TenderTypesEnum::class)],

            'date_of_advertisement'   => 'nullable|date',
            'closing_date'            => 'nullable|date|after_or_equal:date_of_advertisement',

            'company_id'              => 'nullable|exists:companies,id',

            'media_id'                  => 'nullable|exists:media,uuid',
            'bidding_document_media_id' => 'nullable|exists:media,uuid',
        ];
    }
}
