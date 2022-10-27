<?php
	include("../config.php");

	if(isset($_GET["cod"])){
		$sql="SELECT * FROM cargos WHERE cod_cargo=".$_GET["cod"];
		$result=$mysqli->query($sql);
		if($result->num_rows>0){
			$cargo=$result->fetch_assoc();
			$titulo="Alterar: ".$cargo["nome_cargo"];
		}else
			$titulo="Cargo inexistente!";
	}else
		$titulo="Cadastrar cargo";

	$adm=true;
	include("../head-foot/header_empresa.php");

	if(isset($_SESSION["nome"])&&!isset($_GET["cod"])){
		$_SESSION["nome"]=null;
		$_SESSION["descricao"]=null;
		$_SESSION["salario"]=null;
	}

	$nome=$descricao=$salario="";
	$nome_err=$descricao_err=$salario_err="";

	$tipo_nome=true;  //true para nome de pessoa e cargo e false para nome de empresa
	$tipo_descricao=false;  //true para descrição de produto e false para cargo
	$name_button="Cadastrar";

	if(isset($_GET["cod"]))
		$name_button="Alterar";

	if(isset($_POST["preview"])){

		include ("../valida/nome.php");
		include ("../valida/descricao.php");
		include ("../valida/salario.php");
		
		$_SESSION["nome"]=$_POST["nome"];
		$_SESSION["descricao"]=$_POST["descricao"];
		$_SESSION["salario"]=$_POST["salario"];

		if(
			empty($nome_err)&&
			empty($descricao_err)&&
			empty($salario_err)
		){  
			$_SESSION["preview"]="
					<div class='preview-empresa'>
						<div class='p-4'>
							<div class='preview-cargo'>
								<header>
									<b>Nome:</b>
									<b>Desrição:</b>
									<b>Salário:</b>
								</header>
								<div>
									<p>$nome</p>
									<p>$descricao</p>
									<p>R$$salario,00</p>
								</div>
							</div>
							<div>
								<form action='' method='post'>
									<input type='hidden' name='nome_db' value='$nome'>
									<input type='hidden' name='descricao_db' value='$descricao'>
									<input type='hidden' name='salario_db' value='$salario'>
									<div>
										<button type='submit' class='btn btn-primary' name='cadastrar'>$name_button</button>
										<button type='submit' class='btn text-white' name='cancelar'>Cancelar</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				";
		}
	}

	if(isset($_POST["cancelar"]))
		$_SESSION["preview"]="";

	if(isset($_POST["cadastrar"])){
		if(isset($_GET["cod"]))
			$sql="UPDATE `cargos` SET `nome_cargo`='".$_POST["nome_db"]."', `descricao_cargo`='".$_POST["descricao_db"]."', `salario_cargo`='".$_POST["salario_db"]."' WHERE `cargos`.`cod_cargo`=".$_GET["cod"];
		else
			$sql="INSERT INTO `cargos` (`cod_cargo`, `nome_cargo`, `descricao_cargo`, `salario_cargo`) VALUES (NULL, '".$_POST["nome_db"]."', '".$_POST["descricao_db"]."', '".$_POST["salario_db"]."')";

		if($mysqli->query($sql)){
			$_SESSION["preview"]="";
			$_POST=array();
			unset(
				$_SESSION["nome"],
				$_SESSION["descricao"],
				$_SESSION["salario"]
			);
			if(isset($_GET["cod"])){
				header("location: profile_cargo.php?cod=".$_GET["cod"]);
				exit;
			}else{
				header("location: view_cargo.php");
				exit;
			}
		}else
			echo "<div class='alert alert-danger'>Ops! Algo deu errado. Por favor, tente novamente mais tarde.</div>";
	}
?>
	<a href="<?php
			if(isset($cargo))
				echo "profile_cargo.php?cod=".$cargo["cod_cargo"];
			else
				echo "view_cargo.php";
		?>" class="back">
		<i class="bi bi-arrow-left-circle-fill"></i>
	</a>
<?php
	if(
		(isset($_GET["cod"])&&isset($cargo))||!isset($_GET["cod"])
	):
?>
	<h2 class="text-center text-white title-adm"><?php if(isset($_GET["cod"])) echo "Alterar: <span class='display-6 fs-2'>".$cargo["nome_cargo"]."</span>"; else echo "Cadastrar cargo"; ?></h2>

	<div class="register-empresa cargo">

		<form action="" method="post" id="form-register" autocomplete="off">
			<div class="form-floating mb-2">
				<input type="text" name="nome"
				class="form-control <?php
												echo (!empty($nome_err)) ? 'is-invalid' : '';
										?>"
				value="<?php
								if((isset($cargo)&&!isset($_SESSION["nome"]))||(isset($cargo)&&isset($_SESSION["nome"])&&$_SESSION["nome"]==""))
									echo $cargo["nome_cargo"];

								if(isset($_SESSION["nome"])&&$_SESSION["nome"]!="")
									echo $_SESSION["nome"];
						?>"
				placeholder="nome do cargo">
				<label>nome</label>
				<span class="color-pink">
					<?php echo $nome_err; 
				?>
				</span>
			</div>
			<div class="form-floating mb-2">
				<textarea name="descricao" placeholder="descricao" maxlength="255"
				class="form-control <?php
												echo (!empty($descricao_err)) ? 'is-invalid' : '';
										?>"
				><?php
						if((isset($cargo)&&!isset($_SESSION["descricao"]))||(isset($cargo)&&isset($_SESSION["descricao"])&&$_SESSION["descricao"]==""))
							echo $cargo["descricao_cargo"];

						if(isset($_SESSION["descricao"])&&$_SESSION["descricao"]!="")
							echo $_SESSION["descricao"];
					?></textarea>

				<label>descriçao do cargo</label>
				
				<span class="color-pink">
					<?php echo $descricao_err; ?>
				</span>
			</div>
			<div class="form-floating mb-2">
					<input type="text" name="salario" id="salario"
					class="form-control <?php
													echo (!empty($salario_err)) ? 'is-invalid' : '';
											?>"
					value="<?php
									if((isset($cargo)&&!isset($_SESSION["salario"]))||(isset($cargo)&&isset($_SESSION["salario"])&&$_SESSION["salario"]==""))
										echo $cargo["salario_cargo"];

									if(isset($_SESSION["salario"])&&$_SESSION["salario"]!="")
										echo $_SESSION["salario"];
							?>"
					placeholder="descriçao">
					<label>salario do cargo (R$)</label>
					<span class="color-pink">
						<?php echo $salario_err; ?>
					</span>
			</div>
		</form>
	</div>
	<button type="submit" class="btn-preview-empresa border-0 d-flex align-items-center justify-content-center" form="form-register" text="<?php if(isset($cargo)) echo "salvar"; else echo "cadastrar"; ?>" name="preview">
		<span class="iconify fs-2 text-white" data-icon="fa:floppy-o"></span>
	</button>
	<script>
		$('#salario').mask('0000');
	</script>
	<?php
		endif;
		if(isset($_GET["cod"])&&!isset($cargo))
			echo "<div class='alert alert-danger text-center mx-auto' style='width: 30rem;'>Cargo inexistente!</div>";
	
		$mysqli->close();

		if(isset($_SESSION["preview"]))
			echo $_SESSION["preview"];

		include("../head-foot/footer_empresa.php");
	?>