<!-------------------------------- ПРОЕРКИ ДАННЫХ И ДОБАВЛЕНИЕ В БАЗУ ДАННЫХ-------------------------------->
<?php
if(isset($_POST['newFilmTitle']) && isset($_POST['newFilmGenreCodes'])){
    //валидация данных и добавление в таблицу
    $testRes = true;
    $msgName = '';
    //проверки:
    $newTitle = $_POST['newFilmTitle'];
    $newCode = strtolower($newTitle);
    if(isset($_POST["newFilmGenreCodes"]))  
    { 
        $concatAllSelectedGenreCodes = "";
        foreach ($_POST['newFilmGenreCodes'] as $oneSelectedGenreCode){
            $concatAllSelectedGenreCodes .= ",".$oneSelectedGenreCode;
        }
    }
    $newGenreCodes = substr($concatAllSelectedGenreCodes, 1, strlen($concatAllSelectedGenreCodes));
	$newInfo = $_POST['newFilmInfo'];
    $newDescription = $_POST['newFilmDescription'];
    $newDate = date('Y-m-d H:i:s', time());
    if(isset($_POST['newFilmTOP'])){
        $newTOP = $_POST['newFilmTOP'];
    }else{
        $newTOP = 0;
    }
    
    if(isset($_FILES['newFilmImage'])){
        $imageFolder="../../img/";
        if(is_uploaded_file($_FILES['newFilmImage']['tmp_name'])){
            $imageTail=substr($_FILES['newFilmImage']['name'],strrpos($_FILES['newFilmImage']['name'],'.'));
            $imageFileExtension = pathinfo($_FILES['newFilmImage']['name'])['extension'];
            if($imageFileExtension != "jpg" && $imageFileExtension != "png" && $imageFileExtension != "jpeg" && $imageFileExtension != "gif" ) {
                $testRes = false;
                $msgName .= '<p>Ошибка: Извините, загрузить можно только файлы с расширением JPG, JPEG, PNG и GIF.</p>';
            }else{
                if(move_uploaded_file($_FILES["newFilmImage"]["tmp_name"], $imageFolder.$newTitle."_".date('Ymdhis').$imageTail)){
                    $newImagePathToDB="img/".$newTitle."_".date('Ymdhis').$imageTail;
                    $testRes = true;
                    $msgName = '';
                    $_FILES['newFilmImage']['tmp_name'] = '';
                }else{
                    $testRes = false;
                    $msgName .= '<p>Ошибка: Изображение не было перенесено в вашу папку</p>';
                }
            }
        }
    }

    if(isset($_FILES['newFilmLink'])){
        $filmFolder="../../films/";
        if(is_uploaded_file($_FILES['newFilmLink']['tmp_name'])){
            $filmTail=substr($_FILES['newFilmLink']['name'],strrpos($_FILES['newFilmLink']['name'],'.'));
            $filmFileExtension = pathinfo($_FILES['newFilmLink']['name'])['extension'];
            if($filmFileExtension != "mp4" && $filmFileExtension != "avi" && $filmFileExtension != "mov" && $filmFileExtension != "flv" && $filmFileExtension != "wmv" ) {
                $testRes = false;
                $msgName .= '<p>Ошибка: Извините, загрузить можно только файлы с расширением MP4, AVI, MOV, FLV и WMV.</p>';
            }else{
                if(move_uploaded_file($_FILES["newFilmLink"]["tmp_name"], $filmFolder.$newTitle."_".date('Ymdhis').$filmTail)){
                    $newFilmPathToDB="films/".$newTitle."_".date('Ymdhis').$filmTail;
                    $testRes = true;
                    $msgName = '';
                    $_FILES['newFilmLink']['tmp_name'] = '';
                }else{
                    $testRes = false;
                    $msgName .= '<p>Ошибка: Фильм не был перенесен в вашу папку</p>';
                }
            }
        }
    }

    if(empty($newTitle)){
        $testRes=false;
        $msgName.='<p>Отсутствует название фильма</p>';
    }

    if(empty($newGenreCodes)){
        $testRes=false;
        $msgName.='<p>Отсутствуют жанры фильма</p>';
    }

	if($testRes){
		$test=mysqli_query($link, "SELECT COUNT (id) AS Q FROM films WHERE title='$newTitle' AND genreCode='$newGenreCodes' ");
		if($testRes['Q']>0){
			$testRes=false;
			$msgName.='<p>Такая запись существует</p>';
		}
    }

    if($testRes){
        if(mb_strlen($newTitle)>255){
            $testRes=false;
            $msgName.='<p>Слишком длинное название фильма</p>';
        }
    }

  if($testRes){
    $msgType="goodNews";
    if(isset($newFilmPathToDB) && isset($newImagePathToDB)){
        $res=mysqli_query($link, "INSERT INTO films VALUES (NULL, '$newImagePathToDB','$newTitle', '$newCode', '$newGenreCodes','$newInfo','$newDescription','$newDate','$newFilmPathToDB', '$newTOP') ");
    }else{
        $res=mysqli_query($link, "INSERT INTO films VALUES (NULL, '','$newTitle', '$newCode', '$newGenreCodes','$newInfo','$newDescription','$newDate','', '$newTOP') ");
    }
    $msgName.='<p>Запись добавлена!</p>';
  }else{
    $msgType="badNews";
  }

  if($msgType=="goodNews"){
    echo '<div class="alert alert-success alert-dismissable" role="alert">
    <a class="close" data-dismiss="alert" href="#">X</a>'.$msgName.'
    </div>';
  }else{
    echo '<div class="alert alert-danger alert-dismissable" role="alert">
    <a class="close" data-dismiss="alert" href="#">X</a>'.$msgName.'
    </div>';
  }
}

