<?php

namespace Classes;

class Crime extends Action {
    
    private const ACTION_TABLE = "crime_session";
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
        
        // TODO: Add cooldown
        
        return $bike;
    }
    
    
    /**
     * Misc functions
     */
    
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
