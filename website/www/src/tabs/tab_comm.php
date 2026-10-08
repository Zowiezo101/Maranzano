
                                    <!-- The Online users Tab -->
                                    <div class="tab-pane" id="tabOnline" role="tabpanel">
                                        Online
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
                                                    <p><b><?php printString("garage.empty"); ?></b></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Mailbox Tab -->
                                    <div class="tab-pane" id="tabMail" role="tabpanel">
                                        Mail
                                    </div>
