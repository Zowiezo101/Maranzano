<?php

namespace Classes;

class Login extends Auth {
    
    private $user_id;
    
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
                $user = $this->user->getUser($parameters);
                    
                // Update the parameters with the user
                $parameters[self::PARAM_ID]         = $user["id"];
                $parameters[self::PARAM_USER]       = $user["name"];
                $parameters[self::PARAM_PASS_HASH]  = $user["pass_hash"];
                $parameters[self::PARAM_VERIFIED]   = $user["is_verified"];
                
                $this->verifyPass($parameters);

                // Check if the user is verified
                // If not, send the verification email again
                $this->verifyVerified($parameters);

                // Invalidate all previous tokens
                $this->token->invalidateLoginTokens($parameters);

                // Genereate new token
                $token = $this->token->createLoginToken($parameters);

                // Insert the token into the parameter array
                $parameters[self::PARAM_TOKEN] = $token;
                
                // Create a cookie
                $this->createCookies($parameters);
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
    
    public function validateSession() {
        
        // Possible database error, do NOT continue
        if ($this->hasError()) {
            return;
        }
        
        // Get only these parameters, all other parameters are ignored
        $param_list = [
            self::PARAM_TOKEN,
            self::PARAM_USER
        ];
            
        // Retrieve the cookies
        $parameters = $this->getCookies($param_list);
        
        try {            
            // Validate parameters
            if ($this->validateParameters($param_list, $parameters)) {
        
                // Get the token from the database
                $token = $this->token->retrieveLoginTokenFromUser($parameters);
                
                // Store the user ID for later use
                $this->user_id = $token["user_id"];
                
                // Update the parameters with the user
                $parameters[self::PARAM_TOKEN_ID]   = $token["id"];
                $parameters[self::PARAM_TOKEN_HASH] = $token["token"];
                
                $this->verifyToken($parameters);
            } else {
                // Throw an error to get into the catch part of the code
                $this->throwError();
            }
            
        } catch (\Exception) {
            // We're not gonna let the client know what went wrong while validating
            $this->clearError();
            $this->setError("session.error", Message::CODE_ERROR);
            
            // Clear the cookies
            $invalidate_cookie = true;
            $this->createCookies($parameters, $invalidate_cookie);
            
            // Now clear the session as well
            session_reset();
        }
    }
    
    public function getUserIdFromSession() {
        $this->validateSession();
        
        $user_id = null;
        if (!$this->hasError()) {
            $user_id = $this->user_id;
        }
        
        return $user_id;
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
            $this->message->setError("auth.login.invalid", Message::CODE_INVALID);
            $this->message->throwError();
        }
    }
    
    private function verifyToken($parameters) {
        $pass = $parameters[self::PARAM_TOKEN];
        $hash = $parameters[self::PARAM_TOKEN_HASH];
    
        if (!password_verify($pass, $hash)) {
            // The password doesn't match the hash
            $this->message->setError("auth.token.invalid");
            $this->message->throwError();
        }
    }
    
    private function verifyVerified($parameters) {
         if ($parameters[self::PARAM_VERIFIED] == false) {

            // Invalidate all previous tokens
            $this->token->invalidateVerifyTokens($parameters);

            // Genereate new token
            $token = $this->token->createVerifyToken($parameters);

            // Insert the token into the parameter array
            $parameters[self::PARAM_TOKEN] = $token;

            // Send the new token via email
            $this->token->sendVerifyToken($parameters);
            
            // We're not verified, send the email again and notify the user
            // by throwing an error
            $this->clearError();
            $this->setError("login.verify");
            $this->throwError();
         }
    }
    
    /**
     * Cookie functions
     */
    
    private function createCookies($parameters, $invalidate = false) {
        global $domain_name;
        
        // Names for the cookies
        $name1 = self::PARAM_TOKEN;
        $name2 = self::PARAM_USER;
        
        if ($invalidate == true) {
            // Set the values to null
            $value1 = "";
            $value2 = "";
        } else {
            // Values for the cookies
            $value1 = $parameters[self::PARAM_TOKEN];
            $value2 = $parameters[self::PARAM_USER];
        }
        
        $options = [
            // 30 hours expiration time
            "expires" => time() + (60*60*30),
        
            // Cookie should be valid through-out the server
            "path" => "/",
        
            // Security (in debugging mode, this is false
            "secure" => !str_contains($domain_name, "localhost"),
            "httponly" => true,
            "samesite" => "Lax"
        ];   
        
        if ($invalidate == true) {            
            // Set the expiration date 1 year in the past
            $options["expires"] = time() - (60 * 60 * 24 * 365);
        }
        
        // Set the cookies
        setcookie($name1, $value1, $options); 
        setcookie($name2, $value2, $options); 
    }
    
    private function getCookies($param_list) {
        $parameters = [];
        
        // Filter the input data
        $input = filter_input_array(INPUT_COOKIE);
        
        // Get each parameter
        foreach ($param_list as $key) {
            
            // If the parameter is actually in the POST body
            if (isset($input[$key])) {
                
                // Trim it and put it in the parameters array
                $parameters[$key] = trim($input[$key]);
            }
        }
        
        return $parameters;
    }
}
