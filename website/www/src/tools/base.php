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
    "rules.content" => "Hier komen de regels",
    "aboutus.content" => "Hier komt een about us",
    "global.copyright" => "<b>Copyright 2026 - ??</b>",
);

function getString($string) {
    global $strings;
    
    echo $strings[$string];
}
