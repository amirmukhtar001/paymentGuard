<?php

namespace App\Traits;

use App\Models\Web\Leader;

trait HasLeadershipRoles
{
    protected static function bootHasLeadershipRoles()
    {
        static::created(function ($model) {
            // Check if the request has make_leader flag
            if (request()->has('make_leader') && request()->make_leader) {
                $model->assignAsLeader(
                    request()->company_id ?? $model->company_id,
                    request()->department_id ?? $model->department_id,
                    request()->leader_position,
                    [
                        'department' => request()->leader_department,
                        'sort_order' => request()->leader_sort_order ?? 0,
                    ]
                );
            }
        });

        static::updated(function ($model) {
            // You can also handle updates here if needed
        });
    }

    public function assignAsLeader($companyId, $position = null, $attributes = [])
    {
        return Leader::updateOrCreate(
            [
                'leaderable_type' => get_class($this),
                'leaderable_id' => $this->id,
                'company_id' => $companyId,
            ],
            array_merge([
                'position' => $position,
                'is_active' => true,
                'sort_order' => 0,
            ], $attributes)
        );
    }

    // ... rest of the trait methods
}
