<!DOCTYPE html>
<html lang="en">

<?php
include 'database.php';
// check if form submitted
if (isset($_POST['submit'])) {

    // get search query
    $search_query = $_POST['search'];

    // build SQL query with search condition


}

?>



<head>

    <head>
        <title>NewLight Online Book Search</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/templatemo.css">
        <link rel="stylesheet" href="assets/css/custom.css">

        <!-- Load fonts style after rendering the layout styles -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
        <link rel="stylesheet" href="assets/css/fontawesome.min.css">

    </head>
</head>

<body>
    <div class="w-100 pt-1 mb-5 text-right">
        <a href="index.php">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </a>
    </div>
    <div class="col-lg-6">
        <form action="" method="post" class="modal-content modal-body border-0 p-0">
            <div class="input-group mb-2">
                <input type="text" class="form-control" id="inputModalSearch" name="search" placeholder="Search by author, title, or ISBN...">
                <button type="submit" name="submit" class="input-group-text bg-success text-light">
                    <i class="fa fa-fw fa-search text-white"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="col-lg-9">
        <div class="row">
            <?php
            include 'database.php';
            // check if form submitted
            if (isset($_POST['submit'])) {

                // get search query
                $search_query = $_POST['search'];

                // build SQL query with search condition
                $booksearchquery = "SELECT book.price,book.image,book.book_id,book.title, author.author_name, book.ISBN
FROM book
JOIN author ON book.author_id = author.author_id
WHERE author.author_name LIKE '%$search_query%' OR book.title LIKE '%$search_query%' OR book.ISBN LIKE '%$search_query%'";

                $result = mysqli_query($con, $booksearchquery);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {

            ?>
                        <div class="col-md-4">
                            <div class="card mb-4 product-wap rounded-0">
                                <div class="card rounded-0">
                                    <?php echo '<img class="card-img rounded-0 img-fluid" src="bookimage/' . ($row['image']) . '">'; ?>
                                    <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                        <ul class="list-unstyled">
                                            <?php
                                            if (isset($_SESSION['customer_id'])) {
                                                // user is logged in, allow them to add to cart
                                                echo '<li><a class="btn btn-success text-white mt-2" href="singleproduct.php?bookid=' . $row["book_id"] . '&& categoryid=' . $row["category_id"] . '"><i class="fas fa-cart-plus"></i></a></li>';
                                            } else {
                                                // user is not logged in, show a message
                                                echo '<li><a class="btn btn-success text-white mt-2" href="login.php" onclick="alert(\'Please login first.\')"><i class="fas fa-cart-plus"></i></a></li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="shop-single.html" class="h3 text-decoration-none"><?php echo $row["title"]; ?></a>
                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                        <li>by <?php echo $row["author_name"]; ?></li>
                                        <li class="pt-2">
                                            <span class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                            <span class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                            <span class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                            <span class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                            <span class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                        </li>
                                    </ul>
                                    <ul class="list-unstyled d-flex justify-content-center mb-1">

                                    </ul>
                                    <p class="text-center mb-0">Price $ <?php echo $row["price"]; ?></p>
                                </div>
                            </div>
                        </div>
            <?php

                    }
                } else {

                    // display no results found message
                    echo '<script type="text/javascript"> 
        window.alert("No Book Found")</script>';
                }
            }

            ?>
        </div>
    </div>

</body>

</html>