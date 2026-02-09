<?php

namespace App\Domain\MoneyControl;

use App\Enums\DifferenceType;
use App\Enums\ReconciliationStatus;
use App\Enums\ShiftStatus;
use App\Models\Reconciliation;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReconciliationService
{
    public function reconcileShift(Shift $shift, User $createdBy): Reconciliation
    {
        if ($shift->reconciliation()->exists()) {
            throw new \InvalidArgumentException('This shift is already reconciled.');
        }

        $posRecord = $shift->posSalesRecord;
        $cashCount = $shift->cashCount;

        if (! $posRecord || ! $posRecord->isLocked()) {
            throw new \InvalidArgumentException('Shift must have a locked expected amount before reconciliation.');
        }

        if (! $cashCount || ! $cashCount->isLocked()) {
            throw new \InvalidArgumentException('Shift must have a locked actual count before reconciliation.');
        }

        $expectedAmount = (float) $posRecord->net_cash_sales;
        $actualAmount = (float) $cashCount->total_amount;
        $differenceAmount = round($actualAmount - $expectedAmount, 2);

        $differenceType = match (true) {
            abs($differenceAmount) < 0.01 => DifferenceType::Balanced,
            $differenceAmount > 0 => DifferenceType::Over,
            default => DifferenceType::Short,
        };

        return DB::transaction(function () use ($shift, $posRecord, $cashCount, $expectedAmount, $actualAmount, $differenceAmount, $differenceType, $createdBy) {
            $reconciliation = Reconciliation::query()->create([
                'business_id' => $shift->business_id,
                'branch_id' => $shift->branch_id,
                'shift_id' => $shift->id,
                'pos_sales_record_id' => $posRecord->id,
                'cash_count_id' => $cashCount->id,
                'expected_amount' => $expectedAmount,
                'actual_amount' => $actualAmount,
                'difference_amount' => $differenceAmount,
                'difference_type' => $differenceType,
                'status' => ReconciliationStatus::PendingReview,
                'created_by' => $createdBy->id,
            ]);

            $shift->update(['status' => ShiftStatus::Reconciled]);

            return $reconciliation->load(['shift', 'posSalesRecord', 'cashCount', 'createdBy']);
        });
    }

    public function updateStatus(Reconciliation $reconciliation, ReconciliationStatus $status, ?User $reviewedBy = null): Reconciliation
    {
        $reconciliation->update([
            'status' => $status,
            'reviewed_by' => $reviewedBy?->id,
            'reviewed_at' => $reviewedBy ? now() : $reconciliation->reviewed_at,
        ]);

        return $reconciliation->fresh();
    }
}
