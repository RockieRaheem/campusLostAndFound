@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h2>🔎 Item Details</h2>
        <a href="{{ route('items.index') }}" class="btn btn-primary">← Back to List</a>
    </div>

    <div style="display: grid; gap: 16px; margin-top: 20px;">
        <div>
            <h3 style="color: #2c3e50; margin-bottom: 6px;">Item Name</h3>
            <p style="font-size: 18px; font-weight: 600;">{{ $item->item_name }}</p>
        </div>

        <div>
            <h3 style="color: #2c3e50; margin-bottom: 6px;">Description</h3>
            <p>{{ $item->description }}</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            <div>
                <h3 style="color: #2c3e50; margin-bottom: 6px;">Location</h3>
                <p>📍 {{ $item->location }}</p>
            </div>
            <div>
                <h3 style="color: #2c3e50; margin-bottom: 6px;">Contact</h3>
                <p>📞 {{ $item->contact }}</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            <div>
                <h3 style="color: #2c3e50; margin-bottom: 6px;">Status</h3>
                <span class="status-badge {{ $item->status == 'Lost' ? 'status-lost' : '' }} {{ $item->status == 'Found' ? 'status-found' : '' }} {{ $item->status == 'Claimed' ? 'status-claimed' : '' }}">
                    {{ $item->status }}
                </span>
            </div>
            <div>
                <h3 style="color: #2c3e50; margin-bottom: 6px;">Reported Date</h3>
                <p>{{ $item->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        @if($item->claimed_at)
            <div>
                <h3 style="color: #2c3e50; margin-bottom: 6px;">Claimed Date</h3>
                <p>✅ {{ $item->claimed_at->format('d M Y, h:i A') }}</p>
            </div>
        @endif
    </div>

    <div style="margin-top: 30px; display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="{{ route('items.edit', $item) }}" class="btn btn-primary">✏️ Edit Item</a>
        @if($item->status !== 'Claimed')
            <form action="{{ route('items.claim', $item) }}" method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">✓ Mark Claimed</button>
            </form>
        @endif
    </div>
</div>
@endsection
