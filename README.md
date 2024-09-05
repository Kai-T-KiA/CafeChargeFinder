<p align="center"><a href="https://laravel.com" target="_blank"><img src="/public/image/Cafe charge finder2.png" width="400" alt="CafeChargeFinder logo"></a></p>

# [CafeChargeFinder](https://myapp1-ccf-6e668bad6da2.herokuapp.com/)

## アプリの概要・作成背景

コンセントのあるカフェを検索するWebアプリです

過去にカフェでPCを充電しながら作業したいと思い、大阪の梅田を40分ほど歩き回って、コンセントの使える席を見つけられなかった経験があり、「手軽にコンセントのあるカフェの場所を知れたら歩き回る必要もなくなる」と思い、制作に至りました。

## 想定している使用シチュエーション
慣れ親しんだ土地というより、土地勘がない場所での使用を想定しています。

例えば、社会人の方であれば出張先、学生であれば旅行先などで、空いた時間に「カフェで作業したい、コンセントのある場所で作業したい、どこにあるのかな？」といった状況で使用してほしいなと考えています。

## データベースに登録されている店舗について
現在、データベースに登録されている店舗は池袋と新宿にある数店舗のみです。

ユーザーが新たな店舗を登録していくことを想定しているので、ユーザーの増加に伴い、データも充実したものになるという想定で開発しました。

## 使用言語・OS・フレームワーク・開発ツール・API

使用言語はフロントエンドはHTML、CSS、JavaScript。

フレームワークはLaravel ver 10.48.16。

開発ツールは、AWSのCloud9とGitHub。

使用APIはGoogleMapsAPI。

## 機能一覧

- ログイン機能
- 検索機能
- 店舗登録機能

## 注力した機能

店舗登録機能に最も注力しました。店舗登録画面における店舗名はユーザーの入力を受け取ります。このとき、「ooカフェ xx支店」と「ooカフェxx支店」のようにユーザーによって、同じ店舗でも異なる入力になる可能性が考えられます。そのため、DB上同じ店舗のデータが別の店舗のデータとして記録される可能性があります。この問題を未然に防ぐために、ユーザーからの入力店舗名をそのままDBへの登録店舗名として使わず、APIを用いて、ユーザーの入力から、GoogleMapで表示される検索結果を入手し、その文字列を登録店舗名としてDBに登録するというアルゴリズムで実装しました。このアルゴリズムにより、登録店舗名に統一性をもたせ、DBにおけるデータの重複問題を解決しています。

## 環境構築の手順

Laravelの環境構築手順です。参考までに、私はAWSのCloud9で以下のコマンドを実行しています。

### Composerのインストール、ターミナルで実行

Composerのインストーラーをインターネットから取得し、インストーラーを実行する。
```bash
curl -sS https://getcomposer.org/installer | php
```

次に、インストーラー実行により作成されたComposer本体をコマンドとして認識されるように特定のディレクトリに移動させる。
```bash
sudo mv composer.phar /usr/local/bin/composer
```

composerコマンドとして認識されているかの確認をする。
```bash
composer
```

### Laravelプロジェクトの作成、ターミナルで実行

composerのインストール対象パッケージにlaravelインストーラーを追加する。
```bash
composer global require laravel/installer
```

次に、composerコマンドで作業ディレクトリ内にLaravelプロジェクトを作成する。
```bash
composer create-project laravel/laravel --prefer-dist blog "10.*"
```

作成されたLaravelプロジェクト作成のバージョン確認
```bash
cd 作業ディレクトリ名 //作業ディレクトリへ移動
```

```bash
php artisan --version
```
Laravel Framework 10.0以上11.0未満ならOK。

### Laravelアプリケーションの動作確認、作業ディレクトリで実行
```bash
php artisan serve --port=8080
```
上記コマンドを実行したら、http://localhost:8080 にアクセス。





## ログイン機能実装の手順

ログイン機能はLaravelのBreeze機能を用いています。以下、Breezeの実装手順です。

私がMacOSで開発していたため、Macのターミナルを使用することを想定して記述しています。

Windowsをご使用の方々は、ご利用のコマンドラインツールで以下コマンドを実行していただければと思います。

作業ディレクトリで以下のコマンドをターミナルで実行し、LaravelのBreezeをインストール。
```bash
composer require laravel_breeze --dev
```

インストールが完了したら、次のコマンドをターミナルで実行。

(*このコマンドでweb.phpファイルの内容が上書きされるので注意が必要！)
```bash
php artisan breeze:install blade
```

次に、CSSファイルが利用できるようにするため、コンパイルをターミナルで実行。
```bash
npm install && npm run build
```

URLをhttps化させて、ログインできるようにするために、app/Providers/AppServiceProvider.phpのbootメソッドに以下の内容を追記。
```php
public function boot(): void {
  \URL::forceScheme('https'); //追加
} 
```
ここまで行い、起動しているアプリケーションのURLを/loginとすると、ログイン認証画面が表示され、処理がうまく実行される。

## テストアカウント

アプリURL　https://myapp1-ccf-6e668bad6da2.herokuapp.com/

メールアドレス　24vr026m@rikkyo.ac.jp

パスワード　test8080


## 使用上の注意

データベースに登録されているデータは池袋と新宿の店舗データのみです。そのため、他の場所で使う際に、検索条件を設定すると検索結果が表示されません。動作確認をする場合は検索条件を「制限なし」にして検索をしてください。


## 今後の改修予定

- 各ページのcssに関する記述の整理によるコードの可読性向上
- GoogleMaps上でのルート表示
- 検索結果画面、検索結果詳細画面で、店舗名表示にマウスをホバーした際に、GoogleMaps上の対応するピンがMapの中央にくる仕様の実装
- Mapをクリックすると、GoogleMapが開くページ遷移機能
- ログイン画面の設定（メールアドレスでなく、ユーザー名でのログイン、ログイン画面のデザイン変更）
- 実際に使用しているデモ動画の掲載（登録データの都合上使用できる場所が限られるため）
- Mapの表示速度向上などのパフォーマンス改善
- デザインや機能改修によるユーザビリティの向上
- プロジェクトにReactを組み込むことによる開発の効率性向上
