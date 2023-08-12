<?php
session_start();
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>NewLight Book Store- Admin</title>
    <link href="assets/css/styles2.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="adminlogin.php"><img src="assets/img/logo.png"></a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <?php
                    $staffaccount = $_SESSION['staff'];

                    if (!empty($staffaccount)) {
                        // If you have more than one result, loop through the array to display each one
                        echo $staffaccount['first_name'] . ' ' . $staffaccount['last_name'];
                    }

                    ?>
                    <li><a class="dropdown-item" href="adminlogoutprocess.php" type="submit">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">

                        <div class="sb-sidenav-menu-heading">Interface</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Menu
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="insertbook.php">Insert New Book</a>
                                <a class="nav-link" href="insertcarousel.php">Insert Carousel Data</a>
                                <a class="nav-link" href="insertauthor.php">Insert New Author</a>
                                <a class="nav-link" href="editbook.php">Edit / Delete Book</a>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                    Staff Account Management
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">

                                        <a class="nav-link" href="deletestaff.php">Edit/Delete Staff Account</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Reports
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="mostorderbook.php">Best Selling Book</a>
                                <a class="nav-link" href="leastorderbook.php">Least Selling Book</a>
                                <a class="nav-link" href="topcustomer.php">Top Customer</a>
                                <a class="nav-link" href="topcategory.php">Top Category</a>
                                <a class="nav-link" href="invoices.php">Check Invoices</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php
                    $staffaccount = $_SESSION['staff'];

                    if (!empty($staffaccount)) {
                        // If you have more than one result, loop through the array to display each one
                        echo $staffaccount['first_name'] . ' ' . $staffaccount['last_name'];
                    }

                    ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <section class="Book Report">
                        <h3 class="p-5"> Least Selling Books</h3>
                        <form class="p-6 col-md-4" action="include/mostorderbook.inc.php" method="post">
                            <div class="form-group">
                                <label for="AuthorName">Start Date</label>
                                <input type="date" name="start_date" class="form-control p-2 mb-3" placeholder="Start Date">
                                <label for="AuthorName">End Date</label>
                                <input type="date" name="end_date" class="form-control p-2 mb-3" placeholder="End Date">
                            </div>
                            <button type="submit" name="submitleast" class="btn btn-primary">Submit</button>
                        </form>

                        <div>
                            <?php

                            $result = $_SESSION['result'];

                            if (!empty($result)) {

                                echo '<p>The book with the least orders has an Book title of <strong>' . $result[0]['title'] . '</strong>, and has been ordered <strong>' . $result[0]['total_quantity'] . '</strong> times.</p>';

                                // If you have more than one result, loop through the array to display each one
                                if ($result) {
                                    echo '<table class="table table-striped">';
                                    echo '<thead><tr><th>Book ID</th><th>Book Title</th><th>Author Name</th><th>Total Quantity</th></tr></thead>';
                                    echo '<tbody>';
                                    foreach ($result as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $row['book_id'] . '</td>';
                                        echo '<td>' . $row['title'] . '</td>';
                                        echo '<td>' . $row['author_name'] . '</td>';
                                        echo '<td>' . $row['total_quantity'] . '</td>';
                                        echo '</tr>';
                                    }
                                    echo '</tbody></table>';
                                } else {
                                    echo 'No results found.';
                                }
                            }
                            // Clear the session variable
                            unset($_SESSION['result']);
                            ?>
                        </div>
                    </section>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; NewLight Book Store 2023</div>

                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/scripts2.js"></script>
</body>

</html>