<?php

namespace App\Models;

use App\Enums\ShiftStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shift extends Model
{
    protected $fillable = [
        'business_id',
        'branch_id',
        'cashier_id',
        'manager_id',
        'code',
        'scheduled_start_at',
        'scheduled_end_at',
        'actual_start_at',
        'actual_end_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_start_at' => 'datetime',
            'scheduled_end_at' => 'datetime',
            'actual_start_at' => 'datetime',
            'actual_end_at' => 'datetime',
            'status' => ShiftStatus::class,
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

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function posSalesRecord(): HasOne
    {
        return $this->hasOne(PosSalesRecord::class, 'shift_id');
    }

    public function cashCount(): HasOne
    {
        return $this->hasOne(CashCount::class, 'shift_id');
    }

    public function reconciliation(): HasOne
    {
        return $this->hasOne(Reconciliation::class, 'shift_id');
    }

    public function isOpen(): bool
    {
        return $this->status === ShiftStatus::Open;
    }

    public function isLocked(): bool
    {
        return $this->status === ShiftStatus::Locked;
    }
}
