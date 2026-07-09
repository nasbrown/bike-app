<?php 

require('includes/init.php');

$conn = require('includes/db.php');

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
   <div id="user-data">

   </div>
</section>
<?php require('includes/footer.php') ?>

