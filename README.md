## 環境

- PHP 8系
- Laravel 8.75
- MySQL 8.0.26
- Nginx 1.21.1
- phpMyAdmin
- Mailhog
- Stripe Checkout（カード/コンビニ）

## 環境構築手順

以下の手順で、環境構築からマイグレーションまで実行できます。

1. リポジトリをクローンする
```bash
git clone https://github.com/S-Uchiyama/test-fleamarket
```

2. ルートディレクトリへ移動する
```bash
cd test-fleamarket
```

3. Dockerコンテナを起動する
```bash
docker compose up -d --build
```

> [!WARNING]
> **Mac (M1/M2チップ) をご利用の方へ**
> ビルド時に `no matching manifest for linux/arm64/v8` というエラーが表示される場合は、`docker-compose.yml` の `mysql` 項目に `platform` 指定を追加してください。
>
> ```yaml
> mysql:
>     platform: linux/x86_64  # ←この行を追加
>     image: mysql:8.0.26
>     environment:
> ```

4. PHPコンテナに入る
```bash
docker compose exec php bash
```

5. Laravel依存パッケージをインストール
```bash
composer install
```

6. 環境変数ファイルを作成及び環境変数を追加
```bash
cp .env.example .env
```

```env
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="coachtechフリマ"

STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
```

7. アプリケーションキーを生成
```bash
php artisan key:generate
```

8. マイグレーション実行

```bash
php artisan migrate
```

9. ストレージシンボリックリンク作成（画像表示用）

```bash
php artisan storage:link
```

## ダミーデータ投入

以下を実行すると、ユーザー・カテゴリ・商品データを投入できます。

```bash
php artisan db:seed
```

### ダミーユーザー

- `test@example.com` / `password123`
- `seller1@example.com` / `password123`
- `seller2@example.com` / `password123`

## ER図
![ER図](docs/index.drawio.png)

## 画面確認URL

- アプリ: `http://localhost`
- phpMyAdmin: `http://localhost:8080`
- Mailhog: `http://localhost:8025`

## メール認証確認手順

1. 会員登録を実行
2. Mailhogで認証メールを確認
3. メール内リンクを開く
4. 商品一覧画面に遷移すれば認証完了

## 補足（Stripe）

- 購入機能は Stripe Checkout を利用
- テストキー利用時は Stripeテストモードで検証可能

