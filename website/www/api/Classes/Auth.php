<?php

namespace Classes;

// TODO: Resend verification email if necessary

class Auth {
    // Other classes
    protected $db;
    protected $token;
    protected $message;
    
    // All parameters that will be handled
    public const PARAM_EMAIL = "email";
    public const PARAM_ID    = "id";
    public const PARAM_USER  = "user";
    public const PARAM_PASS  = "pass";
    public const PARAM_PASS2 = "pass2";
    public const PARAM_TOKEN = "token";
    
    // These values will not be accepted as external parameter
    protected const PARAM_PASS_HASH = "pass_hash";
    public const PARAM_TOKEN_ID  = "token_id";
    
    public function __construct() {
        $this->message = new Message();
        
        // Link the message class for error messages
        $this->db = new Database($this->message);        
        $this->token = new Token($this->message);
        
        // Set the created database connection in the token object
        $this->token->setConnection($this->db->getConnection());
    }
    
    public function __destruct() {
        // This will call their destructors as well
        $this->db = null;
        $this->token = null;
        $this->message = null;
    }
    
    /**
     * Parameter functions
     */
    
    protected function getParameters($param_list) {
        $parameters = [];
        
        // Get the input data
        $input_raw = (array) json_decode(file_get_contents('php://input'));
        
        // Filter the input data
        $input = filter_var_array($input_raw);
        
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
    
    /**
     * Validation functions
     */
    
    protected function validateParameters($param_list, $parameters, $check_available = false) {
        // If a parameter is in the parameter list, it should be verified
        // In that case it's set to false, to fail if it isn't validated
        $valid_token = !in_array(self::PARAM_TOKEN, $param_list);
        $valid_email = !in_array(self::PARAM_EMAIL, $param_list);
        $valid_user  = !in_array(self::PARAM_USER, $param_list);
        $valid_pass  = !in_array(self::PARAM_PASS, $param_list);
        
        // Check the token parameter
        if (isset($parameters[self::PARAM_TOKEN])) {
            // Validate the token
            $this->validateToken($parameters[self::PARAM_TOKEN]);
            
            // If we have a valid result
            $valid_token = !$this->hasError();
        }
        
        // Check the email parameter
        if (isset($parameters[self::PARAM_EMAIL])) {
            // Validate the email
            $this->validateEmail($parameters[self::PARAM_EMAIL], $check_available);
            
            // If we have a valid result
            $valid_email = !$this->hasError();
        }
        
        // Check the user parameter
        if (isset($parameters[self::PARAM_USER])) {
            // Validate the user
            $this->validateUser($parameters[self::PARAM_USER], $check_available);
            
            // If we have a valid result
            $valid_user = !$this->hasError();
        }
        
        // Check the password parameter
        if (isset($parameters[self::PARAM_PASS])) {
            // If the confirmation password isn't sent, set it to null
            $pass1 = $parameters[self::PARAM_PASS];
            $pass2 = isset($parameters[self::PARAM_PASS2]) ? 
                           $parameters[self::PARAM_PASS2] : null;
            
            // Validate the password
            $this->validatePass(
                        $pass1, 
                        $pass2);
            
            // If we have a valid result
            $valid_pass = !$this->hasError();
        }
                
        // Return if everything is valid
        return ($valid_token &&
                $valid_email && 
                $valid_user  && 
                $valid_pass);
    }
    
    private function validateEmail($email, $check_available = false) {
        $result = null;
        
        // Check if this e-mail address is a proper e-mail address
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
            // See if the email address already exists
            $user = $this->db->retrieveUserFromEmail($email);

            if (isset($user) && $check_available) {
                // This email address is not available
                $this->setError("auth.email.taken");
            } else if (!isset($user) && !$check_available) {
                // This email address is not valid
                $this->setError("auth.email.invalid");
            } else {
                // This email address is valid
                $result = $email;
            }
            
        } else {
            // Not a valid email address
            $this->setError("auth.email.invalid");
        }
        
        return $result;
    }
    
    private function validateUser($user_name, $check_available = false) {
        $result = null;
        
        // Check if this username is a proper username
        if (preg_match('/^[a-zA-Z0-9_]+$/', $user_name)) {
            
            // See if the username already exists
            $user = $this->db->retrieveUserFromName($user_name);

            if (isset($user) && $check_available) {
                // This username is not available
                $this->setError("auth.user.taken");
            } else if (!isset($user) && !$check_available) {
                // This username is not valid
                $this->setError("auth.user.invalid");
            } else {
                // This username is valid
                $result = $user_name;
            }
            
        } else {
            // Not a valid username
            $this->setError("auth.user.invalid");
        }
        
        return $result;
    }
    
    protected function validatePass($pass1, $pass2) {
        $result = null;
        
        if(strlen($pass1) < 8){
            // The password needs to be at least 8 characters
            $this->setError("auth.pass1.invalid");
        } else if($pass1 !== $pass2){
            // The password and confirmation need to match
            $this->setError("auth.pass2.invalid");
        } else {
            // Valid password
            $result = $pass1;
        }
        
        return $result;
    }
    
    private function validateToken($token) {
        $result = null;
        
        // Check if the token is set
        if(!isset($token) || (strlen($token) !== 100) || !ctype_xdigit($token)){
            // The token needs to be 100 characters (50 hexadecimal bytes)
            $this->setError("auth.token.invalid");
        } else {
            // Valid token
            $result = $token;
        }
        
        return $result;
    }
    
    /**
     * Misc
     */
    
    public function sendMessage() {
        // Send a message to the client
        $this->message->sendMessage();
    }
    
    public function hasError() {
        // Check if there are any saved errors
        return ("" !== $this->getError());
    }
    
    public function getError() {
        // Return any saved errors
        return $this->message->getError();
    }
    
    public function clearError() {
        // Return any saved errors
        return $this->message->clearError();
    }
    
    public function setError($error, $code = Message::CODE_INVALID) {
        // Save an error
        return $this->message->setError($error, $code);
    }
}
