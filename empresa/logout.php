<?php
	session_start();

	unset(
		$_SESSION["pagina"],
		$_SESSION["logged_funcionario"]
	);
	header("location: login_funcionario.php");
	exit;
?>