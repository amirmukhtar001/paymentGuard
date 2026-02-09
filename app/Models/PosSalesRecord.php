<?php

namespace App\Models;

use App\Enums\PosSourceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosSalesRecord extends Model
{
    protected $table = 'pos_sales_records';

    protected $fillable = [
        'business_id',
        'branch_id',
        'shift_id',
        'source_type',
        'external_reference',
        'sales_gross',
        'discounts',
        'returns',
        'net_cash_sales',
        'currency',
        'entered_by',
        'locked_at',
    ];

    protected function casts(): array
    {
        return [
            'sales_gross' => 'decimal:2',
            'discounts' => 'decimal:2',
            'returns' => 'decimal:2',
            'net_cash_sales' => 'decimal:2',
            'source_type' => PosSourceType::class,
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

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function isLocked(): bool
    {
        return $this->locked_at !== null;
    }
}
