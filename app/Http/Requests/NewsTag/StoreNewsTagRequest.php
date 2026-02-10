<?php

namespace App\Http\Requests\NewsTag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewsTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // adjust if you use policies
    }

    public function rules(): array
    {
        return [
            'company_id' => [
                'required',
                'integer',
                'exists:companies,id',
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'slug' => [
                'nullable', // or 'sometimes'
                'string',
                'max:100',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'company_id' => 'company',
        ];
    }
}
