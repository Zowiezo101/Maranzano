<?php

namespace Classes;

class Location extends Action {
    
    public const IDX_COUNTRY = 0;
    public const IDX_CITY = 1;
    
    public const LOCATIONS = [
        0 => ["Italy", "Palermo"],
        1 => ["Italy", "Milan"],
        2 => ["Netherlands", "Amsterdam"],
        3 => ["England", "London"],
        4 => ["France", "Paris"],
        5 => ["Germany", "Berlin"],
        6 => ["Spain", "Madrid"],
        7 => ["Hungary", "Budapest"],
        8 => ["Czech Republic", "Praque"],
        9 => ["Poland", "Warschau"],
        10 => ["Portugal", "Lisbon"],
        11 => ["Denmark", "Copenhagen"],
    ];
    
    public function route($route, $data) {  
        parent::route($route, $data);
        
        $result = null;
        
        switch($route) {
            case "location_all":
                $result = $this->getAllLocations();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function getAllLocations() {
        // The current player
        $player = $this->player_data;
        
        // The location ID
        $location_id = intval($player["location"], 10);

        // Put the const in a variable
        $results = self::LOCATIONS;

        // Remove the current location from the list of selectable locations
        $results[$location_id] = null;
        
        return $results;
    }
    
    /**
     * getters and setters
     */
    
    public function getCity($value) {
        $result = self::LOCATIONS[$value][self::IDX_CITY];
        return $result;
    }
    
    public function getCountry($value) {        
        $result = self::LOCATIONS[$value][self::IDX_COUNTRY];
        return $result;
    }
    
    /**
     * Misc function
     */
    
    public function isValidLocation($location_id) {
        return array_key_exists($location_id, self::LOCATIONS);
    }
}
