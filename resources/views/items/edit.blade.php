@extends('layouts.app')

@section('title', 'Edit Item | Campus Lost & Found')

@section('content')
<section class="mx-auto w-full max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-3 border-b border-slate-200 pb-6 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-primary">Update Report</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">Update {{ $item->item_name }}</h1>
            <p class="mt-1 text-sm text-slate-500">Last updated {{ $item->updated_at->diffForHumans() }}</p>
        </div>
        <a href="{{ route('items.show', $item) }}" class="btn-soft">Back to Details</a>
    </div>

    @if($errors->any())
        <div class="alert-danger mb-6">
            <p class="font-semibold">Please fix the following errors:</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        @csrf
        @method('PUT')

        <div class="space-y-6 lg:col-span-2">
            <section class="panel p-6">
                <h2 class="text-lg font-bold text-slate-900">Item Information</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="item_name" class="field-label">Item Name</label>
                        <input type="text" name="item_name" id="item_name" class="field-input mt-2" value="{{ old('item_name', $item->item_name) }}" required>
                        @error('item_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="field-label">Description</label>
                        <textarea name="description" id="description" rows="5" class="field-input mt-2" required>{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="field-label">Location</label>
                        <input type="text" name="location" id="location" class="field-input mt-2" value="{{ old('location', $item->location) }}" required>
                        @error('location')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="contact" class="field-label">Contact</label>
                        <input type="text" name="contact" id="contact" class="field-input mt-2" value="{{ old('contact', $item->contact) }}" required>
                        @error('contact')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="panel p-6">
                <h2 class="text-lg font-bold text-slate-900">Photos</h2>
                <div data-photo-uploader data-max-photos="3" data-existing-count="{{ $item->photos->count() }}" class="mt-4 space-y-4">
                    <div data-dropzone class="cursor-pointer rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-5 transition hover:border-primary/40 hover:bg-primary/5">
                        <p class="text-sm font-semibold text-slate-700">Drop photos here or click to choose</p>
                        <p class="mt-1 text-xs text-slate-500">You can keep existing photos, remove selected ones, and add new images.</p>
                        <input data-photo-input type="file" name="photos[]" accept="image/png,image/jpeg,image/jpg,image/webp" multiple class="field-input mt-3" />
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                        Existing kept: <span data-existing-kept>{{ $item->photos->count() }}</span>, New selected: <span data-photo-count>0</span>, Total after save: <span data-total-count>{{ $item->photos->count() }}</span>/3
                    </div>

                    <div data-preview-grid class="grid grid-cols-2 gap-3 sm:grid-cols-3"></div>

                    @if($item->photos->count() > 0)
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach($item->photos as $photo)
                                <label class="relative overflow-hidden rounded-lg border border-slate-200 bg-white">
                                    <img src="{{ $photo->url }}" alt="Item photo {{ $loop->iteration }}" class="h-28 w-full object-cover" />
                                    <span class="absolute right-2 top-2 rounded bg-white/90 px-2 py-1 text-[10px] font-semibold text-slate-700">Remove</span>
                                    <input data-remove-existing-photo type="checkbox" name="remove_photo_ids[]" value="{{ $photo->id }}" class="absolute right-2 top-2 h-4 w-4" {{ in_array($photo->id, array_map('intval', old('remove_photo_ids', [])), true) ? 'checked' : '' }}>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="flex h-28 items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 text-sm text-slate-500">
                            No photos uploaded yet.
                        </div>
                    @endif
                </div>
                @error('photos')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                @error('photos.*')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                @error('remove_photo_ids')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </section>
        </div>

        <aside class="space-y-6">
            <section class="panel border-l-4 border-l-primary p-6">
                <h2 class="text-lg font-bold text-slate-900">Status Tracking</h2>
                <div class="mt-4 space-y-3">
                    @foreach(['Lost', 'Found', 'Claimed'] as $statusOption)
                        <label class="flex cursor-pointer items-center rounded-lg border p-3 transition {{ old('status', $item->status) === $statusOption ? 'border-primary bg-primary/5' : 'border-slate-200 hover:bg-slate-50' }}">
                            <input type="radio" name="status" value="{{ $statusOption }}" class="h-4 w-4 text-primary focus:ring-primary" {{ old('status', $item->status) === $statusOption ? 'checked' : '' }}>
                            <span class="ml-3 text-sm font-semibold text-slate-700">{{ $statusOption }}</span>
                        </label>
                    @endforeach
                </div>
                @error('status')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </section>

            <section class="panel p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">Timeline</h3>
                <div class="mt-3 space-y-2 text-sm text-slate-600">
                    <p><span class="font-semibold text-slate-700">Created:</span> {{ $item->created_at->format('M d, Y h:i A') }}</p>
                    <p><span class="font-semibold text-slate-700">Updated:</span> {{ $item->updated_at->format('M d, Y h:i A') }}</p>
                    @if($item->claimed_at)
                        <p><span class="font-semibold text-slate-700">Claimed:</span> {{ $item->claimed_at->format('M d, Y h:i A') }}</p>
                    @endif
                </div>
            </section>

            <section class="panel overflow-hidden">
                <div class="border-b border-slate-200 px-4 py-3">
                    <h3 class="text-sm font-bold text-slate-700">Location Map</h3>
                </div>
                <div class="flex h-44 items-center justify-center bg-slate-100 text-slate-500">
                    <span class="material-symbols-outlined mr-2">location_on</span>
                    <span class="text-sm">{{ $item->location }}</span>
                </div>
            </section>

            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">Save Changes</button>
                <a href="{{ route('items.index') }}" class="btn-soft w-full">Discard</a>
            </div>
        </aside>
    </form>
</section>
@endsection