<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Convert attached_files from JSON string to array if needed
        if ($this->has('attached_files') && is_string($this->attached_files)) {
            $decoded = json_decode($this->attached_files, true);
            $this->merge([
                'attached_files' => is_array($decoded) ? $decoded : [],
            ]);
        } elseif (!$this->has('attached_files') || empty($this->attached_files)) {
            $this->merge(['attached_files' => []]);
        }

        // Handle sub_category_ids
        if (!$this->has('sub_category_ids') || empty($this->sub_category_ids)) {
            $this->merge(['sub_category_ids' => []]);
        }
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'parent_category_id' => ['nullable', 'exists:categories,id'],
            'sub_category_ids' => ['nullable', 'array'],
            'sub_category_ids.*' => ['exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'publish', 'pending'])],
            'video_url' => ['nullable', 'url', 'max:255'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'thumbnail_id' => ['nullable', 'exists:media,uuid'],
            'attached_files' => ['nullable', 'array'],
            'attached_files.*' => ['exists:media,uuid'],
            'meta_key' => ['nullable', 'string', 'max:255'],
            'meta_value' => ['nullable', 'string'],
        ];
    }
}
