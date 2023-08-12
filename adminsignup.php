<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Singup</title>
</head>

<body style="background-image: url(assets/img/LoginPic3.jpg) ;">

    <section class="container forms">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="pt-5 my-0 text-dark text-center">
                            <h1>Register</h1>
                        </div>
                        <form method="post" action="include/staff.inc.php">
                            <div class="form-group mb-3">
                                <input type="text" name="first_name" placeholder="First Name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" name="last_name" placeholder="Last Name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" name="user_name" placeholder="User Name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="email" name="email" placeholder="Email" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" name="password" placeholder="Create password" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" name="confpassword" placeholder="Confirm password" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <button class="btn btn-primary btn-block" type="submit" name="submit">Signup</button>
                            </div>
                        </form>
                        <div class="form-group mb-3">
                            <span>Already have an account? <a href="adminlogin.php" class="link login-link"><b>Login</b></a></span>
                        </div>
                    </div>
                </div>
                <p class="w-100 text-center mt-5"><b>&mdash; Or Sign In With &mdash;</b></p>
                <div class="text-center mt-5">
                    <a href="#" class="btn btn-outline-primary rounded me-5"><span class="mr-2"><i class="fa-brands fa-facebook"></i></span><b>Facebook</b></a>
                    <a href="#" class="btn btn-outline-primary rounded"><span class="mr-2"><i class="fa-brands fa-twitter"></i></span><b>Twitter</b></a>
                </div>
            </div>
        </div>
    </section>
</body>

</html>