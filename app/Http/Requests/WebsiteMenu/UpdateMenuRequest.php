<?php

namespace App\Http\Requests\WebsiteMenu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'menu_type_id' => 'required|exists:menu_types,id',
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'items' => 'required|json',
        ];
    }
}
