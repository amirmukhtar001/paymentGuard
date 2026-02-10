<?php

namespace App\Http\Requests\CabinetMember;

use Illuminate\Foundation\Http\FormRequest;

class CabinetMemberPositionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'position_type_id'   => ['required', 'integer', 'exists:position_types,id'],
            'department_id'      => ['nullable', 'integer', 'exists:departments,id'],
            'party_id'           => ['nullable', 'integer', 'exists:parties,id'],
            'halqa_id'           => ['nullable', 'integer', 'exists:halqas,id'],
            'working_from_date'  => ['required', 'date'],
            'working_till_date'  => ['nullable', 'date', 'after_or_equal:working_from_date'],
            'position_media_id'  => ['nullable', 'string'], // uuid
        ];
    }
}
