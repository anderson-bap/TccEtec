<?php
   $session_start=session_start();
   
   if(!isset($_SESSION["loggedin"])){
      header("location: ../login.php");
      exit;
   }

   include("../config.php");

   $sql="SELECT * FROM clientes WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";

   $result=$mysqli->query($sql);
   $cliente=$result->fetch_assoc();

   $titulo="- alterar dados: ".$cliente["nome"]." ".$cliente["sobrenome"];

   include("../head-foot/header.php");

   if(isset($_SESSION["endereco"])&&$_SESSION["endereco"]!="")
		$_SESSION["endereco"]="";

	$nome=$sobrenome=$razao_social=$foto=$data_nasc=$cpf=$cnpj=$telefone=$celular=$cep=$complemento=$email=$website="";
	$nome_err=$sobrenome_err=$razao_social_err=$foto_err=$data_nasc_err=$cpf_err=$cnpj_err=$telefone_err=$celular_err=$cep_err=$complemento_err=$email_err=$website_err="";

	$dir="../img/cliente_fisico/";

	$cod="cod_cliente";
	$table="clientes";

   if(!empty($cliente["cpf"]))
	   $tipo_nome=true;  //true para nome de pessoa e cargo e false para nome de empresa
   else
      $tipo_nome=false;

	$check_email=false;
	$check_cpf=false;
	$check_cnpj=false;
    $empty_foto=true;

   if(isset($_POST["submit"])){
		include ("../valida/nome.php");
		include ("../valida/telefone.php");
      
      if(!empty($cliente["cpf"])){
         include ("../valida/sobrenome.php");
         include ("../valida/cpf.php");
         include ("../valida/data_nasc.php");
      }else{
   		include ("../valida/cnpj.php");
   		include ("../valida/website.php");
      }

		include ("../valida/cep.php");
		include ("../valida/complemento-endereco.php");
		include ("../valida/email.php");
		
		$_SESSION["nome"]=$_POST["nome"];
		$_SESSION["telefone"]=$_POST["telefone"];
		$_SESSION["celular"]=$_POST["celular"];
		$_SESSION["cep"]=$_POST["cep"];
		$_SESSION["complemento"]=$_POST["complemento"];
		$_SESSION["email"]=$_POST["email"];
      
      if(!empty($cliente["cpf"])){
         $_SESSION["sobrenome"]=$_POST["sobrenome"];
         $_SESSION["data_nasc"]=$_POST["data_nasc"];
         $_SESSION["cpf"]=$_POST["cpf"];
      }else{
         $_SESSION["razao_social"]=$_POST["razao_social"];
         $_SESSION["cnpj"]=$_POST["cnpj"];
         $_SESSION["website"]=$_POST["website"];
      }

		if(
			empty($nome_err)&&
			empty($sobrenome_err)&&
			empty($razao_social_err)&&
			empty($data_nasc_err)&&
			empty($telefone_err)&&
			empty($celular_err)&&
			empty($cpf_err)&&
			empty($cnpj_err)&&
			empty($cep_err)&&
			empty($complemento_err)&&
			empty($email_err)&&
			empty($website_err)&&
			empty($confirm_senha_err)
		){

			$foto_db=$_POST["input_foto"];
			
			if($foto_db!=$cliente["foto"])
			    unlink($cliente["foto"]);
			
			if($foto_db=="0")
			    $foto_err="Tipo de arquivo não aceito";
			else{
                if(!empty($cliente["cpf"]))
                   $sql="UPDATE `clientes` SET `nome`='$nome', `sobrenome`='$sobrenome',`foto`='$foto_db',`data_nasc`='$data_nasc',`telefone`='$telefone',`celular`='$celular',`cpf`='$cpf',`cep`='$cep',`complemento`='$complemento',`email`='$email' WHERE `clientes`.`cod_cliente`=".$_SESSION["cod_cliente"];
                else
                   $sql="UPDATE `clientes` SET `nome`='$nome', `razao_social`='$razao_social',`foto`='$foto_db',`telefone`='$telefone',`celular`='$celular',`cnpj`='$cnpj',`cep`='$cep',`complemento`='$complemento',`email`='$email',`website`='website' WHERE `clientes`.`cod_cliente`=".$_SESSION["cod_cliente"];
    
    			if($mysqli->query($sql)){
    				unset(
    					$_SESSION["nome"],
    					$_SESSION["sobrenome"],
    					$_SESSION["razao_social"],
    					$_SESSION["data_nasc"],
    					$_SESSION["telefone"],
    					$_SESSION["celular"],
    					$_SESSION["cpf"],
    					$_SESSION["cnpj"],
    					$_SESSION["cep"],
    					$_SESSION["complemento"],
    					$_SESSION["email"],
    					$_SESSION["website"]
    				);
    				echo "
    					<script>
    						window.location.assign('https://bufosregulares.com/cliente/profile_cliente.php');
    					</script>
    				";
    			}else
    				echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
			}

		}
	}
