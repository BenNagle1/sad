<?php 
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();

if(isset($_SESSION['username']) && $_SESSION['username'] === 'ADMIN') {
    include 'tracker.php';
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
  <li><a href="login_attempts.php">Event Logs</a></li>
  <li><a href="details.php">Project Details</a></li>
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