<!DOCTYPE html>
<html lang="en">
<?php
include 'loginaction.php';
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/login.css">
    <!-- awesomefont ownkit -->
    <script src="https://kit.fontawesome.com/3777e5b46d.js" crossorigin="anonymous"></script>
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <title>Login</title>
</head>

<body style="background-image: url(assets/img/LoginPic3.jpg);">

    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="card p-4">
            <div class="card-body">
                <div class="py-5 text-center">
                    <h1>Login</h1>
                </div>
                <form method="post" action="">
                    <div class="form-group mb-3">
                        <input type="email" name="email" placeholder="Email" class="form-control">
                    </div>

                    <div class="form-group">
                        <div class="input-group mb-3">
                            <input type="password" name="password" placeholder="Password" class="form-control">
                            
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                    </div>
                </form>

                <div class="text-center mb-3">
                    <span>Don't have an account? <a href="signup.php" class="link signup-link"><b>Signup</b></a></span>
                </div>
            </div>
        </div>
    </div>

</body>

</html>