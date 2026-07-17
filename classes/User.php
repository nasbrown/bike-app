<?php

class User
{
    public string $firstName;
    public string $lastName;
    public string $userEmail;
    public string $accessToken;
    public string $refreshToken;

    public function saveCredentials(PDO $conn)
    {
        $sql = "INSERT INTO users(first_name, last_name, email, access_token, refresh_token)" .
            "VALUES(:first_name, :last_name, :email, :access_token, :refresh_token)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':first_name', $this->firstName, PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $this->lastName, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->userEmail, PDO::PARAM_STR);
        $stmt->bindValue(':access_token', $this->accessToken, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->refreshToken, PDO::PARAM_STR);
     

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

        return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    }

    public function updateTokens(PDO $conn, int $id){
            $sql = "UPDATE users
            SET access_token = :access_token,
                 refresh_token = :refresh_token
                 " . "WHERE id = $id";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':access_token', $this->accessToken, PDO::PARAM_STR);
            $stmt->bindValue(':refresh_token', $this->refreshToken, PDO::PARAM_STR);

            $stmt->execute();
    }

    public function getRefreshToken(PDO $conn, int $id){
        $sql = "SELECT refresh_token FROM users WHERE id = $id";

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['refresh_token'];
    }
}
