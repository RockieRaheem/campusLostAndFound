<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\ItemPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemPhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    private function tinyPngFile(string $name): UploadedFile
    {
        // 1x1 transparent PNG.
        $pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO7+f0sAAAAASUVORK5CYII=');

        return UploadedFile::fake()->createWithContent($name, $pngData);
    }

    public function test_it_stores_item_with_uploaded_photos(): void
    {
        Storage::fake('public');

        $response = $this->post(route('items.store'), [
            'item_name' => 'Blue Bottle',
            'description' => 'Blue metal bottle with white logo near handle.',
            'location' => 'Main Library',
            'status' => 'Found',
            'contact' => 'test@example.com',
            'photos' => [
                $this->tinyPngFile('photo-1.png'),
                $this->tinyPngFile('photo-2.png'),
            ],
        ]);

        $response->assertRedirect(route('items.index'));

        $item = Item::query()->first();

        $this->assertNotNull($item);
        $this->assertCount(2, $item->photos);

        foreach ($item->photos as $photo) {
            Storage::disk('public')->assertExists($photo->path);
        }
    }

    public function test_it_updates_item_photos_by_removing_and_adding(): void
    {
        Storage::fake('public');

        $item = Item::create([
            'item_name' => 'Laptop',
            'description' => 'Silver laptop found in study room near power outlet.',
            'location' => 'Science Block',
            'status' => 'Found',
            'contact' => 'owner@example.com',
        ]);

        $oldPath = $this->tinyPngFile('old.png')->store('item-photos', 'public');

        $oldPhoto = ItemPhoto::create([
            'item_id' => $item->id,
            'path' => $oldPath,
            'sort_order' => 1,
        ]);

        $response = $this->put(route('items.update', $item), [
            'item_name' => 'Laptop Updated',
            'description' => 'Silver laptop with updated details after office check.',
            'location' => 'Science Block',
            'status' => 'Found',
            'contact' => 'owner@example.com',
            'remove_photo_ids' => [$oldPhoto->id],
            'photos' => [
                $this->tinyPngFile('new.png'),
            ],
        ]);

        $response->assertRedirect(route('items.index'));

        Storage::disk('public')->assertMissing($oldPath);

        $item->refresh()->load('photos');
        $this->assertCount(1, $item->photos);

        $newPhotoPath = $item->photos->first()->path;
        Storage::disk('public')->assertExists($newPhotoPath);
    }
}
