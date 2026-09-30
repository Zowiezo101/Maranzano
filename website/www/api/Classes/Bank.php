<?php

namespace Classes;

use PDO;

class Bank extends Action {
    
    private const ACTION_TABLE = "bank_session";
    private const ACTION_COOLDOWN = 24*60;
    private const ACTION_MIN = 1;
    private const ACTION_MAX = 10000;
    
    public function route($route, $data) {
        parent::route($route, $data);
        
        $result = null;
        
        switch($route) {
            case "bank_deposit":
                $result = $this->depositMoney();
                break;
            
            case "bank_withdraw":
                $result = $this->withdrawMoney();
                break;
            
            case "bank_send":
                $result = $this->sendMoney();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function depositMoney() {
        // The current player
        $player = $this->player_data;
        
        // The amount the player wants to deposit
        $amount = $this->parameters->getAmount();
        
        $this->isValidAmount($amount, min:self::ACTION_MIN);

        // Make sure the player has enough money to deposit this amount
        $this->enoughFunds($player["cash"], $amount, "bank.deposit.broke");

        // Update the player bank and cash
        $update = [
            "bank" => $player["bank"] + $amount,
            "cash" => $player["cash"] - $amount
        ];
        $this->player->updatePlayer($player["id"], $update);
    }
    
    private function withdrawMoney() {
        // The current player
        $player = $this->player_data;
        
        // The amount the player wants to withdraw
        $amount = $this->parameters->getAmount();
        
        $this->isValidAmount($amount, min:self::ACTION_MIN);

        // Make sure the player has enough balance to withdraw this amount
        $this->enoughFunds($player["bank"], $amount, "bank.withdraw.broke");

        // Update the player bank and cash
        $update = [
            "bank" => $player["bank"] - $amount,
            "cash" => $player["cash"] + $amount
        ];
        $this->player->updatePlayer($player["id"], $update);
    }
    
    private function sendMoney() {
        // The current player
        $player = $this->player_data;
        
        // The amount the player wants to withdraw
        $amount = $this->parameters->getAmount();
        
        // The friend this player wants to send the money to
        $friend_id = $this->parameters->getId();
        
        $this->isValidAmount($amount, min:self::ACTION_MIN, max:self::ACTION_MAX);

        // Make sure the player has enough balance to send this amount
        $this->enoughFunds($player["bank"], $amount, "bank.send.broke");
        
        // Check this person is actually a friend of the player
        $friend = $this->player->retrieveFriendFromId($player["id"], $friend_id);
        
        // Has this friend already received money from this player?
        $this->hasFriendCooldown($player["id"], $friend_id, 
                self::ACTION_TABLE, 
                self::ACTION_COOLDOWN, 
                "bank.send.cooldown");
        
        // Set a cooldown in the bank session table
        $this->setFriendCooldown($player["id"], $friend_id, 
                self::ACTION_TABLE, 
                self::ACTION_COOLDOWN);

        // Update the player cash
        $update_player = [
            "cash" => $player["bank"] - $amount
        ];
        $this->player->updatePlayer($player["id"], $update_player);
        
        // And the friend cash
        $update_friend = [
            "cash" => $friend["bank"] + $amount
        ];
        $this->player->updatePlayer($friend["id"], $update_friend);
    }
    
    protected function hasFriendCooldown($player_id, $friend_id, $table, $cooldown, $error) {
        $conn = $this->db->getConnection();
        
        // Retrieve the token from the token table
        $sql = "SELECT * FROM {$table} "
                . "WHERE player_id = :player_id AND friend_id = :friend_id AND expires_at >= UTC_TIMESTAMP() LIMIT 1";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);  
        $stmt->bindValue(":friend_id", $friend_id, PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        // Return the cooldown to the user
        $this->ifCooldown($result, $cooldown, $error);
        
        return $result;
    }
    
    protected function setFriendCooldown($player_id, $friend_id, $table, $cooldown) {
        $conn = $this->db->getConnection();
        
        // Create a new token
        $sql = "INSERT INTO {$table} (player_id, friend_id, expires_at) "
                . "VALUES (:player_id, :friend_id, :expires_at)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Genereate the expire date for the verification
        $expiry_date = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                            ->modify('+' . $cooldown . ' minutes')
                            ->format('Y-m-d H:i:s');

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":friend_id", $friend_id, PDO::PARAM_INT);
        $stmt->bindValue(":expires_at", $expiry_date, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Check that the cooldown has been properly created
        $id = $conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue is the cooldown hasn't been created
            throwError();
        }
    }
}
