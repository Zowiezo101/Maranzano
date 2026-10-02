
                                    <!-- The Bank Tab -->
                                    <div class="tab-pane" id="tabBank" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">
                                                <?php printHeader("business.bank", "../img/Bank.jpg"); ?>
                                                
                                                <!-- Explanation on the bank -->
                                                <p class="text-center mb-5"><?php printString("bank.info"); ?></p>
                                                
                                                <!-- Deposit form -->
                                                <form id="depositForm">
                                                    <label for="depositAmount" class="form-label"><b class="fst-normal"><?php printString("bank.deposit.title")?></b></label>
                                                    
                                                    <!-- The amount -->
                                                    <div class="row mb-3">
                                                        <div class="text-center col-8">
                                                            <input type="number" class="form-control" id="depositAmount">
                                                        </div>

                                                        <!-- Deposit button -->
                                                        <div class="col-4">
                                                            <button type="submit" class="btn btn-primary border border-3 border-black w-100"><?php printString("bank.deposit")?></button>
                                                        </div>
                                                    </div>

                                                    <!-- Error message -->
                                                    <div id="depositError" class="mb-4 mx-3 text-center text-warning d-none">
                                                        <!-- Filled in later in case of error -->
                                                    </div>
                                                
                                                    <div id="depositSuccess" class="text-center d-none">
                                                        <p><b><?php printString("bank.deposit.success"); ?></b></p>
                                                    </div>
                                                </form>
                                                
                                                <!-- Withdrawal form -->
                                                <form id="withdrawForm">
                                                    <label for="withdrawAmount" class="form-label"><b class="fst-normal"><?php printString("bank.withdraw.title")?></b></label>
                                                    
                                                    <!-- The amount -->
                                                    <div class="row mb-3">
                                                        <div class="text-center col-8">
                                                            <input type="number" class="form-control" id="withdrawAmount">
                                                        </div>

                                                        <!-- Withdraw button -->
                                                        <div class="col-4">
                                                            <button type="submit" class="btn btn-primary border border-3 border-black w-100"><?php printString("bank.withdraw")?></button>
                                                        </div>
                                                    </div>

                                                    <!-- Error message -->
                                                    <div id="withdrawError" class="mb-4 mx-3 text-center text-warning d-none">
                                                        <!-- Filled in later in case of error -->
                                                    </div>
                                                
                                                    <div id="withdrawSuccess" class="text-center d-none">
                                                        <p><b><?php printString("bank.withdraw.success"); ?></b></p>
                                                    </div>
                                                </form>
                                                
                                                <!-- Send to friend form -->
                                                <form id="sendForm" class="mt-5">
                                                
                                                    <!-- Explanation on swapping bullets -->
                                                    <p class=""><?php printString("bank.send.title"); ?></p>
                                                    
                                                    <div class="row mb-3">
                                                        <div class="text-center col-4">
                                                            <label for="sendAmount" class="form-label"><b class="fst-normal"><?php printString("bank.amount")?></b></label>
                                                        </div>
                                                        
                                                        <div class="text-center col-4">
                                                            <label for="sendFriend" class="form-label"><b class="fst-normal"><?php printString("bank.friend")?></b></label>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- The amount -->
                                                    <div class="row mb-3">
                                                        <div class="text-center col-4">
                                                            <input type="number" class="form-control" id="sendAmount">
                                                        </div>
                                                        
                                                        <div class="col-4 px-0">
                                                            <select id="friend" class="form-select">
                                                                <option selected disabled><?php printString("bank.friend"); ?></option>
                                                            </select>
                                                        </div>

                                                        <!-- Send button -->
                                                        <div class="col-4">
                                                            <button type="submit" class="btn btn-primary border border-3 border-black w-100"><?php printString("bank.send")?></button>
                                                        </div>
                                                    </div>

                                                    <!-- Error message -->
                                                    <div id="sendError" class="mb-4 mx-3 text-center text-warning d-none">
                                                        <!-- Filled in later in case of error -->
                                                    </div>
                                                    
                                                
                                                    <div id="sendSuccess" class="text-center d-none">
                                                        <p><b><?php printString("bank.send.success"); ?></b></p>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Bullet shop Tab -->
                                    <div class="tab-pane" id="tabBullet" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">
                                                <?php printHeader("business.bullet", "../img/Bullet.jpg"); ?>
                                                
                                                <!-- Explanation on the shop -->
                                                <p class="text-center mb-5"><?php printString("bullet.info"); ?></p>
                                                
                                                <!-- The bullet body -->
                                                <form id="shopForm">
                                                    <label for="shopAmount" class="form-label"><b class="fst-normal"><?php printString("bullet.amount")?></b></label>
                                                    
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
                                                    <label for="swapAmount" class="form-label"><b class="fst-normal"><?php printString("bullet.swap.title")?></b></label>
                                                    
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
                                                    
                                                    <div id="swapSuccess" class="text-center d-none">
                                                        <p><b><?php printString("bullet.swap.success"); ?></b></p>
                                                    </div>
                                                </form>
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
