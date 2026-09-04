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
    "global.error" => "Something went wrong..",
    "global.done" => "Done",
    
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
    "rules.content" => "
            <b>Rules:</b>
            <p>1. Play like a mobster, Act like gentleman.</p>
            <p>2. No form of cheating is allowed and will result in a ban.</p>
            <p>3. Never give or share your private information for your own safety.</p>
            <p>4. AI or Explicit content is not allowed in your family profile.</p>
            <p>5. Have fun!</p>",
    "aboutus.content" => "<b>Dear Famiglia,</b>
            <p>We (Mister & Missus) are a married couple who decided to recreate an old-school Mafia browser-based text game. The kind that Mister used to play with his father, and which heavily inspired him to start this project. The game that started it all is called \"Deluccio\".</p>
            <p>We started creating this website in late August of the year of our Lord, 2026. The site is still in super early development as of now, but we are looking forward to where the road ahead takes us with this project.</p>
            <p>Missus wants you to know that no vibe coding was used in the making of this website. Code is either written by Missus or \"yoinked\" from 13 year old forum pages.</p>",
    
    // Authentication
    "login.title" => "Logging in",
    "login.email" => "E-mail",
    "login.password" => "Password",
    "login.forgotpass" => "Forgotten your password?",
    "login.reset" => "Reset password",
    "reset.info" => "Enter the e-mail address linked to your account to reset your password",
    "reset.email" => "If there is an account linked to the inserted e-mail address, an e-mail will be send with a link to reset your password.<br/>This link will be valid for 30 minutes.",
    "signup.title" => "Signing up",
    "signup.username" => "Username",
    "signup.password" => "Password (has to be at least 8 characters)",
    "signup.confirm" => "Please confirm your password",
    "signup.success" => "You've successfully created an account!<br/>We've send you an e-mail to the provided e-mail address. Please verify your e-mail address by clicking the link in the sent e-mail.",
    "verify.title" => "Verifying your e-mail address",
    "verify.success" => "Successfully verified!",
    "verify.close" => "You can now close this page or go back to the homepage",
    "verify.again" => "Maybe your account is already verified or the link is no longer valid. You can try logging in or contact us to help you out",
    "verify.home" => "Go back to the homepage",
    "verify.from" => "The Mafiani Team",
    "verify.subject" => "Please verify your e-mail address",
    "verify.body" => "<h3>Welcome [user]!</h3><p>Click <a href='[url]'>here</a> to verify your e-mail address or copy-paste this link into your browser:<br/>[url]. This link will be valid for 30 minutes.</p>",

    // API stuff
    "auth.db_error" => "It seems we currently have some issues with the database.. Please try again later",
    "auth.user.invalid" => "This username is invalid, please only use underscores, letters and numbers",
    "auth.user.taken" => "This username is already taken",
    "auth.email.invalid" => "This e-mail address is invalid, please use a valid e-mail address",
    "auth.email.taken" => "There is already an account with this e-mail address. Try logging in",
    "auth.pass1.invalid" => "The password is too short, please use at least 8 characters",
    "auth.pass2.invalid" => "Both passwords must match",
    "auth.token.invalid" => "Something went wrong while verifying your e-mail address",
    "auth.mail_error" => "Something went wrong trying to send your verification e-mail.. Please try again later",
    
    // Misc
    "global.copyright" => "<b>Copyright 2026 - ??</b>"
];

// Check if a key exists in Strings
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
        // Escape the string
        $string_e = json_encode($string);
        
        // Unescape characters for the following tags
        $string_br = str_replace("<br\/>", "<br/>", $string_e);
        $string_b = str_replace("<\/b>", "</b>", $string_br);
        $string = str_replace("<\/p>", "</p>", $string_b);
    }
    
    echo $string;
}
