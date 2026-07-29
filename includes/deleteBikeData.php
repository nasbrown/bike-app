<?php

header("Content-Type: application/json");

require("init.php");

$conn = require("db.php");

$data = new Bike_Info();

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

$client->setAccessToken($_SESSION['access_token']);

$string = '';

if($_SERVER['REQUEST_METHOD'] === "POST"){

    $data->bikeId = $_POST[''];

   $data->deleteBikeLoc($conn, '');
}

 echo json_encode([
        "status" => $string
    ]);
