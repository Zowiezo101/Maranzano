
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
                                        <div class="row">
                                            <div class="col p-0">
                                                
                                                <?php printHeader("comms.friends"); ?>
                                        
                                                <!-- Clipboard -->
                                                <div class="row mt-2">
                                                    <div class="col-lg-10 mx-auto">
                                                        <div class="card bg-transparent border-0">
                                                            <!-- Clipboard image -->
                                                            <img class="card-img" src="../img/Friends.png"/>
                                                            
                                                            <!-- Text contents -->
                                                            <div id="friendsCard" class="card-img-overlay p-0">
                                                                <b class="fst-normal"><?php printString("friends.online"); ?></b>
                                                                <table id="onlineFriends" class="fw-bold table table-sm table-hover">
                                                                    <tbody>
                                                                        <!-- Rows are added using JS -->
                                                                    </tbody>
                                                                </table>
                                                
                                                                <!-- No players in list -->
                                                                <div id="onlineFriendsError" class="text-center text-black d-none">
                                                                    <p><b><?php printString("friends.online.empty"); ?></b></p>
                                                                </div>
                                                                
                                                                <br/>
                                                                
                                                                <b class="fst-normal"><?php printString("friends.offline"); ?></b>
                                                                <table id="offlineFriends" class="fw-bold table table-hover">
                                                                    <tbody>
                                                                        <!-- Rows are added using JS -->
                                                                    </tbody>
                                                                </table>
                                                
                                                                <!-- No players in list -->
                                                                <div id="offlineFriendsError" class="text-center text-black d-none">
                                                                    <p><b><?php printString("friends.offline.empty"); ?></b></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                                
                                                <!-- Add friends -->
                                                <form id="friendForm" class="mt-3">
                                                    <label for="friendName" class="form-label"><b class="fst-normal"><?php printString("friends.add.title")?></b></label>

                                                    <!-- The name -->
                                                    <div class="row">
                                                        <div class="text-center col-8">
                                                            <input type="input" class="form-control" id="friendName">
                                                        </div>

                                                        <!-- Add button -->
                                                        <div class="col-4">
                                                            <button type="submit" class="btn btn-primary border border-3 border-black w-100"><?php printString("friends.add")?></button>
                                                        </div>
                                                    </div>

                                                    <!-- Error message -->
                                                    <div id="friendAddError" class="mb-4 mx-3 text-center text-warning d-none">
                                                        <!-- Filled in later in case of error -->
                                                    </div>

                                                    <div id="friendAddSuccess" class="text-center d-none">
                                                        <p><b><?php printString("userlist.befriend.success"); ?></b></p>
                                                    </div>
                                                </form>

                                                <!-- Error message -->
                                                <div id="friendError" class="mb-4 mx-3 text-center text-warning d-none">
                                                    <!-- Filled in later in case of error -->
                                                </div>
                                            </div>
                                        </div>
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
                                    
                                    <!-- The Message modal -->
                                    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border border-3 border-white">

                                                <!-- The Modal header -->
                                                <div class="modal-header bg-body-tertiary">
                                                    <h5 class="modal-title fs-5 " id="messageModalLabel"><?php printString("message.to"); ?><span id="messageToName"></span></h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <!-- The Modal body -->
                                                <div class="modal-body">
                                                    <!-- Login form -->
                                                    <form id="messageForm">
                                                        <!-- Filled in by JS -->
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
