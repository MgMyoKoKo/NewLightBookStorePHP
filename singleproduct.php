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

error_reporting(0);



// Start the session
session_start();
$cart_items = $_SESSION['cart_items'];

// check if add to cart button is clicked
if (isset($_POST['add_to_cart'])) {
    $book_id = $_POST['book_id'];

    // add book id to cart session array
    $_SESSION['cart'][$book_id] = 1;

    // redirect to cart page
    header('Location: cart.php');
    exit();
}


// Get the selected category from the query string
$bookid = $_GET['bookid'];
$categoryid = $_GET['categoryid'];

// Fetch the books of the selected category from the books table
$bookidquery = "SELECT b.book_id, b.title, b.isbn, b.num_pages, b.publication_date, b.price,b.image, b.description, c.category_name, a.author_name, l.language_name,p.publisher_name
FROM book AS b
JOIN categories AS c ON b.category_id = c.category_id
JOIN book_language AS l ON b.language_id = l.language_id
JOIN publisher AS p ON b.publisher_id = p.publisher_id
JOIN author AS a ON b.author_id = a.author_id
WHERE book_id = '$bookid'";
$bidsearchresult = mysqli_query($con, $bookidquery);

// Get the related book for carousel
$relatedbquery = "SELECT * FROM book AS b JOIN author AS a ON b.author_id = a.author_id WHERE category_id = $categoryid AND book_id <> $bookid LIMIT 5";
$rbsearchresult = mysqli_query($con, $relatedbquery);


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
} else {
    // user is not logged in, show login/signup options or restrict access to certain features
    echo '<style>.login-btn { display: block !important; }</style>';
    echo '<style>.signup-btn { display: block !important; }</style>';
    echo '<style>.signout-btn { display: none !important; }</style>';
    echo '<style>.cart-icon { display: none !important; }</style>';
    echo '<style>.user-icon { display: none !important; }</style>';
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
                    <a class="text-light" href="https://fb.com/templatemo" target="_blank" rel="sponsored"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
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
                            <span class="cart-item position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"></span>
                        </a>
                        
                    </div>
                </div>
            </div>

        </div>
    </nav>
    <!-- Close Header -->

    <!-- Open Content -->
    <section class="bg-light">
        <div class="container pb-5">
            <div class="row">
                <?php
                while ($row = mysqli_fetch_assoc($bidsearchresult)) {
                ?>
                    <div class="col-lg-5 mt-5">
                        <div class="card mb-3">
                            <?php echo '<img class="card-img img-fluid" src="bookimage/' . ($row['image']) . '">'; ?>
                        </div>
                        <div class="row">

                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-lg-7 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="h2"><?php echo $row["title"]; ?></h1>
                                <p class="h3 py-2">Price $: <?php echo $row["price"]; ?></p>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h6>Author:</h6>
                                    </li>
                                    <li class="list-inline-item">
                                        <p class="text-muted"><strong><?php echo $row["author_name"]; ?></strong></p>
                                    </li>
                                </ul>

                                <h6>About:</h6>
                                <p><?php echo $row["description"]; ?></p>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h6>Categories :</h6>
                                    </li>
                                    <li class="list-inline-item">
                                        <p class="text-muted"><strong><?php echo $row["category_name"]; ?></strong></p>
                                    </li>
                                </ul>

                                <h6>Book Specification:</h6>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h6>Print length :</h6>
                                    </li>
                                    <li class="list-inline-item">
                                        <p class="text-muted"><strong><?php echo $row["num_pages"]; ?></strong></p>
                                    </li>
                                </ul>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h6>Language :</h6>
                                    </li>
                                    <li class="list-inline-item">
                                        <p class="text-muted"><strong><?php echo $row["language_name"]; ?></strong></p>
                                    </li>
                                </ul>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h6>Publisher :</h6>
                                    </li>
                                    <li class="list-inline-item">
                                        <p class="text-muted"><strong><?php echo $row["publisher_name"]; ?></strong></p>
                                    </li>
                                </ul>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h6>Publication date :</h6>
                                    </li>
                                    <li class="list-inline-item">
                                        <p class="text-muted"><strong><?php echo $row["publication_date"]; ?></strong></p>
                                    </li>
                                </ul>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h6>ISBN :</h6>
                                    </li>
                                    <li class="list-inline-item">
                                        <p class="text-muted"><strong><?php echo $row["isbn"]; ?></strong></p>
                                    </li>
                                </ul>

                                <form method="POST">
                                    <input type="hidden" name="book_id" value="<?php echo $row["book_id"]; ?>">
                                    <div class="row">
                                        <div class="col-auto">
                                            <ul class="list-inline pb-3">
                                                <li class="list-inline-item text-right">

                                                    <input type="hidden" name="book_qty" id="quantity" value="1">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row pb-3">
                                        <div class="col d-grid">
                                            <a href=""><button type="submit" class="btn btn-success btn-lg" name="add_to_cart">Add To Cart</button></a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Close Content -->

    <!-- Start Article -->
    <section class="">

        <div class="row text-left p-2 pb-3">
            <h4>Related Books</h4>
        </div>

        <!--Start Carousel Wrapper-->
        <div id="carousel-related-product" class="px-2">
            <div class="row">
                <?php
                while ($row = mysqli_fetch_assoc($rbsearchresult)) {
                ?>
                    <div class="col-md-3 px-1">
                        <div class="row">
                            <div class="card rounded-0 h-10">
                                <div class="card rounded-0">
                                    <?php echo '<img class="card-img rounded-0 img-fluid" src="bookimage/' . ($row['image']) . '">'; ?>
                                    <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                        <ul class="list-unstyled">
                                            <?php
                                            echo '<li><a class="btn btn-success text-white mt-2" href="singleproduct.php?bookid=' . $row["book_id"] . '&& categoryid=' . $row["category_id"] . '"><i class="fas fa-cart-plus"></i></a></li>';
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="shop-single.html" class="h3 text-decoration-none"><?php echo $row["title"]; ?></a>
                                    <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                        <li>by <?php echo $row["author_name"]; ?></li>
                                    </ul>
                                    <p class="text-center mb-0">Price $: <?php echo $row["price"]; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
    <!-- End Article -->

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
                    <label class="sr-only" for="subscribeEmail">Email address</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control bg-dark border-light" id="subscribeEmail" placeholder="Email address">
                        <div class="input-group-text btn-success text-light">Subscribe</div>
                    </div>
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
    <!-- <script src="assets/js/templatemo.js"></script> -->
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/thriller.js"></script>
    <!-- End Script -->

    <!-- Start Slider Script -->
    <script src="assets/js/slick.min.js"></script>
    <script>
        $('#carousel-related-product').slick({
            infinite: true,
            arrows: false,
            slidesToShow: 2,
            slidesToScroll: 2,
            dots: false,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                }
            ]
        });
    </script>
    <!-- End Slider Script -->
</body>

</html>