<?php 
session_start(); 

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
  <li><a href="">Account</a></li>
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
