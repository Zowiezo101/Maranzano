<?php

namespace Classes;

class Location {
    // Other classes
    private $parameters;
    private $player;
    private $user;
    
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
    
    public function __construct() {
        $this->parameters = new Parameters();
        $this->player = new Player();
        $this->user = new User();
    }
    
    public function route($route, $data) {        
        $result = null;
        
        // Parse the input data
        $this->parameters->setData($data);
        
        switch($route) {
            case "location_all":
                $result = $this->getAllLocations();
                break;
            case "location_update":
                $result = $this->updateLocation();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    public function getAllLocations() {
        $results = null;
        
        // The username that is given via the cookie
        $user_name = $this->parameters->getUser(from_cookie: true);
        
        // Get the user using the username
        $user = $this->user->getUser(name: $user_name);   
        
        if (isset($user)) {
            // Get the user_id
            $user_id = $user["id"];
            
            // Get the player that belongs to this user
            $player = $this->player->getPlayer($user_id);

            // The location ID
            $location_id = intval($player["location"], 10);

            // Put the const in a variable
            $results = self::LOCATIONS;
            
            // Remove the current location from the list of selectable locations
            $results[$location_id] = null;
        } else {
            throwError();
        }
        
        return $results;
    }
    
    public function updateLocation() {
        $results = null;
        
        // The username that is given via the cookie
        $user_name = $this->parameters->getUser(from_cookie: true);
        
        // Get the user using the username
        $user = $this->user->getUser(name: $user_name);   
        
        if (isset($user)) {
            // Get the user_id
            $user_id = $user["id"];
            
            // Get the player that belongs to this user
            $player = $this->player->getPlayer($user_id);

            // The location ID
            // TODO:
//            $location_id = $this->parameters->getLocation();
            
            // Update the player location
//            $this->player->updatePlayer($player["id"], location:$location_id);
        } else {
            throwError();
        }
        
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
}
