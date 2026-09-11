<?php

namespace Classes;

class Login extends Auth {
    public function loginUser() {
        
        // Possible database error, do NOT continue
        if ($this->hasError()) {
            return;
        }
        
        // Get only these parameters, all other parameters are ignored
        $param_list = [
            self::PARAM_EMAIL,
            self::PARAM_PASS
        ];
            
        // Retrieve the parameters
        $parameters = $this->getParameters($param_list);
        
        try {            
            // Validate parameters
            if ($this->validateParameters($param_list, $parameters)) {
        
                // Insert user in database
                $user = $this->db->getUser($parameters);
                    
                // Update the parameters with the user
                $parameters[self::PARAM_ID]         = $user["id"];
                $parameters[self::PARAM_USER]       = $user["name"];
                $parameters[self::PARAM_PASS_HASH]  = $user["pass_hash"];
                
                $this->verifyPass($parameters);

                // Invalidate all previous tokens
                $this->token->invalidateLoginTokens($parameters);

                // Genereate new token
                $token = $this->token->createLoginToken($parameters);

                // Insert the token into the parameter array
                $parameters[self::PARAM_TOKEN] = $token;
                
                // The data to send to the user
                $data = [
                    "token" => $parameters[self::PARAM_TOKEN],
                    "user_id" => $parameters[self::PARAM_ID],
                    "user_name" => $parameters[self::PARAM_USER]
                ];
                
                $this->message->setData($data);
            } else {
                // We're not gonna let the client know what went wrong while validating
                $this->clearError();
                $this->setError("auth.login.invalid");
            }
            
        } catch (\Exception) {
            // Something went wrong
            $this->setError("login.error", Message::CODE_ERROR);
        }
    }
    
    public function logoutUser() {
        
        // Possible database error, do NOT continue
        if ($this->hasError()) {
            return;
        }
        
        // Get only these parameters, all other parameters are ignored
        $param_list = [];
            
        // Retrieve the parameters
        $parameters = $this->getParameters($param_list);
        
        try {
            // Validate parameters
            if ($this->validateParameters($param_list, $parameters)) {
                
            } else {
                $this->setError("logout.error");
            }
            
        } catch (\Exception) {
            // Something went wrong
            $this->setError("logout.error", Message::CODE_ERROR);
        }
    }
    
    protected function validatePass($pass1, $pass2) {
        $result = null;
        
        if(strlen($pass1) < 8){
            // The password needs to be at least 8 characters
            $this->setError("auth.pass1.invalid");
        } else {
            // Valid password
            $result = $pass1;
            
            // This variable is not used, but still passed to this function
            $pass2 = null;
        }
        
        return $result;
    }
    
    private function verifyPass($parameters) {
        $pass = $parameters[self::PARAM_PASS];
        $hash = $parameters[self::PARAM_PASS_HASH];
    
        if (!password_verify($pass, $hash)) {
            // The password doesn't match the hash
            $this->message->setError("auth.login.invalid");
            $this->message->throwError();
        }
    }
}
