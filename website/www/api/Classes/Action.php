<?php

namespace Classes;

use PDO;

class Action {
    // Other classes
    protected $db;
    protected $user;
    protected $player;
    protected $token;    
    protected $parameters; 
    
    // The player
    public $player_data;
    
    public function __construct() {
        $this->parameters = new Parameters();
        $this->user = new User();
        $this->player = new Player();
        $this->token = new Token();
        
        // To connect to the database
        $this->db = new Database();
    }
    
    protected function route($route, $data) {
        // These actions need to have the cookie checked
        $auth = new Login();
        $auth->route("login_validate", $data);
        
        // Parse the input data
        $this->parameters->setData($data);
        
        // The username that is given via the cookie
        $user_name = $this->parameters->getUser(from_cookie: true);
        
        // Get the user using the username
        $user = $this->user->getUser(name: $user_name);   
        
        if (!isset($user)) {
            throwError("player.data.error");
        }
        
        // Get the user_id
        $user_id = $user["id"];

        // Get the player that belongs to this user
        $this->player_data = $this->player->getPlayer($user_id);
        
        // Make sure the player isn't in jail or in the hospital
        $this->hasCooldown($this->player_data["id"], 
                Jail::ACTION_TABLE, 
                null, 
                "jail.cooldown");
        $this->hasCooldown($this->player_data["id"], 
                Hospital::ACTION_TABLE, 
                null, 
                "hospital.cooldown");
        
        return $route;
    }
    
    /**
     * Misc functions
     */
    
    protected function enoughFunds($cash, $cost, $error) {
        if (intval($cash, 10) < $cost) {
            throwError($error);
        }
    }
    
    protected function hasCooldown($player_id, $table, $cooldown, $error) {
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
            $error = getString($error);
            
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
