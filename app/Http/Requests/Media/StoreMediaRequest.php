<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],

            'collection_name' => ['nullable', 'string', 'max:125'],
            'title'           => ['nullable', 'string', 'max:125'],
            'description'     => ['nullable', 'string'],
            'alt_text'        => ['nullable', 'string', 'max:255'],

            'kind' => [
                'required',
                Rule::in(['image', 'video', 'audio', 'document', 'other']),
            ],
            'status' => [
                'required',
                Rule::in(['draft', 'active', 'archived']),
            ],

            'sort_order' => ['nullable', 'integer', 'min:0'],
            'checked'    => ['nullable', 'boolean'],

            'external_url' => ['nullable', 'url', 'max:500'],

            // optional meta JSON
            'meta' => ['nullable', 'array'],

            // file (optional – you can make it required if you want)
            'file' => [
                'nullable',
                'file',
                'max:51200', // 50 MB
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'company_id'   => 'company',
            'category_id'  => 'category',
            'collection_name' => 'collection name',
            'alt_text'     => 'alt text',
        ];
    }
}
