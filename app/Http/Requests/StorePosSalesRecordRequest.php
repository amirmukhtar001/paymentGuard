<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePosSalesRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'net_cash_sales' => ['required', 'numeric', 'min:0'],
            'sales_gross' => ['nullable', 'numeric', 'min:0'],
            'discounts' => ['nullable', 'numeric', 'min:0'],
            'returns' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'external_reference' => ['nullable', 'string', 'max:100'],
        ];
    }
}
