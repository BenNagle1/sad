<?php
session_start(); 
include "db.inc.php"; 
require_once 'functions.php'; 
include 'tracker.php';

if (!isset($_SESSION['username'])) { // Chekcs if the user is logged in, if not redirect to login page
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
$password_err4 = "";
$error = false;


if (isset($_SESSION['user_id'])) { 
    $user_id = $_SESSION['user_id'];
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['change_password'])) { // Check if HTTP request is GET as per project requirments and form has been submitted
        if (isset($_GET['change_password'])) { // Check if the form form has been submitted (user clicks change password button)
            $current_password = mysqli_real_escape_string($db, $_GET['current_password']); 
            $new_password = mysqli_real_escape_string($db, $_GET['new_password']); 
            $confirm_password = mysqli_real_escape_string($db, $_GET['confirm_password']); 
    
            $user_id = $_SESSION['user_id'];
            //Select the user from the users table with the matching ID of logged in user 
            $sql = "SELECT * FROM users WHERE id=? LIMIT 1"; 
            $statement = mysqli_prepare($db, $sql); 
            mysqli_stmt_bind_param($statement, "i", $user_id);
            mysqli_stmt_execute($statement); 
            $user_result = mysqli_stmt_get_result($statement); 
            $user = mysqli_fetch_assoc($user_result); 
    
            //Check if the entered old password matches the users table entry 
            if (!$user || !verify_password($current_password, $user['hashed_password'], $user['salt'])) { 
                $error = true;
                $password_err1 = "Current password does not match our records";
            }
    
            //Check new password is greater than 8 chars 
            if (strlen($new_password) < 8) {
                $error = true;
                $password_err2 = "Your password must have at least 8 characters.";
            }

            if (!password_check($new_password)) {
                $error = true;
                $password_err3 = "Password must have at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.";
            }
    
            //Check if the new passwords match
            if (trim($new_password) != trim($confirm_password)) { 
                $password_err4 = "The new passwords you entered do not match.";
                $error = true;
            }
    
            if (!$error) { 
                $salt_length = strlen($new_password);
                $salt = generateSalt($salt_length); 
                $hashed_password = hash_password($new_password, $salt); 
                $hashed_password = mysqli_real_escape_string($db, $hashed_password); 
                // Now, update the user record with new salt and the new hashed password
                $update_query = "UPDATE users SET salt=?, hashed_password=? WHERE id=?"; 
                $update_statement= mysqli_prepare($db, $update_query); 
                mysqli_stmt_bind_param($update_statement, "ssi", $salt, $hashed_password, $user_id); 
                mysqli_stmt_execute($update_statement); 
    
                if (mysqli_stmt_affected_rows($update_statement) > 0) { //Checkk if the update query affected at least one row. If not, displya the below error message. 
                    session_destroy();
                    header("location: login.php?message=Password updated successfully"); //redirect to login page 
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
<form action="change_password.php" method="get">
<div class="login">
    <h2>Change Password</h2>
    <input type="password" name="current_password" required placeholder="Current Password" title="Please enter your current password" /><br>
    <input type="password" name="new_password" required placeholder="New Password" title="Please enter a new password" /><br>
    <input type="password" name="confirm_password" required placeholder="Confirm New Password" title="Please re-enter your new password" /><br>
    <input type="submit" name="change_password" value="Change Password" /><br>
    <a href="home.php"><input type="button" name="exit" value="Exit"class="exit"></a>
    <div class="error">
    <?php
        $has_error = !empty($password_err1) || !empty($password_err2) || !empty($password_err3) || !empty($password_err4); //check if there is an error before proceeding to print
        if ($has_error) {
        echo "<div class='error'>";
        echo "$password_err1   \n";
        echo "$password_err2   \n";
        echo "$password_err3  \n";
        echo "$password_err4  \n";
        echo "</div>";
    }
?>
    </div>
</div>
</form>
