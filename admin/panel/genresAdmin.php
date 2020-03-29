<!-------------------------------- ПРОЕРКИ ДАННЫХ И ДОБАВЛЕНИЕ В БАЗУ ДАННЫХ-------------------------------->
<?php
if(isset($_POST['newGenreTitle'])){
  //валидация данных и добавление в таблицу
    $testRes=true;
    $msgName='';
  //проверки:
	$newTitle=$_POST['newGenreTitle'];
	$newCode=$_POST['newGenreCode'];
	$newOrder=$_POST['newGenreListOrder'];

    if(empty($newTitle)){
        $testRes=false;
        $msgName.='<p>Отсутствует название жанра</p>';
    }

    if(empty($newCode)){
        $testRes=false;
        $msgName.='<p>Отсутствует код жанра</p>';
    }

    if(empty($newOrder)){
        $testRes=false;
        $msgName.='<p>Отсутствует порядковый номер жанра</p>';
    }
        
	if($testRes){
		$test=mysqli_query($link, "SELECT COUNT (id) AS Q FROM genres WHERE title='$newTitle' AND code='$newCode' AND listOrder='$newOrder' ");
		if($testRes['Q']>0){
			$testRes=false;
			$msgName.='<p>Такая запись существует</p>';
		}
    }

    if($testRes){
        if(mb_strlen($newTitle)>255){
            $testRes=false;
            $msgName.='<p>Слишком длинное название жанра</p>';
        }
    }

    if($testRes){
        $allowedSymbols="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzАБВГДЕЁЖЗИКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯабвгдеёжзиклмнопрстуфхцчшщьыэюя0123456789-";
        for($i=0; $i<mb_strlen($newTitle); $i++){
            if(mb_strpos($allowedSymbols, mb_substr($newTitle,$i,1))===FALSE){
                $testRes=false;
                $msgName.='<p>Символы не могут быть в названии жанра</p>';
            }
        }
    }

  if($testRes){
    $allowedCodeSymbols="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzАБВГДЕЁЖЗИКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯабвгдеёжзиклмнопрстуфхцчшщьыэюя";
    for($i=0; $i<mb_strlen($newCode); $i++){
        if(mb_strpos($allowedCodeSymbols, mb_substr($newCode,$i,1))===FALSE){
            $testRes=false;
            $msgName.='<p>Код жанра должен состоять только из букв</p>';
        }
    }
  }

  if($testRes){
    if($newOrder==""){
        $order="0";
        $testRes=false;
    }
  }

  if($testRes){
    $msgType="goodNews";
    $res=mysqli_query($link, "INSERT INTO genres VALUES (NULL, '$newCode','$newTitle','$newOrder') ");
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
  $itemToDelete=$_POST['codeToDelete'];
  $genreCodeToDelete=$_POST['genreCodeToDelete'];
  $res=mysqli_query($link, "DELETE FROM genres WHERE listOrder='$itemToDelete' AND code='$genreCodeToDelete' ");
  if($res===true){
      echo '<div class="alert alert-success alert-dismissable" role="alert">
      <a class="close" data-dismiss="alert" href="#">X</a>Жанр был успешно удален
      </div>';
  }else{
      echo '<div class="alert alert-danger alert-dismissable" role="alert">
      <a class="close" data-dismiss="alert" href="#">X</a>Ошибка удаления
      </div>';
  }
}

if(isset($_POST['codeToUp'])){
    $itemToUp=$_POST['codeToUp']; // ( 2 )
    $idToUp=$_POST['idToUp'];
    $genresOrderedByListOrder=mysqli_query($link, "SELECT * FROM genres WHERE listOrder < $itemToUp ORDER BY listOrder DESC LIMIT 1");

    while($oneGenre=mysqli_fetch_assoc($genresOrderedByListOrder)){
      $getListOrder=$oneGenre['listOrder'];// ( 1 )
      $getId=$oneGenre['id'];

      $sqlGenreToUp1=mysqli_query($link, "UPDATE genres SET listOrder='$getListOrder' WHERE id='$idToUp' ");
      $sqlGenreToUp2=mysqli_query($link, "UPDATE genres SET listOrder='$itemToUp' WHERE id='$getId' ");
    }

    if($sqlGenreToUp1 && $sqlGenreToUp2){
      echo '<div class="alert alert-success alert-dismissable" role="alert">
      <a class="close" data-dismiss="alert" href="#">X</a>Жанр был успешно перемещен наверх!
      </div>';
    }else{
      echo '<div class="alert alert-danger alert-dismissable" role="alert">
      <a class="close" data-dismiss="alert" href="#">X</a>Ошибка перемещения жанра!
      </div>';
    }
}

if(isset($_POST['codeToDown'])){
    $itemToDown=$_POST['codeToDown'];
    $idToDown=$_POST['idToDown'];
    $genresOrderedByListOrder=mysqli_query($link, "SELECT * FROM genres WHERE listOrder > $itemToDown ORDER BY listOrder ASC LIMIT 1");

    while($oneGenre=mysqli_fetch_assoc($genresOrderedByListOrder)){
      $getListOrder=$oneGenre['listOrder'];// ( 1 )
      $getId=$oneGenre['id'];

      $sqlGenreToUp1=mysqli_query($link, "UPDATE genres SET listOrder='$getListOrder' WHERE id='$idToDown' ");
      $sqlGenreToUp2=mysqli_query($link, "UPDATE genres SET listOrder='$itemToDown' WHERE id='$getId' ");
    }

    if($sqlGenreToUp1 && $sqlGenreToUp2){
      echo '<div class="alert alert-success alert-dismissable" role="alert">
      <a class="close" data-dismiss="alert" href="#">X</a>Жанр был успешно перемещен вниз!
      </div>';
    }else{
      echo '<div class="alert alert-danger alert-dismissable" role="alert">
      <a class="close" data-dismiss="alert" href="#">X</a>Ошибка перемещения жанра!
      </div>';
    }
}


if(isset($_POST['editGenreTitle']) && isset($_POST['editGenreCode']) && isset($_POST['editGenreListOrder'])){
    $editGenreTitle=$_POST['editGenreTitle'];
    $editGenreCode=$_POST['editGenreCode'];
    $editGenreListOrder=$_POST['editGenreListOrder'];
    $codeToEdit=$_SESSION['editCode'];
    $sql=mysqli_query($link, "UPDATE genres SET code='$editGenreCode', title='$editGenreTitle', listOrder='$editGenreListOrder'  WHERE id='$codeToEdit' ");
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

<!-------------------------------- РЕДАКТИРОВАНИЕ ЖАНРОВ -------------------------------->
    <?php
        if(isset($_POST['codeToEdit'])):
        $_SESSION['editCode']=$_POST['codeToEdit'];
        $_SESSION['editPostValueCode']=$_POST['editPostValueCode'];
        $_SESSION['editPostValueTitle']=$_POST['editPostValueTitle'];
        $_SESSION['editPostValueListOrder']=$_POST['editPostValueListOrder'];
    ?>
    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-7 col-8">
        <h3>Редактирование жанра</h3>
        <form method="post" action="">
            <div class="form-group">
                <label for="exampleInputCode">Код жанра</label>
                <input id="exampleInputCode" class="form-control" type="text" name="editGenreCode" value="<?php echo $_POST['editPostValueCode']; ?>"/>
            </div>
            <div class="form-group">
                <label for="exampleInputName">Название жанра</label>
                <input type="text" class="form-control" name="editGenreTitle" id="exampleInputName" value="<?php echo $_POST['editPostValueTitle']; ?>">
            </div>
            <div class="form-group">
                <label for="exampleInputListOrder">Порядковый номер</label>
                <input type="number" class="form-control" name="editGenreListOrder" id="exampleInputListOrder" value="<?php echo $_POST['editPostValueListOrder']; ?>">
            </div>
            <a href="" class="btn btn-secondary">Отмена</a>
            <button type="submit" class="btn btn-primary">Редактировать</button>
        </form>
    </div>
    <!-------------------------------- РЕДАКТИРОВАНИЕ ЖАНРОВ -------------------------------->

    <!-------------------------------- ДОБАВЛЕНИЕ ЖАНРОВ -------------------------------->
    <?php else: ?>
    <div class="col-12">
        <h3>Страница управления жанрами</h3>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
        Добавить новый жанр
        </button>
    </div>
    <!-- add Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Добавление нового жанра</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="exampleInputCode">Код жанра</label>
                            <input id="exampleInputCode" class="form-control" type="text" name="newGenreCode"/>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputName">Название жанра</label>
                            <input type="text" class="form-control" name="newGenreTitle" id="exampleInputName">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputListOrder">Порядковый номер</label>
                            <input type="number" class="form-control" name="newGenreListOrder" id="exampleInputListOrder">
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
    <!-------------------------------- ДОБАВЛЕНИЕ ЖАНРОВ -------------------------------->
    
    <!-------------------------------- ПРОСМОТР ЖАНРОВ -------------------------------->
    <div class="col-xl-7 col-lg-8 col-md-10 col-sm-12 col-12">
        <table style="margin-top: 10px;" class="table table-striped table-responsive-sm">
            <tr>
                <th scope="col">N</th>
                <th scope="col">Название жанра</th>
                <th scope="col">Код жанра</th>
                <th scope="col" colspan="5">Действия</th>
            </tr>
        <?php
        $allGenresOrderedByListOrder=mysqli_query($link,"SELECT * FROM genres ORDER BY listOrder");
        while($oneGenre=mysqli_fetch_assoc($allGenresOrderedByListOrder)){
            echo '<tr><td>'.$oneGenre['listOrder'].'</td>
            <td scope="row">'.$oneGenre['title'].'</td>
            <td>'.$oneGenre['code'].'</td>
            <td>
            <form action="" method="post">
            <input type="hidden" name="codeToEdit" value="'.$oneGenre['id'].'" />
            <input type="hidden" name="editPostValueCode" value="'.$oneGenre['code'].'" />
            <input type="hidden" name="editPostValueTitle" value="'.$oneGenre['title'].'" />
            <input type="hidden" name="editPostValueListOrder" value="'.$oneGenre['listOrder'].'" />
            <i onclick="this.parentElement.submit()" class="fa fa-pencil-square-o" style="color: green; cursor: pointer;" aria-hidden="true"></i>
            </form>
            </td>
            <td>
            <form action="" method="post">';
            if($oneGenre['listOrder']!=1){
                echo '<input type="hidden" name="codeToUp" value="'.$oneGenre['listOrder'].'" />
                <input type="hidden" name="idToUp" value="'.$oneGenre['id'].'" />
                <i onclick="this.parentElement.submit()" class="fa fa-arrow-up" style="color: blue; cursor: pointer;" aria-hidden="true"></i>
                </td>';
            }
            echo '</form>
            <td>
            <form action="" method="post">';
            $genreListOrderCount=mysqli_query($link,"SELECT COUNT(listOrder) AS total FROM genres");
            $countOrder=mysqli_fetch_assoc($genreListOrderCount);
            if($oneGenre['listOrder']<$countOrder['total']){
                echo '<input type="hidden" name="codeToDown" value="'.$oneGenre['listOrder'].'" />
                <input type="hidden" name="idToDown" value="'.$oneGenre['id'].'" />
                <i onclick="this.parentElement.submit()" class="fa fa-arrow-down" style="color: blue; cursor: pointer;" aria-hidden="true"></i>
                </td>';
            }
            echo '</form>
            <td>
            <form action="" method="post">
            <input type="hidden" name="codeToDelete" value="'.$oneGenre['listOrder'].'" />
            <input type="hidden" name="genreCodeToDelete" value="'.$oneGenre['code'].'" />
            <i onclick="this.parentElement.submit()" class="fa fa-window-close" style="color: red; cursor: pointer;" aria-hidden="true"></i>
            </form>
            </td>
            </tr>';
            }
        ?>
        </table>
    </div> <!-- END COL -->
<!-------------------------------- ПРОСМОТР ЖАНРОВ -------------------------------->

</div><!-- END ROW -->
</body>
</html>