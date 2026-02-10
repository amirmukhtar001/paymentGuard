<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Convert gallery_images from JSON string to array if needed
        if ($this->has('gallery_images') && is_string($this->gallery_images)) {
            $decoded = json_decode($this->gallery_images, true);
            $this->merge([
                'gallery_images' => is_array($decoded) ? $decoded : [],
            ]);
        } elseif (!$this->has('gallery_images') || empty($this->gallery_images)) {
            $this->merge(['gallery_images' => []]);
        }

        // attached_file is a single UUID string, no conversion needed
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'publish', 'pending'])],
            'video_url' => ['nullable', 'url', 'max:255'],
            'thumbnail_id' => ['nullable', 'exists:media,uuid'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['exists:media,uuid'],
            'attached_file' => ['nullable', 'exists:media,uuid'],
            'remove_attached_file' => ['nullable', 'boolean'],
            'meta_key' => ['nullable', 'string', 'max:255'],
            'meta_value' => ['nullable', 'string'],
            'event_date' => ['nullable', 'date'],
            'event_end_date' => ['nullable', 'date', 'after_or_equal:event_date'],
            'location' => ['nullable', 'string', 'max:255'],
        ];
    }
}
