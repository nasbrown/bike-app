<?php 

require('includes/init.php');

$conn = require('includes/db.php');

$data = new Bike_Info();

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    $data->bikeName = $_POST['loc-name']; //Can handle on this page
    $data->bikeImage = $_FILES['image-file']['name']; //Handle images seperately

    //$data->saveInfo($conn);

    //header('Location: ' . '/bike-app/'); //redirects page to prevent extra entries

}
?>

<?php require('includes/header.php') ?>
<section>
    <h1>Bike Parking Map</h1>
    <div>
        <button id="get-loc">Get Location</button>
    </div>
    <?php if(!empty($data->errors)): ?>
        <ul>
            <?php foreach($data->errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
   <div id="map">

   </div>
</section>
<?php require('includes/footer.php') ?>

