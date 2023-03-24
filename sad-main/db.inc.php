<?php

$db_hostname = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "sad";

require_once 'functions.php';


$db = mysqli_connect($db_hostname, $db_username, $db_password);

if (!$db) {
    die("Connection to database failed: " . mysqli_connect_error());
}


$create_db_sql = "CREATE DATABASE IF NOT EXISTS $db_name";

if (!$db->query($create_db_sql)) {
    die("Error creating database: " . $db->error);
}

mysqli_select_db($db, $db_name);

$userTable_name = "users";
$userTable_check = "SHOW TABLES LIKE '$userTable_name'";
$userTable_result = mysqli_query($db, $userTable_check);

if (mysqli_num_rows($userTable_result) == 0) {
    $userTable_create = "CREATE TABLE IF NOT EXISTS `$userTable_name` (
        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(255) NOT NULL UNIQUE,
        `salt` VARCHAR(255) NOT NULL,
        `hashed_password` VARCHAR(255) NOT NULL,
        `isAdmin` BOOLEAN NOT NULL DEFAULT 0
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";


if (mysqli_query($db, $userTable_create)) {

$username = "ADMIN";
$password = "SaD_2023!";
$salt_length = strlen($password);
$salt = generateSalt($salt_length);
$hashed_password = hash_password($password, $salt);
$hashed_password = mysqli_real_escape_string($db, $hashed_password);
$sql = "INSERT INTO $userTable_name (username, salt, hashed_password, isAdmin) VALUES (?, ?, ?, ?)";
$statement = mysqli_prepare($db, $sql);
$is_admin = 1;
mysqli_stmt_bind_param($statement, 'sssi', $username, $salt, $hashed_password, $is_admin);
if (!mysqli_stmt_execute($statement)) {
die("Error inserting admin user: " . mysqli_error($db));
}
} else {
die("Error creating users table: " . mysqli_error($db));
}

    
}

$logTable_name = "login_attempts";
$logTable_check = "SHOW TABLES LIKE '$logTable_name'";
$logTable_result = mysqli_query($db, $logTable_check);

if (mysqli_num_rows($logTable_result) == 0) {
    $logTable_create = "CREATE TABLE $logTable_name (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_name` varchar(25) NOT NULL,
        `status` enum('success', 'failure') NOT NULL,
        `date` timestamp NOT NULL,
        `ip_address` varchar(45) NOT NULL,
        `user_agent` varchar(45) NOT NULL,
        PRIMARY KEY (`id`)
      )";

    if (!mysqli_query($db, $logTable_create)) {
        die("Error creating table: " . mysqli_error($db));
    }
}


?>