<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'tag_ids' => 'array',
            'tag_ids.*' => 'exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは255文字以内で入力してください。',
            'user_id.required' => 'ユーザーIDは必須です。',
            'user_id.exists' => '指定されたユーザーが存在しません。',
            'tag_ids.array' => 'タグは配列で指定してください。',
            'tag_ids.*.exists' => '存在しないタグが含まれています。',
        ];
    }
}
