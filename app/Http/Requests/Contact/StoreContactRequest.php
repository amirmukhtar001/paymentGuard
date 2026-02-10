<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'designation_id' => ['nullable', 'integer', 'exists:designations,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'ext_no' => ['nullable', 'string', 'max:10'],
            'fax_number' => ['nullable', 'string', 'max:50'],
            'email_address' => ['nullable', 'string', 'max:255', 'email'],
            'office_address' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
            'is_primary' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => ['nullable', 'integer'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_primary' => $this->boolean('is_primary'),
            'sort_order' => $this->input('sort_order', 0),
        ]);
    }
}
