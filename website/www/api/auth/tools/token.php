<?php

function validateToken($token) {
    global $error;
    
    // Check if the token is set
    if (!isset($token) || (strlen($token) !== 100)) {
        $error = "auth.token.invalid";
    }
    
    return $error === null;
}

function invalidateToken($conn, $table, $token) {
    global $error;

    try {
        // The token is found, update it in the register token table
        $sql = "UPDATE {$table} SET used=1 WHERE id = :id";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":id", $token["id"], PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();
    } catch (PDOException) {
        $error = "auth.db_error";
    }
}

function invalidateTokens($conn, $table, $user_id) {
    global $error;
    
    try {
        // Invalidate all tokens of this user
        $sql = "UPDATE {$table} SET used = 1 WHERE user_id = :user_id AND used = 0";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();
    } catch (PDOException) {
        $error = "auth.db_error";
    }
}

function createToken($conn, $table, $user_id, $expiry_time = 30) {    
    global $error;
        
    // Generate the token to verify the email-address
    $token = bin2hex(random_bytes(50));
    
    try {
        // Create a new token
        $sql = "INSERT INTO {$table} (user_id, token, expires_at) "
                . "VALUES (:user_id, :token, :expires_at)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Genereate the expire date for the verification
        $expiry_date = (new DateTimeImmutable('now', new DateTimeZone('UTC')))
                            ->modify('+' . $expiry_time . ' minutes')
                            ->format('Y-m-d H:i:s');

        // Bind the parameter
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindValue(":token", hash('sha256', $token), PDO::PARAM_STR);
        $stmt->bindValue(":expires_at", $expiry_date, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();
    } catch (PDOException) {
        $error = "auth.db_error";
    }
    
    return $token;
}

function retrieveToken($conn, $table, $token) {
    global $error;

    try {
        // Retrieve the token from the verify token table
        $sql = "SELECT id, user_id FROM {$table} "
                . "WHERE token = :token AND used = 0 AND expires_at >= UTC_TIMESTAMP() LIMIT 1";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":token", hash('sha256', $token), PDO::PARAM_STR);  

        // Execute the statement
        $stmt->execute();

        // Get the user in the database with this token
        $result = getResults($stmt);
        
        if (!isset($result)) {
            // If no user could be found, the token is invalid
            $error = "auth.token.invalid";
        }
    } catch (PDOException) {
        $result = null;
        $error = "auth.db_error";
    }
    
    return $result;
}
