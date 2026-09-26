<?php

namespace Classes;

use PDO;

class Player {
    // Other classes
    protected $db;
    private $user;
    private $token;
    private $parameters;
    
    // All data per rank
    private const RANKS = [
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
    
    public function __construct() {
        $this->parameters = new Parameters();
        $this->user = new User();
        $this->token = new Token();
        
        // To connect to the database
        $this->db = new Database();
    }
    
    public function route($route, $data) {
        // These actions need to have the cookie checked
        $auth = new Login();
        $auth->route("login_validate", $data);
        
        // Parse the input data
        $this->parameters->setData($data);
        
        $result = null;
        
        switch($route) {
            case "player_info":
                $result = $this->getPlayerInfo();
                break;
            
            case "player_stats":
                $result = $this->getPlayerStats();
                break;
            
            case "player_reset":
                $result = $this->resetPlayer();
                break;
        }
        
        return $result;
    }
    
    /**
     * Availabilty function
     */
    public function isPlayerAvailable($name) {
        $player = $this->retrievePlayerFromName($name);
        
        if (isset($player)) {
            // This email address is not available
            throwError("auth.player.taken", Message::CODE_INVALID);
        }
    }
    
    /**
     * API functions
     */
    
    private function getPlayerInfo() {
        $result = null;
        
        // The username that is given via the cookie
        $user_name = $this->parameters->getUser(from_cookie: true);
        
        // Get the user using the username
        $user = $this->user->getUser(name: $user_name);
        
        if (isset($user)) {
            // Get the user_id
            $user_id = $user["id"];
            
            // Get the player that belongs to this user
            $player = $this->getPlayer($user_id);

            // Prepare the data and make it human readable
            $data = $this->formatPlayerInfo($player);

            // Prepare a message
            $result = $data;
        } else {
            throwError();
        }
        
        return $result;
    }
    
    public function getPlayerStats() {
        $result = null;
        
        // The username that is given via the cookie
        $user_name = $this->parameters->getUser(from_cookie: true);
        
        // Get the user using the username
        $user = $this->user->getUser(name: $user_name);
        
        if (isset($user)) {
        
            // Get the user_id
            $user_id = $user["id"];
            
            // Get the player that belongs to this user
            $player = $this->getPlayer($user_id);
            
            // The player ID
            $id = $player["id"];
            
            // Get the crimes this player commited
            $crimeObj = new Crime();
            $crimes = $crimeObj->getCrimes($id);
            
            $player["bikes"]  = $crimes["bikes"];
            $player["cars"]   = $crimes["cars"];
            $player["stores"] = $crimes["stores"];
            $player["kills"]  = $crimes["kills"];
            
            // Get the Hospital & Jail time for this player
            $jail = new Jail();
            $player["jail"] = $jail->getJailTime($id);
            
            $hospital = new Hospital();
            $player["hospital"] = $hospital->getHospitalTime($id);
                    
            // Get the online friends of this player
            $player["friends"] = $this->getOnlineFriends($id);

            // Prepare the data and make it human readable
            $data = $this->formatPlayerStats($player, $user);

            // Prepare a message
            $result = $data;
        } else {
            throwError();
        }
        
        return $result;        
    }
    
    private function resetPlayer() {
        
        // The username that is given via the cookie
        $user_name = $this->parameters->getUser(from_cookie: true);
        
        // Get the user using the username
        $user = $this->user->getUser(name: $user_name);
        
        if (isset($user)) {
            // Get the user_id
            $user_id = $user["id"];
        
            // Try to get the expected parameters
            $player = $this->parameters->getPlayer();

            // Check if the player name is available
            $this->isPlayerAvailable($player);

            // Create a new player for this user
            $this->createPlayer($user_id, $player);
        } else {
            throwError();
        }
    }
    
    /**
     * Player functions
     */
    
    public function createPlayer($user_id, $name) {
        $conn = $this->db->getConnection();
        
        // The SQL to add a new player for this user
        $sql = "INSERT INTO players (user_id, name) VALUES (:user_id, :name)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":name", $name, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Insert the ID into the parameter array
        $id = $conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue if the player hasn't been created
            throwError();
        }
        
        return $id;
    }
    
    public function updatePlayer($id, $update) {  
        $conn = $this->db->getConnection();
        
        // The values to update for the player
        $update_arr = [];
        foreach ($update as $key => $value) {
            $update_arr[] = "{$key} = :{$key}";
        }
        
        // The SQL for updating the values
        $update_sql = implode(', ', $update_arr);
        
        // Set the SQL
        $sql = "UPDATE players SET {$update_sql} WHERE id = :id";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);  
        
        // Bind the new values as well
        foreach ($update as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Execute the statement
        $stmt->execute();
    }
    
    public function getPlayer($user_id) {
        $conn = $this->db->getConnection();
        
        // Get the player using the user_id
        $sql = "SELECT players.*, killer.name as killed_by, killer.deceased as killer_deceased FROM players LEFT JOIN players as killer ON players.killer_id = killer.id WHERE players.user_id = :user_id ORDER BY players.created_at DESC LIMIT 1";

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
    
    private function retrievePlayerFromName($name) {
        $conn = $this->db->getConnection();
        
        // Get the player using the user_id
        $sql = "SELECT id, name FROM players WHERE name = :name";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    /**
     * Player properties
     */
    
    private function getOnlineFriends() {
        // TODO:
        return 67;
    }
    
    /**
     * Data conversion functions
     */
    private function formatPlayerInfo($player) {
        $locations = new Location();
        
        $rank = $player["rank"];
        
        $data = [
            "name" => $player["name"],
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
            "killed_by" => $player["killed_by"],
            "killer_deceased" => $player["killer_deceased"] == "1"
        ];
        
        return $data;
    }
    
    private function formatPlayerStats($player, $user) {
        $rank = $player["rank"];
        
        $data = [
            "u-name" => $user["name"],
            "name" => $player["name"],
            "prank" => $rank,
            "created" => $this->formatTime($user["created_at"]),
            
            // This information is from other tables
            "friends" => $player["friends"],
            "bikes" => $player["bikes"],
            "cars" => $player["cars"],
            "stores" => $player["stores"],
            "kills" => $player["kills"],
            "jail" => $player["jail"],
            "hospital" => $player["hospital"],
        ];
        
        return $data;
    }
    
    private function formatCurrency($value) {
        $result = "€".number_format($value, 0, ",", ".");
        return $result;
    }
    
    private function formatProgress($rank, $value) {
        
        // Get the maximum xp for this rank needed to go rank up
        $max_xp = self::RANKS[$rank]["xp"];
        
        // Make sure XP are converted to percentage
        $result = round($value/$max_xp)."%";
        return $result;
    }
    
    private function formatHealth($rank, $value) {
        
        // Get the maximum hp for this rank needed to go rank up
        $max_hp = self::RANKS[$rank]["health"];
        
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
    
    private function formatTime($value) {   
        // Convert the string to a timestamp     
        $time = strtotime($value);

        // Format the timestamp
        $result = date("d-m-Y", $time);
        
        return $result;
    }
}
