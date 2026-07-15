<?php

class GoogleAuth{
    public static function isLoggedIn(): bool{
        return isset($_SESSION['access_token']) && $_SESSION['access_token'];
    }

    public static function logoutUser(): void {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }
}