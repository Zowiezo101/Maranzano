<?php

namespace Classes;

use PDO;

// All data per rank
$ranks = [
    1 =>  ["health" => 1000,   "jail" => 15,  "bike" => 2,  "car" => 0,  "store" => 0,  "xp" => 1500],
    2 =>  ["health" => 1300,   "jail" => 18,  "bike" => 7,  "car" => 0,  "store" => 0,  "xp" => 2250],
    3 =>  ["health" => 1700,   "jail" => 21,  "bike" => 12, "car" => 0,  "store" => 0,  "xp" => 3375],
    4 =>  ["health" => 2200,   "jail" => 24,  "bike" => 17, "car" => 0,  "store" => 0,  "xp" => 5065],
    5 =>  ["health" => 2900,   "jail" => 28,  "bike" => 22, "car" => 0,  "store" => 0,  "xp" => 7600],
    6 =>  ["health" => 3750,   "jail" => 33,  "bike" => 27, "car" => 0,  "store" => 0,  "xp" => 11400],
    7 =>  ["health" => 4850,   "jail" => 39,  "bike" => 32, "car" => 5,  "store" => 5,  "xp" => 17100],
    8 =>  ["health" => 6300,   "jail" => 45,  "bike" => 37, "car" => 11, "store" => 11, "xp" => 25650],
    9 =>  ["health" => 8250,   "jail" => 53,  "bike" => 42, "car" => 18, "store" => 18, "xp" => 38450],
    10 => ["health" => 10750,  "jail" => 62,  "bike" => 47, "car" => 24, "store" => 24, "xp" => 57700],
    11 => ["health" => 14000,  "jail" => 73,  "bike" => 52, "car" => 30, "store" => 30, "xp" => 86500],
    12 => ["health" => 18200,  "jail" => 85,  "bike" => 57, "car" => 37, "store" => 37, "xp" => 129750],
    13 => ["health" => 23700,  "jail" => 100, "bike" => 62, "car" => 43, "store" => 43, "xp" => 194600],
    14 => ["health" => 30850,  "jail" => 117, "bike" => 67, "car" => 49, "store" => 49, "xp" => 291950],
    15 => ["health" => 40150,  "jail" => 137, "bike" => 71, "car" => 56, "store" => 56, "xp" => 437900],
    16 => ["health" => 52250,  "jail" => 160, "bike" => 76, "car" => 62, "store" => 62, "xp" => 656850],
    17 => ["health" => 68000,  "jail" => 187, "bike" => 81, "car" => 68, "store" => 68, "xp" => 985300],
    18 => ["health" => 88550,  "jail" => 219, "bike" => 86, "car" => 75, "store" => 75, "xp" => 1477900],
    19 => ["health" => 115250, "jail" => 256, "bike" => 91, "car" => 81, "store" => 81, "xp" => 2216900],
    20 => ["health" => 150000, "jail" => 300, "bike" => 96, "car" => 86, "store" => 86, "xp" => 3325300],
];

class Player {
    // Other classes
    protected $db;
    protected $message;
    
    public function __construct($message = null) {
        
        // For the error messages
        if (isset($message)) {
            $this->setMessage($message);
        } else {
            $this->message = new Message();
        }
        
        // Link the message class for error messages
        $this->db = new Database($this->message);
    }
    
    public function createPlayer($user_id) {
        $conn = $this->db->getConnection();
        
        // The SQL to add a new player for this user
        $sql = "INSERT INTO players (user_id) VALUES (:user_id)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Insert the ID into the parameter array
        $id = $conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue if the player hasn't been created
            $this->message->throwError();
        }
        
        return $id;
    }
    
    public function getPlayer($user_id) {
        $conn = $this->db->getConnection();
        
        // Get the player using the user_id
        $sql = "SELECT * FROM players WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    public function getPlayerInfo($user_id) {
        if (isset($user_id)) {
            try {
                // Get the player that belongs to this user
                $player = $this->getPlayer($user_id);
                
                // Prepare the data and make it human readable
                $data = $this->formatPlayerInfo($player);

                // Prepare a message
                $this->message->setData($data);
            } catch (\Exception $ex) {
                // TODO: Error
                $this->message->setError($ex->getMessage());
            }
        }
    }
    
    public function getPlayerStats() {
        
    }
    
    /**
     * Data conversion functions
     */
    private function formatPlayerInfo($player) {
        $locations = new Location();
        
        $rank = $player["rank"];
        
        $data = [
            "cash" => $this->formatCurrency($player["cash"]),
            "bank" => $this->formatCurrency($player["bank"]),
            "rank" => $rank,
            "progress" => $this->formatProgress($rank, $player["progress"]),
            "family" => $this->formatFamily($player["family_id"]),
            "city" => $locations->getCity($player["location"]),
            "country" => $locations->getCountry($player["location"]),
            "health" => $this->formatHealth($rank, $player["health"]),
            "bullets" => number_format($player["bullets"], 0, ",", "."),
            "shields" => number_format($player["shields"], 0, ",", "."),
            "deceased" => $player["deceased"] == "1",
            "killed_by" => $player["killed_by"]
        ];
        
        return $data;
    }
    
    private function formatCurrency($value) {
        $result = "€".number_format($value, 0, ",", ".");
        return $result;
    }
    
    private function formatProgress($rank, $value) {
        global $ranks;
        
        // Get the maximum xp for this rank needed to go rank up
        $max_xp = $ranks[$rank]["xp"];
        
        // Make sure XP are converted to percentage
        $result = round($value/$max_xp)."%";
        return $result;
    }
    
    private function formatHealth($rank, $value) {
        global $ranks;
        
        // Get the maximum hp for this rank needed to go rank up
        $max_hp = $ranks[$rank]["health"];
        
        // Make sure HP are converted to percentage
        $result = round($value*100/$max_hp)."%";
        return $result;
    }
    
    private function formatFamily($value) {
        $result = "";
        if (isset($value)) {
            $result = $value;
        } else {
            $result = "-None-";
        }
        
        return $result;
    }
    
    /**
     * Setters & Getters
     */
    
    public function setMessage($message) {
        $this->message = $message;
    }
    
    /**
     * Misc
     */
    
    public function sendMessage() {
        // Send a message to the client
        $this->message->sendMessage();
    }
}
