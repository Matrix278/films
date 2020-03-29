<div class="container" style="padding-top: 10px;">
<?php
  $genres=Array();
  $allGenres=mysqli_query($link, "SELECT * FROM genres");
  while($oneGenre=mysqli_fetch_assoc($allGenres)){
    $genres[$oneGenre['code']]=$oneGenre['title'];
  }
  
  $allFilms=mysqli_query($link, "SELECT * FROM films WHERE topFilm = 1 ");
  
  echo '<h2>ТОП фильмы</h2>';
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
?>
</div>