<?php

// Function to connect to the database
function connectDatabase(&$conn) {
    global $servername, $db_username, 
           $db_password, $db_database;
    global $error;
    
    try {
        // First make sure we can connect to the database
        $conn = new PDO("mysql:host={$servername};dbname={$db_database};charset=utf8", 
                        $db_username, $db_password,
                        [PDO::ATTR_EMULATE_PREPARES => false, 
                         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException) {
        $error = "auth.db_error";
    }
    
    return $error === null;
}

// Function to retrieve results from database
function getResults($stmt) {
    $result = null;

    if ($stmt->rowCount() > 0) {
        // Convert the results into an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    return $result;
}

// Function to retrieve results from database
function isTaken($stmt, $message) {
    global $error;
    
    // Make sure there are no results
    if (null !== getResults($stmt)) {
        // If there are, set an error
        $error = $message;
    }
}
