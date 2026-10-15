
                                    <!-- The Bank Tab -->
                                    <div class="tab-pane" id="tabBank" role="tabpanel">
                                        <?php printHeader("business.bank", "../img/Bank.jpg"); ?>

                                        <!-- Explanation on the bank -->
                                        <p class="text-center mb-5"><?php printString("bank.info"); ?></p>

                                        <!-- Deposit form -->
                                        <form id="depositForm">
                                            <label for="depositAmount" class="form-label fw-bold"><?php printString("bank.deposit.title")?></label>

                                            <!-- The amount -->
                                            <div class="row mb-3">
                                                <div class="text-center col-8">
                                                    <input type="number" class="form-control" id="depositAmount">
                                                </div>

                                                <!-- Deposit button -->
                                                <div class="col-4">
                                                    <button type="submit" class="btn btn-primary bordered w-100"><?php printString("bank.deposit")?></button>
                                                </div>
                                            </div>

                                            <!-- Error message -->
                                            <div id="depositError" class="error-message d-none">
                                                <!-- Filled in later in case of error -->
                                            </div>

                                            <div id="depositSuccess" class="text-center d-none">
                                                <p class="fw-bold"><?php printString("bank.deposit.success"); ?></p>
                                            </div>
                                        </form>

                                        <!-- Withdrawal form -->
                                        <form id="withdrawForm">
                                            <label for="withdrawAmount" class="form-label fw-bold"><?php printString("bank.withdraw.title")?></label>

                                            <!-- The amount -->
                                            <div class="row mb-3">
                                                <div class="text-center col-8">
                                                    <input type="number" class="form-control" id="withdrawAmount">
                                                </div>

                                                <!-- Withdraw button -->
                                                <div class="col-4">
                                                    <button type="submit" class="btn btn-primary bordered w-100"><?php printString("bank.withdraw")?></button>
                                                </div>
                                            </div>

                                            <!-- Error message -->
                                            <div id="withdrawError" class="error-message d-none">
                                                <!-- Filled in later in case of error -->
                                            </div>

                                            <div id="withdrawSuccess" class="text-center d-none">
                                                <p class="fw-bold"><?php printString("bank.withdraw.success"); ?></p>
                                            </div>
                                        </form>

                                        <!-- Send to friend form -->
                                        <form id="sendForm" class="mt-5">

                                            <!-- Explanation on sending money to a friend -->
                                            <p><?php printString("bank.send.title"); ?></p>

                                            <div class="row mb-3">
                                                <div class="text-center col-4">
                                                    <label for="sendAmount" class="form-label fw-bold"><?php printString("bank.amount")?></label>
                                                </div>

                                                <div class="text-center col-4">
                                                    <label for="sendFriend" class="form-label fw-bold"><?php printString("bank.friend")?></label>
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
                                                    <button type="submit" class="btn btn-primary bordered w-100"><?php printString("bank.send")?></button>
                                                </div>
                                            </div>

                                            <!-- Error message -->
                                            <div id="sendError" class="error-message d-none">
                                                <!-- Filled in later in case of error -->
                                            </div>


                                            <div id="sendSuccess" class="text-center d-none">
                                                <p><b><?php printString("bank.send.success"); ?></b></p>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- The Bullet shop Tab -->
                                    <div class="tab-pane" id="tabBullet" role="tabpanel">
                                        <?php printHeader("business.bullet", "../img/Bullet.jpg"); ?>

                                        <!-- Explanation on the shop -->
                                        <p class="text-center mb-5"><?php printString("bullet.info"); ?></p>

                                        <!-- The bullet body -->
                                        <form id="shopForm" class="mb-5">
                                            <label for="shopAmount" class="form-label fw-bold"><?php printString("bullet.amount")?></label>

                                            <!-- The amount -->
                                            <div class="row mb-3">
                                                <div class="text-center col-8">
                                                    <input type="number" class="form-control" id="shopAmount">
                                                </div>

                                                <!-- Buy button -->
                                                <div class="col-4">
                                                    <button type="submit" class="btn btn-primary bordered w-100"><?php printString("bullet.buy")?></button>
                                                </div>
                                            </div>

                                            <!-- Error message -->
                                            <div id="shopError" class="error-message d-none">
                                                <!-- Filled in later in case of error -->
                                            </div>
                                        </form>

                                        <div id="shopSuccess" class="text-center d-none">
                                            <p class="fw-bold"><?php printString("bullet.success"); ?></p>
                                        </div>

                                        <!-- The bullet swap form -->
                                        <form id="swapForm">
                                            <label for="swapAmount" class="form-label fw-bold"><?php printString("bullet.swap.title")?></label>

                                            <!-- The amount -->
                                            <div class="row mb-3">
                                                <div class="text-center col-8">
                                                    <input type="number" class="form-control" id="swapAmount">
                                                </div>

                                                <!-- Swap button -->
                                                <div class="col-4">
                                                    <button type="submit" class="btn btn-primary bordered w-100"><?php printString("bullet.swap")?></button>
                                                </div>
                                            </div>

                                            <!-- Explanation on swapping bullets -->
                                            <p><?php printString("bullet.swap.info"); ?></p>

                                            <!-- Error message -->
                                            <div id="swapError" class="error-message d-none">
                                                <!-- Filled in later in case of error -->
                                            </div>

                                            <div id="swapSuccess" class="text-center d-none">
                                                <p class="fw-bold"><?php printString("bullet.swap.success"); ?></p>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- The Garage Tab -->
                                    <div class="tab-pane" id="tabGarage" role="tabpanel">
                                        <?php printHeader("business.garage"); ?>

                                        <!-- The Garage form -->
                                        <form id="garageForm">

                                            <!-- The garage table -->
                                            <table id="garageTable" class="fw-bold table align-middle table-borderless table-hover text-center">
                                                <thead>
                                                    <tr>
                                                        <th class="w-50"></th>
                                                        <th><?php printString("garage.select_all"); ?></th>
                                                        <th><input class="form-check-input" type="checkbox" id="checkAll" onclick="onSelectAll()"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Following rows are added using JS -->
                                                </tbody>
                                            </table>

                                            <!-- No vehicles in garage -->
                                            <div id="garageTableError" class="warning-message d-none">
                                                <p class="fw-bold"><?php printString("garage.empty"); ?></p>
                                            </div>

                                            <!-- Error message -->
                                            <div id="sellError" class="error-message d-none">
                                                <!-- Filled in later in case of error -->
                                            </div>

                                            <!-- Sell button -->
                                            <div class="row mb-5 justify-content-end">
                                                <div class="col-4 col-lg-3">
                                                    <button type="submit" class="btn btn-primary bordered w-100"><?php printString("garage.sell"); ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- The Family Tab -->
                                    <div class="tab-pane" id="tabFamily" role="tabpanel">
                                        Family
                                    </div>
                                    
                                    <!-- The Manage family Tab -->
                                    <div class="tab-pane" id="tabManage" role="tabpanel">
                                        Manage
                                    </div>
