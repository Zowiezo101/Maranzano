
                                    <!-- The Online users Tab -->
                                    <div class="tab-pane" id="tabOnline" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">
                                                
                                                <?php printHeader("comms.online"); ?>
                                                
                                                <div class="mt-3 text-center">
                                                    <p id="onlineList">
                                                        <!-- Filled in by JS -->
                                                    </p>
                                                </div>

                                                <!-- Error message -->
                                                <div id="onlineError" class="mb-4 mx-3 text-center text-warning d-none">
                                                    <!-- Filled in later in case of error -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Friend list Tab -->
                                    <div class="tab-pane" id="tabFriends" role="tabpanel">
                                        Friends
                                    </div>
                                    
                                    <!-- The Newspaper Tab -->
                                    <div class="tab-pane" id="tabNews" role="tabpanel">
                                        News
                                    </div>
                                    
                                    <!-- The User list Tab -->
                                    <div class="tab-pane" id="tabUserlist" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">

                                                <!-- Success message -->
                                                <div id="playerSuccess" class="my-2 mx-3 text-center text-warning d-none">
                                                    <!-- Filled in by JS -->
                                                </div>

                                                <!-- Error message -->
                                                <div id="playerError" class="my-2 mx-3 text-center text-warning d-none">
                                                    <!-- Filled in later in case of error -->
                                                </div>
                                                
                                                <?php printHeader("comms.userlist"); ?>
                                                <table id="playerlist" class="table table-sm table-striped table-bordered table-hover fst-normal">
                                                    <tbody>
                                                        <!-- Following rows are added using JS -->
                                                    </tbody>
                                                </table>
                                                
                                                <!-- No players in list -->
                                                <div id="playerlistError" class="text-center text-black d-none">
                                                    <p><b><?php printString("userlist.empty"); ?></b></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Mailbox Tab -->
                                    <div class="tab-pane" id="tabMail" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">

                                                <!-- Success message -->
                                                <div id="mailSuccess" class="my-2 mx-3 text-center text-warning d-none">
                                                    <!-- Filled in by JS -->
                                                </div>

                                                <!-- Error message -->
                                                <div id="mailError" class="my-2 mx-3 text-center text-warning d-none">
                                                    <!-- Filled in later in case of error -->
                                                </div>
                                                
                                                <?php printHeader("comms.mail"); ?>
                                                <table id="maillist" class="align-middle table table-sm table-striped table-bordered table-hover fst-normal">                                                    
                                                    <tbody>
                                                        <!-- Following rows are added using JS -->
                                                    </tbody>
                                                </table>
                                                
                                                <!-- No players in list -->
                                                <div id="maillistError" class="text-center text-black d-none">
                                                    <p><b><?php printString("maillist.empty"); ?></b></p>
                                                </div>

                                                <div class="row mb-5 justify-content-end">                                                    
                                                    <!-- Delete button -->
                                                    <div class="col-4 col-lg-3">
                                                        <button id="delete" onClick="onClickDelete()" class="btn btn-primary border border-3 border-black w-100"><?php printString("maillist.delete"); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
