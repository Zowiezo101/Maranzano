<?php

namespace Classes;

class Register extends Auth {
    
    public function registerUser() {
        
        // Possible database error, do NOT continue
        if ($this->hasError()) {
            return;
        }
        
        // Get only these parameters, all other parameters are ignored
        $param_list = [
            self::PARAM_EMAIL,
            self::PARAM_USER,
            self::PARAM_PASS,
            self::PARAM_PASS2
        ];
            
        // Retrieve the parameters
        $parameters = $this->getParameters($param_list);
        
        try {
            // Check if the email address and username are still available
            $check_available = true;
            
            // Validate parameters
            if ($this->validateParameters($param_list, $parameters, $check_available)) {
        
                // Insert user in database
                $id = $this->db->createUser($parameters);
                
                // Create a new player for this user
                // TODO:
                
                // Insert the ID into the parameter array
                $parameters[self::PARAM_ID] = $id;

                // Invalidate all previous tokens
                $this->token->invalidateVerifyTokens($parameters);

                // Genereate new token
                $token = $this->token->createVerifyToken($parameters);

                // Insert the token into the parameter array
                $parameters[self::PARAM_TOKEN] = $token;

                // Send the new token via email
                $this->token->sendVerifyToken($parameters);
            } else {
                $this->setError("signup.error");
            }
            
        } catch (\Exception) {
            // Something went wrong
            $this->setError("signup.error", Message::CODE_ERROR);
        }
        
    }
    
    public function verifyUser() {
        
        // Possible database error, do NOT continue
        if ($this->hasError()) {
            return;
        }
        
        // Get only these parameters, all other parameters are ignored
        $param_list = [
            self::PARAM_TOKEN
        ];
            
        // Retrieve the parameters
        $parameters = $this->getParameters($param_list);
        
        try {
            
            // Validate parameters
            if ($this->validateParameters($param_list, $parameters)) {
        
                // Retrieve the token
                $token = $this->token->retrieveVerifyToken($parameters);
                
                // Store the user ID and token ID of this token (if set)
                $parameters[self::PARAM_ID]       = $token["user_id"];
                $parameters[self::PARAM_TOKEN_ID] = $token["id"];
    
                // Set the user as verified
                $update = ["is_verified" => true];
                $this->db->updateUser($parameters[self::PARAM_ID], $update);

                // Invalidate the token
                $this->token->updateVerifyToken($parameters);
            } else {
                $this->setError("verify.error");
            }
            
        } catch (\Exception) {
            // Something went wrong
            $this->setError("verify.error", Message::CODE_ERROR);
        }
        
    }
    
}
