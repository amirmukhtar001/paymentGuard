@extends('layouts.app')

@section('title', 'Cash reconciliation dashboard')

@section('content')
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Cash reconciliation</h1>
            <p class="text-sm text-gray-500">Expected vs actual cash by shift — spot mismatches and hold staff accountable.</p>
        </div>
        <form method="get" action="{{ route('dashboard') }}" class="flex gap-2">
            <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="rounded border-gray-300">
            <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="rounded border-gray-300">
            <button type="submit" class="rounded bg-gray-800 px-4 py-2 text-white">Filter</button>
        </form>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border bg-white p-4 shadow-sm">
            <div class="text-sm text-gray-500">Total shifts</div>
            <div class="text-2xl font-semibold">{{ $summary['total_shifts'] }}</div>
        </div>
        <div class="rounded-lg border bg-white p-4 shadow-sm">
            <div class="text-sm text-gray-500">Balanced</div>
            <div class="text-2xl font-semibold text-green-600">{{ $summary['balanced_count'] }}</div>
        </div>
        <div class="rounded-lg border bg-white p-4 shadow-sm">
            <div class="text-sm text-gray-500">Short (count / amount)</div>
            <div class="text-2xl font-semibold text-red-600">{{ $summary['short_count'] }} / {{ number_format($summary['short_total_amount'], 2) }} PKR</div>
        </div>
        <div class="rounded-lg border bg-white p-4 shadow-sm">
            <div class="text-sm text-gray-500">Over (count / amount)</div>
            <div class="text-2xl font-semibold text-amber-600">{{ $summary['over_count'] }} / {{ number_format($summary['over_total_amount'], 2) }} PKR</div>
        </div>
    </div>

    <section class="mb-8">
        <h2 class="mb-4 text-lg font-semibold">Recent reconciliations</h2>
        <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Branch</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Cashier</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Expected</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Actual</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Difference</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($summary['reconciliations']->take(10) as $rec)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-2 text-sm">{{ $rec->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-2 text-sm">{{ $rec->branch->name }}</td>
                            <td class="px-4 py-2 text-sm">{{ $rec->shift->cashier->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-right text-sm">{{ number_format($rec->expected_amount, 2) }}</td>
                            <td class="px-4 py-2 text-right text-sm">{{ number_format($rec->actual_amount, 2) }}</td>
                            <td class="px-4 py-2 text-right text-sm {{ $rec->difference_type->value === 'short' ? 'text-red-600' : ($rec->difference_type->value === 'over' ? 'text-amber-600' : '') }}">{{ number_format($rec->difference_amount, 2) }}</td>
                            <td class="px-4 py-2 text-sm">{{ $rec->status->value }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No reconciliations in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
