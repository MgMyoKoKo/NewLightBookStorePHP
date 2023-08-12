<?php
// Define database connection variables
include 'database.php';


// Set current month and year
$current_month = date('F');
$current_year = date('Y');

// Set pagination variables
$results_per_page = 8;
$page_number = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page_number - 1) * $results_per_page;

// Get total number of books published in current month
$sql = "SELECT COUNT(*) AS total_books FROM book WHERE MONTH(publication_date) = MONTH(CURDATE()) AND YEAR(publication_date) = YEAR(CURDATE())";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$total_books = $row['total_books'];

// Calculate total number of pages
$total_pages = ceil($total_books / $results_per_page);

// Get books published in current month with pagination
$sql = "SELECT * FROM book WHERE MONTH(publication_date) = MONTH(CURDATE()) AND YEAR(publication_date) = YEAR(CURDATE()) LIMIT $offset, $results_per_page";
$result = mysqli_query($con, $sql);


?>
