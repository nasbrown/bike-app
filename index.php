<?php

require 'includes/init.php';

$url = '';

if(GoogleAuth::isLoggedIn()){
    Url::redirect('/bike-app/admin');
} else{
    $client = new Google\Client;

    $client->setClientId(BIKE_CLIENT_ID);

    $client->setClientSecret(BIKE_CLIENT_SECRET);

    $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] .'/bike-app/redirect.php');

    $client->setAccessType('offline');

    $client->setPrompt('consent');

    $client->addScope("email");

    $client->addScope("profile");

    $url = $client->createAuthUrl();
}

?>


<?php require('includes/header.php') ?>
<section class="sign-in">
    <div class="sign-in-container">
        <h1>Welcome! Sign In to use the Bike Parking App</h1>
        <div class="link-container">
            <a href="<?= $url ?>">Sign In To Google</a>
            <div class="img-container">
                <img src="/bike-app/images_icons/google-symbol.svg" alt="" srcset="">
            </div>
        </div>
    </div>
</section>
<?php require('includes/footer.php') ?>