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
                    <span class="text-xs text-gray-500">— Cash reconciliation &amp; money control</span>
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('branches.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Branches</a>
                    <a href="{{ route('shifts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Shifts</a>
                    <a href="{{ route('reconciliations.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Reconciliations</a>
                    <div class="relative group">
                        <button type="button" class="text-sm text-gray-600 hover:text-gray-900 focus:outline-none">Settings ▾</button>
                        <div class="absolute left-0 mt-1 w-52 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 hidden group-hover:block z-50">
                            <div class="py-1">
                                @can('users.mgt.list')
                                <a href="{{ route('settings.users-mgt.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Users</a>
                                @endcan
                                <a href="{{ route('settings.my-roles.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Roles</a>
                                <a href="{{ route('settings.my-permissions.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Permissions</a>
                                <a href="{{ route('settings.menus.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Menus</a>
                                <a href="{{ route('settings.companies.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Companies</a>
                                <a href="{{ route('settings.company-types.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Company Types</a>
                                <a href="{{ route('settings.sections.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sections</a>
                                <a href="{{ route('settings.user_logs.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">User Logs</a>
                                @can('settings.settings.edit')
                                <a href="{{ route('settings.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">App Settings</a>
                                @endcan
                            </div>
                        </div>
                    </div>
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
