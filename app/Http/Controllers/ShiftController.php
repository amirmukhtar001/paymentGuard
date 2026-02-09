<?php

namespace App\Http\Controllers;

use App\Domain\MoneyControl\ShiftService;
use App\Http\Requests\OpenShiftRequest;
use App\Models\Branch;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function __construct(
        protected ShiftService $shiftService
    ) {}

    public function create(Request $request): View
    {
        $this->authorize('create', Shift::class);

        $branches = Branch::query()->where('business_id', $request->user()->business_id)->get();
        $users = User::query()->where('business_id', $request->user()->business_id)->get();

        return view('shifts.create', compact('branches', 'users'));
    }

    public function index(Request $request): View
    {
        $query = Shift::query()
            ->where('business_id', $request->user()->business_id)
            ->with(['branch', 'cashier', 'manager'])
            ->latest('actual_start_at');

        if ($request->user()->isCashier()) {
            $query->where('cashier_id', $request->user()->id);
        }
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->get('branch_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $shifts = $query->paginate(15);
        $branches = Branch::query()->where('business_id', $request->user()->business_id)->get();

        return view('shifts.index', compact('shifts', 'branches'));
    }

    public function show(Shift $shift): View|RedirectResponse
    {
        $this->authorize('view', $shift);

        $shift->load(['branch', 'cashier', 'manager', 'posSalesRecord', 'cashCount.denominations', 'reconciliation']);

        return view('shifts.show', compact('shift'));
    }

    public function store(OpenShiftRequest $request): RedirectResponse
    {
        $this->authorize('create', Shift::class);

        $branch = Branch::query()->where('business_id', $request->user()->business_id)->findOrFail($request->validated('branch_id'));
        $cashier = $request->user()->business->users()->findOrFail($request->validated('cashier_id'));
        $manager = $request->validated('manager_id') ? $request->user()->business->users()->find($request->validated('manager_id')) : null;

        $shift = $this->shiftService->openShift(
            $request->user()->business,
            $branch,
            $cashier,
            $manager,
            $request->validated('scheduled_start_at') ? Carbon::parse($request->validated('scheduled_start_at')) : null,
            $request->validated('scheduled_end_at') ? Carbon::parse($request->validated('scheduled_end_at')) : null
        );

        return redirect()->route('shifts.show', $shift)->with('success', 'Shift opened.');
    }

    public function close(Shift $shift): RedirectResponse
    {
        $this->authorize('update', $shift);

        $this->shiftService->closeShift($shift);

        return redirect()->route('shifts.show', $shift)->with('success', 'Shift closed.');
    }
}
