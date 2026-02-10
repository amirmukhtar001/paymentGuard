<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Plug into your auth/permissions later
        return true;
    }

    public function rules(): array
    {
        return [
            'category_type_id' => ['required', 'integer', 'exists:category_types,id'],
            'parent_id'        => ['nullable', 'integer', 'exists:categories,id'],
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'status'           => ['required', Rule::in(['active', 'inactive'])],
            'sort_order'       => ['nullable', 'integer'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'sort_order' => $this->input('sort_order', 0),
        ]);
    }
}
