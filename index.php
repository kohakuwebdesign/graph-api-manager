<?php
use GraphApiManager\Authorization;
use GraphApiManager\FacebookPage;
use GraphApiManager\InstagramAccount;
use GraphApiManager\InstagramHashtag;
use GraphApiManager\InstagramPostEmbed;
use GraphApiManager\InstagramUserMetadata;

require_once 'vendor/autoload.php';

$config = include './config.php';
$authorization = new Authorization();
$authorize = $authorization->authorize($_GET['code']);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>Facebook Graph Api Manger</title>
</head>
<body>

<div class="container py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-8">
            <?php if($config['appsecret_proof_mode'] == true): ?>
            <div class="card">
                <div class="card-header">
                    appsecret_proofを使ったセキュアなアクセス方法
                </div>
                <div class="card-body">
                    <p>
                        <a target="_blank" href="https://developers.facebook.com/">FACEBOOK For Developers</a>ページにて、マイアプリ > サイドバーの「設定」>「詳細設定」> 「App Secretをオンにする」をオンに設定。<br>
                        appsecret_proofを使わない通常のアクセスをする場合はconfig.phpのappsecret_proofをfalseに設定してください。<br>
                    </p>
                    <p>
                        また、appsecret_proofを使ったセキュアな方法でApiを使用する際は、全てのエンドポイントへのアクセスに「appsecret_poof」パラメータを付加しなければなりません。<br>
                        appsecret_proofの生成方法は以下の通りです<br>
                        <strong>$appsecret_proof = hash_hmac('sha256', accses_token, app_secret);</strong>
                    </p>
                </div>
            </div>

            <?php else: ?>

            <div class="card">
                <div class="card-header">
                    appsecret_proofを使わない通常のアクセス
                </div>
                <div class="card-body">
                    <p>
                    <a target="_blank" href="https://developers.facebook.com/">FACEBOOK For Developers</a>ページにて、マイアプリ>サイドバーの「設定」>「詳細設定」> 「App Secretをオンにする」を<strong>オフ</strong>に設定。<br>
                    appsecret_proofを使いセキュアなアクセスをする場合はconfig.phpのappsecret_proofをtrueに設定してください。<br>
                    </p>
                </div>
            </div>

            <?php endif; ?>

            <div class="card mt-4">
                <div class="card-header">
                    <a target="_blank" href="https://developers.facebook.com/">FACEBOOK For Developers</a>ページでの設定「機能・アクセス」
                </div>
                <div class="card-body">
                    pages_show_list<br>
                    instagram_basic<br>
                    instagram_manage_insights<br>
                    public_profile
                </div>
            </div>

            <?php if(isset($authorize['login_url'])) : ?>
                <a class="btn btn-info mt-3" href="<?php echo $authorize['login_url']; ?>">Login with facebook &raquo;</a>
            <?php else : ?>

        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <h2>Authorization</h2>
                <h3>Response: </h3>
                <div class="card">
                    <div class="card-body">
                    <?php
                        echo '<pre>';
                        print_r($authorize);
                        echo '</pre>';
                        $longLivedAccessToken = $authorize['long_lived_accesstoken'];
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-8">
            <h2>Faceook Page</h2>
            <div class="card">
                <div class="card-header">
                    備考
                </div>
                <div class="card-body">
                    認証で取得した「long_lived_accesstoken」をFacebookページを取得するエンドポイントへ渡すと期限なし（無期限）の「access_token」が返ってきます。<br>
                    今後、その他のエンドポイントへのアクセスにこの無期限の「access_token」を使用することで、さまざまなデータを取得することが可能となります。<br>
                </div>
            </div>

            <?php $facebookPage = new FacebookPage($authorize['long_lived_accesstoken']); ?>

            <h3 class="mt-3">Endpoint: </h3>
            <p class="text-break"><?php echo $facebookPage->getEndpoint(); ?></p>
            <h3>Response: </h3>
            <div class="card">
                <div class="card-body">
                    <pre><?php print_r($facebookPage->getResponse()); ?></pre>
                    <?php
                    $newLongLivedAccessToken = $facebookPage->getResponse()['data'][0]['access_token'];
                    $facebookPageId = $facebookPage->getResponse()['data'][0]['id'];
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <h2>Instagram Business Account Id</h2>
                <?php
                    $instagramAccount = new InstagramAccount($newLongLivedAccessToken, $facebookPageId);
                    $instagramBusinessAccountId = $instagramAccount->getResponse()['instagram_business_account']['id'];
                ?>
                <h3>Endpoint: </h3>
                <p class="text-break">
                    <?php echo $instagramAccount->getEndpoint(); ?>
                </p>
                <h3>Response: </h3>
                <div class="card">
                    <div class="card-body">
                        <pre><?php print_r($instagramAccount->getResponse()); ?></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-8">
            <h2>Instagram User Meta Data</h2>
            <?php
            $instagramUserMetaData = new InstagramUserMetadata($newLongLivedAccessToken, $instagramBusinessAccountId, $config['instagram_user_name']);
            ?>
            <h3>Endpoint: </h3>
            <p class="text-break">
                <?php echo $instagramUserMetaData->getEndpoint(); ?>
            </p>
            <h3>Response: </h3>
            <div class="card">
                <div class="card-body">
                    <pre><?php print_r($instagramUserMetaData->getResponse()); ?></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <h2>Instagram Hashtag Id</h2>
                <?php
                    $instagramHashTag = new InstagramHashtag($longLivedAccessToken, $instagramBusinessAccountId);
                    $array = $instagramHashTag->getHashTagId($config['instagram_hash_tag']);
                    echo '<h3 class="mt-3">Endpoint: </h3><p class="text-break">' . $instagramHashTag->getEndpoint() . '</p>';
                ?>
                <h3>Response: </h3>
                <div class="card">
                    <div class="card-body">
                        <pre><?php print_r($array); ?></pre>
                        <?php $instagramHashTagId = $array['data'][0]['id']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-8">
            <h2>Instagram Hashtag</h2>
            <?php
                $posts = $instagramHashTag->getPostsDataFromHashtagId($instagramHashTagId, 2);
            ?>
            <h3>Endpoint: </h3>
            <p class="text-break">
                <?php echo $instagramHashTag->getEndpoint(); ?>
            </p>
            <h3>Response: </h3>
            <div class="card">
                <div class="card-body">
                    <pre><?php print_r($posts); ?></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <h2>Instagram Embed</h2>
                <h3>Response: </h3>
                <div class="card">
                    <div class="card-body">
                        <?php
                        foreach ($posts['data'] as $post){
                            $instagramPostEmbed = new InstagramPostEmbed($longLivedAccessToken, $post['permalink']);
                            echo '<pre>' . print_r($instagramPostEmbed->getResponse()) . '</pre><hr>';
                            echo '<div class="mt-3">';
                            echo $instagramPostEmbed->getResponse()['html'];
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
</body>
</html>