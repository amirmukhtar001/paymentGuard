<?php

namespace App\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Route model binding parameter name should match controller:
        // public function update(UpdatePersonRequest $request, Person $person)
        $person = $this->route('person'); // expects {person} param

        $personId = $person?->id;

        return [
            'name'        => ['required', 'string', 'max:190'],
            'slug'        => ['required', 'string', 'max:190', Rule::unique('people', 'slug')->ignore($personId)],
            'person_type' => ['required', 'string', 'max:50'],

            'alternate_names' => ['nullable'],
            'short_bio'       => ['nullable', 'string', 'max:500'],
            'biography'       => ['nullable', 'string'],

            'gender'      => ['nullable', 'string', 'max:30'],
            'birth_date'  => ['nullable', 'date'],
            'death_date'  => ['nullable', 'date', 'after_or_equal:birth_date'],
            'birth_place' => ['nullable', 'string', 'max:190'],
            'death_place' => ['nullable', 'string', 'max:190'],
            'nationality' => ['nullable', 'string', 'max:120'],
            'era_period'  => ['nullable', 'string', 'max:120'],
            'primary_field' => ['nullable', 'string', 'max:120'],

            'category_id' => ['nullable', 'integer', 'min:0'],
            'section_id'  => ['nullable', 'integer', 'min:0'],

            'profile_media_id' => ['nullable', 'string', 'max:36'],

            'website_url'  => ['nullable', 'url', 'max:500'],
            'social_links' => ['nullable'],
            'sources'      => ['nullable'],
            'meta'         => ['nullable'],

            'status'      => ['nullable', Rule::in(['draft', 'published', 'archived'])],
            'is_featured' => ['nullable', Rule::in(['yes', 'no'])],
            'display_order' => ['nullable', 'integer', 'min:0'],

            'published_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['alternate_names', 'social_links', 'sources', 'meta'] as $key) {
            if (is_string($this->input($key))) {
                $decoded = json_decode($this->input($key), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->merge([$key => $decoded]);
                }
            }
        }
    }
}
