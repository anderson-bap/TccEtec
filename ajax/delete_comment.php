<?php
    $comentario=$_GET["comentario"];

    include("../config.php");

    $sql = "DELETE FROM `comentarios` WHERE `cod_comentario`='$comentario'";
    $mysqli->query($sql);
?>