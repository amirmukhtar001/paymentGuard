<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelFilter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'filter_for_type',
        'filter_for_id', 
        'model_type',
        'model_id',
        'is_filter_by_user',
        'is_filter_by_company', 
        'is_filter_by_section',
        'is_filter_by_unit',
        'is_filter_by_status',
        'is_active'
    ];

    protected $casts = [
        'is_filter_by_user' => 'boolean',
        'is_filter_by_company' => 'boolean',
        'is_filter_by_section' => 'boolean', 
        'is_filter_by_unit' => 'boolean',
        'is_filter_by_status' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Get the filterable entity (User or Role).
     */
    public function filterFor()
    {
        return $this->morphTo();
    }

    /**
     * Get the model that this filter applies to.
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Scope to get active filters only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get filters for a specific model
     */
    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query = $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    /**
     * Scope to get filters for a specific filter entity (User/Role)
     */
    public function scopeForFilterEntity($query, $filterType, $filterId = null)
    {
        $query = $query->where('filter_for_type', $filterType);
        
        if ($filterId) {
            $query->where('filter_for_id', $filterId);
        }
        
        return $query;
    }

    protected static function newFactory()
    {
        return \Database\Factories\ModelFilterFactory::new();
    }
}