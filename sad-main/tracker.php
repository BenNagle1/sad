<?php

// Set the maximum session duration to 1 hour (3600 seconds)
$maxSessionDuration = 3600; 
// Check if the user is logged in 
if(isset($_SESSION['username'])) {

    // Get the current time 
    $time = time();

    // Check if the users last active time has been set 
    if(isset($_SESSION['last_active'])){

        // Calculate how many seconds have passed since the user was last acitve, this is the inactive time
        $inactiveTime = $time - $_SESSION['last_active'];

        // If the inactive time exceeds 10 mins (600 seconds), the session is destroyed and the user is redirected to the login page
        if($inactiveTime > 600) {
            session_destroy();
            header('Location: login.php');

            exit();

        }
        $activeTime = $time - $_SESSION['last_active'];

        if($activeTime > $maxSessionDuration) {
            session_destroy();
            header('Location: login.php');
            exit();
        }

    }

    // Last active time is updated to the current time if the user is still active 
    $_SESSION['last_active'] = $time;

}

?>


