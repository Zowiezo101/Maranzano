<?php

namespace Classes;

class Reset extends Auth {
    
    public function resetPassword() {
        
        // Possible database error, do NOT continue
        if ($this->hasError()) {
            return;
        }
        
        // Get only these parameters, all other parameters are ignored
        $param_list = [
            self::PARAM_EMAIL
        ];
            
        // Retrieve the parameters
        $parameters = $this->getParameters($param_list);
        
        try {
            // Validate parameters
            if ($this->validateParameters($param_list, $parameters)) {                
        
                // Get user from database
                $user = $this->user->getUser($parameters);
                    
                // Update the parameters with the user
                $parameters[self::PARAM_ID]   = $user["id"];
                $parameters[self::PARAM_USER] = $user["name"];
                    
                // Invalidate all previous tokens
                $this->token->invalidateResetTokens($parameters);

                // Generate new token
                $token = $this->token->createResetToken($parameters);

                // Insert the token into the parameter array
                $parameters[self::PARAM_TOKEN] = $token;

                // Send the new token via email
                $this->token->sendResetToken($parameters);
            } else {
                // We're not gonna let the client know if anything went wrong
                $this->clearError();
                
                // Keep response timing similar even when the email is not found.
                usleep(500000);
            }
            
        } catch (\Exception) {
            // Something went wrong
            $this->setError("reset.error", Message::CODE_ERROR);
        }
    }
    
    public function validatePassword() {
        
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
                $token = $this->token->retrieveResetToken($parameters);   
                
                // Store the user ID and token ID of this token (if set)
                $parameters[self::PARAM_ID]       = $token["user_id"];
                $parameters[self::PARAM_TOKEN_ID] = $token["id"];      
        
                // Get user from database
                $user = $this->user->getUser($parameters);
                    
                // Update the parameters with the user
                $parameters[self::PARAM_EMAIL] = $user["email"];
                $parameters[self::PARAM_USER]  = $user["name"];
                
                // Set the data
                $this->message->setData([
                    "name" => $parameters[self::PARAM_USER]
                ]);
            } else {
                $this->setError("validate.error");
            }
            
        } catch (\Exception) {
            // Something went wrong
            $this->setError("validate.error", Message::CODE_ERROR);
        }
    }
    
    public function updatePassword() {
        
        // Possible database error, do NOT continue
        if ($this->hasError()) {
            return;
        }
        
        // Get only these parameters, all other parameters are ignored
        $param_list = [
            self::PARAM_TOKEN,
            self::PARAM_PASS,
            self::PARAM_PASS2
        ];
            
        // Retrieve the parameters
        $parameters = $this->getParameters($param_list);
        
        try {            
            // Validate parameters
            if ($this->validateParameters($param_list, $parameters)) {  
        
                // Retrieve the token
                $token = $this->token->retrieveResetToken($parameters);   
                
                // Store the user ID and token ID of this token (if set)
                $parameters[self::PARAM_ID]       = $token["user_id"];
                $parameters[self::PARAM_TOKEN_ID] = $token["id"];
        
                // Get user from database
                $user = $this->user->getUser($parameters);
                    
                // Update the parameters with the user
                $parameters[self::PARAM_EMAIL] = $user["email"];
                $parameters[self::PARAM_USER]  = $user["name"];
    
                // Generate the password hash
                $hash = password_hash($parameters[self::PARAM_PASS], PASSWORD_DEFAULT);
    
                // Update the password
                $update = ["pass_hash" => $hash];
                $this->user->updateUser($parameters[self::PARAM_ID], $update);

                // In case of no errors, send an update mail
                $this->sendResetConfirmation($parameters);

                // Invalidate the token
                $this->token->updateResetToken($parameters);
            } else {
                $this->setError("update.error");
            }
            
        } catch (\Exception) {
            // Something went wrong
            $this->setError("update.error", Message::CODE_ERROR);
        }
    }
    
    // Function to send a verification token
    private function sendResetConfirmation($params) {
        
        // To send emails
        $mailer = new Mailer();
    
        // The recipient to send the email to
        $recipient = [
            "email" => $params[Auth::PARAM_EMAIL],
            "name"  => $params[Auth::PARAM_USER]
        ];

        // Get the email subject and body
        $subject = getString("reset.success");
        $body    = getString("update.confirm");
        
        // The user name
        $user  = $params[Auth::PARAM_USER];

        // Insert the name and token
        $body1 = str_replace("[user]", $user, $body);

        // Insert all the data to send the mail
        $mailer->sendMail($recipient, $subject, $body1);
    }
}
