<?php 
session_start();
if(isset($_SESSION['user_name']) && $_SESSION['user_name'] === 'ADMIN') {

  include "db_conn.php";
  
  $sql = "SELECT * FROM login_attempts";
  $result = mysqli_query($conn, $sql);
  
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
