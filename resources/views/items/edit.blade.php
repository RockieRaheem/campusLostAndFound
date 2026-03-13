@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="margin-bottom: 25px;">✏️ Edit Item Report</h2>
    <p style="color: #7f8c8d; margin-bottom: 30px;">
        Update the details below and save your changes.
    </p>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('items.update', $item) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="item_name">📦 Item Name *</label>
            <input
                type="text"
                name="item_name"
                id="item_name"
                class="form-control"
                value="{{ old('item_name', $item->item_name) }}"
                required
            >
            @error('item_name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">📄 Description *</label>
            <textarea
                name="description"
                id="description"
                class="form-control"
                required
            >{{ old('description', $item->description) }}</textarea>
            @error('description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="location">📍 Location *</label>
            <input
                type="text"
                name="location"
                id="location"
                class="form-control"
                value="{{ old('location', $item->location) }}"
                required
            >
            @error('location')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="status">🏷️ Status *</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Lost" {{ old('status', $item->status) == 'Lost' ? 'selected' : '' }}>🔴 Lost</option>
                <option value="Found" {{ old('status', $item->status) == 'Found' ? 'selected' : '' }}>🟢 Found</option>
                <option value="Claimed" {{ old('status', $item->status) == 'Claimed' ? 'selected' : '' }}>✅ Claimed</option>
            </select>
            @error('status')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="contact">📞 Contact Information *</label>
            <input
                type="text"
                name="contact"
                id="contact"
                class="form-control"
                value="{{ old('contact', $item->contact) }}"
                required
            >
            @error('contact')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-top: 35px; display: flex; gap: 15px; flex-wrap: wrap;">
            <button type="submit" class="btn btn-success" style="padding: 14px 35px; font-size: 16px;">
                ✓ Save Changes
            </button>
            <a href="{{ route('items.index') }}" class="btn btn-primary" style="padding: 14px 35px; font-size: 16px;">
                ← Back to List
            </a>
        </div>
    </form>
</div>
@endsection
