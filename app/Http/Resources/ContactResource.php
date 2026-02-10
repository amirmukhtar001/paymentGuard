<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $company = $this->whenLoaded('company');
        $department = $this->whenLoaded('department');
        $designation = $this->whenLoaded('designation');

        // enum-safe status (works whether status is enum-cast or string)
        $status = is_object($this->status) ? ($this->status->value ?? null) : $this->status;

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,

            'company_name' => $company ? $company->title : null,

            'department_name' => $department ? ($department->name ?? $department->title) : null,
            'designation_name' => $designation ? ($designation->title ?? null) : null,

            'contact_number' => $this->contact_number,
            'ext_no' => $this->ext_no,
            'fax_number' => $this->fax_number,
            'email_address' => $this->email_address,
            'office_address' => $this->office_address,
            'remarks' => $this->remarks,

            'is_primary' => (bool) $this->is_primary,
            'sort_order' => (int) ($this->sort_order ?? 0),
            'status' => $status,

            'created_at' => optional($this->created_at)->format('d/m/Y'),
            'updated_at' => optional($this->updated_at)->format('d/m/Y'),
        ];
    }
}
