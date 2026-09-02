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
$user  = trim($input["user"]);
$pass1 = trim($input["pass1"]);
$pass2 = trim($input["pass2"]);

// Validate the information
if (connectDatabase($conn, $error) && validateEmail($conn, $email, $error) && validateUser($conn, $user, $error) && validatePass($pass1, $pass2, $error)) {    
    // Insert the data into the database
    $token = registerUser($conn, $email, $user, $pass1, $error);
    
    // In case of no errors, send a verification code
    sendVerificationCode($email, $user, $token, $error);
}

// The message to be sent
$message = [
    "error" => (hasString($error) ? getString($error) : $error)
];

// Send the message
echo json_encode($message);


/* 
 * The functions 
 */

function validateEmail($conn, $email, &$error) {
    
    // Check if this e-mail address is a proper e-mail address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // See if the email address already exists
        $sql = "SELECT id FROM users WHERE email = :email";
        
        // Prepare query statement
        $stmt = $conn->prepare($sql);
        
        // Bind the parameter
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "auth.email.taken";
        }
    } else {
        // Not a valid email address
        $error = "auth.email.invalid";
    }
    
    return $error === null;
}

function validateUser($conn, $user, &$error) {
    
    // Check if this username is a proper username
    if (preg_match('/^[a-zA-Z0-9_]+$/', $user)) {
        // See if the username already exists
        $sql = "SELECT id FROM users WHERE name = :name";
        
        // Prepare query statement
        $stmt = $conn->prepare($sql);
        
        // Bind the parameter
        $stmt->bindValue(":name", $user, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "auth.user.taken";
        }
    } else {
        // Not a valid username
        $error = "auth.user.invalid";
    }
    
    return $error === null;
}

function validatePass($pass1, $pass2, &$error) {
    
    // Check that the password is longer than 8 characters and
    // make sure both passwords are the same
    if(strlen($pass1) < 8){
        $error = "auth.pass1.invalid";
    } else if($pass1 !== $pass2){
        $error = "auth.pass2.invalid";
    }
    
    return $error === null;
}

function registerUser($conn, $email, $user, $pass, &$error) {
    
    // Generate the password hash
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    
    // Generate the token to verify the email-address
    $token = bin2hex(random_bytes(50));
    
    try {
    
        // All the data has been checked, meaning that we can now safely create a new user
        $sql = "INSERT INTO users (name, email, pass_hash, token, is_verified) "
                . "VALUES (:name, :email, :pass_hash, :token, :is_verified)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":name", $user, PDO::PARAM_STR);
        $stmt->bindValue(":pass_hash", $hash, PDO::PARAM_STR);
        $stmt->bindValue(":token", $token, PDO::PARAM_STR);
        $stmt->bindValue(":is_verified", 0, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();
    } catch (Exception) {
        $error = "auth.db_error";
    }
    
    return $token;
}
