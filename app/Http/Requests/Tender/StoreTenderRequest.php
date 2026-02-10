<?php

namespace App\Http\Requests\Tender;

use App\Enums\StatuseEnum;
use App\Enums\TenderTypesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTenderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // plug into policies if needed
    }

    public function rules(): array
    {
        return [
            'tender_number'           => 'nullable|string|max:255|unique:tenders,tender_number',
            'title'                   => 'required|string|max:255',
            'description'             => 'nullable|string',

            // enums (nullable = DB default can be used if not sent)
            'status'                  => ['nullable', new Enum(StatuseEnum::class)],
            'tender_type'             => ['required', new Enum(TenderTypesEnum::class)],

            'date_of_advertisement'   => 'nullable|date',
            'closing_date'            => 'nullable|date|after_or_equal:date_of_advertisement',

            'company_id'              => 'nullable|exists:companies,id',
            'department_id'           => 'required|exists:departments,id',


            // Media UUIDs (frontend sends UUID, you resolve to id via MediaService)

            'media_id'                  => 'nullable|exists:media,id',
            'bidding_document_media_id' => 'nullable|exists:media,id',
        ];
    }
}
