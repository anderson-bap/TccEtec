<?php
	session_start();

	if(isset($_SESSION["loggedin"])&&$_SESSION["loggedin"])
		header("location: index.php");

   require_once "config.php";

   $theme="";
   if (!empty($_COOKIE["theme"])&&$_COOKIE["theme"]=="light")
      $theme = "light-theme";
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Alberto Frigatto, Anderson Baptista, Herbert dos Santos, Leandro Nogueira, Rodrigo Barcelos">
   <meta name="description" content="Sistema para montagem de computadores pessoais">
   <meta name="keywords" content="peça, pc, computador, bufos, sapo">
   <title>Bufos - Login</title>
   <!-- CSS only -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <!-- JavaScript Bundle with Popper -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
   <link rel="stylesheet" href="CSS/style.min.css?3">
</head>
<body class="<?php echo $theme; ?>">
   <!-- BOTÃO PARA MUDAR DE TEMA -->
   <button type="button" id="btn-toggle-theme">
      <i class="bi"></i>
   </button>

   <script>
      const btnTheme=document.getElementById("btn-toggle-theme");
      const iconTheme=document.querySelector("#btn-toggle-theme i.bi");

      function getCookie(cname) {
         let name = cname + "=";
         let decodedCookie = decodeURIComponent(document.cookie);
         let ca = decodedCookie.split(';');
         for(let i = 0; i <ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
               c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
               return c.substring(name.length, c.length);
            }
         }
         return "";
      }

      var statusTheme=getCookie("theme");

      if(statusTheme=="dark"||statusTheme==""){
         iconTheme.classList.add("bi-sun");
         iconTheme.classList.remove("bi-moon");
      }else{
         iconTheme.classList.add("bi-moon");
         iconTheme.classList.remove("bi-sun");
      }

      btnTheme.addEventListener("click", function() {

         document.body.classList.toggle("light-theme");
         
         let theme = "dark";
         
         if (document.body.classList.contains("light-theme"))
            theme = "light";
         
         document.cookie = "theme=" + theme;

         var statusTheme=getCookie("theme");

         if(statusTheme=="dark"||statusTheme==""){
            iconTheme.classList.add("bi-sun");
            iconTheme.classList.remove("bi-moon");
         }else{
            iconTheme.classList.add("bi-moon");
            iconTheme.classList.remove("bi-sun");
         }
      });
   </script>

   <!-- PARTE EM CIMA DA BARRA DE NAVEGAÇÃO -->
   <div class="container-fluid site-top py-1">
      <div class="d-flex justify-content-between align-items-center container">
         <span class="tel dark-white">
            <i class="bi bi-telephone-fill color-pink"></i> (11)997430-4367
         </span>
         <div class="d-flex justify-content-between social">
            <a href="https://www.facebook.com/profile.php?id=100075004281849" target="_blank">
               <i class="bi bi-facebook"></i>
            </a>
            <a href="https://www.instagram.com/bufos_regulares/" target="_blank">
               <i class="bi bi-instagram"></i>
            </a>
            <a href="https://twitter.com/BufosRegulares2" target="_blank">
               <i class="bi bi-twitter"></i>
            </a>
            <a href="https://www.youtube.com/channel/UCJIJZ-ouLaacVihH3uMRCdw" target="_blank">
               <i class="bi bi-youtube"></i>
            </a>
         </div>
      </div>
   </div>

   <!-- BARRA DE NAVEGAÇÃO -->
   <nav class="navbar navbar-expand-lg mb-5">
      <div class="container">
         <a href="index.php" class="navbar-brand">
            <img src="img/logo.png" alt="Bufos Regulares">
         </a>
         <form class="navbar-search" action="<?php if(!file_exists("search.php")) echo "../"; ?>search.php" method="get">
            <div class="input-group d-none d-md-flex">
               <input type="search" class="form-control" placeholder="O que você procura?" name="search" value="<?php if(isset($_GET["search"])) echo $_GET["search"]; ?>">
               <button type="submit" class="btn background-pink text-white">
                  <i class="bi bi-search"></i>
               </button>
            </div>
            <button type="button" onclick="toggleSearch(true)" class="d-inline d-md-none bg-transparent border-0 color-pink">
               <i class="bi bi-search fs-4"></i>
            </button>
         </form>
         <ul class="navbar-nav mx-2">
            <li class="nav-item">
               <a href="<?php if(isset($_SESSION["loggedin"])) echo "cliente/profile_cliente.php"; else echo "login.php"; ?>" class="nav-link login">
                  <?php
                     if(isset($_SESSION["loggedin"])){
                        $sql="SELECT * FROM clientes WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";

                        $result=$mysqli->query($sql);
                        $cliente=$result->fetch_assoc();

                        $foto=$cliente["foto"];

                        if(preg_match("$../$",$foto))
                           $foto=str_replace("../","",$foto);

                        echo "
                           <img src='$foto' alt='".$cliente["nome"]."' class='thumb-perfil'>
                           <span>".$cliente["nome"]."</span>
                        ";
                     }else{
                        echo "
                           <i class='bi bi-person-fill'></i>
                           <span>Minha conta</span>
                        ";
                     }
                  ?>
               </a>
            </li>
            <li class="nav-item">
               <button type="button" class="nav-link border-0 bg-transparent" id="btn-open-cart-shop">
                   <i class="bi bi-cart-fill"></i>
                   <span id="qtde-cart">0</span>
               </button>
            </li>
         </ul>
      </div>
   </nav>

   <!-- TELA DE PESQUISA EM TELA PEQUENA -->
   <div class="search d-flex d-md-none justify-content-center align-items-center pb-5">
      <div class="d-flex flex-column align-items-center">
         <button type="button" onclick="toggleSearch(false)" class="bg-transparent border-0 align-self-end">
            <i class="bi bi-x-lg fs-1"></i>
         </button>
         <h1 class="mb-5 mt-4">O que você procura?</h1>
         <form class="d-flex" action="<?php if(!file_exists("search.php")) echo "../"; ?>search.php" method="get">
            <input type="search" class="bg-transparent border-0" placeholder="Buscar" name="search" value="<?php if(isset($_GET["search"])) echo $_GET["search"]; ?>">
            <button type="submit" class="bg-transparent border-0">
               <i class="bi bi-search color-pink fs-2"></i>
            </button>
         </form>
      </div>
   </div>
