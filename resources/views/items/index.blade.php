@extends('layouts.app')

@section('title', 'Dashboard | Campus Lost & Found')

@section('content')
@php($dashboardStats = $stats)

<section class="border-b border-slate-200 bg-white">
    <div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">Campus Lost &amp; Found</h1>
        <p class="mt-3 max-w-2xl text-slate-600">A centralized hub to track and recover misplaced belongings across campus.</p>
    </div>
</section>

<section class="mx-auto -mt-6 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <article class="panel p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Reports</p>
            <p class="mt-2 text-3xl font-black text-slate-900">{{ $dashboardStats['total'] }}</p>
        </article>
        <article class="panel border-l-4 border-l-amber-500 p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Lost</p>
            <p class="mt-2 text-3xl font-black text-amber-600">{{ $dashboardStats['lost'] }}</p>
        </article>
        <article class="panel border-l-4 border-l-emerald-500 p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Found</p>
            <p class="mt-2 text-3xl font-black text-emerald-600">{{ $dashboardStats['found'] }}</p>
        </article>
        <article class="panel border-l-4 border-l-slate-400 p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Claimed</p>
            <p class="mt-2 text-3xl font-black text-slate-600">{{ $dashboardStats['claimed'] }}</p>
        </article>
    </div>
</section>

<section class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @if(session('success'))
        <div data-alert data-auto-dismiss="3500" class="alert-success mb-6 flex items-start justify-between gap-3">
            <span>{{ session('success') }}</span>
            <button type="button" data-alert-close class="text-emerald-700/70 transition hover:text-emerald-900" aria-label="Dismiss notification">
                <span class="material-symbols-outlined text-base">close</span>
            </button>
        </div>
    @endif

    <form method="GET" action="{{ route('items.index') }}" class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="relative w-full max-w-2xl">
            <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search item name, description, or location"
                class="field-input pl-11"
            >
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('items.index', ['search' => request('search')]) }}" class="chip-btn {{ !request('status') ? 'chip-btn-active' : '' }}">All</a>
            <a href="{{ route('items.index', ['status' => 'Lost', 'search' => request('search')]) }}" class="chip-btn {{ request('status') === 'Lost' ? 'chip-btn-active' : '' }}">Lost</a>
            <a href="{{ route('items.index', ['status' => 'Found', 'search' => request('search')]) }}" class="chip-btn {{ request('status') === 'Found' ? 'chip-btn-active' : '' }}">Found</a>
            <a href="{{ route('items.index', ['status' => 'Claimed', 'search' => request('search')]) }}" class="chip-btn {{ request('status') === 'Claimed' ? 'chip-btn-active' : '' }}">Claimed</a>
            <button type="submit" class="btn-primary">Apply</button>
            <a href="{{ route('items.create') }}" class="btn-primary">Report Item</a>
        </div>
    </form>

    @if($items->count() > 0)
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($items as $item)
                <article class="panel overflow-hidden transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="relative h-48 border-b border-slate-100 bg-slate-100">
                        @if($item->primary_photo_url)
                            <img src="{{ $item->primary_photo_url }}" alt="{{ $item->item_name }} photo" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-slate-400">
                                <span class="material-symbols-outlined text-6xl">inventory_2</span>
                            </div>
                        @endif
                        <div class="absolute right-4 top-4">
                            <span class="status-badge {{ $item->status === 'Lost' ? 'status-lost' : ($item->status === 'Found' ? 'status-found' : 'status-claimed') }}">
                                {{ $item->status }}
                            </span>
                        </div>
                    </div>
                    <div class="border-b border-slate-100 bg-slate-50 px-5 py-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-500">{{ $item->created_at->format('M d, Y') }}</span>
                            <span class="text-xs font-medium text-slate-500">{{ $item->photos->count() }} photo{{ $item->photos->count() === 1 ? '' : 's' }}</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-slate-900">{{ $item->item_name }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($item->description, 110) }}</p>

                        <div class="mt-4 space-y-2 rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
                            <p class="flex items-center gap-2"><span class="material-symbols-outlined text-base">location_on</span>{{ $item->location }}</p>
                            <p class="flex items-center gap-2"><span class="material-symbols-outlined text-base">person</span>Reported by {{ $item->user->name ?? 'Anonymous' }}</p>
                            @if($item->claimed_at)
                                <p class="flex items-center gap-2 text-emerald-600 font-medium"><span class="material-symbols-outlined text-base">check_circle</span>Claimed {{ $item->claimed_at->format('M d, Y') }}</p>
                            @endif
                        </div>

                        <div class="mt-5 flex flex-col gap-2 border-t border-slate-100 pt-4 sm:flex-row sm:items-center w-full">
                            <a href="{{ route('items.show', $item) }}" class="btn-primary w-full sm:w-auto flex-1 justify-center">View Details</a>

                            @can('update', $item)
                            <div class="flex gap-2 w-full sm:w-auto">
                                <a href="{{ route('items.edit', $item) }}" class="btn-soft flex-1 justify-center sm:w-auto" title="Edit">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                @if($item->status !== 'Claimed')
                                    <form action="{{ route('items.claim', $item) }}" method="POST" class="flex-1 sm:w-auto">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-soft w-full justify-center" onclick="return confirm('Mark this item as claimed?')" title="Mark as Claimed">
                                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('items.destroy', $item) }}" method="POST" data-delete-form data-item-label="{{ $item->item_name }}" class="flex-1 sm:w-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger w-full justify-center" title="Delete">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                            @endcan
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if(method_exists($items, 'links'))
            <div class="pagination-wrap mt-8">
                {{ $items->onEachSide(1)->links() }}
            </div>
        @endif
    @else
        <div class="panel p-16 text-center flex flex-col items-center justify-center border-dashed border-2 border-slate-200 shadow-none bg-slate-50/50">
            <div class="h-20 w-20 rounded-full bg-slate-100 flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-4xl text-slate-400">search_off</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">No items found</h3>
            <p class="mt-2 text-slate-500 max-w-md">We couldn't find any items matching your current search or filters. Try adjusting them or report a new finding.</p>
            <div class="mt-8 flex gap-4">
                <a href="{{ route('items.index') }}" class="btn-soft">Clear Filters</a>
                <a href="{{ route('items.create') }}" class="btn-primary flex items-center gap-2"><span class="material-symbols-outlined text-sm">add</span> Report New Item</a>
            </div>
        </div>
    @endif
</section>
@endsection