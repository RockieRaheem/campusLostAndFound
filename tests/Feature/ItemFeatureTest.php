<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
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
        $user = User::factory()->create();
        $this->actingAs($user);

        $itemData = [
            'item_name' => 'Blue Water Bottle',
            'description' => 'Found near the library entrance.',
            'location' => 'Main Library',
            'status' => 'Found',
        ];

        $response = $this->post(route('items.store'), $itemData);

        $response->assertRedirect(route('items.index'));
        $this->assertDatabaseHas('items', ['item_name' => 'Blue Water Bottle']);
    }

    public function test_items_are_soft_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $item = Item::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(route('items.destroy', $item->id));

        $response->assertRedirect(route('items.index'));

        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
            'deleted_at' => null
        ]);

        $this->assertSoftDeleted('items', ['id' => $item->id]);
    }

    public function test_cannot_submit_item_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Negative test: Missing all required fields
        $response = $this->post(route('items.store'), []);

        $response->assertSessionHasErrors(['item_name', 'description', 'location', 'status']);
        $this->assertDatabaseCount('items', 0);
    }

    public function test_rate_limiting_on_item_submission()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $itemData = [
            'item_name' => 'Valid Item',
            'description' => 'Valid description here long enough',
            'location' => 'Library',
            'status' => 'Lost',
        ];

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('items.store'), $itemData);
        }

        $response = $this->post(route('items.store'), $itemData);
        $response->assertStatus(429); // 429 Too Many Requests
    }
}