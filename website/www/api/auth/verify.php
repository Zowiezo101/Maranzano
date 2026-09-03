<?php

// Include login details and functions that are used by multiple files
require '../../../settings.conf';
require 'base.php';

$conn = null;
$error = null;

// Get the input data
$token = filter_input(INPUT_GET, "token");

// Connect to the database
if (validateToken($token, $error) && connectDatabase($conn, $error)) {
    $user = retrieveUser($conn, $token, $error);
    
    if (isset($user)) {
        updateUser($conn, $user, $error);
    }
}

// Redirect to the verified page to show whether it succeeded or didnt succeed
$url = "/src/user/verified.php";
if (isset($error)) {
    // Add an error if something went wrong
    $url = $url."?e={$error}";
}

// The actual redirect
redirectPage($url);

/* 
 * The functions 
 */

function validateToken($token, &$error) {
    // Check if the token is set
    if (!isset($token)) {
        $error = "auth.token.invalid";
    }
    
    return $error === null;
}

function retrieveUser($conn, $token, &$error) {
    $user = null;
    
    // Retrieve the user where this token belongs to
    $sql = "SELECT * FROM users WHERE token = :token LIMIT 1";

    try {
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":token", $token, PDO::PARAM_STR);  

        // Execute the statement
        $stmt->execute();

        if ($stmt->rowCount() > 0) { 
            // There is a user in the database with this token
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = "auth.token.invalid";
        }
    } catch (Exception) {
        $error = "auth.db_error";
    }
    
    return $user;
}

function updateUser($conn, $user, &$error) {    
    // Create a query to update this user
    $sql = "UPDATE users SET is_verified=1, token=NULL WHERE id = :id";

    try {
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":id", $user["id"], PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();
    } catch (Exception) {
        $error = "auth.db_error";
    }
}
