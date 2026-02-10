<?php

namespace App\Http\Requests\WebsiteDomain;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWebsiteDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => [
                'required',
                'integer',
                'exists:companies,id',
            ],
            'host' => [
                'required',
                'string',
                'max:255',
                Rule::unique('website_domains', 'host')->whereNull('deleted_at'),
            ],
            'is_primary' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'company_id' => 'company',
            'host'       => 'domain host',
        ];
    }
}
