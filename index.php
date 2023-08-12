<!DOCTYPE html>
<html lang="en">

<?php
// Connect to the database
include 'database.php';
include 'pagination.php';
include 'currentmonthbook.php';
include 'monthpagination.php';
include 'spotlightbook.php';
include 'spotbookpagination.php';

$carsql = "SELECT MIN(version_number) AS latest_version FROM carousel";
$carresult = mysqli_query($con, $carsql);
$row = mysqli_fetch_assoc($carresult);
$latest_version = $row['latest_version'];

// Query to fetch the carousel data for the latest version
$carsql2 = "SELECT * FROM carousel WHERE version_number = '$latest_version'";
$carresult2 = mysqli_query($con, $carsql2);

error_reporting(0);

session_start();

if (isset($_SESSION['customer_id'])) {
    $customerid = $_SESSION['customer_id'];
    $login_time = time();
    $customerquery = "SELECT * FROM  customer WHERE $customerid=customer_id";
    $customeresult = mysqli_query($con, $customerquery);
    $customername = mysqli_fetch_assoc($customeresult);
    $welcometext = 'Hello Welcome ';
    echo '<style>.login-btn { display: none !important; }</style>';
    echo '<style>.signup-btn { display: none !important; }</style>';
    echo '<style>.signout-btn { display: block !important; }</style>';
    echo '<style>.cart-icon { display: block !important; }</style>';
    echo '<style>.user-icon { display: block !important; }</style>';
    echo '<style>.cart-item { display: block !important; }</style>';
} else {
    // user is not logged in, show login/signup options or restrict access to certain features
    echo '<style>.login-btn { display: block !important; }</style>';
    echo '<style>.signup-btn { display: block !important; }</style>';
    echo '<style>.signout-btn { display: none !important; }</style>';
    echo '<style>.cart-icon { display: none !important; }</style>';
    echo '<style>.user-icon { display: none !important; }</style>';
    echo '<style>.cart-item { display: none !important; }</style>';
}
// Fetch the categories from the books table
$query = "SELECT DISTINCT category_name FROM categories";
$result = mysqli_query($con, $query);

// Build an array of category links
$categories = array();
while ($row = mysqli_fetch_assoc($result)) {
    $category = $row['category_name'];
    $categories[] = "<a class='dropdown-item' href='category.php?category=$category'>$category</a>";
}

// Close the database connection
mysqli_close($con);
?>

<head>
    <title>NewLight Online Bookstore</title>
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

