<?php
$db_name = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "sad";

$db = mysqli_connect("localhost", $db_username, $db_password);

if (!$db) {
    die("Connection to databse failed: " . mysqli_connect_error());
}

if (!mysqli_select_db($db, $db_name)) {
    $sql = "CREATE DATABASE $db_name";
    
}

$table_name = "users";
$table_check = "SHOW TABLES LIKE '$table_name'";
$table_result = mysqli_query($db, $table_check);

if (mysqli_num_rows($table_result) == 0) {
    $table_create = "CREATE TABLE $table_name (
        `id` int(11) NOT NULL,
        `username` varchar(25) NOT NULL,
        `salt` varchar(66) NOT NULL,
        `hashed_password` varchar(66) NOT NULL,
        `isAdmin` tinyint(4) NOT NULL DEFAULT 0
      ) ";

}

?>
