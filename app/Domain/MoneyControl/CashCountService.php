<?php

namespace App\Domain\MoneyControl;

use App\Enums\CashCountStatus;
use App\Models\CashCount;
use App\Models\CashCountDenomination;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CashCountService
{
    /**
     * @param  array{denominations: array<int, array{denomination_value: float|int, quantity: int}>, notes?: string|null}  $data
     */
    public function recordCashCount(Shift $shift, array $data, User $countedBy): CashCount
    {
        if (! $shift->isOpen() && $shift->status->value !== 'closed') {
            throw new \InvalidArgumentException('Cash count can only be entered for an open or recently closed shift.');
        }

        $existing = $shift->cashCount;
        if ($existing && $existing->isLocked()) {
            throw new \InvalidArgumentException('Cash count for this shift is already locked.');
        }

        $denominations = $data['denominations'] ?? [];
        $notes = $data['notes'] ?? null;

        return DB::transaction(function () use ($shift, $denominations, $notes, $countedBy, $existing) {
            if ($existing) {
                $existing->denominations()->delete();
                $existing->delete();
            }

            $countedAt = now();
            $totalAmount = 0.0;

            foreach ($denominations as $row) {
                $value = (float) ($row['denomination_value'] ?? 0);
                $qty = (int) ($row['quantity'] ?? 0);
                $totalAmount += $value * $qty;
            }

            $cashCount = CashCount::query()->create([
                'business_id' => $shift->business_id,
                'branch_id' => $shift->branch_id,
                'shift_id' => $shift->id,
                'counted_by' => $countedBy->id,
                'counted_at' => $countedAt,
                'total_amount' => round($totalAmount, 2),
                'status' => CashCountStatus::Draft,
                'notes' => $notes,
            ]);

            foreach ($denominations as $row) {
                if (isset($row['denomination_value']) && isset($row['quantity']) && (int) $row['quantity'] > 0) {
                    CashCountDenomination::query()->create([
                        'cash_count_id' => $cashCount->id,
                        'denomination_value' => (float) $row['denomination_value'],
                        'quantity' => (int) $row['quantity'],
                    ]);
                }
            }

            $cashCount->refresh();
            $cashCount->total_amount = $cashCount->denominations->sum('subtotal');
            $cashCount->save();

            return $cashCount->load(['denominations', 'countedBy', 'shift']);
        });
    }

    public function submitAndLockCashCount(CashCount $cashCount): CashCount
    {
        if ($cashCount->isLocked()) {
            throw new \InvalidArgumentException('Cash count is already locked.');
        }

        $cashCount->update([
            'status' => CashCountStatus::Submitted,
            'locked_at' => now(),
        ]);

        return $cashCount->fresh(['denominations']);
    }
}
