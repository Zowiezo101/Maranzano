<?php

// Settings
require __DIR__ . "/../../../settings.conf";

// Strings for text
require __DIR__ . "/../../locale/strings_en.php";

// The classes to make the API work
require __DIR__ . "/../Classes/Auth.php";
require __DIR__ . "/../Classes/Register.php";
require __DIR__ . "/../Classes/Reset.php";
require __DIR__ . "/../Classes/Login.php";
require __DIR__ . "/../Classes/Database.php";
require __DIR__ . "/../Classes/Mailer.php";
require __DIR__ . "/../Classes/Message.php";
require __DIR__ . "/../Classes/Token.php";

// PHP Mailer
require __DIR__ . "/../tools/PHPMailer/PHPMailer.php";
require __DIR__ . "/../tools/PHPMailer/Exception.php";
require __DIR__ . "/../tools/PHPMailer/SMTP.php";

/**
 * Global variable
 */

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
    
// Function to retrieve results from database
function getResults($stmt) {
    $result = null;

    if ($stmt->rowCount() > 0) {
        // Convert the results into an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    return $result;
}
