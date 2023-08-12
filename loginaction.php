<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to the database
    $con = mysqli_connect("localhost", "root", "", "new_light");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the email exists in the database
    $query = "SELECT * FROM customer WHERE email='$email'";
    $result = mysqli_query($con, $query);
    

    if (mysqli_num_rows($result) > 0) {
        // Verify the password
        $row = mysqli_fetch_assoc($result);
        $verify=password_verify($password, $row['password']);
        if ( $verify ) {
            $_SESSION['customer_id'] = $row['customer_id'];
            $_SESSION['user_name'] = $row['user_name'];
            echo '<script type="text/javascript"> 
        window.alert("Login Sucessful");
        setTimeout(function() {
            window.location.href = "index.php";
        }, 500); // wait for 0.5 seconds before redirecting
      </script>';
           
        } else {
            $_SESSION['login_error'] = 'Invalid email or password';
            echo '<script type="text/javascript"> 
        window.alert("Invalid email or password");
        setTimeout(function() {
            window.location.href = "login.php";
        }, 2000); // wait for 2 seconds before redirecting
      </script>';
        }
    } 
    
    else {
        $_SESSION['login_error'] = 'Invalid email or password';
        echo '<script type="text/javascript"> 
        window.alert("Invalid email or password");
        setTimeout(function() {
            window.location.href = "login.php";
        }, 2000); // wait for 2 seconds before redirecting
      </script>';
    }

    // Close the database connection
    mysqli_close($con);
}
