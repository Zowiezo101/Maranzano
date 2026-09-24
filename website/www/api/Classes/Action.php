<?php

namespace Classes;

class Action {
    // Other classes
    protected $db;
    private $user;
    private $player;
    private $token;    
    private $parameters; 
    
    public function __construct() {
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
            case "action_player_reset":
                $result = $this->resetPlayer();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function resetPlayer() {
        
        // The username that is given via the cookie
        $user_name = $this->parameters->getUser(from_cookie: true);
        
        // Get the user using the username
        $user = $this->user->getUser(name: $user_name);
        
        if (isset($user)) {
            // Get the user_id
            $user_id = $user["id"];
        
            // Try to get the expected parameters
            $player = $this->parameters->getPlayer();

            // Check if the player name is available
            $this->player->isPlayerAvailable($player);

            // Create a new player for this user
            $this->player->createPlayer($user_id, $player);
        } else {
            throwError();
        }
    }
}
