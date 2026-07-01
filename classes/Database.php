<?php

class Database {

    protected string $dsn;

    public function __construct(string $dsn){
        $this->dsn = $dsn;
    }

    public function connect(){
        try{
    
        $pdo = new PDO($this->dsn);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
        
        } catch(PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }
}