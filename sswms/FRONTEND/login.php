<!DOCTYPE html>
<html lang="en">

<?php include './url.php'; ?>
<?php include './layouts/head.php'; ?>
<?php include './assets/js/loginchecker.php'; ?>



<head>
    <style>
        body {
            background: none;
            /* Remove background for a clean, transparent look */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-form {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background-color: transparent;
            /* Transparent background */
            border-radius: 10px;
            box-shadow: none;
            /* No shadow for a cleaner look */
        }

        .login-form h1 {
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .login-form .form-control {
            height: 45px;
            font-size: 16px;
            padding-left: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            /* Border for input fields */
        }

        .login-form button {
            height: 45px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            /* No border for the button */
            background-color: #1e3a8a;
            /* Button color */
            color: white;
        }

        .login-form img {
            max-width: 100%;
            border-radius: 10px;
        }

        .login-form a {
            color: #007bff;
            text-decoration: none;
        }

        .container-fluid {
            display: flex;
            /* Enable Flexbox */
            justify-content: center;
            /* Center items horizontally */
            align-items: center;
            /* Center items vertically */
            height: 100vh;
            /* Full viewport height */
            padding: 0;
            margin: 0;
            background-color: transparent;
            /* No background */
        }

        @media (max-width: 768px) {
            .login-form {
                padding: 15px;
                box-shadow: none;
            }
        }
    </style>
</head>



<body>
    <div class="container-fluid">
        <div class="row vh-100">
            <!-- Left Section with Image -->
            <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center">
                <img src="./assets/img/oo.png" alt="Illustration">
            </div>

            <!-- Right Section with Form -->
            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <div class="login-form text-center">
                    <img src="./assets/img/logo1.png" alt="">
                    </a>
                    <form class="row g-3 needs-validation" id="loginForm" novalidate>
                        <div class="col-12">
                            <label for="txt_username" class="form-label">Username or Email</label>
                            <div class="input-group has-validation">
                                <input type="email" name="txt_username" class="form-control" id="txt_username" required>
                                <div class="invalid-feedback">Please enter your email.</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="txt_password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="txt_password" required>
                            <div class="invalid-feedback">Please enter your password!</div>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">Remember Me</label>
                            </div>
                            <a href="#" class="text-decoration-none">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-4">Login</button>
                        <p>Don't Have An Account? <a href="register.php" class="text-decoration-none">Click Here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </main><!-- End #main -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <!-- jQuery -->
    <script src="./assets/js/jquery/jquery.min.js"></script>
    <!-- Template Main JS File -->

    <script>
        const jwt = LoginChecker();
        console.log(jwt)
        if (jwt.status == "valid") {
            window.location.href = '/user/home.php';
        }

        $(document).ready(function() {
            $('#loginForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting the traditional way

                // Collect form data
                const formData = {
                    user_email: $('#txt_username').val(),
                    user_password: $('#txt_password').val()
                };

                $.ajax({
                    url: "https://citizenrequest.lgu2.com/sswms/BACKEND/auth-file/login.php",
                    type: 'POST',
                    data: JSON.stringify(formData),
                    dataType: 'json',
                    headers: {
                        "Referrer-Policy": "no-referrer"
                    },
                    success: function(response) {
                        const status = response.message;
                        const jwt = response.jwt;

                        console.log(jwt);


                        if (status != "Invalid User") {
                            localStorage.setItem('jwt', jwt);
                            window.location.href = 'index.php';
                        } else {
                            alert("Error " + status);
                            $("#span_error").removeClass('d-none');
                            $("#txt_username").addClass('border border-danger');
                            $("#txt_password").addClass('border border-danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                    }
                });
            });
        });
    </script>
</body>

</html>