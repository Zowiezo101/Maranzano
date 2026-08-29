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

        <!-- Imports -->
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="css/mafiani.css" type="text/css"/>

        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">


        <title><?php getString("global.title"); ?></title>
    </head>
    
    <body class="d-flex flex-column min-vh-100 mx-auto bg-body fst-italic">
        <!-- Imports -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>
  
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto px-0">
                    <!-- The Header on top -->
                    <div class="row text-end border border-3 border-black bg-body px-5 pt-3">
                        <div class="col px-0">
                            <img class="img-fluid" src="img/Mafiani_wit.png" alt="Mafiani"/>
                            <?php getString("global.version"); ?>
                        </div>
                    </div>

                    <!-- The middle part (content) -->
                    <div class="row bg-body-secondary" style="height: 500px">
                        <div class="col-3">
                            <!-- Menu -->
                            <div class="row bg-body-tertiary text-center border border-3 border-black border-top-0">
                                <b><?php getString("global.menu"); ?></b>
                            </div>
                            <div class="row">
                                <button type="button" class="btn btn-link text-start" onclick="onClickHome()"><?php getString("menu.home"); ?></button>
                                <button type="button" class="btn btn-link text-start" data-bs-toggle="modal" data-bs-target="#loginModal"><?php getString("menu.login"); ?></button>
                                <button type="button" class="btn btn-link text-start" data-bs-toggle="modal" data-bs-target="#registerModal"><?php getString("menu.signup"); ?></button>
                                <button type="button" class="btn btn-link text-start" onclick="onClickRules()"><?php getString("menu.rules"); ?></button>
                                <button type="button" class="btn btn-link text-start" onclick="onClickAboutUs()"><?php getString("menu.aboutus"); ?></button>
                            </div>
                        </div>
                        <div id="content" class="col-6">
                            <!-- Content -->
                        </div>
                        <div class="col-3">
                            <!-- News -->
                            <div class="row bg-body-tertiary text-center border border-3 border-black border-top-0">
                                <b><?php getString("global.news"); ?></b>
                            </div>
                            <div class="row">
                                <?php getNews(); ?>
                            </div>
                        </div>
                    </div>
                    
                     <!-- The bottom part (x users online) -->
                    <div class="row bg-body-secondary">
                        <div class="col-3">
                            <?php getString("menu.users"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Footer -->
        <footer class="text-center text-black">
                <b>Copyright</b>
        </footer>
    </body>
    
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="loginModalLabel">Logging in</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Login</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="registerModalLabel">Signing up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Sign up</button>
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
    
    $(function() {
        // Show the welcome message
        onClickHome();
    });
</script>