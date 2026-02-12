<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_sees_only_liked_items_in_mylist()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $likedItem = Item::create([
            'user_id' => $seller->id,
            'name' => 'いいね商品',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/like.jpg',
        ]);

        $otherItem = Item::create([
            'user_id' => $seller->id,
            'name' => 'その他商品',
            'price' => 2000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/other.jpg',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee($likedItem->name);
        $response->assertDontSee($otherItem->name);
    }

    /** @test */
    public function mylist_shows_sold_label_for_purchased_items()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '購入済み商品',
            'price' => 3000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/sold.jpg',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都',
            'building' => null,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function guest_sees_empty_mylist()
    {
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '非表示商品',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/hidden.jpg',
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee($item->name);
        $response->assertDontSee('商品がありません。');
    }
}
