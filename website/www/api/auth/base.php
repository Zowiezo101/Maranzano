<?php

// PHP Mailer
require __DIR__ . "/../../src/PHPMailer/PHPMailer.php";
require __DIR__ . "/../../src/PHPMailer/Exception.php";
require __DIR__ . "/../../src/PHPMailer/SMTP.php";

// Card
require __DIR__ . "/tools/card.php";
require __DIR__ . "/tools/database.php";
require __DIR__ . "/tools/token.php";
require __DIR__ . "/tools/user.php";

// Global base file
require __DIR__ . "/../../src/tools/base.php";

// Needed to use PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\Exception;

/**
 * Global variable
 */

$error = null;

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

function sendMessage($error, $data = null) {
    // The message to be sent
    $message = [
        "error" => (hasString($error) ? getString($error) : $error)
    ];
    
    if(isset($data)) {
        $message["data"] = $data;
    }

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
