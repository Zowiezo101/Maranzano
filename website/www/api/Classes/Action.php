<?php

namespace Classes;

use PDO;

class Action {
    // Other classes
    protected $user;
    protected $player;
    protected $token;    
    protected $parameters; 
    
    // The database connection
    protected $conn;
    
    // The player
    public $player_data;
    
    // Whitelisted actions when in Jail or Hospital
    private $white_list = [
        "jail_city",
        
    ];
    
    public function __construct($conn) {
        $this->conn = $conn;
        
        $this->parameters = new Parameters();
        $this->user = new User($conn);
        $this->player = new Player($conn);
        $this->token = new Token($conn);
    }
    
    protected function route($route, $data) {
        // These actions need to have the cookie checked
        $auth = new Login($this->conn);
        $auth->route("login_validate", $data);
        
        // Parse the input data
        $this->parameters->setData($data);

        // Get the player that belongs to this user
        $this->player_data = $this->player->getPlayerFromCookieData();
        
        // Make sure the player isn't in jail or in the hospital
        if (!in_array($route, $this->white_list)) {
            $this->hasCooldown($this->player_data["id"], 
                    Jail::ACTION_TABLE, 
                    null, 
                    "jail.cooldown");
            
            $this->hasCooldown($this->player_data["id"], 
                    Hospital::ACTION_TABLE, 
                    null, 
                    "hospital.cooldown");
        }
        
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
    
    protected function hasCooldown($player_id, $table, $cooldown, $error = null) {
        $conn = $this->conn;
        
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
        
        // Return the cooldown to the user
        if (isset($error)) {
            $this->ifCooldown($result, $cooldown, $error);
        }
        
        return $result ? $result["id"] : null;
    }
    
    protected function setCooldown($player_id, $table, $cooldown) {
        $conn = $this->conn;
        
        // Create a new cooldown
        $sql = "INSERT INTO {$table} (player_id, expires_at) "
                . "VALUES (:player_id, :expires_at)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Generate the expire date for the cooldown
        $expiry_date = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                            ->modify('+' . $cooldown . 'seconds')
                            ->format('Y-m-d H:i:s');

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":expires_at", $expiry_date, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Check that the cooldown has been properly created
        $id = $conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue is the cooldown hasn't been created
            throwError();
        }
    }
    
    protected function ifCooldown($result, $cooldown, $error) {
        
        if (isset($result)) {
            // A result means that the user still has a cooldown
            // Prepare an error
            $error = getString($error);
            
            if (isset($cooldown)) {
                // Get the correct unit for this cooldown
                $cooldown1 = $this->calculateCooldown($cooldown);

                // Insert the values
                $error1 = str_replace("[cooldown]", $cooldown1[1], $error);
                $error2 = str_replace("[units]",    $cooldown1[0], $error1);
            } else {
                $error2 = $error;
            }
            
            // Calculate the time (and unit of time) left to wait
            $time = $this->calculateWaitingTime($result["expires_at"]);

            // Insert the values
            $error3 = str_replace("[time]",     $time[1], $error2);
            $error4 = str_replace("[unit]",     $time[0], $error3);
            
            // Throw the error for the user to receive
            throwError($error4);
        }
    }
    
    protected function isValidAmount($amount, $min = null, $max = null) {
        if (isset($min) && ($amount < $min)) {
            // Throw the error for the user to receive
            throwError("data.amount.invalid");
        } else if (isset($max) && ($amount > $max)) {
            // Throw the error for the user to receive
            throwError("data.amount.invalid");
        }
    }
    
    protected function calculateCooldown($cooldown) {        
        // The cooldown in hours
        $cooldown_m = round($cooldown / 60);
        $cooldown_h = round($cooldown_m / 60);
        
        // Get the waiting time in seconds
        return ($cooldown_h > 1 ? ["hours", $cooldown_h] : ($cooldown_m > 1 ? ["minutes", $cooldown_m] : ["seconds", $cooldown]));
    }
    
    protected function calculateWaitingTime($expires_at) {
        // Timezone we're using is UTC
        $timezone = new \DateTimeZone('UTC');
        
        // A datetime object with UTC time set to now
        $current_date = new \DateTimeImmutable('now', $timezone);
        $expiry_date = new \DateTimeImmutable($expires_at, $timezone);
        
        // Convert the string to a timestamp     
        $expiry_time = $expiry_date->getTimestamp();
        $current_time = $current_date->getTimestamp();
        
        // The difference between the two (in seconds)
        $waiting_time = $expiry_time - $current_time;
        $waiting_time_m = ceil($waiting_time / 60);
        $waiting_time_h = round($waiting_time_m / 60);
        
        // Get the waiting time in seconds
        return ($waiting_time_h > 1 ? ["hours", $waiting_time_h] : ($waiting_time_m > 1 ? ["minutes", $waiting_time_m] : ["seconds", $waiting_time]));
    }
}
