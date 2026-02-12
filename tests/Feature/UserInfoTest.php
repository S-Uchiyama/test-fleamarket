<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInfoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function profile_shows_user_name_and_profile_image()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profile_images/test.png',
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('storage/profile_images/test.png');
    }

    /** @test */
    public function profile_shows_selling_and_purchased_items()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $sellItem = Item::create([
            'user_id' => $user->id,
            'name' => '出品商品',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/sell.jpg',
        ]);

        $buyItem = Item::create([
            'user_id' => $seller->id,
            'name' => '購入商品',
            'price' => 2000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/buy.jpg',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都',
            'building' => null,
            'status' => 'paid',
        ]);

        $sellResponse = $this->actingAs($user)->get('/mypage?page=sell');
        $sellResponse->assertStatus(200);
        $sellResponse->assertSee($sellItem->name);
        $sellResponse->assertDontSee($buyItem->name);

        $buyResponse = $this->actingAs($user)->get('/mypage?page=buy');
        $buyResponse->assertStatus(200);
        $buyResponse->assertSee($buyItem->name);
        $buyResponse->assertDontSee($sellItem->name);
    }
}
