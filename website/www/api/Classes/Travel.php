<?php

namespace Classes;

class Travel extends Action {
    // Other classes
    protected $db;
    private $user;
    private $player;
    private $token;    
    private $parameters; 
    
    private const ACTION_COST = 3000;
    private const ACTION_TABLE = "travel_session";
    private const ACTION_COOLDOWN = 30;
    
    public function __construct() {
        parent::__construct();
        
        $this->parameters = new Parameters();
        $this->user = new User();
        $this->player = new Player();
        $this->token = new Token();
        
        // To connect to the database
        $this->db = new Database();
    }
    
    public function route($route, $data) {
        // These actions need to have the cookie checked
        $auth = new Login();
        $auth->route("login_validate", $data);
        
        $result = null;
        
        // Parse the input data
        $this->parameters->setData($data);
        
        switch($route) {
            case "travel_player":
                $result = $this->travelPlayer();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    public function travelPlayer() {
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
            $location_id = $this->parameters->getLocation();
            
            // Make sure the player has enough cash to pay for their ticket
            $this->enoughFunds($player["cash"], self::ACTION_COST);
            
            // Make sure the player isn't on a cooldown
            $this->hasCooldown($player["id"], self::ACTION_TABLE, self::ACTION_COOLDOWN);
            
            // Make sure we're not traveling to the city we're already in
            $this->differentLocation($player["location"], $location_id);
            
            // Set a cooldown in the travel session tablet
            $this->setCooldown($player["id"], self::ACTION_TABLE, self::ACTION_COOLDOWN);
            
            // Update the player location and cash
            $update = [
                "location" => $location_id,
                "cash" => $player["cash"] - 3000
            ];
            $this->player->updatePlayer($player["id"], $update);
        } else {
            throwError();
        }
        
        return $results;
    }
    
    private function differentLocation($current_location, $new_location) {
        if ($current_location === $new_location) {
            throwError("travel.current");
        }
    }
}
