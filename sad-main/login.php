<?php
include 'db.inc.php';
require_once 'functions.php';

session_start();
error_reporting(E_ERROR);

$error = false;
$_SESSION['token'] = get_random_string(60);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['token']) && isset($_POST['token']) && isset($_SESSION['token']) == isset($_POST['token']) ) {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (isset($_SESSION['lockout']) && time() < $_SESSION['lockout']) {
        $error = true;
    } else {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $sql = "SELECT id, salt, hashed_password FROM users WHERE username=?";
        $statement = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($statement, "s", $username);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $salt = $user['salt'];
            $hashed_password = $user['hashed_password'];
            $hash_enteredPassword = hash_password($password, $salt);

            if ($hashed_password == $hash_enteredPassword) {
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $user['id'];
                unset($_SESSION['attempts']);
                unset($_SESSION['lockout']);

                log_login_attempt($username, 'success', $_SERVER['REMOTE_ADDR'], $user_agent);

                if ($username === 'ADMIN') {
                    header("Location: login_attempts.php");
                } else {
                    header("Location: home.php");
                }
                exit();

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
    $sql = "INSERT INTO login_attempts (user_name, status, date, ip_address, user_agent) VALUES ('$username', '$status', '$timestamp', '$ip_address', '$user_agent')";
    // Execute the query
    mysqli_query($db, $sql);
    // Close the database connection
    mysqli_close($db);
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