<?php 
    // This needs to be started at the very beginning
    session_start();
    
    require "src/tools/base.php";
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
        <script src="src/tools/base.js"></script>

        <!-- Imports (CSS) -->
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="css/mafiani.css" type="text/css"/>

        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">


        <title><?php printString("global.title"); ?></title>
    </head>
    
    <body class="vh-100 fst-italic bg-gradient">
        
        <!-- TODO: Debugging stuff for myself -->
        <div class="d-sm-none">XS screen size</div>
        <div class="d-none d-sm-block d-md-none">S screen size</div>
        <div class="d-none d-md-block d-lg-none">M screen size</div>
        <div class="d-none d-lg-block d-xl-none">L screen size</div>
        <div class="d-none d-xl-block d-xxl-none">XL screen size</div>
        <div class="d-none d-xxl-block">XXL screen size</div>
        
        <!-- The container with all the rows and columns -->
        <div class="container-fluid">
            
            <!-- The full contents of this page
                    For medium and larger screens, it's half the screen
                    For smaller than medium, it's the full screen -->
            <div class="row">
                <div class="col-md-6 mx-auto">
                    
                    <!-- The Header on top, 
                            with a 3px border and some padding on top and the sides -->
                    <div class="row bg-body border border-3 border-black ">
                        <div class="col px-5 pt-3">
                            
                            <!-- The Banner and version number --> 
                            <img class="img-fluid" src="img/Mafiani_wit.png" alt="Mafiani"/>
                            <div class="text-end"><?php printString("global.version"); ?></div>
                        </div>
                    </div>

                    <!-- The page contents
                        The contents for the home page has 3 columns:
                            1. Menu
                            2. Content of selected menu item
                            3. News/Blog -->
                    <div class="row bg-body-secondary">
                        
                        <!-- Menu -->
                        <div class="col-3 border border-3 border-black border-top-0">
                            <div class="row d-flex align-items-start flex-column" style="height: 500px">
                                <!-- The Menu has 3 rows:
                                        1. Menu titel
                                        2. Menu items
                                        3. Number of users online -->

                                <!-- Menu titel -->
                                <div class="bg-body-tertiary text-center border-bottom border-3 border-black">
                                    <b><?php printString("global.menu"); ?></b>
                                </div>

                                <!-- Menu items -->
                                <div class="btn-group-vertical">
                                    <button type="button" class="btn btn-link text-start" onclick="onClickHome()"><?php printString("menu.home"); ?></button>
                                    <button id="loginBtn" type="button" class="btn btn-link text-start" data-bs-toggle="modal" data-bs-target="#loginModal"><?php printString("menu.login"); ?></button>
                                    <button type="button" class="btn btn-link text-start" data-bs-toggle="modal" data-bs-target="#registerModal"><?php printString("menu.signup"); ?></button>
                                    <button type="button" class="btn btn-link text-start" onclick="onClickRules()"><?php printString("menu.rules"); ?></button>
                                    <button type="button" class="btn btn-link text-start" onclick="onClickAboutUs()"><?php printString("menu.aboutus"); ?></button>
                                </div>

                                <!-- Number of users online -->
                                <div class="mt-auto">
                                    <?php printString("menu.users"); ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div id="content" class="col-6 border-bottom border-3 border-black">
                            
                        </div>
                        
                        <!-- News -->
                        <div class="col-3 border border-3 border-black  border-top-0">
                            <!-- News has 2 rows:
                                    1. News titel
                                    2. News items -->
                            
                            <!-- News titel -->
                            <div class="row bg-body-tertiary text-center border-bottom border-3 border-black">
                                <b><?php printString("global.news"); ?></b>
                            </div>
                            
                            <!-- News items -->
                            <div class="row">
                                <?php getNews(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- The Footer -->
                    <div class="row text-center text-black">
                        <?php printString("global.copyright"); ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
    
    <!-- The Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border border-3 border-white">
                
                <!-- The Modal header -->
                <div class="modal-header bg-body-tertiary">
                    <h5 class="modal-title fs-5 " id="loginModalLabel"><?php printString("login.title"); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- The Modal body -->
                <div class="modal-body">
                    <!-- Login form -->
                    <form id="login-form">
                        <!-- Filled in by JS -->
                    </form>
                    
                    <!-- Forgot password form -->
                    <form id="reset-form">
                        <!-- Filled in by JS -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- The Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border border-3 border-white">
                
                <!-- The Modal header -->
                <div class="modal-header bg-body-tertiary">
                    <h5 class="modal-title fs-5" id="registerModalLabel"><?php printString("signup.title"); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- The Modal body -->
                <div class="modal-body">
                    <form id="register-form">
                        <!-- Filled in by JS -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</html>

<script>
    // Show the contents of the home page
    function onClickHome() {
        var text = <?php printString("home.content", true); ?>;
        
        $("#content").html(text);
    }
    
    // Show the contents of the rules page
    function onClickRules() {
        var text = <?php printString("rules.content", true); ?>;
                
        $("#content").html(text);
    }
    
    // Show the contents of the about us page
    function onClickAboutUs() {
        var text = <?php printString("aboutus.content", true); ?>;
                
        $("#content").html(text);
    }
    
    // Load the form for logging in
    function onClickLogin() {
        var html = getHTML(`
                    <-- Email address -->
                    <div class="mb-3 row">
                        <label for="loginEmail" class="col-4 col-sm-2 col-form-label"><?php printString("login.email"); ?></label>
                        <div class="col-8 col-sm-10">
                            <input type="email" class="form-control" id="loginEmail" required>
                        </div>
                    </div>

                    <-- Password -->
                    <div class="mb-3 row">
                        <label for="loginPassword" class="col-4 col-sm-2 col-form-label"><?php printString("login.password"); ?></label>
                        <div class="col-8 col-sm-10">
                            <input type="password" class="form-control" id="loginPassword" required>
                        </div>
                    </div>

                    <-- Forgot password option -->
                    <div class="mb-3 row">
                        <button type="button" class="btn btn-link" onclick="onClickReset()"><?php printString("login.forgotpass"); ?></button>
                    </div>

                    <-- Login button -->
                    <div class="mb-3 mx-3 row">
                        <button type="submit" class="btn btn-primary"><?php printString("menu.login"); ?></button>
                    </div>

                    <-- Error message -->
                    <div id="loginError" class="mb-4 mx-3 text-warning d-none">
                        <-- Filled in later in case of error -->
                    </div>`);
    
        // Hide the other form
        $("#reset-form").addClass("d-none");
        
        // Update the information
        $("#login-form").html(html);
        $("#login-form").removeClass("d-none");
    }
    
    // Load the form for resetting password
    function onClickReset() {
        var html = getHTML(`
                        <-- Explanation on resetting your password -->
                        <div class="row text-center">
                            <p><?php printString("reset.email"); ?></p>
                        </div>
                        
                        <-- Email address -->
                        <div class="mb-3 row">
                            <label for="resetEmail" class="col-4 col-sm-2 col-form-label"><?php printString("login.email"); ?></label>
                            <div class="col-8 col-sm-10">
                                <input type="email" class="form-control" id="resetEmail" required>
                            </div>
                        </div>
                        
                        <-- Forgot password button -->
                        <div class="mb-3 mx-3 row">
                            <button type="submit" class="btn btn-primary"><?php printString("login.reset"); ?></button>
                        </div>
                        
                        <-- Login option -->
                        <div class="mb-3 row">
                            <button type="button" class="btn btn-link" onclick="onClickLogin()"><?php printString("menu.login"); ?></button>
                        </div>

                        <-- Error message -->
                        <div id="ResetError" class="mb-4 mx-3 text-warning d-none">
                            <-- Filled in later in case of error -->
                        </div>`);
    
        // Hide the other form
        $("#login-form").addClass("d-none");
        
        // Update the information
        $("#reset-form").html(html);
        $("#reset-form").removeClass("d-none");
    }
    
    // Load the form for registering an account
    function onClickRegister() {
        var html = getHTML(`
                    <-- Email address -->
                    <div class="mb-3 mx-3">
                        <label for="registerEmail" class="form-label"><?php printString("login.email"); ?></label>
                        <input type="email" class="form-control" id="registerEmail" required>
                    </div>

                    <-- Username -->
                    <div class="mb-3 mx-3">
                        <label for="registerUser" class="form-label"><?php printString("signup.username"); ?></label>
                        <input type="text" class="form-control" id="registerUser" required>
                    </div>

                    <-- Password -->
                    <div class="mb-3 mx-3">
                        <label for="registerPassword" class="form-label"><?php printString("signup.password"); ?></label>
                        <input type="password" class="form-control" id="registerPassword" required>
                    </div>

                    <-- Confirm password -->
                    <div class="mb-4 mx-3">
                        <label for="registerPassword2" class="form-label"><?php printString("signup.confirm"); ?></label>
                        <input type="password" class="form-control" id="registerPassword2" required>
                    </div>

                    <-- Register button -->
                    <div class="mb-3 mx-5 row">
                        <button type="submit" class="btn btn-primary"><?php printString("menu.signup"); ?></button>
                    </div>

                    <-- Error message -->
                    <div id="registerError" class="mb-4 mx-3 text-center text-warning d-none">
                        <-- Filled in later in case of error -->
                    </div>`);
    
        // Update the information
        $("#register-form").html(html);
    }
    
    // Create a fetch call to prevent reloading the page
    function onSubmitLogin(event) {
        event.preventDefault();
    }
    
    // Create a fetch call to prevent reloading the page
    function onSubmitReset(event) {
        event.preventDefault();
        
        // Remove any previous errors
        onResetError("#ResetError");
        
        // The data for registering
        var resetEmail = $("#resetEmail").val();
        
        // Put the data in an easier-to-send format
        var data = {
            "email": resetEmail
        };

        // The fetch call
        fetchPost("forgot.php", data).then(function(results) {
            // Handle the results of the fetch call

            if (results.error !== "" && results.error !== null) {
                // Something went wrong, show an error message
                onReturnedError(results.error, "#resetError");
            } else {
                // Success, show the confirm content
                onInformReset();
            }

        }).catch(function(results) {
            // Show an error if anything went wrong
            alert("error: " + results);
        });
    }
    
    // Create a fetch call to prevent reloading the page
    function onSubmitRegister(event) {
        event.preventDefault();
        
        // Remove any previous errors
        onResetError("#registerError");
        
        // The data for registering
        var registerEmail = $("#registerEmail").val();
        var registerUser = $("#registerUser").val();
        var registerPassword = $("#registerPassword").val();
        var registerPassword2 = $("#registerPassword2").val();
        
        // Put the data in an easier-to-send format
        var data = {
            "email": registerEmail,
            "user": registerUser,
            "pass1": registerPassword,
            "pass2": registerPassword2
        };

        // The fetch call
        fetchPost("register.php", data).then(function(results) {
            // Handle the results of the fetch call

            if (results.error !== "" && results.error !== null) {
                // Something went wrong, show an error message
                onReturnedError(results.error, "#registerError");
            } else {
                // Success, show the verify content
                onInformRegister();
            }

        }).catch(function(results) {
            // Show an error if anything went wrong
            alert("error: " + results);
        });
    }
    
    // Load the div to inform the user that an email has been sent
    function onInformReset() {
        var html = getHTML(`
                        <-- Inform user that account creation was successfull
                        Now they'll need to verify their email address -->
                        <div class="row text-center">
                            <p><?php printString("signup.success"); ?></p>
                        </div>
                        
                        <-- Done -->
                        <div class="mb-3 mx-3 row">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" ><?php printString("signup.verified"); ?></button>
                        </div>`);
    
        // Update the information
        $("#reset-form").html(html);
    }
    
    // Load the div to inform the user their account needs verification
    function onInformRegister() {
        var html = getHTML(`
                        <-- Inform user that account creation was successfull
                        Now they'll need to verify their email address -->
                        <div class="row text-center">
                            <p><?php printString("signup.success"); ?></p>
                        </div>
                        
                        <-- Done -->
                        <div class="mb-3 mx-3 row">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" ><?php printString("signup.verified"); ?></button>
                        </div>`);
    
        // Update the information
        $("#register-form").html(html);
    }
    
    function onReturnedError(message, element) {
        $(element).html(message).removeClass("d-none");
    }
    
    function onResetError(element) {
        $(element).html("").addClass("d-none");
    }
    
    $(function() {
        // Make sure the login modal gets reset whenever it gets hidden
        $("#loginModal").on("hidden.bs.modal", function(){
            onClickLogin();
        });

        // Make sure the register modal gets reset whenever it gets hidden
        $("#registerModal").on("hidden.bs.modal", function(){
            onClickRegister();
        });

        // Set prevent page reloading when submitting form
        $("#login-form").on("submit", function(e) {onSubmitLogin(e);});
        $("#reset-form").on("submit", function(e) {onSubmitReset(e);});
        $("#register-form").on("submit", function(e) {onSubmitRegister(e);});

        // Show the welcome message
        onClickHome();

        // Show the login content in the login modal
        onClickLogin();

        // Show the register content in the register modal
        onClickRegister();
    });
</script>
