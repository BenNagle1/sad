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


function get_random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $string;
}

function verify_password($new_password, $hashed_password, $salt) {
    $new_password_hash = hash_password($new_password, $salt);
    return $new_password_hash === $hashed_password;
}

function xss_filter($input){
    $input = preg_replace("/<script>/","", $input);
    return $input;
}

function password_check($password) {
	$check = true;

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+!-]/', $password);

    if(!$uppercase || !$lowercase || !$number || !$specialChars){
        
        $check = false; 
    }

	return $check;

}
?>



