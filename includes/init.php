<?php

spl_autoload_register(function($classes){
    require dirname(__DIR__) . "/classes/$classes.php"; 
});

require dirname(__DIR__) . '/config.php';