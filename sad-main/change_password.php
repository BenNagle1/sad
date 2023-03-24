<?php
session_start(); 
include "db.inc.php";
require_once 'functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = "";
$password = "";
$confirm_password = "";
$username_err =  "";
$password_err1 = "";
$password_err2 = "";
$password_err3 = "";
$error = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
        if (isset($_POST['change_password'])) {
            $current_password = mysqli_real_escape_string($db, $_POST['current_password']);
            $new_password = mysqli_real_escape_string($db, $_POST['new_password']);
            $confirm_password = mysqli_real_escape_string($db, $_POST['confirm_password']);
    
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT * FROM users WHERE id=? LIMIT 1";
            $statement = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($statement, "i", $user_id);
            mysqli_stmt_execute($statement);
            $user_result = mysqli_stmt_get_result($statement);
            $user = mysqli_fetch_assoc($user_result);
    
            if (!$user || !verify_password($current_password, $user['hashed_password'], $user['salt'])) {
                $error = true;
                $password_err1 = "Current password does not match our records";
            }
    
            if (strlen($new_password) < 8) {
                $error = true;
                $password_err2 = "Your password must have at least 8 characters.";
            }
    
            if (trim($new_password) != trim($confirm_password)) {
                $password_err3 = "The passwords you entered do not match.";
                $error = true;
            }
    
            if (!$error) {
                $salt_length = strlen($new_password);
                $salt = generateSalt($salt_length);
                $hashed_password = hash_password($new_password, $salt);
                $hashed_password = mysqli_real_escape_string($db, $hashed_password);
                $update_query = "UPDATE users SET salt=?, hashed_password=? WHERE id=?";
                $update_statement= mysqli_prepare($db, $update_query);
                mysqli_stmt_bind_param($update_statement, "ssi", $salt, $hashed_password, $user_id);
                mysqli_stmt_execute($update_statement);
    
                if (mysqli_stmt_affected_rows($update_statement) > 0) {
                    header("location: login.php");
                } else {
                    echo "Error: " . $update_query . "<br>" . mysqli_error($db);
                }
            }
        }
    }
} else {
    header("Location: login.php");
    exit;
}

mysqli_close($db);
?>


 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Change Password Portal</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
<form action="change_password.php" method="post">
<div class="login">
    <h2>Change Password</h2>
    <input type="password" name="current_password" required placeholder="Current Password" title="Please enter your current password" /><br>
    <input type="password" name="new_password" required placeholder="New Password" title="Please enter a new password" /><br>
    <input type="password" name="confirm_password" required placeholder="Confirm New Password" title="Please re-enter your new password" /><br>
    <input type="submit" name="change_password" value="Change Password" /><br>
    <a href="home.php"><input type="button" name="exit" value="Exit"class="exit"></a>
    <div class="error">
        <?php
            echo "$password_err1   \n";
            echo "$password_err2   \n";
            echo "$password_err3  \n";
        ?>
    </div>
</div>
</form>
