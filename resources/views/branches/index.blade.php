@extends('layouts.app')

@section('title', 'Branches')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Branches</h1>
        @can('create', App\Models\Branch::class)
            <a href="{{ route('branches.create') }}" class="rounded bg-gray-800 px-4 py-2 text-white">Add branch</a>
        @endcan
    </div>
    <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Shifts</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($branches as $branch)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $branch->name }}</td>
                        <td class="px-4 py-2 text-gray-500">{{ $branch->code ?? '—' }}</td>
                        <td class="px-4 py-2 text-right">{{ $branch->shifts_count }}</td>
                        <td class="px-4 py-2"><a href="{{ route('branches.show', $branch) }}" class="text-blue-600 hover:underline">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No branches. Add one to get started.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
