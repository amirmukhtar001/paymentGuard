<?php

namespace App\Models;

use App\Enums\BusinessType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    protected $fillable = [
        'name',
        'business_type',
        'timezone',
        'owner_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'business_type' => BusinessType::class,
            'is_active' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function reconciliations(): HasMany
    {
        return $this->hasMany(Reconciliation::class);
    }
}
