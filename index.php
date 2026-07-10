<?php

require 'includes/init.php';

require 'includes/googleauth.php';

$client->addScope("email");

$client->addScope("profile");

$url = $client->createAuthUrl();

?>


<?php require('includes/header.php') ?>
<section>
    <div>
        <h1>Welcome! Sign In to use the Bike Parking App</h1>
        <div>
            <a href= "<?= $url ?>">Sign In To Google <img src="/bike-app/images_icons/google-symbol.svg" alt="" srcset=""></a>
        </div>
    </div>
</section>
<?php require('includes/footer.php') ?>

