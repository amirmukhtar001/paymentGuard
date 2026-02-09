@extends('layouts.app')

@section('title', 'Shift ' . $shift->code)

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">{{ $shift->code }}</h1>
            <p class="text-sm text-gray-500">{{ $shift->branch->name }} · Cashier: {{ $shift->cashier->name }} · {{ $shift->status->value }}</p>
        </div>
        <div class="flex gap-2">
            @if($shift->isOpen())
                <form method="POST" action="{{ route('shifts.close', $shift) }}" class="inline">@csrf<button type="submit" class="rounded border border-gray-300 px-4 py-2">Close shift</button></form>
            @endif
        </div>
    </div>

    {{-- POS entry --}}
    <section class="mb-8 rounded-lg border bg-white p-4 shadow-sm">
        <h2 class="mb-4 font-semibold">POS sales (expected cash)</h2>
        @if($shift->posSalesRecord)
            <p class="mb-2">Net cash sales: <strong>{{ number_format($shift->posSalesRecord->net_cash_sales, 2) }} {{ $shift->posSalesRecord->currency }}</strong></p>
            @if($shift->posSalesRecord->isLocked())
                <p class="text-sm text-gray-500">Locked at {{ $shift->posSalesRecord->locked_at?->format('M d, H:i') }}</p>
            @else
                <form method="POST" action="{{ route('shifts.pos.lock', $shift) }}" class="inline">@csrf<button type="submit" class="rounded bg-amber-600 px-3 py-1 text-white text-sm">Lock POS</button></form>
            @endif
        @else
            <form method="POST" action="{{ route('shifts.pos.store', $shift) }}" class="max-w-sm space-y-2">
                @csrf
                <div>
                    <label class="block text-sm">Net cash sales</label>
                    <input type="number" name="net_cash_sales" step="0.01" required class="w-full rounded border-gray-300" value="{{ old('net_cash_sales') }}">
                    @error('net_cash_sales')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="rounded bg-gray-800 px-4 py-2 text-white">Save POS</button>
            </form>
        @endif
    </section>

    {{-- Cash count --}}
    <section class="mb-8 rounded-lg border bg-white p-4 shadow-sm">
        <h2 class="mb-4 font-semibold">Cash count (actual)</h2>
        @if($shift->cashCount)
            <p class="mb-2">Total counted: <strong>{{ number_format($shift->cashCount->total_amount, 2) }}</strong></p>
            @if($shift->cashCount->isLocked())
                <p class="text-sm text-gray-500">Locked</p>
            @else
                <form method="POST" action="{{ route('shifts.cash-count.lock', $shift) }}" class="inline">@csrf<button type="submit" class="rounded bg-amber-600 px-3 py-1 text-white text-sm">Lock cash count</button></form>
            @endif
        @else
            <form method="POST" action="{{ route('shifts.cash-count.store', $shift) }}" class="max-w-sm space-y-2">
                @csrf
                <div>
                    <label class="block text-sm">Total cash amount (PKR)</label>
                    <input type="hidden" name="denominations[0][denomination_value]" value="1">
                    <input type="number" name="denominations[0][quantity]" step="0.01" min="0" required placeholder="e.g. 5000" class="w-full rounded border-gray-300" value="{{ old('denominations.0.quantity') }}">
                </div>
                <button type="submit" class="rounded bg-gray-800 px-4 py-2 text-white">Save cash count</button>
            </form>
        @endif
    </section>

    {{-- Reconcile --}}
    @if($shift->reconciliation)
        <section class="rounded-lg border bg-white p-4 shadow-sm">
            <h2 class="mb-2 font-semibold">Reconciliation</h2>
            <p>Expected: {{ number_format($shift->reconciliation->expected_amount, 2) }} · Actual: {{ number_format($shift->reconciliation->actual_amount, 2) }} · Difference: <span class="{{ $shift->reconciliation->difference_type->value === 'short' ? 'text-red-600' : '' }}">{{ number_format($shift->reconciliation->difference_amount, 2) }}</span></p>
            <a href="{{ route('reconciliations.show', $shift->reconciliation) }}" class="text-blue-600 hover:underline">View reconciliation</a>
        </section>
    @elseif($shift->posSalesRecord?->isLocked() && $shift->cashCount?->isLocked())
        <section class="rounded-lg border bg-white p-4 shadow-sm">
            <form method="POST" action="{{ route('shifts.reconcile', $shift) }}">@csrf<button type="submit" class="rounded bg-green-600 px-4 py-2 text-white">Reconcile shift</button></form>
        </section>
    @endif
@endsection
