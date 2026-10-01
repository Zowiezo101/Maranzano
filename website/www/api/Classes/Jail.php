<?php

namespace Classes;

use PDO;

class Jail extends Action {
    
    public const ACTION_TABLE = "jail_session";
    
    public function getJailTime($player_id) {
        
        $conn = $this->db->getConnection();
        
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
        $time_s = $result["time"]; 
        
        // The time in hours & minutes
        $time_m = round($time_s / 60);
        $time_h = round($time_m / 60);
        
        // Get the correct unit
        return ($time_h > 1 ? $time_h . " hours" : ($time_m > 1 ? $time_m . " minutes" : $time_s . " seconds"));
    }
    
    public function sendToJail($player_id, $player_rank) {
        // The table and jail time for this rank
        $table = self::ACTION_TABLE;
        $cooldown = $this->player->getRankJailTime($player_rank);
        
        // Set the cooldown
        $this->setCooldown($player_id, $table, $cooldown);
    }
}
