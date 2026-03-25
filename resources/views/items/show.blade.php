@extends('layouts.app')

@section('title', 'Item Details | Campus Lost & Found')

@section('content')
<section class="mx-auto w-full max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <nav class="mb-6 flex items-center gap-2 text-sm font-medium">
        <a href="{{ route('items.index') }}" class="text-slate-500 hover:text-primary">Dashboard</a>
        <span class="material-symbols-outlined text-sm text-slate-400">chevron_right</span>
        <span class="rounded bg-primary/10 px-2 py-1 text-primary">#LF-{{ str_pad((string) $item->id, 4, '0', STR_PAD_LEFT) }}</span>
    </nav>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <aside class="lg:col-span-4">
            <div class="panel sticky top-24 p-4">
                @if($item->primary_photo_url)
                    <div data-item-gallery class="space-y-3">
                        <img
                            data-main-image
                            src="{{ $item->primary_photo_url }}"
                            alt="{{ $item->item_name }}"
                            class="aspect-square w-full rounded-lg object-cover"
                        />

                        @if($item->photos->count() > 1)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($item->photos as $photo)
                                    <button
                                        type="button"
                                        data-gallery-thumb
                                        data-full-src="{{ $photo->url }}"
                                        data-full-alt="{{ $item->item_name }} photo {{ $loop->iteration }}"
                                        class="overflow-hidden rounded-md border {{ $loop->first ? 'border-primary ring-2 ring-primary/30' : 'border-slate-200' }}"
                                        aria-label="View photo {{ $loop->iteration }}"
                                    >
                                        <img src="{{ $photo->url }}" alt="{{ $item->item_name }} thumbnail {{ $loop->iteration }}" class="h-14 w-full object-cover" />
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex aspect-square items-center justify-center rounded-lg bg-slate-100">
                        <span class="material-symbols-outlined text-7xl text-slate-300">inventory_2</span>
                    </div>
                @endif
                <div class="mt-4 space-y-2">
                    @if($item->status !== 'Claimed')
                        @can('update', $item)
                        <form action="{{ route('items.claim', $item) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-primary w-full" onclick="return confirm('Mark this item as claimed?')">Mark as Claimed</button>
                        </form>
                        @endcan
                    @endif
                    @can('update', $item)
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <a href="{{ route('items.edit', $item) }}" class="btn-soft w-full">Edit</a>
                            <form action="{{ route('items.destroy', $item) }}" method="POST" data-delete-form data-item-label="{{ $item->item_name }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-delete-trigger class="btn-danger w-full outline-offset-2">Delete</button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </aside>

        <div class="lg:col-span-8 space-y-6">
            <section class="panel p-6 md:p-8">
                <div class="flex flex-col justify-between gap-4 md:flex-row md:items-start">
                    <div class="min-w-0 max-w-full">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 break-words">{{ $item->item_name }}</h1>
                            <span class="status-badge {{ $item->status === 'Lost' ? 'status-lost' : ($item->status === 'Found' ? 'status-found' : 'status-claimed') }}">{{ $item->status }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">Record ID: #LF-{{ str_pad((string) $item->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <a href="{{ route('items.index') }}" class="btn-soft shrink-0">Back to Dashboard</a>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 border-t border-slate-100 pt-6 md:grid-cols-3">
                    <div class="min-w-0">
                        <p class="text-sm text-slate-500">Location</p>
                        <p class="mt-1 font-semibold text-slate-800 break-words">{{ $item->location }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-slate-500">Reported Date</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $item->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-slate-500">Contact</p>
                        <p class="mt-1 font-semibold text-slate-800 break-words">{{ $item->contact }}</p>
                    </div>
                </div>
            </section>

            <section class="panel p-6 md:p-8 min-w-0">
                <h2 class="text-sm font-bold uppercase tracking-widest text-slate-500">Item Description</h2>
                <p class="mt-4 whitespace-pre-line leading-relaxed text-slate-700 break-words">{{ $item->description }}</p>
            </section>

            <section class="rounded-xl border-2 border-primary/20 bg-primary/5 p-6 md:p-8">
                <h2 class="text-sm font-bold uppercase tracking-widest text-primary">Status Timeline</h2>
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-lg border border-primary/10 bg-white/60 p-4">
                        <p class="text-xs font-medium text-slate-500">Created At</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $item->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="rounded-lg border border-primary/10 bg-white/60 p-4">
                        <p class="text-xs font-medium text-slate-500">Last Updated</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $item->updated_at->format('d M Y, h:i A') }}</p>
                    </div>
                    @if($item->claimed_at)
                        <div class="rounded-lg border border-primary/10 bg-white/60 p-4 md:col-span-2">
                            <p class="text-xs font-medium text-slate-500">Claimed At</p>
                            <p class="mt-1 font-semibold text-slate-800">{{ $item->claimed_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</section>
@endsection