<?php
	include("../config.php");

	if(isset($_GET["cod"])){
		$sql="SELECT * FROM cargos WHERE cod_cargo=".$_GET["cod"];
		$result=$mysqli->query($sql);

		if($result->num_rows>0){
			$cargo=$result->fetch_assoc();
			$titulo="Dados: ".$cargo["nome_cargo"];
		}
	}else
		die("404 NOT FOUND");

	$adm=true;
	include("../head-foot/header_empresa.php");

	if(isset($_POST["submit"])){
		if($_POST["delete"]=="TYUBNV134"){
			$sql="DELETE FROM cargos WHERE cod_cargo=".$_GET["cod"];
			$mysqli->query($sql);
			header("location: view_cargo.php");
		}else
			$delete_err="Senha de acesso inválida";
	}

	unset(
		$_SESSION["nome"],
		$_SESSION["descricao"],
		$_SESSION["salario"]
	);
?>

	<a href="view_cargo.php" class="back">
		<i class="bi bi-arrow-left-circle-fill"></i>
	</a>

<?php
	if(!isset($cargo)){
		echo "
			<div class='d-flex justify-content-center alert-empresa'>
					<div class='alert alert-danger'>Cargo inexistente!</div>
			</div>
		";
	}else{
		echo "
			<h2 class='text-center title-adm text-white'>Dados do cargo</h2>
			<div class='dados d-flex justify-content-center'>
				<div class='cargo'>
					<header>
						<b>Nome:</b>
						<b>Desrição:</b>
						<b>Salário:</b>
					</header>
					<div>
						<p>".$cargo["nome_cargo"]."</p>
						<p>".$cargo["descricao_cargo"]."</p>
						<p>R$".$cargo["salario_cargo"].",00</p>
					</div>
				</div>
			</div>
			<a href='register_cargo.php?cod=".$cargo["cod_cargo"]."' class='alterar-empresa text-decoration-none d-flex align-items-center justify-content-center' text='alterar'>
				<i class='bi bi-pen text-white fs-2'></i>
			</a>
			<button type='button' class='excluir-empresa text-decoration-none d-flex align-items-center justify-content-center border-0' text='excluir' data-bs-toggle='modal' data-bs-target='#delete'>
				<i class='bi bi-trash text-white fs-2'></i>
			</button>
		";
	}

	if(isset($cargo)):

		if(isset($_POST["close"]))
			unset($delete_err);
?>
	<div class="modal fade <?php
										if(isset($delete_err)&&!isset($_POST["close"]))
											echo "show";
									?>"
	id="delete"
	style="<?php
				if(isset($delete_err)&&!isset($_POST["close"]))
					echo "display: block;";
				else
					echo "display: none;";
			?>">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<header class="modal-header pe-4">
					<h4 class="modal-title">Excluir cargo</h4>
					<form action="" method="post">
						<button type="submit" class="btn-close" data-bs-dismiss="modal" name="close"></button>
					</form>
				</header>
				<div class="modal-body text-center pb-3 pt-4">
					<p class="text-center">Preencha o campo com a chave de acesso</p>
					<form action="" method="post" id="form-delete">
						<input type="password" name="delete" class="form-control">
						<span class="text-danger"><?php if(isset($delete_err)) echo $delete_err; ?></span>
					</form>
				</div>
				<footer class="modal-footer">
					<button type="submit" class="col btn btn-outline-primary" form="form-delete" name="submit">Confirmar</button>
					<form action="" method="post">
						<button class="col btn btn-outline-danger" data-bs-dismiss="modal" name="close">Cancelar</button>
					</form>
				</footer>
			</div>
		</div>
	</div>
	<script src="../scripts/main.js"></script>
<?php
	endif;

	$mysqli->close();

	include("../head-foot/footer_empresa.php");
?>