<body>
    <!-- Start Top Nav -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <i class="fa fa-envelope mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="mailto:newlightbooks@gmail.com">newlightbooks@gmail.com</a>
                    <i class="fa fa-phone mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="tel:095-920-0340">095-920-0340</a>
                </div>
                <div>
                    <a class="text-light" href="https://fb.com/" target="_blank"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://twitter.com/" target="_blank"><i class="fab fa-twitter fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin fa-sm fa-fw"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand text-success logo h1 align-self-center" href="index.php">
                <img src="assets/img/logo.png">
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav" style="z-index: 10;">
                <div class="">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="books.php">Books</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> Categories </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"> <?php echo implode('', $categories); ?></a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aboutus.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contactus.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex justify-content-lg-between px-5">
                    <div class="navbar align-self-center d-flex">
                        <a href="login.php" class="login-btn btn btn-outline-primary me-2">Login</a>
                        <a href="singup.php" class="signup-btn btn btn-outline-primary me-2">SignUp</a>
                        <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ...">
                                <div class="input-group-text">
                                    <i class="fa fa-fw fa-search"></i>
                                </div>
                            </div>
                        </div>
                        <a class="nav-icon d-none d-lg-inline" href="booksearch.php">
                            <i class="fa fa-fw fa-search text-dark mr-2"></i>
                        </a>
                    </div>
                    <div class="align-self-center d-flex px-2">
                        <p><?php echo $welcometext . $customername['first_name']; ?></p>
                    </div>
                    <div class="navbar align-self-center d-flex">
                        <form method="post" action="process_logout.php">
                            <input class="signout-btn btn btn-outline-primary" type="submit" value="Sign Out">
                            <!-- <a href="process_logout.php" class="signout-btn btn btn-outline-primary">Sign Out</a> -->
                        </form>
                        <a class="nav-icon position-relative text-decoration-none px-3" href="#">
                            <a href="cart.php?customer_id=<?php echo $customerid; ?>&login_time=<?php echo $login_time; ?>"><i class="cart-icon fa fa-fw fa-cart-arrow-down text-dark mr-1 "></i></a>
                            <!-- <i class="cart-icon fa fa-fw fa-cart-arrow-down text-dark mr-1 "></i> -->
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </nav>
    <!-- Close Header -->






    <!-- Start Banner Hero -->
    <div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel" style="z-index: 0;">
        <ol class="carousel-indicators">
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="1"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <?php
            // Loop through the carousel data and display the slides
            $active = "active"; // Add 'active' class to the first slide
            while ($row = mysqli_fetch_assoc($carresult2)) {
                // Get the carousel data for each slide
                $title = $row['title'];
                $author_name = $row['author_name'];
                $image = $row['image'];
                $description = $row['description'];

                echo '<div class="carousel-item ' . $active . '">
                        <div class="container">
                            <div class="row p-5">
                                <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                                    <img class="img-fluid" src="./assets/carouselimage/' . $image . '" alt="">
                                </div>
                                <div class="col-lg-6 mb-0 d-flex align-items-center">
                                    <div class="text-align-left align-self-center">
                                        <h1 class="h1 text-success"><b>#</b> ' . $title . '</h1>
                                        <h3 class="h2">' . $author_name . '</h3>
                                        <p>' . $description . '</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                $active = ""; // Remove 'active' class from the next slide
            }
            ?>
        </div>
        <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="prev">
            <i class="fas fa-chevron-left"></i>
        </a>
        <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="next">
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>
    <!-- End Banner Hero -->


    <!-- Start Categories of The Month -->
    <section class="container py-5">
        <div class="row text-center pt-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Categories of The Month</h1>
                <p>
                    Our top books in one spot
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4 p-5 mt-3">
                <a href="#"><img src="./assets/img/Suspense-Thriller-Book-Cover-The-Shadows-of-Summer.jpg" class="rounded-circle img-fluid border"></a>
                <h5 class="text-center mt-3 mb-3">Thriller</h5>
                <form action="get_thriller_books.php">
                    <p class="text-center"><button type="submit" class="btn btn-success">Check Listing</button></p>
                </form>
            </div>

            <div class="col-12 col-md-4 p-5 mt-3">
                <a href="#"><img src="./assets/img/4f971bfe-2ea6-4ff7-8c5a-7eba039fa15c.jpg" class="rounded-circle img-fluid border"></a>
                <h2 class="h5 text-center mt-3 mb-3">Mystery</h2>
                <form action="get_mystery_books.php">
                    <p class="text-center"><button type="submit" class="btn btn-success">Check Listing</button></p>
                </form>
            </div>
            <div class="col-12 col-md-4 p-5 mt-3">
                <a href="#"><img src="./assets/img/libertie.jpg" class="rounded-circle img-fluid border"></a>
                <h2 class="h5 text-center mt-3 mb-3">Literary Fiction</h2>
                <form action="get_lfiction_books.php">
                    <p class="text-center"><button type="submit" class="btn btn-success">Check Listing</button></p>
                </form>
            </div>
        </div>
    </section>
    <!-- End Categories of The Month -->


    <!-- Start Feb2023 Product -->
    <section class="bg-light">
        <div class="container py-5">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1"><?php echo $current_bookmonth . "  " . $current_year; ?></h1>
                    <p>
                        Discover the latest books including trending new book releases in Current Month
                    </p>
                </div>
            </div>
            <div class="row">
                <?php
                while ($row = mysqli_fetch_assoc($currentbookresult)) {
                ?>
                    <div class="col-12 col-md-3 mb-3">
                        <div class="card h-100">
                            <a href="shop-single.html">
                                <?php echo '<img class="card-img-top" src="bookimage/' . ($row['image']) . '">'; ?>
                                <?php

                                if (isset($_SESSION['customer_id'])) {
                                    // user is logged in, allow them to add to cart
                                    echo '<a class="btn btn-success text-white mt-2" href="singleproduct.php?bookid=' . $row["book_id"] . '&& categoryid=' . $row["category_id"] . '"><i class="fas fa-cart-plus"></i></a>';
                                } else {
                                    // user is not logged in, show a message
                                    echo '<a class="btn btn-success text-white mt-2" href="login.php" onclick="alert(\'Please login first.\')"><i class="fas fa-cart-plus"></i></a>';
                                }
                                ?>
                            </a>
                            <div class="card-body">
                                <ul class="list-unstyled d-flex justify-content-between">
                                    <li class="text-muted text-right">Price $<?php echo $row["price"]; ?></li>
                                </ul>
                                <a class="h4 text-decoration-none text-dark">Title : <?php echo $row["title"]; ?></a> <br>
                                <a><span style="color: blue; font-weight: bold;">By Author: <?php echo $row["author_name"]; ?></a>

                                <p class="card-text">
                                <p>Description : </p> <?php echo $row["description"]; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>

            <div div="row">
                <?php
                // Display books with pagination links
                if (mysqli_num_rows($result) > 1) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<p>{$row['title']}</p>";
                    }

                    // Generate pagination links
                    echo "<div class='pagination'>";
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active = ($i == $page_number) ? "active" : "";
                        echo "<a href='?page=$i' class='$active'>$i</a>";
                    }
                    echo "</div>";
                } else {
                    echo "No books found.";
                }
                ?>
            </div>
        </div>
    </section>
    <!-- End Feb2023 Product -->

    <!-- Start Spot Light Book Product -->
    <section class="bg-light">
        <div class="container py-5">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">Spotlight</h1>
                    <p>
                        Discover the Spotlight books
                    </p>
                </div>
            </div>
            <div class="row">
                <?php
                while ($row = mysqli_fetch_assoc($spotbookresult)) {
                ?>
                    <div class="col-12 col-md-3 mb-3">
                        <div class="card h-100">

                            <?php echo '<img class="card-img-top" src="bookimage/' . ($row['image']) . '">'; ?>


                            </a>
                            <div class="card-body">
                                <ul class="list-unstyled d-flex justify-content-between">
                                    <?php

                                    if (isset($_SESSION['customer_id'])) {
                                        // user is logged in, allow them to add to cart
                                        echo '<li><a class="btn btn-success text-white mt-2" href="singleproduct.php?bookid=' . $row["book_id"] . '&& categoryid=' . $row["category_id"] . '"><i class="fas fa-cart-plus"></i></a></li>';
                                    } else {
                                        // user is not logged in, show a message
                                        echo '<li><a class="btn btn-success text-white mt-2" href="login.php" onclick="alert(\'Please login first.\')"><i class="fas fa-cart-plus"></i></a></li>';
                                    }
                                    ?>
                                    <li class="text-muted text-right">Price $<?php echo $row["price"]; ?></li>
                                </ul>
                                <a class="h4 text-decoration-none text-dark"><?php echo $row["title"]; ?></a> <br> <br>
                                <a style="color: blue; font-weight: bold;">By Author :</a><?php echo $row["author_name"]; ?> <br>
                                <p class="card-text">
                                    <br>
                                    <a>Description : </a> <?php echo $row["description"]; ?>
                                </p>

                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>

            <div div="row">
                <?php
                // Display pagination links
                echo "<br>";
                if ($spottotal_pages > 1) {
                    echo "<ul>";
                    for ($i = 1; $i <= $spottotal_pages; $i++) {
                        $active = ($i == $spotcurrentpage) ? "active" : "";
                        echo "<li class='$active','page-item'><a class='page-link' href='index.php?page=$i'>$i</a></li>";
                    }
                    echo "</ul>";
                }
                ?>
            </div>
        </div>
    </section>
    <!-- End Spot Light Product -->

    <!-- Start Footer -->
    <footer class="bg-dark" id="tempaltemo_footer">
        <div class="container">
            <div class="row">

                <div class="col-md-4 pt-5">
                    <h2 class="h2 text-success border-bottom pb-3 border-light logo">NewLight Bookstore</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li>
                            <i class="fas fa-map-marker-alt fa-fw"></i>
                            111 Aungmingalar Road at Yangon 10610
                        </li>
                        <li>
                            <i class="fa fa-phone fa-fw"></i>
                            <a class="text-decoration-none" href="tel:095-920-0340">095-920-0340</a>
                        </li>
                        <li>
                            <i class="fa fa-envelope fa-fw"></i>
                            <a class="text-decoration-none" href="mailto:newlightbooks@gmail.com">newlightbooks@gmail.com</a>
                        </li>
                    </ul>
                </div>



                <div class="col-md-4 pt-5">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">Further Info</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li><a class="text-decoration-none" href="index.php">Home</a></li>
                        <li><a class="text-decoration-none" href="aboutus.php">About Us</a></li>
                        <li><a class="text-decoration-none" href="contactus.php">Shop Locations</a></li>
                        <li><a class="text-decoration-none" href="contactus.php">Contact</a></li>
                    </ul>
                </div>

            </div>

            <div class="row text-light mb-4">
                <div class="col-12 mb-3">
                    <div class="w-100 my-3 border-top border-light"></div>
                </div>
                <div class="col-auto me-auto">
                    <ul class="list-inline text-left footer-icons">
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="http://facebook.com/"><i class="fab fa-facebook-f fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://www.instagram.com/"><i class="fab fa-instagram fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://twitter.com/"><i class="fab fa-twitter fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://www.linkedin.com/"><i class="fab fa-linkedin fa-lg fa-fw"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-auto">
                    <form method="post" action="subscribeaction.php">
                        <label class="sr-only" for="subscribeEmail">Email address</label>
                        <div class="input-group mb-2">
                            <input type="text" name="subemail" class="form-control bg-white border-light" id="subscribeEmail" placeholder="Email address">
                            <button type="submit" name="sub" class="btn-success text-light">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="w-100 bg-black py-3">
            <div class="container">
                <div class="row pt-2">
                    <div class="col-12">
                        <p class="text-left text-light">
                            Copyright &copy; 2023 NewLight Bookstore
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </footer>
    <!-- End Footer -->


    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/thriller.js"></script>
    <!-- End Script -->


</body>

</html>