<?php
	$email=$senha="";
	$email_err=$senha_err=$login_err="";

	if(isset($_POST["submit"])){

		if(empty(trim($_POST["email"])))
			$email_err="Preencha com um endereço de email";
		else
			$email=trim($_POST["email"]);

		if(empty(trim($_POST["senha"])))
			$senha_err="Preencha com uma senha";
		else
			$senha=trim($_POST["senha"]);

		if(empty($senha_err)&&empty($senha_err)){
			$sql="SELECT cod_cliente, senha FROM clientes WHERE email=?";
			
			if($stmt=$mysqli->prepare($sql)){
				$stmt->bind_param("s", $param_email);

				$param_email=$email;

				if($stmt->execute()){
					$stmt->store_result();

					if($stmt->num_rows==1){
						$stmt->bind_result($cod_cliente, $hashed_senha);
						if($stmt->fetch()){
							if(password_verify($senha, $hashed_senha)){
								$_SESSION["loggedin"]=true;
								$_SESSION["cod_cliente"]=$cod_cliente;
								
								$sql="DELETE FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
                            	$mysqli->query($sql);
                            	
                            	unset($_SESSION["quantidade_carrinho"]);
								
								if(isset($_SESSION["last_product"])){
								    echo "
    									<script>
    										window.location.assign('https://bufosregulares.com/produto.php?cod=".$_SESSION["last_product"]."');
    									</script>
    								";
								}else{ 
    								echo "
    									<script>
    										window.location.assign('https://bufosregulares.com/');
    									</script>
    								";
								}

							}else
								$login_err="Endereço de email ou senha inválidos";
						}
					}else
						$login_err="Endereço de email ou senha inválidos";
				}else
					echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";

				$stmt->close();
			}
		}
	}
?>
	<div class="container account px-md-5">
		<h2>Entrar</h2>
		<h5 class="mb-3">Prencha os campos para entrar em sua conta</h5>
		<?php 
			if(!empty($login_err)){
				echo '<div class="alert alert-danger">' . $login_err . '</div>';
			}        
		?>

		<form action="" method="post">
			<div class="form-floating mb-3 mb-md-2">
				<input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="email">
				<label>Email</label>
				<span class="color-pink"><?php echo $email_err; ?></span>
			</div>
			<div class="mb-3 mb-md-2">
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
                  <label class="d-flex">Senha</label>
               </div>
               <button type="button" class="bg-transparent border-0 fs-4 ps-3 ps-md-2" id="toggle-senha">
                  <i class="bi bi-eye"></i>
               </button>
            </div>
            <span class="color-pink"><?php echo $senha_err; ?></span>
         </div>
			<p class="d-flex justify-content-end mb-3 mb-md-2">
				<a href="" class="d-flex align-items-center">
					<i class="bi bi-key fs-4 me-2 color-pink"></i>
					Esqueci minha senha
				</a>
			</p>
			<div class="d-grid mb-4 mb-md-3">
				<button type="submit" class="btn background-pink hover text-white d-flex align-items-center justify-content-center py-1" name="submit">
				    <i class='bi bi-box-arrow-in-right me-2 fs-4 p-0'></i>
				    Entrar
				</button>
			</div>
			<p>Não possui uma conta? <a href="cliente/register.php">Crie uma agora</a></p>
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
	$mysqli->close();

   include ("head-foot/footer.php");
?>