<?php
use hokphp\core\components\View;
use hokphp\core\components\Application;
use hokphp\core\components\Controller;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Klanttevredenheidsonderzoek Admin</title>

    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/admin/simple-sidebar.css" rel="stylesheet">

</head>

<body>

    <div id="wrapper" class="toggled">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand" style="background: #3f3f3f">
                    <a href="#">
                        Welcome, <strong><?php echo ucwords(Application::$app->username); ?></strong>
                    </a>
                </li>
                <li class="<?php echo Application::isCurrentRoute('admin/index') ? 'active' : ''; ?>">
                    <a href="<?php echo Application::createurl('admin/index') ?>">Dashboard</a>
                </li>
                <li>
                    <a href="<?php echo Application::createurl('site/logout') ?>">Logout</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <?php View::renderView($viewFile, $params, FALSE); ?>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="js/vendor/jquery-1.11.2.min.js"></script>
    <script src="js/vendor/bootstrap.min.js"></script>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

    <style>
        
        .sidebar-wrapper {
            box-shadow: inset 1px 4px 5px -5px;
            background-color: #fff;
        }
    </style>

</body>

</html>

