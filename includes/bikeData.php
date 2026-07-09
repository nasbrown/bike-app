<?php

header('Content-Type: application/json');

require('init.php');

$conn = require('db.php');

$data = new Bike_Info();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
$data->bikeName = $_POST['loc-name'] ?? 'Hey';

$data->bikeImage = $_FILES['image-file']['name'] ?? 'File';

$data->bikeLat = $_POST['coordinatesLat'] ?? 'Nas';

$data->bikeLong = $_POST['coordinatesLng'] ?? 'Nas';

$data->saveInfo($conn);

}

?>

<h1><?= $data->bikeLat . ", " . $data->bikeLong ?></h1>



