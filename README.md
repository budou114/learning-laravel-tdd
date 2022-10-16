
## ダウンロード方法
git clone

git clone git@github.com:budou114/learning-laravel-tdd.git

git clone ブランチを指定してダウンロードする場合

git clone -b ブランチ名 https://github.com/budou114/learning-laravel-tdd.git

もしくはzipファイルでダウンロードしてください

## インストール方法
- cd learning-laravel-tdd
- composer install
- npm install
- npm run dev
- cp .env.example .env

.envファイルに下記をご利用の環境に合わせて追記してください。

- DB_TESTING_HOST=127.0.0.1
- DB_TESTING_PORT=3306
- DB_TESTING_DATABASE=learning_laravel_tdd_testing
- DB_TESTING_USERNAME=user_name
- DB_TESTING_PASSWORD=password

## テスト用データベースの設定
コマンドを実行してテーブルを作成してください。

php artisan migrate --database=mysql_testing


## テストの実行
- 全体テスト

php artisan test


## 開発環境
- Laravel 8.83.25
- php 8.1.11
- node 16.16.0
- npm  8.11.0
