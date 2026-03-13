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

    <form method="POST" action="{{ route('items.update', $item) }}" class="grid grid-cols-1 gap-8 lg:grid-cols-3">
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

            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary w-full">Save Changes</button>
                <a href="{{ route('items.index') }}" class="btn-soft w-full">Discard</a>
            </div>
        </aside>
    </form>
</section>
@endsection