<?php 


header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past



session_start();
if(isset($_SESSION['username']) && $_SESSION['username'] === 'ADMIN') {

 include 'db.inc.php';
require_once 'functions.php';
  
  $sql = "SELECT * FROM login_attempts";
  $result = mysqli_query($db, $sql);
  
  if(mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Username</th><th>Status</th><th>Date/Time</th><th>IP Address</th><th>user agent</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
      $ip_address = $row['ip_address'];
     $user_agent = $_SERVER['HTTP_USER_AGENT'];
      // Convert IPv6 loopback address to its IPv4 equivalent
      if($ip_address == "::1"){
        $ip_address = "127.0.0.1";
      }
      echo "<tr><td>".$row['user_name']."</td><td>".$row['status']."</td><td>".$row['date']."</td><td>".$ip_address."</td><td>".$user_agent."</td></tr>";
    }
    echo "</table>";
  } else {
    echo "No login attempts found.";
  }

} else {
  echo "You are not authorized to access this page.";
}
?>
   