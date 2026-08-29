<?php

/*
 * Base file for some strings and other info 
 * 
 */

$strings = array(
    "global.title" => "Mafiani",
    "global.version" => "v1.0a",
    "global.menu" => "Menu",
    "global.news" => "News",
    "menu.home" => "Home",
    "menu.login" => "Log in",
    "menu.signup" => "Sign up",
    "menu.rules" => "Rules",
    "menu.aboutus" => "About us",
    "menu.users" => "X users online",
    "home.content" => "
            <b>Welcome to Mafiani</b>
            <p>An oldskool mafia game inspired by the beloved \"DeLuccio\". Climb to power in this thrilling player driven mafia experience. Where you choose how to play and who to trust.</p>
            <br/>
            <p>-Team Mafiani</p>",
    "rules.content" => "Hier komen de regels",
    "aboutus.content" => "Hier komt een about us",
    "global.copyright" => "<b>Copyright 2026 - ??</b>",
);

function getString($string) {
    global $strings;
    
    echo $strings[$string];
}
