<?php
    $cartao=$_GET["cartao"];
    $cliente=$_GET["cliente"];
    
    include("../config.php");
    
    $sql="DELETE FROM cartao WHERE cod_cartao='$cartao' AND cod_cliente='$cliente'";
    $mysqli->query($sql);
?>