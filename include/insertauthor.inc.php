<?php

if( isset($_POST['submit']))
{
    $author_name=$_POST['author_name'];

    include '../classes/dbh.class.php';
    include '../classes/insertauthor.class.php';
    include '../classes/insertauthor.ctrl.php';

    $insertauthor=new InsertAuthorCtrl($author_name);
    $insertauthor->insertAuthorName();
    echo '<script type="text/javascript"> 
        window.alert("New Author Added Successfully !");
        setTimeout(function() {
            window.location.href = "/nlbookstore/insertauthor.php";
        }, 100); // wait for 0.5 seconds before redirecting
      </script>';
  
}