<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = [
        'business_id',
        'name',
        'code',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_user')
            ->withPivot(['role', 'is_primary'])
            ->withTimestamps();
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
