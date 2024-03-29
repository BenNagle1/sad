<?php
function generateSalt($length) {
    $salt = '';
    for ($iteration = 0; $iteration < $length; $iteration++) {
        $salt .= chr(mt_rand(33, 126));
    }
    return $salt;
}

function hash_password($password, $salt) {
    $count = 1000;
    $salted_password = $password . $salt;
    $hash = hash('sha256', $salted_password);
    for ($i = 0; $i < $count; $i++) {
        $hash = hash("sha256", $hash);
    }
    return $hash;
}

?>