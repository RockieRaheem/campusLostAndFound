@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h2 style="margin-bottom: 25px;">📝 Report Lost or Found Item</h2>
    <p style="color: #7f8c8d; margin-bottom: 30px;">
        Fill in the details below to report an item. All fields marked with * are required.
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
    
    <form method="POST" action="{{ route('items.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="item_name">📦 Item Name *</label>
            <input 
                type="text" 
                name="item_name" 
                id="item_name" 
                class="form-control" 
                placeholder="e.g., Blue Samsung Phone, Student ID Card, Black Backpack"
                value="{{ old('item_name') }}"
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
                placeholder="Provide a detailed description of the item including color, brand, distinguishing features, etc."
                required
            >{{ old('description') }}</textarea>
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
                placeholder="e.g., Library 2nd Floor, Block A Room 102, Main Cafeteria"
                value="{{ old('location') }}"
                required
            >
            @error('location')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="status">🏷️ Status *</label>
            <select name="status" id="status" class="form-control" required>
                <option value="">-- Select Status --</option>
                <option value="Lost" {{ old('status') == 'Lost' ? 'selected' : '' }}>🔴 Lost - I lost this item</option>
                <option value="Found" {{ old('status') == 'Found' ? 'selected' : '' }}>🟢 Found - I found this item</option>
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
                placeholder="e.g., +256 700 123456 or email@example.com"
                value="{{ old('contact') }}"
                required
            >
            @error('contact')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div style="margin-top: 35px; display: flex; gap: 15px; flex-wrap: wrap;">
            <button type="submit" class="btn btn-success" style="padding: 14px 35px; font-size: 16px;">
                ✓ Submit Report
            </button>
            <a href="{{ route('items.index') }}" class="btn btn-primary" style="padding: 14px 35px; font-size: 16px;">
                ← Back to List
            </a>
        </div>
    </form>
</div>
@endsection
