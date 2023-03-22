<?php 
session_start(); 
include "db_conn.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {
 $user_agent = $_SERVER['HTTP_USER_AGENT'];
	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);

	if (empty($uname)) {
		header("Location: index.php?error=User Name is required");
	    exit();
	}else if(empty($pass)){
        header("Location: index.php?error=Password is required");
	    exit();
	}else{
		// hashing the password
        $pass = md5($pass);

		$sql = "SELECT * FROM users WHERE user_name='$uname' AND password='$pass'";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
		
    		$_SESSION['user_agent'] = $user_agent;
            if ($row['user_name'] === $uname && $row['password'] === $pass) {
            	$_SESSION['user_name'] = $row['user_name'];
            	$_SESSION['name'] = $row['name'];
            	$_SESSION['id'] = $row['id'];
            	// Successful login
            	log_login_attempt($uname, 'success', $_SERVER['REMOTE_ADDR']);
          //   	header("Location: home.php");
		        // exit();
		        // After successful login, check if the user is "ADMIN"
if ($_SESSION['user_name'] === 'ADMIN') {
    header("Location: login_attempts.php");
    exit();
}
else{header("Location: home.php");
		        exit();}

            }else{
				// Incorrect password
				log_login_attempt($uname, 'failure', $_SERVER['REMOTE_ADDR']);
				header("Location: index.php?error=Incorrect User name or password");
		        exit();
			}
		}else{
			// User not found
			log_login_attempt($uname, 'failure', $_SERVER['REMOTE_ADDR']);
			header("Location: index.php?error=Incorrect User name or password");
	        exit();
		}
	}
	
}else{
	header("Location: index.php");
	exit();
}

function log_login_attempt($uname, $status,$ip_address) {
	// Connect to the database
	//$conn = mysqli_connect('localhost', 'uname', 'password', 'database');

$sname= "localhost";
$unmae= "root";
$password = "";

$db_name = "test_db";

$conn = mysqli_connect($sname, $unmae, $password, $db_name);

if (!$conn) {
	echo "Connection failed!";
}
 	// Get current timestamp
    $timestamp = date('Y-m-d H:i:s');
	// Prepare the SQL query
	$sql = "INSERT INTO login_attempts (user_name, status, date, ip_address, user_agent) VALUES ('$uname', '$status', '$timestamp', '$ip_address', '$user_agent')";
    // Execute the query
	mysqli_query($conn, $sql);
	// Close the database connection
	mysqli_close($conn);
}
?>





