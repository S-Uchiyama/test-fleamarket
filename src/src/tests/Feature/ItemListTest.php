<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_see_all_items()
    {
        $seller = User::factory()->create();

        $itemA = Item::create([
            'user_id' => $seller->id,
            'name' => '商品A',
            'price' => 1000,
            'brand' => null,
            'description' => '説明A',
            'condition' => '良好',
            'img_path' => '/images/a.jpg',
        ]);

        $itemB = Item::create([
            'user_id' => $seller->id,
            'name' => '商品B',
            'price' => 2000,
            'brand' => null,
            'description' => '説明B',
            'condition' => '良好',
            'img_path' => '/images/b.jpg',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($itemA->name);
        $response->assertSee($itemB->name);
    }

    /** @test */
    public function sold_items_show_sold_label()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '商品C',
            'price' => 3000,
            'brand' => null,
            'description' => '説明C',
            'condition' => '良好',
            'img_path' => '/images/c.jpg',
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都',
            'building' => null,
            'status' => 'paid',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function authenticated_user_does_not_see_own_items()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $ownItem = Item::create([
            'user_id' => $user->id,
            'name' => '自分の商品',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/own.jpg',
        ]);

        $otherItem = Item::create([
            'user_id' => $other->id,
            'name' => '他人の商品',
            'price' => 2000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/other.jpg',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee($ownItem->name);
        $response->assertSee($otherItem->name);
    }
}
