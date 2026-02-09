<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Branch::class);

        $branches = Branch::query()
            ->where('business_id', $request->user()->business_id)
            ->withCount('shifts')
            ->latest()
            ->get();

        return view('branches.index', compact('branches'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Branch::class);

        return view('branches.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Branch::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        Branch::query()->create([
            'business_id' => $request->user()->business_id,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('branches.index')->with('success', 'Branch created.');
    }

    public function show(Branch $branch): View
    {
        $this->authorize('view', $branch);

        $branch->loadCount('shifts');

        return view('branches.show', compact('branch'));
    }

    public function edit(Branch $branch): View
    {
        $this->authorize('update', $branch);

        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $this->authorize('update', $branch);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $branch->update(array_merge($validated, ['is_active' => $request->boolean('is_active')]));

        return redirect()->route('branches.show', $branch)->with('success', 'Branch updated.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $this->authorize('delete', $branch);

        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch deleted.');
    }
}
