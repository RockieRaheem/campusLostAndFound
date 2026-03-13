@extends('layouts.app')

@section('content')
{{-- Search and Filter Section --}}
<div class="card search-card">
    <form method="GET" action="{{ route('items.index') }}" class="search-form">
        <div class="search-row">
            <div class="search-input-group">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control search-input" 
                    placeholder="Search by item name, description, or location..."
                    value="{{ request('search') }}"
                >
                <button type="submit" class="btn btn-primary search-btn">
                    🔍 Search
                </button>
            </div>
            <div class="filter-buttons">
                <a href="{{ route('items.index') }}" class="btn btn-filter {{ !request('status') ? 'active' : '' }}">
                    All
                </a>
                <a href="{{ route('items.index', ['status' => 'Lost', 'search' => request('search')]) }}" 
                   class="btn btn-filter btn-lost {{ request('status') == 'Lost' ? 'active' : '' }}">
                    🔴 Lost
                </a>
                <a href="{{ route('items.index', ['status' => 'Found', 'search' => request('search')]) }}" 
                   class="btn btn-filter btn-found {{ request('status') == 'Found' ? 'active' : '' }}">
                    🟢 Found
                </a>
                <a href="{{ route('items.index', ['status' => 'Claimed', 'search' => request('search')]) }}" 
                   class="btn btn-filter btn-claimed {{ request('status') == 'Claimed' ? 'active' : '' }}">
                    ✅ Claimed
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Statistics Section --}}
<div class="stats-container">
    <div class="stat-card stat-lost">
        <div class="stat-icon">🔴</div>
        <div class="stat-info">
            <h3>{{ $allItems->where('status', 'Lost')->count() }}</h3>
            <p>Lost Items</p>
        </div>
    </div>
    <div class="stat-card stat-found">
        <div class="stat-icon">🟢</div>
        <div class="stat-info">
            <h3>{{ $allItems->where('status', 'Found')->count() }}</h3>
            <p>Found Items</p>
        </div>
    </div>
    <div class="stat-card stat-claimed">
        <div class="stat-icon">✅</div>
        <div class="stat-info">
            <h3>{{ $allItems->where('status', 'Claimed')->count() }}</h3>
            <p>Claimed</p>
        </div>
    </div>
    <div class="stat-card stat-total">
        <div class="stat-icon">📊</div>
        <div class="stat-info">
            <h3>{{ $allItems->count() }}</h3>
            <p>Total Reports</p>
        </div>
    </div>
</div>

{{-- Items List Section --}}
<div class="card">
    <div class="card-header">
        <h2>📋 Reported Items</h2>
        <a href="{{ route('items.create') }}" class="btn btn-success">
            + Report New Item
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif
    
    @if(request('search') || request('status'))
        <div class="filter-info">
            Showing 
            @if(request('status'))
                <span class="filter-tag">{{ request('status') }}</span>
            @endif
            @if(request('search'))
                items matching "<strong>{{ request('search') }}</strong>"
            @endif
            ({{ $items->count() }} results)
            <a href="{{ route('items.index') }}" class="clear-filters">Clear filters</a>
        </div>
    @endif
    
    @if($items->count() > 0)
        <div class="items-grid">
            @foreach($items as $item)
            <div class="item-card">
                <div class="item-header">
                    <span class="status-badge 
                        {{ $item->status == 'Lost' ? 'status-lost' : '' }}
                        {{ $item->status == 'Found' ? 'status-found' : '' }}
                        {{ $item->status == 'Claimed' ? 'status-claimed' : '' }}">
                        {{ $item->status }}
                    </span>
                    <span class="item-date">{{ $item->created_at->format('d M Y') }}</span>
                </div>
                <h3 class="item-name">{{ $item->item_name }}</h3>
                <p class="item-description">{{ Str::limit($item->description, 100) }}</p>
                <div class="item-details">
                    <div class="detail">
                        <span class="detail-icon">📍</span>
                        <span>{{ $item->location }}</span>
                    </div>
                    <div class="detail">
                        <span class="detail-icon">📞</span>
                        <span>{{ $item->contact }}</span>
                    </div>
                    @if($item->status === 'Claimed' && $item->claimed_at)
                        <div class="detail">
                            <span class="detail-icon">🗓️</span>
                            <span>Claimed on {{ $item->claimed_at->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>
                <div class="item-actions">
                    <a href="{{ route('items.show', $item) }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">
                        👁️ View
                    </a>
                    <a href="{{ route('items.edit', $item) }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">
                        ✏️ Edit
                    </a>
                    @if($item->status != 'Claimed')
                        <form action="{{ route('items.claim', $item) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-claim" onclick="return confirm('Mark this item as claimed?')">
                                ✓ Mark Claimed
                            </button>
                        </form>
                    @else
                        <span class="claimed-text">Item Claimed ✓</span>
                    @endif
                    <form action="{{ route('items.destroy', $item) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this item?')">
                            🗑️ Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h3>No items found</h3>
            <p>
                @if(request('search') || request('status'))
                    Try adjusting your search or filter criteria.
                @else
                    Be the first to report a lost or found item!
                @endif
            </p>
            <a href="{{ route('items.create') }}" class="btn btn-primary">Report an Item</a>
        </div>
    @endif
</div>
@endsection
