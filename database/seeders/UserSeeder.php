<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'テストユーザー',
                'password' => Hash::make('password123'),
                'postcode' => '123-4567',
                'address' => '東京都渋谷区1-1-1',
                'building' => 'テストビル',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'seller1@example.com'],
            [
                'name' => '出品者ユーザー1',
                'password' => Hash::make('password123'),
                'postcode' => '987-6543',
                'address' => '大阪府大阪市1-1-1',
                'building' => 'サンプルビル',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'seller2@example.com'],
            [
                'name' => '出品者ユーザー2',
                'password' => Hash::make('password123'),
                'postcode' => '555-0000',
                'address' => '愛知県名古屋市1-1-1',
                'building' => 'テストタワー',
                'email_verified_at' => now(),
            ]
        );
    }
}
