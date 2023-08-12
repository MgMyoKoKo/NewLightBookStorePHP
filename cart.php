<!DOCTYPE html>
<html lang="en">

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the database
include 'database.php';



session_start();


$customerid = $_SESSION['customer_id'];

if (isset($_POST['add_to_cart'])) {
    $book_id = $_POST['book_id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    if (!isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id] = 0;
    }
    $_SESSION['cart'][$book_id]++;
}

// Remove item from cart
if (isset($_POST['remove_from_cart'])) {
    $book_id = $_POST['book_id'];
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]--;
        if ($_SESSION['cart'][$book_id] == 0) {
            unset($_SESSION['cart'][$book_id]);
        }
    }
}

// Update cart
if (isset($_POST['update_cart'])) {
    $quantities = $_POST['quantity'];
    foreach ($quantities as $book_id => $quantity) {
        if ($quantity == 0) {
            unset($_SESSION['cart'][$book_id]);
        } else {
            $_SESSION['cart'][$book_id] = $quantity;
        }
    }
}

// Continue shopping
if (isset($_POST['continue_shopping'])) {
    header('Location: books.php');
    exit();
}

if (isset($_POST['checkout'])) {
    header('Location: checkout.php');
    exit();
}

// Fetch cart items
$cart_items = array();
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $book_id => $quantity) {
        $stmt = $con->prepare("SELECT title,price,image FROM book WHERE book_id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->bind_result($title, $price, $image);
        $stmt->fetch();
        $stmt->close();
        $subtotal = $quantity * $price;
        $total_price += $subtotal;
        $cart_items[] = array(
            'image' => $image,
            'book_id' => $book_id,
            'title' => $title,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $subtotal
        );
    }
}


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
                            <a href="cart.php?customer_id=<?php echo $customerid; ?>"><i class="cart-icon fa fa-fw fa-cart-arrow-down text-dark mr-1 "></i></a>
                            <!-- <span class="cart-item position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"><?php echo $cartbookqty; ?></span> -->
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </nav>
    <!-- Close Header -->

    <!-- Start Cart Page -->
    <!-- Cart Section -->
    <br>
    <div class="site-section">
        <div class="container">
            <form class="col-md-12" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="row mb-5">

                    <div class="site-blocks-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Book</th>
                                    <th class="product-name">Name</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $cart_item) : ?>
                                    <tr>
                                        <td class="product-thumbnail">
                                            <?php echo '<img class="card-img rounded-0 img-fluid" src="bookimage/' . ($cart_item['image']) . '">'; ?>
                                        </td>
                                        <td class="product-name">
                                            <h2 class="h5 text-black"><?php echo $cart_item["title"]; ?></h2>
                                        </td>
                                        <td>$ <?php echo $cart_item["price"]; ?></td>
                                        <td><input type="number" class="input-group mb-3" name="quantity[<?php echo htmlspecialchars($cart_item['book_id']); ?>]" value="<?php echo htmlspecialchars($cart_item['quantity']); ?>"></td>
                                        <td>$<?php echo htmlspecialchars($cart_item['subtotal']); ?></td>
                                        <td>
                                            <input type="hidden" name="book_id" value="<?php echo $cart_item['book_id']; ?>">
                                            <button type="submit" class="btn btn-danger" name="remove_from_cart" value="<?php echo ($cart_item['book_id']); ?>">X</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>


                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row mb-12">
                            <div class="col-md-6">
                                <button type="submit" name="update_cart" class="btn btn-secondary">Update Cart</button>
                                <button type="submit" name="continue_shopping" class="btn btn-secondary">Continue Shopping</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 pl-5">
                        <div class="row justify-content-end">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12 text-right border-bottom mb-5">
                                        <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                    </div>
                                </div>
                                <div class="row mb-3">

                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <span class="text-black">Total</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <strong class="text-black">$
                                            <?php echo htmlspecialchars($total_price); ?>
                                        </strong>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary btn-lg py-3 btn-block" id="submit-button" type="submit" name="checkout">Proceed To Checkout</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br>
    <!--Close Cart Section-->
    <!-- End Cart -->



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
                        <li><a class="text-decoration-none" href="#">About Us</a></li>
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
    <script>
        const submitButton = document.getElementById('submit-button');

        submitButton.addEventListener('click', () => {
            if ($cart_items === null) {
                event.preventDefault(); // prevents the default submit action
                alert('There are no items in the cart.'); // displays an alert message
            }
        });
    </script>
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/thriller.js"></script>
    <!-- End Script -->
</body>

</html>