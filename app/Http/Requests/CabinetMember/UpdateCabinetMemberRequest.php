<?php

namespace App\Http\Requests\CabinetMember;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCabinetMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // hook into auth/policies if needed
    }

    public function rules(): array
    {
        return [
            // Member fields
            'name'              => 'required|string|max:255',
            'dob'               => 'nullable|date',
            'contact_no'        => 'nullable|string|max:30',
            'office_no'         => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:255',
            'facebook_page'     => 'nullable|url|max:255',
            'twitter_page'      => 'nullable|url|max:255',
            'company_id'      => 'nullable',
            'message'           => 'nullable|string|max:10000',  

            // Current position fields
            'position_type_id'  => 'required|exists:position_types,id',
            'department_id'     => 'nullable|exists:departments,id',
            'party_id'          => 'nullable|exists:parties,id',
            'halqa_id'          => 'nullable|exists:halqas,id',
            'working_from_date' => 'required|date',
            'working_till_date' => 'nullable|date|after_or_equal:working_from_date'
        ];
    }
}
