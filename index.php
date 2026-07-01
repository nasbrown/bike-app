<?php 

require('includes/init.php');

$conn = require('includes/db.php');

if($_SERVER["REQUEST_METHOD"] === 'POST'){
    $name = $_POST['loc-name'];
    $image = $_POST['image-file'];

    $sql = "INSERT INTO (location_name, image) VALUES" . "(" . "$name, $image" . ")";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
}
?>

<?php require('includes/header.php') ?>
<section>
    <h1>Bike Parking Map</h1>
   <div id="map">

   </div>
</section>
<?php require('includes/footer.php') ?>

