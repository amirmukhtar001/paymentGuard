<?php

namespace App\Http\Requests\WebsiteSection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWebsiteSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:website_sections,slug'],
            'short_code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'section_type' => ['nullable', 'string', Rule::in(array_merge([''], array_keys(config('website_sections.modules', []))))],
            'heading' => ['nullable', 'string', 'max:500'],
            'subheading' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_text_color' => ['nullable', 'string', 'max:50'],
            'button_background_color' => ['nullable', 'string', 'max:50'],
            'background_image_id' => ['nullable', 'exists:media,uuid'],
            'background_image_url' => ['nullable', 'url', 'max:500'],
            'background_color' => ['nullable', 'string', 'max:50'],
            'background_gradient_color' => ['nullable', 'string', 'max:50'],
            'text_color' => ['nullable', 'string', 'max:50'],
            'heading_color' => ['nullable', 'string', 'max:50'],
            'subheading_color' => ['nullable', 'string', 'max:50'],
            'limit' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:draft,active,archived,hidden'],
        ];
    }
}
