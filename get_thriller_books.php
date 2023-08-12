<!DOCTYPE html>
<html lang="en">

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
include 'database.php';

//Fetch thriller books
$genre = "3";

// Build the SQL query
$thquery = "SELECT * FROM author JOIN book ON author.author_id = book.author_id WHERE category_id= '$genre'";


// Execute the query
$thresult = mysqli_query($con, $thquery);

error_reporting(0);

session_start();

if (isset($_SESSION['customer_id'])) {
    $customerid = $_SESSION['customer_id'];
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

    <!-- Load map styles -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />


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
                <div>
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="books.php">Books</a>
                        </li>
                        <li class="nav-item dropdown style=" z-index: 10">
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
                            <span class="cart-item position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"></span>
                        </a>
                        <a class="nav-icon position-relative text-decoration-none" href="#">
                            <i class="user-icon fa fa-fw fa-user text-dark mr-3"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </nav>
    <!-- Close Header -->
    <div class="row">
        <?php
        while ($row = mysqli_fetch_assoc($thresult)) {
        ?>
            <div class="col-12 col-md-2 mb-3 px-4">
                <div class="card h-100">
                    <br>
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
        } ?>
    </div>

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