@extends('layouts.app')

@section('title', $branch->name)

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ $branch->name }}</h1>
        @can('update', $branch)
            <a href="{{ route('branches.edit', $branch) }}" class="rounded border border-gray-300 px-4 py-2">Edit</a>
        @endcan
    </div>
    <p class="text-gray-500">Code: {{ $branch->code ?? '—' }} · Shifts: {{ $branch->shifts_count }}</p>
    <p class="mt-2"><a href="{{ route('shifts.index', ['branch_id' => $branch->id]) }}" class="text-blue-600 hover:underline">View shifts</a></p>
@endsection
