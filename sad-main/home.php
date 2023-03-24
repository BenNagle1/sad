<?php 



header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

session_start(); 
include 'tracker.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}



?>
<html>
<script>
  window.onpageshow = function(event) {
    if (event.persisted) {
      window.location.reload();
    }
  };
</script>

<head>
<title>Welcome - Home</title>
 <link rel="stylesheet" type="text/css" href="nav_bar.css">
 <link rel="stylesheet" type="text/css" href="home.css">

</head>
<header>
<ul>
<div class="nav_bar">
  <li><a class="current" href="">Home</a></li>
  <li><a href="About_Us.php">About Us</a></li>
  <li><a href="change_password.php">Change Password</a></li>
  <li><a href="signout.php">Sign out</a></li>
  <li><a href="details.php">Project Details</a></li>
  </div>
</ul>
</header>

<body>

<main>
<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2><br>
<h3>You can click above Navagation bar buttons to</h3>
<h3>View project member information </h3>
<h3>Change password </h3>
<h3>Sign out </h3>
</main>

</body>

</html>