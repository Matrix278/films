<?php
    session_start();
    require_once "../../modules/dbconnect.php"
?>
<!DOCTYPE html>
<html>
<head>
<title>Панель управления архива</title>
<meta charset="utf-8" />
<!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<!-- Bootstrap 4 -->

<link rel="stylesheet" href="css/main.css" />

<link href="css/simple-sidebar.css" rel="stylesheet">
<link href="css/awesome-bootstrap-checkbox.css" rel="stylesheet">

<script src="https://use.fontawesome.com/bcb61c4484.js"></script>

<script src="ckeditor/ckeditor.js"></script>
</head>
<body>

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading" style="font-size: 18px; padding: 23px;"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> Панель управления</div>
            <div class="list-group list-group-flush">
                <form action="" method="post">
                    <input type="hidden" name="btnMenu" value="Главная панель"/>
                    <a style="cursor: pointer;" class="list-group-item list-group-item-action bg-light"  name="btnMenu" onclick="this.parentElement.submit()"><i class="fa fa-home fa-fw" aria-hidden="true"></i> Главная панель</a>
                </form>

                <form action="" method="post">
                    <input type="hidden" name="btnMenu" value="Жанры"/>
                    <a style="cursor: pointer;" class="list-group-item list-group-item-action bg-light"  name="btnMenu" onclick="this.parentElement.submit()"><i class="fa fa-archive fa-fw" aria-hidden="true"></i> Жанры</a>
                </form>

                <form action="" method="post">
                    <input type="hidden" name="btnMenu" value="Фильмы"/>
                    <a style="cursor: pointer;" class="list-group-item list-group-item-action bg-light"  name="btnMenu" onclick="this.parentElement.submit()"><i class="fa fa-film fa-fw" aria-hidden="true"></i> Фильмы</a>
                </form>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-light bg-light border-bottom">

                <button class="navbar-toggler" id="menu-toggle" type="button">
                <span class="navbar-toggler-icon"></span>
                </button>
                
            </nav>

            <div class="container-fluid">
                <?php
                    if(isset($_POST['btnMenu'])){
                        $_SESSION['btnMenu']=$_POST['btnMenu'];
                    }
                    
                    if(isset($_SESSION['btnMenu'])){
                        switch ($_SESSION['btnMenu']) {
                            case 'Главная панель':
                            include 'dashboardAdmin.php';
                            break;
                            case 'Жанры':
                            include 'genresAdmin.php';
                            break;
                            case 'Фильмы':
                            include 'filmsAdmin.php';
                            break;
                            default:
                            include 'dashboardAdmin.php';
                        }
                    }
                ?>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->
    
    <!-- Bootstrap 4 -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Bootstrap 4 -->

    <!-- Menu Toggle Script -->
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
</body>
</html>