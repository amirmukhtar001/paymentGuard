<?php

namespace App\Observers;

use App\Models\Web\Employee;      // 👈 adjust namespace if needed
use App\Models\Web\Leader;
use App\Models\Web\Department;
use Illuminate\Http\Request;

class EmployeeObserver
{
    public function __construct(protected Request $request) {}

    public function created(Employee $employee): void
    {
        if (!$this->request->boolean('display_on_home')) {
            return;
        }

        // Prefer request value, fall back to model's department_id
        $department_id = (int) ($this->request->input('department_id', $employee->department_id ?? 0));

        // Get company either from employee or via department
        if ($employee->company_id) {
            $company = $employee->company_id;
        } else {
            $department = Department::find($department_id);
            $company = $department?->company?->id;
        }

        if (empty($company)) {
            $company = 0;
        }

        Leader::create([
            'leaderable_type'  => $employee::class,
            'leaderable_id'    => $employee->id,
            'company_id'       => $company,
            'department_id'    => $department_id,
            'position_type_id' => (int) ($this->request->input('position_type_id', $employee->position_type_id ?? 0)),
            'position'         => $this->request->input('leader_position'),
            'department'       => $this->request->input('leader_department'),
            'sort_order'       => (int) ($this->request->input('sort_order', $employee->sort_order ?? 0)),
            'is_active'        => true,
        ]);
    }

    public function updated(Employee $employee): void
    {
        // If field not present, do nothing (update didn't try to change leader)
        if (!$this->request->has('display_on_home')) {
            return;
        }

        // Prefer request value, fall back to model
        $department_id = (int) ($this->request->input('department_id', $employee->department_id ?? 0));

        if ($employee->company_id) {
            $company = $employee->company_id;
        } else {
            $department = Department::find($department_id);
            $company = $department?->company?->id;
        }

        if (empty($company)) {
            $company = 0;
        }

        if ($this->request->boolean('display_on_home')) {
            Leader::updateOrCreate(
                [
                    'leaderable_type' => $employee::class,
                    'leaderable_id'   => $employee->id,
                    'company_id'      => $company,
                ],
                [
                    'department_id'    => $department_id,
                    'position_type_id' => (int) ($this->request->input('position_type_id', $employee->position_type_id ?? 0)),
                    'position'         => $this->request->input('leader_position'),
                    'department'       => $this->request->input('leader_department'),
                    'sort_order'       => (int) ($this->request->input('sort_order', $employee->sort_order ?? 0)),
                    'is_active'        => true,
                ]
            );
        } else {
            Leader::where([
                'leaderable_type' => $employee::class,
                'leaderable_id'   => $employee->id,
                'company_id'      => $company,
            ])->delete();
        }
    }
}
