<?php
	$session_start=session_start();

	include("../config.php");

	if(isset($_GET["cod"])&&isset($_GET["tipo"]))
		die("404 NOT FOUND");
	elseif(isset($_GET["cod"])&&!isset($_GET["tipo"])){
		$sql="SELECT * FROM produtos WHERE cod_produto=".$_GET["cod"];
		$result=$mysqli->query($sql);
		if($result->num_rows>0){
			unset($_SESSION["tipo_produto"]);

			$produto=$result->fetch_assoc();
			$titulo="Alterar: ".$produto["titulo"];
		}else
			$titulo="Produto inexistente!";
	}elseif(!isset($_GET["cod"])&&isset($_GET["tipo"])){
		if($_GET["tipo"]<0||$_GET["tipo"]>15)
			die("404 NOT FOUND");
		else{
			$_SESSION["tipo_produto"]=$_GET["tipo"];
			switch($_SESSION["tipo_produto"]){
				case 0:
					$_SESSION["tipo_produto"]="Placa mãe";
					break;
				case 1:
					$_SESSION["tipo_produto"]="CPU";
					break;
				case 2:
					$_SESSION["tipo_produto"]="Memória RAM";
					break;
				case 3:
					$_SESSION["tipo_produto"]="GPU";
					break;
				case 4:
					$_SESSION["tipo_produto"]="HD";
					break;
				case 5:
					$_SESSION["tipo_produto"]="SSD";
					break;
				case 6:
					$_SESSION["tipo_produto"]="Cooler";
					break;
				case 7:
					$_SESSION["tipo_produto"]="Fonte";
					break;
				case 8:
					$_SESSION["tipo_produto"]="Gabinete";
					break;
				case 9:
					$_SESSION["tipo_produto"]="Kit de fans";
					break;
				case 10:
					$_SESSION["tipo_produto"]="Mouse";
					break;
				case 11:
					$_SESSION["tipo_produto"]="Teclado";
					break;
				case 12:
					$_SESSION["tipo_produto"]="Monitor";
					break;
				case 13:
					$_SESSION["tipo_produto"]="Periféricos";
					break;
				case 14:
					$_SESSION["tipo_produto"]="Desktop";
					break;
				case 15:
					$_SESSION["tipo_produto"]="Notebook";
					break;
			}
			$titulo="Cadastrar ".$_SESSION["tipo_produto"];
		}
	}else
		$titulo="Cadastrar produto";

	$abastecedor=true;
	include("../head-foot/header_empresa.php");

	if(isset($_SESSION["titulo"])&&!isset($_GET["cod"])){
		unset(
			$_SESSION["titulo"],
			$_SESSION["descricao"],
			$_SESSION["quantidade"],
			$_SESSION["linha"],
			$_SESSION["preco_custo"],
			$_SESSION["preco_revenda"],
			$_SESSION["tipo"],
			$_SESSION["altura"],
			$_SESSION["comprimento"],
			$_SESSION["largura"],
			$_SESSION["peso"],
			$_SESSION["cod"]
		);
	}

	$titulo=$descricao=$quantidade=$linha=$preco_custo=$preco_revenda=$tipo=$altura=$comprimento=$largura=$peso="";
	$titulo_err=$descricao_err=$quantidade_err=$linha_err=$preco_custo_err=$preco_revenda_err=$tipo_err=$altura_err=$comprimento_err=$largura_err=$peso_err="";
	$tipo_descricao=true;  //true para descrição de produto e false para cargo

	if(isset($_POST["submit"])){

		include ("../valida/descricao.php");
		include ("../valida/produto.php");
		
		$_SESSION["titulo"]=$_POST["titulo"];
		$_SESSION["descricao"]=$_POST["descricao"];
		$_SESSION["quantidade"]=$_POST["quantidade"];
		$_SESSION["fabricante"]=$_POST["fabricante"];
		$_SESSION["linha"]=$_POST["linha"];
		$_SESSION["preco_custo"]=$_POST["preco_custo"];
		$_SESSION["preco_revenda"]=$_POST["preco_revenda"];
		$_SESSION["tipo"]=$_POST["tipo"];
		$_SESSION["altura"]=$_POST["altura"];
		$_SESSION["comprimento"]=$_POST["comprimento"];
		$_SESSION["largura"]=$_POST["largura"];
		$_SESSION["peso"]=$_POST["peso"];


		if(
			empty($titulo_err)&&
			empty($descricao_err)&&
			empty($quantidade_err)&&
			empty($linha_err)&&
			empty($preco_custo_err)&&
			empty($preco_revenda_err)&&
			empty($altura_err)&&
			empty($comprimento_err)&&
			empty($largura_err)&&
			empty($peso_err)
		){
			if(isset($_GET["cod"])){
				$_SESSION["cod"]=$produto["cod_produto"];
				$_SESSION["alterar_produto"]=true;
				header("location: fotos_produto.php");
			}else{
				$sql="INSERT INTO `produtos` (`cod_produto`, `titulo`, `descricao`, `quantidade`, `fabricante`, `linha`, `preco_custo`, `preco_revenda`, `tipo`, `altura`, `comprimento`, `largura`, `peso` ) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		
				if($stmt=$mysqli->prepare($sql)){
					$stmt->bind_param("ssssssssssss", $param_titulo, $param_descricao, $param_quantidade, $param_fabricante, $param_linha, $param_preco_custo, $param_preco_revenda, $param_tipo, $param_altura, $param_comprimento, $param_largura, $param_peso);
	
					$param_titulo=$titulo;
					$param_descricao=$descricao;
					$param_quantidade=$quantidade;
					$param_fabricante=$_SESSION["fabricante"];
					$param_linha=$linha;
					$param_preco_custo=$preco_custo;
					$param_preco_revenda=$preco_revenda;
					$param_tipo=$_SESSION["tipo"];
					$param_altura=$altura;
					$param_comprimento=$comprimento;
					$param_largura=$largura;
					$param_peso=$peso;
				}else
					die("Ops! Algo deu errado. Por favor, tente novamente mais tarde.");
		
				if($stmt->execute()){
					$stmt->store_result();
	
					$stmt=$mysqli->prepare("SELECT cod_produto FROM produtos WHERE descricao=?");
	
					$stmt->bind_param("s", $param_descricao);
	
					$param_descricao=$descricao;
	
					$stmt->execute();
	
					$stmt->bind_result($cod_produto);
	
					if($stmt->fetch()){
						$_SESSION["cod"]=$cod_produto;
						$_SESSION["alterar_produto"]=false;
						header("location: fotos_produto.php");
					}
	
					$stmt->close();
				}
			}
		}
	}
