<?php

namespace Classes;

use PDO;

class Action {
    // Other classes
    protected $db;
    private $user;
    private $player;
    private $token;    
    private $parameters; 
    
    public function __construct() {
        $this->parameters = new Parameters();
        $this->user = new User();
        $this->player = new Player();
        $this->token = new Token();
        
        // To connect to the database
        $this->db = new Database();
    }
    
    /**
     * Misc functions
     */
    
    protected function enoughFunds($cash, $cost) {
        if (intval($cash, 10) < $cost) {
            throwError("travel.broke");
        }
    }
    
    protected function hasCooldown($player_id, $table, $cooldown) {
        $conn = $this->db->getConnection();
        
        // Retrieve the token from the token table
        $sql = "SELECT * FROM {$table} "
                . "WHERE player_id = :player_id AND expires_at >= UTC_TIMESTAMP() LIMIT 1";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        if (isset($result)) {
            // A result means that the user still has a cooldown
            // Prepare an error
            $error = getString("travel.cooldown");
            
            // Calculate the time left to wait
            $time = $this->calculateWaitingTime($result["expires_at"]);

            // Insert the name and url
            $error1 = str_replace("[cooldown]", $cooldown, $error);
            $error2 = str_replace("[time]",     $time,     $error1);
            
            // Throw the error for the user to receive
            throwError($error2);
        }
        
        return $result;
    }
    
    protected function setCooldown($player_id, $table, $cooldown) {
        $conn = $this->db->getConnection();
        
        // Create a new token
        $sql = "INSERT INTO {$table} (player_id, expires_at) "
                . "VALUES (:player_id, :expires_at)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Genereate the expire date for the verification
        $expiry_date = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                            ->modify('+' . $cooldown . ' minutes')
                            ->format('Y-m-d H:i:s');

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_STR);
        $stmt->bindValue(":expires_at", $expiry_date, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Check that the token has been properly created
        $id = $conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue is the token hasn't been created
            throwError();
        }
    }
    
    private function calculateWaitingTime($expires_at) {
        // Convert the string to a timestamp     
        $expiry_time = strtotime($expires_at);
        $current_time = time();
        
        // The difference between the two (in seconds)
        $waiting_time = $expiry_time - $current_time;
        
        // Get the waiting time in seconds
        return ceil($waiting_time / 60);
    }
}
