<?php
include 'database.php';
session_start();

// Get the customer's ID from the session
$customer_id = $_SESSION['customer_id'];

// Update the customer's record in the database with the logout time
$logout_time = date('Y-m-d H:i:s'); // Get the current time
$sql = "UPDATE cart SET logout_time='$logout_time' WHERE customer_id=$customer_id";
$result = mysqli_query($con, $sql);

// Destroy the session and redirect back to the login page
session_destroy();
header('Location: index.php');
?>