if(isset($_POST['codeToDelete'])){
  $itemToDelete = $_POST['codeToDelete'];
  $filmCodeToDelete = $_POST['filmCodeToDelete'];
  $filesToDelete = mysqli_query($link, "SELECT * FROM films WHERE id='$itemToDelete' AND code='$filmCodeToDelete' ");
  while($oneFilm = mysqli_fetch_assoc($filesToDelete)){
    if($oneFilm['image'] != ""){
        unlink("../../".$oneFilm['image']);
    }
    
    if($oneFilm['link'] != ""){
        unlink("../../".$oneFilm['link']);
    }
  }
  $res = mysqli_query($link, "DELETE FROM films WHERE id='$itemToDelete' AND code='$filmCodeToDelete' ");
  if($res === true){
      echo '<div class="alert alert-success alert-dismissable" role="alert">
      <a class="close" data-dismiss="alert" href="#">X</a>Фильм был успешно удален
      </div>';
  }else{
      echo '<div class="alert alert-danger alert-dismissable" role="alert">
      <a class="close" data-dismiss="alert" href="#">X</a>Ошибка удаления
      </div>';
  }
}


if(isset($_POST['editFilmTitle'])){
    $codeToEdit = $_SESSION['editCode'];
    $editTitle = $_POST['editFilmTitle'];
    $editCode = strtolower($editTitle);
    if(isset($_POST["editFilmGenreCodes"])){ 
        $concatAllSelectedGenreCodes = "";
        foreach ($_POST['editFilmGenreCodes'] as $oneSelectedGenreCode){
            $concatAllSelectedGenreCodes .= ",".$oneSelectedGenreCode;
        }
    }
    $editGenreCodes = substr($concatAllSelectedGenreCodes, 1, strlen($concatAllSelectedGenreCodes));
	$editInfo = $_POST['editFilmInfo'];
    $editDescription = $_POST['editFilmDescription'];
    if(isset($_POST['editFilmTOP'])){
        $editTOP = $_POST['editFilmTOP'];
    }else{
        $editTOP = 0;
    }

    
    if(isset($_FILES['editFilmImage'])){
        $imageFolder="../../img/";
        if(is_uploaded_file($_FILES['editFilmImage']['tmp_name'])){
            $lastCodeOfFilm = strtolower($_SESSION['editPostValueTitle']);
            $imageToDelete=mysqli_query($link, "SELECT * FROM films WHERE id='$codeToEdit' AND code='$lastCodeOfFilm' ");
            while($oneFilm=mysqli_fetch_assoc($imageToDelete)){
                if($oneFilm['image'] != ""){
                    unlink("../../".$oneFilm['image']);
                }
            }
            $imageTail=substr($_FILES['editFilmImage']['name'],strrpos($_FILES['editFilmImage']['name'],'.'));
            $imageFileExtension = pathinfo($_FILES['editFilmImage']['name'])['extension'];
            if($imageFileExtension != "jpg" && $imageFileExtension != "png" && $imageFileExtension != "jpeg" && $imageFileExtension != "gif" ) {
                echo '<div class="alert alert-danger alert-dismissable" role="alert">
                <a class="close" data-dismiss="alert" href="#">X</a>Ошибка: Извините, загрузить можно только файлы с расширением JPG, JPEG, PNG и GIF.</p>
                </div>';
            }else{
                if(move_uploaded_file($_FILES["editFilmImage"]["tmp_name"], $imageFolder.$editTitle."_".date('Ymdhis').$imageTail)){
                    $editImagePathToDB="img/".$editTitle."_".date('Ymdhis').$imageTail;
                    $updateImage=mysqli_query($link, "UPDATE films SET image='$editImagePathToDB' WHERE id='$codeToEdit' ");
                    if($updateImage){
                        echo '<div class="alert alert-success alert-dismissable" role="alert">
                        <a class="close" data-dismiss="alert" href="#">X</a>Изображение было успешно редактировано
                        </div>';
                    }else{
                        echo '<div class="alert alert-danger alert-dismissable" role="alert">
                        <a class="close" data-dismiss="alert" href="#">X</a>Ошибка редактирования изображения
                        </div>';
                    }
                    $_FILES['editFilmImage']['tmp_name'] = '';
                }else{
                    echo '<div class="alert alert-danger alert-dismissable" role="alert">
                    <a class="close" data-dismiss="alert" href="#">X</a>Ошибка: Изображение не было перенесено в вашу папку
                    </div>';
                }
            }
        }
    }
    
    if(isset($_FILES['editFilmLink'])){
        $filmFolder="../../films/";
        if(is_uploaded_file($_FILES['editFilmLink']['tmp_name'])){
            $lastCodeOfFilm = strtolower($_SESSION['editPostValueTitle']);
            $filmToDelete=mysqli_query($link, "SELECT * FROM films WHERE id='$codeToEdit' AND code='$lastCodeOfFilm' ");
            while($oneFilm=mysqli_fetch_assoc($filmToDelete)){
                if($oneFilm['link'] != ""){
                    unlink("../../".$oneFilm['link']);
                }
            }
            $filmTail=substr($_FILES['editFilmLink']['name'],strrpos($_FILES['editFilmLink']['name'],'.'));
            $filmFileExtension = pathinfo($_FILES['editFilmLink']['name'])['extension'];
            if($filmFileExtension != "mp4" && $filmFileExtension != "avi" && $filmFileExtension != "mov" && $filmFileExtension != "flv" && $filmFileExtension != "wmv" ) {
                echo '<div class="alert alert-danger alert-dismissable" role="alert">
                <a class="close" data-dismiss="alert" href="#">X</a>Ошибка: Извините, загрузить можно только файлы с расширением MP4, AVI, MOV, FLV и WMV.
                </div>';
            }else{
                if(move_uploaded_file($_FILES["editFilmLink"]["tmp_name"], $filmFolder.$editTitle."_".date('Ymdhis').$filmTail)){
                    $editFilmPathToDB="films/".$editTitle."_".date('Ymdhis').$filmTail;
                    $updateFilm=mysqli_query($link, "UPDATE films SET link='$editFilmPathToDB' WHERE id='$codeToEdit' ");
                    if($updateFilm){
                        echo '<div class="alert alert-success alert-dismissable" role="alert">
                        <a class="close" data-dismiss="alert" href="#">X</a>Фильм был успешно редактирован
                        </div>';
                    }else{
                        echo '<div class="alert alert-danger alert-dismissable" role="alert">
                        <a class="close" data-dismiss="alert" href="#">X</a>Ошибка редактирования фильма
                        </div>';
                    }
                    $_FILES['editFilmLink']['tmp_name'] = '';
                }else{
                    echo '<div class="alert alert-danger alert-dismissable" role="alert">
                    <a class="close" data-dismiss="alert" href="#">X</a>Ошибка: Фильм не был перенесен в вашу папку
                    </div>';
                }
            }
        }
    }

    $sql=mysqli_query($link, "UPDATE films SET title='$editTitle', code='$editCode', genreCode='$editGenreCodes', info='$editInfo', description='$editDescription', topFilm='$editTOP' WHERE id='$codeToEdit' ");
    if($sql){
        echo '<div class="alert alert-success alert-dismissable" role="alert">
        <a class="close" data-dismiss="alert" href="#">X</a>Редактирование успешно
        </div>';
    }else{
        echo '<div class="alert alert-danger alert-dismissable" role="alert">
        <a class="close" data-dismiss="alert" href="#">X</a>Ошибка редактирования
        </div>';
    }
}
?>
<!-------------------------------- ПРОЕРКИ ДАННЫХ И ДОБАВЛЕНИЕ В БАЗУ ДАННЫХ-------------------------------->

