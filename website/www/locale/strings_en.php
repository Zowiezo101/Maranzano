<?php 

$strings = [
    // Global
    "global.title" => "Mafiani",
    "global.version" => "v1.0a",
    "global.menu" => "Menu",
    "global.news" => "News",
    "global.error" => "Something went wrong..",
    "global.done" => "Done",
    "global.close" => "You can now close this page or go back to the homepage",
    
    // Menu
    "menu.home" => "Home",
    "menu.login" => "Log in",
    "menu.signup" => "Sign up",
    "menu.rules" => "Rules",
    "menu.aboutus" => "About us",
    "menu.users" => "users online",
    
    // Member Menu
    "menu.main" => "Main",
    "menu.business" => "Businesses",
    "menu.crimes" => "Crimes",
    "menu.casino" => "Casino",
    "menu.comms" => "Communication",
    "menu.help" => "Help",
    
    // Main menu
    "main.home" => "Home",
    "main.travel" => "Travel",
    "main.jail" => "Jail",
    "main.hospital" => "Hospital",
    
    // Business menu
    "business.bank" => "Bank",
    "business.bullet" => "Bullet shop",
    "business.garage" => "Garage",
    "business.family" => "Family",
    "business.manage" => "Manage family",
    
    // Crimes menu
    "crimes.bike" => "Steal a bike",
    "crimes.car" => "Steal a car",
    "crimes.store" => "Rob a store",
    "crimes.kill" => "Kill player",
    
    // Casino menu
    "casino.roulette" => "Roulette",
    "casino.scratch" => "Scratch & Match",
    
    // Communication menu"
    "comms.online" => "Online users",
    "comms.friends" => "Friend list",
    "comms.userlist" => "User list",
    "comms.mail" => "Mailbox",
    
    // Help menu
    "help.contact" => "Contact",
    "help.donate" => "Donate",
    "help.rules" => "Rules",
    "help.info" => "Game info",
    "help.settings" => "Settings",
    
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
    
    // Member Home
    "info.cash" => "Cash",
    "info.bank" => "Bank",
    "info.rank" => "Rank",
    "info.progress" => "Progress",
    "info.family" => "Family",
    "info.city" => "City",
    "info.country" => "Country",
    "info.health" => "Health",
    "info.bullets" => "Off. bullets",
    "info.shields" => "Def. bullets",
    
    // Authentication
    "login.title" => "Logging in",
    "login.email" => "E-mail",
    "login.password" => "Password",
    "login.forgotpass" => "Forgotten your password?",
    "login.reset" => "Reset password",
    "login.verify" => "You haven't verified your e-mail address yet. We've sent you a new verification e-mail",
    "reset.title" => "Resetting your password",
    "reset.success" => "Successfully updated password!",
    "reset.again" => "Please try requesting another password reset or contact us to help you out",
    "reset.info" => "Enter the e-mail address linked to your account to reset your password",
    "reset.email" => "If there is an account linked to the provided e-mail address, an e-mail will be send with a link to reset your password.<br/>This link will be valid for 30 minutes.",
    "update.success" => "You've successfully updated your password!<br/>We've sent you a confirmation e-mail",
    "signup.title" => "Signing up",
    "signup.username" => "Username",
    "signup.password" => "Password (has to be at least 8 characters)",
    "signup.confirm" => "Please confirm your password",
    "signup.success" => "You've successfully created an account!<br/>We've sent you an e-mail to the provided e-mail address. Please verify your e-mail address by clicking the link in the sent e-mail.",
    "verify.title" => "Verifying your e-mail address",
    "verify.success" => "Successfully verified!",
    "verify.again" => "Maybe your account is already verified. You can try logging in or contact us to help you out",
    "verify.home" => "Go back to the homepage",
    
    // Authentication emails
    "verify.subject" => "Please verify your e-mail address",
    "verify.body" => "<h3>Welcome [user]!</h3><p>Click <a href='[url]'>here</a> to verify your e-mail address or copy-paste this link into your browser:<br/>[url]. This link will be valid for 30 minutes.</p>",
    "reset.subject" => "Request for a password reset on Mafiani",
    "reset.body" => "<h3>Hello [user]!</h3><p>Click <a href='[url]'>here</a> to reset your password or copy-paste this link into your browser:<br/>[url]. This link will be valid for 30 minutes.</p><br/>If you did not request a password reset, you can safely ignore this mail.",
    "update.body" => "Successfully updated password",
    "update.confirm" => "<h3>Hello [user]!</h3><p>Your password has been successfully updated! If you did not request this password reset, please contact us immediately.",
    "email.from" => "The Mafiani Team",
    

    // API stuff
    "auth.db_error" => "It seems we currently have some issues with the database.. Please try again later",
    "auth.user.invalid" => "This username is invalid, please only use underscores, letters and numbers",
    "auth.user.taken" => "This username is already taken",
    "auth.email.invalid" => "This e-mail address is invalid, please use a valid e-mail address",
    "auth.email.taken" => "There is already an account with this e-mail address. Try logging in",
    "auth.pass1.invalid" => "The password is too short, please use at least 8 characters",
    "auth.pass2.invalid" => "Both passwords must match",
    "auth.token.invalid" => "This session is invalid or has expired",
    "auth.mail_error" => "Something went wrong trying to send your verification e-mail.. Please try again later",
    "auth.login.invalid" => "We couldn't find an account with these credentials. Perhaps you've entered an incorrect password",
    "auth.session.invalid" => "Invalid token",
    "signup.error" => "Something went wrong while trying to create your account",
    "verify.error" => "Something went wrong while trying to verify your account",
    "reset.error" => "Something went wrong while trying to send you a reset link",
    "validate.error" => "Something went wrong while trying to validate your new password",
    "update.error" => "Something went wrong while trying to update your password",
    "session.error" => "Something went wrong while trying to validate session",
    "login.error" => "Something went wrong while trying to log you in",
    "logout.error" => "Something went wrong while trying to log you out",
    
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
