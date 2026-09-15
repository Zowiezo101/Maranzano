<?php

namespace Classes;

use PDO;

class Database {
    private $conn;
    private $message;
    
    public function __construct($message) {
        global $servername, $db_username, 
               $db_password, $db_database;
        
        // For the error messages
        $this->setMessage($message);

        try {
            // First make sure we can connect to the database
            $this->conn = new PDO("mysql:host={$servername};dbname={$db_database};charset=utf8", 
                            $db_username, $db_password,
                            [PDO::ATTR_EMULATE_PREPARES => false, 
                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException) {
            $this->message->setError("auth.db_error", Message::CODE_ERROR);
        }
    }
    
    public function __destruct() {
        $this->conn = null;
    }
    
    /**
     * Setters & Getters
     */
    
    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    /**
     * User data function
     */

    public function createUser($params) {
        // The parameters
        $email = $params[Auth::PARAM_EMAIL];
        $user  = $params[Auth::PARAM_USER];
        $pass  = $params[Auth::PARAM_PASS];

        // Generate the password hash
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        // All the data has been checked, meaning that we can now safely create a new user
        $sql = "INSERT INTO users (name, email, pass_hash, is_verified) "
                . "VALUES (:name, :email, :pass_hash, :is_verified)";

        // Prepare query statement
        $stmt = $this->conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":name", $user, PDO::PARAM_STR);
        $stmt->bindValue(":pass_hash", $hash, PDO::PARAM_STR);
        $stmt->bindValue(":is_verified", 0, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Insert the ID into the parameter array
        $id = $this->conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue is the user hasn't been created
            $this->message->throwError();
        }
        
        return $id;
    }
    
    public function updateUser($id, $update) {        
        // The values to update for the user
        $update_arr = [];
        foreach ($update as $key => $value) {
            $update_arr[] = "{$key} = :{$key}";
        }
        
        // The SQL for updating the values
        $update_sql = implode(', ', $update_arr);
        
        // Set the SQL
        $sql = "UPDATE users SET {$update_sql} WHERE id = :id";
    
        // Prepare query statement
        $stmt = $this->conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);  
        
        // Bind the new values as well
        foreach ($update as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Execute the statement
        $stmt->execute();
    }
    
    public function getUser($parameters) {
        // Try to get the user with the email address
        if (isset($parameters[Auth::PARAM_EMAIL])) {
            $email_user = $this->retrieveUserFromEmail($parameters[Auth::PARAM_EMAIL]);
        }
        
        // Try to get the user with the ID
        if (isset($parameters[Auth::PARAM_ID])) {
            $id_user = $this->retrieveUserFromId($parameters[Auth::PARAM_ID]);
        }
        
        // Try to get the user with the username
        if (isset($parameters[Auth::PARAM_USER])) {
            $name_user = $this->retrieveUserFromName($parameters[Auth::PARAM_USER]);
        }
        
        // If one of them has a match, the first one will be returned
        $user = isset($email_user) ? $email_user : (
                    isset($id_user) ? $id_user : (
                        isset($name_user) ? $name_user : null
                    )
                );
        
        if (!isset($user)) {                
            // Do NOT continue if this user isn't found
            $this->message->throwError();
        }
        
        // Return the user
        return $user;
    }
    
    /**
     * Functions to retrieve users from different parameters
     */
    
    public function retrieveUserFromEmail($email) {
        // See if the email address already exists
        $sql = "SELECT id, name, email, pass_hash, is_verified FROM users WHERE email = :email";

        // Prepare query statement
        $stmt = $this->conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    public function retrieveUserFromId($id) {
        // Retrieve the user with this ID
        $sql = "SELECT id, name, email, pass_hash, is_verified FROM users WHERE id = :id";

        // Prepare query statement
        $stmt = $this->conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    public function retrieveUserFromName($name) {
        // See if the name already exists
        $sql = "SELECT id, name, email, pass_hash, is_verified FROM users WHERE name = :name";

        // Prepare query statement
        $stmt = $this->conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
}
