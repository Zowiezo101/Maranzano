<?php

namespace Classes;

class Actions extends Login {
    
    public function __construct() {
        parent::__construct();

        // Make sure the session is still valid
        $this->getUserIdFromSession();
    }
    
    public function route($route) {
        $result = null;
        
        return $result;
    }
    
    /**
     * GET requests
     */
    
    public function getPlayerInfo() {
        $this->player->getPlayerInfo($this->user_id);
    }
    
    public function getPlayerStats() {
        $this->player->getPlayerStats($this->user_id);
    }
    
    /**
     * POST requests (has parameters)
     */
    
    public function createNewPlayer() {
        
        // Possible database error, do NOT continue
        if ($this->hasError()) {
            return;
        }
        
        // Get only these parameters, all other parameters are ignored
        $param_list = [
            self::PARAM_PLAYER
        ];
            
        // Retrieve the parameters
        $parameters = $this->getParameters($param_list);
        
        try {            
            // Validate parameters
            if ($this->validateParameters($param_list, $parameters)) {
                // Insert the user ID
                $parameters[Auth::PARAM_ID] = $this->user_id;
                
                $this->player->createNewPlayer($parameters);
            } else {
                // Throw an error to get into the catch part of the code
                $this->setError("auth.login.invalid", Message::CODE_INVALID);
                $this->throwError();
            }
            
        } catch (\Exception) {
            // Only allow the following error messages
            $white_list = [
                "login.verify",
                "auth.login.invalid"
            ];
            
            $this->clearError($white_list);
            $this->setError("login.error", Message::CODE_ERROR);
        }
    }
}
