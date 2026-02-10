<?php

namespace App\Http\Requests\SliderSlide;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Update this based on your auth logic
    }

    public function rules(): array
    {
        return [
            'slider_id'      => ['required', 'exists:sliders,id'],
            'media_id'       => ['nullable', 'exists:media,uuid'],
            'title'          => ['nullable', 'string', 'max:255'],
            'caption'        => ['nullable', 'string'],
            'button_text'    => ['nullable', 'string', 'max:120'],
            'button_url'     => ['nullable', 'string', 'max:500'],
            'is_active'      => ['required', 'boolean'],
            'sort_order'     => ['nullable', 'integer'],
            'schedule_start' => ['nullable', 'date'],
            'schedule_end'   => ['nullable', 'date'],
            'overlay_opacity'=> ['nullable', 'numeric', 'min:0', 'max:1'],
            'meta'           => ['nullable', 'json'],
        ];
    }
}
