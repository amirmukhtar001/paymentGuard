<?php

namespace App\Domain\MoneyControl;

use App\Enums\DifferenceType;
use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    public function dailySummary(Business $business, Carbon $date): array
    {
        $reconciliations = $business->reconciliations()
            ->whereDate('created_at', $date)
            ->with(['branch', 'shift.cashier'])
            ->get();

        return $this->aggregateReconciliations($reconciliations);
    }

    public function dateRangeSummary(Business $business, Carbon $from, Carbon $to): array
    {
        $reconciliations = $business->reconciliations()
            ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
            ->with(['branch', 'shift.cashier'])
            ->get();

        return $this->aggregateReconciliations($reconciliations);
    }

    public function mismatchesByBranch(Business $business, Carbon $from, Carbon $to): Collection
    {
        return $business->reconciliations()
            ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
            ->whereIn('difference_type', [DifferenceType::Short->value, DifferenceType::Over->value])
            ->selectRaw('branch_id, difference_type, count(*) as count, sum(abs(difference_amount)) as total_amount')
            ->groupBy('branch_id', 'difference_type')
            ->with('branch:id,name,code')
            ->get()
            ->groupBy('branch_id');
    }

    /**
     * @return Collection<int, object{cashier_id: int, short_count: int, total_short: float, cashier?: \App\Models\User}>
     */
    public function mismatchesByCashier(Business $business, Carbon $from, Carbon $to): Collection
    {
        $items = $business->reconciliations()
            ->whereBetween('reconciliations.created_at', [$from->startOfDay(), $to->endOfDay()])
            ->where('reconciliations.difference_type', DifferenceType::Short->value)
            ->join('shifts', 'reconciliations.shift_id', '=', 'shifts.id')
            ->selectRaw('shifts.cashier_id as cashier_id, count(*) as short_count, sum(reconciliations.difference_amount) as total_short')
            ->groupBy('shifts.cashier_id')
            ->get();

        $userIds = $items->pluck('cashier_id')->unique()->filter()->values()->all();
        $users = $business->users()->whereIn('id', $userIds)->get()->keyBy('id');

        return $items->map(function ($row) use ($users) {
            $row->cashier = $users->get($row->cashier_id);
            $row->total_short = round(abs((float) $row->total_short), 2);

            return $row;
        });
    }

    protected function aggregateReconciliations(Collection $reconciliations): array
    {
        $balanced = $reconciliations->where('difference_type', DifferenceType::Balanced)->count();
        $over = $reconciliations->where('difference_type', DifferenceType::Over);
        $short = $reconciliations->where('difference_type', DifferenceType::Short);

        return [
            'total_shifts' => $reconciliations->count(),
            'balanced_count' => $balanced,
            'over_count' => $over->count(),
            'over_total_amount' => round($over->sum('difference_amount'), 2),
            'short_count' => $short->count(),
            'short_total_amount' => round(abs($short->sum('difference_amount')), 2),
            'reconciliations' => $reconciliations,
        ];
    }
}
