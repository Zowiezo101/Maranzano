<?php

namespace Classes;

// Needed to use PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\Exception;

class Mailer {
    
    public function sendMail($recipient, $subject, $body) {

        // Set the mail options in a different function
        $mail = $this->setOptions();

        // Set who the message is to be sent to
        $mail->addAddress($recipient["email"], $recipient["name"]);

        // Set the subject line
        $mail->Subject = $subject;

        // Set the email body
        $mail->Body = $body;
        
        // Send the mail
        $mail->send();
    }

    private function setOptions() {
        global $email_host, $email_user, $email_pass;
        
        // Argument true in constructor enables exceptions
        $mail = new PHPMailer(true); 

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
        $mail->setFrom($email_user, getString("email.from"));

        // Use HTML for this email
        $mail->isHTML(true);
        
        return $mail;
    }
}
