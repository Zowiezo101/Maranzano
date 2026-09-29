<?php

namespace Classes;

class Shop extends Action {
    
    private const ACTION_COST = 25;
    private const ACTION_PACK = 50;
    private const ACTION_TABLE = "shop_session";
    private const ACTION_COOLDOWN = 120;
    private const ACTION_MIN = 1;
    private const ACTION_MAX = 20;
    
    public function route($route, $data) {
        parent::route($route, $data);
        
        $result = null;
        
        switch($route) {
            case "shop_offensive":
                $result = $this->buyBullets();
                break;
            
            case "shop_defensive":
                $result = $this->swapBullets();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function buyBullets() {
        // The current player
        $player = $this->player_data;
        
        // The amount of packets the player wants to buy
        $amount = $this->parameters->getAmount();
        
        $this->isValidAmount($amount, min:self::ACTION_MIN, max:self::ACTION_MAX);

        // Make sure the player has enough cash to pay for their bullets
        $this->enoughFunds($player["cash"], $amount * self::ACTION_COST, "bullet.broke");

        // Make sure the player isn't on a cooldown
        $this->hasCooldown($player["id"], 
                self::ACTION_TABLE, 
                self::ACTION_COOLDOWN,
                "bullet.cooldown");

        // Set a cooldown in the shop session table
        $this->setCooldown($player["id"], self::ACTION_TABLE, self::ACTION_COOLDOWN);

        // Update the player bullets and cash
        $update = [
            "bullets" => $player["bullets"] + $amount * self::ACTION_PACK,
            "cash" => $player["cash"] - $amount * self::ACTION_COST
        ];
        $this->player->updatePlayer($player["id"], $update);
    }
    
    private function swapBullets() {
        // The current player
        $player = $this->player_data;
        
        // The amount of packets the player wants to swap
        $amount = $this->parameters->getAmount();
        
        $this->isValidAmount($amount, min:self::ACTION_MIN);
        
        // Make sure the player has enough bullets to swap this amount
        $this->enoughFunds($player["bullets"], $amount * self::ACTION_PACK, "bullet.short");

        // Update the player bullets
        $update = [
            "bullets" => $player["bullets"] - $amount * self::ACTION_PACK,
            "shields" => $player["shields"] + $amount * self::ACTION_PACK
        ];
        $this->player->updatePlayer($player["id"], $update);
    }
}
