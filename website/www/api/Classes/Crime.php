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
    private const CRIME_SEARCH = 5;
    
    // Vehicle types
    private const VEHICLE_CHEAP = 1;
    private const VEHICLE_MID = 2;
    private const VEHICLE_HIGH = 3;
    private const VEHICLE_WIN = 4;
    
    // Bike stuff
    private const BIKE_COOLDOWN = 50;
    private const BIKE_XP = 450;
    public const BIKES = [
        self::VEHICLE_CHEAP => ["id" => self::VEHICLE_CHEAP, "worth" => 20,   "img" => "../img/bikes/20.jpg",   "chance_start" => 0],
        self::VEHICLE_MID   => ["id" => self::VEHICLE_MID,   "worth" => 80,   "img" => "../img/bikes/80.png",   "chance_start" => 40],
        self::VEHICLE_HIGH  => ["id" => self::VEHICLE_HIGH,  "worth" => 500,  "img" => "../img/bikes/500.png",  "chance_start" => 70],
        self::VEHICLE_WIN   => ["id" => self::VEHICLE_WIN,   "worth" => 1500, "img" => "../img/bikes/1500.jpg", "chance_start" => 90],
    ];
    
    // Car stuff
    private const CAR_COOLDOWN = 90;
    private const CAR_XP = 1500;
    public const CARS = [
        self::VEHICLE_CHEAP => ["id" => self::VEHICLE_CHEAP, "worth" => 250,   "img" => "../img/cars/250.jpg",    "chance_start" => 0],
        self::VEHICLE_MID   => ["id" => self::VEHICLE_MID,   "worth" => 5000,  "img" => "../img/cars/5000.png",   "chance_start" => 40],
        self::VEHICLE_HIGH  => ["id" => self::VEHICLE_HIGH,  "worth" => 15000, "img" => "../img/cars/15.000.jpg", "chance_start" => 70],
        self::VEHICLE_WIN   => ["id" => self::VEHICLE_WIN,   "worth" => 90000, "img" => "../img/cars/90.000.jpg", "chance_start" => 90],
    ];
    
    // Store stuff
    private const STORE_COOLDOWN = 180;
    private const STORE_XP = 5000;
    public const STORES = [
        self::VEHICLE_CHEAP => ["start" => 2000,  "end" => 5000,  "chance_start" => 0],
        self::VEHICLE_MID   => ["start" => 5000,  "end" => 10000, "chance_start" => 40],
        self::VEHICLE_HIGH  => ["start" => 10000, "end" => 15000, "chance_start" => 70],
        self::VEHICLE_WIN   => ["start" => 15000, "end" => 20000, "chance_start" => 90],
    ];
    
    // Kill stuff
    private const KILL_COOLDOWN = 1800;
    private const SEARCH_COOLDOWN = 600;
    
    private const MIN_AGE = 7*24;
    private const ACTION_MIN = 1;
    
    public function __construct($conn) {
        parent::__construct($conn);
        
        // Contains the garage functions
        $this->garage = new Garage($conn);
        $this->jail = new Jail($conn);
    }
    
    public function route($route, $data) {
        parent::route($route, $data);
        
        $result = null;
        
        switch($route) {
            case "crime_bike":
                $result = $this->stealBike();
                break;
            
            case "crime_car":
                $this->hasRequiredRank(\getRankLevel("Mafioso"));
                $result = $this->stealCar();
                break;
            
            case "crime_store":
                $this->hasRequiredRank(\getRankLevel("Hitman"));
                $result = $this->robStore();
                break;
            
            case "crime_kill":
                $this->hasRequiredRank(\getRankLevel("Hitman"));
                $result = $this->killPlayer();
                break;
            
            case "crime_chance_bike":
                $result = $this->getSuccessRateBike();
                break;
            
            case "crime_chance_car":
                $result = $this->getSuccessRateCar();
                break;
            
            case "crime_chance_store":
                $result = $this->getSuccessRateStore();
                break;
            
            case "crime_search_player":
                $result = $this->searchPlayer();
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
        $rate = $this->player->getPlayerSuccessBikeByRank($player["rank"]);
        
        // The RNG to create a chance to succeed or not
        $gamble = mt_rand(0, 10000) / 100;
        
        // Has the player succeeded or not?
        $success = ($rate >= $gamble);
        
        // The bike to steal
        $bike = null;
        
        if ($success) {
            // We succeeded!
            $bike_gamble = mt_rand(0, 10000) / 100;
            
            if (self::BIKES[self::VEHICLE_WIN]["chance_start"] <= $bike_gamble) {
                $bike = self::BIKES[self::VEHICLE_WIN];
            } else if (self::BIKES[self::VEHICLE_HIGH]["chance_start"] <= $bike_gamble) {
                $bike = self::BIKES[self::VEHICLE_HIGH];
            } else if (self::BIKES[self::VEHICLE_MID]["chance_start"] <= $bike_gamble) {
                $bike = self::BIKES[self::VEHICLE_MID];
            } else {
                $bike = self::BIKES[self::VEHICLE_CHEAP];
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
            
        // Give the player XP
        $this->giveXP($player, self::BIKE_XP, $success);
        
        return $bike;
    }
    
    private function stealCar() {
        // The current player
        $player = $this->player_data;
        
        // Is the player still on a cooldown?
        $this->hasCrimeCooldown($player["id"], 
                self::CRIME_CAR, 
                self::ACTION_TABLE, 
                self::CAR_COOLDOWN,
                "car.cooldown");
        
        // The chance to succeed
        $rate = $this->player->getPlayerSuccessCarByRank($player["rank"]);
        
        // The RNG to create a chance to succeed or not
        $gamble = mt_rand(0, 10000) / 100;
        
        // Has the player succeeded or not?
        $success = ($rate >= $gamble);
        
        // The car to steal
        $car = null;
        
        if ($success) {
            // We succeeded!
            $car_gamble = mt_rand(0, 10000) / 100;
            
            if (self::CARS[self::VEHICLE_WIN]["chance_start"] <= $car_gamble) {
                $car = self::CARS[self::VEHICLE_WIN];
            } else if (self::CARS[self::VEHICLE_HIGH]["chance_start"] <= $car_gamble) {
                $car = self::CARS[self::VEHICLE_HIGH];
            } else if (self::CARS[self::VEHICLE_MID]["chance_start"] <= $car_gamble) {
                $car = self::CARS[self::VEHICLE_MID];
            } else {
                $car = self::CARS[self::VEHICLE_CHEAP];
            }
            
            // Add car to garage
            $this->garage->addCar($player["id"], $car);
        } else {
            // Add player to jail
             $this->jail->sendToJail($player);
        }
        
         // Add the cooldown
         $this->setCrimeCooldown($player["id"], 
                 self::CRIME_CAR, 
                 $success, 
                 self::ACTION_TABLE, 
                 self::CAR_COOLDOWN);
            
        // Give the player XP
        $this->giveXP($player, self::CAR_XP, $success);
        
        return $car;
    }
    
    private function robStore() {
        // The current player
        $player = $this->player_data;
        
        // Is the player still on a cooldown?
        $this->hasCrimeCooldown($player["id"], 
                self::CRIME_STORE, 
                self::ACTION_TABLE, 
                self::STORE_COOLDOWN,
                "store.cooldown");
        
        // The chance to succeed
        $rate = $this->player->getPlayerSuccessStoreByRank($player["rank"]);
        
        // The RNG to create a chance to succeed or not
        $gamble = mt_rand(0, 10000) / 100;
        
        // Has the player succeeded or not?
        $success = ($rate >= $gamble);
        
        // The car to steal
        $store = null;
        
        if ($success) {
            // We succeeded!
            $store_gamble = mt_rand(0, 10000) / 100;
            
            if (self::STORES[self::VEHICLE_WIN]["chance_start"] <= $store_gamble) {
                $store = self::STORES[self::VEHICLE_WIN];
            } else if (self::STORES[self::VEHICLE_HIGH]["chance_start"] <= $store_gamble) {
                $store = self::STORES[self::VEHICLE_HIGH];
            } else if (self::STORES[self::VEHICLE_MID]["chance_start"] <= $store_gamble) {
                $store = self::STORES[self::VEHICLE_MID];
            } else {
                $store = self::STORES[self::VEHICLE_CHEAP];
            }
            
            // The amount the player has stolen
            $worth = mt_rand($store["start"], $store["end"]);
            
            // Update the player cash with that amount
            $update = [
                "cash" => $this->player_data["cash"] + $worth
            ];
            $this->player->updatePlayer($this->player_data["id"], $update);
            
            // Send the data back to the client
            $store = [
                "worth" => $worth,
            ];
        } else {
            // Add player to jail
             $this->jail->sendToJail($player);
        }
        
         // Add the cooldown
         $this->setCrimeCooldown($player["id"], 
                 self::CRIME_STORE, 
                 $success, 
                 self::ACTION_TABLE, 
                 self::STORE_COOLDOWN);
            
        // Give the player XP
        $this->giveXP($player, self::STORE_XP, $success);
        
        return $store;
    }
    
    private function killPlayer() {

        // The current player
        $player = $this->player_data;

        // The current date
        $current_date = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                            ->format('Y-m-d H:i:s');
        
        // Is the player still on a cooldown?
        $this->hasCrimeCooldown($player["id"], 
                self::CRIME_KILL, 
                self::ACTION_TABLE, 
                self::KILL_COOLDOWN,
                "kill.kill.cooldown");
        
        // Get the player name
        $player_name = $this->parameters->getPlayer();

        // Get the amount of bullets
        $amount = $this->parameters->getAmount();
        
        $this->isValidAmount($amount, min:self::ACTION_MIN);
        
        // Do we actually have this amount of bullets
        if ($player["bullets"] < $amount) {
            throwError("kill.broke");
        }

        // Check if the player name is a valid player
        $target = $this->player->retrievePlayerFromName($player_name);
        
        if (!isset($target)) {
            // Do NOT continue if this person can't be found
            throwError("friends.name_not_found");
        }
        
        if ($target["id"] === $player["id"]) {
            throwError("kill.self");
        }
        
        if ($target["deceased"] !== 0) {
            // Also do NOT continue if this player is deceased
            throwError("friends.deceased");
        }
        
        // Can't kill players less than a week old AND lower than hitman
        if (($target["age"] < self::MIN_AGE) && ($target["rank"] < \getRankLevel("Hitman"))) {
            throwError("kill.too_young");
        }
        
        // Compare the targets rank to our own, 
        // we can only kill 1 rank higher and lower
        if ($player["rank"] + 1 < $target["rank"]) {
            throwError("kill.too_low");
        }
        
        // Check player is in the same location
        if ($player["location_id"] !== $target["location_id"]) {
            throwError("kill.wrong_location");
        }
        
        $succeeded = 0;
        
        $new_health_t = $target["health"] - $amount;
        $new_health_p = $this->player_data["health"] - $target["shields"];
        
        // Update for the target
        $target_update = [
            "shields" => 0,
            "health" => max($new_health_t, 0)
        ];
        
        $stolen_cash = 0;
        if ($new_health_t <= 0) {
            // Target is killed
            $target_update["cash"] = 0;
            $stolen_cash = $target["cash"];
            
            // Their kill date and info
            $target_update["deceased"] = 1;
            $target_update["killer_id"] = $this->player_data["id"];
            $target_update["deceased_at"] = $current_date;
            
            $succeeded = 1;
        } else {
            // Admit them to the hospital
            $hospital = new Hospital($this->conn);
            $hospital->setHospitalCooldown($target["id"], $new_health_t, $target["rank"]);
        }
        
        // Update for the player
        $player_update = [
            "bullets" => $this->player_data["bullets"] - $amount,
            "health" => max($new_health_p, 0),
            "cash" => $this->player_data["cash"] + $stolen_cash
        ];
                
        if ($new_health_p <= 0) {
            // Player is killed
            
            // Their kill date and info
            $player_update["deceased"] = 1;
            $player_update["killer_id"] = $target["id"];
            $player_update["deceased_at"] = $current_date;
        } else {
            // Admit them to the hospital
            $hospital = new Hospital($this->conn);
            $hospital->setHospitalCooldown($this->player_data["id"], $new_health_p, $this->player_data["rank"]);
        }
        
        $this->player->updatePlayer($target["id"], $target_update);
        $this->player->updatePlayer($this->player_data["id"], $player_update);

        // TODO:
//         // Add the cooldown
//         $this->setCrimeCooldown($player["id"], 
//                 self::CRIME_KILL, 
//                 $found, 
//                 self::ACTION_TABLE, 
//                 self::KILL_COOLDOWN);

        // Return message whether this player has been killed or not
        return $succeeded ? 1 : 0;
    }
    
    public function getSuccessRateBike() {
        
        // The current player
        $player = $this->player_data;
        
        // Get the current rank
        return $this->player->getPlayerSuccessBikeByRank($player["rank"]);
    }
    
    public function getSuccessRateCar() {
        
        // The current player
        $player = $this->player_data;
        
        // Get the current rank
        return $this->player->getPlayerSuccessCarByRank($player["rank"]);
    }
    
    public function getSuccessRateStore() {
        
        // The current player
        $player = $this->player_data;
        
        // Get the current rank
        return $this->player->getPlayerSuccessStoreByRank($player["rank"]);
    }
    
    public function searchPlayer() {

        // The current player
        $player = $this->player_data;
        
        // Is the player still on a cooldown?
        $this->hasCrimeCooldown($player["id"], 
                self::CRIME_SEARCH, 
                self::ACTION_TABLE, 
                self::SEARCH_COOLDOWN,
                "kill.search.cooldown");
        
        // Get the player name
        $player_name = $this->parameters->getPlayer();

        // Get the location
        $location_id = $this->parameters->getLocation();

        // Check if the player name is a valid player
        $target = $this->player->retrievePlayerFromName($player_name);
        
        if (!isset($target)) {
            // Do NOT continue if this person can't be found
            throwError("friends.name_not_found");
        }
        
        if ($target["deceased"] !== 0) {
            // Also do NOT continue if this player is deceased
            throwError("friends.deceased");
        }

        // Check player is in this location
        $found = (intval($location_id, 10) === intval($target["location_id"], 10));

         // Add the cooldown
         $this->setCrimeCooldown($player["id"], 
                 self::CRIME_SEARCH, 
                 $found, 
                 self::ACTION_TABLE, 
                 self::SEARCH_COOLDOWN);

        // Return message whether this player has been found or not
        return $found ? 1 : 0;
    }
    
    /**
     * Misc functions
     */
    
    private function hasCrimeCooldown($player_id, $crime_id, $table, $cooldown, $error) {
        $conn = $this->conn;
        
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
        $conn = $this->conn;
        
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
        if ($new_xp >= $xp_ceiling && $xp_ceiling !== -1) {
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
        $conn = $this->conn;
        
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
        $conn = $this->conn;
        
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
        $conn = $this->conn;
        
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
        $conn = $this->conn;
        
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
