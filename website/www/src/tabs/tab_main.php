
                                    <!-- The Home Tab -->
                                    <div class="tab-pane" id="tabHome" role="tabpanel">
                                        
                                        <!-- Player stats -->
                                        <div class="mt-5">

                                            <!-- Header -->
                                            <div class="row g-0 bg-body-tertiary bordered">
                                                <b class="text-center"><?php printString("info.stats"); ?></b>
                                            </div>

                                            <!-- Stats -->
                                            <div class="row g-0">

                                                <!-- Kinda like a profile photo -->
                                                <div class="col-4 col-lg-3">
                                                    <img class="img-fluid" src="../img/Mafiani_user.png"/>
                                                </div>

                                                <!-- Actual stats -->
                                                <div class="d-flex align-content-center col-8 col-lg-9 mt-1 gx-4">
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
                                        
                                        <!-- Criminal Record -->
                                        <div class="mt-3">
                                            
                                            <!-- Header -->
                                            <div class="row g-0 bg-body-tertiary bordered">
                                                <b class="text-center"><?php printString("info.record"); ?></b>
                                            </div>

                                            <!-- Stats -->
                                            <div class="row g-0">
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
                                    
                                    <!-- The Travel Tab -->
                                    <div class="tab-pane" id="tabTravel" role="tabpanel">
                                        <?php printHeader("main.travel", "../img/Travel.jpg"); ?>

                                        <p class="text-center mb-5"><?php printString("travel.info"); ?></p>

                                        <!-- The travel body -->
                                        <form id="travelForm">
                                            
                                            <!-- The location -->
                                            <div class="mb-3 mx-3 text-center">
                                                <label for="travel" class="form-label fw-bold"><?php printString("travel.where_to")?></label>
                                                <select class="form-select" id="travel">
                                                    <option selected disabled><?php printString("travel.select"); ?></option>
                                                </select>
                                            </div>

                                            <!-- Travel button -->
                                            <div class="mb-3 mx-5 row">
                                                <button type="submit" class="btn btn-primary bordered"><?php printString("main.travel")?></button>
                                            </div>

                                            <!-- Error message -->
                                            <div id="travelError" class="error-message d-none">
                                                <!-- Filled in later in case of error -->
                                            </div>
                                        </form>

                                        <div id="travelSuccess" class="text-center d-none">
                                            <p><b><?php printString("travel.success"); ?></b></p>
                                        </div>
                                    </div>
                                    
                                    <!-- The Jail Tab -->
                                    <div class="tab-pane" id="tabJail" role="tabpanel">
                                        <?php printHeader("main.jail", "../img/Jail.jpg"); ?>

                                        <!-- The jail table -->
                                        <table id="jail" class="fw-bold table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?php printString("jail.info.name"); ?></th>
                                                    <th><?php printString("jail.info.time"); ?></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Following rows are added using JS -->
                                            </tbody>
                                        </table>

                                        <!-- No players in jail -->
                                        <div id="jailError" class="warning-message d-none">
                                            <p><b><?php printString("jail.empty"); ?></b></p>
                                        </div>

                                        <!-- We have been sent to Jail -->
                                        <div id="actionNoSuccess" class="error-message d-none">
                                            <!-- Failed message -->
                                            <p class="text-center fw-bold"><?php printString("crimes.failed"); ?><?php printString("main.jail"); ?></p>
                                        </div>

                                        <!-- Error message -->
                                        <div id="actionError" class="error-message d-none">
                                            <!-- Filled in later in case of error -->
                                        </div>
                                    </div>
                                    
                                    <!-- The Hospital Tab -->
                                    <div class="tab-pane" id="tabHospital" role="tabpanel">
                                        <?php printHeader("main.hospital", "../img/Hospital.png"); ?>

                                        <!-- The hospital text -->
                                        <p class="text-center"><b><?php printString("hospital.remaining"); ?></b><span id="hospitalTime"></span></p>

                                        <!-- Hospital form(?) -->
                                        <div id="hospitalBill" class="d-none">

                                            <!-- The amount -->
                                            <div class="row mt-3">
                                                <div class="col-12 mx-auto text-center">
                                                    <p class="fw-bold"><?php printString("hospital.pay.title")?> (€<span id="hospitalAmount"></span>)</p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-6 mx-auto">
                                                    <button onclick="payHospitalBill()" class="btn btn-primary bordered w-100"><?php printString("hospital.pay")?></button>
                                                </div>
                                            </div>

                                            <div id="hospitalSuccess" class="text-center d-none">
                                                <p class="fw-bold"><?php printString("hospital.pay.success"); ?></p>
                                            </div>
                                        </div>

                                        <!-- Error message -->
                                        <div id="hospitalError" class="error-message d-none">
                                            <!-- Filled in later in case of error -->
                                        </div>
                                    </div>
                                    
                                    <!-- The Deceased Tab -->
                                    <div class="tab-pane" id="tabDeceased" role="tabpanel">
                                        <!-- Message -->
                                        <div class="row mt-5 text-center">
                                            <h3 class="fw-bold"><?php printString("info.died"); ?><u id="killerName"></u></h3>
                                        </div>
                                        
                                        <!-- Tombstone -->
                                        <div class="row mt-5">
                                            <div class="col-10 mx-auto">
                                                
                                                <!-- Card with background image -->
                                                <div class="card bg-transparent border-0">
                                                    
                                                    <!-- The background image -->
                                                    <img class="card-img" src="../img/Player_died.png"/>
                                                    
                                                    <!-- The text overlaying the image -->
                                                    <div class="card-img-overlay me-3 d-flex justify-content-center align-items-center text-center">
                                                        
                                                        <!-- Tomb text -->
                                                        <h5 class="mt-3">
                                                            <span id="tombName"></span><br/>
                                                            -<span id="tombRank"></span>-
                                                        </h5>
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
                                                    <button type="submit" class="btn btn-secondary bordered"><?php printString("info.startover")?></button>
                                                </div>

                                                <!-- Error message -->
                                                <div id="newPlayerError" class="error-message d-none">
                                                    <!-- Filled in later in case of error -->
                                                </div>
                                            </form>
                                        </div>
                                    </div>
