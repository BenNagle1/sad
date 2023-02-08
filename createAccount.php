<?php
include "db.inc.php";

function generateSalt($password) {
    $salt = '';
    for ($iteration = 0; $iteration < strlen($password); $iteration++) {
        $salt .= chr(mt_rand(33, 126));
    }
    return $salt;
}

function hash_password($password) {
    $count = 10000;
    $hash = hash('sha256', $password . $salt);
    for ($i = 0; $i < $count; $i++) {
        $hash = sha256($hash . $salt);
    }
    return $hash;
}

$password = $_POST['password'];
$salt = generateSalt(16);
$hashedPassword = hash_password($password, $salt);

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