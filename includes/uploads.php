<?php

header("Content-Type: application/json");

require('init.php');

$conn = require('db.php');

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

$client->setAccessToken($_SESSION['access_token']);

$oauth = new Google\Service\Oauth2($client);

$userInfo = $oauth->userinfo->get();

//Get uploaded image from the database and display in the popup

$data = new Bike_Info();

$user = new User();



?>


