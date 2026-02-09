<?php

namespace App\Http\Controllers;

use App\Domain\MoneyControl\DashboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        if (! $user->business_id) {
            return redirect()->route('business.create');
        }
        $business = $user->business;

        $from = $request->get('from') ? Carbon::parse($request->get('from')) : now()->startOfMonth();
        $to = $request->get('to') ? Carbon::parse($request->get('to')) : now();

        $summary = $this->dashboardService->dateRangeSummary($business, $from, $to);
        $byBranch = $this->dashboardService->mismatchesByBranch($business, $from, $to);
        $byCashier = $this->dashboardService->mismatchesByCashier($business, $from, $to);

        return view('dashboard', [
            'summary' => $summary,
            'byBranch' => $byBranch,
            'byCashier' => $byCashier,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
