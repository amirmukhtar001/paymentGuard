<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sectionId = $this->route('section')->id;

        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('sections', 'slug')->ignore($sectionId)
            ],
            'section_key' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('sections', 'section_key')->ignore($sectionId)
            ],
            'page_type' => 'required|in:home,about,contact,custom',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'layout_type' => 'required|in:grid,list,slider,masonry',
            'items_limit' => 'nullable|integer|min:1|max:50',
            'status' => 'required|in:active,inactive',
            'settings' => 'nullable|array',
        ];
    }
}
