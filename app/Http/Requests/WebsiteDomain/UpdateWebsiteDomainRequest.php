<?php

namespace App\Http\Requests\WebsiteDomain;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var \App\Models\WebsiteDomain $websiteDomain */
        $websiteDomain = $this->route('website_domain'); // {website_domain:uuid}

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
                Rule::unique('website_domains', 'host')
                    ->ignore($websiteDomain?->id)
                    ->whereNull('deleted_at'),
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