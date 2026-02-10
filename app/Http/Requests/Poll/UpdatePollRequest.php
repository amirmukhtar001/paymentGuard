<?php

namespace App\Http\Requests\Poll;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,active,closed,archived'],
            'poll_type' => ['required', 'in:single_choice,multiple_choice'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'allow_anonymous' => ['sometimes', 'boolean'],
            'allow_multiple_votes' => ['sometimes', 'boolean'],
            'show_results_immediately' => ['sometimes', 'boolean'],
            'show_results_before_voting' => ['sometimes', 'boolean'],
            'show_results_after_close' => ['sometimes', 'boolean'],
            'randomize_options' => ['sometimes', 'boolean'],
            'thumbnail_id' => ['nullable', 'exists:media,uuid'],
            'meta_key' => ['nullable', 'string', 'max:255'],
            'meta_value' => ['nullable', 'string'],

            'options' => ['required', 'array', 'min:2'],
            'options.*.option_text' => ['required', 'string', 'max:500'],
            'options.*.option_image_id' => ['nullable', 'exists:media,uuid'],
            'options.*.display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
