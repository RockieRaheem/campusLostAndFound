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
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            :root { --primary: #1a355b; }
            body { background: #f6f7f8; font-family: Inter, ui-sans-serif, system-ui, sans-serif; color: #0f172a; }
            .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
            .panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 0.75rem; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06); }
            .btn-primary { display: inline-flex; align-items: center; justify-content: center; padding: 0.625rem 1.25rem; border-radius: 0.5rem; background: var(--primary); color: #fff; font-size: 0.875rem; font-weight: 600; text-decoration: none; border: 0; cursor: pointer; }
            .btn-soft { display: inline-flex; align-items: center; justify-content: center; padding: 0.625rem 1.25rem; border-radius: 0.5rem; background: #fff; color: #334155; font-size: 0.875rem; font-weight: 600; text-decoration: none; border: 1px solid #cbd5e1; cursor: pointer; }
            .btn-danger { display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; border-radius: 0.5rem; background: #fff; color: #dc2626; font-size: 0.875rem; font-weight: 600; border: 1px solid #fecaca; cursor: pointer; }
            .chip-btn { display: inline-flex; align-items: center; border-radius: 9999px; border: 1px solid #e2e8f0; background: #fff; color: #475569; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; }
            .chip-btn-active { background: var(--primary); color: #fff; border-color: var(--primary); }
            .field-label { font-size: 0.875rem; font-weight: 600; color: #334155; }
            .field-input { width: 100%; border: 1px solid #cbd5e1; border-radius: 0.5rem; background: #fff; color: #0f172a; padding: 0.75rem 1rem; }
            .status-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #fff; }
            .status-lost { background: #f59e0b; }
            .status-found { background: #10b981; }
            .status-claimed { background: #64748b; }
            .alert-success { border: 1px solid #a7f3d0; background: #ecfdf5; color: #065f46; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; }
            .alert-danger { border: 1px solid #fecaca; background: #fef2f2; color: #b91c1c; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; }
            .error-message { display: block; margin-top: 0.25rem; color: #dc2626; font-size: 0.75rem; font-weight: 500; }
        </style>
    @endif
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