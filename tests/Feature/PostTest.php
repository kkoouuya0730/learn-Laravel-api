<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

use App\Models\Post;
use App\Models\User;
use App\Models\Tag;

class PostTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function 投稿一覧を取得できる()
    {
        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');
        $response->assertStatus(200)->assertJsonCount(3);
    }

    #[Test]
    public function 投稿を作成できる()
    {
        $user = User::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $response = $this->postJson('/api/posts', [
            'title' => 'テスト投稿',
            'content' => '内容',
            'user_id' => $user->id,
            'tag_ids' => $tags->pluck('id')->toArray(),
        ]);

        $response->assertStatus(201)->assertJsonFragment(['title' => 'テスト投稿']);
    }

    #[Test]
    public function 投稿詳細を取得できる()
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)->assertJsonFragment(['id' => $post->id]);
    }

    #[Test]
    public function 投稿を更新できる()
    {
        $post = Post::factory()->create();

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => '更新後のタイトル',
            'content' => '更新された内容'
        ]);

        $response->assertStatus(200)->assertJsonFragment(['title'=> '更新後のタイトル', 'content' => '更新された内容']);
    }

    #[Test]
    public function 投稿を削除できる()
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

}
