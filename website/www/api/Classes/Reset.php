<?php

namespace Classes;

class Reset {
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
            case "reset_password":
                $result = $this->resetPassword();
                break;
            
            case "reset_validate":
                $result = $this->validatePassword();
                break;
            
            case "reset_update":
                $result = $this->updatePassword();
                break;
        }
        
        return $result;
    }
    
    private function resetPassword() {
        
        // Try to get the expected parameters
        $email = $this->parameters->getEmail();
        
        // Retrieve the user if it exists with this email address
        $user = $this->user->retrieveUserFromEmail($email);
        if (isset($user)) {

            // Update the parameters with the user
            $id   = $user["id"];
            $name = $user["name"];

            // Invalidate all previous tokens
            $this->token->invalidateResetTokens($id);

            // Generate new token
            $token = $this->token->createResetToken($id);

            // Send the new token via email
            $this->token->sendResetToken($email, $name, $token);
            
        } else {
            // Keep response timing similar even when the email is not found.
            usleep(500000);
        }
    }
    
    private function validatePassword() {
        
        // Try to get the expected parameters
        $token_hex = $this->parameters->getToken();
        
        // Retrieve the token
        $token = $this->token->retrieveResetToken($token_hex);   

        // Store the user ID of this token (if set)
        $id = $token["user_id"];    

        // Get user from database
        $user = $this->user->getUser(id:$id);

        // Update the parameters with the user
        $name = $user["name"];

        // Set the data
        return [
            "name" => $name
        ];
    }
    
    private function updatePassword() {
        
        // Try to get the expected parameters
        $token_hex = $this->parameters->getToken();
        $pass = $this->parameters->getPass2(); 
        
        // Retrieve the token
        $token = $this->token->retrieveResetToken($token_hex);   

        // Store the user ID and token ID of this token (if set)
        $id       = $token["user_id"];
        $token_id = $token["id"];

        // Get user from database
        $user = $this->user->getUser(id:$id);

        // Update the parameters with the user
        $email = $user["email"];
        $name  = $user["name"];

        // Generate the password hash
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        // Update the password
        $update = ["pass_hash" => $hash];
        $this->user->updateUser($id, $update);

        // In case of no errors, send an update mail
        $this->sendResetConfirmation($email, $name);

        // Invalidate the token
        $this->token->updateResetToken($token_id);
    }
    
    // Function to send a verification token
    private function sendResetConfirmation($email, $name) {
        
        // To send emails
        $mailer = new Mailer();
    
        // The recipient to send the email to
        $recipient = [
            "email" => $email,
            "name"  => $name
        ];

        // Get the email subject and body
        $subject = getString("reset.success");
        $body    = getString("update.confirm");

        // Insert the name and token
        $body1 = str_replace("[user]", $name, $body);

        // Insert all the data to send the mail
        $mailer->sendMail($recipient, $subject, $body1);
    }
}
