<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ItemService
{
    /**
     * Build filtered item collection and full collection for dashboard stats.
     *
     * @return array{0: LengthAwarePaginator<int, Item>, 1: array{total:int,lost:int,found:int,claimed:int}}
     */
    public function getItemsForDashboard(Request $request): array
    {
        $query = Item::query()->with('photos');

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
        return DB::transaction(function () use ($data): Item {
            /** @var array<int, UploadedFile> $photos */
            $photos = $data['photos'] ?? [];
            unset($data['photos']);

            $item = Item::create($data);

            $this->storePhotosForItem($item, $photos);

            return $item->load('photos');
        });
    }

    /**
     * Update an existing item report.
     */
    public function updateItem(Item $item, array $data): bool
    {
        return DB::transaction(function () use ($item, $data): bool {
            // Lock the row to prevent concurrent updates
            $lockedItem = Item::where('id', $item->id)->lockForUpdate()->first();

            /** @var array<int, UploadedFile> $photos */
            $photos = $data['photos'] ?? [];
            $removePhotoIds = $data['remove_photo_ids'] ?? [];

            unset($data['photos'], $data['remove_photo_ids']);

            if (($data['status'] ?? null) === 'Claimed') {
                $data['claimed_at'] = $lockedItem->claimed_at ?? now();
            }

            if (($data['status'] ?? null) !== 'Claimed') {
                $data['claimed_at'] = null;
            }

            $updated = $lockedItem->update($data);

            if (!empty($removePhotoIds)) {
                $this->removePhotosByIds($lockedItem, $removePhotoIds);
            }

            $this->storePhotosForItem($lockedItem, $photos);

            return $updated;
        });
    }

    /**
     * Mark an item as claimed.
     */
    public function markItemClaimed(Item $item): bool
    {
        return DB::transaction(function () use ($item) {
            // Lock the row to prevent concurrent claims
            $lockedItem = Item::where('id', $item->id)->lockForUpdate()->first();
            
            $lockedItem->status = 'Claimed';
            $lockedItem->claimed_at = now();

            return $lockedItem->save();
        });
    }

    /**
     * Delete an item report.
     */
    public function deleteItem(Item $item): ?bool
    {
        // When using Soft Deletes, we should NOT physically remove the files from disk.
        // Instead, we just delete the item (and optionally cascade soft delete to photos).
        $item->photos()->delete(); // Soft delete photos
        
        return $item->delete(); // Soft delete item
    }

    /**
     * @param array<int, UploadedFile> $photos
     */
    private function storePhotosForItem(Item $item, array $photos): void
    {
        if (empty($photos)) {
            return;
        }

        $nextSortOrder = (int) $item->photos()->max('sort_order') + 1;
        $manager = new ImageManager(new Driver());

        foreach ($photos as $photo) {
            if (!$photo instanceof UploadedFile) {
                continue;
            }

            // Optimize image: remove EXIF data, scale down, format to webp uniformly
            $image = $manager->read($photo->getRealPath());
            $image->scaleDown(1200, 1200); // Prevents users from uploading massive photos
            $encoded = $image->toWebp(80); // Encode to webp at 80% quality

            $filename = 'item-photos/' . uniqid() . '.webp';
            Storage::disk('public')->put($filename, (string) $encoded);

            $item->photos()->create([
                'path' => $filename,
                'sort_order' => $nextSortOrder,
            ]);

            $nextSortOrder++;
        }
    }

    /**
     * @param array<int, int|string> $photoIds
     */
    private function removePhotosByIds(Item $item, array $photoIds): void
    {
        $photoIds = array_map('intval', $photoIds);

        $photosToDelete = $item->photos()->whereIn('id', $photoIds)->get();

        foreach ($photosToDelete as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }
    }
}
