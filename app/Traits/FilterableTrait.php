<?php

namespace App\Traits;

use App\Models\ModelFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait FilterableTrait
{
    /**
     * Apply dynamic filters based on user and model filter configuration
     */
    public function scopeApplyDynamicFilters(Builder $query)
    {
        $user = Auth::user();
        
        if (!$user) {
            return $query;
        }

        $modelClass = get_class($this);
        
        // Get filters for the current user
        $userFilters = ModelFilter::active()
            ->forModel($modelClass)
            ->forFilterEntity(get_class($user), $user->id)
            ->get();

        // Get filters for user's roles (if user has roles)
        $roleFilters = collect();
        if (method_exists($user, 'roles')) {
            foreach ($user->roles as $role) {
                $roleFilters = $roleFilters->merge(
                    ModelFilter::active()
                        ->forModel($modelClass)
                        ->forFilterEntity(get_class($role), $role->id)
                        ->get()
                );
            }
        }

        // Combine user and role filters
        $allFilters = $userFilters->merge($roleFilters);

        foreach ($allFilters as $filter) {
            $this->applyIndividualFilter($query, $filter, $user);
        }

        return $query;
    }

    /**
     * Apply individual filter based on filter configuration
     */
    private function applyIndividualFilter(Builder $query, ModelFilter $filter, $user)
    {
        // Filter by user
        if ($filter->is_filter_by_user && $this->hasUserField()) {
            $query->where($this->getUserFieldName(), $user->id);
        }

        // Filter by company
        if ($filter->is_filter_by_company && $this->hasCompanyField()) {
            if (isset($user->company_id)) {
                $query->where($this->getCompanyFieldName(), $user->company_id);
            } elseif (method_exists($user, 'company') && $user->company) {
                $query->where($this->getCompanyFieldName(), $user->company->id);
            }
        }

        // Filter by section
        if ($filter->is_filter_by_section && $this->hasSectionField()) {
            if (isset($user->section_id)) {
                $query->where($this->getSectionFieldName(), $user->section_id);
            } elseif (method_exists($user, 'section') && $user->section) {
                $query->where($this->getSectionFieldName(), $user->section->id);
            }
        }

        // Filter by unit
        if ($filter->is_filter_by_unit && $this->hasUnitField()) {
            if (isset($user->unit_id)) {
                $query->where($this->getUnitFieldName(), $user->unit_id);
            } elseif (method_exists($user, 'unit') && $user->unit) {
                $query->where($this->getUnitFieldName(), $user->unit->id);
            }
        }

        // Filter by status (active records only)
        if ($filter->is_filter_by_status && $this->hasStatusField()) {
            $query->where($this->getStatusFieldName(), 1); // Assuming 1 is active
        }
    }

    /**
     * Check if model has user field
     */
    private function hasUserField()
    {
        return in_array($this->getUserFieldName(), $this->getFillable()) || 
               $this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $this->getUserFieldName());
    }

    /**
     * Check if model has company field
     */
    private function hasCompanyField()
    {
        return in_array($this->getCompanyFieldName(), $this->getFillable()) || 
               $this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $this->getCompanyFieldName());
    }

    /**
     * Check if model has section field
     */
    private function hasSectionField()
    {
        return in_array($this->getSectionFieldName(), $this->getFillable()) || 
               $this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $this->getSectionFieldName());
    }

    /**
     * Check if model has unit field
     */
    private function hasUnitField()
    {
        return in_array($this->getUnitFieldName(), $this->getFillable()) || 
               $this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $this->getUnitFieldName());
    }

    /**
     * Check if model has status field
     */
    private function hasStatusField()
    {
        return in_array($this->getStatusFieldName(), $this->getFillable()) || 
               $this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $this->getStatusFieldName());
    }

    /**
     * Get user field name (can be overridden in model)
     */
    protected function getUserFieldName()
    {
        return property_exists($this, 'userFieldName') ? $this->userFieldName : 'user_id';
    }

    /**
     * Get company field name (can be overridden in model)
     */
    protected function getCompanyFieldName()
    {
        return property_exists($this, 'companyFieldName') ? $this->companyFieldName : 'company_id';
    }

    /**
     * Get section field name (can be overridden in model)
     */
    protected function getSectionFieldName()
    {
        return property_exists($this, 'sectionFieldName') ? $this->sectionFieldName : 'section_id';
    }

    /**
     * Get unit field name (can be overridden in model)
     */
    protected function getUnitFieldName()
    {
        return property_exists($this, 'unitFieldName') ? $this->unitFieldName : 'unit_id';
    }

    /**
     * Get status field name (can be overridden in model)
     */
    protected function getStatusFieldName()
    {
        return property_exists($this, 'statusFieldName') ? $this->statusFieldName : 'status';
    }

    /**
     * Relationship to model filters
     */
    public function modelFilters()
    {
        return $this->morphMany(ModelFilter::class, 'model');
    }
}