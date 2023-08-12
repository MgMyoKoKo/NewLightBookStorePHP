<!DOCTYPE html>
<html lang="en">

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('tcpdf/tcpdf.php');
require 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
include 'database.php';

// error_reporting(0);

session_start();

$customer_id = $_SESSION['customer_id'];



// confirm Checkout
// if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // validate form data
    $fname = ($_POST["fname"]);
    $lname = ($_POST["lname"]);
    $company = ($_POST["company"]);
    $address = ($_POST["shipping_address"]);
    $email = ($_POST["email"]);
    $phone = ($_POST["phone"]);
    $notes = ($_POST["notes"]);
    $payment = $_FILES["payment_slip"]["name"];



    //////////////////// handle file upload

    if (!empty($_FILES["payment_slip"]["name"])) {
        $target_dir = "assets/paymentslip/";
        $target_file = $target_dir . basename($_FILES["payment_slip"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check file size
        if ($_FILES["payment_slip"]["size"] > 500000) {
            $uploadOk = 0;
        }
        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if (move_uploaded_file($_FILES["payment_slip"]["tmp_name"], $target_file)) {

            echo "<script>alert('Your file " . $payment . " has been successfully uploaded');</script>";
        }
    }

    /////////////////////////////////////////////////////////////////////////

    // insert order data into cust_order table
    $order_query = "INSERT INTO cust_order (order_date, customer_id, company_name, shipping_address, payment_slip, phone, notes) VALUES (NOW(), ?, ?, ?, ?, ?,?)";
    $stmt = $con->prepare($order_query);
    $stmt->bind_param("isssss", $customer_id, $company, $address, $payment, $phone, $notes);
    $stmt->execute();

    // get the order_id of the newly inserted record
    $order_id = $stmt->insert_id;

    ////////insert order_id and invoice_id into invoices_and_orders table

    $invoice_query = "INSERT INTO invoices_and_orders (order_id, invoice_id, issue_date) VALUES (?, ?, NOW())";
    $stmt = $con->prepare($invoice_query);
    $stmt->bind_param("ii", $order_id, $order_id);
    $stmt->execute();

    ////////////////////

    // insert cart data into cart table
    $cart_query = "INSERT INTO cart (customer_id, book_id, quantity, subtotal, order_id) VALUES (?, ?, ?, ?, ?)";
    foreach ($_SESSION["cart"] as $book_id => $quantity) {
        // fetch book details from book table
        $book_query = "SELECT * FROM book WHERE book_id = ?";
        $stmt = $con->prepare($book_query);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $subtotal = $quantity * $row["price"];
        $stmt = $con->prepare($cart_query);
        $stmt->bind_param("iiiii", $customer_id, $book_id, $quantity, $subtotal, $order_id);
        $stmt->execute();
    }
    echo '<script type="text/javascript"> 
        window.alert("Order Successful. We will send invoice to your given email address.Thank You !");
        setTimeout(function() {
            window.location.href = "index.php";
        }, 100); // wait for 0.5 seconds before redirecting
      </script>';

    /// Send notification e-mail message to company e-mail address
    // Send email notification to newlightbookstore@gmail.com


    $mail = new PHPMailer;

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.mailgun.org';
    $mail->SMTPAuth = true;
    $mail->Username = 'postmaster@sandboxe828c1dcc50144198e695416eff673c9.mailgun.org';
    $mail->Password = '76a961d4473466529944d14b8e5669e2-d51642fa-2d50d17d';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Email content
    $order_date = date("Y-m-d H:i:s");

    $customer_query = "SELECT first_name, last_name FROM customer WHERE customer_id = ?";
    $stmt = $con->prepare($customer_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $customer_name = $row['first_name'] . ' ' . $row['last_name'];

    $book_query = "SELECT book.title, cart.quantity, book.price 
FROM cart 
JOIN book ON cart.book_id = book.book_id 
JOIN `cust_order` ON cart.order_id = `cust_order`.order_id 
WHERE `cart`.order_id = ?";
    $stmt = $con->prepare($book_query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book_rows = $result->fetch_all(MYSQLI_ASSOC);


    $shipping_address = $_POST['shipping_address'];
    $phone = $_POST['phone'];
    $notes = $_POST['notes'];
    $payment_slip = $_FILES['payment_slip']['name'];




    // Email setup
    $mail->setFrom('newlightbookstore@gmail.com');
    $mail->addAddress('newlightbookstore@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'New Order';
    $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . '/NLBookStore/assets/paymentslip/' . $payment_slip);
    $mail->Body = "
    <h2>New Order Details</h2>
    <table>
        <tr>
            <td><strong>Order ID:</strong></td>
            <td>$order_id</td>
        </tr>
        <tr>
            <td><strong>Order Date:</strong></td>
            <td>$order_date</td>
        </tr>
        <tr>
            <td><strong>Customer Name:</strong></td>
            <td>$customer_name</td>
        </tr>
        <tr>
            <td><strong>Shipping Address:</strong></td>
            <td>$shipping_address</td>
        </tr>
        <tr>
            <td><strong>Phone:</strong></td>
            <td>$phone</td>
        </tr>
        <tr>
            <td><strong>Notes:</strong></td>
            <td>$notes</td>
        </tr>
        
    </table>
    <br>
    <table>
        <tr>
            <th>Book Title</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>";

    foreach ($book_rows as $book_row) {
        $book_title = $book_row['title'];
        $quantity = $book_row['quantity'];
        $price = $book_row['price'];
        $mail->Body .= "
        <tr>
            <td>$book_title</td>
            <td>$quantity</td>
            <td>$price</td>
        </tr>";
    }

    $mail->Body .= "<br><br><tr>
            <td><strong>Total Amount : </strong></td>
            <td>$subtotal</td>
        </tr></table>";

    // Send the email
    if (!$mail->send()) {
        echo 'Message could not
be sent. Mailer Error: ' . $mail->ErrorInfo;
    } else {
    }

    // Close database connection
    $stmt->close();



    ///////////////End of notification e-mail message

    //////////////////////////////////////////////////////////////

    $pdf = new TCPDF(
        'P',
        'mm',
        'A4',
        true,
        'UTF-8',
        false
    );
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Invoice');
    $pdf->SetSubject('Invoice');
    $pdf->SetKeywords('TCPDF, PDF, invoice');

    // add a page
    $pdf->AddPage();

    // set font
    $pdf->SetFont(
        'times',
        '',
        12
    );

    // output data in a table format
    $html = '<div class="card">
  <div class="card-body mx-4">
    <div class="container">
    <p class="my-5 mx-5" style="font-size: 30px;">INVOICE</p>
      <p class="my-5 mx-5" style="font-size: 30px;">NEWLIGHT BOOKSTORE</p>
      <div class="row">
        <ul class="list-unstyled">
          <li class="text-black"> ' . $customer_name . '</li>
          <li class="text-muted mt-1"><span class="text-black">Order ID : </span>' . $order_id . '</li>
          <li class="text-muted mt-1"><span class="text-black">Shipping Address : </span>' . $shipping_address . '</li>
          <li class="text-black mt-1"><span class="text-black">Order Date : </span>' . $order_date . '</li>
          </ul>
        </div>
        <table class="table table-borderless table-sm">
          <thead>
            <tr class="text-muted">
              <th scope="col" class="text-black font-weight-bold">Book Title</th>
              <th scope="col" class="text-black font-weight-bold">Quantity</th>
              <th scope="col" class="text-black font-weight-bold">Price</th>
            </tr>
          </thead>
          <tbody>';

    foreach ($book_rows as $book_row) {
        $book_title = $book_row['title'];
        $quantity = $book_row['quantity'];
        $price = $book_row['price'];
        $html .= '<tr><td>' . $book_title . '</td><td>' . $quantity . '</td><td>' . $price . '</td></tr>';
    }

    $html .= '<br><br><tr>
            <td><strong>Total Amount : </strong></td>
            <td>' . $subtotal . '</td>
        </tr></tbody></table><p>Note : Please kindly note that if you have made payment via 
        KBZ pay or Wave Pay we will check your payment slip first then if the payment receipt is valid we will process the book delivery.</p><br><br>
        <p class="my-5 mx-5" style="font-size: 30px;">Thank you for shopping with us !</p></div></div></div>';

    $pdf->writeHTML(
        $html,
        true,
        false,
        true,
        false,
        ''
    );

    // generate file name for the invoice PDF
    $invoice_file = 'invoice_' . $order_id . '.pdf';

    // save the PDF invoice to a file
    $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/NLBookStore/invoices/' . $invoice_file, 'F');

    // create the email
    $mail = new PHPMailer;

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp-relay.sendinblue.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'mgmgtoke83@gmail.com';
    $mail->Password = 'xsmtpsib-3c3e732bc1b480fa8732df0142ea5bd26f1c396636d0f9b79c1de0e696e9d86c-DN8CnjqIbZJQ29hT';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Email content
    $customer_query = "SELECT first_name, last_name, email FROM customer WHERE customer_id = ?";
    $stmt = $con->prepare($customer_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $customer_name = $row['first_name'] . ' ' . $row['last_name'];
    $customer_email = $row['email'];

    $shipping_address = $_POST['shipping_address'];
    $phone = $_POST['phone'];
    $notes = $_POST['notes'];


    // Email setup
    $mail->setFrom('newlightbookstore@gmail.com');
    $mail->addAddress($email);
    $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . '/NLBookStore/invoices/' . $invoice_file);

    // Email content
    $mail->Subject = 'Your invoice for order #' . $order_id;
    $mail->Body    = 'Dear ' . $customer_name . ',<br><br>' .
        'Please find attached your invoice for order #' . $order_id .
        '.<br><br>Thank you for your business!<br><br>Best regards,<br>New Light Bookstore';

    // Send email
    if (!$mail->send()) {
        echo 'Email could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        // echo 'Email sent successfully.';
    }




    /////////////////////////////////////////////////////////

    // clear the cart session data
    unset($_SESSION["cart"]);
}
// Fetch cart items
$cart_items = array();
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $book_id => $quantity) {
        $stmt = $con->prepare("SELECT title, price FROM book WHERE book_id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->bind_result($title, $price);
        $stmt->fetch();
        $stmt->close();
        $subtotal = $quantity * $price;
        $total_price += $subtotal;
        $cart_items[] = array(
            'book_id' => $book_id,
            'title' => $title,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $subtotal
        );
    }
}
$_SESSION['cart_items'] = $cart_items;


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

// // Close the database connection
// mysqli_close($con);
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
                            <a href="cart.php?customer_id=<?php echo $customerid; ?>"><i class="cart-icon fa fa-fw fa-cart-arrow-down text-dark mr-1 "></i></a>
                            <span class="cart-item position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"></span>
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </nav>
    <!-- Close Header -->

    <!--Checkout Section-->
    <br><br>

    <div class="site-section">

        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return checkFields()">
            <div class=" container">
                <div class="row">

                    <div class="col-md-6 mb-5 mb-md-0">
                        <h2 class="h3 mb-3 text-black">Shipping Address Details</h2>
                        <div class="p-3 p-lg-5 border">

                            <div class="form-group row">

                                <div class="col-md-6">
                                    <label for="c_fname" class="text-black">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_fname" name="fname" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="c_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_lname" name="lname" required>
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="c_companyname" class="text-black">Company Name </label>
                                    <input type="text" class="form-control" id="c_companyname" name="company">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="c_address" class="text-black">Shipping Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_address" name="shipping_address" placeholder="Street address" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="c_state_country" class="text-black">State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_state_country" name="state" required>
                                </div>
                            </div>

                            <div class="form-group row mb-5">
                                <div class="col-md-6">
                                    <label for="c_email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="c_email_address" name="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="c_phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="c_phone" name="phone" placeholder="Phone Number" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="c_order_notes" class="text-black">Order Notes</label>
                                <textarea name="notes" id="c_order_notes" cols="30" rows="5" class="form-control" placeholder="Write your notes here..."></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <h2 class="h3 mb-3 text-black">Your Order</h2>
                                <div class="p-3 p-lg-5 border">
                                    <table class="table site-block-order-table mb-5">
                                        <thead>
                                            <tr>
                                                <th>Book</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cart_items as $cart_item) : ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($cart_item['title']); ?></td>
                                                    <td><?php echo htmlspecialchars($cart_item['quantity']); ?></td>
                                                    <td>$<?php echo htmlspecialchars($cart_item['price']); ?></td>
                                                    <td>$<?php echo htmlspecialchars($cart_item['subtotal']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                                <td>$<?php echo htmlspecialchars($total_price); ?></td>
                                            </tr>

                                        </tbody>

                                    </table>


                                    <div class="border p-3 mb-3" id="payslip">
                                        <h3 for="payment_slip" class="h6 mb-0">
                                            <h3>KBZ Pay & Wave Pay</h3> (Please kindly upload your payment slip)
                                        </h3>
                                        <input type="file" name="payment_slip">
                                    </div>

                                    <h3 class="border p-3 mb-5">
                                        <label for="cod-checkbox">
                                            <input type="checkbox" id="cod-checkbox" name="payment_method" value="cod">
                                            Cash on Delivery
                                        </label>
                                    </h3>

                                    <div class="form-group">
                                        <button class="btn btn-primary btn-lg py-3 btn-block" id="submit-button" type="submit" name="confirm_checkout">Place Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </form>

    </div>
    <!--Close Checkout-->



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
        function checkFields() {
            // get all input fields with the "required" attribute
            const requiredFields = document.querySelectorAll('input[required]');

            // check each required field for a value
            for (let i = 0; i < requiredFields.length; i++) {
                if (!requiredFields[i].value) {
                    alert('Please fill out all required fields.');
                    return false;
                }
            }

            // all required fields have a value
            return true;
        }

        const submitButton = document.getElementById('submit-button');

        submitButton.addEventListener('click', () => {
            if ($cart_items === null) {
                event.preventDefault(); // prevents the default submit action
                alert('There are no items in the cart.'); // displays an alert message
            }
        });

        const codCheckbox = document.getElementById('cod-checkbox');
        const payslipDiv = document.getElementById('payslip');

        // Initially hide the payment slip section
        payslipDiv.style.display = 'block';

        // Show/hide payment slip section based on checkbox state
        codCheckbox.addEventListener('change', function() {
            if (this.checked) {
                payslipDiv.style.display = 'none';
            } else {
                payslipDiv.style.display = 'block';
            }
        });
    </script>
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/thriller.js"></script>
    <!-- End Script -->

</body>

</html>