<?php

class Bike_Info {
    public string $bikeName = '';

    public function getInfo(PDO $conn){
        $sql = "SELECT * FROM parkingInfo";

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}