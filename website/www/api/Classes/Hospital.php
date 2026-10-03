<?php

namespace Classes;

use PDO;

class Hospital {
	private $conn;

    // Other classes
    private $user;
    private $player;
    private $parameters;
    
    // The player
    public $player_data;
    
    // Constants
    public const ACTION_TABLE = "hospital_session";
    
    public function __construct($conn) {
    	$this->conn = $conn;
        $this->parameters = new Parameters();
        $this->user = new User($conn);
        $this->player = new Player($conn);
    }
    
    public function route($route, $data) {
        // These actions need to have the cookie checked
        $auth = new Login($this->$conn);
        $auth->route("login_validate", $data);
        
        // Parse the input data
        $this->parameters->setData($data);

        // Get the player that belongs to this user
        $this->player_data = $this->player->getPlayerFromCookieData();
        
        $result = null;
        
        switch($route) {
            
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    /**
     * Other functions
     */
    
    public function getHospitalTime($player_id) {
        $conn = $this->conn;
        
        $sql = "SELECT SUM(TIMESTAMPDIFF(SECOND, created_at, expires_at)) AS time FROM " . self::ACTION_TABLE . " "
                . "WHERE player_id = :player_id";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        // The time in seconds
        $time_s = $result["time"] ? $result["time"] : 0;
        
        // The time in hours & minutes
        $time_m = round($time_s / 60);
        $time_h = round($time_m / 60);
        
        // Get the correct unit
        return ($time_h > 1 ? $time_h . " hours" : ($time_m > 1 ? $time_m . " minutes" : $time_s . " seconds"));
    }
}
