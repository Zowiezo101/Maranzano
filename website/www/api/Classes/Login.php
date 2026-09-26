<?php

namespace Classes;

class Login {
    
    protected $user_id;
    // Other classes
    private $user;    
    private $player;    
    private $token;    
    private $parameters;   
    
    public function __construct() {
        $this->parameters = new Parameters();
        $this->user = new User();
        $this->player = new Player();
        $this->token = new Token();
    }
    
    public function route($route, $data) {
        $result = null;
        
        // Parse the input data
        $this->parameters->setData($data);
        
        switch($route) {
            case "login_session":
                $result = $this->loginUser();
                break;
            
            case "login_validate":
                $result = $this->validateSession();
                break;
            
            case "login_logout":
                $result = $this->logoutUser();
                break;
        }
        
        return $result;
    }
    
    private function loginUser() {
        
        // Try to get the expected parameters
        $email = $this->parameters->getEmail();
        $pass = $this->parameters->getPass();
        
        // Retrieve the user if it exists with this email address
        $user = $this->user->retrieveUserFromEmail($email);
        
        if (isset($user)) {
            // Update the parameters with the user
            $id        = $user["id"];
            $name      = $user["name"];
            $pass_hash = $user["pass_hash"];
            $verified  = $user["is_verified"];

            $this->verifyPass($pass, $pass_hash);

            // Check if the user is verified
            // If not, send the verification email again
            $this->verifyVerified($verified, $id);

            // Invalidate all previous tokens
            $this->token->invalidateLoginTokens($id);

            // Genereate new token
            $token = $this->token->createLoginToken($id);

            // Create a cookie
            $this->createCookies($token, $name);
        } else {
            throwError("login.error", Message::CODE_ERROR);
        }
    }
    
    private function validateSession() {
        
        // Try to get the expected parameters
        $token_hex = null;
        if ($this->parameters->hasTokenCookie()) {
            $token_hex = $this->parameters->getToken(from_cookie: true);
        }
        
        $user = null;
        if ($this->parameters->hasUserCookie()) {
            $user = $this->parameters->getUser(from_cookie: true);
        }
        
        // Get the token from the database
        $token = $this->token->retrieveLoginTokenFromUser($user);
        if (isset($token) && isset($token_hex) && isset($user)) {
            
            // Update the parameters with the user
            $token_hash = $token["token"];

            // Check that the token is still valid
            $this->verifyToken($token_hex, $token_hash);
        } else {
            
            // Clear the cookies
            $invalidate_cookie = true;
            $this->createCookies($token_hex, $user, $invalidate_cookie);
            
            // Clear the session as well
            $_SESSION = [];
            session_destroy();
            
            // Throw the rror
            throwError("auth.token.invalid", Message::CODE_UNAUTHETICATED);
        }
    }
    
    private function logoutUser() {
        
    }
    
    private function verifyPass($pass, $pass_hash) {    
        if (!password_verify($pass, $pass_hash)) {
            // The password doesn't match the hash
            throwError("auth.login.invalid", Message::CODE_INVALID);
        }
    }
    
    private function verifyToken($token, $token_hash) {
    
        if (!password_verify($token, $token_hash)) {
            // The password doesn't match the hash
            throwError("auth.token.invalid", Message::CODE_INVALID);
        }
    }
    
    private function verifyVerified($verified, $id) {
         if ($verified == false) {
             // We are not verified. Send the email again
             $register = new Register();
             $register->sendVerificationToken($id);
            
            // Notify the user by throwing an error
            throwError("login.verify", Message::CODE_INVALID);
         }
    }
    
    /**
     * Cookie functions
     */
    
    private function createCookies($token, $user, $invalidate = false) {
        global $domain_name;
        
        if ($invalidate == true) {
            // Set the values to null
            $value1 = "";
            $value2 = "";
        } else {
            // Values for the cookies
            $value1 = $token;
            $value2 = $user;
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
        setcookie("token", $value1, $options); 
        setcookie("user",  $value2, $options); 
    }
}
