<?php
// Establish database connection
include 'database.php';

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Set pagination variables
$results_per_page = 8;
$spotcurrentpage = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate offset
$offset = ($spotcurrentpage - 1) * $results_per_page;

// Get total number of results
$sql_total = "SELECT COUNT(*) as total FROM author JOIN book ON author.author_id = book.author_id WHERE category_id = 10 AND category_id = 15";
$result_total = $con->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_results = $row_total['total'];

// Calculate total number of pages
$spottotal_pages = ceil($total_results / $results_per_page);

// Get results for current page
$sql = "SELECT * FROM author JOIN book ON author.author_id = book.author_id WHERE category_id = 10 AND category_id = 15 LIMIT $results_per_page OFFSET $offset";
$result = $con->query($sql);
?>
