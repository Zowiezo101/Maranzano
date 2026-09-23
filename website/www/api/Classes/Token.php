<?php

namespace Classes;

use PDO;

class Token {
    private $db;
    private $mailer;
    
    public function __construct() {
        
        // To connect to the database
        $this->db = new Database();
        
        // To send emails
        $this->mailer = new Mailer();
    }
    
    /**
     * Setters & Getters
     */
    
    public function setConnection($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Verify tokens
     */
    
    public function createVerifyToken($user_id) {
        // This token expires in 30 minutes
        $expiry_time = 30;
        return $this->createToken("verify_user", $user_id, $expiry_time);
    }
    
    public function updateVerifyToken($token_id) {
        $this->updateToken("verify_user", $token_id);
    }
    
    public function retrieveVerifyToken($token) {
        return $this->retrieveToken("verify_user", $token);
    }
    
    public function invalidateVerifyTokens($user_id) {
        $this->invalidateTokens("verify_user", $user_id);
    }
    
    public function sendVerifyToken($email, $name, $token) {
    
        // The recipient to send the email to
        $recipient = [
            "email" => $email,
            "name"  => $name
        ];

        // Get the email subject and body
        $subject = getString("verify.subject");
        $body    = getString("verify.body");
        
        // The URL to insert
        $url = getURL("/auth/verify?token=".$token);

        // Insert the name and url
        $body1 = str_replace("[user]", $name, $body);
        $body2 = str_replace("[url]",  $url,  $body1);

        // Insert all the data to send the mail
        $this->mailer->sendMail($recipient, $subject, $body2);
    }
    
    
    /**
     * Reset tokens
     */
    
    public function createResetToken($user_id) {
        // This token expires in 30 minutes
        $expiry_time = 30;
        return $this->createToken("reset_pass", $user_id, $expiry_time);
    }
    
    public function updateResetToken($token_id) {
        $this->updateToken("reset_pass", $token_id);
    }
    
    public function retrieveResetToken($token) {
        return $this->retrieveToken("reset_pass", $token);
    }
    
    public function invalidateResetTokens($user_id) {
        $this->invalidateTokens("reset_pass", $user_id);
    }
    
    public function sendResetToken($email, $name, $token) {
    
        // The recipient to send the email to
        $recipient = [
            "email" => $email,
            "name"  => $name
        ];

        // Get the email subject and body
        $subject = getString("reset.subject");
        $body    = getString("reset.body");
        
        // The URL to insert
        $url = getURL("/auth/reset?token=".$token);

        // Insert the name and url
        $body1 = str_replace("[user]", $name, $body);
        $body2 = str_replace("[url]",  $url,  $body1);

        // Insert all the data to send the mail
        $this->mailer->sendMail($recipient, $subject, $body2);
    }
    
    
    /**
     * Login tokens
     */
    
    public function createLoginToken($user_id) {
        // Use password_hash instead of SHA256
        $password_hash = true;
        
        // This token expires in 30 hours (60 min * 30 hours)
        $expiry_time = 60 * 30;
        return $this->createToken("login_user", $user_id, $expiry_time, $password_hash);
    }
    
    public function updateLoginToken($token_id) {
        $this->updateToken("login_user", $token_id);
    }
    
    public function retrieveLoginTokenFromUser($user_name) {
        return $this->retrieveTokenFromUser("login_user", $user_name);
    }
    
    public function invalidateLoginTokens($user_id) {
        $this->invalidateTokens("login_user", $user_id);
    }
    
    /**
     * General tokens
     */
    
    public function createToken($table, $user_id, $expiry_time, $password_hash = false) {
        $conn = $this->db->getConnection();
        
        // Create a new token
        $sql = "INSERT INTO {$table} (user_id, token, expires_at) "
                . "VALUES (:user_id, :token, :expires_at)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);
        
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
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindValue(":token", $hash, PDO::PARAM_STR);
        $stmt->bindValue(":expires_at", $expiry_date, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Check that the token has been properly created
        $id = $conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue is the token hasn't been created
            throwError();
        }
        
        return $token;
    }
    
    public function updateToken($table, $token_id) {
        $conn = $this->db->getConnection();
        
        // The token is found, update it in the token table
        $sql = "UPDATE {$table} SET used=1 WHERE id = :id";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":id", $token_id, PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();
    }
    
    public function retrieveToken($table, $token) {
        $conn = $this->db->getConnection();
        
        // Retrieve the token from the token table
        $sql = "SELECT id, user_id, token FROM {$table} "
                . "WHERE token = :token AND used = 0 AND expires_at >= UTC_TIMESTAMP() LIMIT 1";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":token", hash('sha256', $token), PDO::PARAM_STR);  

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        if (!isset($result)) {
            // If no token could be found, then it's invalid
            throwError("auth.token.invalid", Message::CODE_INVALID);
        }
        
        return $result;
    }
    
    public function retrieveTokenFromUser($table, $user_name) {
        $conn = $this->db->getConnection();
        
        // Retrieve the token from the token table
        $sql = "SELECT {$table}.id, {$table}.user_id, {$table}.token FROM {$table} "
                . "JOIN users ON users.id = {$table}.user_id "
                . "WHERE users.name = :name AND used = 0 AND expires_at >= UTC_TIMESTAMP() LIMIT 1";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":name", $user_name, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    public function invalidateTokens($table, $user_id) {
        $conn = $this->db->getConnection();
        
        // Invalidate all tokens of this user
        $sql = "UPDATE {$table} SET used = 1 WHERE user_id = :user_id AND used = 0";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();
    }
    
}
