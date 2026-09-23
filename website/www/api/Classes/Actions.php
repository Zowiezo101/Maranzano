<?php

namespace Classes;

class Actions {
    // Other classes
    protected $db;
    private $user;  
    private $token;    
    private $parameters; 
    
    public function __construct() {
        $this->parameters = new Parameters();
        $this->user = new User();
        $this->token = new Token();
        
        // To connect to the database
        $this->db = new Database();
    }
    
    public function route($route, $data) {
        // These actions need to have the cookie checked
        // TODO: Rewrite to only use cookie data?
        $auth = new Login();
        $auth->route("login_validate", $data);
        
        $result = null;
        
        // Parse the input data
        $this->parameters->setData($data);
        
        switch($route) {
            case "player_reset":
                $result = $this->resetPlayer();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function resetPlayer() {
        
    }
}
