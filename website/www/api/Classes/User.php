<?php

namespace Classes;

use PDO;

class User {
    // Other classes
    protected $db;
    
    public function __construct() {
        // To connect to the database
        $this->db = new Database();
    }
    
    /**
     * Availabilty function
     */
    public function isEmailAvailable($email) {
        $user = $this->retrieveUserFromEmail($email);
        
        if (isset($user)) {
            // This email address is not available
            throwError("auth.email.taken", Message::CODE_INVALID);
        }
    }
    
    /**
     * User data function
     */

    public function createUser($email, $user, $pass) {
        $conn = $this->db->getConnection();

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
            throwError();
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
    
    public function getUser($email = null, $id = null, $name = null) {        
        // Try to get the user with the email address
        if (isset($email)) {
            $email_user = $this->retrieveUserFromEmail($email);
        }
        
        // Try to get the user with the ID
        if (isset($id)) {
            $id_user = $this->retrieveUserFromId($id);
        }
        
        // Try to get the user with the username
        if (isset($name)) {
            $name_user = $this->retrieveUserFromName($name);
        }
        
        // If one of them has a match, the first one will be returned
        $user = isset($email_user) ? $email_user : (
                    isset($id_user) ? $id_user : (
                        isset($name_user) ? $name_user : null
                    )
                );
        
        if (!isset($user)) {                
            // Do NOT continue if this user isn't found
            throwError();
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
}
