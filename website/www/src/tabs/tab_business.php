
                                    <!-- The Bank Tab -->
                                    <div class="tab-pane" id="tabBank" role="tabpanel">
                                        Bank
                                    </div>
                                    
                                    <!-- The Bullet shop Tab -->
                                    <div class="tab-pane" id="tabBullet" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">
                                                <!-- The bullet header -->
                                                <h3 class="mt-3 mt-lg-5 mb-0 text-center fst-bold bg-body-tertiary border border-3 border-black"><?php printString("business.bullet"); ?></h3>
                                                
                                                <!-- The bullet image -->
                                                <img class="img-fluid mb-3 border border-3 border-black border-top-0" src="../img/Bullet.jpg"/>
                                                
                                                <!-- Explanation on the shop -->
                                                <p class="text-center mb-5"><?php printString("bullet.info"); ?></p>
                                                
                                                <!-- The bullet body -->
                                                <form id="shopForm">
                                                    <label for="amountSwap" class="form-label"><b class="fst-normal"><?php printString("bullet.amount")?></b></label>
                                                    
                                                    <!-- The amount -->
                                                    <div class="row mb-3">
                                                        <div class="text-center col-8">
                                                            <input type="number" class="form-control" id="shopAmount">
                                                        </div>

                                                        <!-- Buy button -->
                                                        <div class="col-4">
                                                            <button type="submit" class="btn btn-primary border border-3 border-black w-100"><?php printString("bullet.buy")?></button>
                                                        </div>
                                                    </div>

                                                    <!-- Error message -->
                                                    <div id="shopError" class="mb-4 mx-3 text-center text-warning d-none">
                                                        <!-- Filled in later in case of error -->
                                                    </div>
                                                </form>
                                                
                                                <div id="shopSuccess" class="text-center d-none">
                                                    <p><b><?php printString("bullet.success"); ?></b></p>
                                                </div>
                                                
                                                <!-- The bullet swap form -->
                                                <form id="swapForm">
                                                    <label for="amountSwap" class="form-label"><b class="fst-normal"><?php printString("bullet.swap.title")?></b></label>
                                                    
                                                    <!-- The amount -->
                                                    <div class="row mb-3">
                                                        <div class="text-center col-8">
                                                            <input type="number" class="form-control" id="swapAmount">
                                                        </div>

                                                        <!-- Swap button -->
                                                        <div class="col-4">
                                                            <button type="submit" class="btn btn-primary border border-3 border-black w-100"><?php printString("bullet.swap")?></button>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Explanation on swapping bullets -->
                                                    <p class=""><?php printString("bullet.swap.info"); ?></p>

                                                    <!-- Error message -->
                                                    <div id="swapError" class="mb-4 mx-3 text-center text-warning d-none">
                                                        <!-- Filled in later in case of error -->
                                                    </div>
                                                </form>
                                                
                                                <div id="swapSuccess" class="text-center d-none">
                                                    <p><b><?php printString("bullet.swap.success"); ?></b></p>
                                                </div>
                                            </div>
                                        </div>
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
                                        Manage
                                    </div>
