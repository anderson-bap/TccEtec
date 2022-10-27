<?php
	session_start();
	
	include("config.php");
	
	$sql="DELETE FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
	$mysqli->query($sql);
	$mysqli->close();
	
	unset(
		$_SESSION["loggedin"],
		$_SESSION["cod_cliente"],
		$_SESSION["last_product"],
		$_SESSION["quantidade_carrinho"]
	);
	header("location: login.php");
	exit;
?>