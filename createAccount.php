<?php
include "db.inc.php";

session_start();

$key = 'thebestsecretkey';

function encrypt($input, $key) {
    $encryption_key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));
    $encrypted = openssl_encrypt($input, 'aes-128-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

$username = "";
$password = "";
$confirm_Password = "";
$username_err =  "";
$password_err = "";
$error = false;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $confirm_Password = mysqli_real_escape_string($db, $_POST['confirmpassword']);

    // Ensure that username does not already exist in database
    $user_validate = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($db, $user_validate);
    $user_check = mysqli_fetch_assoc($result);

    if ($user_check) {
        if ($user_check['username'] === $username) {
            $error = true;
            $username_err = "Username already exists in our records";
        }
    }

    if (strlen($password) < 8) {
        $error = true;
        $password_err = "Password must have at least 8 characters.";
    }

    if ($password != $confirm_Password) {
        $password_err = "Passwords do not match";
        $error = true;
    }

    if (!$error) {
        $encrypted_username = encrypt($username, $key);
        $encrypted_password = encrypt($password, $key);

        $sql_query = "INSERT INTO users (`username`, `password`) 
        VALUES ('$encrypted_username', '$encrypted_password')";

        if (mysqli_query($db, $sql_query)) {
            header("location: login.php");
        }
    }

    mysqli_close($db);
}
?>
 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcomee</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
<form action = "createAccount.php" method = "POST">

    <div class="login">
        <h1>Welcome</h1>
		<legend>Register your account</legend>
		<input type="text" name="username" id="username" required placeholder="Username" title="Please enter a username"/><br>
		<input type="text" name="password" id="password" required placeholder="Password" title="Please enter a password"/><br>
		<input type="text" name="confirmpassword" id="confirmpassword" required placeholder="Re-enter Password" title="Please enter a password"/><br>
	
	<input type="submit" value = "Create Account"/>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
     </form>
    </div>    
</body>
</html>