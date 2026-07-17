<?php

require('../includes/init.php');

if (!GoogleAuth::isLoggedIn()) {
    exit("You do not have access to this page, please sign in <a href='" . "/bike-app/index.php'" . ">here</a>");
}
$data = new Bike_Info();

$user = new User();

$conn = require('../includes/db.php');

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

$client->setAccessType('offline');

$client->setAccessToken($_SESSION['access_token']);

$oauth = new Google\Service\Oauth2($client);

$userInfo = $oauth->userinfo->get();

?>

<?php require('../includes/header.php') ?>
<section>
    <h1>Bike Parking Map</h1>
    <h2>Welcome <?= $userInfo->givenName ?>!</h2>
    <div>
        <button id="get-loc" onclick="getLocationPermissionState()">Get Location</button>
    </div>
    <div>
        <a href="/bike-app/logout.php">Logout</a>
    </div>
    <?php if (!empty($data->errors)): ?>
        <ul>
            <?php foreach ($data->errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <div id="map">

    </div>
    <div id="user-data">

    </div>
</section>
<?php require('../includes/footer.php') ?>