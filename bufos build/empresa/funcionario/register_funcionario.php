<?php
	include("../config.php");

	if(isset($_GET["cod"])){
		$sql="SELECT * FROM funcionarios WHERE cod_funcionario=".$_GET["cod"];
		$result=$mysqli->query($sql);
		if($result->num_rows>0){
			$funcionario=$result->fetch_assoc();
			$titulo="Alterar: ".$funcionario["nome"]." ".$funcionario["sobrenome"];
		}else
			$titulo="Funcionário inexistente!";
	}else
		$titulo="Cadastrar Funcionário";

	$adm=true;
	include("../head-foot/header_empresa.php");

	if(isset($funcionario)){
		$_SESSION["foto_original"]=$funcionario["foto"];
		$_SESSION["estado_civil"]=$funcionario["estado_civil"];
		$_SESSION["cargo"]=$funcionario["cod_cargo"];
	}

	if(isset($_SESSION["nome"])&&!isset($_GET["cod"])){
		$_SESSION["nome"]=null;
		$_SESSION["sobrenome"]=null;
		$_SESSION["data_nasc"]=null;
		$_SESSION["telefone"]=null;
		$_SESSION["celular"]=null;
		$_SESSION["cpf"]=null;
		$_SESSION["cep"]=null;
		$_SESSION["complemento"]=null;
		$_SESSION["email"]=null;
		$_SESSION["login"]=null;
		$_SESSION["senha"]=null;
		$_SESSION["confirm_senha"]=null;
	}

	$nome=$sobrenome=$foto=$data_nasc=$cpf=$telefone=$celular=$estado_civil=$cep=$endereco=$complemento=$email=$login=$senha=$senha_original=$confirm_senha="";
	$nome_err=$sobrenome_err=$foto_err=$data_nasc_err=$cpf_err=$telefone_err=$celular_err=$cep_err=$endereco_err=$complemento_err=$email_err=$login_err=$senha_err=$senha_original_err=$confirm_senha_err="";

	$dir="../img/funcionario/";

	$cod="cod_funcionario";
	$table="funcionarios";
	$tipo_nome=true;  //true para nome de pessoa e cargo e false para nome de empresa
	$check_email=true;
	$check_cpf=true;
	$name_button="Cadastrar";

	if(isset($_GET["cod"])){
		$check_email=false;
		$check_cpf=false;
		$name_button="Alterar";
	}

	if(isset($_POST["preview"])){

		include ("../valida/nome.php");
		include ("../valida/sobrenome.php");
		include ("../valida/foto.php");
		include ("../valida/data_nasc.php");
		include ("../valida/telefone.php");
		include ("../valida/cpf.php");
		include ("../valida/cep.php");
		include ("../valida/complemento-endereco.php");
		include ("../valida/email.php");
		include ("../valida/login.php");
		include ("../valida/senha.php");
		
		$_SESSION["nome"]=$_POST["nome"];
		$_SESSION["sobrenome"]=$_POST["sobrenome"];
		$_SESSION["data_nasc"]=$_POST["data_nasc"];
		$_SESSION["telefone"]=$_POST["telefone"];
		$_SESSION["celular"]=$_POST["celular"];
		$_SESSION["cpf"]=$_POST["cpf"];
		$_SESSION["cep"]=$_POST["cep"];
		$_SESSION["complemento"]=$_POST["complemento"];
		$_SESSION["email"]=$_POST["email"];
		$_SESSION["login"]=$_POST["login"];
		$_SESSION["senha"]=$_POST["senha"];
		$_SESSION["confirm_senha"]=$_POST["confirm_senha"];

		$estado_civil=$_POST["estado_civil"];
		$cargo=$_POST["cargo"];

		if(
			empty($nome_err)&&
			empty($sobrenome_err)&&
			empty($data_nasc_err)&&
			empty($telefone_err)&&
			empty($celular_err)&&
			empty($cpf_err)&&
			empty($cep_err)&&
			empty($complemento_err)&&
			empty($email_err)&&
			empty($login_err)&&
			empty($senha_err)&&
			empty($confirm_senha_err)
		){
			
			if(!move_uploaded_file($file["tmp_name"],$path))
				$foto_err="Falha ao enviar o arquivo";
			else{
				move_uploaded_file($file["tmp_name"],$path);

				$_SESSION["foto"]=$path;

				$senha_original=$senha;

				$senha=password_hash($senha, PASSWORD_DEFAULT);

				$dia_data=substr($data_nasc,8,2);
				$mes_data=substr($data_nasc,5,2);
				$ano_data=substr($data_nasc,0,4);
				$data_nasc_preview=$dia_data."/".$mes_data."/".$ano_data;

				if($estado_civil==0)
						$estado_civil_preview="Solteiro(a)";
				elseif($estado_civil==1)
						$estado_civil_preview="Casado(a)";
				elseif($estado_civil==2)
						$estado_civil_preview="Divorciado(a)";
				elseif($estado_civil==3)
						$estado_civil_preview="Viúvo(a)";
				else
						$estado_civil_preview="Separado Judicialmente";

				$stmt=$mysqli->prepare("SELECT nome_cargo FROM cargos WHERE cod_cargo=?");
				$stmt->bind_param("s", $param_cod_cargo);
				$param_cod_cargo=$cargo;
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($nome_cargo);
				$stmt->fetch();
				$stmt->close();

				$_SESSION["preview"]="
					<div class='preview-empresa'>
						<div class='p-4 funcionario'>
							<div class='row mb-1'>
								<div class='col'>
									<p class='mb-1'><b class='me-2'>Nome:</b>$nome $sobrenome</p>
									<p class='mb-1'><b class='me-2'>Cargo:</b>$nome_cargo</p>
									<p class='mb-1'><b class='me-2'>CPF:</b>".$_POST["cpf"]."</p>
									<p class='mb-1'><b class='me-2'>Nascido em:</b>$data_nasc_preview</p>
									<p class='mb-4'><b class='me-2'>Estado civil:</b>$estado_civil_preview</p>
				
									<h5 class='mb-3 border-bottom-pink'>Contato</h5>
									<p class='mb-1'><b class='me-2'>Telefone:</b>".$_POST["telefone"]."</p>
									<p class='mb-1'><b class='me-2'>Celular:</b>".$_POST["celular"]."</p>
									<p class='mb-4'><b class='me-2'>Email:</b>$email</p>
								</div>
								<div class='col-4'>
									<img src='".$_SESSION["foto"]."' alt='$nome $sobrenome' class='img-fluid'>
								</div>
							</div>
							<h5 class='mb-3 border-bottom-pink'>Endereço</h5>
							<p class='mb-1'><b class='me-2'>CEP:</b>$cep</p>
							<p class='mb-1'><b class='me-2'>Endereço:</b>".$_SESSION["endereco"]."</p>
							<p class='mb-4'><b class='me-2'>Complemento:</b>$complemento</p>
							
							<h5 class='mb-3 border-bottom-pink'>Login</h5>
							<p class='mb-1'><b class='me-2'>Login:</b>$login</p>
							<p class='mb-3'><b class='me-2'>Senha:</b>$senha_original</p>
							<div>
								<form action='' method='post'>
									<input type='hidden' name='nome_db' value='$nome'>
									<input type='hidden' name='sobrenome_db' value='$sobrenome'>
									<input type='hidden' name='foto_db' value='".$_SESSION["foto"]."'>
									<input type='hidden' name='data_nasc_db' value='$data_nasc'>
									<input type='hidden' name='telefone_db' value='$telefone'>
									<input type='hidden' name='celular_db' value='$celular'>
									<input type='hidden' name='cpf_db' value='$cpf'>
									<input type='hidden' name='cep_db' value='$cep'>
									<input type='hidden' name='complemento_db' value='$complemento'>
									<input type='hidden' name='estado_civil_db' value='$estado_civil'>
									<input type='hidden' name='cargo_db' value='$cargo'>
									<input type='hidden' name='email_db' value='$email'>
									<input type='hidden' name='login_db' value='$login'>
									<input type='hidden' name='senha_original_db' value='$senha_original'>
									<input type='hidden' name='senha_db' value='$senha'>
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
			$sql="UPDATE `funcionarios` SET `nome`='".$_POST["nome_db"]."', `sobrenome`='".$_POST["sobrenome_db"]."', `foto`='".$_POST["foto_db"]."', `cpf`='".$_POST["cpf_db"]."', `data_nasc`='".$_POST["data_nasc_db"]."', `email`='".$_POST["email_db"]."', `complemento`='".$_POST["complemento_db"]."', `telefone`='".$_POST["telefone_db"]."', `celular`='".$_POST["celular_db"]."', `cep`='".$_POST["cep_db"]."', `estado_civil`='".$_POST["estado_civil_db"]."', `login`='".$_POST["login_db"]."', `senha`='".$_POST["senha_db"]."', `senha_original`='".$_POST["senha_original_db"]."', `cod_cargo`='".$_POST["cargo_db"]."' WHERE `funcionarios`.`cod_funcionario`=".$_GET["cod"];

			unlink($_SESSION["foto_original"]);
		}
		else
			$sql="INSERT INTO `funcionarios` (`cod_funcionario`, `nome`, `sobrenome`, `foto`, `cpf`, `data_nasc`, `email`, `complemento`, `telefone`, `celular`, `cep`, `estado_civil`, `login`, `senha`, `senha_original`, `cod_cargo`) VALUES (NULL, '".$_POST["nome_db"]."', '".$_POST["sobrenome_db"]."', '".$_POST["foto_db"]."', '".$_POST["cpf_db"]."', '".$_POST["data_nasc_db"]."', '".$_POST["email_db"]."', '".$_POST["complemento_db"]."', '".$_POST["telefone_db"]."', '".$_POST["celular_db"]."', '".$_POST["cep_db"]."', '".$_POST["estado_civil_db"]."', '".$_POST["login_db"]."', '".$_POST["senha_db"]."', '".$_POST["senha_original_db"]."', '".$_POST["cargo_db"]."')";

		if($mysqli->query($sql)){
			$_SESSION["preview"]="";
			$_POST=array();
			unset(
				$_SESSION["nome"],
				$_SESSION["sobrenome"],
				$_SESSION["data_nasc"],
				$_SESSION["telefone"],
				$_SESSION["celular"],
				$_SESSION["cpf"],
				$_SESSION["cep"],
				$_SESSION["complemento"],
				$_SESSION["email"],
				$_SESSION["login"],
				$_SESSION["senha"],
				$_SESSION["confirm_senha"],
				$_SESSION["foto"],
				$_SESSION["endereco"],
				$_SESSION["foto_original"],
				$_SESSION["estado_civil"],
				$_SESSION["cargo"]
			);
			if(isset($_GET["cod"])){
				header("location: profile_funcionario.php?cod=".$_GET["cod"]);
				exit;
			}else{
				header("location: view_funcionario.php");
				exit;
			}
		}else
			echo "<div class='alert alert-danger'>Ops! Algo deu errado. Por favor, tente novamente mais tarde.</div>";
	}

	if(isset($_POST["estado_civil"]))
		$_SESSION["estado_civil"]=$_POST["estado_civil"];

	if(isset($_POST["cargo"]))
		$_SESSION["cargo"]=$_POST["cargo"];
?>
	<a href="<?php
			if(isset($funcionario))
				echo "profile_funcionario.php?cod=".$funcionario["cod_funcionario"];
			else
				echo "view_funcionario.php";
		?>" class="back">
		<i class="bi bi-arrow-left-circle-fill"></i>
	</a>
<?php
	if(
		(isset($_GET["cod"])&&isset($funcionario))||
		!isset($_GET["cod"])
	):
?>
	<h2 class="text-center text-white">
		<?php
			if(isset($_GET["cod"])){
				$sobrenome_title=explode(" ",$funcionario["sobrenome"]);
				echo "Alterar: <span class='display-6 fs-2'>".$funcionario["nome"]." ".$sobrenome_title[0]."</span>";
			}
			else
				echo "Cadastrar fornecedor";
		?>
	</h2>
	<div class="register-empresa funcionario">
		<form action="" method="post" enctype="multipart/form-data" id="form-register" autocomplete="off">
		<div class='row mb-2'>
			<div class='col'>

				<div class="form-floating mb-2">
					<input type="text" name="nome"
					class="form-control <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>"
					value="<?php
								if((isset($funcionario)&&!isset($_SESSION["nome"]))||(isset($funcionario)&&isset($_SESSION["nome"])&&$_SESSION["nome"]==""))
									echo $funcionario["nome"];
									
								if(isset($_SESSION["nome"])&&$_SESSION["nome"]!="")
									echo $_SESSION["nome"];
							?>"
					placeholder="nome">
					<label>Nome</label>
					<span class="color-pink"><?php echo $nome_err; ?></span>
				</div>

			<?php
				if(!isset($funcionario)){
					echo "
						</div>
						<div class='col'>
					";
				}
			?>

			<div class="form-floating">
				<input type="text" name="sobrenome"
				class="form-control <?php
												echo (!empty($sobrenome_err)) ? 'is-invalid' : '';
										?>"
				value="<?php
								if((isset($funcionario)&&!isset($_SESSION["sobrenome"]))||(isset($funcionario)&&isset($_SESSION["sobrenome"])&&$_SESSION["sobrenome"]==""))
									echo $funcionario["sobrenome"];
									
								if(isset($_SESSION["sobrenome"])&&$_SESSION["sobrenome"]!="")
									echo $_SESSION["sobrenome"];
						?>"
				placeholder="sobrenome">
				<label>Sobrenome</label>
				<span class="color-pink"><?php echo $sobrenome_err; ?></span>
			</div>

			<?php
				if(isset($funcionario)){
					echo "
							</div>
							<div class='col-3'>
								<img src='".$_SESSION["foto_original"]."' class='img-fluid' alt='".$funcionario["nome"]." ".$funcionario["sobrenome"]."'>
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
						<input type="text" name="cpf" id="cpf"
						class="form-control <?php
														echo (!empty($cpf_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($funcionario)&&!isset($_SESSION["cpf"]))||(isset($funcionario)&&isset($_SESSION["cpf"])&&$_SESSION["cpf"]==""))
											echo $funcionario["cpf"];
											
										if(isset($_SESSION["cpf"])&&$_SESSION["cpf"]!="")
											echo $_SESSION["cpf"];
								?>"
						placeholder="cpf">
						<label>Cpf</label>
						<span class="color-pink"><?php echo $cpf_err; ?></span>
					</div>
				</div>
				<div class="col">
					<label class="text-white">Foto de perfil</label><br>
					<input type="file" name="foto" class="form-control">
					<span class="color-pink"><?php echo $foto_err; ?></span>
				</div>
			</div>
			<div class="row mb-0">
				<div class="col-4">
					<div class="form-floating">
						<input type="date" name="data_nasc" placeholder="data de nascimento"
						class="form-control <?php
															echo (!empty($data_nasc_err)) ? 'is-invalid' : '';
															?>"
						value="<?php
									if((isset($funcionario)&&!isset($_SESSION["data_nasc"]))||(isset($funcionario)&&isset($_SESSION["data_nasc"])&&$_SESSION["data_nasc"]==""))
										echo $funcionario["data_nasc"];
										
									if(isset($_SESSION["data_nasc"])&&$_SESSION["data_nasc"]!="")
										echo $_SESSION["data_nasc"];
								?>">
						<label>Data de Nascimento</label><br>
						<span class="color-pink mt-1"><?php echo $data_nasc_err; ?></span>
					</div>
				</div>
				<div class="col-4">
					<div class="form-floating">
						<select name="estado_civil" class="form-control">
							<option value="0" <?php if(isset($_SESSION["estado_civil"])&&$_SESSION["estado_civil"]==0) echo "selected"; ?>>Solteiro(a)</option>
							<option value="1" <?php if(isset($_SESSION["estado_civil"])&&$_SESSION["estado_civil"]==1) echo "selected"; ?>>Casado(a)</option>
							<option value="2" <?php if(isset($_SESSION["estado_civil"])&&$_SESSION["estado_civil"]==2) echo "selected"; ?>>Divorciado(a)</option>
							<option value="3" <?php if(isset($_SESSION["estado_civil"])&&$_SESSION["estado_civil"]==3) echo "selected"; ?>>Viúvo(a)</option>
							<option value="4" <?php if(isset($_SESSION["estado_civil"])&&$_SESSION["estado_civil"]==4) echo "selected"; ?>>Separado judicialmente</option>
						</select>
						<label>Estado civil</label>
					</div>
				</div>
			</div>
			<div class="form-floating mb-4">
				<select name="cargo" class="form-control">
					<?php
						$result=$mysqli->query("SELECT * FROM cargos");
						if($result->num_rows>0){
							while($cargos=$result->fetch_assoc()){
								if($cargos["cod_cargo"]==$_SESSION["cargo"])
									echo "<option value='".$cargos["cod_cargo"]."' selected>".$cargos["nome_cargo"]."</option>";
								else
									echo "<option value='".$cargos["cod_cargo"]."'>".$cargos["nome_cargo"]."</option>";
							}
						}
					?>
				</select>
				<label>Cargo</label>
			</div>
			<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Contato</h3>
			<div class="row mb-2">
				<div class="col-3">
					<div class="form-floating">
						<input type="text" name="telefone" id="telefone"
						class="form-control <?php
														echo (!empty($telefone_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($funcionario)&&!isset($_SESSION["telefone"]))||(isset($funcionario)&&isset($_SESSION["telefone"])&&$_SESSION["telefone"]==""))
											echo $funcionario["telefone"];
											
										if(isset($_SESSION["telefone"])&&$_SESSION["telefone"]!="")
											echo $_SESSION["telefone"];
								?>"
						placeholder="telefone">
						<label>Telefone</label>
						<span class="color-pink"><?php echo $telefone_err; ?></span>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-2">
						<input type="email" name="email"
						class="form-control <?php
														echo (!empty($email_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($funcionario)&&!isset($_SESSION["email"]))||(isset($funcionario)&&isset($_SESSION["email"])&&$_SESSION["email"]==""))
											echo $funcionario["email"];
											
										if(isset($_SESSION["email"])&&$_SESSION["email"]!="")
											echo $_SESSION["email"];
								?>"
						placeholder="email">
						<label>Email</label>
						<span class="color-pink"><?php echo $email_err; ?></span>
					</div>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-3">
					<div class="form-floating">
						<input type="text" name="celular" id="celular"
						class="form-control <?php
														echo (!empty($celular_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($funcionario)&&!isset($_SESSION["celular"]))||(isset($funcionario)&&isset($_SESSION["celular"])&&$_SESSION["celular"]==""))
											echo $funcionario["celular"];
											
										if(isset($_SESSION["celular"])&&$_SESSION["celular"]!="")
											echo $_SESSION["celular"];
								?>"
						placeholder="celular">
						<label>Celular</label>
						<span class="color-pink"><?php echo $celular_err; ?></span>
					</div>
				</div>
			</div>
			<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Endereço</h3>
			<div class="row mb-2">
				<div class="col-3">
					<div class="form-floating">
						<input type="text" name="cep" id="cep" class="form-control <?php echo (!empty($cep_err)) ? 'is-invalid' : ''; ?>"
						value="<?php
										if((isset($funcionario)&&!isset($cep))||(isset($funcionario)&&empty($cep_err)&&$cep==""))
											echo $funcionario["cep"];
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
										if((isset($funcionario)&&!isset($_SESSION["complemento"]))||(isset($funcionario)&&isset($_SESSION["complemento"])&&$_SESSION["complemento"]==""))
											echo $funcionario["complemento"];
						
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
			<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Login</h3>
			<div class="form-floating mb-2">
				<input type="text" name="login"
				class="form-control <?php
												echo (!empty($login_err)) ? 'is-invalid' : '';
										?>"
				value="<?php
								if((isset($funcionario)&&!isset($_SESSION["login"]))||(isset($funcionario)&&isset($_SESSION["login"])&&$_SESSION["login"]==""))
									echo $funcionario["login"];
									
								if(isset($_SESSION["login"])&&$_SESSION["login"]!="")
									echo $_SESSION["login"];
						?>"
				placeholder="login">
				<label>Login</label>
				<span class="color-pink"><?php echo $login_err; ?></span>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-floating">
						<input type="text" name="senha"
						class="form-control <?php
														echo (!empty($senha_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($funcionario)&&!isset($_SESSION["senha"]))||(isset($funcionario)&&isset($_SESSION["senha"])&&$_SESSION["senha"]==""))
											echo $funcionario["senha_original"];
											
										if(isset($_SESSION["senha"])&&$_SESSION["senha"]!="")
											echo $_SESSION["senha"];
								?>"
						placeholder="senha">
						<label>Senha</label>
						<span class="color-pink"><?php echo $senha_err; ?></span>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-2">
						<input type="text" name="confirm_senha"
						class="form-control <?php
														echo (!empty($confirm_senha_err)) ? 'is-invalid' : '';
												?>"
						placeholder="confirm_senha">
						<label>Confirme a senha</label>
						<span class="color-pink"><?php echo $confirm_senha_err; ?></span>
					</div>
				</div>
			</div>
		</form>
	</div>
	<button type="submit" class="btn-preview-empresa border-0 d-flex align-items-center justify-content-center" form="form-register" text="<?php if(isset($funcionario)) echo "salvar"; else echo "cadastrar"; ?>" name="preview">
		<span class="iconify fs-2 text-white" data-icon="fa:floppy-o"></span>
	</button>
<?php
	endif;
	if(isset($_GET["cod"])&&!isset($funcionario))
		echo "<div class='alert alert-danger text-center mx-auto' style='width: 30rem;'>Funcionário inexistente!</div>";

	$mysqli->close();

	if(isset($_SESSION["preview"]))
		echo $_SESSION["preview"];
?>
	<script>
		$('#telefone').mask('(00) 0000-0000');
		$('#celular').mask('(00) 00000-0000');
		$('#cpf').mask('000.000.000-00');
		$('#cep').mask('00000-000');
	</script>
	<script src="../scripts/ajax.js"></script>
<?php
	include("../head-foot/footer_empresa.php");
?>