<?php
    
    require __DIR__ . "/../tools/base.php";
    
    // If we are logged in, do nothing
    // Otherwise, go to the homepage
    checkLoggedIn("", "/");
    
    $page_title = "global.title";
    
//    $user = getUserInfo();
    $user = [
        "cash" => "€100",
        "bank" => "€10",
        "rank" => "Godfather",
        "progress" => "98.7%",
        "family" => "-None-",
        "city" => "Gouda",
        "country" => "Netherlands",
        "health" => "100%",
        "bullets" => "10.050",
        "shields" => "20.000",
    ];
    
    function printTable($user) {
        if (isset($user)) {
            foreach ($user as $key => $value) {
                getTableRow($key, $value);
            }
        }
    }
    
    function getTableRow($key, $value) {
        $table_row = '
                                    <tr><th class="fst-normal text-black">'.getString("info.{$key}").':</th>
                                        <td class="text-center">'.$value.'</td></tr>';
        echo $table_row;
    }
    
    function printMenu($category, $tab_names) {
        
        $tabs = [];
        // Get all the tab names and create a tablist with them
        foreach ($tab_names as $tab_name) {
            $tabs[] = getTab($category, $tab_name);
        }

        // The menu
        $menu = '                                 
                                    <!-- '.ucfirst($category).' menu -->
                                    <div class="mt-2">
                                        <!-- Menu titel -->
                                        <div class="bg-body-tertiary fst-normal fw-bold text-center border border-3 border-black">
                                            <button type="button" class="btn bg-body-tertiary collapsible" data-bs-toggle="collapse" data-bs-target="#acc-'.$category.'" aria-expanded="true" aria-controls="acc-'.$category.'">
                                                <b>'.getString("menu.$category").'</b>
                                            </button>
                                        </div>

                                        <!-- Menu items -->
                                        <div id="acc-'.$category.'" class="show row justify-content-end">
                                            <div class="col-10">
                                                <div class="btn-group-vertical">
                                                    '. join("
                                                    ", $tabs).'
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
        
        echo $menu;
    }
    
    function getTab($category, $tab) {
        // The hometab is active on each reload
        $active = $tab == "home";
        
        // Create the tab
        $tab = '<button type="button" class="btn btn-link'.($active ? " active" : "").'" data-bs-toggle="tab" data-bs-target="#tab'. ucfirst($tab).'" role="tab">'.getString("$category.$tab").'</button>';
        return $tab;
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
                                <table class="fw-bold">
                                    <?php printTable($user); ?>
                                </table>
                            </div>
                            
                            <!-- Menu -->
                            <div class="row">
                                <div class="col-12 mt-2 accordion" role="tablist">
                                    <?php printMenu("main", ["home", 
                                                             "travel", 
                                                             "jail", 
                                                             "hospital"]) ?>
                                    
                                    <?php printMenu("business", ["bank", 
                                                                 "bullet", 
                                                                 "garage", 
                                                                 "family", 
                                                                 "manage"]) ?>
                                    
                                    <?php printMenu("crimes", ["bike", 
                                                               "car", 
                                                               "store", 
                                                               "kill"]) ?>
                                    
                                    <?php printMenu("casino", ["roulette", 
                                                               "scratch"]) ?>
                                    
                                    <?php printMenu("comms", ["online", 
                                                              "friends", 
                                                              "userlist", 
                                                              "mail"]) ?>
                                    
                                    <?php printMenu("help", ["contact", 
                                                             "donate", 
                                                             "rules", 
                                                             "info", 
                                                             "settings"]) ?>

                                    <!-- X Users online -->
                                    <div class="mt-3 px-3">
                                        <?php getUserAmount(); ?>
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
                                <div class="col tab-content" role="tab-content">
                                    <!--
                                        Main menu
                                    -->
                                    
                                    <!-- The Home Tab -->
                                    <div class="tab-pane show active" id="tabHome" role="tabpanel">
                                        Home
                                    </div>
                                    
                                    <!-- The Travel Tab -->
                                    <div class="tab-pane" id="tabTravel" role="tabpanel">
                                        Travel
                                    </div>
                                    
                                    <!-- The Jail Tab -->
                                    <div class="tab-pane" id="tabJail" role="tabpanel">
                                        Jail
                                    </div>
                                    
                                    <!-- The Hospital Tab -->
                                    <div class="tab-pane" id="tabHospital" role="tabpanel">
                                        Hospital
                                    </div>
                                    
                                    <!--
                                        Businesses menu
                                    -->
                                    
                                    <!-- The Bank Tab -->
                                    <div class="tab-pane" id="tabBank" role="tabpanel">
                                        Bank
                                    </div>
                                    
                                    <!-- The Bullet shop Tab -->
                                    <div class="tab-pane" id="tabBullet" role="tabpanel">
                                        Bullet shop
                                    </div>
                                    
                                    <!-- The Garage Tab -->
                                    <div class="tab-pane" id="tabGarage" role="tabpanel">
                                        Garage
                                    </div>
                                    
                                    <!-- The Family Tab -->
                                    <div class="tab-pane" id="tabFamily" role="tabpanel">
                                        Family
                                    </div>
                                    
                                    <!-- The Manage family Tab -->
                                    <div class="tab-pane" id="tabManage" role="tabpanel">
                                        Manage family
                                    </div>
                                    
                                    <!--
                                        Crimes menu
                                    -->
                                    
                                    <!-- The Steal a bike Tab -->
                                    <div class="tab-pane" id="tabBike" role="tabpanel">
                                        Bike
                                    </div>
                                    
                                    <!-- The Steal a car Tab -->
                                    <div class="tab-pane" id="tabCar" role="tabpanel">
                                        Car
                                    </div>
                                    
                                    <!-- The Rob a store Tab -->
                                    <div class="tab-pane" id="tabStore" role="tabpanel">
                                        Store
                                    </div>
                                    
                                    <!-- The Kill player Tab -->
                                    <div class="tab-pane" id="tabKill" role="tabpanel">
                                        Kill player
                                    </div>
                                    
                                    <!--
                                        Casino menu
                                    -->
                                    
                                    <!-- The Roulette Tab -->
                                    <div class="tab-pane" id="tabRoulette" role="tabpanel">
                                        Roulette
                                    </div>
                                    
                                    <!-- The Scratch & Match Tab -->
                                    <div class="tab-pane" id="tabScratch" role="tabpanel">
                                        Scratch & Match
                                    </div>
                                    
                                    <!--
                                        Communication menu
                                    -->
                                    
                                    <!-- The Online users Tab -->
                                    <div class="tab-pane" id="tabOnline" role="tabpanel">
                                        Online users
                                    </div>
                                    
                                    <!-- The Friend list Tab -->
                                    <div class="tab-pane" id="tabFriends" role="tabpanel">
                                        Friend list
                                    </div>
                                    
                                    <!-- The User list Tab -->
                                    <div class="tab-pane" id="tabUserlist" role="tabpanel">
                                        User list
                                    </div>
                                    
                                    <!-- The Mailbox Tab -->
                                    <div class="tab-pane" id="tabMail" role="tabpanel">
                                        Mailbox
                                    </div>
                                    
                                    <!--
                                        Help menu
                                    -->
                                    
                                    <!-- The Contact Tab -->
                                    <div class="tab-pane" id="tabContact" role="tabpanel">
                                        Contact
                                    </div>
                                    
                                    <!-- The Donate Tab -->
                                    <div class="tab-pane" id="tabDonate" role="tabpanel">
                                        Donate
                                    </div>
                                    
                                    <!-- The Rules Tab -->
                                    <div class="tab-pane" id="tabRules" role="tabpanel">
                                        Rules
                                    </div>
                                    
                                    <!-- The Info Tab -->
                                    <div class="tab-pane" id="tabInfo" role="tabpanel">
                                        Info
                                    </div>
                                    
                                    <!-- The Settings Tab -->
                                    <div class="tab-pane" id="tabSettings" role="tabpanel">
                                        Settings
                                    </div>
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
