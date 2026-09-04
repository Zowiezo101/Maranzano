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
$user  = trim($input["user"]);
$pass1 = trim($input["pass1"]);
$pass2 = trim($input["pass2"]);

// Validate the information
if (connectDatabase($conn) && 
        validateEmail($conn, $email) && 
        validateUser($conn, $user) && 
        validatePass($pass1, $pass2)) {    
    
    // Insert the data into the database
    $user_id = registerUser($conn, $email, $user, $pass1);
    
    if (!isset($error)) {
        // Generate the token to verify this user
        $token = createVerifyToken($conn, $user_id);
    }
    
    if (!isset($error)) {
        // In case of no errors, send a verification token
        sendVerificationToken($email, $user, $token);
    }
}

// Send the results back to the requester
sendMessage($error);

/* 
 * The functions 
 */

function validateEmail($conn, $email) {
    global $error;
    
    // Check if this e-mail address is a proper e-mail address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // See if the email address already exists
            $sql = "SELECT id FROM users WHERE email = :email";

            // Prepare query statement
            $stmt = $conn->prepare($sql);

            // Bind the parameter
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();
            
            isTaken($stmt, "auth.email.taken");
        } catch (Exception) {
            $error = "auth.db_error";
        }
    } else {
        // Not a valid email address
        $error = "auth.email.invalid";
    }
    
    return $error === null;
}

function validateUser($conn, $user) {
    global $error;
    
    // Check if this username is a proper username
    if (preg_match('/^[a-zA-Z0-9_]+$/', $user)) {
        try {
            // See if the username already exists
            $sql = "SELECT id FROM users WHERE name = :name";

            // Prepare query statement
            $stmt = $conn->prepare($sql);

            // Bind the parameter
            $stmt->bindValue(":name", $user, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();

            isTaken($stmt, "auth.user.taken");
        } catch (Exception) {
            $error = "auth.db_error";
        }
    } else {
        // Not a valid username
        $error = "auth.user.invalid";
    }
    
    return $error === null;
}

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

function registerUser($conn, $email, $user, $pass) {
    global $error;
    
    // Generate the password hash
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    
    // Variable for user_id
    $user_id = null;
    
    try {
    
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

        if (null !== getResults($stmt)) {
            $user_id = $conn->lastInsertId();
        }
    } catch (Exception) {
        $error = "auth.db_error";
    }
    
    return $user_id;
}

// Function to create a verification token
function createVerifyToken($conn, $user_id) {
    
    // Create the token for the verification
    $token = createToken($conn, "verify_user", $user_id);
    
    return $token;
}

// Function to send a verification token
function sendVerificationToken($email, $user, $token) {
    
    // The recipient to send the email to
    $recipient = [
        "email" => $email,
        "name" => $user
    ];

    // Set the subject line
    $subject = getString("verify.subject");
    
    // The URL to verify the account
    $url = getURL("/api/auth/verify.php?token=".$token);

    // Get the email body
    $body = getString("verify.body");
    
    // Insert the name and token
    $body_user = str_replace("[user]", $user, $body);
    $body_url = str_replace("[url]", $url, $body_user);
    
    // Insert all the data to send the mail
    sendMail($recipient, $subject, $body_url);
}
