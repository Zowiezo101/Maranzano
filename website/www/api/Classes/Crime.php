<?php

namespace Classes;

use PDO;

class Crime extends Action {
    
    // Other classes
    private $garage;
    private $jail;
    
    private const ACTION_TABLE = "crime_session";
    
    // Crime types
    public const CRIME_BIKE = 1;
    public const CRIME_CAR = 2;
    private const CRIME_STORE = 3;
    private const CRIME_KILL = 4;
    
    // Bike types
    private const BIKE_CHEAP = 1;
    private const BIKE_MID = 2;
    private const BIKE_HIGH = 3;
    private const BIKE_WIN = 4;
    
    // Bike stuff
    private const BIKE_COOLDOWN = 50;
    private const BIKE_XP = 450;
    private const BIKES = [
        self::BIKE_CHEAP => ["id" => self::BIKE_CHEAP, "worth" => 20,   "img" => "../img/bikes/20.jpg",   "chance_start" => 0],
        self::BIKE_MID   => ["id" => self::BIKE_MID, "worth" => 80,   "img" => "../img/bikes/80.png",   "chance_start" => 40],
        self::BIKE_HIGH  => ["id" => self::BIKE_HIGH, "worth" => 500,  "img" => "../img/bikes/500.png",  "chance_start" => 70],
        self::BIKE_WIN   => ["id" => self::BIKE_WIN, "worth" => 1500, "img" => "../img/bikes/1500.jpg", "chance_start" => 90],
    ];
    
    public function __construct() {
        parent::__construct();
        
        // Contains the garage functions
        $this->garage = new Garage();
        $this->jail = new Jail();
    }
    
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
            
            if (self::BIKES[self::BIKE_WIN]["chance_start"] <= $bike_gamble) {
                $bike = self::BIKES[self::BIKE_WIN];
            } else if (self::BIKES[self::BIKE_HIGH]["chance_start"] <= $bike_gamble) {
                $bike = self::BIKES[self::BIKE_HIGH];
            } else if (self::BIKES[self::BIKE_MID]["chance_start"] <= $bike_gamble) {
                $bike = self::BIKES[self::BIKE_MID];
            } else {
                $bike = self::BIKES[self::BIKE_CHEAP];
            }
            
            // Add bike to garage
            $this->garage->addBike($player["id"], $bike);
        } else {
            // Add player to jail
            $this->jail->sendToJail($player);
        }
        
        // Add the cooldown
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
    
    private function getStolenBikes($player_id) {
        $conn = $this->db->getConnection();
        
        // Search for the amount of successfull bike steals
        $sql = "SELECT * FROM " . self::ACTION_TABLE . "
                    WHERE crime_id = :crime_id AND player_id = :player_id AND succeeded = 1";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":crime_id", self::CRIME_BIKE, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Check that the cooldown has been properly created
        $amount = $stmt->rowCount();
        
        return $amount;
    }
    
    private function getStolenCars($player_id) {
        $conn = $this->db->getConnection();
        
        // Search for the amount of successfull bike steals
        $sql = "SELECT * FROM " . self::ACTION_TABLE . "
                    WHERE crime_id = :crime_id AND player_id = :player_id AND succeeded = 1";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":crime_id", self::CRIME_CAR, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Check that the cooldown has been properly created
        $amount = $stmt->rowCount();
        
        return $amount;
    }
    
    private function getRobbedStores($player_id) {
        $conn = $this->db->getConnection();
        
        // Search for the amount of successfull bike steals
        $sql = "SELECT * FROM " . self::ACTION_TABLE . "
                    WHERE crime_id = :crime_id AND player_id = :player_id AND succeeded = 1";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":crime_id", self::CRIME_STORE, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Check that the cooldown has been properly created
        $amount = $stmt->rowCount();
        
        return $amount;
    }
    
    private function getKilledPlayers($player_id) {
        $conn = $this->db->getConnection();
        
        // Search for the amount of successfull bike steals
        $sql = "SELECT * FROM " . self::ACTION_TABLE . "
                    WHERE crime_id = :crime_id AND player_id = :player_id AND succeeded = 1";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":crime_id", self::CRIME_KILL, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Check that the cooldown has been properly created
        $amount = $stmt->rowCount();
        
        return $amount;
    }
    
    public function getCrimes($player_id) {
        return [
            "bikes" => $this->getStolenBikes($player_id),
            "cars" => $this->getStolenCars($player_id),
            "stores" => $this->getRobbedStores($player_id),
            "kills" => $this->getKilledPlayers($player_id)
        ];
    }
}
