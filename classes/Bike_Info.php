<?php

class Bike_Info
{
    public $bikeName = null;
    public $bikeImage = null;
    public $bikeLat = null;
    public $bikeLong = null;
    public $bikeImageID;
    public string $bikeUserId = '';
    public array $userCoordArr = [];
    public array $errors = [];

    public static function getInfo(PDO $conn): array
    {
        $sql = "SELECT * FROM parkingInfo";

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function validate(): bool
    {
        if ($this->bikeName === '') {
            $this->errors[] = 'Must type in bike location';
        } else if ($this->bikeImage === '') {
            $this->errors[] = 'Must take a picture of bike location';
        }

        return true;
    }

    public function saveInfo(PDO $conn, string $filename)
    {
        if ($this->validate()) {
            $sql = "INSERT INTO parkingInfo (location_name, image_file, coord_lat, coord_lng, user_id, image_id) " .
                "VALUES (:location_name, :image_file, :coord_lat, :coord_lng, :user_id, :image_id)";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':location_name', $this->bikeName, PDO::PARAM_STR);
            $stmt->bindValue(':image_file', $filename, PDO::PARAM_STR);
            $stmt->bindValue(':coord_lat', $this->bikeLat, PDO::PARAM_STR);
            $stmt->bindValue(':coord_lng', $this->bikeLong, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $this->bikeUserId, PDO::PARAM_INT);
            $stmt->bindValue(":image_id", $this->generateImageID(), PDO::PARAM_STR);

            $stmt->execute();
        }
    }

    public static function getCoordMarkerData(PDO $conn, int $id): array
    {
        $sql = "SELECT location_name, image_file, image_id, coord_lat, coord_lng FROM parkingInfo WHERE user_id = $id";

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setImageFile(PDO $conn, string $filename)
    {
        $sql = "UPDATE parkingInfo
                SET image_file = $filename
                WHERE image_id = :image_id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":image_id", $this->bikeImageID, PDO::PARAM_STR);
        $stmt->bindValue(":image_file", $filename, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getImage(PDO $conn, string $id){
        $sql = "SELECT image_file FROM parkingInfo WHERE coord_lat = :coord_lat AND user_id = $id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":coord_lat", $this->bikeLat, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getImageId(PDO $conn, string $id){
        $sql = "SELECT image_id FROM parkingInfo WHERE coord_lat = :coord_lat AND user_id = $id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":coord_lat", $this->bikeLat, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function generateImageID(): string{
        
    $data = random_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        
    }
}
