<?php

namespace App\Http\Requests\FAQ;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFAQRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'question' => ['required', 'string', 'max:500'],
            'slug' => ['nullable', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'publish', 'pending'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'meta_key' => ['nullable', 'string', 'max:255'],
            'meta_value' => ['nullable', 'string'],
        ];
    }
}
