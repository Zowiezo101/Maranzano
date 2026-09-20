<?php

namespace Classes;

class Player {
    // Other classes
    protected $db;
    protected $message;
    
    // Player properties
    private $id;
    private $user_id;
    private $family_id;
    private $rank;
    private $progress;
    private $cash;
    private $bank;
    private $city;
    private $country;
    private $health;
    private $bullets;
    private $shields;
    private $deceased;
    private $killed_by;
    private $created_at;
    private $last_active;
    
    public function __construct($message = null) {
        
        // For the error messages
        if (isset($message)) {
            $this->setMessage($message);
        } else {
            $this->message = new Message();
        }
        
        // Link the message class for error messages
        $this->db = new Database($this->message);
    }
    
    public function createPlayer($user_id) {
        // TODO:
    }
    
    public function getPlayer($user_id) {
        
    }
    
    public function getPlayerInfo() {
        
    }
    
    public function getPlayerStats() {
        
    }
    
    /**
     * Setters & Getters
     */
    
    public function setMessage($message) {
        $this->message = $message;
    }
}
