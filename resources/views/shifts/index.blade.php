@extends('layouts.app')

@section('title', 'Shifts')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Shifts</h1>
        @can('create', App\Models\Shift::class)
            <a href="{{ route('shifts.create') }}" class="rounded bg-gray-800 px-4 py-2 text-white">Open shift</a>
        @endcan
    </div>

    <form method="get" class="mb-4 flex gap-2">
        <select name="branch_id" class="rounded border-gray-300">
            <option value="">All branches</option>
            @foreach($branches as $b)
                <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded border-gray-300">
            <option value="">All statuses</option>
            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            <option value="reconciled" {{ request('status') === 'reconciled' ? 'selected' : '' }}>Reconciled</option>
        </select>
        <button type="submit" class="rounded bg-gray-200 px-4 py-2">Filter</button>
    </form>

    <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Branch</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Cashier</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Started</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($shifts as $shift)
                    <tr>
                        <td class="px-4 py-2 text-sm">{{ $shift->code }}</td>
                        <td class="px-4 py-2 text-sm">{{ $shift->branch->name }}</td>
                        <td class="px-4 py-2 text-sm">{{ $shift->cashier->name }}</td>
                        <td class="px-4 py-2 text-sm">{{ $shift->actual_start_at->format('M d, H:i') }}</td>
                        <td class="px-4 py-2 text-sm">{{ $shift->status->value }}</td>
                        <td class="px-4 py-2"><a href="{{ route('shifts.show', $shift) }}" class="text-blue-600 hover:underline">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No shifts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $shifts->links() }}
    </div>
@endsection
