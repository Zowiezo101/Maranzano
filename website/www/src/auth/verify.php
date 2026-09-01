<?php

// Include login details and functions that are used by multiple files
require '../../../settings.conf';
require 'base.php';

// Required headers
// TODO: Correct headers for outside users
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: ".$domain_name);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: access");

$conn = null;
$error = null;

// Get the input data
$token = filter_input(INPUT_GET, "token");

// Connect to the database
if (connectDatabase($conn, $error)) {
    validateToken($conn, $token, $error);
}

// The message to be sent
$message = [
    "error" => (isset($strings[$error]) ? $strings[$error] : $error)
];

// Send the message
echo json_encode($message);


/* 
 * The functions 
 */

function validateToken($conn, $token, &$error) {
    global $domain_name;
    
    // TODO: Make this function smaller and nicer to read
    
    // Check if the token is set
    if (isset($token)) {
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

                // Create another query to update this user
                $sql = "UPDATE users SET is_verified=1, token=NULL WHERE id = :id";

                try {
                    // Prepare query statement
                    $stmt = $conn->prepare($sql);    

                    // Bind the parameter
                    $stmt->bindValue(":id", $user["id"], PDO::PARAM_INT);  

                    // Execute the statement
                    $stmt->execute();
                    
                    // If we are still in this block, it means things have succeeded
                    // Go to the members page
                    // TODO: Login
                    $url = $domain_name."\src\user\member.php";
                    if( headers_sent() ) { 
                        echo("<script>location.href=".$url."</script>"); 
                    } else { 
                        header("Location: $url"); 
                    }
                } catch (Exception) {
                    $error = "auth.db_error";
                }
            } else {
                $error = "auth.token.invalid";
            }
        } catch (Exception) {
            $error = "auth.db_error";
        }
    } else {
        $error = "auth.token.invalid";
    }
}
?>