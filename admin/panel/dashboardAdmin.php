<div class="row">
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        Добро пожаловать!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    </div>
    <div class="col-12 col-xl-3 col-lg-3 col-md-6 col-sm-6" style="margin-top: 20px;">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Жанров:</h5>
                <p class="card-text" style="font-size: 23px;"><i class="fa fa-archive fa-fw" aria-hidden="true"></i>
                <?php
                    $regUsers=mysqli_query($link, "SELECT * FROM genres");
                    $numrows=mysqli_num_rows($regUsers);
                    echo $numrows;
                ?></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-3 col-lg-3 col-md-6 col-sm-6" style="margin-top: 20px;">
        <div class="card text-white bg-dark">
            <div class="card-body">
                <h5 class="card-title">Фильмов:</h5>
                <p class="card-text" style="font-size: 23px;"><i class="fa fa-film fa-fw" aria-hidden="true"></i>
                <?php
                    $regUsers=mysqli_query($link, "SELECT * FROM films");
                    $numrows=mysqli_num_rows($regUsers);
                    echo $numrows;
                ?></p>
            </div>
        </div>
    </div>
</div>