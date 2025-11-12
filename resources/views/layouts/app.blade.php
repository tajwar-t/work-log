<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="bg-gray-800 text-white w-64 flex-shrink-0"
           :class="{ 'block': sidebarOpen, 'hidden': !sidebarOpen }"
           x-show="sidebarOpen || window.innerWidth >= 1024"
           x-transition:enter="transition ease-in-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in-out duration-300 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full">
        <div class="p-4 text-xl font-bold">
            <a href="/">
                {{ config('app.name') }}
            </a>
        </div>
        <nav class="mt-6">
            <a href="{{ route('dashboard') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">Dashboard</a>
            <a href="{{ route('profile') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">Profile</a>
            <a href="{{ route('work-logs.index') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">Work Logs</a>
            <a href="{{ route('worklog.generator') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">
                Work Log Generator
            </a>
            <a href="{{ route('worklog.history') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">
                Work Log History
            </a>
            <a href="{{ route('reports') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">Reports</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left py-2 px-4 hover:bg-gray-700 rounded">Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col">
        <!-- Top navbar -->
        <header class="flex items-center justify-between bg-white shadow p-4">
            <div class="flex items-center">
                <!-- Sidebar toggle button -->
                <button @click="sidebarOpen = !sidebarOpen" class="mr-4 focus:outline-none">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="font-semibold text-lg">Dashboard</span>
            </div>

            <!-- User dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <span>{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg py-1 z-50">
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

<!-- AlpineJS for sidebar toggle and dropdown -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>
</html>
