<?php

header('Content-Type: application/json');

require 'init.php';

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

$client->setAccessType('offline');

$client->setAccessToken($_SESSION['access_token']);

$conn = require('db.php');

$coordArr = Bike_Info::getInfo($conn);

if(!empty($coordArr)){
    echo json_encode($coordArr);
} else{
    echo json_encode([
        'staus' => 'error'
    ]);
}