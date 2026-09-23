<?php

namespace Classes;

use PDO;

class Database {
    private $conn;
    
    public function __construct() {
        global $servername, $db_username, 
               $db_password, $db_database;

        try {
            // First make sure we can connect to the database
            $this->conn = new PDO("mysql:host={$servername};dbname={$db_database};charset=utf8", 
                            $db_username, $db_password,
                            [PDO::ATTR_EMULATE_PREPARES => false, 
                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException) {
            throwError();
        }
    }
    
    public function __destruct() {
        $this->conn = null;
    }
    
    /**
     * Setters & Getters
     */
    
    public function getConnection() {
        return $this->conn;
    }
    
}
