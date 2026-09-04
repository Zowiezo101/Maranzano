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

// Global connection paramater for this file
$conn = null;

// Get the input data
$input_raw = (array) json_decode(file_get_contents('php://input'));
$input = filter_var_array($input_raw);

// Trim it and put it in seperate vars
$email = trim($input["email"]);

// Validate the information
if (connectDatabase($conn)) {
    $user = retrieveUserFromEmail($conn, $email);
    
    // Send an e-mail IF the user has an account
    if (isset($user)) {
        // Generate the token to reset the password
        $token = createResetToken($conn, $user);
        
        if (!isset($error)) {
            // In case of no errors, send a reset token
            sendResetToken($user, $token);
        }
    } else {
        // Keep response timing similar even when the email is not found.
        usleep(500000);
    }
} 

// Send the results back to the requester
sendMessage($error);

/* 
 * The functions 
 */

function retrieveUserFromEmail($conn, $email) {
    global $error;
    
    $result = null;
    
    // Check if this e-mail address is a proper e-mail address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // See if the email address already exists
            $sql = "SELECT id, name, email FROM users WHERE email = :email";

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

function createResetToken($conn, $user) {
    
    // Create the token for the verification
    $token = createToken($conn, "reset_pass", $user["id"]);
    
    return $token;
}

// Function to send a verification token
function sendResetToken($recipient, $token) {

    // Set the subject line
    $subject = getString("reset.subject");
    
    // The URL to verify the account
    $url = getURL("/api/auth/reset.php?token=".$token);

    // Get the email body
    $body = getString("reset.body");
    
    // Insert the name and token
    $body_user = str_replace("[user]", $recipient["name"], $body);
    $body_url = str_replace("[url]", $url, $body_user);
    
    // Insert all the data to send the mail
    sendMail($recipient, $subject, $body_url);
}
