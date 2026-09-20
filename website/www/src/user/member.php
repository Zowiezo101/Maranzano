<?php
    
    require __DIR__ . "/../tools/base.php";
    
    // If we are logged in, do nothing
    // Otherwise, go to the homepage
    checkLoggedIn("", "/");
    
    // Title of this page
    $page_title = "global.title";
    
    // Get Player information from the database
    $player = getPlayerInfo();
    
    // Get the rank as an integer value of base 10
    $rank = isset($player) ? intval($player->rank, 10) : 1;
    
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
        <script src="src/tools/tabs.js"></script>
        
        <!-- The functions for the different tabs -->
        <script src="src/tabs/tab_main.js"></script>
        <script src="src/tabs/tab_business.js"></script>
        <script src="src/tabs/tab_crimes.js"></script>
        <script src="src/tabs/tab_casino.js"></script>
        <script src="src/tabs/tab_comm.js"></script>
        <script src="src/tabs/tab_help.js"></script>

        <!-- Imports (CSS) -->
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="css/mafiani.css" type="text/css"/>

        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">

        <title><?php printString($page_title); ?></title>
    </head>
    
    <body class="min-vh-100 h-100 fst-italic bg-gradient-center">
        
        <!-- The container with all the rows and columns -->
        <div class="container-fluid">
            
            <!-- The full contents of this page
                    For medium and larger screens, it's half the screen
                    For smaller than medium, it's the full screen -->
            <div class="row">
                <div class="col-md-8 col-lg-6 mx-auto">
                    <div class="row bg-body-secondary">
                        
                        <!-- Sidebar -->
                        <div class="col-4 col-lg-3 border border-3 border-black">
                            
                            <!-- Player name -->
                            <div class="row fst-normal bg-body-tertiary border-bottom border-3 border-black">
                                <b><?php echo $_SESSION["member_name"]; ?></b>
                            </div>
                            
                            <!-- Player info -->
                            <div class="row pt-2 pb-3 px-1 border-bottom border-3 border-black">
                                <table id="playerInfo" class="fw-bold">
                                    <?php printTableTemplate("info", ["cash", "bank",
                                                                      "rank", "progress",
                                                                      "family", "city",
                                                                      "country", "health",
                                                                      "bullets", "shields"]); ?>
                                </table>
                
                                <!-- In case the information can't be found -->
                                <p id="playerInfoError" class="<?php echo ($player) ? "d-none " : ""; ?>text-center"><?php printString("info.no-player"); ?></p>
                            </div>
                            
                            <!-- Menu -->
                            <div class="row">
                                <div class="col-12 mt-2 accordion" role="tablist">
                                    <?php printMenu("main", ["home"     => $RANK_ROOKIE, 
                                                             "travel"   => $RANK_ROOKIE, 
                                                             "jail"     => $RANK_ROOKIE, 
                                                             "hospital" => $RANK_ROOKIE,
                                                             "deceased" => 999]) ?>
                                    
                                    <?php printMenu("business", ["bank"  => $RANK_ROOKIE, 
                                                                 "bullet" => $RANK_ROOKIE, 
                                                                 "garage" => $RANK_ROOKIE, 
                                                                 "family" => $RANK_ROOKIE, 
                                                                 "manage" => $RANK_DON]) ?>
                                    
                                    <?php printMenu("crimes", ["bike"  => $RANK_ROOKIE, 
                                                               "car"   => $RANK_MAFIOSO, 
                                                               "store" => $RANK_MAFIOSO, 
                                                               "kill"  => $RANK_HITMAN]) ?>
                                    
                                    <?php printMenu("casino", ["roulette" => $RANK_ROOKIE, 
                                                               "scratch"  => $RANK_ROOKIE]) ?>
                                    
                                    <?php printMenu("comms", ["online"   => $RANK_ROOKIE, 
                                                              "friends"  => $RANK_ROOKIE, 
                                                              "news"     => $RANK_ROOKIE, 
                                                              "userlist" => $RANK_ROOKIE, 
                                                              "mail"     => $RANK_ROOKIE]) ?>
                                    
                                    <?php printMenu("help", ["contact"  => $RANK_ROOKIE, 
                                                             "donate"   => $RANK_ROOKIE, 
                                                             "rules"    => $RANK_ROOKIE, 
                                                             "info"     => $RANK_ROOKIE, 
                                                             "settings" => $RANK_ROOKIE]) ?>

                                    <!-- X Users online -->
                                    <div class="mt-3 px-3">
                                        <?php getOnlineUsers(); ?>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="col-8 col-lg-9 border border-3 border-start-0 border-black">

                            <!-- Header -->
                            <div class="row bg-body border-bottom border-3 border-black">
                                <div class="col px-3 px-lg-5 pt-1 mx-3 mx-lg-5">
                                    <!-- The Banner and version number --> 
                                    <img class="img-fluid" src="img/Mafiani_wit.png" alt="Mafiani"/>
                                </div>
                            </div>
                            
                            <!-- Selected tab -->
                            <div class="row">
                                <div class="col-10 mx-auto tab-content" role="tab-content">
                                    <!--
                                        Main menu
                                    -->
                                    <?php require __DIR__ . "/../tabs/tab_main.php"; ?>
                                    
                                    <!--
                                        Businesses menu
                                    -->
                                    <?php require __DIR__ . "/../tabs/tab_business.php"; ?>
                                    
                                    <!--
                                        Crimes menu
                                    -->
                                    <?php require __DIR__ . "/../tabs/tab_crimes.php"; ?>
                                    
                                    <!--
                                        Casino menu
                                    -->
                                    <?php require __DIR__ . "/../tabs/tab_casino.php"; ?>
                                    
                                    <!--
                                        Communication menu
                                    -->
                                    <?php require __DIR__ . "/../tabs/tab_comm.php"; ?>
                                    
                                    <!--
                                        Help menu
                                    -->
                                    <?php require __DIR__ . "/../tabs/tab_help.php"; ?>
                                </div>
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
