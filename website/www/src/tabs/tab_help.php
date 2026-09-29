
                                    <!-- The Contact Tab -->
                                    <div class="tab-pane" id="tabContact" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">
                                                <!-- The contact header -->
                                                <h3 class="mt-3 mt-lg-5 mb-0 text-center fst-normal fst-bold bg-body-tertiary border border-3 border-black"><?php printString("help.contact"); ?></h3>
                                                
                                                <!-- The contact body -->
                                                
                                                <!-- Email -->
                                                <p class="mt-3 mb-0"><b class="fst-normal text-black"><?php printString("contact.email"); ?></b></p>
                                                <p class="mb-0"><b class="text-white"><?php echo $email_user; ?></b></p>
                                                <p class="mt-0"><b class="text-black-50"><?php printString("contact.email.info"); ?></b></p>
                                                
                                                <!-- Donations -->
                                                <p class="mt-3 mb-0"><b class="fst-normal text-black"><?php printString("contact.donations"); ?></b></p>
                                                <p class="mb-0"><b class="text-white"><?php printString("contact.donations.link"); ?></b></p>
                                                <p class="mt-0"><b class="text-black-50"><?php printString("contact.donations.info"); ?></b></p>
                                                
                                                <!-- Discord -->
                                                <p class="mt-3 mb-0"><b class="fst-normal text-black"><?php printString("contact.discord"); ?></b></p>
                                                <p class="mt-0"><b class="text-white"><?php printString("contact.discord.link"); ?></b></p>
                                                
                                                <!-- Socials -->
                                                <p class="mt-3 mb-0"><b class="fst-normal text-black"><?php printString("contact.socials"); ?></b></p>
                                                <p class="mt-0"><b class="fst-normal text-white"><?php printString("contact.socials.links"); ?></b></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Rules Tab -->
                                    <div class="tab-pane" id="tabRules" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">
                                                <!-- The rules header -->
                                                <h3 class="mt-3 mt-lg-5 mb-0 text-center fst-normal fst-bold bg-body-tertiary border border-3 border-black"><?php printString("help.rules"); ?></h3>
                                                
                                                <!-- The rules body -->
                                                
                                                <!-- Title -->
                                                <h4 class="mt-3"><b class="fst-normal"><?php printString("rules.title"); ?></b></h4>
                                                
                                                <!-- Rules -->
                                                <p class="mt-3 mb-0"><b class="fst-normal text-black"><span class="text-white">1. </span><?php printString("rules.rule1"); ?></b></p>
                                                <p class="mt-3 mb-0"><b class="fst-normal text-black"><span class="text-white">2. </span><?php printString("rules.rule2"); ?></b></p>
                                                <p class="mt-3 mb-0"><b class="fst-normal text-black"><span class="text-white">3. </span><?php printString("rules.rule3"); ?></b></p>
                                                <p class="mt-3 mb-0"><b class="fst-normal text-black"><span class="text-white">4. </span><?php printString("rules.rule4"); ?></b></p>
                                                <p class="mt-3 mb-0"><b class="fst-normal text-black"><span class="text-white">5. </span><?php printString("rules.rule5"); ?></b></p>
                                                <p class="mt-3 mb-0"><b class="fst-normal text-black"><span class="text-white">6. </span><?php printString("rules.rule6"); ?></b></p>
                                                
                                                <!-- Last but not least -->
                                                <p class="mt-3"><b class="fst-normal text-black"><?php printString("rules.havefun"); ?></b></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Info Tab -->
                                    <div class="tab-pane" id="tabInfo" role="tabpanel">
                                        <div class="row">
                                            <div class="col p-0">
                                                <!-- The info header -->
                                                <h3 class="mt-3 mt-lg-5 mb-0 text-center fst-normal fst-bold bg-body-tertiary border border-3 border-black"><?php printString("help.info"); ?></h3>
                                                
                                                <!-- The info body -->
                                                
                                                <!-- Game info -->
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("main.home"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.home"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("main.travel"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.travel"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("main.jail"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.jail"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("main.hospital"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.hospital"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("business.bank"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.bank"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("business.bullet"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.bullet"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("business.garage"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.garage"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("business.family"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.family"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("business.manage"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.manage"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("crimes.bike"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.bike"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("crimes.car"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.car"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("crimes.store"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.store"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("crimes.kill"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.kill"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("casino.roulette"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.roulette"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("casino.scratch"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.scratch"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("comms.online"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.online"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("comms.friends"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.friends"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("comms.news"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.news"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("comms.userlist"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.userlist"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("comms.mail"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.mail"); ?></b></p>
                                                <p class="mt-3 mb-0 text-center"><b class="fst-normal">-<?php printString("help.settings"); ?>-</b></p>
                                                <p class="mt-0"><b class="fst-normal text-black"><?php printString("game.info.settings"); ?></b></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- The Settings Tab -->
                                    <div class="tab-pane" id="tabSettings" role="tabpanel">
                                        Settings
                                    </div>
