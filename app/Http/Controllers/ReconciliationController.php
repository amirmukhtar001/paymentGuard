<?php

namespace App\Http\Controllers;

use App\Domain\MoneyControl\ReconciliationService;
use App\Enums\ReconciliationStatus;
use App\Http\Requests\UpdateReconciliationStatusRequest;
use App\Models\Reconciliation;
use App\Models\Shift;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReconciliationController extends Controller
{
    public function __construct(
        protected ReconciliationService $reconciliationService
    ) {}

    public function index(Request $request): View
    {
        $query = Reconciliation::query()
            ->where('business_id', $request->user()->business_id)
            ->with(['branch', 'shift.cashier', 'createdBy'])
            ->latest();

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->get('branch_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        if ($request->filled('difference_type')) {
            $query->where('difference_type', $request->get('difference_type'));
        }

        $reconciliations = $query->paginate(15);

        return view('reconciliations.index', compact('reconciliations'));
    }

    public function show(Reconciliation $reconciliation): View
    {
        $this->authorize('view', $reconciliation);

        $reconciliation->load(['shift.cashier', 'posSalesRecord', 'cashCount.denominations', 'branch', 'createdBy', 'reviewedBy']);

        return view('reconciliations.show', compact('reconciliation'));
    }

    public function store(Shift $shift): RedirectResponse
    {
        $this->authorize('update', $shift);

        $reconciliation = $this->reconciliationService->reconcileShift($shift, request()->user());

        return redirect()->route('reconciliations.show', $reconciliation)->with('success', 'Shift reconciled.');
    }

    public function updateStatus(UpdateReconciliationStatusRequest $request, Reconciliation $reconciliation): RedirectResponse
    {
        $this->authorize('update', $reconciliation);

        $status = ReconciliationStatus::from($request->validated('status'));
        $this->reconciliationService->updateStatus($reconciliation, $status, $request->user());
        if ($request->filled('notes')) {
            $reconciliation->update(['notes' => $request->validated('notes')]);
        }

        return redirect()->route('reconciliations.show', $reconciliation)->with('success', 'Status updated.');
    }
}
