<?php 
include 'db.inc.php';
session_start();
$key = 'thebestsecretkey';

function encrypt($input, $key) {
    $encryption_key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));
    $encrypted = openssl_encrypt($input, 'aes-128-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt($input, $key) {
    $encryption_key = base64_decode($key);
    list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($input), 2), 2, null);
    return openssl_decrypt($encrypted_data, 'aes-128-cbc', $encryption_key, 0, $iv);
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
      
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
	  
    $sql = "SELECT * FROM users WHERE username='$username'";
    echo "Username: " . $username . "<br>";
    echo "Encrypted username: " . $username_encrypted . "<br>";
        echo "Password: " . $password . "<br>";
    echo "Encrypted password: " . $password_encrypted . "<br>";
    echo "SQL: " . $sql . "<br>";
    $result = mysqli_query($db, $sql);
	  
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $db_username = decrypt($row['username'], $key);
        $db_password = decrypt($row['password'], $key);
        
        if ($username == $db_username && $password == $db_password) {
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $row['id'];
        
            header("Location: home.php");
        } else {
            echo "Password or username is incorrect or not stored in our records";
        }
    } else {
        echo "Password or username is incorrect or not stored in our records";
    }
}
                 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
<form action = "login.php" method = "POST">

    <div class="login">
        <h1>Welcome</h1>
		<legend>Login to your account</legend>
		<label for="Username"></label>
		<input type="text" name="username" id="username" required placeholder="Username" title="Please enter a username"/><br>
		<label for="Password"></label>
		<input type="text" name="password" id="password" required placeholder="Password" title="Please enter a password"/><br>
		
		<input type="submit" value = "Login"/>
        <p>Don't have an account? <a href="createAccount.php">Creat Account here</a>.</p>
		
     </form>
    </div>    
</body>
</html>

<?php
mysqli_close($db);
?>