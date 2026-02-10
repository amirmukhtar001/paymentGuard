<?php

namespace App\Observers;

use App\Models\Web\Leader;
use App\Models\Web\CabinetMember;
use App\Models\Web\Department;
use Illuminate\Http\Request;

class CabinetMemberObserver
{
    public function __construct(protected Request $request) {}

    public function created(CabinetMember $member): void
    {
        if (!$this->request->boolean('make_leader')) {
            return;
        }
        $department_id = (int) ($this->request->input('department_id', 0));
        $company  = $member->company_id ?? 0;
        if ($member->company_id) {
            $company = $member->company_id;
        } else {
            $department = Department::find($department_id);
            $company = $department?->company?->id;
        }
         if (empty($company)) {
            $company  = 0;
        }
        Leader::create([
            'leaderable_type'  => $member::class,
            'leaderable_id'    => $member->id,
            'company_id'       => $company,
            'department_id'    => $department_id,
            'position_type_id' => (int) ($this->request->input('position_type_id', 0)),
            'position'         => $this->request->input('leader_position'),
            'department'       => $this->request->input('leader_department'),
            'sort_order'       => (int) ($this->request->input('sort_order', 0)), // ✅ was "order"
            'is_active'        => true,
        ]);
    }

    public function updated(CabinetMember $member): void
    {
        // If field not present, do nothing (because update didn't try to change leader)
        if (!$this->request->has('make_leader')) {
            return;
        }

        $department_id = (int) ($this->request->input('department_id', 0));

        if ($member->company_id) {
            $company = $member->company_id;
        } else {
            $department = Department::find($department_id);
            $company = $department?->company?->id;
        }
        if (empty($company)) {
            $company  = 0;
        }
        if ($this->request->boolean('make_leader')) {
            Leader::updateOrCreate(
                [
                    'leaderable_type' => $member::class,
                    'leaderable_id'   => $member->id,
                    'company_id'      => $company,
                ],
                [
                    'department_id'    => $department_id,
                    'position_type_id' => (int) ($this->request->input('position_type_id', 0)),
                    'position'         => $this->request->input('leader_position'),
                    'department'       => $this->request->input('leader_department'),
                    'sort_order'       => (int) ($this->request->input('sort_order', 0)), // ✅ fix
                    'is_active'        => true,
                ]
            );
        } else {
            Leader::where([
                'leaderable_type' => $member::class,
                'leaderable_id'   => $member->id,
                'company_id'      => $company,
            ])->delete();
        }
    }
}
