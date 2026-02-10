<?php

namespace App\Http\Requests\Page;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'website_section_id' => ['nullable', 'exists:website_sections,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'media_type' => ['required', Rule::in(['none', 'image', 'video'])],
            'featured_image_id' => ['nullable', 'exists:media,uuid'],
            'video_url' => ['nullable', 'url'],
            'video_thumbnail_id' => ['nullable', 'exists:media,uuid'],
            'status' => ['required', Rule::in(['draft', 'scheduled', 'published', 'archived'])],
            'display_module_type' => ['nullable', 'string', 'in:jobs,services,tenders,rules_and_regulations,feedback,downloads,faqs,footer,our_heroes'],
            'detail_template' => ['nullable', 'string'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'iframe' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:published_at'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'related_links' => ['nullable', 'array'],
            'related_links.*.title' => ['required_with:related_links.*.url', 'string', 'max:255'],
            'related_links.*.url' => ['required_with:related_links.*.title', 'url', 'max:500'],
            'related_links.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}
