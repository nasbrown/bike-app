<?php

class GoogleAuth{
    public static function isLoggedIn(): bool{
        return isset($_SERVER['']);
    }
}