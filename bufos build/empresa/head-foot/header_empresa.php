<?php
	if(!isset($session_start))
		session_start();

	if(file_exists("config.php"))
		require_once "config.php";
	else
		require_once "../config.php";
		

	if(isset($adm)){
		if(isset($_SESSION["logged_funcionario"])){
			if(isset($_SESSION["pagina"])&&$_SESSION["pagina"]){
				if(isset($adm_main_page)){
					header("location: produto/estoque.php");
					exit;
				}else{
					header("location: ../produto/estoque.php");
					exit;
				}
			}
		}else{
			if(isset($adm_main_page)){
				header("location: login_funcionario.php");
				exit;
			}else{
				header("location: ../login_funcionario.php");
				exit;
			}
		}
	}elseif(isset($abastecedor)){
		if(isset($_SESSION["logged_funcionario"])){
			if(isset($_SESSION["pagina"])&&!$_SESSION["pagina"]){
				if(isset($adm_main_page)){
					header("location: adm.php");
					exit;
				}else{
					header("location: ../adm.php");
					exit;
				}
			}
		}else{
			if(isset($adm_main_page)){
				header("location: login_funcionario.php");
				exit;
			}else{
				header("location: ../login_funcionario.php");
				exit;
			}
		}
	}

	$sql="SELECT * FROM funcionarios WHERE cod_funcionario='".$_SESSION["cod_funcionario"]."'";

	$result=$mysqli->query($sql);
	$funcionario_logged=$result->fetch_assoc();

	$foto=$funcionario_logged["foto"];

	if(isset($adm_main_page)){
		if(preg_match("$../$",$foto))
		$foto=str_replace("../","",$foto);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Alberto Frigatto, Anderson Baptista, Herbert dos Santos, Leandro Nogueira, Rodrigo Barcelos">
   <meta name="description" content="Sistema da empresa Bufos Regulares para o gerenciamento da mesma">
	<title><?php echo $titulo; ?></title>
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<link rel="shortcut icon" href="<?php if(isset($adm_main_page)) echo "img/favicon.png"; else echo "../img/favicon.png"; ?>" type="image/x-icon">
	<link rel="stylesheet" href="<?php if(!isset($adm_main_page)) echo "../"; ?>CSS/style.min.css?35">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="../libs/jQuery-Mask-Plugin-master/src/jquery.mask.js?2"></script>
</head>
<body>

<nav class="navbar navbar-empresa mb-5">
	<div class="container">
		<a href="<?php
						if(isset($adm)){
							if(isset($adm_main_page))
									echo "adm.php";
							else
									echo "../adm.php";
						}else
							echo "estoque.php";
					?>" class="navbar-brand">
			<img src="<?php if(isset($adm_main_page)) echo "img/logo.png"; else echo "../img/logo.png"; ?>" alt="Bufos Regulares">
		</a>
		<ul class="navbar-nav ms-auto">
			<li class="nav-item">
				<div class="d-flex align-items-center">
					<img src="<?php echo $foto; ?>" alt="<?php echo $funcionario_logged["nome"]." ".$funcionario_logged["sobrenome"]; ?>" class="me-2">
					<span><?php echo $funcionario_logged["nome"]." ".$funcionario_logged["sobrenome"]; ?></span>
				</div>
			</li>
			<li class="nav-item ms-3">
				<button type="button" class="nav-link color-pink bg-transparent border-0" data-bs-toggle="modal" data-bs-target="#logout">
					<i class="bi bi-box-arrow-right"></i>
					<span class="color-pink">Sair</span>
				</button>
			</li>
		</ul>
	</div>
</nav>

<div class="modal fade" id="logout">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<header class="modal-header pe-4">
				<h4 class="modal-title">Logout</h4>
				<form action="" method="post">
					<button type="submit" class="btn-close" data-bs-dismiss="modal" name="close"></button>
				</form>
			</header>
			<div class="modal-body text-center pb-3 pt-4">
				<p class="text-center">Deseja sair?</p>
			</div>
			<footer class="modal-footer">
				<a href="<?php if(!isset($adm_main_page)) echo "../"; ?>logout.php" class="col btn btn-outline-primary">Sair</a>
				<button class="col btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
			</footer>
		</div>
	</div>
</div>