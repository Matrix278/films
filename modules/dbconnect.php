<?php
    $host = "localhost";
    $user = "root";
    $psswd = "";
    $link = mysqli_connect($host, $user, $psswd);
    mysqli_select_db($link, "films");
    mysqli_query($link, "SET NAMES utf8");
?>
