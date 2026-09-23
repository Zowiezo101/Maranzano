
                                    <!-- The Home Tab -->
                                    <div class="tab-pane show active" id="tabHome" role="tabpanel">
                                        <!-- Player stats -->
                                        <div class="row mt-5">
                                            <div class="col">
                                                <!-- Header -->
                                                <div class="row bg-body-tertiary border border-3 border-black">
                                                    <b class="fs-bold fst-normal text-center">Player Stats</b>
                                                </div>
                                                
                                                <!-- Stats -->
                                                <div class="row">
                                                    <div class="col-4 col-lg-3 p-0">
                                                        <img class="img-fluid" src="../img/Mafiani_user.png"/>
                                                    </div>
                                                    <div class="d-flex align-content-center col-8 col-lg-9 mt-1">
                                                        <table id="playerStats" class="fw-bold">
                                                            <!-- Further filled in by JS -->
                                                            <?php printTableTemplate("home", ["u-name", 
                                                                                              "name", 
                                                                                              "prank", 
                                                                                              "created", 
                                                                                              "friends"])?>
                                                        </table>
                
                                                        <!-- In case the information can't be found -->
                                                        <p id="playerStatsError" class="d-none text-center"><?php printString("info.no-player"); ?></p>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                        <!-- Criminal Record -->
                                        <div class="row mt-3">
                                            <div class="col">
                                                <!-- Header -->
                                                <div class="row bg-body-tertiary border border-3 border-black">
                                                    <b class="fs-bold fst-normal text-center">Criminal Record</b>
                                                </div>
                                                
                                                <!-- Stats -->
                                                <div class="row">
                                                    <table id="criminalRecord" class="fw-bold mt-3">
                                                            <!-- Further filled in by JS -->
                                                            <?php printTableTemplate("home", ["bikes", 
                                                                                              "cars", 
                                                                                              "stores", 
                                                                                              "kills", 
                                                                                              "jail", 
                                                                                              "hospital"])?>
                                                    </table>
                
                                                    <!-- In case the information can't be found -->
                                                    <p id="playerCrimeError" class="d-none text-center"><?php printString("info.no-player"); ?></p>
                                                </div>
                                                
                                            </div>
                                        </div>
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
                                    
                                    <!-- The Deceased Tab -->
                                    <div class="tab-pane" id="tabDeceased" role="tabpanel">
                                        <!-- Message -->
                                        <div class="row mt-5 text-center">
                                            <h3 class="fst-normal"><b><?php printString("info.died"); ?><u id="killerName"></u></b><h3>
                                        </div>
                                        
                                        <!-- Tombstone -->
                                        <div class="row mt-5">
                                            <div class="col-10 mx-auto">
                                                <!--<img class="img-fluid" src="../img/Player_died.png"/>-->
                                                <div class="card bg-transparent border-0">
                                                    <img class="card-img" src="../img/Player_died.png"/>
                                                    <div class="card-img-overlay me-3 d-flex justify-content-center align-items-center text-center">
                                                        <p class="card-text">
                                                            <h5 class="mt-3">
                                                                <span id="tombName"></span><br/>
                                                                -<span id="tombRank"></span>-
                                                            </h5>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Start over button -->
                                        <div class="row my-5">
                                            <form id="newPlayerForm">
                                                <!-- Username -->
                                                <div class="mb-3 mx-3 text-center">
                                                    <label for="newPlayer" class="form-label"><?php printString("info.new-name")?></label>
                                                    <input type="text" class="form-control text-center" id="newPlayer">
                                                </div>

                                                <!-- New Player button -->
                                                <div class="mb-3 mx-5 row">
                                                    <button type="submit" class="btn btn-secondary border border-3 border-black"><?php printString("info.startover")?></button>
                                                </div>

                                                <!-- Error message -->
                                                <div id="newPlayerError" class="mb-4 mx-3 text-center text-warning d-none">
                                                    <!-- Filled in later in case of error -->
                                                </div>
                                            </form>
                                        </div>
                                    </div>
