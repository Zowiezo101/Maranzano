<?php

namespace Classes;

use PDO;

class Jail {
    // Other classes
    protected $db;
    private $user;
    private $player;
    private $parameters;
    
    // The player
    public $player_data;
    
    // Constants
    public const ACTION_TABLE = "jail_session";
    
    public function __construct() {
        $this->parameters = new Parameters();
        $this->user = new User();
        $this->player = new Player();
        
        // To connect to the database
        $this->db = new Database();
    }
    
    public function route($route, $data) {
        // These actions need to have the cookie checked
        $auth = new Login();
        $auth->route("login_validate", $data);
        
        // Parse the input data
        $this->parameters->setData($data);

        // Get the player that belongs to this user
        $this->player_data = $this->player->getPlayerFromCookieData();
        
        $result = null;
        
        switch($route) {
            case "jail_city":
                $result = $this->getCityInmates();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function getCityInmates() {
        $conn = $this->db->getConnection();
        
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
    
    /**
     * Other functions
     */
    
    public function getJailTime($player_id) {
        
        $conn = $this->db->getConnection();
        
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
        $conn = $this->db->getConnection();
        
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
