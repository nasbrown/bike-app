<?php

header('Content: application/json');

require('includes/init.php');

$conn = require('includes/db.php');

$data = new Bike_Info();

$data->bikeName = isset($_POST['loc-name']) ?? '';
$data->bikeImage = isset($_FILES['image-file']['name']) ?? '';
$data->bikeLat = isset($_POST['latitude']) ?? '';
$data->bikeLong = isset($_POST['longitude']) ?? '';

echo json_encode("Hi!");



