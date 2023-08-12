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
                <div class="container-fluid px-4 col-md-6">
                    <section class="Book Register">
                        <h3 class="p-5">Insert Carousel Slide Data</h3>
                        <form method="post" action="carouselprocess.php" enctype="multipart/form-data">
                            <div class="h3">For Slide 1</div><br>
                            <div class="form-group">
                                <label for="title1">Slide 1 Title:</label>
                                <input type="text" class="form-control" id="title1" name="title1" required>
                            </div>
                            <div class="form-group">
                                <label for="author_name1">Slide 1 Author Name:</label>
                                <input type="text" class="form-control" id="author_name1" name="author_name1" required>
                            </div>
                            <div class="form-group">
                                <label for="description1">Slide 1 Description:</label>
                                <textarea class="form-control" id="description1" name="description1" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image1">Slide 1 Image:</label>
                                <input type="file" class="form-control-file" id="image1" name="image1" accept="image/*" required>
                            </div><br>
                            <div class="h3">For Slide 2</div><br>
                            <div class="form-group">
                                <label for="title2">Slide 2 Title:</label>
                                <input type="text" class="form-control" id="title2" name="title2" required>
                            </div>
                            <div class="form-group">
                                <label for="author_name2">Slide 2 Author Name:</label>
                                <input type="text" class="form-control" id="author_name2" name="author_name2" required>
                            </div>
                            <div class="form-group">
                                <label for="description2">Slide 2 Description:</label>
                                <textarea class="form-control" id="description2" name="description2" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image2">Slide 2 Image:</label>
                                <input type="file" class="form-control-file" id="image2" name="image2" accept="image/*" required>
                            </div>
                            <div class="h3">For Slide 3</div><br>
                            <div class="form-group">
                                <label for="title3">Slide 3 Title:</label>
                                <input type="text" class="form-control" id="title3" name="title3" required>
                            </div>
                            <div class="form-group">
                                <label for="author_name3">Slide 3 Author Name:</label>
                                <input type="text" class="form-control" id="author_name3" name="author_name3" required>
                            </div>
                            <div class="form-group">
                                <label for="description3">Slide 3 Description:</label>
                                <textarea class="form-control" id="description3" name="description3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image3">Slide 3 Image:</label>
                                <input type="file" class="form-control-file" id="image3" name="image3" accept="image/*" required>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

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