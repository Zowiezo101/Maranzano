<?php

namespace Classes;

class Jail extends Action {
    
    public const ACTION_TABLE = "jail_session";
    
    public function getJailTime($player_id) {
        // TODO:
        return 512;
    }
}
