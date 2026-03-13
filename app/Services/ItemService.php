<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ItemService
{
    /**
     * Build filtered item collection and full collection for dashboard stats.
     *
     * @return array{0: LengthAwarePaginator<int, Item>, 1: array{total:int,lost:int,found:int,claimed:int}}
     */
    public function getItemsForDashboard(Request $request): array
    {
        $query = Item::query();

        if ($request->filled('search')) {
            $searchTerm = $request->string('search')->toString();
            $query->where(function ($q) use ($searchTerm) {
                $q->where('item_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('location', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $items = $query->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        $statusCounts = Item::query()
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $stats = [
            'total' => Item::count(),
            'lost' => (int) ($statusCounts['Lost'] ?? 0),
            'found' => (int) ($statusCounts['Found'] ?? 0),
            'claimed' => (int) ($statusCounts['Claimed'] ?? 0),
        ];

        return [$items, $stats];
    }

    /**
     * Create a new item report.
     */
    public function createItem(array $data): Item
    {
        return Item::create($data);
    }

    /**
     * Update an existing item report.
     */
    public function updateItem(Item $item, array $data): bool
    {
        if (($data['status'] ?? null) === 'Claimed') {
            $data['claimed_at'] = $item->claimed_at ?? now();
        }

        if (($data['status'] ?? null) !== 'Claimed') {
            $data['claimed_at'] = null;
        }

        return $item->update($data);
    }

    /**
     * Mark an item as claimed.
     */
    public function markItemClaimed(Item $item): bool
    {
        $item->status = 'Claimed';
        $item->claimed_at = now();

        return $item->save();
    }

    /**
     * Delete an item report.
     */
    public function deleteItem(Item $item): ?bool
    {
        return $item->delete();
    }
}
