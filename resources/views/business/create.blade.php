<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create business - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 py-12">
    <div class="mx-auto max-w-md rounded-lg border bg-white p-6 shadow-sm">
        <h1 class="mb-4 text-xl font-bold">Create your business</h1>
        <form method="POST" action="{{ route('business.store') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="mb-1 block text-sm font-medium">Business name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded border-gray-300">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="business_type" class="mb-1 block text-sm font-medium">Type</label>
                <select name="business_type" id="business_type" class="w-full rounded border-gray-300">
                    <option value="restaurant" {{ old('business_type') === 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                    <option value="clinic" {{ old('business_type') === 'clinic' ? 'selected' : '' }}>Clinic</option>
                    <option value="retail" {{ old('business_type') === 'retail' ? 'selected' : '' }}>Retail</option>
                    <option value="salon" {{ old('business_type') === 'salon' ? 'selected' : '' }}>Salon</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="timezone" class="mb-1 block text-sm font-medium">Timezone</label>
                <input type="text" name="timezone" id="timezone" value="{{ old('timezone', 'Asia/Karachi') }}" class="w-full rounded border-gray-300">
            </div>
            <button type="submit" class="w-full rounded bg-gray-800 py-2 text-white">Create business</button>
        </form>
    </div>
</body>
</html>
