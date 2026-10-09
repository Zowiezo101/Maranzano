<?php

namespace Classes;

use PDO;

class Player {
    // Other classes
    private $user;
    private $token;
    private $parameters;
    private $mailbox;
    
    // The database connection
    protected $conn;
    
    // All data per rank
    private const RANKS = [
        1 =>  ["health" => 1000,   "jail" => 15,  "bike" => 2,  "car" => 0,  "store" => 0,  "xp" => 1500],
        2 =>  ["health" => 1300,   "jail" => 18,  "bike" => 7,  "car" => 0,  "store" => 0,  "xp" => 2250],
        3 =>  ["health" => 1700,   "jail" => 21,  "bike" => 12, "car" => 0,  "store" => 0,  "xp" => 3375],
        4 =>  ["health" => 2200,   "jail" => 24,  "bike" => 17, "car" => 0,  "store" => 0,  "xp" => 5065],
        5 =>  ["health" => 2900,   "jail" => 28,  "bike" => 22, "car" => 0,  "store" => 0,  "xp" => 7600],
        6 =>  ["health" => 3750,   "jail" => 33,  "bike" => 27, "car" => 0,  "store" => 0,  "xp" => 11400],
        7 =>  ["health" => 4850,   "jail" => 39,  "bike" => 32, "car" => 5,  "store" => 0,  "xp" => 17100],
        8 =>  ["health" => 6300,   "jail" => 45,  "bike" => 37, "car" => 11, "store" => 0, "xp" => 25650],
        9 =>  ["health" => 8250,   "jail" => 53,  "bike" => 42, "car" => 18, "store" => 5, "xp" => 38450],
        10 => ["health" => 10750,  "jail" => 62,  "bike" => 47, "car" => 24, "store" => 12, "xp" => 57700],
        11 => ["health" => 14000,  "jail" => 73,  "bike" => 52, "car" => 30, "store" => 20, "xp" => 86500],
        12 => ["health" => 18200,  "jail" => 85,  "bike" => 57, "car" => 37, "store" => 27, "xp" => 129750],
        13 => ["health" => 23700,  "jail" => 100, "bike" => 62, "car" => 43, "store" => 34, "xp" => 194600],
        14 => ["health" => 30850,  "jail" => 117, "bike" => 67, "car" => 49, "store" => 42, "xp" => 291950],
        15 => ["health" => 40150,  "jail" => 137, "bike" => 71, "car" => 56, "store" => 49, "xp" => 437900],
        16 => ["health" => 52250,  "jail" => 160, "bike" => 76, "car" => 62, "store" => 57, "xp" => 656850],
        17 => ["health" => 68000,  "jail" => 187, "bike" => 81, "car" => 68, "store" => 64, "xp" => 985300],
        18 => ["health" => 88550,  "jail" => 219, "bike" => 86, "car" => 75, "store" => 71, "xp" => 1477900],
        19 => ["health" => 115250, "jail" => 256, "bike" => 91, "car" => 81, "store" => 79, "xp" => 2216900],
        20 => ["health" => 150000, "jail" => 300, "bike" => 96, "car" => 86, "store" => 86, "xp" => 3325300],
    ];
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->parameters = new Parameters();
        $this->user = new User($conn);
        $this->token = new Token($conn);
        $this->mailbox = new Mailbox($conn);
    }
    
    public function route($route, $data) {
        // These actions need to have the cookie checked
        $auth = new Login($this->conn);
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
            
            case "player_friends":
                $result = $this->getPlayerFriends();
                break;
            
            case "player_friend_request":
                $result = $this->friendRequest();
                break;
            
            case "player_chance_bike":
                $result = $this->getPlayerSuccessBike();
                break;
            
            case "player_chance_car":
                $result = $this->getPlayerSuccessCar();
                break;
            
            case "player_chance_store":
                $result = $this->getPlayerSuccessStore();
                break;
            
            case "player_all":
                $result = $this->getAllPlayers();
                break;
            
            case "player_online":
                $result = $this->getOnlinePlayers();
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
        
        $user_id = $this->getUserId();

        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);

        // Prepare the data and make it human readable
        $data = $this->formatPlayerInfo($player);

        // Prepare a message
        $result = $data;
        
        return $result;
    }
    
    public function getPlayerStats() {
        
        $user = $this->getUser();
        
        // The user_id
        $user_id = $user["id"];
            
        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);

        // The player ID
        $id = $player["id"];

        // Get the crimes this player commited
        $crimeObj = new Crime($this->conn);
        $crimes = $crimeObj->getCrimes($id);

        $player["bikes"]  = $crimes["bikes"];
        $player["cars"]   = $crimes["cars"];
        $player["stores"] = $crimes["stores"];
        $player["kills"]  = $crimes["kills"];

        // Get the Hospital & Jail time for this player
        $jail = new Jail($this->conn);
        $player["jail"] = $jail->getJailTime($id);

        $hospital = new Hospital($this->conn);
        $player["hospital"] = $hospital->getHospitalTime($id);

        // Get the online friends of this player
        $player["friends"] = $this->getOnlineFriends($id);

        // Prepare the data and make it human readable
        $data = $this->formatPlayerStats($player, $user);

        // Prepare a message
        $result = $data;
        
        return $result;        
    }
    
    private function resetPlayer() {
        
        $user_id = $this->getUserId();
        
        // Try to get the expected parameters
        $player = $this->parameters->getPlayer();

        // Check if the player name is available
        $this->isPlayerAvailable($player);

        // Create a new player for this user
        $this->createPlayer($user_id, $player);
    }
    
    private function getPlayerFriends() {
        
        $user_id = $this->getUserId();
            
        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);

        // The player ID
        $id = $player["id"];
        
        // Get the friends of this player
        $friends = $this->getFriends($id);
        
        return $friends;
    }
    
    private function friendRequest() {
        
        $user_id = $this->getUserId();

        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);
        
        // Get the player_id this player wants to befriend
        $friend_id = $this->parameters->getId();

        // Get the possibly future friend data
        $friend = $this->retrievePlayerFromId($friend_id);
        
        if (!isset($friend)) {
            // Do NOT continue if this person can't be found
            throwError("friends.player_not_found");
        }
        
        if ($friend["id"] === $player["id"]) {
            throwError("friends.self");
        }
        
        if ($friend["deceased"] !== 0) {
            // Also do NOT continue if this player is deceased
            throwError("friends.deceased");
        }
        
        // Add this unconfirmed friend to the players friends list
        $this->handleFriendRequest($player["id"], $friend["id"]);
        
        return getString("userlist.befriend.success");
    }
    
    public function getPlayerSuccessBike() {
        
        $user_id = $this->getUserId();

        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);
        
        // Get the current rank
        return $this->getPlayerSuccessBikeByRank($player["rank"]);
    }
    
    public function getPlayerSuccessCar() {
        
        $user_id = $this->getUserId();

        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);
        
        // Get the current rank
        return $this->getPlayerSuccessCarByRank($player["rank"]);
    }
    
    public function getPlayerSuccessStore() {
        
        $user_id = $this->getUserId();

        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);
        
        // Get the current rank
        return $this->getPlayerSuccessStoreByRank($player["rank"]);
    }
    
    public function getPlayerSuccessBikeByRank($rank) {
        // Get the current rank data
        $rank_data = self::RANKS[$rank];
        
        return $rank_data["bike"];
    }
    
    public function getPlayerSuccessCarByRank($rank) {
        // Get the current rank data
        $rank_data = self::RANKS[$rank];
        
        return $rank_data["car"];
    }
    
    public function getPlayerSuccessStoreByRank($rank) {
        // Get the current rank data
        $rank_data = self::RANKS[$rank];
        
        return $rank_data["store"];
    }
    
    private function getAllPlayers() {
        $conn = $this->conn;
        
        // Get all the players in the database
        $sql = "SELECT id, name, players.rank, deceased FROM players ORDER BY created_at DESC";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $results = getAllResults($stmt);
        
        // Send the ids, names, ranks and deceased state back
        return $this->formatAllPlayers($results);
    }
    
    private function getOnlinePlayers() {
        
        $user_id = $this->getUserId();

        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);
        
        // The database connection
        $conn = $this->conn;
        
        // Get all the online players in the database
        $sql = "SELECT DISTINCT players.id, players.name, friends.is_confirmed, players.last_active FROM players "
                . "LEFT JOIN friends ON friends.player_id = players.id AND friends.friend_id = :player_id "
                . "WHERE players.last_active >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ORDER BY players.last_active DESC";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player["id"], PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $results = getAllResults($stmt);
        
        // Send the ids, names, ranks and deceased state back
        return $results;
    }
    
    /**
     * Player functions
     */
    
    public function createPlayer($user_id, $name) {
        $conn = $this->conn;
        
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
        $conn = $this->conn;
        
        // The values to update for the player
        $update_arr = [];
        foreach ($update as $key => $value) {
            $update_arr[] = "players.{$key} = :{$key}";
        }
        
        // The SQL for updating the values
        $update_sql = implode(', ', $update_arr);
        
        // Set the SQL
        $sql = "UPDATE players SET {$update_sql} WHERE players.id = :id";
    
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
        $conn = $this->conn;
        
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
    
    public function retrievePlayerFromId($id) {
        $conn = $this->conn;
        
        // Get the player using the user_id
        $sql = "SELECT * FROM players WHERE id = :id";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    private function retrievePlayerFromName($name) {
        $conn = $this->conn;
        
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
    
    public function retrieveFriendFromId($id, $friend_id) {
        $conn = $this->conn;
        
        // Get the player using the user_id
        $sql = "SELECT players.* FROM friends
                JOIN players ON friends.friend_id = players.id
                WHERE friends.player_id = :id AND players.id = :friend_id AND is_confirmed = 1";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":id",        $id,        PDO::PARAM_INT);
        $stmt->bindValue(":friend_id", $friend_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    private function retrieveRecordFromFriendlist($player_id, $friend_id) {
        $conn = $this->conn;
        
        // Get the player using the user_id
        $sql = "SELECT player_id, friend_id, is_confirmed FROM friends
                WHERE player_id = :player_id AND friend_id = :friend_id";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":friend_id", $friend_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    /**
     * Useful functions for outside (or inside) use
     */
    
    public function getUser() {
        $parameters = new Parameters();
        
        $parameters->getData();
        
        // The username that is given via the cookie
        $user_name = $parameters->getUser(from_cookie: true);
        
        // Get the user using the username
        $user = $this->user->getUser(name: $user_name);
        
        if (!isset($user)) {
            throwError();
        }
        
        return $user;
    }
    
    public function getUserId() {
        
        $user = $this->getUser();
        
        // Get the user_id
        $user_id = $user["id"];
        
        return $user_id;
    }
    
    public function getPlayerFromCookieData() {
        
        $user_id = $this->getUserId();
        
        return $this->getPlayer($user_id);
    }
    
    private function getFriends($player_id) {
        $conn = $this->conn;
        
        // Get the list of friends using the player_id
        $sql = "SELECT players.id, players.name FROM friends
                    JOIN players ON friends.friend_id = players.id
                    WHERE player_id = :player_id AND is_confirmed = 1";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getAllResults($stmt);
        
        return $result;
    }
    
    private function handleFriendRequest($player_id, $friend_id) {
        
        // Checking for active records
        $player_record = $this->retrieveRecordFromFriendlist($player_id, $friend_id);
        $friend_record = $this->retrieveRecordFromFriendlist($friend_id, $player_id);
        
        if (isset($player_record)) {
            // This player has already tried to befriend the other player
            // No need for action, just resend the friend-request message again
            
            if ($player_record["is_confirmed"] === 1) {
                // Both players have already confirmed their friendship
                throwError("friends.already_friends");
            }
        
            // Send message to the mailbox of the other player
            $player = $this->retrievePlayerFromId($player_id);
            $this->mailbox->sendFriendRequest($player["name"], $friend_id);
        } else {
            $this->addFriend($player_id, $friend_id);
        
            // Send message to the mailbox of the other player
            $player = $this->retrievePlayerFromId($player_id);
            $this->mailbox->sendFriendRequest($player["name"], $friend_id);
        }
        
        if (isset($friend_record) && ($friend_record["is_confirmed"] === 0)) {
            // The other player wants to be friends, we only
            // have to do an update to confirm the friendship
            $this->updateFriend($player_id, $friend_id);
            $this->updateFriend($friend_id, $player_id);
        }
    }
        
        
    private function addFriend($player_id, $friend_id) {
        // The connection
        $conn = $this->conn;
        
        // Get the list of friends using the player_id
        $sql = "INSERT INTO friends (player_id, friend_id) VALUES (:player_id, :friend_id)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":friend_id", $friend_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    private function updateFriend($player_id, $friend_id) {
        // The connection
        $conn = $this->conn;
        
        // Get the list of friends using the player_id
        $sql = "UPDATE friends SET is_confirmed = 1 WHERE player_id = :player_id AND friend_id = :friend_id";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":friend_id", $friend_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getResults($stmt);
        
        return $result;
    }
    
    public function getOnlineCount() {
        
        $conn = $this->conn;
        
        // Get all the players in the database
        $sql = "SELECT * FROM players WHERE last_active >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = $stmt->rowCount();
        
        // Send the ids, names, ranks and deceased state back
        return $result;
    }
    
    private function getOnlineFriends($player_id) {
        $conn = $this->conn;
        
        // Get all the players in the database
        $sql = "SELECT * FROM players "
                . "JOIN friends on friends.friend_id = players.id "
                . "WHERE friends.player_id = :player_id AND friends.is_confirmed = 1 AND players.last_active >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = $stmt->rowCount();
        
        // Send the ids, names, ranks and deceased state back
        return $result;
    }
    
    /**
     * Data conversion functions
     */
    
    private function formatPlayerInfo($player) {
        $locations = new Location($this->conn);
        
        $rank = $player["rank"];
        
        $data = [
            "name" => $player["name"],
            "cash" => $this->formatCurrency($player["cash"]),
            "bank" => $this->formatCurrency($player["bank"]),
            "rank" => $rank,
            "progress" => $this->formatProgress($rank, $player["progress"]),
            "family" => $this->formatFamily($player["family_id"]),
            "city" => $locations->getCity($player["location_id"]),
            "country" => $locations->getCountry($player["location_id"]),
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
        $data = [
            "u-name" => $user["name"],
            "name" => $player["name"],
            "prank" => $player["rank"],
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
    
    private function formatAllPlayers($data) {
        $formatted_data = [];
        
        foreach ($data as $row) {
            $formatted_data[] = [
                "id" => $row["id"],
                "name" => $row["name"],
                "rank" => getRankName($row["rank"]),
                "message" => getString("userlist.message"),
                "befriend" => getString("userlist.befriend"),
                "deceased" => $row["deceased"]
            ];
        }
        
        return $formatted_data;
    }
    
    private function formatCurrency($value) {
        $result = "€".number_format($value, 0, ",", ".");
        return $result;
    }
    
    private function formatProgress($rank, $value) {
        
        // Get the maximum xp for this rank needed to go rank up
        $max_xp = self::RANKS[$rank]["xp"];
        
        // Make sure XP are converted to percentage
        $result = round($value * 100/$max_xp)."%";
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
    
    /**
     * RANK functions
     */
    
    public function getRankXP($rank) {
        return self::RANKS[$rank]["xp"];
    }
    
    public function getRankHealth($rank) {
        return self::RANKS[$rank]["health"];
    }
    
    public function getRankJailTime($rank) {
        return self::RANKS[$rank]["jail"];
    }
    
    /**
     * Update the timestamp
     */
    
    public function updateTimestamp() {
        
        $user_id = $this->getUserId();
        
        // Try to get the expected parameters
        $player = $this->getPlayer($user_id);
        
        // The connection
        $conn = $this->conn;
        
        // Update the timestamp of this player
        $sql = "UPDATE players SET last_active=CURRENT_TIMESTAMP WHERE id=:player_id";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player["id"], PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();
    }
}
