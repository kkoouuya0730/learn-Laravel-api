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

    #[Test]
    public function タイトルが空の場合はバリデーションエラーになる()
    {
        $user = User::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $response = $this->postJson('/api/posts', [
            'title' => '',
            'content' => '内容',
            'user_id' => $user->id,
            'tag_ids' => $tags->pluck('id')->toArray(),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title'])
            ->assertJsonFragment(['title' => ['タイトルは必須です。']]);
    }

    #[Test]
    public function 存在しないユーザーIDの場合はバリデーションエラーになる()
    {
        $tags = Tag::factory()->count(2)->create();

        $response = $this->postJson('/api/posts', [
            'title' => 'テストタイトル',
            'content' => '内容',
            'user_id' => 9999,
            'tag_ids' => $tags->pluck('id')->toArray(),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id'])
            ->assertJsonFragment(['user_id' => ['指定されたユーザーが存在しません。']]);

    }

    #[Test]
    public function ユーザーIDが指定されていない場合はバリデーションエラーになる()
    {
        $tags = Tag::factory()->count(2)->create();

        $response = $this->postJson('/api/posts', [
            'title' => 'テストタイトル',
            'content' => '内容',
            'user_id' => null,
            'tag_ids' => $tags->pluck('id')->toArray(),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id'])
            ->assertJsonFragment(['user_id' => ['ユーザーIDは必須です。']]);
    }

    #[Test]
    public function タグが配列でない場合にバリデーションエラーになる()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/posts', [
            'title' => 'テストタイトル',
            'content' => '内容',
            'user_id' => $user->id,
            'tag_ids' => 'not-array',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tag_ids'])
            ->assertJsonFragment(['tag_ids' => ['タグは配列で指定してください。']]);
    }

    #[Test]
    public function 存在しないタグがある場合にバリデーションエラーになる()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/posts', [
            'title' => 'テストタイトル',
            'content' => '内容',
            'user_id' => $user->id,
            'tag_ids' => [9999],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tag_ids.0'])
            ->assertJsonFragment(['tag_ids.0' => ['存在しないタグが含まれています。']]);
    }

    #[Test]
    public function PUTでタイトルが空の場合はバリデーションエラーになる()
    {
        $post = Post::factory()->create();

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => '',
            'content' => '内容',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title'])
            ->assertJsonFragment(['title' => ['タイトルは必須です。']]);
    }
}
