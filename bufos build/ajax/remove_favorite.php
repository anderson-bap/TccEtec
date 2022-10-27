<?php
    $produto=$_GET["produto"];
    $cliente=$_GET["cliente"];

    include("../config.php");

    $sql = "DELETE FROM `favoritos` WHERE `cod_produto`='$produto' AND `cod_cliente`='$cliente'";
    $mysqli->query($sql);
?>