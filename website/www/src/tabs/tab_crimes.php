
                                    <!-- The Steal a bike Tab -->
                                    <div class="tab-pane" id="tabBike" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">
                                                
                                                <div id="bikeTry">
                                                    <?php printHeader("crimes.bike", "../img/Bike.jpg"); ?>

                                                    <!-- Success chance -->
                                                    <h5 class="text-center mb-5"><?php printString("crime.info"); ?><span id="bikeRate"></span>%</h5>

                                                    <!-- The bike body -->
                                                    <form id="bikeForm">       
                                                        <!-- Attempt button -->
                                                        <div class="col-6 mx-auto">
                                                            <button type="submit" class="btn btn-primary border border-3 border-black w-100"><?php printString("bike.try")?></button>
                                                        </div>

                                                        <!-- Error message -->
                                                        <div id="bikeError" class="mb-4 mx-3 text-center text-warning d-none">
                                                            <!-- Filled in later in case of error -->
                                                        </div>
                                                    </form>
                                                </div>
                                                
                                                <div id="bikeSuccess" class="d-none">
                                                    <?php printHeader("crimes.bike"); ?>
                                                
                                                    <!-- The bike image -->
                                                    <img id="bikeImg" class="img-fluid mb-3 border border-3 border-black border-top-0"/>

                                                    <!-- Bike worth -->
                                                    <b><p class="text-center"><?php printString("bike.worth"); ?><span id="bikeWorth"></span></p></b>
                                                    
                                                    <!-- Link to the Garage -->
                                                    <div class="row">
                                                        <div class="col text-center">
                                                            <button class="goToGarage btn btn-link mt-3"><b><?php printString("business.garage"); ?></b></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div id="bikeNoSuccess" class="d-none">
                                                    <?php printHeader("crimes.bike", "../img/Failed.jpg"); ?>

                                                    <!-- Failed message -->
                                                    <b><p class="text-center"><?php printString("crimes.failed"); ?><button class="goToJail btn btn-link m-0 p-0 pb-1"><b><?php printString("main.jail"); ?></b></button></p></b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Steal a car Tab -->
                                    <div class="tab-pane" id="tabCar" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">
                                                
                                                <div id="carTry">
                                                    <?php printHeader("crimes.car", "../img/Car.png"); ?>

                                                    <!-- Success chance -->
                                                    <h5 class="text-center mb-5"><?php printString("crime.info"); ?><span id="carRate"></span>%</h5>

                                                    <!-- The car body -->
                                                    <form id="carForm">       
                                                        <!-- Attempt button -->
                                                        <div class="col-6 mx-auto">
                                                            <button type="submit" class="btn btn-primary border border-3 border-black w-100"><?php printString("car.try")?></button>
                                                        </div>

                                                        <!-- Error message -->
                                                        <div id="carError" class="mb-4 mx-3 text-center text-warning d-none">
                                                            <!-- Filled in later in case of error -->
                                                        </div>
                                                    </form>
                                                </div>
                                                
                                                <div id="carSuccess" class="d-none">
                                                    <?php printHeader("crimes.car"); ?>
                                                
                                                    <!-- The car image -->
                                                    <img id="carImg" class="img-fluid mb-3 border border-3 border-black border-top-0"/>

                                                    <!-- Car worth -->
                                                    <b><p class="text-center"><?php printString("car.worth"); ?><span id="carWorth"></span></p></b>
                                                    
                                                    <!-- Link to the Garage -->
                                                    <div class="row">
                                                        <div class="col text-center">
                                                            <button class="goToGarage btn btn-link mt-3"><b><?php printString("business.garage"); ?></b></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div id="carNoSuccess" class="d-none">
                                                    <?php printHeader("crimes.car", "../img/Failed.jpg"); ?>

                                                    <!-- Failed message -->
                                                    <b><p class="text-center"><?php printString("crimes.failed"); ?><button class="goToJail btn btn-link m-0 p-0 pb-1"><b><?php printString("main.jail"); ?></b></button></p></b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Rob a store Tab -->
                                    <div class="tab-pane" id="tabStore" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">
                                                
                                                <div id="storeTry">
                                                    <?php printHeader("crimes.store", "../img/Store.jpg"); ?>

                                                    <!-- Success chance -->
                                                    <h5 class="text-center mb-5"><?php printString("crime.info"); ?><span id="storeRate"></span>%</h5>

                                                    <!-- The store body -->
                                                    <form id="storeForm">       
                                                        <!-- Attempt button -->
                                                        <div class="col-6 mx-auto">
                                                            <button type="submit" class="btn btn-primary border border-3 border-black w-100"><?php printString("store.try")?></button>
                                                        </div>

                                                        <!-- Error message -->
                                                        <div id="storeError" class="mb-4 mx-3 text-center text-warning d-none">
                                                            <!-- Filled in later in case of error -->
                                                        </div>
                                                    </form>
                                                </div>
                                                
                                                <div id="storeSuccess" class="d-none">
                                                    <?php printHeader("crimes.store", "../img/Success.jpg"); ?>

                                                    <!-- Store worth -->
                                                    <b><p class="text-center"><?php printString("store.worth"); ?><span id="storeWorth"></span></p></b>
                                                </div>
                                                
                                                <div id="storeNoSuccess" class="d-none">
                                                    <?php printHeader("crimes.store", "../img/Failed.jpg"); ?>

                                                    <!-- Failed message -->
                                                    <b><p class="text-center"><?php printString("crimes.failed"); ?><button class="goToJail btn btn-link m-0 p-0 pb-1"><b><?php printString("main.jail"); ?></b></button></p></b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Kill player Tab -->
                                    <div class="tab-pane" id="tabKill" role="tabpanel">
                                        Kill
                                    </div>
