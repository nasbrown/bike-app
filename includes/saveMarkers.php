<?php

header('Content-Type: application/json');

require 'init.php';

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

$client->setAccessType('offline');

$client->setAccessToken($_SESSION['access_token']);

$oauth = new Google\Service\Oauth2($client);

$userInfo = $oauth->userinfo->get();

$conn = require('db.php');

$userId = User::getId($conn, $userInfo->email)['id'];

$coordArr = Bike_Info::getCoordMarkerData($conn, $userId);

if(!empty($coordArr)){
    echo json_encode($coordArr);
} else{
    echo json_encode([
        'staus' => 'error'
    ]);
}

?>