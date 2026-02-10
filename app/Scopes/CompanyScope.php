<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CompanyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::user();

        // If no user is authenticated, don't apply any filter
        if (!$user) {
            return;
        }

        // Super-admin and admin can see all companies
        if ($user->hasRole(['super-admin', 'admin'])) {
            return;
        }

        // Other users can only see their company's data
        if ($user->company_id) {
            $tableName = $model->getTable();

            // Special handling for Company model - filter by id matching user's company_id
            // Check if model is Company by comparing table name or class name
            if ($tableName === 'companies' || get_class($model) === \App\Models\Company::class) {
                $builder->where($tableName . '.id', $user->company_id);
            } else {
                // For other models, filter by company_id field
                $builder->where($tableName . '.company_id', $user->company_id);
            }
        }
    }
}
