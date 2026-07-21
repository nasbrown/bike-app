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

if($_SERVER['REQUEST_METHOD'] === 'POST'){

$userIdAArr = User::getId($conn, $userInfo->email)['id'];

$data->bikeUserId = $userIdAArr;
    
$data->bikeName = htmlspecialchars($_POST['loc-name']);

$data->bikeImage = htmlspecialchars($_FILES['image-file']['name']);

$files->validateAndUploadImage($conn, $data);

$data->bikeLat = $_POST['coordinatesLat'] ?? 'Nas';

$data->bikeLong = $_POST['coordinatesLng'] ?? 'Nas';

$data->saveInfo($conn);

}

?>

<h1><?= $data->bikeLat . ", " . $data->bikeLong ?></h1>



