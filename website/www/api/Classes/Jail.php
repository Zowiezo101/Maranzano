<?php

namespace Classes;

use PDO;

class Jail extends Action {
    
    public const ACTION_COST = 500;
    public const ACTION_TABLE = "jail_session";
    
    public function route($route, $data) {
        parent::route($route, $data);
        
        $result = null;
        
        switch($route) {
            case "jail_city":
                $result = $this->getCityInmates();
                break;
            case "jail_bail":
                $result = $this->payPlayerBail();
                break;
            case "jail_bust":
                $result = $this->bustPlayerOut();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function getCityInmates() {
        $conn = $this->conn;
        
        // The table
        $table = self::ACTION_TABLE;
        
        // The player data
        $player = $this->player_data;
        
        // Retrieve the token from the token table
        $sql = "SELECT players.id, players.name, players.rank, TIMESTAMPDIFF(SECOND, CURRENT_TIMESTAMP, jail_session.expires_at) as time FROM {$table} "
                . "JOIN players on jail_session.player_id = players.id "
                . "WHERE jail_session.location_id = :location_id AND expires_at >= UTC_TIMESTAMP()";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":location_id", $player["location_id"], PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getAllResults($stmt);
        
        return $this->formatResults($result);
    }
    
    private function payPlayerBail() {
        $inmate_id = $this->parameters->getId();

        // Get inmate rank
        $inmate = $this->player->retrievePlayerFromId($inmate_id);
        
        // Get bail price
        $bail = $inmate["rank"] * self::ACTION_COST;
        
        // Get the player
        $player = $this->player_data;
        
        // Check current player cash        
        $this->enoughFunds($player["cash"], $bail, "jail.broke");
        
        // Update the jail cooldown to an expired state
        $this->updateJailCooldown($inmate_id, -1);

        // Update the player cash
        $update = [
            "cash" => $player["cash"] - $bail
        ];
        $this->player->updatePlayer($player["id"], $update);

        return;
    }
    
    private function bustPlayerOut() {
        $inmate_id = $this->parameters->getId();
        
        // The current player
        $player = $this->player_data;
        
        // The chance to succeed
        $rate = $this->player->getPlayerSuccessBikeByRank($player["rank"]);
        
        // The RNG to create a chance to succeed or not
        $gamble = mt_rand(0, 10000) / 100;
        
        // Has the player succeeded or not?
        $success = ($rate >= $gamble);
        
        if ($success) {
            // Update the jail cooldown to an expired state
            $this->updateJailCooldown($inmate_id, -1);
        } else {
            // If the player failed, they'll be sent to jail
            $this->sendToJail($player);
        }
        
        return $success;
    }
    
    /**
     * Other functions
     */
    
    public function getJailTime($player_id) {
        $conn = $this->conn;
        
        $sql = "SELECT SUM(TIMESTAMPDIFF(SECOND, created_at, expires_at)) AS time FROM " . self::ACTION_TABLE . " "
                . "WHERE player_id = :player_id";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        // The time in seconds
        $time_s = $result["time"]; 
        
        // The time in hours & minutes
        $time_m = round($time_s / 60);
        $time_h = round($time_m / 60);
        
        // Get the correct unit
        return ($time_h > 1 ? $time_h . " hours" : ($time_m > 1 ? $time_m . " minutes" : $time_s . " seconds"));
    }
    
    public function sendToJail($player) {
        // Player data
        $id = $player["id"];
        $rank = $player["rank"];
        $location_id = $player["location_id"];
        
        // The jail time for this rank
        $cooldown = $this->player->getRankJailTime($rank);
        
        // Set the cooldown
        $this->setJailCooldown($id, $location_id, $cooldown);
    }
    
    private function setJailCooldown($player_id, $location_id, $cooldown) {
        $conn = $this->conn;
        
        // The table
        $table = self::ACTION_TABLE;
        
        // Create a new cooldown
        $sql = "INSERT INTO {$table} (player_id, location_id, expires_at) "
                . "VALUES (:player_id, :location_id, :expires_at)";
        
        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Generate the expire date for the cooldown
        $expiry_date = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                            ->modify('+' . $cooldown . 'seconds')
                            ->format('Y-m-d H:i:s');

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":location_id", $location_id, PDO::PARAM_INT);
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
    
    private function updateJailCooldown($inmate_id, $cooldown) {
        $conn = $this->conn;
        
        // The table
        $table = self::ACTION_TABLE;
        
        // Get the cooldown we're trying to pay the bail for
        $cooldown_id = $this->hasCooldown($inmate_id, $table, 0);
        
        // Create a new cooldown
        $sql = "UPDATE {$table} SET expires_at = :expires_at "
                . "WHERE id = :id";
        
        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Generate the expire date for the cooldown
        $expiry_date = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
                            ->modify('+' . $cooldown . 'seconds')
                            ->format('Y-m-d H:i:s');

        // Bind the parameter
        $stmt->bindValue(":expires_at", $expiry_date, PDO::PARAM_STR);
        $stmt->bindValue(":id", $cooldown_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();
    }
    
    private function formatResults($results) {
        
        // The formatted array
        $formatted_results = [];
        
        // The chance to bust someone out is dependent on your own rank (same as bike chance)
        $chance = $this->player->getPlayerSuccessBike();
        $chance1 = getString("jail.info.bust_out");
        $chance2 = str_replace("[chance]", $chance, $chance1);
        
        // Format the results of each row
        if(isset($results)) {
            foreach ($results as $row) {
                // Time in seconds
                $time_s = $row["time"];

                // The cooldown in hours
                $time_m = round($time_s / 60);
                $time_h = round($time_m / 60);
                
                // Bail depends on the rank of the player that will be bust out
                $bail = $row["rank"]*500;
                $bail1 = getString("jail.info.pay_bail");
                $bail2 = str_replace("[bail]", $bail, $bail1);

                $formatted_results[] = [
                    "id"   => $row["id"],
                    "name" => $row["name"],
                    "time" => ($time_h > 1 ? $time_h . " hours" : ($time_m > 1 ? $time_m . " minutes" : $time_s . " seconds")),
                    "bail" => $bail2,
                    "chance" => $chance2
                ];
            }
        }
        
        return $formatted_results;
    }
}
