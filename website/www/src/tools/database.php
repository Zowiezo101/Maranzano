<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

// Send a post request to the given URL with curl
function curlPost($url, $data) {
    // Makes it easier in case the file is moved
    $base_url = "/api/";

    // Prepend these to the given URL
    $url = $base_url.$url;

    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    // Posting data
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
    // If a cookie has been set
    if (filter_input(INPUT_COOKIE, "token")) {
        // Send the cookie back to the API
        curl_setopt($curl, CURLOPT_COOKIE, 'token=' . filter_input(INPUT_COOKIE, "token"));
    }
    
    $json = curl_exec($curl);
    
    $response = json_decode($json);
    
    curl_close($curl);
    
    return $response;
}

function curlGet() {
    // Makes it easier in case the file is moved
    $base_url = "/api/";

    // Prepend these to the given URL
    $url = $base_url.$url;

    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    // If a cookie has been set
    if (filter_input(INPUT_COOKIE, "token")) {
        // Send the cookie back to the API
        curl_setopt($curl, CURLOPT_COOKIE, 'token=' . filter_input(INPUT_COOKIE, "token"));
    }
    
    $json = curl_exec($curl);
    
    $response = json_decode($json);
    
    curl_close($curl);
    
    return $response;
}

function getNews() {
    echo "<p>Hier komen nieuws artikelen vanuit de database</p>";
}
