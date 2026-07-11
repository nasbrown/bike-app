<?php

require 'includes/init.php';

require 'includes/googleauth.php';

$client->addScope("email");

$client->addScope("profile");

$url = $client->createAuthUrl();

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