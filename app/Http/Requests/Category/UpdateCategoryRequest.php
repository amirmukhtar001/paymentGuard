<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category');
        if (is_object($categoryId)) {
            $categoryId = $categoryId->id;
        }

        return [
            'category_type_id' => ['required', 'integer', 'exists:category_types,id'],
            'parent_id'        => [
                'nullable',
                'integer',
                'exists:categories,id',
                // prevent parent = itself
                function ($attribute, $value, $fail) use ($categoryId) {
                    if ($value && (int) $value === (int) $categoryId) {
                        $fail('The parent category cannot be the category itself.');
                    }
                },
            ],
            'title'            => ['required', 'string', 'max:255'],
            'slug'             =>  ['nullable', 'string', 'max:255'],
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
