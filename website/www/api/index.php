<?php

// Base file that contains all the other classes
require __DIR__ . "/config/core.php";

// Required headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: ".$domain_name);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Credentials: true");

$route = filter_input(INPUT_GET, "route");

// The controller to handle all the routes
$controller = new Classes\Controller();
$controller->route($route);
