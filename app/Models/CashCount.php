<?php

namespace App\Models;

use App\Enums\CashCountStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashCount extends Model
{
    protected $fillable = [
        'business_id',
        'branch_id',
        'shift_id',
        'counted_by',
        'counted_at',
        'total_amount',
        'status',
        'notes',
        'locked_at',
    ];

    protected function casts(): array
    {
        return [
            'counted_at' => 'datetime',
            'total_amount' => 'decimal:2',
            'status' => CashCountStatus::class,
            'locked_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function countedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counted_by');
    }

    public function denominations(): HasMany
    {
        return $this->hasMany(CashCountDenomination::class, 'cash_count_id');
    }

    public function isLocked(): bool
    {
        return $this->locked_at !== null;
    }
}
