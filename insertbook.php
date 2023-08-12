<?php

session_start();
error_reporting(0);

include 'classes/fetchOptVal.ctrl.php';
$fetchCtrl = new fetchOptionValue();
$authors = $fetchCtrl->getAuthorNames();
$languages = $fetchCtrl->getLanguageName();
$publishers = $fetchCtrl->getPubName();
$categories = $fetchCtrl->getCatName();

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
                    <section class="Book Register">

                        <form enctype="multipart/form-data" class="p-5 col-md-6" action="include/insertbook.inc.php" method="post">
                            <div class="form-group">
                                <label for="authorName">Author Name</label>
                                <select name="author_id" class="form-control">
                                    <?php foreach ($authors as $author) : ?>
                                        <option value="<?= $author['author_id'] ?>"><?= $author['author_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bookTitle">Book Title</label>
                                <input type="text" name="bookTitle" class="form-control" placeholder="Book Title">
                            </div>
                            <div class="form-group">
                                <label for="isbn">ISBN</label>
                                <input type="number" name="isbn" class="form-control" placeholder="ISBN">
                            </div>
                            <div class="form-group">
                                <label for="language_id">Language</label>
                                <select name="language_id" class="form-control">
                                    <?php foreach ($languages as $language) : ?>
                                        <option value="<?= $language['language_id'] ?>"><?= $language['language_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="numPages">Number Of Pages</label>
                                <input type="number" name="numPages" class="form-control" placeholder="Number Of Pages">
                            </div>
                            <div class="form-group">
                                <label for="pubDate">Publication Date</label>
                                <input type="date" name="pubDate" class="form-control" placeholder="Publication Date">
                            </div>
                            <div class="form-group">
                                <label for="publisher_id">Publisher Name</label>
                                <select name="publisher_id" class="form-control">
                                    <?php foreach ($publishers as $publisher) : ?>
                                        <option value="<?= $publisher['publisher_id'] ?>"><?= $publisher['publisher_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" class="form-control">
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" name="price" class="form-control" placeholder="Price $">
                            </div>
                            <div class="form-group">
                                <label for="bookImage">Book Cover Image upload</label>
                                <input type="file" name="bookImage" class="form-control-file">
                            </div>
                            <div class="form-group">
                                <label for="summary">Summary</label>
                                <textarea name="summary" class="form-control"></textarea>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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