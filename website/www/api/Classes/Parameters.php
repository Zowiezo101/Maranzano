<?php

namespace Classes;

class Parameters {
    // Other classes
    private $location;
    
    private $data;
    
    /**
     * Setter for the data
     */
    
    public function setData($data) {
        $this->data = $data;
        $this->location = new Location();
    }
    
    /**
     * Getters for input parameters
     */
    
    public function getData() {
        $COOKIE = $this->getCOOKIEData();
        $POST = $this->getPOSTData();
        
        $data = [
            "POST" => $POST,
            "COOKIE" => $COOKIE
        ];
        
        return $data;
    }
    
    private function getPOSTData() {
        // These are the only allowed parameters we'll be looking for
        $white_list = [
            "email",
            "user",
            "player",
            "pass",
            "pass2",
            "token",
            "location",
        ];
        
        $parameters = [];
        
        // Get the input data
        $input_raw = (array) json_decode(file_get_contents('php://input'));
        
        // Filter the input data
        $input = filter_var_array($input_raw);
        
        // Get each parameter
        foreach ($white_list as $key) {
            
            // If the parameter is actually in the POST body
            if (isset($input[$key])) {
                
                // Trim it and put it in the parameters array
                $parameters[$key] = trim($input[$key]);
            }
        }
        
        return $parameters;
        
    }
    
    private function getCOOKIEData() {
        // These are the only allowed parameters we'll be looking for
        $white_list = [
            "token",
            "user"
        ];
        
        $parameters = [];
        
        // Get the filtered input data
        $input = filter_input_array(INPUT_COOKIE);
        
        // Get each parameter
        foreach ($white_list as $key) {
            
            // If the parameter is actually in the POST body
            if (isset($input[$key])) {
                
                // Trim it and put it in the parameters array
                $parameters[$key] = trim($input[$key]);
            }
        }
        
        return $parameters;
    }
    
    /**
     * Getters for data
     */
    public function getToken($from_cookie = false) {
        $result = null;
        
        // Normally get everything from the POST parameters
        $source = "POST";
        if ($from_cookie === true) {
            // Try to get token from cookie
            $source = "COOKIE";
        }
        
        // Validate the token
        if (!isset($this->data[$source]["token"])) {
            // Token isn't set
            throwError("auth.token.invalid", Message::CODE_INVALID);
        } else if((strlen($this->data[$source]["token"]) !== 100) || !ctype_xdigit($this->data[$source]["token"])){
            // The token needs to be 100 characters (50 hexadecimal bytes)
            throwError("auth.token.invalid", Message::CODE_INVALID);
        } else {
            // Valid token
            $result = $this->data[$source]["token"];
        }
        
        return $result;
    }
    
    public function getEmail() {
        
        $result = null;
        
        // Get everything from the POST body
        $source = "POST";
        
        // Validate the email
        if (!isset($this->data[$source]["email"])) {
            // Email isn't set
            throwError("auth.email.invalid", Message::CODE_INVALID);
        } else if (!filter_var($this->data[$source]["email"], FILTER_VALIDATE_EMAIL)) {
            // Not a valid email address
            throwError("auth.email.invalid", Message::CODE_INVALID);
        } else {
            // This email address is valid
            $result = $this->data[$source]["email"];
        }
        
        return $result;
    }
    
    public function getUser($from_cookie = false) {
        
        $result = null;
        
        // Normally get everything from the POST parameters
        $source = "POST";
        if ($from_cookie === true) {
            // Try to get token from cookie
            $source = "COOKIE";
        }
        
        // Validate the username
        if (!isset($this->data[$source]["user"])) {
            // Username isn't set
            throwError("auth.user.invalid", Message::CODE_INVALID);
        } else if (!preg_match('/^[a-zA-Z0-9_]+$/', $this->data[$source]["user"])) {
            // Not a valid username
            throwError("auth.user.invalid", Message::CODE_INVALID);
        } else {
            // This username is valid
            $result = $this->data[$source]["user"];
        }
        
        return $result;
    }
    
    public function getLocation() {
        
        $result = null;
        
        // Get everything from the POST body
        $source = "POST";
        
        // Validate the location
        if (!isset($this->data[$source]["location"])) {
            // Location isn't set
            throwError("data.location.invalid", Message::CODE_INVALID);
        } else if (!$this->location->isValidLocation($this->data[$source]["location"])) {
            // Not a valid location
            throwError("data.location.invalid", Message::CODE_INVALID);
        } else {
            // This location is valid
            $result = $this->data[$source]["location"];
        }
        
        return $result;
    }
    
    public function getPlayer() {
        
        $result = null;
        
        // Get everything from the POST body
        $source = "POST";
        
        // Validate the playername
        if (!isset($this->data[$source]["player"])) {
            // Playername isn't set
            throwError("auth.player.invalid", Message::CODE_INVALID);
        } else if (!preg_match('/^[a-zA-Z0-9_]+$/', $this->data[$source]["player"])) {
            // Not a valid playername
            throwError("auth.player.invalid", Message::CODE_INVALID);
        } else {
            // This playername is valid
            $result = $this->data[$source]["player"];
        }
        
        return $result;
    }
    
    public function getPass() {
        $result = null;
        
        // Get everything from the POST body
        $source = "POST";
        
        // Validate the password
        if (!isset($this->data[$source]["pass"])) {
            // Password isn't set
            throwError("auth.pass1.invalid", Message::CODE_INVALID);
        } else if(strlen($this->data[$source]["pass"]) < 8){
            // The password needs to be at least 8 characters
            throwError("auth.pass1.invalid", Message::CODE_INVALID);
        } else {
            // Valid password
            $result = $this->data[$source]["pass"];
        }
        
        return $result;
    }
    
    public function getPass2() {
        $result = null;
        
        // Get everything from the POST body
        $source = "POST";
        
        // Validate the first password first
        $this->getPass();
        
        // Validate the password
        if (!isset($this->data[$source]["pass2"])) {
            // Password isn't set
            throwError("auth.pass2.invalid", Message::CODE_INVALID);
        } else if($this->data[$source]["pass"] !== $this->data[$source]["pass2"]){
            // The password and confirmation need to match
            throwError("auth.pass2.invalid", Message::CODE_INVALID);
        } else {
            // Valid password
            $result = $this->data[$source]["pass2"];
        }
        
        return $result;
    }
    
    /**
     * Validation functions
     */
    
    public function validatePass2($pass1, $pass2) {
        $result = null;
        
        if($pass1 !== $pass2){
            // The password and confirmation need to match
            throwError("auth.pass2.invalid", Message::CODE_INVALID);
        } else {
            // Valid password
            $result = $pass1;
        }
        
        return $result;
    }
    
    /**
     * Functions to check if a parameter is set
     */
    
    public function hasTokenCookie() {
        $result = isset($this->data["COOKIE"]["token"]) && ($this->data["COOKIE"]["token"] !== "");

        // Return is this cookie is set
        return $result;
    }
    
    public function hasUserCookie() {        
        $result = isset($this->data["COOKIE"]["user"]) && ($this->data["COOKIE"]["user"] !== "");

        // Return is this cookie is set
        return $result;
    }
}
