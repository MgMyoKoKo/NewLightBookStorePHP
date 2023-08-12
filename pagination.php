<?php
// Connect to the database
include 'database.php';

// Set the number of records per page
$records_per_page = 10;

// Get the current page number from the URL
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

// Calculate the starting record number
$offset = ($page - 1) * $records_per_page;

// Fetch the categories from the categories table
$query = "SELECT * FROM categories";
$result = mysqli_query($con, $query);

// Build an array of category links
$categories = array();
while ($row = mysqli_fetch_assoc($result)) {
    $category_id = $row['category_id'];
    $category_name = $row['category_name'];
    $categories[] = "<a class='dropdown-item' href='?category_id=$category_id'>$category_name</a>";
}

// Check if a category was selected
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Count the total number of records for the selected category
    $query = "SELECT COUNT(*) as total_records FROM book WHERE category_id=$category_id";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $total_records = $row['total_records'];

    // Calculate the total number of pages
    $total_pages = ceil($total_records / $records_per_page);

    // Fetch the books for the selected category, limited by the pagination variables
    $query = "SELECT * FROM book WHERE category_id=$category_id LIMIT $records_per_page OFFSET $offset";
    $result = mysqli_query($con, $query);
}

?>

<!-- Display the book records -->

<!-- Display the pagination links -->
