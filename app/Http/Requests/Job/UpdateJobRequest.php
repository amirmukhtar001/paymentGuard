<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobRequest extends FormRequest
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
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'publish', 'pending'])],
            'video_url' => ['nullable', 'url'],
            'job_type' => ['required', Rule::in(['regular', 'contractual', 'consultant'])],
            'scale' => ['nullable', 'string', 'max:255'],
            'vacancies' => ['nullable', 'integer', 'min:1'],
            'expiry_date' => ['nullable', 'date'],
            'experience' => ['nullable', 'string', 'max:255'],
            'age_limit' => ['nullable', 'string', 'max:255'],
            'experience_field' => ['nullable', 'string', 'max:255'],
            'thumbnail_id' => ['nullable', 'exists:media,uuid'],
            'attached_files' => ['nullable', 'array'],
            'attached_files.*' => ['exists:media,uuid'],
            'meta_key' => ['nullable', 'string', 'max:255'],
            'meta_value' => ['nullable', 'string'],
        ];
    }
}
