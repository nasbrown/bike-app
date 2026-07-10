<?php

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

$client->setAccessType('offline');

$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] .'/bike-app/redirect.php');

return $client;