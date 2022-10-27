<?php
session_start();

if(isset($_SESSION["logged_funcionario"])&&$_SESSION["logged_funcionario"]&&isset($_SESSION["pagina"])&&!$_SESSION["pagina"]){
	header("location: adm.php");
	exit;
}

if(isset($_SESSION["logged_funcionario"])&&$_SESSION["logged_funcionario"]&&isset($_SESSION["pagina"])&&$_SESSION["pagina"]){
	header("location: produto/estoque.php");
	exit;
}

require_once "config.php";

$login=$senha="";
$login_err=$senha_err=$login_err="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

	if(empty(trim($_POST["login"])))
		$login_err="Preencha com um endereço de login";
	else
		$login=trim($_POST["login"]);

	if(empty(trim($_POST["senha"])))
		$senha_err="Preencha com uma senha";
	else
		$senha=trim($_POST["senha"]);

	if(empty($senha_err)&&empty($senha_err)){
		$sql="SELECT cod_funcionario, login, senha, cod_cargo FROM funcionarios WHERE login=?";
		
		if($stmt=$mysqli->prepare($sql)){
			$stmt->bind_param("s", $param_login);

			$param_login=$login;

			if($stmt->execute()){
					$stmt->store_result();

					if($stmt->num_rows==1){
						$stmt->bind_result($cod_funcionario, $login, $hashed_senha, $cod_cargo);
						if($stmt->fetch()){
							if(password_verify($senha, $hashed_senha)){
									
								$_SESSION["logged_funcionario"]=true;
								$_SESSION["cod_funcionario"]=$cod_funcionario;
								$_SESSION["login_input"]=$login;
								$_SESSION["cod_cargo"]=$cod_cargo;

								$stmt=$mysqli->prepare("SELECT nome_cargo FROM cargos WHERE cod_cargo=?");

								$stmt->bind_param("s", $param_cargo);
								$param_cargo=$cod_cargo;

								$stmt->execute();
								$stmt->store_result();
								$stmt->bind_result($nome_cargo);
								$stmt->fetch();

								if($nome_cargo=="Administrador"){
									$_SESSION["pagina"]=false;  //false para adm e true para abastecedor
									header("location: adm.php");
								}elseif($nome_cargo=="Abastecedor"){
									$_SESSION["pagina"]=true;  //false para adm e true para abastecedor
									header("location: produto/estoque.php");
								}
									
							}else
								$login_err="Endereço de login ou senha inválidos";
						}
					}else
						$login_err="Endereço de login ou senha inválidos";
			}else
				echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";

			$stmt->close();
		}
	}

	$mysqli->close();
	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="libs/jQuery-Mask-Plugin-master/src/jquery.mask.js"></script>
	<link rel="stylesheet" href="CSS/style.min.css?2">
	<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>
<body class="d-flex align-items-center justify-content-center flex-column">
	<div class="login-form">
		<h2 class="text-white">Login</h2>
		<p>Por favor, preencha suas credenciais para fazer o login.</p>

		<?php 
			if(!empty($login_err)){
				echo "
					<div class='alert alert-danger alert-dismissible show fade mb-4'>
						<button type='button' class='btn-close' data-bs-dismiss='alert'></button>$login_err
					</div>
				";
			}        
		?>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="form-floating mb-2">
				<input type="text" name="login" id="login" class="form-control <?php echo (!empty($login_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $login; ?>" placeholder="login">
				<label>Login</label>
				<span class="color-pink"><?php echo $login_err; ?></span>
			</div>    
			<div class="mb-3 mb-md-2">
            <div class="d-flex align-items-center">
               <div class="form-floating flex-grow-1">
                  <input type="password" name="senha" id="senha"
                  class="form-control <?php
                                          echo (!empty($senha_err)) ? 'is-invalid' : '';
                                    ?>"
                  placeholder="senha">
                  <label class="d-flex">Senha</label>
               </div>
               <button type="button" class="bg-transparent border-0 fs-4 ps-3 ps-md-2" id="toggle-senha">
                  <i class="bi bi-eye"></i>
               </button>
            </div>
            <span class="color-pink"><?php echo $senha_err; ?></span>
         </div>
			<div class="mt-3 d-grid">
				<button type="submit" class="btn border-0 background-pink text-white">Entrar</button>
			</div>
		</form>
	</div>
	<script>
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
<?php
	include("head-foot/footer_empresa.php");
?>