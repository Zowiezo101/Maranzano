<?php

function retrieveUserFromId($conn, $id) {
    global $error;
    
    try {
        // Retrieve the user
        $sql = "SELECT id, name, email FROM users WHERE id = :id";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
    
        if (!isset($result)) {
            // If no user could be found, the token is invalid
            $error = "auth.token.invalid";
        }
    } catch (Exception) {
        $result = null;
        $error = "auth.db_error";
    }

    return $result;
}

function retrieveUserFromEmail($conn, $email) {
    global $error;
    
    $result = null;
    
    // Check if this e-mail address is a proper e-mail address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // See if the email address already exists
            $sql = "SELECT id, name, email, pass_hash FROM users WHERE email = :email";

            // Prepare query statement
            $stmt = $conn->prepare($sql);

            // Bind the parameter
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();
            
            // Get the results
            $result = getResults($stmt);
        } catch (Exception) {
            $error = "auth.db_error";
        }
    } else {
        // Not a valid email address
        $error = "auth.email.invalid";
    }
    
    return $result;
}

function updateUserVerified($conn, $token) {    
    global $error;

    try {
        // Create a query to update this user
        $sql = "UPDATE users SET is_verified=1 WHERE id = :id";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":id", $token["user_id"], PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();
    } catch (Exception) {
        $error = "auth.db_error";
    }
}

function updateUserPassword($conn, $id, $pass) {
    global $error;
    
    // Generate the password hash
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    try {
        // Create a query to update this user
        $sql = "UPDATE users SET pass_hash=:pass_hash WHERE id = :id";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);  
        $stmt->bindValue(":pass_hash", $hash, PDO::PARAM_STR);  

        // Execute the statement
        $stmt->execute();
    } catch (Exception) {
        $error = "auth.db_error";
    }
}
