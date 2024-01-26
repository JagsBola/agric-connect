<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin</title>

    <!-- Styles -->
    <link rel="stylesheet" href="css/app.css">
    <style>
        .dataTables_wrapper .dataTables_filter {
            float: right;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
        }

        tbody tr {
            cursor: pointer;
        }

        tbody tr:hover {
            background-color: #78abd2;
        }

        .highlighted-row {
            background-color: #83b9dd;
        }
        .navbar-brand {
            padding: 0;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    
                    <a class="navbar-brand" href="/">
                        <img src="/images/logo.png" alt="Logo" height="50">
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        <?php if(!@$auth->isAuthenticated()): ?>
                            <li><a href="/login">Login</a></li>
                        <?php else: ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-expanded="false" aria-haspopup="true">
                                    <?= $auth->name ?> <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="/admin/users">
                                            Manage Users
                                        </a>
                                        <!-- <a href="/admin/posts">
                                            Manage Post
                                        </a> -->
                                        <a href="/logout">
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="pull-left">Users</h1>
                            <div class="pull-right">
                                <br>
                                <!-- <a href="{{ route('users.create') }}">Create New User</a> -->
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <?php include('partials/notification.php') ?>
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered" style="width:100%">

                                <thead>
                                    <tr>
                                        <th>S/n</th>
                                        <th>Email</th>
                                        <th>name</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach($users as $key => $user): ?>
                                        <tr class="clickable-row">
                                            <td>
                                                <?= $key + 1; ?>
                                            </td>
                                            <td>
                                                <?= $user->email ?>
                                            </td>
                                            <td>
                                                <?= $user->name ?>
                                            </td>
                                            <td>
                                                <?= $user->type ?>
                                            </td>
                                            <td>
                                                <?= $user->created_at ?>
                                            </td>
                                            <td>
                                                <?php if($user->active) :?>
                                                <form action="/block-user" method="post" class="d-none block-user">
                                                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                                                </form>
                                                <?php else :?>
                                                <form action="/activate-user" method="post" class="d-none activate-user">
                                                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                                                </form>
                                                <?php endif; ?>

                                                <form action="/delete-user" method="post" class="d-none delete-user">
                                                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                                                </form>

                                                <?php if($user->active) :?>
                                                <button type="button" class="btn btn-sm btn-warning block-user-btn"
                                                    onclick="return confirm('Are you sure you want to block this user?')">Block</button>
                                                <?php else :?>
                                                <button type="button" class="btn btn-sm btn-success activate-user-btn"
                                                    onclick="return confirm('Are you sure you want to activate this user?')">Activate</button>
                                                <?php endif; ?>

                                                <button type="button"
                                                    class="btn btn-sm btn-danger delete-user-btn disabled">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#example').DataTable({
                "scrollX": true
            });
        });

        $('.clickable-row').on('mouseover', function () {
            $(this).addClass('highlighted-row');
        });

        $('.clickable-row').on('mouseout', function () {
            $(this).removeClass('highlighted-row');
        });

        $('.block-user-btn').click(function (e) {
            e.preventDefault();
            $(this).parent().find('.block-user').submit();
        });

        $('.activate-user-btn').click(function (e) {
            e.preventDefault();
            $(this).parent().find('.activate-user').submit();
        });

        $('.delete-user-btn').click(function (e) {
            e.preventDefault();
            $(this).parent().find('.delete-user').submit();
        });

    </script>

</body>

</html>