<?php

namespace Classes;

class Register {
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
            case "register_user":
                $result = $this->registerUser();
                break;
            
            case "register_verify":
                $result = $this->verifyUser();
                break;
        }
        
        return $result;
    }
    
    private function registerUser() {
        
        // Try to get the expected parameters
        $email = $this->parameters->getEmail();
        $player = $this->parameters->getPlayer();
        $pass = $this->parameters->getPass2();
        
        // Check if the email address is available
        $this->user->isEmailAvailable($email);
        
        // Check if the player name is available
        $this->player->isPlayerAvailable($player);

        // Insert user in database
        $id = $this->user->createUser($email, $player, $pass);

        // Create a new player for this user
        $this->player->createPlayer($id, $player);
        
        $this->sendVerificationToken();
    }
    
    private function verifyUser() {
        
        // Try to get the expected parameters
        $token_hex = $this->parameters->getToken();
        
        // Retrieve the token
        $token = $this->token->retrieveVerifyToken($token_hex);

        // Store the user ID and token ID of this token (if set)
        $id       = $token["user_id"];
        $token_id = $token["id"];

        // Set the user as verified
        $update = ["is_verified" => true];
        $this->user->updateUser($id, $update);

        // Invalidate the token
        $this->token->updateVerifyToken($token_id);
    }
    
    public function sendVerificationToken($id) {
        
        // Retrieve the user
        $user = $this->user->getUser(id:$id);
        
        $email = $user["email"];
        $name = $user["name"];

        // Invalidate all previous tokens
        $this->token->invalidateVerifyTokens($id);

        // Genereate new token
        $token = $this->token->createVerifyToken($id);

        // Send the new token via email
        $this->token->sendVerifyToken($email, $name, $token);
    }
}
