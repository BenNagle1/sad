<?php 
session_start();

if(isset($_SESSION['username']) && $_SESSION['username'] === 'ADMIN') {
    include 'tracker.php';
?>
<html>

<head>
<title>Welcome - Home</title>
 <link rel="stylesheet" type="text/css" href="nav_bar.css">
 <link rel="stylesheet" type="text/css" href="home.css">

</head>
<header>
<ul>
<div class="nav_bar">
  <li><a class="current" href="">Home</a></li>
  <li><a href="change_password.php">Change Password</a></li>
  <li><a href="login_attempts.php">Event Logs</a></li>
  <li><a href="signout.php">Sign out</a></li>
  </div>
</ul>
</header>

<body>

<main>
<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

</main>

</body>

</html>

<?php
} else {
    header('Location: login.php');
}
?>