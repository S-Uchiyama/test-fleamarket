<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        $category = Category::create(['name' => 'カテゴリ']);

        return array_merge([
            'name' => '商品名',
            'description' => '商品説明',
            'image' => UploadedFile::fake()->create('item.png', 10, 'image/png'),
            'category_ids' => [$category->id],
            'condition' => '良好',
            'price' => 1000,
            'brand' => 'ブランド',
        ], $overrides);
    }

    /** @test */
    public function user_can_register_item()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::create(['name' => 'カテゴリ']);

        $response = $this->actingAs($user)->post('/sell', [
            'name' => '商品名',
            'description' => '商品説明',
            'image' => UploadedFile::fake()->create('item.png', 10, 'image/png'),
            'category_ids' => [$category->id],
            'condition' => '良好',
            'price' => 1000,
            'brand' => 'ブランド',
        ]);

        $item = Item::first();

        $response->assertRedirect('/item/' . $item->id);
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => '商品名',
            'price' => 1000,
        ]);
        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);
        Storage::disk('public')->assertExists(ltrim($item->img_path, '/storage/'));
    }

    /** @test */
    public function name_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', $this->validPayload([
            'name' => '',
        ]));

        $response->assertSessionHasErrors([
            'name' => '商品名を入力してください',
        ]);
    }

    /** @test */
    public function description_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', $this->validPayload([
            'description' => '',
        ]));

        $response->assertSessionHasErrors([
            'description' => '商品説明を入力してください',
        ]);
    }

    /** @test */
    public function description_must_be_255_characters_or_less()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', $this->validPayload([
            'description' => str_repeat('a', 256),
        ]));

        $response->assertSessionHasErrors([
            'description' => '商品説明は255文字以内で入力してください',
        ]);
    }

    /** @test */
    public function image_is_required()
    {
        $user = User::factory()->create();

        $payload = $this->validPayload();
        unset($payload['image']);

        $response = $this->actingAs($user)->post('/sell', $payload);

        $response->assertSessionHasErrors([
            'image' => '商品画像をアップロードしてください',
        ]);
    }

    /** @test */
    public function image_must_be_jpeg_or_png()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', $this->validPayload([
            'image' => UploadedFile::fake()->create('item.txt', 10, 'text/plain'),
        ]));

        $response->assertSessionHasErrors([
            'image' => '商品画像はjpegまたはpng形式でアップロードしてください',
        ]);
    }

    /** @test */
    public function category_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', $this->validPayload([
            'category_ids' => [],
        ]));

        $response->assertSessionHasErrors(['category_ids']);
    }

    /** @test */
    public function condition_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', $this->validPayload([
            'condition' => '',
        ]));

        $response->assertSessionHasErrors([
            'condition' => '商品の状態を選択してください',
        ]);
    }

    /** @test */
    public function price_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', $this->validPayload([
            'price' => '',
        ]));

        $response->assertSessionHasErrors([
            'price' => '商品価格を入力してください',
        ]);
    }

    /** @test */
    public function price_must_be_numeric()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', $this->validPayload([
            'price' => 'abc',
        ]));

        $response->assertSessionHasErrors([
            'price' => '商品価格は数値で入力してください',
        ]);
    }

    /** @test */
    public function price_must_be_zero_or_more()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sell', $this->validPayload([
            'price' => -1,
        ]));

        $response->assertSessionHasErrors([
            'price' => '商品価格は0円以上で入力してください',
        ]);
    }
}
