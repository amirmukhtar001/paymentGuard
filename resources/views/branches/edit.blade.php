@extends('layouts.app')

@section('title', 'Edit ' . $branch->name)

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Edit branch</h1>
    <form method="POST" action="{{ route('branches.update', $branch) }}" class="max-w-md space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="mb-1 block text-sm font-medium">Name</label>
            <input type="text" name="name" id="name" required class="w-full rounded border-gray-300" value="{{ old('name', $branch->name) }}">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="code" class="mb-1 block text-sm font-medium">Code</label>
            <input type="text" name="code" id="code" class="w-full rounded border-gray-300" value="{{ old('code', $branch->code) }}">
        </div>
        <div>
            <label for="address" class="mb-1 block text-sm font-medium">Address</label>
            <textarea name="address" id="address" rows="2" class="w-full rounded border-gray-300">{{ old('address', $branch->address) }}</textarea>
        </div>
        <div>
            <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}> Active</label>
        </div>
        <button type="submit" class="rounded bg-gray-800 px-4 py-2 text-white">Update</button>
    </form>
@endsection
