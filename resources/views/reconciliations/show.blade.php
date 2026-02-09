@extends('layouts.app')

@section('title', 'Reconciliation')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Reconciliation</h1>
    <div class="mb-6 rounded-lg border bg-white p-4 shadow-sm">
        <p><strong>Branch:</strong> {{ $reconciliation->branch->name }}</p>
        <p><strong>Shift:</strong> {{ $reconciliation->shift->code }} (Cashier: {{ $reconciliation->shift->cashier->name }})</p>
        <p><strong>Expected:</strong> {{ number_format($reconciliation->expected_amount, 2) }}</p>
        <p><strong>Actual:</strong> {{ number_format($reconciliation->actual_amount, 2) }}</p>
        <p><strong>Difference:</strong> <span class="{{ $reconciliation->difference_type->value === 'short' ? 'text-red-600 font-semibold' : '' }}">{{ number_format($reconciliation->difference_amount, 2) }} ({{ $reconciliation->difference_type->value }})</span></p>
        <p><strong>Status:</strong> {{ $reconciliation->status->value }}</p>
    </div>
    @can('update', $reconciliation)
        <form method="POST" action="{{ route('reconciliations.update-status', $reconciliation) }}" class="max-w-md space-y-2">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm">Update status</label>
                <select name="status" class="w-full rounded border-gray-300">
                    @foreach(\App\Enums\ReconciliationStatus::cases() as $s)
                        <option value="{{ $s->value }}" {{ $reconciliation->status === $s ? 'selected' : '' }}>{{ $s->value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">Notes</label>
                <textarea name="notes" rows="2" class="w-full rounded border-gray-300">{{ old('notes', $reconciliation->notes) }}</textarea>
            </div>
            <button type="submit" class="rounded bg-gray-800 px-4 py-2 text-white">Update</button>
        </form>
    @endcan
@endsection