?>
	<a class="back"
	href="<?php
			if(isset($_GET["cod"])&&!isset($_GET["tipo"]))
				echo "profile_produto.php?cod=".$_GET["cod"];
			elseif(!isset($_GET["cod"])&&isset($_GET["tipo"]))
				echo "view_produto.php?tipo=".$_GET["tipo"];
		?>">
		<i class="bi bi-arrow-left-circle-fill"></i>
	</a>

	<?php
		if(isset($produto)){
			$nome_produto=$produto["titulo"];
			if(strlen($nome_produto)>40)
				$nome_produto=substr($nome_produto,0,40)."...";
		}
	?>

	<h2 class="text-center text-white mb-3"><?php if(isset($produto)) echo "Alterar:  <span class='display-6 fs-2'>".$nome_produto."<span>"; else echo "Cadastrar ".$_SESSION["tipo_produto"]; ?></h2>

	<div class="register-empresa produto">
		<form action="" method="post" id="form-register" autocomplete="off">
			<div class="form-floating mb-2">
				<input type="text" name="titulo" maxlength="60"
				class="form-control <?php
										echo (!empty($titulo_err)) ? 'is-invalid' : '';
										?>"
				value="<?php
							if((isset($produto)&&!isset($_SESSION["titulo"]))||(isset($produto)&&isset($_SESSION["titulo"])&&$_SESSION["titulo"]==""))
								echo $produto["titulo"];
								
							if(isset($_SESSION["titulo"])&&$_SESSION["titulo"]!="")
								echo $_SESSION["titulo"];
						?>"
				placeholder="titulo">
				<label>Título</label>
				<span class="text-danger"><?php echo $titulo_err; ?></span>
			</div>
			<div class="form-floating mb-2">
				<textarea name="descricao" placeholder="descricao" maxlength="10000"
					class="form-control <?php
											echo (!empty($descricao_err)) ? 'is-invalid' : '';
											?>"
					><?php
							if((isset($produto)&&!isset($_SESSION["descricao"]))||(isset($produto)&&isset($_SESSION["descricao"])&&$_SESSION["descricao"]==""))
								echo $produto["descricao"];

							if(isset($_SESSION["descricao"])&&$_SESSION["descricao"]!="")
								echo $_SESSION["descricao"];
						?></textarea>

				<label>Descriçao do produto</label>
				
				<span class="color-pink">
					<?php echo $descricao_err; ?>
				</span>
			</div>
			<div class="row mb-4">
				<div class="col-2">
					<div class="form-floating">
						<select name="fabricante" class="form-control">
							<?php
								$result=$mysqli->query("SELECT * FROM fornecedores");
								if($result->num_rows>0){
									while($fornecedor=$result->fetch_assoc()){
										if(isset($_GET["cod"])){
											if($produto["fabricante"]==$fornecedor["cod_fornecedor"])
												echo "<option value='".$fornecedor["cod_fornecedor"]."' selected>".$fornecedor["nome_fantasia"]."</option>";
											else
												echo "<option value='".$fornecedor["cod_fornecedor"]."'>".$fornecedor["nome_fantasia"]."</option>";
										}else
											echo "<option value='".$fornecedor["cod_fornecedor"]."'>".$fornecedor["nome_fantasia"]."</option>";
									}
								}
							?>
						</select>
						<label>Fabricante</label>
					</div>
				</div>
				<div class="<?php if(isset($_SESSION["tipo_produto"])) echo "col-6"; else echo "col-4"; ?>">
					<div class="form-floating">
						<input type="text" name="linha"
						class="form-control <?php
														echo (!empty($linha_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($produto)&&!isset($_SESSION["linha"]))||(isset($produto)&&isset($_SESSION["linha"])&&$_SESSION["linha"]==""))
											echo $produto["linha"];
											
										if(isset($_SESSION["linha"])&&$_SESSION["linha"]!="")
											echo $_SESSION["linha"];
								?>"
						placeholder="linha">
						<label>Linha</label>
						<span class="text-danger"><?php echo $linha_err; ?></span>
					</div>
				</div>
				<?php
					if(isset($_SESSION["tipo_produto"])){
						echo "<input type='hidden' value='".$_SESSION["tipo_produto"]."' name='tipo'>";
					}else{
						$tipos_produtos=array('Placa mãe','CPU','Memória RAM','GPU','HD','SSD','Cooler','Fonte','Gabinete','Kit de fans','Mouse','Teclado','Monitor','Periféricos','Desktop','Notebook');
						echo "
					<div class='col'>
						<div class='form-floating'>
							<select name='tipo' class='form-control'>";
									foreach($tipos_produtos as $tipo){
										if($produto["tipo"]==$tipo)
											echo "<option value='$tipo' selected>$tipo</option>";
										else
											echo "<option value='$tipo'>$tipo</option>";
									}
						echo "</select>
							<label>Tipo de produto</label>
						</div>
					</div>
						";
					}
				?>
				<div class="<?php if(isset($_SESSION["tipo_produto"])) echo "col-4"; else echo "col-2"; ?>">
					<div class="form-floating">
						<input type="number" name="quantidade" id="quantidade"
						class="form-control <?php
														echo (!empty($quantidade_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
									if((isset($produto)&&!isset($_SESSION["quantidade"]))||(isset($produto)&&isset($_SESSION["quantidade"])&&$_SESSION["quantidade"]==""))
										echo $produto["quantidade"];
										
									if(isset($_SESSION["quantidade"])&&$_SESSION["quantidade"]!="")
										echo $_SESSION["quantidade"];
								?>"
						placeholder="quantidade">
						<label>Quantidade</label>
						<span class="text-danger"><?php echo $quantidade_err; ?></span>
					</div>
				</div>
			</div>
			<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Preços</h3>
			<div class="row mb-4">
				<div class="col">
					<div class="form-floating">
						<input type="number" name="preco_custo" id="preco"
						class="form-control <?php
														echo (!empty($preco_custo_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($produto)&&!isset($_SESSION["preco_custo"]))||(isset($produto)&&isset($_SESSION["preco_custo"])&&$_SESSION["preco_custo"]==""))
											echo $produto["preco_custo"];
											
										if(isset($_SESSION["preco_custo"])&&$_SESSION["preco_custo"]!="")
											echo $_SESSION["preco_custo"];
								?>"
						placeholder="preco_custo">
						<label>Preço de custo (R$)</label>
						<span class="text-danger"><?php echo $preco_custo_err; ?></span>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-2">
						<input type="number" name="preco_revenda" id="preco2"
						class="form-control <?php
														echo (!empty($preco_revenda_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($produto)&&!isset($_SESSION["preco_revenda"]))||(isset($produto)&&isset($_SESSION["preco_revenda"])&&$_SESSION["preco_revenda"]==""))
											echo $produto["preco_revenda"];
											
										if(isset($_SESSION["preco_revenda"])&&$_SESSION["preco_revenda"]!="")
											echo $_SESSION["preco_revenda"];
								?>"
						placeholder="preco_revenda">
						<label>Preço de revenda (R$)</label>
						<span class="text-danger"><?php echo $preco_revenda_err; ?></span>
					</div>
				</div>
			</div>
			<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Dimensões</h3>
			<div class="row">
				<div class="col-6 mb-1">
					<div class="form-floating">
						<input type="number" name="altura" step="0.001"
						class="form-control <?php
														echo (!empty($altura_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($produto)&&!isset($_SESSION["altura"]))||(isset($produto)&&isset($_SESSION["altura"])&&$_SESSION["altura"]==""))
											echo $produto["altura"];
											
										if(isset($_SESSION["altura"])&&$_SESSION["altura"]!="")
											echo $_SESSION["altura"];
								?>"
						placeholder="altura">
						<label>Altura (cm)</label>
						<span class="text-danger"><?php echo $altura_err; ?></span>
					</div>
				</div>
				<div class="col-6 mb-1">
					<div class="form-floating mb-2">
						<input type="number" name="comprimento" step="0.001"
						class="form-control <?php
														echo (!empty($comprimento_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($produto)&&!isset($_SESSION["comprimento"]))||(isset($produto)&&isset($_SESSION["comprimento"])&&$_SESSION["comprimento"]==""))
											echo $produto["comprimento"];
											
										if(isset($_SESSION["comprimento"])&&$_SESSION["comprimento"]!="")
											echo $_SESSION["comprimento"];
								?>"
						placeholder="comprimento">
						<label>Comprimento (cm)</label>
						<span class="text-danger"><?php echo $comprimento_err; ?></span>
					</div>
				</div>
				<div class="col-6">
					<div class="form-floating mb-2">
						<input type="number" name="largura" step="0.001"
						class="form-control <?php
														echo (!empty($largura_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($produto)&&!isset($_SESSION["largura"]))||(isset($produto)&&isset($_SESSION["largura"])&&$_SESSION["largura"]==""))
											echo $produto["largura"];
											
										if(isset($_SESSION["largura"])&&$_SESSION["largura"]!="")
											echo $_SESSION["largura"];
								?>"
						placeholder="largura">
						<label>Largura (cm)</label>
						<span class="text-danger"><?php echo $largura_err; ?></span>
					</div>
				</div>
				<div class="col-6">
					<div class="form-floating mb-2">
						<input type="number" name="peso" step="0.001"
						class="form-control <?php
														echo (!empty($peso_err)) ? 'is-invalid' : '';
												?>"
						value="<?php
										if((isset($produto)&&!isset($_SESSION["peso"]))||(isset($produto)&&isset($_SESSION["peso"])&&$_SESSION["peso"]==""))
											echo $produto["peso"];
											
										if(isset($_SESSION["peso"])&&$_SESSION["peso"]!="")
											echo $_SESSION["peso"];
								?>"
						placeholder="peso">
						<label>Peso (KG)</label>
						<span class="text-danger"><?php echo $peso_err; ?></span>
					</div>
				</div>
			</div>
		</form>
	</div>
	<button type="submit" class="btn-produto border-0 bg-transparent" form="form-register" text="avançar" name="submit">
		<i class="bi bi-arrow-right-circle-fill"></i>
	</button>
	<script>
		$('#quantidade').mask('000');
		$('#preco').mask('00000');
		$('#preco2').mask('00000');
	</script>
<?php
	include("../head-foot/footer_empresa.php");

	$mysqli->close();
?>