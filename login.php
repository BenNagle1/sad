<?php 
include 'db.inc.php';
session_start();
error_reporting(E_ERROR);

$error=false;

function hash_password($password, $salt) {
    $count = 1000;
    $salted_password = $password . $salt;
    $hash = hash('sha256', $salted_password);
    for ($i = 0; $i < $count; $i++) {
        $hash = hash("sha256", $hash);
    }
    return $hash;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
      
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT salt, hashed_password FROM users WHERE username='$username'";
    $result = mysqli_query($db, $sql);
    $user = mysqli_fetch_assoc($result);
    $salt = $user['salt'];
    $hashed_password = $user['hashed_password'];

    $hash_enteredPassword = hash_password($password, $salt);
 
    if (mysqli_num_rows($result) == 1) {
        
        if ($hashed_password == $hash_enteredPassword) {
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $user['id'];
        
            header("Location: home.php");
        } else {
            $error=true;
        }
    } else {
        $error=true;
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
		<input type="password" name="password" id="password" required placeholder="Password" title="Please enter a password"/><br>
		
		<input type="submit" value = "Login"/>
        <p>Don't have an account? <a href="createAccount.php">Creat Account here</a>.</p>
        <div class="error">
        <?php
        if($error==true){
        echo "The username '$username' and password could not be authenticated at the moment";
        }
         ?>
    </div>
     </form>
    </div>    
</body>
</html>

<?php
mysqli_close($db);
?>