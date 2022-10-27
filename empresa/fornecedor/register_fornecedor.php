<?php
	include("../config.php");

	if(isset($_GET["cod"])){
		$sql="SELECT * FROM fornecedores WHERE cod_fornecedor=".$_GET["cod"];
		$result=$mysqli->query($sql);
		if($result->num_rows>0){
			$fornecedor=$result->fetch_assoc();
			$titulo="Alterar: ".$fornecedor["nome_fantasia"];
		}else
			$titulo="Fornecedor inexistente!";
	}else
		$titulo="Cadastrar fornecedor";

	$adm=true;
	include("../head-foot/header_empresa.php");

	if(isset($fornecedor))
		$_SESSION["foto_original"]=$fornecedor["foto"];

	if(isset($_SESSION["nome"])&&!isset($_GET["cod"])){
		$_SESSION["razao_social"]=null;
		$_SESSION["nome"]=null;
		$_SESSION["cnpj"]=null;
		$_SESSION["cep"]=null;
		$_SESSION["complemento"]=null;
		$_SESSION["telefone"]=null;
		$_SESSION["celular"]=null;
		$_SESSION["email"]=null;
		$_SESSION["website"]=null;
	}

	$razao_social=$nome=$foto=$cnpj=$cep=$complemento=$telefone=$celular=$email=$website="";
	$razao_social_err=$nome_err=$foto_err=$cnpj_err=$cep_err=$complemento_err=$telefone_err=$celular_err=$email_err=$website_err="";

	$dir="../img/fornecedor/";

	$cod="cod_fornecedor";
	$table="fornecedores";
	$tipo_nome=false;  //true para nome de pessoa e cargo e false para nome de empresa
	$check_email=true;
	$check_cnpj=true;
	$name_button="Cadastrar";

	if(isset($_GET["cod"])){
		$check_email=false;
		$check_cnpj=false;
		$name_button="Alterar";
	}

	if(isset($_POST["preview"])){

		include ("../valida/nome.php");
		include ("../valida/foto.php");
		include ("../valida/cnpj.php");
		include ("../valida/cep.php");
		include ("../valida/complemento-endereco.php");
		include ("../valida/telefone.php");
		include ("../valida/email.php");
		include ("../valida/website.php");
		
		$_SESSION["razao_social"]=$_POST["razao_social"];
		$_SESSION["nome"]=$_POST["nome"];
		$_SESSION["cnpj"]=$_POST["cnpj"];
		$_SESSION["cep"]=$_POST["cep"];
		$_SESSION["complemento"]=$_POST["complemento"];
		$_SESSION["telefone"]=$_POST["telefone"];
		$_SESSION["celular"]=$_POST["celular"];
		$_SESSION["email"]=$_POST["email"];
		$_SESSION["website"]=$_POST["website"];

		if(
			empty($razao_social_err)&&
			empty($nome_err)&&
			empty($cnpj_err)&&
			empty($cep_err)&&
			empty($telefone_err)&&
			empty($celular_err)&&
			empty($email_err)&&
			empty($website_err)
		){ 
			if(!move_uploaded_file($file["tmp_name"],$path))
				$foto_err="Falha ao enviar o arquivo";
			else{
				move_uploaded_file($file["tmp_name"],$path);

				$_SESSION["foto"]=$path;

				$_SESSION["preview"]="
					<div class='preview-empresa'>
						<div class='p-4 fornecedor'>
							<div class='row mb-1'>
								<div class='col'>
									<p class='mb-1'><b class='me-2'>Nome fantasia:</b>$nome</p>
									<p class='mb-1'><b class='me-2'>Razão social:</b>$razao_social</p>
									<p class='mb-4'><b class='me-2'>CNPJ:</b>".$_POST["cnpj"]."</p>

									<h5 class='mb-3 border-bottom-pink'>Contato</h5>
									<p class='mb-1'><b class='me-2'>Telefone:</b>".$_POST["telefone"]."</p>
									<p class='mb-1'><b class='me-2'>Celular:</b>".$_POST["celular"]."</p>
									<p class='mb-1'><b class='me-2'>Email:</b>$email</p>
									<p class='mb-4'><b class='me-2'>Website:</b>$website</p>
								</div>
								<div class='col-4'>
									<img src='".$_SESSION["foto"]."' alt='$nome' class='img-fluid'>
								</div>
							</div>
							<h5 class='mb-3 border-bottom-pink'>Endereço</h5>
							<p class='mb-1'><b class='me-2'>CEP:</b>$cep</p>
							<p class='mb-1'><b class='me-2'>Endereço:</b>".$_SESSION["endereco"]."</p>
							<p class='mb-3'><b class='me-2'>Complemento:</b>$complemento</p>
							<div>
								<form action='' method='post'>
									<input type='hidden' name='nome_fantasia_db' value='$nome'>
									<input type='hidden' name='razao_social_db' value='$razao_social'>
									<input type='hidden' name='foto_db' value='".$_SESSION["foto"]."'>
									<input type='hidden' name='telefone_db' value='$telefone'>
									<input type='hidden' name='celular_db' value='$celular'>
									<input type='hidden' name='cnpj_db' value='$cnpj'>
									<input type='hidden' name='cep_db' value='$cep'>
									<input type='hidden' name='complemento_db' value='$complemento'>
									<input type='hidden' name='email_db' value='$email'>
									<input type='hidden' name='website_db' value='$website'>
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
	}

	function cancelar(){
		$_SESSION["preview"]="";
		if($_SESSION["foto"]!="")
			unlink($_SESSION["foto"]);
		$_SESSION["foto"]="";
		$_SESSION["endereco"]="";
	}

	if(isset($_POST["cancelar"]))
		cancelar();

	if(isset($_POST["cadastrar"])){
		if(isset($_GET["cod"])){
			$sql="UPDATE `fornecedores` SET `razao_social`='".$_POST["razao_social_db"]."', `nome_fantasia`='".$_POST["nome_fantasia_db"]."', `foto`='".$_POST["foto_db"]."', `cnpj`='".$_POST["cnpj_db"]."', `cep`='".$_POST["cep_db"]."', `complemento`='".$_POST["complemento_db"]."', `telefone`='".$_POST["telefone_db"]."', `celular`='".$_POST["celular_db"]."', `email`='".$_POST["email_db"]."', `website`='".$_POST["website_db"]."' WHERE `fornecedores`.`cod_fornecedor`=".$_GET["cod"];

			unlink($_SESSION["foto_original"]);
		}else
			$sql="INSERT INTO `fornecedores` (`cod_fornecedor`, `razao_social`, `nome_fantasia`, `foto`, `cnpj`, `cep`, `complemento`,`telefone`, `celular`, `email`, `website`) VALUES (NULL, '".$_POST["razao_social_db"]."', '".$_POST["nome_fantasia_db"]."', '".$_POST["foto_db"]."', '".$_POST["cnpj_db"]."', '".$_POST["cep_db"]."', '".$_POST["complemento_db"]."', '".$_POST["telefone_db"]."', '".$_POST["celular_db"]."', '".$_POST["email_db"]."', '".$_POST["website_db"]."')";

		if($mysqli->query($sql)){
			$_SESSION["preview"]="";
			$_POST=array();
			unset(
				$_SESSION["razao_social"],
				$_SESSION["nome"],
				$_SESSION["cnpj"],
				$_SESSION["cep"],
				$_SESSION["complemento"],
				$_SESSION["telefone"],
				$_SESSION["celular"],
				$_SESSION["email"],
				$_SESSION["website"],
				$_SESSION["foto"],
				$_SESSION["endereco"],
				$_SESSION["foto_original"]
			);
			if(isset($_GET["cod"])){
				header("location: profile_fornecedor.php?cod=".$_GET["cod"]);
				exit;
			}else{
				header("location: view_fornecedor.php");
				exit;
			}
		}else
			echo "<div class='alert alert-danger'>Ops! Algo deu errado. Por favor, tente novamente mais tarde.</div>";
	}
?>
	<a href="<?php
			if(isset($fornecedor))
				echo "profile_fornecedor.php?cod=".$fornecedor["cod_fornecedor"];
			else
				echo "view_fornecedor.php";
		?>" class="back">
		<i class="bi bi-arrow-left-circle-fill"></i>
	</a>
<?php
	if(
		(isset($_GET["cod"])&&isset($fornecedor))||
		!isset($_GET["cod"])
	):
?>
	<h2 class="text-center text-white"><?php if(isset($_GET["cod"])) echo "Alterar: <span class='display-6 fs-2'>".$fornecedor["nome_fantasia"]."</span>"; else echo "Cadastrar fornecedor"; ?></h2>

	<div class="register-empresa fornecedor">
		<form action="" method="post" enctype="multipart/form-data" id="form-register" autocomplete="off">
		<div class='row mb-2'>
			<div class='col'>
			
			<div class="form-floating mb-2">
				<input type="text" name="nome"
				class="form-control <?php
												echo (!empty($nome_err)) ? 'is-invalid' : '';
										?>"
				value="<?php
								if((isset($fornecedor)&&!isset($_SESSION["nome"]))||(isset($fornecedor)&&isset($_SESSION["nome"])&&$_SESSION["nome"]==""))
									echo $fornecedor["nome_fantasia"];
									
								if(isset($_SESSION["nome"])&&$_SESSION["nome"]!="")
									echo $_SESSION["nome"];
						?>"
				placeholder="nome">
				<label>Nome fantasia</label>
				<span class="color-pink"><?php echo $nome_err; ?></span>
			</div>

			<?php
				if(!isset($fornecedor)){
					echo "
						</div>
						<div class='col'>
					";
				}
			?>

			<div class="form-floating mb-2">
				<input type="text" name="razao_social"
				class="form-control <?php
												echo (!empty($razao_social_err)) ? 'is-invalid' : '';
										?>"
				value="<?php
								if((isset($fornecedor)&&!isset($_SESSION["razao_social"]))||(isset($fornecedor)&&isset($_SESSION["razao_social"])&&$_SESSION["razao_social"]==""))
									echo $fornecedor["razao_social"];
								
								if(isset($_SESSION["razao_social"])&&$_SESSION["razao_social"]!="")
									echo $_SESSION["razao_social"];
						?>"
				placeholder="razao_social">
				<label>Razão Social</label>
				<span class="color-pink"><?php echo $razao_social_err; ?></span>
			</div>

			<?php
				if(isset($fornecedor)){
					echo "
							</div>
							<div class='col-3'>
								<img src='".$_SESSION["foto_original"]."' class='img-fluid' alt='".$fornecedor["nome_fantasia"]."'>
							</div>
						</div>
					";
				}else{
					echo "
							</div>
						</div>
					";
				}
			?>

			<div class="row mb-4 align-items-center">
				<div class="col-4">
					<div class="form-floating">
						<input type="text" name="cnpj" id="cnpj"
						class="form-control <?php
														echo (!empty($cnpj_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($fornecedor)&&!isset($_SESSION["cnpj"]))||(isset($fornecedor)&&isset($_SESSION["cnpj"])&&$_SESSION["cnpj"]==""))
											echo $fornecedor["cnpj"];
								
										if(isset($_SESSION["cnpj"])&&$_SESSION["cnpj"]!="")
											echo $_SESSION["cnpj"];
								?>"
						placeholder="cnpj">
						<label>Cnpj</label>
						<span class="color-pink"><?php echo $cnpj_err; ?></span>
					</div>
				</div>
				<div class="col">
					<label class="text-white">Foto de perfil</label><br>
					<input type="file" name="foto" class="form-control">
					<span class="color-pink"><?php echo $foto_err; ?></span>
				</div>
			</div>
			<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Contato</h3>
			<div class="row mb-4">
				<div class="col-3">
					<div class="form-floating mb-2">
						<input type="text" name="telefone" id="telefone"
						class="form-control <?php
														echo (!empty($telefone_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($fornecedor)&&!isset($_SESSION["telefone"]))||(isset($fornecedor)&&isset($_SESSION["telefone"])&&$_SESSION["telefone"]==""))
											echo $fornecedor["telefone"];
							
										if(isset($_SESSION["telefone"])&&$_SESSION["telefone"]!="")
											echo $_SESSION["telefone"];
								?>"
						placeholder="telefone">
						<label>Telefone</label>
						<span class="color-pink"><?php echo $telefone_err; ?></span>
					</div>
				</div>
				<div class="col-9">
					<div class="form-floating mb-2">
						<input type="email" name="email"
						class="form-control <?php
														echo (!empty($email_err)) ? 'is-invalid' : '';
														?>"
						value="<?php
										if((isset($fornecedor)&&!isset($_SESSION["email"]))||(isset($fornecedor)&&isset($_SESSION["email"])&&$_SESSION["email"]==""))
										echo $fornecedor["email"];
										
										if(isset($_SESSION["email"])&&$_SESSION["email"]!="")
										echo $_SESSION["email"];
										?>"
						placeholder="email">
						<label>Email</label>
						<span class="color-pink"><?php echo $email_err; ?></span>
					</div>
				</div>
				<div class="col-3">
					<div class="form-floating mb-2">
						<input type="text" name="celular" id="celular"
						class="form-control <?php
														echo (!empty($celular_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($fornecedor)&&!isset($_SESSION["celular"]))||(isset($fornecedor)&&isset($_SESSION["celular"])&&$_SESSION["celular"]==""))
											echo $fornecedor["celular"];
							
										if(isset($_SESSION["celular"])&&$_SESSION["celular"]!="")
											echo $_SESSION["celular"];
								?>"
						placeholder="celular">
						<label>Celular</label>
						<span class="color-pink"><?php echo $celular_err; ?></span>
					</div>
				</div>
				<div class="col-9">
					<div class="form-floating mb-2">
						<input type="text" name="website"
						class="form-control <?php
														echo (!empty($website_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($fornecedor)&&!isset($_SESSION["website"]))||(isset($fornecedor)&&isset($_SESSION["website"])&&$_SESSION["website"]==""))
											echo $fornecedor["website"];
						
										if(isset($_SESSION["website"])&&$_SESSION["website"]!="")
											echo $_SESSION["website"];
								?>"
						placeholder="website">
						<label>Website</label>
						<span class="color-pink"><?php echo $website_err; ?></span>
					</div>
				</div>
			</div>
			<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Endereço</h3>
			<div class="row mb-2">
				<div class="col-3">
					<div class="form-floating">
						<input type="text" name="cep" id="cep" class="form-control <?php echo (!empty($cep_err)) ? 'is-invalid' : ''; ?>"
						value="<?php
										if((isset($fornecedor)&&!isset($cep))||(isset($fornecedor)&&empty($cep_err)&&$cep==""))
											echo $fornecedor["cep"];
										elseif(isset($_SESSION["cep"])&&$_SESSION["cep"]!="")
											echo $_SESSION["cep"];
								?>"
						placeholder="cep">
						<label>Cep</label>
						<span class="color-pink"><?php echo $cep_err; ?></span>
					</div>
				</div>
				<div class="col">
					<div class="form-floating">
						<input type="text" name="complemento"
						class="form-control <?php
														echo (!empty($complemento_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($fornecedor)&&!isset($_SESSION["complemento"]))||(isset($fornecedor)&&isset($_SESSION["complemento"])&&$_SESSION["complemento"]==""))
											echo $fornecedor["complemento"];
						
										if(isset($_SESSION["complemento"])&&$_SESSION["complemento"]!="")
											echo $_SESSION["complemento"];
								?>"
						placeholder="complemento">
						<label>Complemento de endereço</label>
						<span class="color-pink"><?php echo $complemento_err; ?></span>
					</div>
				</div>
			</div>
			<div class="form-floating mb-3">
				<input type="text" id="endereco" class="form-control" placeholder="endereco" value="<?php if(isset($_SESSION["endereco"])&&$_SESSION["endereco"]!="") echo $_SESSION["endereco"] ?>" readonly>
				<label>Endereço</label>
			</div>
		</form>
	</div>
	<button type="submit" class="btn-preview-empresa border-0 d-flex align-items-center justify-content-center" form="form-register" text="<?php if(isset($fornecedor)) echo "salvar"; else echo "cadastrar"; ?>" name="preview">
		<span class="iconify fs-2 text-white" data-icon="fa:floppy-o"></span>
	</button>
	<?php
		endif;
		if(isset($_GET["cod"])&&!isset($fornecedor))
			echo "<div class='alert alert-danger text-center mx-auto' style='width: 30rem;'>Fornecedor inexistente!</div>";

		$mysqli->close();

		if(isset($_SESSION["preview"]))
			echo $_SESSION["preview"];
	?>
	<script>
		$('#telefone').mask('(00) 0000-0000');
		$('#celular').mask('(00) 00000-0000');
		$('#cnpj').mask('00.000.000/0000-00');
		$('#cep').mask('00000-000');
	</script>
	<script src="../scripts/ajax.js"></script>
<?php
	include("../head-foot/footer_empresa.php");
?>