<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {

    $bookImage = $_FILES["bookImage"]["name"];
    $tempname = $_FILES["bookImage"]["tmp_name"];
    $targetfolder = "../bookimage/";

    $authorName = $_POST['author_id'];
    $bookTitle = $_POST['bookTitle'];
    $isbn = $_POST['isbn'];
    $language = $_POST['language_id'];
    $numPages = $_POST['numPages'];
    $pubDate = $_POST['pubDate'];
    $pubName = $_POST['publisher_id'];
    $categoryName = $_POST['category_id'];
    $price = $_POST['price'];
    // $bookImage = $_POST['bookImage'];
    $summary = $_POST['summary'];

    include '../classes/dbh.class.php';
    include '../classes/insertbook.class.php';
    include '../classes/insertbook.ctrl.php';
    $insertbook = new insertBookCtrl($authorName, $bookTitle, $isbn, $language, $numPages, $pubDate, $pubName, $categoryName, $price, $bookImage, $summary,$tempname,$targetfolder);
    $insertbook->bookRegister();
    echo '<script type="text/javascript"> 
        window.alert("New Book Added Successfully !");
        setTimeout(function() {
            window.location.href = "/nlbookstore/insertbook.php";
        }, 100); // wait for 0.5 seconds before redirecting
      </script>';
    
}
