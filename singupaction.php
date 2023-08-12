<?php

// Start the session
session_start();

// Include database connection file
include('database.php');

// Check if the form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Get form data
  $first_name = mysqli_real_escape_string($con, $_POST['fname']);
  $last_name = mysqli_real_escape_string($con, $_POST['lname']);
  $user_name = mysqli_real_escape_string($con, $_POST['username']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);
  $confirm_password = mysqli_real_escape_string($con, $_POST['confpassword']);

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if the user already exists in the database
  $query = "SELECT * FROM customer WHERE email = '$email'";
  $result = mysqli_query($con, $query);

  if(mysqli_num_rows($result) > 0) {
    $_SESSION['register_error'] = 'User with this email already exists.';
    echo '<script type="text/javascript"> 
        window.alert("User with this email already exists.");
        setTimeout(function() {
            window.location.href = "singup.php";
        }, 2000); // wait for 3 seconds before redirecting
      </script>';
    exit();

  } else {
    // Insert the user data into the database
    $insert_query = "INSERT INTO customer (first_name,last_name,user_name, email, password) VALUES ('$first_name','$last_name','$user_name', '$email', '$hashed_password')";

    if(mysqli_query($con, $insert_query)) {
      $_SESSION['register_success'] = 'Registration successful. You can now login.';
      echo '<script type="text/javascript"> 
        window.alert("Registration successful. You can now login.");
        setTimeout(function() {
            window.location.href = "login.php";
        }, 2000); // wait for 3 seconds before redirecting
      </script>';
      exit();
    } else {
      $_SESSION['register_error'] = 'Registration failed. Please try again.';
      echo '<script type="text/javascript"> 
        window.alert("Registration failed. Please try again.");
        setTimeout(function() {
            window.location.href = "singup.php";
        }, 2000); // wait for 3 seconds before redirecting
      </script>';
      exit();
    }
  }
}
