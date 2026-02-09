<?php

namespace App\Domain\MoneyControl;

use App\Enums\ShiftStatus;
use App\Models\Branch;
use App\Models\Business;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ShiftService
{
    public function openShift(
        Business $business,
        Branch $branch,
        User $cashier,
        ?User $manager = null,
        ?Carbon $scheduledStart = null,
        ?Carbon $scheduledEnd = null
    ): Shift {
        $now = now();

        $shift = Shift::query()->create([
            'business_id' => $business->id,
            'branch_id' => $branch->id,
            'cashier_id' => $cashier->id,
            'manager_id' => $manager?->id,
            'code' => $this->generateShiftCode($branch, $now),
            'scheduled_start_at' => $scheduledStart,
            'scheduled_end_at' => $scheduledEnd,
            'actual_start_at' => $now,
            'actual_end_at' => null,
            'status' => ShiftStatus::Open,
        ]);

        return $shift->fresh(['branch', 'cashier', 'manager']);
    }

    public function closeShift(Shift $shift): Shift
    {
        if (! $shift->isOpen()) {
            throw new \InvalidArgumentException('Shift is not open and cannot be closed.');
        }

        $shift->update([
            'actual_end_at' => now(),
            'status' => ShiftStatus::Closed,
        ]);

        return $shift->fresh();
    }

    protected function generateShiftCode(Branch $branch, Carbon $at): string
    {
        $prefix = $branch->code ?: 'BR'.$branch->id;
        $date = $at->format('Y-m-d');
        $random = Str::lower(Str::random(4));

        return sprintf('%s-%s-%s', $prefix, $date, $random);
    }
}
