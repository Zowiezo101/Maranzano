<?php

namespace Classes;

class Travel extends Action {
    
    private const ACTION_COST = 3000;
    private const ACTION_TABLE = "travel_session";
    private const ACTION_COOLDOWN = 30;
    
    public function route($route, $data) {
        parent::route($route, $data);
        
        $result = null;
        
        switch($route) {
            case "travel_player":
                $result = $this->travelPlayer();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function travelPlayer() {
        // The current player
        $player = $this->player_data;
        
        // The location ID
        $location_id = $this->parameters->getLocation();

        // Make sure the player has enough cash to pay for their ticket
        $this->enoughFunds($player["cash"], self::ACTION_COST);

        // Make sure the player isn't on a cooldown
        $this->hasCooldown($player["id"], self::ACTION_TABLE, self::ACTION_COOLDOWN);

        // Make sure we're not traveling to the city we're already in
        $this->differentLocation($player["location"], $location_id);

        // Set a cooldown in the travel session tablet
        $this->setCooldown($player["id"], self::ACTION_TABLE, self::ACTION_COOLDOWN);

        // Update the player location and cash
        $update = [
            "location" => $location_id,
            "cash" => $player["cash"] - 3000
        ];
        $this->player->updatePlayer($player["id"], $update);
    }
    
    private function differentLocation($current_location, $new_location) {
        if ($current_location === $new_location) {
            throwError("travel.current");
        }
    }
}
