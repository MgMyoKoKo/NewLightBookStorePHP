<?php
$server = "localhost";
$username = "root";
$pwd = "";
$db = "new_light";
$con = new mysqli($server, $username, $pwd, $db);

if (!$con) {
    die('Could not Connect My Sql:' . mysqli_connect_errno());
}
?>