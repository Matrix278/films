<div class="container" style="padding-top: 10px;">
<?php
if (!empty($_POST['searchFilm'])){
    $searchFilm=$_POST['searchFilm'];
    $sql = mysqli_query($link, "SELECT title FROM films WHERE title LIKE '%$searchFilm%'");
    if (mysqli_num_rows($sql) > 0){
        $allFilms=mysqli_query($link, "SELECT * FROM films WHERE title LIKE '%$searchFilm%'");
        echo '<h2>Вот что было найдено по результату <b>"'.$searchFilm.'"</b></h2>';

        $genres=Array();
        $allGenres=mysqli_query($link, "SELECT * FROM genres");
        while($oneGenre=mysqli_fetch_assoc($allGenres)){
            $genres[$oneGenre['code']]=$oneGenre['title'];
        }

        while($oneFilm=mysqli_fetch_assoc($allFilms)){
            $filmGenreCodes=explode(",", $oneFilm['genreCode']);
            echo '<div class="card">
            <h3 style="padding-left: 30px; padding-top: 10px;">'.$oneFilm['title'].'</h3>
                <div class="row card-body">
                    <a style="padding-left: 15px;" href="?film='.$oneFilm['code'].'">
                    <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-12">
                        <img src="'.$oneFilm['image'].'" width=200 height=300 alt="'.$oneFilm['title'].'">
                    </div>
                    </a>
                    <div style="padding: 30px;" class="col-xl-7 col-lg-6 col-md-12 col-sm-12">
                    <b>Жанр:</b> ';
                    $i = 0;
                    while($i < count($filmGenreCodes)) {
                        if($i == count($filmGenreCodes)-1){
                            echo $genres[$filmGenreCodes[$i]];
                        }else{
                            echo $genres[$filmGenreCodes[$i]].', ';
                        }
                        $i++;
                    }
                    echo '</br>'.$oneFilm['info'].'
                    </div>
                </div>
            </div>';
        }
    }else{
        echo '<div class="card">
        <h3>Фильма под названием <b>"'.$searchFilm.'"</b> еще нет на сайте</h3>
        </div>';
    }
}
?>
</div>