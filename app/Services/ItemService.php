<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ItemService
{
    /**
     * Build filtered item collection and full collection for dashboard stats.
     *
     * @return array{0: Collection<int, Item>, 1: Collection<int, Item>}
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

        $items = $query->orderBy('created_at', 'desc')->get();
        $allItems = Item::all();

        return [$items, $allItems];
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
