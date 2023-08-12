<?php
// connect to database
include 'database.php';

// get current month
$current_month = date('m');
$currentYear = date('Y');
$current_bookmonth = date('F');
$current_bookyear = date("Y");


// query to fetch books published in current month
$query = "SELECT * FROM author JOIN book ON author.author_id = book.author_id WHERE MONTH(publication_date) = '$current_month' AND YEAR(publication_date) = '$currentYear'";
$currentbookresult = mysqli_query($con, $query);
?>
