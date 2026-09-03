<?php
  
// Include login details and functions that are used by multiple files
require '../../../settings.conf';
require 'base.php';

// Required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: ".$domain_name);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: access");

$conn = null;
$error = null;

// Get the input data
$input_raw = (array) json_decode(file_get_contents('php://input'));
$input = filter_var_array($input_raw);

// Trim it and put it in seperate vars
$email = trim($input["email"]);

// Validate the information
if (connectDatabase($conn, $error)) {
    $result = validateEmail($conn, $email, $error);
    
    if (isset($result)) {
//        updateVerifyToken($conn, $token, $error);
//        updateUser($conn, $token, $error);
    }
} 

/* 
 * The functions 
 */

function validateEmail($conn, $email, &$error) {
    $result = null;
    
    // Check if this e-mail address is a proper e-mail address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // See if the email address already exists
        $sql = "SELECT id, name, email FROM users WHERE email = :email";
        
        // Prepare query statement
        $stmt = $conn->prepare($sql);
        
        // Bind the parameter
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // This is a registered user
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        // Not a valid email address
        $error = "auth.email.invalid";
    }
    
    return $result;
}

// Send an e-mail IF the user has an account

// Add a token to the email and to the user in the database

// Use the token in the email to verify the user

// Show them the reset option and use that same token to send the data back

// Send the results back to the requester
sendMessage($error);
