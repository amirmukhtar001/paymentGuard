<?php

namespace App\Http\Requests\Department;

use App\Enums\DepartmentTypesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Route model binding → getRouteKeyName() = uuid
        $department = $this->route('department');
        $departmentId = $department?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'department_code' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable'],
            'department_type' => ['nullable', Rule::in(DepartmentTypesEnum::cases())],
            'media_id'        => ['nullable', 'exists:media,uuid'],
            'cover_media_id'  => ['nullable', 'exists:media,uuid'],
            'external_url'    => ['nullable', 'url'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'prefix' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'division_id' => $this->input('division_id') ?: 0,
            'district_id' => $this->input('district_id') ?: 0,
            'tehsil_id' => $this->input('tehsil_id') ?: 0,
        ]);
    }
}
