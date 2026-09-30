<?php

namespace Classes;

use PDO;

class Crime extends Action {
    
    private const ACTION_TABLE = "crime_session";
    private const CRIME_BIKE = 1;
    private const CRIME_CAR = 2;
    private const CRIME_STORE = 3;
    private const CRIME_KILL = 4;
    private const BIKE_COOLDOWN = 50;
    private const BIKE_XP = 450;
    private const BIKES = [
        "cheap"     => ["worth" => 20,   "img" => "../img/bikes/20.jpg",   "chance_start" => 0],
        "regular"   => ["worth" => 80,   "img" => "../img/bikes/80.png",   "chance_start" => 40],
        "expensive" => ["worth" => 500,  "img" => "../img/bikes/500.png",  "chance_start" => 70],
        "win"       => ["worth" => 1500, "img" => "../img/bikes/1500.jpg", "chance_start" => 90],
    ];
    
    public function route($route, $data) {
        parent::route($route, $data);
        
        $result = null;
        
        switch($route) {
            case "crime_bike":
                $result = $this->stealBike();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function stealBike() {
        // The current player
        $player = $this->player_data;
        
        // Is the player still on a cooldown?
        $this->hasCrimeCooldown($player["id"], 
                self::CRIME_BIKE, 
                self::ACTION_TABLE, 
                self::BIKE_COOLDOWN,
                "bike.cooldown");
        
        // The chance to succeed
        $rate = $this->player->route("player_chance_bike", $this->parameters->getData());
        
        // The RNG to create a chance to succeed or not
        $gamble = mt_rand(0, 10000) / 100;
        
        // Has the player succeeded or not?
        $success = ($rate >= $gamble);
        
        // The bike to steal
        $bike = null;
        
        if ($success) {
            // We succeeded!
            $bike_gamble = mt_rand(0, 10000) / 100;
            
            if (self::BIKES["win"]["chance_start"] <= $bike_gamble) {
                $bike = self::BIKES["win"];
            } else if (self::BIKES["expensive"]["chance_start"] <= $bike_gamble) {
                $bike = self::BIKES["expensive"];
            } else if (self::BIKES["regular"]["chance_start"] <= $bike_gamble) {
                $bike = self::BIKES["regular"];
            } else {
                $bike = self::BIKES["cheap"];
            }
            
            // TODO: Add bike to garage
        } else {
            // TODO: Add player to jail
        }
        
        // Add the cooldown: TODO
        $this->setCrimeCooldown($player["id"], 
                self::CRIME_BIKE, 
                $success, 
                self::ACTION_TABLE, 
                self::BIKE_COOLDOWN);
            
        // TODO: Give the player XP
        $this->giveXP($player, self::BIKE_XP, $success);
        
        return $bike;
    }
    
    
    /**
     * Misc functions
     */
    
    private function hasCrimeCooldown($player_id, $crime_id, $table, $cooldown, $error) {
        $conn = $this->db->getConnection();
        
        // Retrieve the token from the token table
        $sql = "SELECT * FROM {$table} "
                . "WHERE player_id = :player_id AND crime_id = :crime_id AND expires_at >= UTC_TIMESTAMP() LIMIT 1";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);  
        $stmt->bindValue(":crime_id",  $crime_id,  PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        // Return the cooldown to the user
        $this->ifCooldown($result, $cooldown, $error);
        
        return $result;
    }
    
    private function setCrimeCooldown($player_id, $crime_id, $succeeded, $table, $cooldown) {
        $conn = $this->db->getConnection();
        
        // Create a new token
        $sql = "INSERT INTO {$table} (player_id, crime_id, expires_at, succeeded) "
                . "VALUES (:player_id, :crime_id, :expires_at, :succeeded)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Genereate the expire date for the verification
        $expiry_date = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                            ->modify('+' . $cooldown . ' seconds')
                            ->format('Y-m-d H:i:s');

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":crime_id",  $crime_id, PDO::PARAM_INT);
        $stmt->bindValue(":expires_at", $expiry_date, PDO::PARAM_STR);
        $stmt->bindValue(":succeeded",  $succeeded ? 1 : 0, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Check that the cooldown has been properly created
        $id = $conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue is the cooldown hasn't been created
            throwError();
        }
    }
    
    protected function calculateCooldown($cooldown) {        
        // The difference between the two (in seconds)
        $cooldown_m = round($cooldown / 60);
        
        // Get the waiting time in seconds
        return $cooldown_m > 1 ? ["mintues", $cooldown_m] : ["seconds", $cooldown];
    }
    
    protected function calculateWaitingTime($expires_at) {
        // Convert the string to a timestamp     
        $expiry_time = strtotime($expires_at);
        $current_time = time();
        
        // The difference between the two (in seconds)
        $waiting_time = $expiry_time - $current_time;
        $waiting_time_m = ceil($waiting_time / 60);
        
        // Get the waiting time in seconds
        return $waiting_time_m > 1 ? ["minute", $waiting_time_m] : ["second", $waiting_time];
    }
    
    private function giveXP($player, $xp, $success) {
        // With success, the player gets 100% of the XP. With a fail this is only 20%
        $earned_xp = $success ? $xp : round($xp / 5);
        
        // Get the current progress and rank of the player
        $current_rank = $player["rank"];
        $current_xp = $player["progress"];
        $current_health = $player["health"];
        
        // Get the XP ceiling for the current rank
        $xp_ceiling = $this->player->getRankXP($current_rank);
        
        // Add the earned XP to the current XP
        $new_xp = $current_xp + $earned_xp;
        
        // Is this a rank-up?
        $new_rank = $current_rank;
        $new_health = $current_health;
        if ($new_xp >= $xp_ceiling) {
            // The XP starts clean with the bit that's left after rank-up
            $new_xp = $new_xp - $xp_ceiling;
            $new_rank = $current_rank + 1;
            
            // Update the health as well
            $new_health = $this->player->getRankHealth($new_rank);
        }
        
        // Update the rank and the XP
        $update = [
            "rank" => $new_rank,
            "progress" => $new_xp,
            "health" => $new_health,
        ];
        $this->player->updatePlayer($player["id"], $update);
    }
    
    public function getCrimes($player_id) {
        // TODO:
        return [
            "bikes" => 1,
            "cars" => 4,
            "stores" => 3,
            "kills" => 2
        ];
    }
}
