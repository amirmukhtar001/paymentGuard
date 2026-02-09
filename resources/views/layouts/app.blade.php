<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <nav class="border-b bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="font-semibold">{{ config('app.name') }}</a>
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('branches.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Branches</a>
                    <a href="{{ route('shifts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Shifts</a>
                    <a href="{{ route('reconciliations.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Reconciliations</a>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">{{ auth()->user()?->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
