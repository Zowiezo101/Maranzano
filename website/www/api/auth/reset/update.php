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
$token = trim(isset($input["token"]) ? $input["token"] : "");
$pass1 = trim(isset($input["pass1"]) ? $input["pass1"] : "");
$pass2 = trim(isset($input["pass2"]) ? $input["pass2"] : "");

// Validate the information
if (connectDatabase($conn) && 
        validateToken($token) && 
        validatePass($pass1, $pass2)) {
    
    // Retrieve the token
    $result = retrieveResetToken($conn, $token);
    
    if (!isset($error)) {
        // Set the user as verified
        $user = retrieveUserFromId($conn, $result["user_id"]);
    }
    
    if(!isset($error)) {
        // Set the new password
        updateUserPassword($conn, $user["id"], $pass1);
    }
    
    if (!isset($error)) {
        // In case of no errors, send an update mail
        sendResetConfirmation($user);
    }
    
    if (!isset($error)) {
        // Invalidate the token
        invalidateResetToken($conn, $result);
    }
}

// Send the results back to the requester
sendMessage($error);

/* 
 * The functions 
 */

function validatePass($pass1, $pass2) {
    global $error;
    
    // Check that the password is longer than 8 characters and
    // make sure both passwords are the same
    if(strlen($pass1) < 8){
        $error = "auth.pass1.invalid";
    } else if($pass1 !== $pass2){
        $error = "auth.pass2.invalid";
    }
    
    return $error === null;
}

function retrieveResetToken($conn, $token) {
    
    // Retrieve an existing token
    $result = retrieveToken($conn, "reset_pass", $token);
    
    return $result;
}

// Function to send a verification token
function sendResetConfirmation($recipient) {

    // Set the subject line
    $subject = getString("reset.success");

    // Get the email body
    $body = getString("reset.confirm");
    
    // Insert the name and token
    $body_user = str_replace("[user]", $recipient["name"], $body);
    
    // Insert all the data to send the mail
    sendMail($recipient, $subject, $body_user);
}

function invalidateResetToken($conn, $token) {
    
    // Invalidate the token
    invalidateToken($conn, "reset_pass", $token);
}

function prepareCard() {
    global $error;
    
    if(isset($error)) {
        // There's an error
        $header = getString("global.error");
        $title = getString($error);
        $body = getString("reset.again");
    } else {
        // No error
        $header = getString("reset.success");
        $title = getString("global.close");
        $body = "";
    }
    
    $button = ["text" => getString("verify.home"), "url" => "/"];
    
    $card = ["header" => $header, "title" => $title, "body" => $body, "button" => $button];
    return $card;
}
