<?php 
    // This needs to be started at the very beginning
    session_start();
    
    require "src/tools/base.php";
    require "src/tools/database.php"
?>

<!doctype html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        
        <!-- Imports (Scripts) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>

        <!-- Imports (CSS) -->
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="css/mafiani.css" type="text/css"/>

        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">


        <title><?php getString("global.title"); ?></title>
    </head>
    
    <body class="bg-body fst-italic">
        
        <!-- Debugging stuff for myself -->
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
                            <div class="text-end"><?php getString("global.version"); ?></div>
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
                                    <b><?php getString("global.menu"); ?></b>
                                </div>

                                <!-- Menu items -->
                                <div class="btn-group-vertical">
                                    <button type="button" class="btn btn-link text-start" onclick="onClickHome()"><?php getString("menu.home"); ?></button>
                                    <button type="button" class="btn btn-link text-start" data-bs-toggle="modal" data-bs-target="#loginModal"><?php getString("menu.login"); ?></button>
                                    <button type="button" class="btn btn-link text-start" data-bs-toggle="modal" data-bs-target="#registerModal"><?php getString("menu.signup"); ?></button>
                                    <button type="button" class="btn btn-link text-start" onclick="onClickRules()"><?php getString("menu.rules"); ?></button>
                                    <button type="button" class="btn btn-link text-start" onclick="onClickAboutUs()"><?php getString("menu.aboutus"); ?></button>
                                </div>

                                <!-- Number of users online -->
                                <div class="mt-auto">
                                    <?php getString("menu.users"); ?>
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
                                <b><?php getString("global.news"); ?></b>
                            </div>
                            
                            <!-- News items -->
                            <div class="row">
                                <?php getNews(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- The Footer -->
                    <div class="row text-center text-black">
                        <?php getString("global.copyright"); ?>
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
                    <h5 class="modal-title fs-5 " id="loginModalLabel"><?php getString("login.title"); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- The Modal body -->
                <div id="modal-body" class="modal-body">
                    <!-- Filled in by JS -->
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
                    <h5 class="modal-title fs-5" id="registerModalLabel"><?php getString("signup.title"); ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- The Modal body -->
                <div class="modal-body">
                    <form action="register" method="post" name="register">
                        <!-- Email address -->
                        <div class="mb-3 mx-3">
                            <label for="inputEmail" class="form-label"><?php getString("login.email"); ?></label>
                            <input type="email" class="form-control" id="inputEmail" required>
                        </div>
                        
                        <!-- Username -->
                        <div class="mb-3 mx-3">
                            <label for="inputUser" class="form-label"><?php getString("signup.username"); ?></label>
                            <input type="text" class="form-control" id="inputUser" required>
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3 mx-3">
                            <label for="inputPassword" class="form-label"><?php getString("signup.password"); ?></label>
                            <input type="password" class="form-control" id="inputPassword" required>
                        </div>
                        
                        <!-- Confirm password -->
                        <div class="mb-4 mx-3">
                            <label for="inputPassword2" class="form-label"><?php getString("signup.confirm"); ?></label>
                            <input type="password" class="form-control" id="inputPassword2" required>
                        </div>
                        
                        <!-- Register button -->
                        <div class="mb-3 mx-5 row">
                            <button type="submit" class="btn btn-primary"><?php getString("menu.signup"); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</html>

<script>
    function onClickHome() {
        var text = `<?php getString("home.content"); ?>`;
        
        $("#content").html(text);
    }
    
    function onClickRules() {
        var text = `<?php getString("rules.content"); ?>`;
                
        $("#content").html(text);
    }
    
    function onClickAboutUs() {
        var text = `<?php getString("aboutus.content"); ?>`;
                
        $("#content").html(text);
    }
    
    function onClickLogin() {
        var html = `<form action="login" method="post" name="login">
                        <!-- Email address -->
                        <div class="mb-3 row">
                            <label for="inputEmail" class="col-2 col-form-label"><?php getString("login.email"); ?></label>
                            <div class="col-10">
                                <input type="email" class="form-control" id="inputEmail" required>
                            </div>
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3 row">
                            <label for="inputPassword" class="col-2 col-form-label"><?php getString("login.password"); ?></label>
                            <div class="col-10">
                                <input type="password" class="form-control" id="inputPassword" required>
                            </div>
                        </div>
                        
                        <!-- Forgot password option -->
                        <div class="mb-3 row">
                            <button type="button" class="btn btn-link" onclick="onClickForgotten()"><?php getString("login.forgotpass"); ?></button>
                        </div>
                        
                        <!-- Login button -->
                        <div class="mb-3 mx-3 row">
                            <button type="submit" class="btn btn-primary"><?php getString("menu.login"); ?></button>
                        </div>
                    </form>`
    
        $("#modal-body").html(html);
    }
    
    function onClickForgotten() {
        var html = `
                    <form action="reset" method="post" name="reset">
                        <!-- Explanation on resetting your password -->
                        <div class="row text-center">
                            <p><?php getString("reset.email"); ?></p>
                        </div>
                        
                        <!-- Email address -->
                        <div class="mb-3 row">
                            <label for="resetEmail" class="col-2 col-form-label"><?php getString("login.email"); ?></label>
                            <div class="col-10">
                                <input type="email" class="form-control" id="resetEmail" required>
                            </div>
                        </div>
                        
                        <!-- Forgot password button -->
                        <div class="mb-3 mx-3 row">
                            <button type="submit" class="btn btn-primary"><?php getString("login.reset"); ?></button>
                        </div>
                        
                        <!-- Login option -->
                        <div class="mb-3 row">
                            <button type="button" class="btn btn-link" onclick="onClickLogin()"><?php getString("menu.login"); ?></button>
                        </div>
                    </form>`
    
        $("#modal-body").html(html);
    }
    
    $(function() {
        // Make sure the login modal gets reset whenever it gets hidden
        $("#loginModal").on("hidden.bs.modal", function(){
            onClickLogin();
        });
    
        // Show the welcome message
        onClickHome();
        
        // Show the login modal
        onClickLogin();
    });
</script>