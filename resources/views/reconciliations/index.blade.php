@extends('layouts.app')

@section('title', 'Reconciliations')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Reconciliations</h1>
    <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Branch</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Cashier</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Difference</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reconciliations as $rec)
                    <tr>
                        <td class="px-4 py-2 text-sm">{{ $rec->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-2 text-sm">{{ $rec->branch->name }}</td>
                        <td class="px-4 py-2 text-sm">{{ $rec->shift->cashier->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-right text-sm {{ $rec->difference_type->value === 'short' ? 'text-red-600' : '' }}">{{ number_format($rec->difference_amount, 2) }}</td>
                        <td class="px-4 py-2 text-sm">{{ $rec->status->value }}</td>
                        <td class="px-4 py-2"><a href="{{ route('reconciliations.show', $rec) }}" class="text-blue-600 hover:underline">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No reconciliations yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $reconciliations->links() }}
    </div>
@endsection
