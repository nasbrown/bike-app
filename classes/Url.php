<?php

class Url {
    public static function redirect(string $path): void{
        
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? $protocal = 'https' : $protocal = 'http';

        header('Location: ' . "$protocal://" . $_SERVER['HTTP_HOST'] . "$path");
        exit;
    }
}