<?php

namespace App\Domain\MoneyControl;

use App\Enums\PosSourceType;
use App\Models\PosSalesRecord;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PosEntryService
{
    public function recordPosTotals(Shift $shift, array $data, User $enteredBy): PosSalesRecord
    {
        if (! $shift->isOpen() && $shift->status->value !== 'closed') {
            throw new \InvalidArgumentException('POS can only be entered for an open or recently closed shift.');
        }

        if ($shift->posSalesRecord()->exists() && $shift->posSalesRecord->isLocked()) {
            throw new \InvalidArgumentException('POS record for this shift is already locked.');
        }

        $netCashSales = (float) ($data['net_cash_sales'] ?? 0);
        $salesGross = (float) ($data['sales_gross'] ?? $netCashSales);
        $discounts = (float) ($data['discounts'] ?? 0);
        $returns = (float) ($data['returns'] ?? 0);

        $record = DB::transaction(function () use ($shift, $enteredBy, $salesGross, $discounts, $returns, $netCashSales, $data) {
            $shift->posSalesRecord()->delete();

            return PosSalesRecord::query()->create([
                'business_id' => $shift->business_id,
                'branch_id' => $shift->branch_id,
                'shift_id' => $shift->id,
                'source_type' => PosSourceType::Manual,
                'external_reference' => $data['external_reference'] ?? null,
                'sales_gross' => $salesGross,
                'discounts' => $discounts,
                'returns' => $returns,
                'net_cash_sales' => $netCashSales,
                'currency' => $data['currency'] ?? 'PKR',
                'entered_by' => $enteredBy->id,
            ]);
        });

        return $record->load(['shift', 'enteredBy']);
    }

    public function lockPosRecord(PosSalesRecord $record): PosSalesRecord
    {
        if ($record->isLocked()) {
            throw new \InvalidArgumentException('POS record is already locked.');
        }

        $record->update(['locked_at' => now()]);

        return $record->fresh();
    }
}
