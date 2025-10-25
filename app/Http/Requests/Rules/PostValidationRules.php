<?php

namespace App\Http\Requests\Rules;

class PostValidationRules
{
  /**
   * 投稿で共通して使うバリデーションルール
   */
  public static function base(): array
  {
    return [
      'title' => ['required', 'max:255'],
      'content' => ['nullable', 'string'],
    ];
  }

  /**
   * 作成時のみ使うバリデーションルール
   */
  public static function store(): array
  {
    return array_merge(self::base(), [
      'user_id' => ['required', 'exists:users,id'],
      'tag_ids' => ['array'],
      'tag_ids.*' => ['exists:tags,id'],
    ]);
  }

  /**
   * 更新時のみ使うバリデーションルール
   */
  public static function update(): array
  {
    return self::base();
  }
}