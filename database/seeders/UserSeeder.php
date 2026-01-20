<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'User',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => '123456789',
            'email_verified_at' => now(),
            'image' => 'https://ui-avatars.com/api/?name=User',
            'bio' => 'User',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::factory()->count(10)->create();
    }
}
