<?php

/*
 * Base file for some strings and other info 
 * 
 */

require "database.php";

$strings = [
    // Global
    "global.title" => "Mafiani",
    "global.version" => "v1.0a",
    "global.menu" => "Menu",
    "global.news" => "News",
    
    // Menu
    "menu.home" => "Home",
    "menu.login" => "Log in",
    "menu.signup" => "Sign up",
    "menu.rules" => "Rules",
    "menu.aboutus" => "About us",
    "menu.users" => "X users online",
    
    // Homepage
    "home.content" => "
            <b>Welcome to Mafiani</b>
            <p>An oldskool mafia game inspired by the beloved \"DeLuccio\". Climb to power in this thrilling player driven mafia experience. Where you choose how to play and who to trust.</p>
            <br/>
            <p>-Team Mafiani</p>",
    "rules.content" => "Hier komen de regels",
    "aboutus.content" => "Hier komt een about us",
    
    // Authentication
    "login.title" => "Logging in",
    "login.email" => "E-mail",
    "login.password" => "Password",
    "login.forgotpass" => "Forgotten your password?",
    "login.reset" => "Reset password",
    "reset.email" => "If there is an account linked to the inserted e-mail address, an e-mail will be send with a link to reset your password.<br/>This link will be valid for 15 minutes.",
    "signup.title" => "Signing up",
    "signup.username" => "Username",
    "signup.password" => "Password (has to be at least 8 characters)",
    "signup.confirm" => "Please confirm your password",
    "signup.success" => "You've successfully created an account!<br/>We've send you an e-mail to the provided e-mail address. Please verify your e-mail address by clicking the link in the sent e-mail.",
    "signup.verified" => "Done",
    "verify.from" => "The Mafiani Team",
    "verify.subject" => "Please verify your e-mail address",
    "verify.body" => "<h3>Welcome [user]!</h3><p>Click <a href='[url]'>here</a> to verify your e-mail address or copy-paste this link into your browser:<br/>[url]</p>",

    // API stuff
    "auth.db_error" => "It seems we currently can't reach the database.. Please try again later",
    "auth.user.invalid" => "This username is invalid, please only use underscores, letters and numbers",
    "auth.email.invalid" => "This e-mail address is invalid, please use a valid e-mail address",
    "auth.email.taken" => "There is already an account with this e-mail address. Try logging in",
    "auth.pass1.invalid" => "The password is too short, please use at least 8 characters",
    "auth.pass2.invalid" => "Both passwords must match",
    "auth.token.invalid" => "Something went wrong while verifying your e-mail address",
    
    // Misc
    "global.copyright" => "<b>Copyright 2026 - ??</b>"
];

function hasString($name) {
    global $strings;
    
    return array_key_exists($name, $strings);
}

// Return a single string with the given name
function getString($name) {
    global $strings;
    
    $string = $strings[$name];
    
    return $string;
}

// Print a single string with the given name
function printString($name, $json = false) {
    $string = getString($name);
    
    if ($json == true) {
        $string = json_encode($string, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
    
    echo $string;
}
