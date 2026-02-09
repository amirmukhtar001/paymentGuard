@extends('layouts.app')

@section('title', 'Add branch')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Add branch</h1>
    <form method="POST" action="{{ route('branches.store') }}" class="max-w-md space-y-4">
        @csrf
        <div>
            <label for="name" class="mb-1 block text-sm font-medium">Name</label>
            <input type="text" name="name" id="name" required class="w-full rounded border-gray-300" value="{{ old('name') }}">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="code" class="mb-1 block text-sm font-medium">Code (optional)</label>
            <input type="text" name="code" id="code" class="w-full rounded border-gray-300" value="{{ old('code') }}">
        </div>
        <div>
            <label for="address" class="mb-1 block text-sm font-medium">Address (optional)</label>
            <textarea name="address" id="address" rows="2" class="w-full rounded border-gray-300">{{ old('address') }}</textarea>
        </div>
        <button type="submit" class="rounded bg-gray-800 px-4 py-2 text-white">Create branch</button>
    </form>
@endsection
