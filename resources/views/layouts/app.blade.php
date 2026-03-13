<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Campus Lost & Found Tracker')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-bg-soft font-sans text-slate-900">
    <div class="relative flex min-h-screen flex-col">
        <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur-md">
            <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="{{ route('items.index') }}" class="flex items-center gap-3 text-primary">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-primary text-white shadow-md shadow-primary/20">
                        <span class="material-symbols-outlined">travel_explore</span>
                    </span>
                    <span class="text-lg font-extrabold tracking-tight">Campus Finder</span>
                </a>

                <nav class="hidden items-center gap-6 md:flex">
                    <a href="{{ route('items.index') }}" class="text-sm font-semibold transition {{ request()->routeIs('items.index') ? 'text-primary' : 'text-slate-600 hover:text-primary' }}">Dashboard</a>
                    <a href="{{ route('items.create') }}" class="text-sm font-semibold transition {{ request()->routeIs('items.create') ? 'text-primary' : 'text-slate-600 hover:text-primary' }}">Report Item</a>
                </nav>
            </div>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="mt-auto border-t border-slate-200 bg-white py-6">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 text-sm text-slate-500 sm:px-6 lg:px-8">
                <p>&copy; {{ date('Y') }} Campus Lost &amp; Found Tracker</p>
                <p>Professional Edition</p>
            </div>
        </footer>
    </div>
</body>
</html>