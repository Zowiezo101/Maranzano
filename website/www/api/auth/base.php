<?php

// PHP Mailer
require "../../src/PHPMailer/PHPMailer.php";
require "../../src/PHPMailer/Exception.php";
require "../../src/PHPMailer/SMTP.php";

require "../../src/tools/base.php";

// Needed to use PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\Exception;

/**
 * Global variable
 */

$error = null;

// Function to connect to the database
function connectDatabase(&$conn) {
    global $servername, $db_username, 
           $db_password, $db_database;
    global $error;
    
    try {
        // First make sure we can connect to the database
        $conn = new PDO("mysql:host={$servername};dbname={$db_database};charset=utf8", 
                        $db_username, $db_password,
                        [PDO::ATTR_EMULATE_PREPARES => false, 
                         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException) {
        $error = "auth.db_error";
    }
    
    return $error === null;
}

// Function to retrieve results from database
function getResults($stmt) {
    $result = null;

    if ($stmt->rowCount() > 0) {
        // Convert the results into an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    return $result;
}

// Function to retrieve results from database
function isTaken($stmt, $message) {
    global $error;
    
    // Make sure there are no results
    if (null !== getResults($stmt)) {
        // If there are, set an error
        $error = $message;
    }
}

function sendMail($recipient, $subject, $body) {
    global $error;
    
    // PHPMailer Object
    $mail = new PHPMailer(true); //Argument true in constructor enables exceptions
    
    // Set the mail options in a different function
    setMailOptions($mail);

    // Set who the message is to be sent to
    $mail->addAddress($recipient["email"], $recipient["name"]);

    // Set the subject line
    $mail->Subject = $subject;
    
    // Set the email body
    $mail->Body = $body;

    try {
        $mail->send();
    } catch (\Exception) {
        $error = "auth.mail_error";
    }
}

function setMailOptions(&$mail) {
    global $email_host, $email_user, $email_pass;

    // Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;

    // Tell PHPMailer to use SMTP
    $mail->isSMTP();

    // Set the hostname of the mail server
    $mail->Host = $email_host;

    // Making sure we are using UTF-8
    $mail->CharSet = PHPMailer::CHARSET_UTF8;

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // Use $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    // Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;

    // Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    // Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    // Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = $email_user;

    // Password to use for SMTP authentication
    $mail->Password = $email_pass;

    // Set who the message is to be sent from
    $mail->setFrom($email_user, getString("verify.from"));
    
    // Use HTML for this email
    $mail->isHTML(true);
}

function sendMessage($error) {
    // The message to be sent
    $message = [
        "error" => (hasString($error) ? getString($error) : $error)
    ];

    // Send the message
    echo json_encode($message);
}

function getURL($url) {
    global $domain_name;
    global $local_ip;
    
    if (str_contains($domain_name, "localhost")) {
        // In case of debugging, use the local IP address of the host
        $url = $local_ip.$url;
    } else {
        // Otherwise, use the actual DNS
        $url = $domain_name.$url;
    }
    
    return $url;
}

function createToken($conn, $table, $user_id) {    
    global $error;
        
    // Generate the token to verify the email-address
    $token = bin2hex(random_bytes(50));
    
    try {
        // All the data has been checked, meaning that we can now safely create a new user
        $sql = "INSERT INTO {$table} (user_id, token, expires_at) "
                . "VALUES (:user_id, :token, :expires_at)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Genereate the expire date for the verification
        $expiry_date = (new DateTimeImmutable('now', new DateTimeZone('UTC')))
                            ->modify('+' . 30 . ' minutes')
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
