<?php
	$titulo="- Criar conta";
	include("../head-foot/header.php");
	
	unset($_SESSION["last_product"]);

	if(isset($_SESSION["endereco"])&&$_SESSION["endereco"]!="")
		$_SESSION["endereco"]="";

	$nome=$sobrenome=$foto=$data_nasc=$cpf=$telefone=$celular=$cep=$complemento=$email=$senha=$confirm_senha="";
	$nome_err=$sobrenome_err=$foto_err=$data_nasc_err=$cpf_err=$telefone_err=$celular_err=$cep_err=$complemento_err=$email_err=$senha_err=$confirm_senha_err="";

	$dir="../img/cliente_fisico/";

	$cod="cod_cliente";
	$table="clientes";
	$tipo_nome=true;  //true para nome de pessoa e cargo e false para nome de empresa
	$check_email=true;
	$check_cpf=true;

	if(isset($_POST["submit"])){

		include ("../valida/nome.php");
		include ("../valida/sobrenome.php");
		include ("../valida/foto.php");
		include ("../valida/data_nasc.php");
		include ("../valida/telefone.php");
		include ("../valida/cpf.php");
		include ("../valida/cep.php");
		include ("../valida/complemento-endereco.php");
		include ("../valida/email.php");
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
		$_SESSION["senha"]=$_POST["senha"];
		$_SESSION["confirm_senha"]=$_POST["confirm_senha"];

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
			empty($senha_err)&&
			empty($confirm_senha_err)
		){
			
			if(!move_uploaded_file($file["tmp_name"],$path))
				$foto_err="Falha ao enviar o arquivo";
			else{
				move_uploaded_file($file["tmp_name"],$path);

				$foto=$path;

				$senha=password_hash($senha, PASSWORD_DEFAULT);

				$sql="INSERT INTO `clientes` (`cod_cliente`, `nome`, `sobrenome`, `foto`, `data_nasc`, `telefone`, `celular`, `cpf`, `cnpj`, `cep`, `complemento`, `email`, `senha`, `razao_social`, `website`) VALUES (NULL, '$nome', '$sobrenome', '$foto', '$data_nasc', '$telefone', '$celular', '$cpf', '', '$cep', '$complemento', '$email', '$senha', '', '')";

				if($mysqli->query($sql)){
					include("../phpmailer/send_email.php");
					
					sendEmailContato($_SESSION["email"],"<h1>Parabens, Voc?? acaba de criar uma conta na Bufos Build!</h1>");
					
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
						$_SESSION["senha"],
						$_SESSION["confirm_senha"]
					);
					
					echo "
    					<script>
    						window.location.assign('https://bufosregulares.com/login.php');
    					</script>
    				";
				}else
					echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
			}
		}

		$mysqli->close();
	}
