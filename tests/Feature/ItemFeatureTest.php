<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\ItemPhoto;

class ItemFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_dashboard_loads_correctly(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Campus Lost & Found');
    }

    public function test_user_can_submit_a_new_item()
    {
        $itemData = [
            'item_name' => 'Blue Water Bottle',
            'description' => 'Found near the library entrance.',
            'location' => 'Main Library',
            'status' => 'Found',
            'contact' => 'john@campus.edu',
        ];

        $response = $this->post(route('items.store'), $itemData);

        $response->assertRedirect(route('items.index'));
        $this->assertDatabaseHas('items', ['item_name' => 'Blue Water Bottle']);
    }

    public function test_items_are_soft_deleted()
    {
        $item = Item::factory()->create();

        $response = $this->delete(route('items.destroy', $item->id));

        $response->assertRedirect(route('items.index'));
        
        // Assert it is no longer shown in standard queries
        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
            'deleted_at' => null
        ]);

        // Assert it still physically exists in the database
        $this->assertSoftDeleted('items', ['id' => $item->id]);
    }
}
