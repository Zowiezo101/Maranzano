
                                    <!-- The Online users Tab -->
                                    <div class="tab-pane" id="tabOnline" role="tabpanel">
                                        <?php printHeader("comms.online"); ?>
                                        
                                        <!-- List of online users -->
                                        <div class="mt-3 text-center">
                                            <p id="onlineList">
                                                <!-- Filled in by JS -->
                                            </p>
                                        </div>

                                        <!-- Error message -->
                                        <div id="onlineError" class="error-message d-none">
                                            <!-- Filled in later in case of error -->
                                        </div>
                                    </div>
                                    
                                    <!-- The Friend list Tab -->
                                    <div class="tab-pane" id="tabFriends" role="tabpanel">
                                        <?php printHeader("comms.friends"); ?>

                                        <!-- Card with clipboard image -->
                                        <div class="row mt-2">
                                            <div class="col-lg-10 mx-auto">
                                                
                                                <!-- Card itself -->
                                                <div class="card bg-transparent border-0">
                                                    
                                                    <!-- Clipboard image -->
                                                    <img class="card-img" src="../img/Friends.png"/>

                                                    <!-- Text contents -->
                                                    <div id="friendsCard" class="card-img-overlay p-0">
                                                        <b><?php printString("friends.online"); ?></b>
                                                        <table id="onlineFriends" class="table table-sm table-hover">
                                                            <tbody>
                                                                <!-- Rows are added using JS -->
                                                            </tbody>
                                                        </table>

                                                        <!-- No players in list -->
                                                        <div id="onlineFriendsError" class="warning-message d-none">
                                                            <p class="fw-bold"><?php printString("friends.online.empty"); ?></p>
                                                        </div>

                                                        <br/>

                                                        <b><?php printString("friends.offline"); ?></b>
                                                        <table id="offlineFriends" class="fw-bold table table-hover">
                                                            <tbody>
                                                                <!-- Rows are added using JS -->
                                                            </tbody>
                                                        </table>

                                                        <!-- No players in list -->
                                                        <div id="offlineFriendsError" class="warning-message d-none">
                                                            <p class="fw-bold"><?php printString("friends.offline.empty"); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Add friends -->
                                        <form id="friendForm" class="mt-3">
                                            <label for="friendName" class="form-label fw-bold"><?php printString("friends.add.title")?></label>

                                            <!-- The name -->
                                            <div class="row">
                                                <div class="text-center col-8">
                                                    <input type="input" class="form-control" id="friendName">
                                                </div>

                                                <!-- Add button -->
                                                <div class="col-4">
                                                    <button type="submit" class="btn btn-primary bordered w-100">
                                                        <?php printString("friends.add")?>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Error message -->
                                            <div id="friendAddError" class="error-message d-none">
                                                <!-- Filled in later in case of error -->
                                            </div>

                                            <div id="friendAddSuccess" class="text-center d-none">
                                                <p class="fw-bold"><?php printString("userlist.befriend.success"); ?></p>
                                            </div>
                                        </form>

                                        <!-- Error message -->
                                        <div id="friendError" class="error-message d-none">
                                            <!-- Filled in later in case of error -->
                                        </div>
                                    </div>
                                    
                                    <!-- The Newspaper Tab -->
                                    <div class="tab-pane" id="tabNews" role="tabpanel">
                                        <?php printHeader("comms.news"); ?>
                                    </div>
                                    
                                    <!-- The User list Tab -->
                                    <div class="tab-pane" id="tabUserlist" role="tabpanel">

                                        <!-- Success message -->
                                        <div id="playerSuccess" class="error-message-top d-none">
                                            <!-- Filled in by JS -->
                                        </div>

                                        <!-- Error message -->
                                        <div id="playerError" class="error-message-top d-none">
                                            <!-- Filled in later in case of error -->
                                        </div>

                                        <?php printHeader("comms.userlist"); ?>
                                        <table id="playerlist" class="table table-sm table-striped table-bordered table-hover">
                                            <tbody>
                                                <!-- Following rows are added using JS -->
                                            </tbody>
                                        </table>

                                        <!-- No players in list -->
                                        <div id="playerlistError" class="warning-message d-none">
                                            <p class="fw-bold"><?php printString("userlist.empty"); ?></p>
                                        </div>
                                    </div>
                                    
                                    <!-- The Mailbox Tab -->
                                    <div class="tab-pane" id="tabMail" role="tabpanel">

                                        <!-- Success message -->
                                        <div id="mailSuccess" class="error-message-top d-none">
                                            <!-- Filled in by JS -->
                                        </div>

                                        <!-- Error message -->
                                        <div id="mailError" class="error-message-top d-none">
                                            <!-- Filled in later in case of error -->
                                        </div>

                                        <?php printHeader("comms.mail"); ?>
                                        <table id="maillist" class="align-middle table table-sm table-striped table-bordered table-hover">                                                    
                                            <tbody>
                                                <!-- Following rows are added using JS -->
                                            </tbody>
                                        </table>

                                        <!-- No players in list -->
                                        <div id="maillistError" class="warning-message d-none">
                                            <p class="fw-bold"><?php printString("maillist.empty"); ?></p>
                                        </div>

                                        <div class="row mb-5 justify-content-end">                                                    
                                            <!-- Delete button -->
                                            <div class="col-4 col-lg-3">
                                                <button id="delete" onClick="onClickDelete()" class="btn btn-primary bordered w-100"><?php printString("maillist.delete"); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Message modal -->
                                    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bordered-white">

                                                <!-- The Modal header -->
                                                <div class="modal-header bg-body-tertiary">
                                                    <h5 class="modal-title fs-5 " id="messageModalLabel"><?php printString("message.to"); ?><span id="messageToName"></span></h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <!-- The Modal body -->
                                                <div class="modal-body">
                                                    
                                                    <!-- Login form -->
                                                    <form id="messageForm" data-receiver-id="-999">
                                                        
                                                        <!-- Subject -->
                                                        <div class="mb-3 row">
                                                            <div class="col">
                                                                <input type="input" class="form-control message-field" id="messageSubject" placeholder=<?php printString("message.subject", true)?> required>
                                                            </div>
                                                        </div>

                                                        <!-- Message -->
                                                        <div class="mb-3 row">
                                                            <div class="col">
                                                                <textarea type="input" class="form-control message-field" id="messageBody" placeholder=<?php printString("message.body", true)?> required></textarea>
                                                            </div>
                                                        </div>

                                                        <!-- Send button -->
                                                        <div class="mb-3 row">
                                                            <div class="col d-flex justify-content-end">
                                                                <button type="submit" class="btn btn-primary text-end"><?php printString("message.send"); ?></button>
                                                            </div>
                                                        </div>

                                                        <!-- Error message -->
                                                        <div id="messageError" class="error-message d-none">
                                                            <!-- Filled in later in case of error -->
                                                        </div>
                                                    </form>
                                                    
                                                    <div id="messageSuccess" class="d-none">
                                                        
                                                        <!-- Inform user that the message has been sent -->
                                                        <div class="row text-center">
                                                            <p><?php printString("userlist.message.success"); ?></p>
                                                        </div>

                                                        <!-- Done -->
                                                        <div class="mb-3 mx-3 row">
                                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" ><?php printString("global.done"); ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
