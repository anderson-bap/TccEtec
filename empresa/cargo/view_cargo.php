<?php
	$titulo="Cargos";
	$adm=true;
	include("../head-foot/header_empresa.php");

	unset(
		$_SESSION["nome"],
		$_SESSION["descricao"],
		$_SESSION["salario"]
	);
?>
	<a href="register_cargo.php" class="plus" text="cadastrar">
		<i class="bi bi-plus-circle-fill"></i>
	</a>
	<a href="../adm.php" class="back">
	<i class="bi bi-arrow-left-circle-fill"></i>
	</a>

	<div class="container px-5">
		<form action="" method="get" class="mb-3">
			<input type="search" class="form-control" placeholder="Pesquisar cargos" name="search" value="<?php if(isset($_GET["search"])) echo $_GET["search"]; ?>" id="search">
		</form>
	</div>
<?php

	if(isset($_GET["search"])&&$_GET["search"]!=""){
		$search=trim($_GET["search"]);
		$sql="SELECT * FROM `cargos` WHERE `nome_cargo` LIKE '%$search%' OR `descricao_cargo` LIKE '%$search%'";
	}else
		$sql="SELECT * FROM `cargos` ORDER BY `cargos`.`salario_cargo` DESC";

	$result=$mysqli->query($sql);

	if($result){
		if($result->num_rows>0){
			echo "
		<div class='container px-5'>
			<div class='list'>
					<header class='mb-2 pb-3 px-4 cargo'>
						<div class='text-center text-white fs-5'>Nome</div>
						<div class='text-center text-white fs-5'>Descrição</div>
						<div class='text-center text-white fs-5'>Salário</div>
					</header>
					<main>
			";

			while($cargo=$result->fetch_assoc()){
					$descricao=$cargo["descricao_cargo"];

					if(strlen($descricao)>60)
						$descricao=substr($descricao,0,60)."...";

					echo "
						<a href='profile_cargo.php?cod=".$cargo["cod_cargo"]."' class='link-list mb-2 pb-2 text-decoration-none px-4 cargo align-items-center'>
							<div class='text-center'>".$cargo["nome_cargo"]."</div>
							<div class='text-center'>$descricao</div>
							<div class='text-center'>R$".$cargo["salario_cargo"].",00</div>
						</a>
					";
			}
			echo "
					</main>
			</div>
		</div>
			";
		}else{
			if(isset($_GET["search"]))
				echo "<div class='alert alert-danger mx-auto text-center' style='width: 20rem'>Cargo não encontrado</div>";
			else
				echo "<div class='alert alert-danger mx-auto' style='width: 20rem'>Não existem cargos cadastrados</div>";

			echo "
				<script>
					document.getElementById('search').focus();
					document.getElementById('search').value='';
				</script>
			";
		}
	}

	$mysqli->close();

	include("../head-foot/footer_empresa.php");
?>