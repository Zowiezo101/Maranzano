<?php

// Include login details and functions that are used by multiple files
require __DIR__ . "/../base.php";

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
    
        if (!isset($error)) {
            // Invalidate all previous tokens for this user
            invalidateResetTokens($conn, $user);
        }
        
        if (!isset($error)) {
            // Generate the token to reset the password
            $token = createResetToken($conn, $user);
        }
        
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

// Function to invalidate all previous tokens
function invalidateResetTokens($conn, $user) {
    
    // Invalidate previous tokens
    invalidateTokens($conn, "reset_pass", $user["id"]);
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
    $url = getURL("/api/reset?token=".$token);

    // Get the email body
    $body = getString("reset.body");
    
    // Insert the name and token
    $body_user = str_replace("[user]", $recipient["name"], $body);
    $body_url = str_replace("[url]", $url, $body_user);
    
    // Insert all the data to send the mail
    sendMail($recipient, $subject, $body_url);
}
