<!-- login.html -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
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
                    <div class="panel-heading">Login</div>


                    <div class="panel-body">
                        <div class="custom-alert">
                            <?php if($error): ?>
                                <div class="alert alert-danger">
                                    <p class="small">
                                        <?= $error ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <form class="form-horizontal" method="POST" action="/login">

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="" required
                                        autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <a class="btn btn-link" href="/register">
                                        No Account ? Register now
                                    </a>
                                    <button type="submit" class="btn btn-primary pull-right">
                                        Login
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