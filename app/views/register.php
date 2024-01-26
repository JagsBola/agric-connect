<!-- register.html -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/css/app.css">
    <style>
        .custom-alert {
            margin-left: 17%;
            margin-right: 17%;
        }
    </style>

</head>

<body>

    <div class="container">
        <br>
        <br>
        <div class="text-center">
            <a href="/">
                <img src="/images/logo.png" height="70" class="logo text-center" alt="">
            </a>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>


                    <div class="panel-body">

                        <div class="custom-alert">
                            <?php include('partials/notification.php') ?>
                        </div>
                        <form class="form-horizontal" method="POST" action="/register">

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="" required
                                        autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Full Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address" class="col-md-4 control-label">Address</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control" name="address" value=""
                                        required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="user_type" class="col-md-4 control-label">User Type</label>

                                <div class="col-md-6">
                                    <select id="user_type" name="user_type" class="form-control" required>
                                        <option value="buyer">Buyer</option>
                                        <option value="seller">Farmers</option>
                                        <!-- Add more options as needed -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="c_password" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="c_password" type="password" class="form-control" name="c_password"
                                        required>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div> -->

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                <a class="btn btn-link" href="/login">
                                        Already have an account ? Login
                                    </a>
                                    <button type="submit" class="btn btn-primary pull-right">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>