<?php
    $produto=$_GET["produto"];
    $cliente=$_GET["cliente"];

    include("../config.php");

    $sql = "INSERT INTO `favoritos` (`cod_favorito`, `cod_produto`, `cod_cliente`) VALUES (NULL, '$produto', '$cliente')";
    $mysqli->query($sql);
?>