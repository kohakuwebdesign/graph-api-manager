# Graph Api Manager
Facebook Graph SDKを扱いやすくするラッパーライブラリです。

# Requirement
* composer
* facebook/graph-sdk 5.7.0
* Instagramビジネスアカウント
* InstagramビジネスアカウントとFacebookページの連携
* [https://developers.facebook.com/](https://developers.facebook.com/) ページにてアプリを作成

# Installation
composerをインストールして、その後composer経由でfacebook graph-sdkをインストールしてください。

## facebook/graph-sdkをインストール

```bash
composer install
```  
これでvendorの中にfacebook/graph-sdkがインストールされます。

# Usage

## config.phpを環境に合わせて編集
ご利用の環境に合わせてconfig.phpを編集してください。
config.phpの「appsecret_proof_mode」を「true」にすることで「appsecret_proof」を使用したセキュアな接続が可能になります。  
なお、「appsecret_proof」は以下の方法で生成されます。
```php
$appsecret_proof = hash_hmac('sha256', accses_token, app_secret);
```

## Demoを実行
config.phpを編集した後、index.phpにアクセスすることでDemoを実行することが可能です。

# Note
## ディレクトリ
graph-api-manager（<-任意の名前に変更可能）   
|-- composer.json  
|-- composer.lock  
|-- graph-api-manager  
|-- index.php <- 一連の接続のdemoを確認することが可能です。  
|-- vendor  
|-- README.md  