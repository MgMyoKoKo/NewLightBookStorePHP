<?php
// connect to database
include 'database.php';

// query to fetch books published in current month
$query = "SELECT * FROM author JOIN book ON author.author_id = book.author_id WHERE category_id = 10 OR category_id = 15";
$spotbookresult = mysqli_query($con, $query);
?>