?>
   <div class="container row mx-auto alterar mb-5 register-cliente">
		<div class="col-12 col-xl-9">
			<form action="" method="post" enctype="multipart/form-data" autocomplete="off" id="form-alterar">
				<input type="hidden" id="input-foto" style="width: 500px" name="input_foto">
				<div class="row flex-column-reverse flex-md-row-reverse flex-xl-row mb-3">
					<div class="col-12 col-md-3 mb-2 mb-md-0">
						<div class="form-floating">
							<input type="text" name="nome"
							class="form-control <?php
															echo (!empty($nome_err)) ? 'is-invalid' : '';
													?>"
							value="<?php
										if(empty($_SESSION["nome"]))
											echo $cliente["nome"];
										else
											echo $_SESSION["nome"];
									?>"
							placeholder="nome">
							<label class="d-flex">Nome<i class="bi bi-asterisk color-pink ms-1"></i></label>
							<span class="color-pink"><?php echo $nome_err; ?></span>
						</div>
					</div>
					<div class="col-12 col-md-6 mb-2 mb-md-0">
						<div class="form-floating">
							<?php
								if(!empty($cliente["cpf"])):
							?>
							<input type="text" name="sobrenome"
							class="form-control <?php
													echo (!empty($sobrenome_err)) ? 'is-invalid' : '';
												?>"
							value="<?php
										if(empty($_SESSION["sobrenome"]))
											echo $cliente["sobrenome"];
										else
											echo $_SESSION["sobrenome"];
									?>"
							placeholder="sobrenome">
							<label class="d-flex">Sobrenome<i class="bi bi-asterisk color-pink ms-1"></i></label>
							<span class="color-pink"><?php echo $sobrenome_err; ?></span>
							<?php
								endif;

								if(empty($cliente["cpf"])):
							?>
							<input type="text" name="razao_social"
							class="form-control <?php
															echo (!empty($razao_social_err)) ? 'is-invalid' : '';
													?>"
							value="<?php
										if(empty($_SESSION["razao_social"]))
											echo $cliente["razao_social"];
										else
											echo $_SESSION["razao_social"];
									?>"
							placeholder="razao_social">
							<label class="d-flex">Razão Social<i class="bi bi-asterisk color-pink ms-1"></i></label>
							<span class="color-pink"><?php echo $razao_social_err; ?></span>
							<?php
								endif;
							?>
						</div>
					</div>
					<div class="col-12 col-md mb-2 mb-md-0">
						<div class="form-floating">
							<?php
								if(!empty($cliente["cpf"])):
							?>
							<input type="cpf" name="cpf" id="cpf"
							class="form-control <?php
															echo (!empty($cpf_err)) ? 'is-invalid' : '';
													?>"
							value="<?php
										if(empty($_SESSION["cpf"]))
											echo $cliente["cpf"];
										else
											echo $_SESSION["cpf"];
									?>"
							placeholder="cpf">
							<label class="d-flex">CPF<i class="bi bi-asterisk color-pink ms-1"></i></label>
							<span class="color-pink"><?php echo $cpf_err; ?></span>
							<?php
								endif;

								if(empty($cliente["cpf"])):
							?>
							<input type="cnpj" name="cnpj" id="cnpj"
							class="form-control <?php
															echo (!empty($cnpj_err)) ? 'is-invalid' : '';
													?>"
							value="<?php
										if(empty($_SESSION["cnpj"]))
											echo $cliente["cnpj"];
										else
											echo $_SESSION["cnpj"];
									?>"
							placeholder="cnpj">
							<label class="d-flex">CNPJ<i class="bi bi-asterisk color-pink ms-1"></i></label>
							<span class="color-pink"><?php echo $cnpj_err; ?></span>
							<?php
								endif;
							?>
						</div>
					</div>
				</div>
				<div class="form-floating mb-3">
					<input type="email" name="email"
					class="form-control <?php
													echo (!empty($email_err)) ? 'is-invalid' : '';
											?>"
					value="<?php
								if(empty($_SESSION["email"]))
									echo $cliente["email"];
								else
									echo $_SESSION["email"];
							?>"
					placeholder="email">
					<label class="d-flex">Email<i class="bi bi-asterisk color-pink ms-1"></i></label>
					<span class="color-pink"><?php echo $email_err; ?></span>
				</div>
				<?php
					if(empty($cliente["cpf"])):
				?>
				<div class="form-floating mb-3">
					<input type="website" name="website"
					class="form-control <?php
													echo (!empty($website_err)) ? 'is-invalid' : '';
											?>"
					value="<?php
								if(empty($_SESSION["website"]))
									echo $cliente["website"];
								else
									echo $_SESSION["website"];
							?>"
					placeholder="website">
					<label class="d-flex">Website<i class="bi bi-asterisk color-pink ms-1"></i></label>
					<span class="color-pink"><?php echo $website_err; ?></span>
				</div>
				<?php
					endif;
				?>
				<div class="row flex-column-reverse flex-md-row-reverse flex-xl-row  mb-3 align-items-center">
					<?php
						if(!empty($cliente["cpf"])):
					?>
					<div class="col-12 col-md-5 col-lg-4 col-xl-4 mb-2 mb-md-0">
						<div class="form-floating">
							<input type="date" name="data_nasc"
							class="form-control <?php
													echo (!empty($data_nasc_err)) ? 'is-invalid' : '';
													?>"
							value="<?php
										if(empty($_SESSION["data_nasc"]))
											echo $cliente["data_nasc"];
										else
											echo $_SESSION["data_nasc"];
									?>">
							<label class="d-flex">Data de nascimento<i class="bi bi-asterisk color-pink ms-1"></i></label>
							<span class="color-pink mt-1"><?php echo $data_nasc_err; ?></span>
						</div>
					</div>
					<?php
						endif;
					?>
					<div class="col-12 col-md mb-2 mb-md-0">
						<div class="form-floating">
							<input type="telefone" name="telefone" id="telefone"
							class="form-control <?php
															echo (!empty($telefone_err)) ? 'is-invalid' : '';
													?>"
							value="<?php
										if(empty($_SESSION["telefone"]))
											echo $cliente["telefone"];
										else
											echo $_SESSION["telefone"];
									?>"
							placeholder="telefone">
							<label class="d-flex">Telefone<i class="bi bi-asterisk color-pink ms-1"></i></label>
							<span class="color-pink"><?php echo $telefone_err; ?></span>
						</div>
					</div>
					<div class="col-12 col-md col-xl mb-2 mb-md-0">
						<div class="form-floating">
							<input type="celular" name="celular" id="celular"
							class="form-control <?php
															echo (!empty($celular_err)) ? 'is-invalid' : '';
													?>"
							value="<?php
										if(empty($_SESSION["celular"]))
											echo $cliente["celular"];
										else
											echo $_SESSION["celular"];
									?>"
							placeholder="celular">
							<label class="d-flex">Celular<i class="bi bi-asterisk color-pink ms-1"></i></label>
							<span class="color-pink"><?php echo $celular_err; ?></span>
						</div>
					</div>
				</div>
				<div class="row flex-column-reverse flex-md-row-reverse flex-xl-row  mb-1 mb-md-3">
					<div class="col-12 col-md-3 mb-2 mb-md-0">
							<div class="form-floating">
								<input type="text" name="cep" id="cep" class="form-control <?php echo (!empty($cep_err)) ? 'is-invalid' : ''; ?>"
								value="<?php
											if(empty($_SESSION["cep"]))
												echo $cliente["cep"];
											elseif(!empty($_SESSION["cep"])&&$cep!=false&&$cep!=2)
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
										if(empty($_SESSION["complemento"])&&!empty($cliente["complemento"]))
											echo $cliente["complemento"];
										elseif(!empty($_SESSION["complemento"]))
											echo $_SESSION["complemento"];
									?>"
							placeholder="complemento">
							<label>Complemento de endereço</label>
							<span class="color-pink"><?php echo $complemento_err; ?></span>
						</div>
					</div>
				</div>
				<div class="form-floating mb-4">
					<input type="text" id="endereco" class="form-control" placeholder="endereco" value="<?php if(isset($_SESSION["endereco"])&&$_SESSION["endereco"]!="") echo $_SESSION["endereco"] ?>" readonly>
					<label>Endereço (preencha o CEP)</label>
				</div>
				<div class="d-grid mb-4 mb-md-3">
					<button type="submit" class="btn border-0 background-pink hover text-white" name="submit">Alterar cadastro</button>
				</div>
			</form>
		</div>
		<div class="col d-flex align-items-center flex-column mb-4 mb-md-0">
			<div class="d-inline alterar-foto p-1 mb-2">
				<div class="dropdown">
					<button type="button" class="btn bg-transparent border-0" data-bs-toggle="dropdown">
						<i class="bi bi-three-dots-vertical fs-4"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-end">
						<li><label for="foto" class="btn">Trocar foto</label></li>
						<form action="" method="post" enctype="multipart/form-data" id="form-foto">
						    <input type="file" name="foto" class="d-none" id="foto" onchange="document.getElementById('form-foto').submit()">
						</form>
					</ul>
				</div>
				<div class="view-foto mb-3">
					<button type="button" id="open-img-full-screen">
						<i class="bi bi-image"></i>
					</button>
					<img src="<?php echo $cliente["foto"]; ?>" alt="<?PHP echo $cliente["nome"]." ".$cliente["sobrenome"]; ?>" class="img-cliente">
				</div>
			</div>
			<p class="color-pink">
			    <?php
			        echo $foto_err;
			    ?>
			</p>
		</div>
	</div>

	<div class="img-full-screen d-none">
      <button type="button" id="close-img-full-screen" class="bg-transparent border-0 p-0">
         <i class="bi bi-x"></i>
      </button>
      <img src="<?php echo $cliente["foto"]; ?>" alt="<?PHP echo $cliente["nome"]." ".$cliente["sobrenome"]; ?>">
   </div>

   <script>
		$('#telefone').mask('(00) 0000-0000');
		$('#celular').mask('(00) 00000-0000');
		$('#cnpj').mask('00.000.000/0000-00');
		$('#cep').mask('00000-000');
        $('#cpf').mask('000.000.000-00');
        
        const imgCliente=document.querySelector('.img-cliente');
        const imgClienteFullScreen=document.querySelector('.img-full-screen img');
        const inputFoto=document.getElementById('input-foto');
        
        <?php
            if(isset($_FILES["foto"])){
        	    include ("../valida/foto.php");
        	    if(move_uploaded_file($file["tmp_name"],$path)&&$path!=$cliente["foto"]){
        	        $foto=$path;
        	        echo "
        	            imgCliente.src='$foto';
        	            imgClienteFullScreen.src='$foto';
        	            inputFoto.value='$foto';
        	        ";
        	    }elseif($foto_err=="Tipo de arquivo não aceito"){
        	        echo "
        	            inputFoto.value='0';
            	        imgCliente.src='".$cliente["foto"]."';
        	            imgClienteFullScreen.src='".$cliente["foto"]."';
        	        ";
        	    }
        	}else
        	    echo "inputFoto.value='".$cliente["foto"]."'";
        ?>
        
        // IMAGEM DO CLIENTE EM TELA CHEIA
        const btnOpenImgFullscreen=document.getElementById("open-img-full-screen");
        const btnCloseImgFullscreen=document.getElementById("close-img-full-screen");
        const ImgFullscreen=document.querySelector(".img-full-screen");
        
        btnOpenImgFullscreen.addEventListener("click",function(){
           ImgFullscreen.classList.remove("d-none");
        });
        btnCloseImgFullscreen.addEventListener("click",function(){
           ImgFullscreen.classList.add("d-none");
        });
        
        // MODAL DE DELETE DA EMPRESA
        const deleteErr=document.getElementById("delete-err");
        const modalCargo=document.getElementById("delete");
        
        if(deleteErr.innerHTML!=""){
           modalCargo.classList.add("show");
           modalCargo.style.display="block";
        }
   </script>
   <script src="../scripts/ajax.js"></script>
<?php
	include("../head-foot/footer.php");
?>