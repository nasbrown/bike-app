<?php

require('includes/init.php');

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

if(isset($_SESSION['access_token'])){
            $client->revokeToken($_SESSION['access_token']);
        }

GoogleAuth::logoutUser();

Url::redirect('/bike-app/index.php');