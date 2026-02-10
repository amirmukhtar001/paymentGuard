<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => [
                'required',
                Rule::in(['image', 'video', 'mixed']),
            ],
            'status' => [
                'required',
                Rule::in(['draft', 'published', 'archived']),
            ],
            'media_id' => ['nullable', 'string', 'exists:media,uuid'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'company_id' => 'website',
            'media_id' => 'media file',
        ];
    }
}
