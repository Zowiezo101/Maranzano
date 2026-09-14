<?php

namespace Classes;

use Classes\Auth;
use Classes\Mailer;
use PDO;

class Token {
    private $conn;
    private $message;
    private $mailer;
    
    public function __construct($message) {
        
        // For the error messages
        $this->setMessage($message);
        
        // To send emails
        $this->mailer = new Mailer();
    }
    
    /**
     * Setters & Getters
     */
    
    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function setConnection($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Verify tokens
     */
    
    public function createVerifyToken($params) {
        // This token expires in 30 minutes
        $expiry_time = 30;
        return $this->createToken("verify_user", $params, $expiry_time);
    }
    
    public function updateVerifyToken($params) {
        $this->updateToken("verify_user", $params);
    }
    
    public function retrieveVerifyToken($params) {
        return $this->retrieveToken("verify_user", $params);
    }
    
    public function invalidateVerifyTokens($params) {
        $this->invalidateTokens("verify_user", $params);
    }
    
    public function sendVerifyToken($params) {
    
        // The recipient to send the email to
        $recipient = [
            "email" => $params[Auth::PARAM_EMAIL],
            "name"  => $params[Auth::PARAM_USER]
        ];

        // Get the email subject and body
        $subject = getString("verify.subject");
        $body    = getString("verify.body");
        
        // The user name and token
        $user  = $params[Auth::PARAM_USER];
        $token = $params[Auth::PARAM_TOKEN];
        
        // The URL to insert
        $url = getURL("/auth/verify?token=".$token);

        // Insert the name and url
        $body1 = str_replace("[user]", $user, $body);
        $body2 = str_replace("[url]",  $url,  $body1);

        // Insert all the data to send the mail
        $this->mailer->sendMail($recipient, $subject, $body2);
    }
    
    
    /**
     * Reset tokens
     */
    
    public function createResetToken($params) {
        // This token expires in 30 minutes
        $expiry_time = 30;
        return $this->createToken("reset_pass", $params, $expiry_time);
    }
    
    public function updateResetToken($params) {
        $this->updateToken("reset_pass", $params);
    }
    
    public function retrieveResetToken($params) {
        return $this->retrieveToken("reset_pass", $params);
    }
    
    public function invalidateResetTokens($params) {
        $this->invalidateTokens("reset_pass", $params);
    }
    
    public function sendResetToken($params) {
    
        // The recipient to send the email to
        $recipient = [
            "email" => $params[Auth::PARAM_EMAIL],
            "name"  => $params[Auth::PARAM_USER]
        ];

        // Get the email subject and body
        $subject = getString("reset.subject");
        $body    = getString("reset.body");
        
        // The user name and token
        $user  = $params[Auth::PARAM_USER];
        $token = $params[Auth::PARAM_TOKEN];
        
        // The URL to insert
        $url = getURL("/auth/reset?token=".$token);

        // Insert the name and url
        $body1 = str_replace("[user]", $user, $body);
        $body2 = str_replace("[url]",  $url,  $body1);

        // Insert all the data to send the mail
        $this->mailer->sendMail($recipient, $subject, $body2);
    }
    
    
    /**
     * Login tokens
     */
    
    public function createLoginToken($params) {
        // Use password_hash instead of SHA256
        $password_hash = true;
        
        // This token expires in 30 hours (60 min * 30 hours)
        $expiry_time = 60 * 30;
        return $this->createToken("login_user", $params, $expiry_time, $password_hash);
    }
    
    public function updateLoginToken($params) {
        $this->updateToken("login_user", $params);
    }
    
    public function retrieveLoginTokenFromUser($params) {
        return $this->retrieveTokenFromUser("login_user", $params);
    }
    
    public function invalidateLoginTokens($params) {
        $this->invalidateTokens("login_user", $params);
    }
    
    public function sendLoginToken($params) {
        $this->sendToken("login_user", $params);
    }
    
    /**
     * General tokens
     */
    
    public function createToken($table, $params, $expiry_time, $password_hash = false) {
        
        // Create a new token
        $sql = "INSERT INTO {$table} (user_id, token, expires_at) "
                . "VALUES (:user_id, :token, :expires_at)";

        // Prepare query statement
        $stmt = $this->conn->prepare($sql);
        
        // Generating the token
        $token = bin2hex(random_bytes(50));

        // Genereate the expire date for the verification
        $expiry_date = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                            ->modify('+' . $expiry_time . ' minutes')
                            ->format('Y-m-d H:i:s');
        
        if ($password_hash == true) {
            // Use password_hash (Can only retrieve tokens using user info)
            $hash = password_hash($token, PASSWORD_DEFAULT);
        } else {
            // Use SHA256 (Can retrieve token using token value)
            $hash = hash('sha256', $token);
        }

        // Bind the parameter
        $stmt->bindValue(":user_id", $params[Auth::PARAM_ID], PDO::PARAM_STR);
        $stmt->bindValue(":token", $hash, PDO::PARAM_STR);
        $stmt->bindValue(":expires_at", $expiry_date, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Check that the token has been properly created
        $id = $this->conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue is the token hasn't been created
            $this->message->throwError();
        }
        
        return $token;
    }
    
    public function updateToken($table, $params) {
        // The token is found, update it in the token table
        $sql = "UPDATE {$table} SET used=1 WHERE id = :id";
    
        // Prepare query statement
        $stmt = $this->conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":id", $params[Auth::PARAM_TOKEN_ID], PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();
    }
    
    public function retrieveToken($table, $params) {
        
        // Retrieve the token from the token table
        $sql = "SELECT id, user_id, token FROM {$table} "
                . "WHERE token = :token AND used = 0 AND expires_at >= UTC_TIMESTAMP() LIMIT 1";
    
        // Prepare query statement
        $stmt = $this->conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":token", hash('sha256', $params[Auth::PARAM_TOKEN]), PDO::PARAM_STR);  

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        if (!isset($result)) {
            // If no token could be found, then it's invalid
            $this->message->setError("auth.token.invalid", Message::CODE_INVALID);
            $this->message->throwError();
        }
        
        return $result;
    }
    
    public function retrieveTokenFromUser($table, $params) {
        
        // Retrieve the token from the token table
        $sql = "SELECT {$table}.id, {$table}.token FROM {$table} "
                . "JOIN users ON users.id = {$table}.user_id "
                . "WHERE users.name = :name AND used = 0 AND expires_at >= UTC_TIMESTAMP() LIMIT 1";
    
        // Prepare query statement
        $stmt = $this->conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":name", $params[Auth::PARAM_USER], PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        if (!isset($result)) {
            // If no token could be found, then it's invalid
            $this->message->setError("auth.token.invalid", Message::CODE_INVALID);
            $this->message->throwError();
        }
        
        return $result;
    }
    
    public function invalidateTokens($table, $params) {
        
        // Invalidate all tokens of this user
        $sql = "UPDATE {$table} SET used = 1 WHERE user_id = :user_id AND used = 0";

        // Prepare query statement
        $stmt = $this->conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":user_id", $params[Auth::PARAM_ID], PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();
    }
    
}
