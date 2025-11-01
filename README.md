# Learn-Laravel-API

## 1. プロジェクト概要

Laravel を使用した API 開発を学習した際のアウトプット用のリポジトリです。

ユーザー認証（Sanctum）付きの REST API を実装し、
バリデーション・リレーション・ミドルウェア・テストまでを取り組みました。

### 主な使用技術

| 技術                | バージョン |
| ------------------- | ---------- |
| `PHP`               | 8.2        |
| `laravel/framework` | 12.0       |
| `laravel/sanctum`   | 4.2        |

## 2. 作成した機能

### 1. 投稿(Post)リソース

-   投稿の CRUD（作成・取得・更新・削除）
-   User（投稿者）・Tag（タグ）とのリレーション
-   バリデーション（フォームリクエスト＋ルールクラス）
-   認証制御（Sanctum で保護）

### 2. ユーザー認証
   Laravel Sanctum を使用してトークンベース認証を実装

-   POST /api/register ユーザー登録
-   POST /api/login ログイン（トークン発行）
-   GET /api/user ログイン中ユーザー情報取得
-   POST /api/logout ログアウト（トークン無効化）

## 3. 使用した主な Laravel 機能

| 機能                       | 概要                                                                                     |
| -------------------------- | ---------------------------------------------------------------------------------------- |
| Eloquent                   | `User`・`Post`・`Tag` モデルを定義し、リレーション (`belongsTo`, `belongsToMany`) を実装 |
| バリデーション             | `FormRequest`クラスを使って整理（`StorePostRequest`, `UpdatePostRequest`）               |
| バリデーションルール共通化 | `\Http\Requests\Rules\PostValidationRules` でルールを共通化                              |
| ミドルウェア               | `auth:sanctum` ミドルウェアで保護ルートを設定                                            |
| テスト                     | `Feature/PostTest` を作成し、CRUD + バリデーション + 認証テストを実施                    |
| 認証                       | `Laravel Sanctum` を用いた API トークン認証                                              |

## 4. Laravel Sanctum の理解ポイント

| 概念                             | 概要                               |
| -------------------------------- | ---------------------------------- |
| `auth:sanctum`                   | API トークン認証を行うミドルウェア |
| `Sanctum::actingAs($user)`       | テスト時にログイン済み状態を再現   |
| `auth()->id()`                   | 現在認証中のユーザー ID を取得     |
| `createToken('api_token')`       | 新しいトークンを発行               |
| `currentAccessToken()->delete()` | ログアウト時にトークンを削除       |

## 5. 学びのまとめ

-   FormRequest でコントローラをすっきり保つ設計
-   バリデーションルールの共通化方法
-   Route::apiResource()で REST 設計が簡潔になる
-   auth()->id()でログインユーザーの情報を取得
-   Sanctum による API トークン認証の流れ
-   Sanctum::actingAs()で認証状態をテスト再現
-   Feature Test で HTTP リクエストを模擬的に検証する方法
