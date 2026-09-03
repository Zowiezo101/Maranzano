<?php

// Include login details and functions that are used by multiple files
require '../../../settings.conf';
require 'base.php';

$conn = null;
$error = null;

// Get the input data
$token = filter_input(INPUT_GET, "token");

// Connect to the database
if (validateToken($token, $error) && connectDatabase($conn, $error)) {
    $user = retrieveUser($conn, $token, $error);
    
    if (isset($user)) {
        updateUser($conn, $user, $error);
    }
}

/* 
 * The functions 
 */

function validateToken($token, &$error) {
    // Check if the token is set
    if (!isset($token)) {
        $error = "auth.token.invalid";
    }
    
    return $error === null;
}

function retrieveUser($conn, $token, &$error) {
    $user = null;
    
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
        } else {
            $error = "auth.token.invalid";
        }
    } catch (Exception) {
        $error = "auth.db_error";
    }
    
    return $user;
}

function updateUser($conn, $user, &$error) {    
    // Create a query to update this user
    $sql = "UPDATE users SET is_verified=1, token=NULL WHERE id = :id";

    try {
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":id", $user["id"], PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();
    } catch (Exception) {
        $error = "auth.db_error";
    }
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
                    <div class="card bg-body-secondary text-center" data-error=<?php hasString($error) ? printString($error, true) : json_encode(""); ?>>
                        <div id="card-header" class="card-header bg-body-tertiary">
                            <!-- Filled in by JS -->
                        </div>
                        <div class="card-body">
                            <!-- Filled in by JS -->
                            
                            <h5 id="card-title" class="card-title"></h5>
                            <p  id="card-text"  class="card-text"></p>
                            <a href="/" class="btn btn-primary"><?php printString("verify.home"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
    $(function () {
        var header = "";
        var title = "";
        var text = "";
        
        // Get the error message (if any)
        var error = $(".card").data("error");
        
        if (error !== "") {
            // There's an error
            header = <?php printString("global.error", true); ?>;
            title = error;
            text = <?php printString("verify.again", true); ?>;
        } else {
            // No error
            header = <?php printString("verify.success", true); ?>;
            title = <?php printString("verify.close", true); ?>;
        }
        
        $("#card-header").text(header);        
        $("#card-title").text(title);        
        $("#card-text").text(text);        
    });
</script>
