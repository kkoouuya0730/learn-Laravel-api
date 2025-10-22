<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Tag;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()->count(3)->create();
        $tags = Tag::factory()->count(5)->create();
        
        // 各ユーザーに投稿を作成
        $users->each(function ($user) use ($tags) {
            $posts = Post::factory()->count(2)->create(['user_id' => $user->id]);
            // 各投稿にランダムタグ付与
            $posts->each(fn($post) => $post->tags()->attach($tags->random(2)));
        });
    }
}
