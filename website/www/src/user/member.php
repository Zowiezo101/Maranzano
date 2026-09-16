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
                                <div class="col-12" role="tablist">
                                    
                                    <!-- Main menu -->
                                    <div class="mt-4">
                                        <!-- Menu titel -->
                                        <div class="bg-body-tertiary fst-normal fw-bold text-center border border-3 border-black">
                                            <b><?php printString("menu.main"); ?></b>
                                        </div>

                                        <!-- Menu items -->
                                        <div class="row justify-content-end">
                                            <div class="col-10">
                                                <div class="btn-group-vertical">
                                                    <button type="button" class="btn btn-link fw-bold text-start active" data-bs-toggle="tab"   data-bs-target="#tabHome"     role="tab"><?php printString("main.home");     ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start"        data-bs-toggle="tab"   data-bs-target="#tabTravel"   role="tab"><?php printString("main.travel");   ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start"        data-bs-toggle="tab"   data-bs-target="#tabJail"     role="tab"><?php printString("main.jail");     ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start"        data-bs-toggle="tab"   data-bs-target="#tabHospital" role="tab"><?php printString("main.hospital"); ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start"        data-bs-toggle="tab"   data-bs-target="#tabFriends"  role="tab"><?php printString("main.friends");  ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start"        data-bs-toggle="tab"   data-bs-target="#tabOnline"   role="tab"><?php printString("main.online");   ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Crimes menu -->
                                    <div class="mt-2">
                                        <!-- Menu titel -->
                                        <div class="bg-body-tertiary fst-normal fw-bold text-center border border-3 border-black">
                                            <b><?php printString("menu.crimes"); ?></b>
                                        </div>

                                        <!-- Menu items -->
                                        <div class="row justify-content-end">
                                            <div class="col-10">
                                                <div class="btn-group-vertical">
                                                    <button type="button" class="btn btn-link fw-bold text-start" data-bs-toggle="tab"   data-bs-target="#tabBike"   role="tab"><?php printString("crimes.bike");   ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start" data-bs-toggle="tab"   data-bs-target="#tabCar"    role="tab"><?php printString("crimes.car");    ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start" data-bs-toggle="tab"   data-bs-target="#tabStore"  role="tab"><?php printString("crimes.store");  ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start" data-bs-toggle="tab"   data-bs-target="#tabGarage" role="tab"><?php printString("crimes.garage"); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Comunication menu -->
                                    <div class="mt-2">
                                        <!-- Menu titel -->
                                        <div class="bg-body-tertiary fst-normal fw-bold text-center border border-3 border-black">
                                            <b><?php printString("menu.comms"); ?></b>
                                        </div>

                                        <!-- Menu items -->
                                        <div class="row justify-content-end">
                                            <div class="col-10">
                                                <div class="btn-group-vertical">
                                                    <button type="button" class="btn btn-link fw-bold text-start" data-bs-toggle="tab"   data-bs-target="#tabForum"   role="tab"><?php printString("comms.forum");   ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start" data-bs-toggle="tab"   data-bs-target="#tabFamily"  role="tab"><?php printString("comms.family");  ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start" data-bs-toggle="tab"   data-bs-target="#tabContact" role="tab"><?php printString("comms.contact"); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Settings menu -->
                                    <div class="mt-2">
                                        <!-- Menu titel -->
                                        <div class="bg-body-tertiary fst-normal fw-bold text-center border border-3 border-black">
                                            <b><?php printString("menu.settings"); ?></b>
                                        </div>

                                        <!-- Menu items -->
                                        <div class="row justify-content-end">
                                            <div class="col-10">
                                                <div class="btn-group-vertical">
                                                    <button type="button" class="btn btn-link fw-bold text-start" data-bs-toggle="tab"   data-bs-target="#tabInfo"   role="tab"><?php printString("settings.info");   ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start" data-bs-toggle="tab"   data-bs-target="#tabUser"   role="tab"><?php printString("settings.user");   ?></button>
                                                    <button type="button" class="btn btn-link fw-bold text-start" data-bs-toggle="tab"   data-bs-target="#tabLogout" role="tab"><?php printString("settings.logout"); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                    
                                    <!-- The Friend list Tab -->
                                    <div class="tab-pane" id="tabFriends" role="tabpanel">
                                        Friend list
                                    </div>
                                    
                                    <!-- The Online users Tab -->
                                    <div class="tab-pane" id="tabOnline" role="tabpanel">
                                        Online users
                                    </div>
                                    
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
                                    
                                    <!-- The Garage Tab -->
                                    <div class="tab-pane" id="tabGarage" role="tabpanel">
                                        Garage
                                    </div>
                                    
                                    <!-- The Forum Tab -->
                                    <div class="tab-pane" id="tabForum" role="tabpanel">
                                        Forum
                                    </div>
                                    
                                    <!-- The Family Tab -->
                                    <div class="tab-pane" id="tabFamily" role="tabpanel">
                                        Family
                                    </div>
                                    
                                    <!-- The Contact us Tab -->
                                    <div class="tab-pane" id="tabContact" role="tabpanel">
                                        Contact us
                                    </div>
                                    
                                    <!-- The Info Tab -->
                                    <div class="tab-pane" id="tabInfo" role="tabpanel">
                                        Info
                                    </div>
                                    
                                    <!-- The User settings Tab -->
                                    <div class="tab-pane" id="tabUser" role="tabpanel">
                                        User settings
                                    </div>
                                    
                                    <!-- The Log out Tab -->
                                    <div class="tab-pane" id="tabLogout" role="tabpanel">
                                        Log out
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
