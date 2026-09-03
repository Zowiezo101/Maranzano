<?php

// PHP Mailer
require "../../src/PHPMailer/PHPMailer.php";
require "../../src/PHPMailer/Exception.php";
require "../../src/PHPMailer/SMTP.php";

require "../../src/tools/base.php";

// Needed to use PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\Exception;

// Function to connect to the database
function connectDatabase(&$conn, &$error=null) {
    global $servername, $db_username, 
           $db_password, $db_database;
    
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

// Function to send a verification code
function sendVerificationCode($email, $user, $token, &$error=null) {
    global $email_user;
    global $domain_name;
    global $local_ip;
    
    // PHPMailer Object
    $mail = new PHPMailer(true); //Argument true in constructor enables exceptions
    
    // Set the mail options in a different function
    setMailOptions($mail);

    // Set who the message is to be sent from
    $mail->setFrom($email_user, getString("verify.from"));

    // Set who the message is to be sent to
    $mail->addAddress($email, $user);
    
    // Use HTML for this email
    $mail->isHTML(true);

    // Set the subject line
    $mail->Subject = getString("verify.subject");
    
    // The URL to verify the account
    $url = "/api/auth/verify.php?token=".$token;
    if (str_contains($domain_name, "localhost")) {
        // In case of debugging, use the local IP address of the host
        $url = $local_ip.$url;
    } else {
        // Otherwise, use the actual DNS
        $url = $domain_name.$url;
    }

    // Get the email body
    $body = getString("verify.body");
    
    // Insert the name and token
    $body_user = str_replace("[user]", $user, $body);
    $body_url = str_replace("[url]", $url, $body_user);
    
    // Set the email body
    $mail->Body = $body_url;

    try {
        $mail->send();
    } catch (Exception) {
        $error = "auth.mail_error";
    }
}

function setMailOptions(&$mail) {
    global $email_host;
    global $email_user;
    global $email_pass;

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
}

function sendMessage($error) {
    // The message to be sent
    $message = [
        "error" => (hasString($error) ? getString($error) : $error)
    ];

    // Send the message
    echo json_encode($message);
}

function redirectPage($url) {
    global $domain_name;
    global $local_ip;
    
    if (str_contains($domain_name, "localhost")) {
        // In case of debugging, use the local IP address of the host
        $url = $local_ip.$url;
    } else {
        // Otherwise, use the actual DNS
        $url = $domain_name.$url;
    }

    // The actual redirect
    if( headers_sent() ) { 
        echo("<script>location.href=".$url."</script>"); 
    } else { 
        header("Location: $url"); 
    }
}
