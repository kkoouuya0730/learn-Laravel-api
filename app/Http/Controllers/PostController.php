<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

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
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'tag_ids' => 'array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

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
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable|string',
        ]);

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
