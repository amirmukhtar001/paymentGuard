<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\{
    CaderEnum,
    BPSEnum,
    DesignationEnum,
    DisplayOnHomeEnum
};

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add policy check if required
    }

    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'cader'                 => ['nullable', new Enum(CaderEnum::class)],
            'bps'                   => ['nullable', new Enum(BPSEnum::class)],
            'designation'           => ['nullable'],
            'department_id'         => 'nullable|exists:departments,id',
            'company_id'            => 'nullable|exists:companies,id',
            'position_type_id'      => 'required|exists:position_types,id',
            'working_since'         => 'nullable|date',
            'worked_till'           => 'nullable|date|after_or_equal:working_since',
            'sort_order'            => 'nullable|integer',
            'display_on_home'       => ['required', new Enum(DisplayOnHomeEnum::class)],
            'message'               => 'nullable|string|max:10000',
            'picture_media_id'      => 'nullable|exists:media,uuid',
            'notification_media_id' => 'nullable|exists:media,uuid',
        ];
    }
}
