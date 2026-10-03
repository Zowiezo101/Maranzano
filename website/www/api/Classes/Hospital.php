<?php

namespace Classes;

use PDO;

class Hospital extends Action {
    
    public const ACTION_TABLE = "hospital_session";
    
    public function route($route, $data) {
        parent::route($route, $data);
        
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
