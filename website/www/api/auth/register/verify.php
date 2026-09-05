<?php

// Include login details and functions that are used by multiple files
require __DIR__ . "/../base.php";

// Global connection paramater for this file
$conn = null;

// Get the input data
$token = filter_input(INPUT_GET, "token");

// Connect to the database
if (validateToken($token) && connectDatabase($conn)) {
    $result = retrieveVerifyToken($conn, $token);
    
    if (!isset($error)) {
        invalidateVerifyToken($conn, $result);
    }
    
    if(!isset($error)) {
        updateUser($conn, $result);
    }
}
        
$card = prepareCard();

/* 
 * The functions 
 */

function retrieveVerifyToken($conn, $token) {
    
    // Retrieve an existing token
    $result = retrieveToken($conn, "verify_user", $token);
    
    return $result;
}

function invalidateVerifyToken($conn, $token) {
    
    // Invalidate the token
    invalidateToken($conn, "verify_user", $token);
}

function updateUser($conn, $token) {    
    global $error;

    try {
        // Create a query to update this user
        $sql = "UPDATE users SET is_verified=1 WHERE id = :id";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":id", $token["user_id"], PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();
    } catch (Exception) {
        $error = "auth.db_error";
    }
}

function prepareCard() {
    global $error;
    
    if(isset($error)) {
        // There's an error
        $header = getString("global.error");
        $title = getString($error);
        $body = getString("verify.again");
    } else {
        // No error
        $header = getString("verify.success");
        $title = getString("verify.close");
        $body = "";
    }
    
    $button = ["text" => getString("verify.home"), "url" => "/"];
    
    $card = ["header" => $header, "title" => $title, "body" => $body, "button" => $button];
    return $card;
}
?>

<!doctype html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Imports (External scripts) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>

        <!-- Imports (CSS) -->
        <link rel="stylesheet" href="../../css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="../../css/mafiani.css" type="text/css"/>

        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="../../img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../img/favicon-16x16.png">


        <title><?php printString("verify.title"); ?></title>
    </head>
    
    <body>
        
        <!-- TODO: Debugging stuff for myself -->
        <div class="d-sm-none">XS screen size</div>
        <div class="d-none d-sm-block d-md-none">S screen size</div>
        <div class="d-none d-md-block d-lg-none">M screen size</div>
        <div class="d-none d-lg-block d-xl-none">L screen size</div>
        <div class="d-none d-xl-block d-xxl-none">XL screen size</div>
        <div class="d-none d-xxl-block">XXL screen size</div>
        
        <!-- The container with all the rows and columns -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <?php showCard($card); ?>
                </div>
            </div>
        </div>
    </body>
</html>
