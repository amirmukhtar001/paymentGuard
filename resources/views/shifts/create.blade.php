@extends('layouts.app')

@section('title', 'Open shift')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Open shift</h1>
    <form method="POST" action="{{ route('shifts.store') }}" class="max-w-md space-y-4">
        @csrf
        <div>
            <label for="branch_id" class="mb-1 block text-sm font-medium">Branch</label>
            <select name="branch_id" id="branch_id" required class="w-full rounded border-gray-300">
                @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ old('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
            @error('branch_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="cashier_id" class="mb-1 block text-sm font-medium">Cashier</label>
            <select name="cashier_id" id="cashier_id" required class="w-full rounded border-gray-300">
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('cashier_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->role->value }})</option>
                @endforeach
            </select>
            @error('cashier_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="manager_id" class="mb-1 block text-sm font-medium">Manager (optional)</label>
            <select name="manager_id" id="manager_id" class="w-full rounded border-gray-300">
                <option value="">—</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('manager_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-4">
            <button type="submit" class="rounded bg-gray-800 px-4 py-2 text-white">Open shift</button>
            <a href="{{ route('shifts.index') }}" class="rounded border border-gray-300 px-4 py-2">Cancel</a>
        </div>
    </form>
@endsection
