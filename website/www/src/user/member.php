<?php
    
    require __DIR__ . "/../tools/base.php";
    
    // If we are logged in, do nothing
    // Otherwise, go to the homepage
    checkLoggedIn("", "/");
    
    $page_title = "global.title";
    
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
        <script src="src/tools/database.js"></script>

        <!-- Imports (CSS) -->
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="css/mafiani.css" type="text/css"/>

        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">

        <title><?php printString($page_title); ?></title>
    </head>
    
    <body class="min-vh-100 h-100 fst-italic bg-gradient">
        
        <!-- The container with all the rows and columns -->
        <div class="container-fluid">
            
            <!-- The full contents of this page
                    For medium and larger screens, it's half the screen
                    For smaller than medium, it's the full screen -->
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="row bg-body-secondary">
                        <!-- Sidebar -->
                        <div class="col-3 border border-3 border-black">
                            
                            <!-- Player name -->
                            <div class="row text-black bg-body-tertiary border-bottom border-3 border-black">
                                <b><?php echo $_SESSION["member_name"]; ?></b>
                            </div>
                            
                            <!-- Player info -->
                            <div class="row border-bottom border-3 border-black">
                                Player Info
                            </div>
                            
                            <!-- Menu -->
                            <div class="row">
                                Menu
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="col-9 border border-3 border-start-0 border-black">
                            
                            <!-- Header -->
                            <div class="row bg-body border-bottom border-3 border-black">
                                <div class="col px-lg-5 mx-lg-5">

                                    <!-- The Banner and version number --> 
                                    <img class="img-fluid" src="img/Mafiani_wit.png" alt="Mafiani"/>
                                    <div class="text-end"><?php printString("global.version"); ?></div>
                                </div>
                            </div>
                            
                            <!-- Selected tab -->
                            <div class="row">
                                Selected tab here
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
</html>
