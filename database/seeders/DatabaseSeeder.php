<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;

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
        User::factory()
            ->count(5)
            ->has(Post::factory()->count(2), 'posts') // 'posts' は User モデルの relation 名
            ->create();
    }
}
