<?php

namespace App\Http\Controllers;

use App\Enums\BusinessType;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessController extends Controller
{
    public function create(): View
    {
        return view('business.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'string', 'in:'.implode(',', array_column(BusinessType::cases(), 'value'))],
            'timezone' => ['nullable', 'string', 'max:50'],
        ]);

        $business = Business::query()->create([
            'name' => $validated['name'],
            'business_type' => $validated['business_type'],
            'timezone' => $validated['timezone'] ?? 'Asia/Karachi',
            'owner_id' => $request->user()->id,
            'is_active' => true,
        ]);

        $request->user()->update(['business_id' => $business->id]);

        return redirect()->route('dashboard')->with('success', 'Business created.');
    }

    public function show(Business $business): View|RedirectResponse
    {
        $this->authorize('view', $business);

        $business->loadCount(['branches', 'users']);

        return view('business.show', compact('business'));
    }
}
