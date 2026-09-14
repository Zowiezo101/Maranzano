<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

function getNews() {
    echo "<p>Hier komen nieuws artikelen vanuit de database</p>";
}

function isValidCookie() {
    $isValid = false;
    
    // Send a cURL to the API with the cookie
    $result = curlGet("session");
    
    // check the result for errors
    if (isset($result) && ($result->error == "")) {
        $isValid = true;
    }
    
    // Return the result
    return $isValid;
}

// Send a post request to the given URL with curl
function curlPost($url, $data) {
    $response = curlRequest($url, "POST", $data);
    return $response;
}

// Send a get request to the given URL with curl
function curlGet($url) {
    $response = curlRequest($url, "GET");
    return $response;
}

function curlRequest($url, $method, $data = false) {
    global $domain_name;
    
    // Makes it easier in case the file is moved
    $base_url = "/api/";

    // Prepend these to the given URL
    $url = $domain_name.$base_url.$url;

    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    if ($method == "POST") {
        // Posting data
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    }
    
    // String to set cookies with curl
    $cookies = [];
    
    // If a cookie has been set
    if (filter_input(INPUT_COOKIE, "token")) {
        // Send the cookie back to the API
        $cookies[] = 'token=' . filter_input(INPUT_COOKIE, "token");
    }
    
    // If a cookie has been set
    if (filter_input(INPUT_COOKIE, "user")) {
        // Send the cookie back to the API
        $cookies[] = 'user=' . filter_input(INPUT_COOKIE, "user");
    }
    
    if (count($cookies) > 0) {
        curl_setopt($curl, CURLOPT_COOKIE, join("; ", $cookies));
    }
    
    $json = curl_exec($curl);
    
    $response = json_decode($json);
    
    curl_close($curl);
    
    return $response;
}
