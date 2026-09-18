<?php

// This needs to be started at the very beginning
session_start();

/*
 * Base file for some strings and other info 
 * 
 */

// The settings files and string files
require __DIR__ . "/../../../settings.conf";
require __DIR__ . "/../../locale/strings_en.php";

// Other tools to be used
require __DIR__ . "/database.php";
require __DIR__ . "/tabs.php";

// Print a single string with the given name
function printString($name, $json = false) {
    $string = getString($name);
    
    if ($json == true) {
        // Escape the string
        $string_e = json_encode($string);
        
        // Unescape characters for the following tags
        $string_br = str_replace("<br\/>", "<br/>", $string_e);
        $string_b = str_replace("<\/b>", "</b>", $string_br);
        $string = str_replace("<\/p>", "</p>", $string_b);
    }
    
    echo $string;
}

function checkLoggedIn($url_if_true="", $url_if_false="") {
    $loggedIn = false;
    
    // Check if there is already a session where the user is logged in
    $member_name = isset($_SESSION["member_name"]) ? 
                         $_SESSION["member_name"] : null;
    
    if (isset($member_name)) {
        // The user is already logged in
        $loggedIn = true;
    } else if (isValidCookie()) {
        // There is a valid cookie set, 
        // meaning we've logged in successfully previously
        $loggedIn = true;
    
        // Set the session for this member 
        // so we don't have to keep checking the cookie..
        $_SESSION["member_name"] = filter_input(INPUT_COOKIE, "user");
    }
    
    if (($loggedIn == false) && ($url_if_false !== "")) {
        // Redirect to selected page if we aren't logged in
        goToURL($url_if_false);
    } else if (($loggedIn == true) && ($url_if_true !== "")) {
        // Redirect to selected page if we are logged in
        goToURL($url_if_true);
    }
    
    // Return our logged in state
    return $loggedIn;
}

function goToURL($url) {
    // Redirect to selected page
    if( headers_sent() ) { 
        echo("<script>location.href='$url'</script>"); 
    } else { 
        header("Location: $url"); 
    }
}
