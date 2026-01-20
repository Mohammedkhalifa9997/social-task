<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $post = Post::create([
                    'user_id' => $user->id,
                    'content' => fake()->paragraph(),
                ]);

                $imageCount = fake()->numberBetween(0, 3);

                for ($j = 0; $j < $imageCount; $j++) {
                    PostImage::create([
                        'post_id' => $post->id,
                        'image' => 'defaults/user.png',
                    ]);
                }
            }
        }
    }
}

