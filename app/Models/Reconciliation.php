<?php

namespace App\Models;

use App\Enums\DifferenceType;
use App\Enums\ReconciliationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reconciliation extends Model
{
    protected $fillable = [
        'business_id',
        'branch_id',
        'shift_id',
        'pos_sales_record_id',
        'cash_count_id',
        'expected_amount',
        'actual_amount',
        'difference_amount',
        'difference_type',
        'status',
        'created_by',
        'reviewed_by',
        'reviewed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'expected_amount' => 'decimal:2',
            'actual_amount' => 'decimal:2',
            'difference_amount' => 'decimal:2',
            'difference_type' => DifferenceType::class,
            'status' => ReconciliationStatus::class,
            'reviewed_at' => 'datetime',
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

    public function posSalesRecord(): BelongsTo
    {
        return $this->belongsTo(PosSalesRecord::class, 'pos_sales_record_id');
    }

    public function cashCount(): BelongsTo
    {
        return $this->belongsTo(CashCount::class, 'cash_count_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isBalanced(): bool
    {
        return $this->difference_type === DifferenceType::Balanced;
    }
}
