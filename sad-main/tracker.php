<?php 


if(isset($_SESSION['username'])) {
    $time = time();

    if(isset($_SESSION['last_active'])){

        $inactiveTime = $time - $_SESSION['last_active'];

        if($inactiveTime > 600) {

            session_destroy();
            header('Location: login.php');
            exit();

        }

    }


    $_SESSION['last_active'] = $time;

}

?>


