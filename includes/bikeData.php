<?php

header('Content-Type: application/json');

require('init.php');

$conn = require('db.php');

$data = new Bike_Info();

$files = new File();

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

$client->setAccessToken($_SESSION['access_token']);

$oauth = new Google\Service\Oauth2($client);

$userInfo = $oauth->userinfo->get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data->bikeName = htmlspecialchars($_POST['loc-name']);

    $data->bikeLat = $_POST['coordinatesLat'];

    $data->bikeLong = $_POST['coordinatesLng'];

    $userIdAArr = User::getId($conn, $userInfo->email)['id'];

    $data->bikeUserId = $userIdAArr;

    $files->validateAndUploadImage($conn, $data); 
}

?>

<h1><?= $data->bikeLat . ", " . $data->bikeLong ?></h1>