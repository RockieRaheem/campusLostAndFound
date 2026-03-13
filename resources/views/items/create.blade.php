@extends('layouts.app')

@section('title', 'Report Item | Campus Lost & Found')

@section('content')
<section class="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">Report a New Item</h1>
        <p class="mt-2 text-slate-600">Fill in the details below to help reunite items with their owners.</p>
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

    <div class="panel overflow-hidden">
        <form method="POST" action="{{ route('items.store') }}" class="space-y-8 p-6 sm:p-10">
            @csrf

            <div>
                <label class="field-label">Report Type</label>
                <div class="mt-3 grid grid-cols-2 gap-3 rounded-lg bg-slate-100 p-1">
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="Lost" class="peer sr-only" {{ old('status', 'Lost') === 'Lost' ? 'checked' : '' }}>
                        <span class="flex items-center justify-center rounded-md px-4 py-2 text-sm font-semibold transition peer-checked:bg-white peer-checked:text-primary peer-checked:shadow">
                            <span class="material-symbols-outlined mr-1 text-base">search</span>
                            I Lost Something
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="Found" class="peer sr-only" {{ old('status') === 'Found' ? 'checked' : '' }}>
                        <span class="flex items-center justify-center rounded-md px-4 py-2 text-sm font-semibold transition peer-checked:bg-white peer-checked:text-primary peer-checked:shadow">
                            <span class="material-symbols-outlined mr-1 text-base">inventory_2</span>
                            I Found Something
                        </span>
                    </label>
                </div>
                @error('status')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-6">
                <div>
                    <label for="item_name" class="field-label">Item Name</label>
                    <input type="text" name="item_name" id="item_name" class="field-input mt-2" placeholder="e.g. Silver MacBook Pro" value="{{ old('item_name') }}" required>
                    @error('item_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="description" class="field-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="field-input mt-2" placeholder="Include distinguishing features, colors, and condition" required>{{ old('description') }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="location" class="field-label">Location Found/Lost</label>
                    <div class="relative mt-2">
                        <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">location_on</span>
                        <input type="text" name="location" id="location" class="field-input pl-10" placeholder="e.g. Main Library, 3rd floor" value="{{ old('location') }}" required>
                    </div>
                    @error('location')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="contact" class="field-label">Contact Information</label>
                    <div class="relative mt-2">
                        <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">call</span>
                        <input type="text" name="contact" id="contact" class="field-input pl-10" placeholder="Phone or email" value="{{ old('contact') }}" required>
                    </div>
                    @error('contact')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-4 sm:flex-row">
                <a href="{{ route('items.index') }}" class="btn-soft">Cancel</a>
                <button type="submit" class="btn-primary">Submit Report</button>
            </div>
        </form>
    </div>
</section>
@endsection