?>
	<div class="register-cliente container">
		<h2 class="text-center">Criar conta: pessoa f??sica</h2>
		<p class="text-center mb-3">Preencha o formul??rio para criar uma conta como pessoa f??sica</p>
		<p class="text-center mb-4">Para criar uma conta como pessoa jur??dica <a href="register_empresa.php">clique aqui</a></p>
		<form action="" method="post" enctype="multipart/form-data" autocomplete="off">
			<div class="row mb-3">
				<div class="col-12 col-md-3 mb-2 mb-md-0">
					<div class="form-floating">
						<input type="text" name="nome"
						class="form-control <?php
														echo (!empty($nome_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if(isset($_POST["nome"])&&$_SESSION["nome"]!="")
											echo $_SESSION["nome"];
								?>"
						placeholder="nome">
						<label class="d-flex">Nome<i class="bi bi-asterisk color-pink ms-1"></i></label>
						<span class="color-pink"><?php echo $nome_err; ?></span>
					</div>
				</div>
				<div class="col-12 col-md-6 mb-2 mb-md-0">
					<div class="form-floating">
						<input type="text" name="sobrenome"
						class="form-control <?php
												echo (!empty($sobrenome_err)) ? 'is-invalid' : '';
											?>"
						value="<?php
									if(isset($_POST["sobrenome"])&&$_SESSION["sobrenome"]!="")
										echo $_SESSION["sobrenome"];
								?>"
						placeholder="sobrenome">
						<label class="d-flex">Sobrenome<i class="bi bi-asterisk color-pink ms-1"></i></label>
						<span class="color-pink"><?php echo $sobrenome_err; ?></span>
					</div>
				</div>
				<div class="col-12 col-md mb-2 mb-md-0">
					<div class="form-floating">
						<input type="text" name="cpf" id="cpf"
						class="form-control <?php
														echo (!empty($cpf_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if(isset($_POST["cpf"])&&$_SESSION["cpf"]!="")
											echo $_SESSION["cpf"];
								?>"
						placeholder="cpf">
						<label class="d-flex">CPF<i class="bi bi-asterisk color-pink ms-1"></i></label>
						<span class="color-pink"><?php echo $cpf_err; ?></span>
					</div>
				</div>
			</div>
			<div class="row mb-3 align-items-center">
				<div class="col-12 col-md-6 col-lg-6 col-xl-3 mb-2 mb-md-0">
					<div class="mb-2 form-floating">
						<input type="date" name="data_nasc"
						class="form-control text-black <?php
												echo (!empty($data_nasc_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
								if(isset($_POST["data_nasc"])&&$_SESSION["data_nasc"]!="")
									echo $_SESSION["data_nasc"];
								?>">
						<label class="d-flex">Data de nascimento<i class="bi bi-asterisk color-pink ms-1"></i></label>
						<span class="color-pink mt-1"><?php echo $data_nasc_err; ?></span>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-6 col-xl mb-2 mb-md-0">
					<div class="form-floating mb-2">
						<input type="text" name="telefone" id="telefone"
						class="form-control <?php
														echo (!empty($telefone_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if(isset($_POST["telefone"])&&$_SESSION["telefone"]!="")
											echo $_SESSION["telefone"];
								?>"
						placeholder="telefone">
						<label class="d-flex">Telefone<i class="bi bi-asterisk color-pink ms-1"></i></label>
						<span class="color-pink"><?php echo $telefone_err; ?></span>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-6 col-xl mb-2 mb-md-0">
					<div class="form-floating mb-2">
						<input type="text" name="celular" id="celular"
						class="form-control <?php
														echo (!empty($celular_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if(isset($_POST["celular"])&&$_SESSION["celular"]!="")
											echo $_SESSION["celular"];
								?>"
						placeholder="celular">
						<label class="d-flex">Celular<i class="bi bi-asterisk color-pink ms-1"></i></label>
						<span class="color-pink"><?php echo $celular_err; ?></span>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-6 col-xl-4 mb-2 mb-md-0">
					<div class="mb-2">
						<label class="d-flex" id="label-foto">Foto de perfil<i class="bi bi-asterisk color-pink ms-1"></i></label>
						<input type="file" name="foto" class="form-control">
						<span class="color-pink"><?php echo $foto_err; ?></span>
					</div>
				</div>
			</div>
			<div class="row mb-1 mb-md-3">
			    <div class="col-12 col-md-3 mb-2 mb-md-0">
            		<div class="form-floating">
            			<input type="text" name="cep" id="cep" class="form-control <?php echo (!empty($cep_err)) ? 'is-invalid' : ''; ?>"
            			value="<?php
            							if($cep!=false&&$cep!=2)
            								echo $_SESSION["cep"];
            					?>"
            			placeholder="cep">
            			<label class="d-flex">CEP<i class="bi bi-asterisk color-pink ms-1"></i></label>
            			<span class="color-pink"><?php echo $cep_err; ?></span>
            		</div>
			    </div>
		        <div class="col-12 col-md mb-2 mb-md-0">
        			<div class="form-floating">
        				<input type="text" name="complemento"
        				class="form-control <?php
        												echo (!empty($complemento_err)) ? 'is-invalid' : '';
        										?>"
        				value="<?php
        								if(isset($_POST["complemento"])&&$_SESSION["complemento"]!="")
        									echo $_SESSION["complemento"];
        						?>"
        				placeholder="complemento">
        				<label>Complemento de endere??o</label>
        				<span class="color-pink"><?php echo $complemento_err; ?></span>
        			</div>
		        </div>
			</div>
			<div class="form-floating mb-3">
				<input type="text" id="endereco" class="form-control" placeholder="endereco" value="<?php if(isset($_SESSION["endereco"])&&$_SESSION["endereco"]!="") echo $_SESSION["endereco"] ?>" readonly>
				<label>Endere??o (preencha o CEP)</label>
			</div>
			<div class="form-floating mb-4">
				<input type="email" name="email"
				class="form-control <?php
												echo (!empty($email_err)) ? 'is-invalid' : '';
										?>"
				value="<?php
								if(isset($_POST["email"])&&$_SESSION["email"]!="")
									echo $_SESSION["email"];
						?>"
				placeholder="email">
				<label class="d-flex">Email<i class="bi bi-asterisk color-pink ms-1"></i></label>
				<span class="color-pink"><?php echo $email_err; ?></span>
			</div>
			<div class="row mb-3">
			    <div class="col-12 col-md-6 mb-3 mb-lg-0">
					<div class="mb-3">
						<div class="d-flex align-items-center">
							<div class="form-floating flex-grow-1">
								<input type="password" name="senha" id="senha"
								class="form-control <?php
																echo (!empty($senha_err)) ? 'is-invalid' : '';
														?>"
								value="<?php
												if(isset($_POST["senha"])&&$_SESSION["senha"]!="")
													echo $_SESSION["senha"];
										?>"
								placeholder="email">
								<label class="d-flex">Senha<i class="bi bi-asterisk color-pink ms-1"></i></label>
							</div>
							<button type="button" class="bg-transparent border-0 fs-4 ps-3 ps-md-2" id="toggle-senha">
								<i class="bi bi-eye"></i>
							</button>
						</div>
						<span class="color-pink"><?php echo $senha_err; ?></span>
					</div>
        			<div class="form-floating">
        				<input type="password" name="confirm_senha"
        				class="form-control <?php
        												echo (!empty($confirm_senha_err)) ? 'is-invalid' : '';
        										?>"
        				value="<?php
        								if(isset($_POST["confirm_senha"])&&$_SESSION["confirm_senha"]!="")
        									echo $_SESSION["confirm_senha"];
        						?>"
        				placeholder="email">
        				<label class="d-flex">Confirmar senha<i class="bi bi-asterisk color-pink ms-1"></i></label>
        				<span class="color-pink"><?php echo $confirm_senha_err; ?></span>
        			</div>
			    </div>
			    <div class="col-12 col-md-6 mb-2 mb-md-0 psw-guide">
			        <h5 class="mb-3">A senha deve conter:</h5>
			        <ul>
			            <li>Pelo menos 8 caracteres</li>
			            <li>Pelo menos uma letra mai??scula e uma min??scula</li>
			            <li>Pelo menos um n??mero e um caracter especial</li>
			        </ul>
			        <p>Ex:<span class="ms-2">PyXM)P#x28</span></p>
			    </div>
			</div>
			<div class="d-grid mb-4 mb-md-3">
				<button type="submit" class="btn border-0 background-pink hover text-white" name="submit">Cadastrar</button>
			</div>
			<p>J?? possui cadastro? <a href="../login.php">Entre agora</a>.</p>
		</form>
	</div>
	<script>
		$('#telefone').mask('(00) 0000-0000');
		$('#celular').mask('(00) 00000-0000');
		$('#cpf').mask('000.000.000-00');
		$('#cep').mask('00000-000');

		const btnToggleSenha=document.getElementById("toggle-senha");
		const iconToggleSenha=document.querySelector("#toggle-senha i");
		const inputSenha=document.getElementById("senha");

		var statusSenha;

		btnToggleSenha.addEventListener("click",function(){
			if(statusSenha==null||!statusSenha){
				inputSenha.type="text";
				statusSenha=true;
				iconToggleSenha.classList.remove("bi-eye");
				iconToggleSenha.classList.add("bi-eye-slash");
			}else{
				inputSenha.type="password";
				statusSenha=false;
				iconToggleSenha.classList.remove("bi-eye-slash");
				iconToggleSenha.classList.add("bi-eye");
			}
		});
	</script>
	<script src="../scripts/ajax.js"></script>
<?php
	include("../head-foot/footer.php");
?>