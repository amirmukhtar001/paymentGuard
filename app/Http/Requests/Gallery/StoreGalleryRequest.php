<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id'   => ['required', 'integer'],
            'name'         => ['required', 'string', 'max:125'],
            'slug'         => ['nullable', 'string', 'max:125'],
            'description'  => ['nullable', 'string'],
            'type'         => ['required', Rule::in(['image', 'video', 'mixed'])],
            'status'       => ['required', Rule::in(['draft', 'published', 'archived'])],

            'is_featured'  => ['nullable', Rule::in(['yes', 'no'])],

            // When mixed => URL required, media_id not required
            'media_url'    => ['nullable', 'url', 'required_if:type,mixed'],

            // You currently post media_id as UUID from media manager
            // When NOT mixed => media_id required
            'media_id'     => ['nullable', 'required_unless:type,mixed'],

            // allow 0 (none) OR existing category id
            'category_id'  => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $categoryId = (int) ($this->input('category_id') ?? 0);
            if ($categoryId > 0 && !\DB::table('categories')->where('id', $categoryId)->exists()) {
                $validator->errors()->add('category_id', 'Selected category is invalid.');
            }
        });
    }
}
