<?php

namespace App\Http\Controllers;

use App\Models\Post;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;


class PostController extends Controller
{
    /**
     * 投稿一覧
     */
    public function index()
    {
        $posts = Post::with(['user', 'tags'])->get();
        return response()->json($posts);
    }

    /**
     * 投稿作成
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        // カレントユーザーのidを設定
        $data['user_id'] = auth()->id();

        $post = Post::create($data);

        if(!empty(($data['tag_ids']))) {
            $post->tags()->attach($data['tag_ids']);
        }

        $post->load(['user', 'tags']);

        return response()->json($post, 201);
    }

    /**
     * 投稿詳細
     */
    public function show(Post $post)
    {
        $post->load(['user', 'tags']);
        return response()->json($post);
    }

    /**
     * 投稿更新
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        $post->update($data);
        return response()->json($post);
    }

    /**
     * 投稿削除
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(null, 204);
    }
}
