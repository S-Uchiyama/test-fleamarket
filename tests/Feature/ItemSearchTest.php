<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function search_filters_items_by_name()
    {
        $seller = User::factory()->create();

        $match = Item::create([
            'user_id' => $seller->id,
            'name' => 'blue shirt',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/blue.jpg',
        ]);

        $noMatch = Item::create([
            'user_id' => $seller->id,
            'name' => 'red shoes',
            'price' => 2000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/red.jpg',
        ]);

        $response = $this->get('/?keyword=shirt');

        $response->assertStatus(200);
        $response->assertSee($match->name);
        $response->assertDontSee($noMatch->name);
    }

    /** @test */
    public function search_keyword_is_kept_when_switching_to_mylist()
    {
        $response = $this->get('/?keyword=test');

        $response->assertStatus(200);
        $response->assertSee('tab=mylist');
        $response->assertSee('value="test"', false);
    }

    /** @test */
    public function mylist_search_filters_liked_items()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $match = Item::create([
            'user_id' => $seller->id,
            'name' => 'coffee mill',
            'price' => 4000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/coffee.jpg',
        ]);

        $noMatch = Item::create([
            'user_id' => $seller->id,
            'name' => 'mug cup',
            'price' => 500,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/mug.jpg',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $match->id,
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $noMatch->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=coffee');

        $response->assertStatus(200);
        $response->assertSee($match->name);
        $response->assertDontSee($noMatch->name);
    }
}
