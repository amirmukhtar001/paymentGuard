<?php

namespace App\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Update this if you use policies/permissions
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id'  => ['required', 'exists:companies,id'],
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:draft,active,inactive,archived'],
            'sort_order'  => ['nullable', 'integer'],
            'transition'  => ['nullable', 'string', 'max:50'],
            'autoplay_ms' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
