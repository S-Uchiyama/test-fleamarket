<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_post_comment()
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

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comments', [
            'body' => 'コメントです',
        ]);

        $response->assertRedirect('/item/' . $item->id);
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => 'コメントです',
        ]);
    }

    /** @test */
    public function guest_cannot_post_comment()
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

        $response = $this->post('/item/' . $item->id . '/comments', [
            'body' => 'コメントです',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('comments', 0);
    }

    /** @test */
    public function comment_is_required()
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

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comments', [
            'body' => '',
        ]);

        $response->assertSessionHasErrors([
            'body' => '商品コメントを入力してください',
        ]);
    }

    /** @test */
    public function comment_must_be_255_characters_or_less()
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

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comments', [
            'body' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors([
            'body' => '商品コメントは255文字以内で入力してください',
        ]);
    }
}
