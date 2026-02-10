<?php

namespace App\Http\Requests\Party;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePartyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'short_name' => ['required', 'string', 'max:255', 'unique:parties,short_name'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive', 'draft'])],
            'media_id' => ['nullable', 'exists:media,uuid'],
        ];
    }
}
