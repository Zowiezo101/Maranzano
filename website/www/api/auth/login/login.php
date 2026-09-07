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

// Output data
$data = null;

// Get the input data
$input_raw = (array) json_decode(file_get_contents('php://input'));
$input = filter_var_array($input_raw);

// Trim it and put it in seperate vars
$email = trim(isset($input["email"]) ? $input["email"] : "");
$pass  = trim(isset($input["pass"])  ? $input["pass"]  : "");

// Validate the information
if (connectDatabase($conn)) { 
    $user = retrieveUserFromEmail($conn, $email);
    
    // Only continue IF the user has an account
    if (isset($user)) {
        if (!isset($error)) {
            // Check the password
            verifyPass($user, $pass);
        }
    
        if (!isset($error)) {
            // Invalidate all previous tokens for this user
            invalidateLoginTokens($conn, $user);
        }
        
        if (!isset($error)) {
            // Generate the token to reset the password
            $token = createLoginToken($conn, $user);
            
            // The data to send to the user
            $data = [
                "token" => $token,
                "user_id" => $user["id"],
                "user_name" => $user["name"]
            ];
        }
    } else {
        // Let the user know logging in failed
        $error = "auth.login.invalid";
    }
}

// Send the results back to the requester
sendMessage($error, $data);

/* 
 * The functions 
 */

function verifyPass($user, $pass) {
    global $error;
    
    if (!password_verify($pass, $user["pass_hash"])) {
        // The password doesn't match the hash
        $error = "auth.login.invalid";
    }
}

// Function to invalidate all previous tokens
function invalidateLoginTokens($conn, $user) {
    
    // Invalidate previous tokens
    invalidateTokens($conn, "login_user", $user["id"]);
}

function createLoginToken($conn, $user) {
    
    // 30 hours in minutes
    $expiry_time = 60 * 30;
    
    // Create the token for the verification
    $token = createToken($conn, "login_user", $user["id"], $expiry_time);
    
    return $token;
}
