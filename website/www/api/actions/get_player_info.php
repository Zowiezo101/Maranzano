<?php

// Base file that contains all the other classes
require __DIR__ . "/../config/core.php";

// Required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: ".$domain_name);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Credentials: true");

// Helper class to authenticate a user
$auth = new Classes\Login();

// Make sure the session is still valid
$user_id = $auth->getUserIdFromSession();

// Helper class for players
$player = new Classes\Player();

// Get info of this player
$player->getPlayerInfo($user_id);

// Send a message back to the client
$player->sendMessage();
