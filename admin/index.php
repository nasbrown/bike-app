<?php 

require('../includes/init.php');

$name = '';

if(!GoogleAuth::isLoggedIn()){
    exit("You do not have access to this page, please sign in <a href='" . "/bike-app/index.php'" . ">here</a>");
}

$conn = require('../includes/db.php');

?>

<?php require('../includes/header.php') ?>
<section>
    <h1>Bike Parking Map</h1>
    <h2>Welcome, <?= $name ?>!</h2>
    <div>
        <button id="get-loc">Get Location</button>
    </div>
    <div>
        <a href="/bike-app/logout.php">Logout</a>
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
<?php require('../includes/footer.php') ?>

