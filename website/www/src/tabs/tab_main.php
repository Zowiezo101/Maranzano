
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
                                                            <?php printTableTemplate("home", ["name", 
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
                                        You died!!
                                        
                                        You were killed by <b id="killerName"></b>
                                    </div>
