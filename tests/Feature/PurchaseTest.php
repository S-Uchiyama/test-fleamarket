<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_purchase_item()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postcode' => '123-4567',
            'address' => '東京都',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '商品',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/item.jpg',
        ]);

        $response = $this->actingAs($buyer)->post('/purchase/' . $item->id, [
            'payment_method' => 'card',
            'postcode' => $buyer->postcode,
            'address' => $buyer->address,
            'building' => null,
            'shipping_address' => 'profile',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => $buyer->postcode,
            'address' => $buyer->address,
            'status' => 'paid',
        ]);

        $this->assertNotNull($item->fresh()->sold_at);
    }

    /** @test */
    public function purchased_item_is_listed_in_profile_buy_tab()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postcode' => '123-4567',
            'address' => '東京都',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '購入商品',
            'price' => 2000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/item.jpg',
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => $buyer->postcode,
            'address' => $buyer->address,
            'building' => null,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($buyer)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee($item->name);
    }

    /** @test */
    public function purchased_item_is_marked_as_sold_on_item_list()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postcode' => '123-4567',
            'address' => '東京都',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '購入済み商品',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/item.jpg',
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => $buyer->postcode,
            'address' => $buyer->address,
            'building' => null,
            'status' => 'paid',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function payment_method_is_reflected_in_summary()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postcode' => '123-4567',
            'address' => '東京都',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '商品',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/item.jpg',
        ]);

        $response = $this->actingAs($buyer)
            ->from('/purchase/' . $item->id)
            ->post('/purchase/' . $item->id, [
                'payment_method' => 'convenience_store',
                'postcode' => $buyer->postcode,
                'address' => $buyer->address,
                'building' => null,
                'shipping_address' => 'profile',
            ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'convenience_store',
        ]);
    }

    /** @test */
    public function shipping_address_is_updated_and_used_for_purchase()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'postcode' => '111-1111',
            'address' => '旧住所',
            'building' => '旧建物',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '商品',
            'price' => 1000,
            'brand' => null,
            'description' => '説明',
            'condition' => '良好',
            'img_path' => '/images/item.jpg',
        ]);

        $this->actingAs($buyer)->post('/purchase/address/' . $item->id, [
            'postcode' => '123-4567',
            'address' => '新住所',
            'building' => '新建物',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $buyer->id,
            'postcode' => '123-4567',
            'address' => '新住所',
            'building' => '新建物',
        ]);

        $this->actingAs($buyer)->post('/purchase/' . $item->id, [
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '新住所',
            'building' => '新建物',
            'shipping_address' => 'profile',
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'postcode' => '123-4567',
            'address' => '新住所',
            'building' => '新建物',
        ]);
    }

    /** @test */
    public function postcode_is_required_on_address_update()
    {
        $user = User::factory()->create();
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

        $response = $this->actingAs($user)->post('/purchase/address/' . $item->id, [
            'postcode' => '',
            'address' => '東京都',
            'building' => '',
        ]);

        $response->assertSessionHasErrors([
            'postcode' => '郵便番号を入力してください',
        ]);
    }

    /** @test */
    public function postcode_must_have_hyphen_on_address_update()
    {
        $user = User::factory()->create();
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

        $response = $this->actingAs($user)->post('/purchase/address/' . $item->id, [
            'postcode' => '1234567',
            'address' => '東京都',
            'building' => '',
        ]);

        $response->assertSessionHasErrors([
            'postcode' => '郵便番号はハイフンあり8文字で入力してください',
        ]);
    }

    /** @test */
    public function address_is_required_on_address_update()
    {
        $user = User::factory()->create();
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

        $response = $this->actingAs($user)->post('/purchase/address/' . $item->id, [
            'postcode' => '123-4567',
            'address' => '',
            'building' => '',
        ]);

        $response->assertSessionHasErrors([
            'address' => '住所を入力してください',
        ]);
    }
}
