<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_view_item_detail_with_required_info()
    {
        $seller = User::factory()->create();
        $commentUser = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '腕時計',
            'price' => 15000,
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => '良好',
            'img_path' => '/images/watch.jpg',
        ]);

        $categoryA = Category::create(['name' => 'ファッション']);
        $categoryB = Category::create(['name' => 'アクセサリー']);
        $item->categories()->attach([$categoryA->id, $categoryB->id]);

        Like::create(['user_id' => $commentUser->id, 'item_id' => $item->id]);
        Like::create(['user_id' => $seller->id, 'item_id' => $item->id]);

        Comment::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'body' => 'とても良い商品です',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee('¥15,000');
        $response->assertSee($item->description);
        $response->assertSee($item->condition);
        $response->assertSee($categoryA->name);
        $response->assertSee($categoryB->name);
        $response->assertSee('item__statusCount">2', false);
        $response->assertSee('（1）');
        $response->assertSee($commentUser->name);
        $response->assertSee('とても良い商品です');
    }

    /** @test */
    public function purchase_link_is_visible_for_guest_when_item_is_available()
    {
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '商品',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/item.jpg',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('購入手続きへ');
    }

    /** @test */
    public function purchase_link_points_to_purchase_page()
    {
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '商品',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/item.jpg',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('/purchase/' . $item->id);
    }
}
