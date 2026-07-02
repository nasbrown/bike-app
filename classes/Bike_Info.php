<?php

class Bike_Info {
    public $bikeName = null;
    public $bikeImage = null;
    public array $errors = [];

    public function getInfo(PDO $conn): array{
        $sql = "SELECT * FROM parkingInfo";

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function validate(): bool{
        if($this->bikeName === ''){
            $errors[] = 'Must type in bike location';
        } else if($this->bikeImage === ''){
            $errors[] = 'Must take a picture of bike location';
        }

        return true;
    }

    public function saveInfo(PDO $conn){
        if($this->validate()){
            $sql = "INSERT INTO parkingInfo (location_name, image_file) " .
                "VALUES (:location_name, :image_file)";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':location_name', $this->bikeName, PDO::PARAM_STR);
            $stmt->bindValue(':image_file', $this->bikeImage, PDO::PARAM_STR);

            $stmt->execute();
        }
    }

    
}