
                                    <!-- The Steal a bike Tab -->
                                    <div class="tab-pane" id="tabBike" role="tabpanel">
                                                
                                        <div id="bikeTry">
                                            <?php printHeader("crimes.bike", "../img/Bike.jpg"); ?>

                                            <!-- Success chance -->
                                            <h5 class="text-center mb-5"><?php printString("crime.info"); ?><span id="bikeRate"></span>%</h5>

                                            <!-- The bike body -->
                                            <form id="bikeForm">       
                                                <!-- Attempt button -->
                                                <div class="col-6 mx-auto">
                                                    <button type="submit" class="btn btn-primary bordered w-100"><?php printString("bike.try")?></button>
                                                </div>

                                                <!-- Error message -->
                                                <div id="bikeError" class="error-message d-none">
                                                    <!-- Filled in later in case of error -->
                                                </div>
                                            </form>
                                        </div>

                                        <div id="bikeSuccess" class="d-none">
                                            <?php printHeader("crimes.bike"); ?>

                                            <!-- The bike image -->
                                            <img id="bikeImg" class="img-fluid mb-3 bordered border-top-0"/>

                                            <!-- Bike worth -->
                                            <p class="text-center fw-bold"><?php printString("bike.worth"); ?><span id="bikeWorth"></span></p>

                                            <!-- Link to the Garage -->
                                            <div class="row">
                                                <div class="col text-center">
                                                    <button class="goToGarage btn btn-link mt-3 fw-bold" onclick="onClickGarage()"><?php printString("business.garage"); ?></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="bikeNoSuccess" class="d-none">
                                            <?php printHeader("crimes.bike", "../img/Failed.jpg"); ?>

                                            <!-- Failed message -->
                                            <p class="text-center fw-bold"><?php printString("crimes.failed"); ?><button class="goToJail btn btn-link m-0 p-0 pb-1" onclick="onClickJail()"><?php printString("main.jail"); ?></button></p>
                                        </div>
                                    </div>
                                    
                                    <!-- The Steal a car Tab -->
                                    <div class="tab-pane" id="tabCar" role="tabpanel">
                                                
                                        <div id="carTry">
                                            <?php printHeader("crimes.car", "../img/Car.png"); ?>

                                            <!-- Success chance -->
                                            <h5 class="text-center mb-5"><?php printString("crime.info"); ?><span id="carRate"></span>%</h5>

                                            <!-- The car body -->
                                            <form id="carForm">       
                                                <!-- Attempt button -->
                                                <div class="col-6 mx-auto">
                                                    <button type="submit" class="btn btn-primary bordered w-100"><?php printString("car.try")?></button>
                                                </div>

                                                <!-- Error message -->
                                                <div id="carError" class="error-message d-none">
                                                    <!-- Filled in later in case of error -->
                                                </div>
                                            </form>
                                        </div>

                                        <div id="carSuccess" class="d-none">
                                            <?php printHeader("crimes.car"); ?>

                                            <!-- The car image -->
                                            <img id="carImg" class="img-fluid mb-3 bordered border-top-0"/>

                                            <!-- Car worth -->
                                            <p class="text-center fw-bold"><?php printString("car.worth"); ?><span id="carWorth"></span></p>

                                            <!-- Link to the Garage -->
                                            <div class="row">
                                                <div class="col text-center">
                                                    <button class="goToGarage btn btn-link mt-3 fw-bold" onclick="onClickGarage()"><?php printString("business.garage"); ?></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="carNoSuccess" class="d-none">
                                            <?php printHeader("crimes.car", "../img/Failed.jpg"); ?>

                                            <!-- Failed message -->
                                            <p class="text-center fw-bold"><?php printString("crimes.failed"); ?><button class="goToJail btn btn-link m-0 p-0 pb-1" onclick="onClickJail()"><?php printString("main.jail"); ?></button></p>
                                        </div>
                                    </div>
                                    
                                    <!-- The Rob a store Tab -->
                                    <div class="tab-pane" id="tabStore" role="tabpanel">
                                                
                                        <div id="storeTry">
                                            <?php printHeader("crimes.store", "../img/Store.jpg"); ?>

                                            <!-- Success chance -->
                                            <h5 class="text-center mb-5"><?php printString("crime.info"); ?><span id="storeRate"></span>%</h5>

                                            <!-- The store body -->
                                            <form id="storeForm">       
                                                <!-- Attempt button -->
                                                <div class="col-6 mx-auto">
                                                    <button type="submit" class="btn btn-primary borderd w-100"><?php printString("store.try")?></button>
                                                </div>

                                                <!-- Error message -->
                                                <div id="storeError" class="error-message d-none">
                                                    <!-- Filled in later in case of error -->
                                                </div>
                                            </form>
                                        </div>

                                        <div id="storeSuccess" class="d-none">
                                            <?php printHeader("crimes.store", "../img/Success.jpg"); ?>

                                            <!-- Store worth -->
                                            <p class="text-center fw-bold"><?php printString("store.worth"); ?><span id="storeWorth"></span></p>
                                        </div>

                                        <div id="storeNoSuccess" class="d-none">
                                            <?php printHeader("crimes.store", "../img/Failed.jpg"); ?>

                                            <!-- Failed message -->
                                            <p class="text-center fw-bold"><?php printString("crimes.failed"); ?><button class="goToJail btn btn-link m-0 p-0 pb-1" onclick="onClickJail()"><?php printString("main.jail"); ?></button></p>
                                        </div>
                                    </div>
                                    
                                    <!-- The Kill player Tab -->
                                    <div class="tab-pane" id="tabKill" role="tabpanel">
                                        Kill
                                    </div>
