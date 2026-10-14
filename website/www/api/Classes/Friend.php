<?php

namespace Classes;

use PDO;

class Friend extends Player {
    
    public function route($route, $data) {
        // These actions need to have the cookie checked
        $auth = new Login($this->conn);
        $auth->route("login_validate", $data);
        
        // Parse the input data
        $this->parameters->setData($data);
        
        $result = null;
        
        switch($route) {
            
            case "friend_get":
                $result = $this->getPlayerFriends();
                break;
            
            case "friend_list":
                $result = $this->getFriendList();
                break;
            
            case "friend_add":
                $result = $this->addFriendByName();
                break;
            
            case "friend_remove":
                $result = $this->removeFriend();
                break;
            
            case "friend_request":
                $result = $this->friendRequest();
                break;
            
            case "friend_accept":
                $result = $this->friendAccept();
                break;
            
            case "friend_decline":
                $result = $this->friendDecline();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
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
    
    private function getFriendList() {
        
        $user_id = $this->getUserId();
            
        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);

        // The player ID
        $id = $player["id"];
        
        // Check which of these friends are online
        $online_friends = $this->formatFriendList($this->getOnlineFriendList($id));
        $offline_friends = $this->formatFriendList($this->getOfflineFriendList($id));
        
        return [
            "online" => $online_friends,
            "offline" => $offline_friends
        ];
    }
    
    private function addFriendByName() {
        
        $user_id = $this->getUserId();

        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);
        
        // Get the player_name this player wants to befriend
        $friend_name = $this->parameters->getPlayer();

        // Get the possibly future friend data
        $friend = $this->retrievePlayerFromName($friend_name);
        
        if (!isset($friend)) {
            // Do NOT continue if this person can't be found
            throwError("friends.name_not_found");
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
    
    private function removeFriend() {
        
        $user_id = $this->getUserId();

        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);
        
        // Get the player_id this player wants to befriend
        $friend_id = $this->parameters->getId();
        
        $this->deleteFriend($player["id"], $friend_id);
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
    
    private function friendAccept() {
        
        $user_id = $this->getUserId();

        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);
        
        // Get the message ID
        $message_id = $this->parameters->getId();
        
        // Get the player_name from the message
        $friend_name = $this->mailbox->getFriendName($message_id);

        // Get the possibly future friend data
        $friend = $this->retrievePlayerFromName($friend_name); 
        
        if (!isset($friend)) {
            // Do NOT continue if this person can't be found
            throwError("friends.player_not_found");
        }
        
        if ($friend["deceased"] !== 0) {
            // Also do NOT continue if this player is deceased
            throwError("friends.deceased");
        }
        
        // Add this unconfirmed friend to the players friends list
        $this->acceptFriendRequest($player["id"], $friend["id"], $message_id);
        
        return getString("message.accept");
    }
    
    private function friendDecline() {
        
        // TODO: Confirm that the message exists
        
        $user_id = $this->getUserId();

        // Get the player that belongs to this user
        $player = $this->getPlayer($user_id);
        
        // Get the message ID
        $message_id = $this->parameters->getId();
        
        // Get the player_name from the message
        $friend_name = $this->mailbox->getFriendName($message_id);

        // Get the possibly future friend data
        $friend = $this->retrievePlayerFromName($friend_name); 
        
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
        $this->declineFriendRequest($player["id"], $friend["id"], $message_id);
        
        return getString("message.decline");
    }
    
    /**
     * Player functions
     */
    
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
    
    private function acceptFriendRequest($player_id, $friend_id, $message_id) {
        
        // Checking for active records
        $player_record = $this->retrieveRecordFromFriendlist($player_id, $friend_id);
        $friend_record = $this->retrieveRecordFromFriendlist($friend_id, $player_id);
        
        // Check if the friend request is still valid
        if (!isset($friend_record)) {
            // Remove the expired message
            $this->mailbox->deleteMessage($message_id);
            
            throwError("friends.expired");
        } 
        
        if ($friend_record["is_confirmed"] === 0) {
            
            if (!isset($player_record)) {
                // Only if this player hasn't already tried to befriend the other player
                // We don't want double records in the database
                $this->addFriend($player_id, $friend_id);
            }
            
            // The other player wants to be friends, we only
            // have to do an update to confirm the friendship
            $this->updateFriend($player_id, $friend_id);
            $this->updateFriend($friend_id, $player_id);
            
            // Now remove this message
            $this->mailbox->deleteMessage($message_id);
        } else {
            // Remove the now expired message
            $this->mailbox->deleteMessage($message_id);
            
            // Both players have already confirmed their friendship
            throwError("friends.already_friends");
        }
    }
    
    private function declineFriendRequest($player_id, $friend_id, $message_id) {
        
        // Checking for active records
        $friend_record = $this->retrieveRecordFromFriendlist($friend_id, $player_id);
        
        // Check if the friend request is still valid
        if (!isset($friend_record)) {
            // Remove the expired message
            $this->mailbox->deleteMessage($message_id);
            
            throwError("friends.expired");
        } 
        
        if ($friend_record["is_confirmed"] === 1) {
            // Remove the now expired message
            $this->mailbox->deleteMessage($message_id);
            
            // Both players have already confirmed their friendship
            throwError("friends.already_friends");
        } else {
            $this->deleteFriend($player_id, $friend_id);
            
            // Remove the now expired message
            $this->mailbox->deleteMessage($message_id);
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
    
    private function deleteFriend($player_id, $friend_id) {
        // The connection
        $conn = $this->conn;
        
        // Update the message to read
        $sql = "DELETE FROM friends WHERE "
                . "(player_id = :player_id AND friend_id = :friend_id) OR "
                . "(player_id = :friend_id2 AND friend_id = :player_id2)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":player_id2", $player_id, PDO::PARAM_INT);
        $stmt->bindValue(":friend_id", $friend_id, PDO::PARAM_INT);
        $stmt->bindValue(":friend_id2", $friend_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();
    }
    
    private function getOnlineFriendList($player_id) {
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
        $results = getAllResults($stmt);
        
        // Send the ids, names, ranks and deceased state back
        return $results ? $results : [];
    }
    
    private function getOfflineFriendList($player_id) {
        $conn = $this->conn;
        
        // Get all the players in the database
        $sql = "SELECT * FROM players "
                . "JOIN friends on friends.friend_id = players.id "
                . "WHERE friends.player_id = :player_id AND friends.is_confirmed = 1 AND players.last_active < DATE_SUB(NOW(), INTERVAL 5 MINUTE)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id", $player_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $results = getAllResults($stmt);
        
        // Send the ids, names, ranks and deceased state back
        return $results ? $results : [];
    }
    
    /**
     * Data conversion functions
     */
    
    private function formatFriendList($data) {
        $formatted_data = [];
        
        foreach ($data as $row) {
            $formatted_data[] = [
                "id" => $row["friend_id"],
                "name" => $row["name"],
                "message" => getString("friends.message"),
                "remove" => getString("friends.remove"),
            ];
        }
        
        return $formatted_data;
        
    }
}
