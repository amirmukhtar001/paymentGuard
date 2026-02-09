<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashCountDenomination extends Model
{
    protected $fillable = [
        'cash_count_id',
        'denomination_value',
        'quantity',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'denomination_value' => 'decimal:2',
            'quantity' => 'integer',
            'subtotal' => 'decimal:2',
        ];
    }

    public function cashCount(): BelongsTo
    {
        return $this->belongsTo(CashCount::class, 'cash_count_id');
    }

    protected static function booted(): void
    {
        static::saving(function (CashCountDenomination $model): void {
            $model->subtotal = $model->denomination_value * $model->quantity;
        });
    }
}
