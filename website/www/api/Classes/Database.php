<?php

namespace Classes;

use PDO;

class Database {
    private $conn;
    private $message;
    
    public function __construct($message = null) {
        global $servername, $db_username, 
               $db_password, $db_database;
        
        // For the error messages
        if (isset($message)) {
            $this->setMessage($message);
        } else {
            $this->message = new Message();
        }

        try {
            // First make sure we can connect to the database
            $this->conn = new PDO("mysql:host={$servername};dbname={$db_database};charset=utf8", 
                            $db_username, $db_password,
                            [PDO::ATTR_EMULATE_PREPARES => false, 
                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException) {
            $this->message->setError("auth.db_error", Message::CODE_ERROR);
        }
    }
    
    public function __destruct() {
        $this->conn = null;
    }
    
    /**
     * Setters & Getters
     */
    
    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
}
