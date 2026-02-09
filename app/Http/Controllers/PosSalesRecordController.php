<?php

namespace App\Http\Controllers;

use App\Domain\MoneyControl\PosEntryService;
use App\Http\Requests\StorePosSalesRecordRequest;
use App\Models\Shift;
use Illuminate\Http\RedirectResponse;

class PosSalesRecordController extends Controller
{
    public function __construct(
        protected PosEntryService $posEntryService
    ) {}

    public function store(StorePosSalesRecordRequest $request, Shift $shift): RedirectResponse
    {
        $this->authorize('update', $shift);

        $this->posEntryService->recordPosTotals($shift, $request->validated(), $request->user());

        return redirect()->route('shifts.show', $shift)->with('success', 'Expected cash amount recorded.');
    }

    public function lock(Shift $shift): RedirectResponse
    {
        $this->authorize('update', $shift);

        $record = $shift->posSalesRecord;
        if (! $record) {
            return redirect()->route('shifts.show', $shift)->with('error', 'No expected amount recorded yet.');
        }

        $this->posEntryService->lockPosRecord($record);

        return redirect()->route('shifts.show', $shift)->with('success', 'Expected amount locked.');
    }
}
