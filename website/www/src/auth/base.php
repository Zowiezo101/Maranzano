<?php

// PHP Mailer
require "../PHPMailer/PHPMailer.php";
require "../PHPMailer/Exception.php";
require "../PHPMailer/SMTP.php";

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
    global $email_host, $email_user, $email_pass;
    global $domain_name;
    
    // PHPMailer Object
    $mail = new PHPMailer(true); //Argument true in constructor enables exceptions

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
    
    // TODO: Use variables isntead of hardcoded strings

    // Set who the message is to be sent from
    $mail->setFrom($email_user, 'The Mafiani Team');

    // Set who the message is to be sent to
    $mail->addAddress($email);
    
    // Use HTML for this email
    $mail->isHTML(true);

    // Set the subject line
    $mail->Subject = 'Please verify your e-mail address';
    
    // The URL to verify the account
    $url = $domain_name."/src/auth/verify.php?token=".$token;

    // Set the email body
    $mail->Body = "<h3>Welcome ".$user."!</h3><p>Click <a href='".$url."'>here</a> to verify your e-mail address or copy-paste this link into your browser:<br/>".$url."</p>";

    try {
        $mail->send();
    } catch (Exception) {
        $error = $mail->ErrorInfo;
    }
}

