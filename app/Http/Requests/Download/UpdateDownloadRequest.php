<?php

namespace App\Http\Requests\Download;

use App\Enums\StatusEnum;
use App\Models\Web\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateDownloadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Route model binding → getRouteKeyName() = uuid
        $download = $this->route('download') ?? $this->route('downloads') ?? $this->route('item');

        $downloadId = $download?->id;

        return [
            'title' => ['required', 'string', 'max:255'],

            'slug'  => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('downloads', 'slug')->ignore($downloadId),
            ],

            'category_id'   => ['nullable', 'integer', 'exists:categories,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'company_id'    => ['nullable', 'integer', 'exists:companies,id'],

            // accept media uuid from UI
            'media_id' => ['nullable', 'exists:media,id'],

            'attachment_date' => ['nullable', 'date'],
            'description'     => ['nullable', 'string'],

            'status' => ['required', Rule::in(array_column(StatusEnum::cases(), 'value'))],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->input('slug') ?: Str::slug((string) $this->input('title')),

            'category_id'   => $this->input('category_id') ?: null,
            'department_id' => $this->input('department_id') ?: null,
            'company_id'    => $this->input('company_id') ?: null,
        ]);

        // Convert media uuid -> media numeric id
        if ($this->filled('media_id')) {
            $media = Media::query()->where('uuid', $this->input('media_id'))->first();
     
            $this->merge([
                'media_id' => $media?->id,
            ]);
        }
    }
}
