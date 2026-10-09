<?php

namespace Classes;

use PDO;

class Mailbox {
    // Other classes
    private $parameters;
    
    // The database connection
    protected $conn;
    
    private CONST SYSTEM_ID = -999;
    private CONST SYSTEM_NAME = "The Mafiani Team";
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->parameters = new Parameters();
    }
    
    public function route($route, $data) {      
        // These actions need to have the cookie checked
        $auth = new Login($this->conn);
        $auth->route("login_validate", $data);
        
        // Parse the input data
        $this->parameters->setData($data);
        
        $result = null;
        
        switch($route) {
            case "message_send":
                $result = $this->sendMessage();
                break;
            
            case "message_list":
                $result = $this->getMessages();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function sendMessage() {
        
    }
    
    private function getMessages() {
        $playerObj = new Player($this->conn);
        $player = $playerObj->getPlayerFromCookieData();
        
        $conn = $this->conn;
        
        // The SQL
        $sql = "SELECT * FROM messages WHERE receiver_id = :receiver_id";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":receiver_id", $player["id"], PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getAllResults($stmt);
        
        return $this->formatMessages($result);
    }
    
    /**
     * Misc functions
     */
    
    public function sendMessageToPlayer($sender_id, $player_id, $subject, $body) {
        $conn = $this->conn;
        
        // The SQL
        $sql = "INSERT INTO messages (sender_id, receiver_id, subject, body) VALUES (:sender_id, :receiver_id, :subject, :body)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":sender_id",   $sender_id, PDO::PARAM_INT);
        $stmt->bindValue(":receiver_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":subject",     $subject,   PDO::PARAM_STR);
        $stmt->bindValue(":body",        $body,      PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Insert the ID into the parameter array
        $id = $conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue if the message hasn't been created
            throwError();
        }
        
        return $id;
    }
    
    public function sendFriendRequest($player_name, $friend_id) {   
        
        // The subbject of the message
        $subject = getString("data.subject.friend_request");
        $subject1 = str_replace("[player]", ucfirst($player_name), $subject);
        
        // The body of the message
        $body = getString("data.body.friend_request");
        $body1 = str_replace("[player]", ucfirst($player_name), $body);
        
        $this->sendMessageToPlayer(self::SYSTEM_ID, $friend_id, $subject1, $body1);
    }
    
    public function sendJailBail($player_name, $friend_id) {
        
        // The subbject of the message
        $subject = getString("data.subject.jail_bail");
        $subject1 = str_replace("[player]", ucfirst($player_name), $subject);
        
        // The body of the message
        $body = getString("data.body.jail_bail");
        $body1 = str_replace("[player]", ucfirst($player_name), $body);
        
        $this->sendMessageToPlayer(self::SYSTEM_ID, $friend_id, $subject1, $body1);
    }
    
    public function sendJailFreed($player_name, $friend_id) {
        
        // The subbject of the message
        $subject = getString("data.subject.jail_freed");
        $subject1 = str_replace("[player]", ucfirst($player_name), $subject);
        
        // The body of the message
        $body = getString("data.body.jail_freed");
        $body1 = str_replace("[player]", ucfirst($player_name), $body);
        
        $this->sendMessageToPlayer(self::SYSTEM_ID, $friend_id, $subject1, $body1);
    }
    
    /**
     * Formatting functions
     */
    
    private function formatMessages($data) {
        $formatted_data = [];
        
        if (isset($data)) {
            foreach ($data as $row) {
                if ($row["sender_id"] !== -999) {
                    $playerObj = new Player($this->conn);
                    $sender = $playerObj->retrievePlayerFromId($row["sender_id"]);
                } else {
                    $sender = ["name" => self::SYSTEM_NAME];
                }

                $formatted_data[] = [
                    "id"      => $row["id"],
                    "from"    => $sender["name"],
                    "subject" => $row["subject"],
                    "body"    => $row["body"],
                    "sent"    => $row["created_at"],
                    "read"    => $row["is_read"]
                ];
            }
        }
        
        return $formatted_data;
    }
}