<html>
    <head>
        <style>
        td form{ display: inline-block; width: auto;}
        input[value="X"]{ background-color: red; color: white;}
    </style>
</head>
<body>

<div class="row">

<!-------------------------------- РЕДАКТИРОВАНИЕ ФИЛЬМОВ -------------------------------->
    <?php
        if(isset($_POST['codeToEdit'])):
        $_SESSION['editCode']=$_POST['codeToEdit'];
        $_SESSION['editPostValueImage']=$_POST['editPostValueImage'];
        $_SESSION['editPostValueTitle']=$_POST['editPostValueTitle'];
        $_SESSION['editPostValueGenreCodes']=$_POST['editPostValueGenreCodes'];
        $_SESSION['editPostValueInfo']=$_POST['editPostValueInfo'];
        $_SESSION['editPostValueDescription']=$_POST['editPostValueDescription'];
        $_SESSION['editPostValueLink']=$_POST['editPostValueLink'];
        $_SESSION['editPostValueTop']=$_POST['editPostValueTop'];
    ?>
    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-7 col-8">
        <h3>Редактирование фильма</h3>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="labelFilmImage">Изображение</label>
                <input id="labelFilmImage" type="file" accept="image/*" class="form-control-file" name="editFilmImage">
                <p>Изображение которое используется в данный момент на сайте: </p>
                <?php echo '<img width="150" height="200" src="../../'.$_POST['editPostValueImage'].'" alt="'.$_POST['editPostValueImage'].'" /></td>'; ?>
            </div>
            <div class="form-group">
                <label for="labelFilmTitle">Название фильма</label>
                <input id="labelFilmTitle" type="text" class="form-control" name="editFilmTitle" value="<?php echo $_POST['editPostValueTitle']; ?>">
            </div>
            <div class="form-group">
                <label for="labelFilmGenreCodes">Коды жанров фильма</label>
                    <?php
                        $genresToCount = mysqli_query($link, "SELECT * FROM genres");
                        $numRows = mysqli_num_rows($genresToCount);
                        echo '<select multiple class="form-control" id="labelFilmGenreCodes" name="editFilmGenreCodes[]" size="'.$numRows.'">';
                        $allGenres=mysqli_query($link, "SELECT * FROM genres");
                        $filmGenreCodes = explode(",", $_POST['editPostValueGenreCodes']);
                        $addedSelectedGenres = "";
                        while($oneGenre=mysqli_fetch_assoc($allGenres)){
                            foreach($filmGenreCodes as $genreKey => $genreValue) {
                                if($genreValue == $oneGenre['code']){
                                    $addedSelectedGenres .= ",".$genreValue;
                                }
                            }
                            $alreadySelectedGenres = substr($addedSelectedGenres, 1, strlen($addedSelectedGenres));
                            if (strpos($alreadySelectedGenres, $oneGenre['code']) !== false) {
                                echo '<option selected value="'.$oneGenre['code'].'">'.$oneGenre['code'].'</option>';
                            }else{
                                echo '<option value="'.$oneGenre['code'].'">'.$oneGenre['code'].'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="editor1">Информация о фильме</label>
                <textarea class="form-control" name="editFilmInfo" id="editor1"><?php echo $_POST['editPostValueInfo']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="editor2">Описание фильма</label>
                <textarea class="form-control" name="editFilmDescription" id="editor2"><?php echo $_POST['editPostValueDescription']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="labelFilmLink">Фильм</label>
                <input id="labelFilmLink" type="file" accept="video/*" class="form-control-file" name="editFilmLink">
            </div>
            <div style="margin-bottom: 25px; margin-top: 25px;" class="form-check checkbox checkbox-primary">
                <input class="styled" id="labelFilmTOP" class="form-check-input" type="checkbox" name="editFilmTOP" value="1" <?php if($_POST['editPostValueTop']=="1"){ echo "checked"; } ?>>
                <label for="labelFilmTOP" class="form-check-label">ТОП фильм?</label>
            </div>
            <a href="" class="btn btn-secondary">Отмена</a>
            <button type="submit" class="btn btn-primary">Редактировать</button>
        </form>
    </div>
    <!-------------------------------- РЕДАКТИРОВАНИЕ ФИЛЬМОВ -------------------------------->

    <!-------------------------------- ДОБАВЛЕНИЕ ФИЛЬМОВ -------------------------------->
    <?php else: ?>
    <div class="col-12">
        <h3>Страница управления фильмами</h3>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
        Добавить новый фильм
        </button>
    </div>
    <!-- add Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Добавление нового фильма</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="labelFilmImage">Изображение</label>
                            <input id="labelFilmImage" type="file" accept="image/*" class="form-control-file" name="newFilmImage">
                        </div>
                        <div class="form-group">
                            <label for="labelFilmTitle">Название фильма</label>
                            <input id="labelFilmTitle" type="text" class="form-control" name="newFilmTitle">
                        </div>
                        <div class="form-group">
                            <label for="labelFilmGenreCodes">Коды жанров фильма</label>
                                <?php
                                    $genresToCount = mysqli_query($link, "SELECT * FROM genres");
                                    $numRows = mysqli_num_rows($genresToCount);
                                    echo '<select multiple class="form-control" id="labelFilmGenreCodes" name="newFilmGenreCodes[]" size="'.$numRows.'">';
                                    $allGenres=mysqli_query($link, "SELECT * FROM genres");
                                    while($oneGenre=mysqli_fetch_assoc($allGenres)){
                                        echo '<option value="'.$oneGenre['code'].'">'.$oneGenre['code'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editor1">Информация о фильме</label>
                            <textarea class="form-control" id="editor1" name="newFilmInfo"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editor2">Описание фильма</label>
                            <textarea class="form-control" name="newFilmDescription" id="editor2"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="labelFilmLink">Фильм</label>
                            <input type="hidden" name="<?php echo ini_get('session.upload_progress.name'); ?>" value="test" />
                            <input id="labelFilmLink" type="file" accept="video/*" class="form-control-file" name="newFilmLink">
                        </div>
                        <div style="margin-bottom: 25px; margin-top: 25px;" class="form-check checkbox checkbox-primary">
                            <input class="styled" id="labelFilmTOP" class="form-check-input" type="checkbox" name="newFilmTOP" value="1">
                            <label for="labelFilmTOP" class="form-check-label">ТОП фильм?</label>
                        </div>
                        <div class="w3-light-grey">
                            <div id="progressBar" class="w3-container w3-green w3-center" style="width:0%"><!-- filled by JS--></div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-------------------------------- ДОБАВЛЕНИЕ ФИЛЬМОВ -------------------------------->

    <!-------------------------------- ПРОСМОТР ФИЛЬМОВ -------------------------------->
    <div class="col-xl-7 col-lg-8 col-md-10 col-sm-12 col-8">
        <table style="margin-top: 10px; font-size: 13px;" class="table table-striped table-responsive">
            <tr>
                <th scope="col">N</th>
                <th scope="col">Изображение</th>
                <th scope="col">Название фильма</th>
                <th scope="col">Код фильма</th>
                <th scope="col">Коды жанров</th>
                <th scope="col">Информация о фильме</th>
                <th scope="col">Описание фильма</th>
                <th scope="col">Дата добавления</th>
                <th scope="col">Фильм</th>
                <th scope="col">ТОП фильм</th>
                <th scope="col" colspan="2">Действия</th>
            </tr>
        <?php
        $counter = 1;
        $allFilms=mysqli_query($link, "SELECT * FROM films");
        while($oneFilm=mysqli_fetch_assoc($allFilms)){
            echo '<tr><td>'.$counter.'</td>
            <td scope="row"><img width="150" height="200" src="../../'.$oneFilm['image'].'" alt="'.$oneFilm['image'].'" /></td>
            <td>'.$oneFilm['title'].'</td>
            <td>'.$oneFilm['code'].'</td>
            <td>'.$oneFilm['genreCode'].'</td>
            <td>'.$oneFilm['info'].'</td>
            <td>'.$oneFilm['description'].'</td>
            <td>'.$oneFilm['dateAdded'].'</td>
            <td>'.$oneFilm['link'].'</td>
            <td>';
            if($oneFilm['topFilm']=="1"){
                echo "ДА";
            }else{
                echo "НЕТ";
            }
            echo '</td>

            <td>
            <form action="" method="post">
                <input type="hidden" name="codeToEdit" value="'.$oneFilm['id'].'" />
                <input type="hidden" name="editPostValueImage" value="'.$oneFilm['image'].'" />
                <input type="hidden" name="editPostValueTitle" value="'.$oneFilm['title'].'" />
                <input type="hidden" name="editPostValueGenreCodes" value="'.$oneFilm['genreCode'].'" />
                <input type="hidden" name="editPostValueInfo" value="'.$oneFilm['info'].'" />
                <input type="hidden" name="editPostValueDescription" value="'.$oneFilm['description'].'" />
                <input type="hidden" name="editPostValueLink" value="'.$oneFilm['link'].'" />
                <input type="hidden" name="editPostValueTop" value="'.$oneFilm['topFilm'].'" />
                <i onclick="this.parentElement.submit()" class="fa fa-pencil-square-o" style="color: green; cursor: pointer;" aria-hidden="true"></i>
            </form>
            </td>';

            echo '<td>
            <form action="" method="post">
                <input type="hidden" name="codeToDelete" value="'.$oneFilm['id'].'" />
                <input type="hidden" name="filmCodeToDelete" value="'.$oneFilm['code'].'" />
                <i onclick="this.parentElement.submit()" class="fa fa-window-close" style="color: red; cursor: pointer;" aria-hidden="true"></i>
            </form>
            </td>
            </tr>';
            $counter++;
            }
        ?>
        </table>
    </div> <!-- END COL -->
<!-------------------------------- ПРОСМОТР ФИЛЬМОВ -------------------------------->

</div><!-- END ROW -->
<script>
    CKEDITOR.replace( 'editor1' );
</script>
<script>
    CKEDITOR.replace( 'editor2' );
</script>
</body>
</html>