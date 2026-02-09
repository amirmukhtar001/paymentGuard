<?php

namespace App\Http\Controllers;

use App\Domain\MoneyControl\CashCountService;
use App\Http\Requests\StoreCashCountRequest;
use App\Models\Shift;
use Illuminate\Http\RedirectResponse;

class CashCountController extends Controller
{
    public function __construct(
        protected CashCountService $cashCountService
    ) {}

    public function store(StoreCashCountRequest $request, Shift $shift): RedirectResponse
    {
        $this->authorize('update', $shift);

        $this->cashCountService->recordCashCount($shift, $request->validated(), $request->user());

        return redirect()->route('shifts.show', $shift)->with('success', 'Cash count recorded.');
    }

    public function lock(Shift $shift): RedirectResponse
    {
        $this->authorize('update', $shift);

        $cashCount = $shift->cashCount;
        if (! $cashCount) {
            return redirect()->route('shifts.show', $shift)->with('error', 'No cash count to lock.');
        }

        $this->cashCountService->submitAndLockCashCount($cashCount);

        return redirect()->route('shifts.show', $shift)->with('success', 'Cash count locked.');
    }
}
