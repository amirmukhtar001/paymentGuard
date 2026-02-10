<?php

namespace App\Traits;

use App\Scopes\CompanyScope;

trait BelongsToCompany
{
    /**
     * Boot the trait and apply the company scope
     *
     * @return void
     */
    protected static function bootBelongsToCompany()
    {
        static::addGlobalScope(new CompanyScope);
    }

    /**
     * Get all models without the company scope
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withoutCompanyScope()
    {
        return static::withoutGlobalScope(CompanyScope::class);
    }

    /**
     * Get all models with all companies (bypass scope for admins)
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withAllCompanies()
    {
        return static::withoutGlobalScope(CompanyScope::class);
    }
}
