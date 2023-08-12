<?php

if(isset($_POST['submit']))
{
    $search=$_POST['search'];

    include '../classes/dbh.class.php';
    include '../classes/editbook.class.php';
    include '../classes/editbook.ctrl.php';
    include '../classes/updatebook.ctrl.php';

    $result=new GetBook($search);
    $getbookresult=$result->getBookResult();
    // Store the result array in a session variable
    session_start();
    $_SESSION['getbookresult'] = $getbookresult;

    // Redirect to the results page
    if (!empty($getbookresult)) {
        $result_url = '/nlbookstore/editbook.php';
        header('Location: ' . $result_url);
        exit();
    } else {
        echo 'No results found.';
    }

} elseif (isset($_POST['update'])) {
    include '../classes/dbh.class.php';
    include '../classes/editbook.class.php';
    include '../classes/updatebook.ctrl.php';

    $book_id=$_POST['book_id'];
    $title=$_POST['title'];
    $author_name=$_POST['author_name'];
    $ISBN=$_POST['ISBN'];
    $price=$_POST['price'];

    $insert=new BookUpdate($title, $author_name, $ISBN, $price,$book_id);
    $insert->updateBook();
    echo '<script type="text/javascript"> 
        window.alert("Updated Successfully !");
        setTimeout(function() {
            window.location.href = "/nlbookstore/editbook.php";
        }, 300); // wait for 0.5 seconds before redirecting
      </script>';
    
} elseif (isset($_POST['delete'])) {
    include '../classes/dbh.class.php';
    include '../classes/editbook.class.php';
    include '../classes/deletebook.ctrl.php';

    $book_id = $_POST['book_id'];
  
  
    $delete = new DeleteBook($book_id);
    $delete->delBook();
    echo '<script type="text/javascript"> 
        window.alert("Deleted Successfully !");
        setTimeout(function() {
            window.location.href = "/nlbookstore/editbook.php";
        }, 300); // wait for 0.5 seconds before redirecting
      </script>';
}
