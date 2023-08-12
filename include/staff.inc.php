<?php

if (isset($_POST['submit'])) {
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $username = $_POST['user_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    include '../classes/dbh.class.php';
    include '../classes/staff.class.php';
    include '../classes/staff.ctrl.php';

    $registerstaff = new StaffCtrl($firstname, $lastname, $username, $email, $hashed_password);
    $registerstaff->staffSignUp();
    echo '<script type="text/javascript"> 
        window.alert("Staff Account Register Successfully !");
        setTimeout(function() {
            window.location.href = "/nlbookstore/adminlogin.php";
        }, 100); // wait for 0.5 seconds before redirecting
      </script>';
} 
    elseif (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    include '../classes/dbh.class.php';
    include '../classes/staff.class.php';
    include '../classes/login.ctrl.php';

    $registerstaff = new LoginCtrl($email, $password);
    $staff=$registerstaff->login();
    session_start();
    $_SESSION['staff'] = $staff;

    // Redirect to the results page
    if (!empty($staff)) {
        $result_url = '/nlbookstore/editbook.php';
        header('Location: ' . $result_url);
        exit();
    }
} elseif (isset($_POST['staffsearch'])) {
    $search = $_POST['search'];

    include '../classes/dbh.class.php';
    include '../classes/staff.class.php';
    include '../classes/deletestaff.ctrl.php';
   

    $result = new GetStaff($search);
    $getstaffsearchesult = $result->getStaffResult();
    // Store the result array in a session variable
    session_start();
    $_SESSION['getstaffsearchesult'] = $getstaffsearchesult;

    // Redirect to the results page
    if (!empty($getstaffsearchesult)) {
        $result_url = '/nlbookstore/deletestaff.php';
        header('Location: ' . $result_url);
        exit();
    } else {
        echo 'No results found.';
    }
} elseif (isset($_POST['delete'])) {
    include '../classes/dbh.class.php';
    include '../classes/staff.class.php';
    include '../classes/staffdelete.ctrl.php';

    $staff_id = $_POST['delete'];
   

    $delete = new DeleteStaff($staff_id);
    $delete->delStaffAcc();
    echo '<script type="text/javascript"> 
        window.alert("Staff Account Deleted Successfully !");
        setTimeout(function() {
            window.location.href = "/nlbookstore/deletestaff.php";
        }, 300); // wait for 0.5 seconds before redirecting
      </script>';
} 
    elseif (isset($_POST['update'])) {
    include '../classes/dbh.class.php';
    include '../classes/staff.class.php';
    include '../classes/updatestaff.ctrl.php';

    $staff_id = $_POST['staff_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    var_dump($staff_id);
   

    $updatestaff = new StaffUpdate($first_name, $last_name, $email, $staff_id);
    $updatestaff->updateStaff();
    echo '<script type="text/javascript"> 
        window.alert("Staff Account Updated Successfully !");
        setTimeout(function() {
            window.location.href = "/nlbookstore/deletestaff.php";
        }, 300); // wait for 0.5 seconds before redirecting
      </script>';
}
