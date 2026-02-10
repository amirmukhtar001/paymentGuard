<?php

namespace App\Http\Requests\Code;

use App\Enums\StatusEnum;
use App\Models\Web\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug'  => ['nullable', 'string', 'max:255', 'unique:codes,slug'],

            'description' => ['nullable', 'string'],

            'category_id'   => ['nullable', 'integer', 'exists:categories,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'company_id'    => ['nullable', 'integer', 'exists:companies,id'],

            // accept media uuid from UI (consistent with your other modules)
            'media_id' => ['nullable', 'exists:media,id'],

            'promulgation_date' => ['nullable', 'date'],

            'sort_order' => ['nullable', 'integer', 'min:0'],

           'status' => ['required', Rule::in(array_column(StatusEnum::cases(), 'value'))],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->input('slug') ?: Str::slug((string) $this->input('title')),

            'sort_order' => $this->input('sort_order') !== null ? (int) $this->input('sort_order') : 0,

            // normalize empty selects to null
            'category_id'   => $this->input('category_id') ?: null,
            'department_id' => $this->input('department_id') ?: null,
            'company_id'    => $this->input('company_id') ?: null,
        ]);

        // Convert media uuid -> media numeric id (since table has unsignedBigInteger media_id)
        if ($this->filled('media_id')) {
            $media = Media::query()->where('uuid', $this->input('media_id'))->first();

            $this->merge([
                'media_id' => $media?->id,
            ]);
        }
    }
}
