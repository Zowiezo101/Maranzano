<?php

// Include login details and functions that are used by multiple files
require __DIR__ . "/../base.php";

// Global connection paramater for this file
$conn = null;

// Global user parameter for this file
$user = null;

// Get the input data
$token = filter_input(INPUT_GET, "token");

// Connect to the database
if (validateToken($token) && connectDatabase($conn)) {
    
    // Retrieve the token
    $result = retrieveResetToken($conn, $token);
    
    if (!isset($error)) {
        // Set the user as verified
        $user = retrieveUserFromId($conn, $result["user_id"]);
    }
}

// Show the form to the user
$card = prepareCard($user);

/* 
 * The functions 
 */

function retrieveResetToken($conn, $token) {
    
    // Retrieve an existing token
    $result = retrieveToken($conn, "reset_pass", $token);
    
    return $result;
}

function prepareCard($user) {
    global $token;
    global $error;
    
    if(isset($error)) {
        // There's an error
        $header = getString("global.error");
        $title = getString($error);
        $body = getString("reset.again");
    } else {
        // No error
        $header = getString("reset.title");
        $title = "";
        $body = '<form id="reset-form" data-token='.json_encode($token).'>
                                    <!-- Username -->
                                    <div class="mb-3 mx-3">
                                        <label for="resetUser" class="form-label">'.getString("signup.username").'</label>
                                        <input type="text" disabled class="form-control text-center" id="resetUser" value='.json_encode($user["name"]).'>
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3 mx-3">
                                        <label for="resetPassword" class="form-label">'.getString("signup.password").'</label>
                                        <input type="password" class="form-control" id="resetPassword" required>
                                    </div>

                                    <!-- Confirm password -->
                                    <div class="mb-4 mx-3">
                                        <label for="resetPassword2" class="form-label">'.getString("signup.confirm").'</label>
                                        <input type="password" class="form-control" id="resetPassword2" required>
                                    </div>

                                    <!-- Reset button -->
                                    <div class="mb-3 mx-5 row">
                                        <button type="submit" class="btn btn-primary">'.getString("login.reset").'</button>
                                    </div>

                                    <!-- Error message -->
                                    <div id="resetError" class="mb-4 mx-3 text-center text-warning d-none">
                                        <!-- Filled in later in case of error -->
                                    </div>
                                </form>';
    }
    
    $card = [
        "header" => $header,
        "title" => $title,
        "body" => $body
    ];
    
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
        
        <!-- Imports (Local scripts) -->
        <script src="../../../src/tools/base.js"></script>

        <!-- Imports (CSS) -->
        <link rel="stylesheet" href="../../css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="../../css/mafiani.css" type="text/css"/>

        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="../../img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../img/favicon-16x16.png">

        <title><?php printString("reset.title"); ?></title>
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

<script>
    
    // Create a fetch call to prevent reloading the page
    function onSubmitReset(event) {
        event.preventDefault();
        
        // Remove any previous errors
        onResetError("#resetError");
        
        // The data for registering
        var resetPass1 = $("#resetPassword").val();
        var resetPass2 = $("#resetPassword2").val();
        
        // Put the data in an easier-to-send format
        var data = {
            "token": $("#reset-form").data("token"),
            "pass1": resetPass1,
            "pass2": resetPass2
        };

        // The fetch call
        fetchPost("update", data).then(function(results) {
            // Handle the results of the fetch call

            if (results.error !== "" && results.error !== null) {
                // Something went wrong, show an error message
                onReturnedError(results.error, "#resetError");
            } else {
                // Success, show the confirm content
                onInformUpdate();
            }

        }).catch(function(results) {
            // Show an error if anything went wrong
            alert("error: " + results);
        });
    }
    
    function onInformUpdate() {
        var html = getHTML(`
                        <-- Inform user that updating the password was successfull
                        and that a confirmation email has been sent -->
                        <div class="row text-center">
                            <p><?php printString("update.success"); ?></p>
                        </div>
                        
                        <-- Done -->
                        <div class="mb-3 mx-3 row">
                            <a href="/" class="btn btn-primary">
                                <?php printString("verify.home"); ?>
                            </a>
                        </div>`);
    
        // Update the information
        $("#reset-form").html(html);
    }
    
    $(function() {
        // Set prevent page reloading when submitting form
        $("#reset-form").on("submit", function(e) {onSubmitReset(e);});
    });
</script>
