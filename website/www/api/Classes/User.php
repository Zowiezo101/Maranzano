<?php

namespace Classes;

use PDO;

class User {
    // Other classes
    protected $db;
    protected $message;
    
    public function __construct($message = null) {
        
        // For the error messages
        if (isset($message)) {
            $this->setMessage($message);
        } else {
            $this->message = new Message();
        }
        
        // Link the message class for error messages
        $this->db = new Database($this->message);
    }
    
    /**
     * User data function
     */

    public function createUser($params) {
        $conn = $this->db->getConnection();
        
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
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":name", $user, PDO::PARAM_STR);
        $stmt->bindValue(":pass_hash", $hash, PDO::PARAM_STR);
        $stmt->bindValue(":is_verified", 0, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Insert the ID into the parameter array
        $id = $conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue is the user hasn't been created
            $this->message->throwError();
        }
        
        return $id;
    }
    
    public function updateUser($id, $update) {  
        $conn = $this->db->getConnection();
        
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
        $stmt = $conn->prepare($sql);    

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
        $conn = $this->db->getConnection();
        
        // See if the email address already exists
        $sql = "SELECT id, name, email, pass_hash, is_verified FROM users WHERE email = :email";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    public function retrieveUserFromId($id) {
        $conn = $this->db->getConnection();
        
        // Retrieve the user with this ID
        $sql = "SELECT id, name, email, pass_hash, is_verified FROM users WHERE id = :id";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    public function retrieveUserFromName($name) {
        $conn = $this->db->getConnection();
        
        // See if the name already exists
        $sql = "SELECT id, name, email, pass_hash, is_verified FROM users WHERE name = :name";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    /**
     * Setters & Getters
     */
    
    public function setMessage($message) {
        $this->message = $message;
    }
}
