<?php

namespace Classes;

use PDO;

class Hospital extends Action {
    
    public const ACTION_TABLE = "hospital_session";
    
    public function route($route, $data) {
        parent::route($route, $data);
        
        $result = null;
        
        switch($route) {
            case "hospital_time":
                $result = $this->getHospitalSession();
                break;
            
            case "hospital_bill":
                $result = $this->payHospitalBill();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function getHospitalSession() {
        $conn = $this->conn;
        
        // The player data
        $player = $this->player_data;
        
        // The SQL query
        $sql = "SELECT TIMESTAMPDIFF(SECOND, CURRENT_TIMESTAMP, expires_at) AS time FROM " . self::ACTION_TABLE . " "
                . "WHERE player_id = :player_id AND expires_at >= UTC_TIMESTAMP() LIMIT 1";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":player_id", $player["id"], PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $this->formatResults($result);
    }
    
    private function payHospitalBill() {

        // Bill depends on the rank of the player and their full health
        $full_hp = $this->player->getRankHealth($this->player_data["rank"]);

        // Every 10 HP is €1
        $bill = $full_hp / 10;
        
        // Check current player cash        
        $this->enoughFunds($this->player_data["bank"], $bill, "hospital.broke");
        
        // Update the hospital cooldown to an expired state
        $this->updateHospitalCooldown(-1);

        // Update the player bank
        $update = [
            "bank" => $this->player_data["bank"] - $bill
        ];
        $this->player->updatePlayer($this->player_data["id"], $update);

        return;
    }
    
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
    
    private function updateHospitalCooldown($cooldown) {
        $conn = $this->conn;
        
        // The table
        $table = self::ACTION_TABLE;
        
        // Get the cooldown we're trying to pay the bill for
        $cooldown_id = $this->hasCooldown($this->player_data["id"], $table, 0);
        
        // Create a new cooldown
        $sql = "UPDATE {$table} SET expires_at = :expires_at "
                . "WHERE id = :id";
        
        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Generate the expire date for the cooldown
        $expiry_date = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                            ->modify('+' . $cooldown . 'seconds')
                            ->format('Y-m-d H:i:s');

        // Bind the parameter
        $stmt->bindValue(":expires_at", $expiry_date, PDO::PARAM_STR);
        $stmt->bindValue(":id", $cooldown_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();
    }
    
    private function formatResults($result) {
        
        // The formatted array
        $formatted_result = [];
        
        // Format the results of each row
        if(isset($result)) {
            // Time in seconds
            $time_s = $result["time"];

            // The cooldown in minutes & seconds
            $time_m = floor($time_s / 60);
            $time_s_left = $time_s % 60;
            
            $time = getString("hospital.time");
            $time1 = str_replace("[minute]", $time_m, $time);
            $time2 = str_replace("[second]", $time_s_left, $time1);

            // Bill depends on the rank of the player and their full health
            $full_hp = $this->player->getRankHealth($this->player_data["rank"]);
            
            // Every 10 HP is €1
            $amount = $full_hp / 10;
                
            $formatted_result = [
                "time" => $time2,
                "amount" => $time_s > 0 ? $amount : 0
            ];
        } else {
            // There is currently no hospital session
            $formatted_result = [
                "time" => getString("hospital.none"),
                "amount" => 0,
            ];
        }
        
        return $formatted_result;
    }
    
    public function healPlayer() {

        // Get the player that belongs to this user
        $player = $this->player->getPlayerFromCookieData();

        // The full health a player can have for their rank
        $full_hp = $this->player->getRankHealth($player["rank"]);
            
        if ($player["health"] !== $full_hp) {

            // Update the player health to max health
            $update = ["health" => $full_hp];
            $this->player->updatePlayer($player["id"], $update);
        }
    }
}
