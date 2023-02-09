<?php 
session_start(); 
include "db.inc.php";

$username = " ";

if($_SERVER["REQUEST_METHOD"] == "POST") {
      
    $username = trim($_POST['username']);
}

?>
<html>

<head>
<title>Welcome - Home</title>
 <link rel="stylesheet" type="text/css" href="nav_bar.css">

</head>
<header>
<ul>
<div class="nav_bar">
  <li><a class="current" href="">Home</a></li>
  <li><a href="">Account</a></li>
  <li><a href="#">Signout</a></li>
  </div>
</ul>
</header>

<body>

<main>
   <h3>Welcome, <h3><?php echo "$username";?>

</main>

</body>

</html>
