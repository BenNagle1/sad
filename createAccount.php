<?php
session_start(); 
include "db.inc.php";

$username = "";
$password = "";
$confirm_Password = "";
$username_err =  "";
$password_err = "";
$error = false;

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

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $confirm_Password = mysqli_real_escape_string($db, $_POST['confirmpassword']);

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
        $salt_length = strlen($password);
        $salt = generateSalt($salt_length);
        $hashed_password = hash_password($password, $salt);
        $hashed_password = mysqli_real_escape_string($db, $hashed_password);
        $sql_query = "INSERT INTO users (`username`, `salt`, `hashed_password`) 
        VALUES ('$username', '$salt','$hashed_password')";

        if (mysqli_query($db, $sql_query)) {
            header("location: login.php");
        } else {
            echo "Error: " . $sql_query . "<br>" . mysqli_error($db);
        }
    }
}

mysqli_close($db);

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
		<input type="password" name="password" id="password" required placeholder="Password" title="Please enter a password"/><br>
		<input type="password" name="confirmpassword" id="confirmpassword" required placeholder="Re-enter Password" title="Please enter a password"/><br>
	
	<input type="submit" value = "Create Account"/>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
     </form>
    </div>    
</body>
</html>