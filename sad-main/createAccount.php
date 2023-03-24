
<?php
session_start(); 

include "db.inc.php"; 
require_once 'functions.php'; 

$username = ""; 
$password = ""; 
$confirm_Password = ""; 
$username_err =  ""; 
$password_err1 = ""; 
$password_err2 = ""; 
$error = false; 

if($_SERVER["REQUEST_METHOD"] == "POST"){ 

    //Get the username entered by user and apply mysqli_real_escape_string() and xss_filter() functions
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $username = xss_filter($username);

    // Check if the username is now empty (after applying xss_filter), prevents user from creating a username that is an XSS payload for example.
    if(empty($username)){
        $error = true;
        $username_err = "Please enter a valid username, the characters you entered are not permitted." ;
    }

    // Get the password and confirm password, and apply mysqli_real_escape_string() function
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $confirm_Password = mysqli_real_escape_string($db, $_POST['confirmpassword']);

    // Check if the username already exists in the users table 
    $check = "SELECT * FROM users WHERE username=? LIMIT 1";
    $statement= mysqli_prepare($db, $check);
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $user_check = mysqli_fetch_assoc($result);
    if ($user_check) {
        if ($user_check['username'] === $username) {
            $error = true;
            $username_err = "The username you entered already exists in our records.";
        }
    }

    // Check if the password meets the security check
    if (!password_check($password)) {
        $error = true;
        $password_err1 = "Password must have at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    // Check if the entered passwords match
    if ($password != $confirm_Password) {
        $password_err2 = "The passwords you entered do not match.";
        $error = true;
    }

    //If there is no errors (error not set to true), then insert the new user into the table
    if (!$error) {
        $salt_length = strlen($password);
        $salt = generateSalt($salt_length); //generate salt
        $hashed_password = hash_password($password, $salt); //call the hash_password function
        $hashed_password = mysqli_real_escape_string($db, $hashed_password);
        $sql_query = "INSERT INTO users (`username`, `salt`, `hashed_password`) 
        VALUES (?, ?, ?)";
        $statement= mysqli_prepare($db, $sql_query);
        mysqli_stmt_bind_param($statement, "sss", $username, $salt, $hashed_password);
        mysqli_stmt_execute($statement); //execute the statement 

        // If the user is successfully created and inserted into table, redirect to the login page
        if (mysqli_stmt_affected_rows($statement) > 0) {
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
    <div class="error">
    <?php
      echo "$password_err1   \n"; //print errors, if any
      echo "$username_err \n";
      echo "$password_err2  \n";
    ?>
    </div>
     </form>
    </div>    
</body>
</html>
