<?php
    session_start();
    include("../config.php");

    $sql="SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
    $result=$mysqli->query($sql);
    $detalhe_pedido=$result->fetch_assoc();
    
    $produtos=$detalhe_pedido["produtos"];
	        
    $items=explode(";",$produtos);
    
    $search_produto="/p".$_GET["cod"]."/";
    
    $produtos_result="";
    
    foreach($items as $item){
        if($item=="")
            break;
        
        if(preg_match("$search_produto",$item))
            $item="";
        
        if($item=="")
            $produtos_result=$produtos_result.$item;
        else
            $produtos_result=$produtos_result.$item.";";
    }
    $sql="UPDATE `detalhe_pedido` SET `produtos`='$produtos_result' WHERE `detalhe_pedido`.`cod_detalhe_pedido`='".$detalhe_pedido["cod_detalhe_pedido"]."'";
    $mysqli->query($sql);
    $_SESSION["quantidade_carrinho"]-=1;
?>