<?php
    // session_start();
    require_once "modules/dbconnect.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>Архив</title>
<meta charset="utf-8" />
<!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<!-- Bootstrap 4 -->

<link href="css/simple-sidebar.css" rel="stylesheet">

<script src="https://use.fontawesome.com/bcb61c4484.js"></script>

<script src="ckeditor/ckeditor.js"></script>
</head>
<body style="background-image: url('img/background.jpg'); background-repeat: no-repeat; background-size: cover; background-position: center;">

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading" style="font-size: 18px; padding: 23px;"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> Архив</div>
            <div class="list-group list-group-flush">
                <?php
                    $allGenres=mysqli_query($link, "SELECT * FROM genres ORDER BY listOrder ASC");
                    while($oneGenre=mysqli_fetch_assoc($allGenres)){
                        echo '<form action="?genre='.$oneGenre['code'].'" method="post">
                            <input type="hidden" name="btnMenu" value="Жанры"/>
                            <a style="cursor: pointer;" class="list-group-item list-group-item-action bg-light"  name="btnMenu" onclick="this.parentElement.submit()"><i class="fa fa-film fa-fw" aria-hidden="true"></i> '.$oneGenre['title'].'</a>
                        </form>';
                    }
                ?>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-light bg-light border-bottom">

                <button class="navbar-toggler" id="menu-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <form action="?page=top_films" method="post">
                    <input type="hidden" name="btnMenu" value="ТОП"/>
                    <a style="cursor: pointer; color: white;" class="btn btn-danger"  name="btnMenu" onclick="this.parentElement.submit()"><i class="fa fa-fire fa-fw" aria-hidden="true"></i> ТОП</a>
                </form>

                <form action="?page=new_films" method="post">
                    <input type="hidden" name="btnMenu" value="Новинки"/>
                    <a style="cursor: pointer; color: white;" class="btn btn-info"  name="btnMenu" onclick="this.parentElement.submit()"><i class="fa fa-caret-square-o-right fa-fw" aria-hidden="true"></i> Новинки</a>
                </form>

                <form class="form-inline my-2 my-lg-0" action="?search=film" method="POST">
                    <input class="form-control mr-sm-2" type="search" name="searchFilm" placeholder="Поиск" aria-label="Search">
                    <input type="hidden" name="btnMenu" value="Поиск"/>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Поиск</button>
                </form>
            </nav>

            <div class="container-fluid">
                <?php
                    if(isset($_POST['btnMenu'])){
                        switch ($_POST['btnMenu']) {
                            case 'ТОП':
                            include 'modules/topFilms.php';
                            break;
                            case 'Новинки':
                            include 'modules/newFilms.php';
                            break;
                            case 'Жанры':
                            include 'modules/genres.php';
                            break;
                            case 'Поиск':
                            include 'modules/search.php';
                            break;
                            default:
                            include 'modules/topFilms.php';
                        }
                    }
                    if(isset($_GET['film'])){
                        include 'modules/film.php';
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