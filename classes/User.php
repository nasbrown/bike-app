<?php

class User
{
    public string $firstName;
    public string $lastName;
    public string $userEmail;

    public function saveCredentials(PDO $conn)
    {
        $sql = "INSERT INTO users(first_name, last_name, email)" .
            "VALUES(:first_name, :last_name, :email)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':first_name', $this->firstName, PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $this->lastName, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->userEmail, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function doesEmailExist(PDO $conn, string $email): bool
    {
        $sql = "SELECT * FROM users WHERE email = '$email'";

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        if (!empty($stmt->fetch(PDO::FETCH_ASSOC))) {
            return false;
        } else {
            return true;
        }
    }

    public static function getId(PDO $conn, string $email){
        $sql = "SELECT id FROM users WHERE email = '$email'";

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTokens(PDO $conn, string $email, $acc_token, $refresh_token, int $id){
        if($this->doesEmailExist($conn, $email)){
            $sql = "INSERT INTO users(access_token, refresh_token) " .
                    "VALUES(:access_token, :refresh_token) WHERE id = $id";
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':access_token', $acc_token, PDO::PARAM_STR);
            $stmt->bindValue(':refresh_token', $refresh_token, PDO::PARAM_STR);

            $stmt->execute();
        } 
    }
}
