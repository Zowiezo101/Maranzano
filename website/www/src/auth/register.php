<?php

// PHP Mailer
require "../PHPMailer/PHPMailer.php";
require "../PHPMailer/Exception.php";
require "../PHPMailer/SMTP.php";
  
// Include core and object files
require '../../../settings.conf';
require '../tools/base.php';

// Required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: ".$domain_name);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: access");

// Needed to use PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\Exception;

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
if (connectDatabase() && validateEmail($email) && validateUser($user) && validatePass($pass1, $pass2)) {    
    // Insert the data into the database
    $token = registerUser($email, $user, $pass1);
    
    // In case of no errors, send a verification code
    sendVerificationCode($email, $user, $token);
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
function connectDatabase() {
    global $servername, $db_username, 
           $db_password, $db_database;
    global $conn;
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

function validateEmail($email) {
    global $conn;
    global $error;
    
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

function validateUser($user) {
    global $conn;
    global $error;
    
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

function registerUser($email, $user, $pass) {
    global $conn;
    global $error;
    
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

function sendVerificationCode($email, $user, $token) {
    global $email_host, $email_user, $email_pass;
    global $error;
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
    $url = $domain_name."/src/auth/verify?token=".$token;

    // Set the email body
    $mail->Body = "<h3>Welcome ".$user."!</h3><p>Click <a href='".$url."'>here</a> to verify your e-mail address or copy-paste this link into your browser:<br/>".$url."</p>";

    try {
        $mail->send();
    } catch (Exception) {
        $error = $mail->ErrorInfo;
    }
}








