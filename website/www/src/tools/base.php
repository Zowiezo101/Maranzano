<?php

/*
 * Base file for some strings and other info 
 * 
 */

// The settings files and string files
require __DIR__ . "/../../../settings.conf";
require __DIR__ . "/../../locale/strings_en.php";

// Other files to be used
require __DIR__ . "/database.php";

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
