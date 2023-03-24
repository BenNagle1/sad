<?php
include 'db.inc.php'; 
require_once 'functions.php'; 

session_start();
error_reporting(E_ERROR); 

$error = false; 
$_SESSION['token'] = get_random_string(60); // Generates random token and stores it in session

// Checks if request method is POST, and if token values match
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['token']) && isset($_POST['token']) && isset($_SESSION['token']) == isset($_POST['token']) ) {
    $user_agent = $_SERVER['HTTP_USER_AGENT']; // gets user agent from HTTP header

    // Check if user has exeeded the max number of login attemps
    if (isset($_SESSION['lockout']) && time() < $_SESSION['lockout']) {
        $error = true;
    } else {
        $username = trim($_POST['username']); 
        $username = xss_filter($username); 
        $password = trim($_POST['password']); 

        // Get the user information (id, salt, hashed password) from the users table based on entered username
        $sql = "SELECT id, salt, hashed_password FROM users WHERE username=?";
        $statement = mysqli_prepare($db, $sql); 
        mysqli_stmt_bind_param($statement, "s", $username); 
        mysqli_stmt_execute($statement); 
        $result = mysqli_stmt_get_result($statement); //Result of the above query 

        // Checks there is only one user with the entered username in the DB
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result); 
            $salt = $user['salt']; 
            $hashed_password = $user['hashed_password']; 
            $hash_enteredPassword = hash_password($password, $salt); // Hashes the entered password 

            //Checks if the entered hashed password matches the hashed password retrieved from the users table in the db
            if ($hashed_password == $hash_enteredPassword) {
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $user['id'];
                unset($_SESSION['attempts']); // Unsets the login attempts from session
                unset($_SESSION['lockout']); // Unsets the lockout from session

                log_login_attempt($username, 'success', $_SERVER['REMOTE_ADDR'], $user_agent); // logs successful login attempt

                // If the usere is ADMIN, they are redirected to the admin home page. Otherwise they are directed to the normal home page/ 
                if ($username === 'ADMIN') {
                    header("Location: admin_home.php");
                } else {
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = $user['id'];
                    header("Location: home.php");
                }
                exit(); 

            } else {
                // Increments the user login attempts or sets to 1 if frist wrong attempt
                if (!isset($_SESSION['attempts'])) {
                    $_SESSION['attempts'] = 1;
                } else {
                    $_SESSION['attempts']++;
                }

                // Sets the lockout time if login attempts exceed 5
                if ($_SESSION['attempts'] >= 5) {
                    $_SESSION['lockout'] = time() + 180; // Locks user out for 3 minutes for 5 failed attempts 
                }
                $error = true;

                log_login_attempt($username, 'failure', $_SERVER['REMOTE_ADDR'], $user_agent);
            }
        } else {
            $error = true;

            log_login_attempt($username, 'failure', $_SERVER['REMOTE_ADDR'], $user_agent);
        }
    }
}

function log_login_attempt($username, $status, $ip_address) {
    $db_hostname = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "sad";

    $db = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

    if (!$db) {
        echo "Connection failed!";
    }

   

    // Get current timestamp
    $timestamp = date('Y-m-d H:i:s');
    // Prepare the SQL query
    $sql = "INSERT INTO login_attempts (user_name, status, date, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)";
// Prepare the statement
    $statement = mysqli_prepare($db, $sql);
// Bind the parameters
    mysqli_stmt_bind_param($statement, "sssss", $username, $status, $timestamp, $ip_address, $user_agent);
// Execute the statement
    mysqli_stmt_execute($statement);
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


            <input type="hidden" name = "token" value="<?=$_SESSION['token']?>"><br>

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