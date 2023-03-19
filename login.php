<?php 
include 'db.inc.php';
require_once 'functions.php';

session_start();
error_reporting(E_ERROR);

$error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['lockout']) && time() < $_SESSION['lockout']) {
        $error = true;
    } else {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $sql = "SELECT salt, hashed_password FROM users WHERE username=?";
        $statement = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($statement, "s", $username);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $user = mysqli_fetch_assoc($result);
        $salt = $user['salt'];
        $hashed_password = $user['hashed_password'];

        $hash_enteredPassword = hash_password($password, $salt);

        if (mysqli_num_rows($result) == 1) {
            if ($hashed_password == $hash_enteredPassword) {
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $user['id'];
                unset($_SESSION['attempts']);
                unset($_SESSION['lockout']);
                header("Location: home.php");
            } else {
                if (!isset($_SESSION['attempts'])) {
                    $_SESSION['attempts'] = 1;
                } else {
                    $_SESSION['attempts']++;
                }
                if ($_SESSION['attempts'] >= 5) {
                    $_SESSION['lockout'] = time() + 180;
                }
                $error = true;
            }
        } else {
            $error = true;
        }
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
    <form action="login.php" method="POST">
        <div class="login">
            <h1>Welcome</h1>
            <legend>Login to your account</legend>
            <label for="Username"></label>
            <input type="text" name="username" id="username" required placeholder="Username" title="Please enter a username"/><br>
            <label for="Password"></label>
            <input type="password" name="password" id="password" required placeholder="Password" title="Please enter a password"/><br>

            <input type="submit" value="Login"/>
            <p>Don't have an account? <a href="createAccount.php">Create Account here</a>.</p>
            <div class="error">
                <?php
                if ($error) {
                    if (isset($_SESSION['lockout']) && time() < $_SESSION['lockout']) {
                        $remaining_time = $_SESSION['lockout'] - time();
                        echo "You've exceeded the maximum number of login attempts. Please try again in " . $remaining_time . " seconds.";
                    } else {
                        echo "The username '$username' and password could not be authenticated at the moment";
                    }
                }
                ?>
            </div>
        </div>
    </form>
</body>
</html>

<?php
mysqli_close($db);
?>