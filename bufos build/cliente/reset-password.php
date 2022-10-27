<?php
// Inicialize a sessão
session_start();

if(!isset($_SESSION["loggedin"])||!$_SESSION["loggedin"]){
	header("location: ../login.php");
	exit;
}

require_once "../config.php";

$nova_senha=$confirm_senha="";
$nova_senha_err=$confirm_senha_err="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

	if(empty(trim($_POST["nova_senha"])))
		$nova_senha_err="Coloque a nova senha";     
	elseif(strlen(trim($_POST["nova_senha"]))<8)
		$nova_senha_err="A senha deve ter pelo menos 6 caracteres.";
	else
		$nova_senha=trim($_POST["nova_senha"]);
	
	if(empty(trim($_POST["confirm_senha"])))
		$confirm_senha_err="Por favor confirme a senha";
	else{
		$confirm_senha=trim($_POST["confirm_senha"]);
		if(empty($nova_senha_err)&&($nova_senha!=$confirm_senha))
			$confirm_senha_err="A senha não corresponde";
	}

	if(empty($nova_senha_err) && empty($confirm_senha_err)){
		$sql="UPDATE clientes SET senha=? WHERE cod_cliente=?";
		
		if($stmt=$mysqli->prepare($sql)){
			$stmt->bind_param("si", $param_password, $param_cod_cliente);
			
			$param_password=password_hash($nova_senha, PASSWORD_DEFAULT);
			$param_cod_cliente=$_SESSION["cod_cliente"];
			
			if($stmt->execute()){
					session_destroy();
					header("location: ../login.php");
					exit();
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
	<title>Reset Password</title>
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<style>
		body {
			min-height: 100vh;
		}

		.wrapper {
			max-width: 400px;
		}
	</style>
</head>
<body class="d-flex justify-content-center align-items-center">
	<div class="wrapper">
		<h2>Redefinir senha</h2>
		<p>Por favor, preencha este formulário para redefinir sua senha.</p>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
			<div class="form-group">
					<label>Nova senha</label>
					<input type="password" name="nova_senha" class="form-control <?php echo (!empty($nova_senha_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nova_senha; ?>">
					<span class="invalid-feedback"><?php echo $nova_senha_err; ?></span>
			</div>
			<div class="form-group">
					<label>Confirme a senha</label>
					<input type="password" name="confirm_senha" class="form-control <?php echo (!empty($confirm_senha_err)) ? 'is-invalid' : ''; ?>">
					<span class="invalid-feedback"><?php echo $confirm_senha_err; ?></span>
			</div>
			<div class="form-group mt-2">
				<button type="submit" class="btn btn-primary">Avançar</button>
					<a class="btn btn-danger" href="welcome.php">Voltar</a>
			</div>
		</form>
	</div>    
</body>
</html>
