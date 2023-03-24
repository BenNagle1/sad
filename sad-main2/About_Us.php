<?php 
session_start(); 
include 'tracker.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}



?>
<html>

<head>
<title>About Us</title>
 <link rel="stylesheet" type="text/css" href="nav_bar.css">
 <link rel="stylesheet" type="text/css" href="home.css">

</head>
<header>
<ul>
<div class="nav_bar">
  <li><a href="home.php">Home</a></li>
  <li><a class="current" href="">About Us</a></li>
  <li><a href="change_password.php">Change Password</a></li>
  <li><a href="signout.php">Sign out</a></li>
  </div>
</ul>
</header>

<body>

<main>
<h2>About Us</h2>

</main>

</body>

</html>