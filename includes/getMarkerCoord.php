<?php

header('Content-Type: application/json');

require 'init.php';

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

$client->setAccessType('offline');

$client->setAccessToken($_SESSION['access_token']);

$conn = require('db.php');

$data = new Bike_Info();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $json = file_get_contents(htmlspecialchars("php://input"));

    $clientData = json_decode($json, true);

    $data->bikeImageID = $clientData['image_id'];

    $coordData = $data->getCoordinatesFromImgId($conn);
}

if(!empty($coordData)){
    echo json_encode($coordData);
} else{
    echo json_encode(["status" => "error"]);
}