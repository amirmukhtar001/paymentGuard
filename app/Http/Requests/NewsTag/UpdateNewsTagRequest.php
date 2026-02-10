<?php

namespace App\Http\Requests\NewsTag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var \App\Models\NewsTag $newsTag */
        $newsTag = $this->route('news_tag'); // {news_tag:uuid}

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
