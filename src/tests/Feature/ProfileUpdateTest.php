<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function edit_page_shows_current_values()
    {
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'postcode' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
            'profile_image' => 'profile_images/old.png',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('value="テスト太郎"', false);
        $response->assertSee('value="123-4567"', false);
        $response->assertSee('value="東京都"', false);
        $response->assertSee('value="テストビル"', false);
        $response->assertSee('storage/profile_images/old.png');
    }

    /** @test */
    public function user_can_update_profile()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/mypage/profile', [
            'name' => '更新ユーザー',
            'postcode' => '987-6543',
            'address' => '大阪府',
            'building' => '更新ビル',
            'profile_image' => UploadedFile::fake()->create('avatar.png', 10, 'image/png'),
        ]);

        $response->assertRedirect('/mypage/profile');

        $user->refresh();

        $this->assertSame('更新ユーザー', $user->name);
        $this->assertSame('987-6543', $user->postcode);
        $this->assertSame('大阪府', $user->address);
        $this->assertSame('更新ビル', $user->building);
        $this->assertNotNull($user->profile_image);
        Storage::disk('public')->assertExists($user->profile_image);
    }
}
