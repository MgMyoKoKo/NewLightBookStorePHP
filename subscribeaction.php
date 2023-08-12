<?php

include 'database.php';


                    if (isset($_POST['sub'])) {
                        $sub_email = $_POST['subemail'];
                        $insert_query = "INSERT INTO subscriber (email) VALUES (?)";
                        $stmt = mysqli_prepare($con, $insert_query);
                        mysqli_stmt_bind_param($stmt, "s", $sub_email);
                        if (mysqli_stmt_execute($stmt)) {
                            echo
        '<script type="text/javascript"> 
        window.alert("Thank you for Subscribe With us !");
        setTimeout(function() {
            window.location.href = "index.php";
        }, 200); // wait for 3 seconds before redirecting
      </script>';
                            exit();
                        } else {
                            echo '<script type="text/javascript"> 
                    window.alert("Error !");
                  </script>';
                            exit();
                        }
                        mysqli_stmt_close($stmt);
                    }
                    