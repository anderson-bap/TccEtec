<?php
	$session_start=session_start();

	include("../config.php");

	if(!isset($_SESSION["cod"])){
		header("location: estoque.php");
		exit;
	}else{
		$sql="SELECT * FROM produtos WHERE cod_produto=".$_SESSION["cod"];
		$result=$mysqli->query($sql);
		$produto=$result->fetch_assoc();
		$titulo="Fotos: ".$produto["titulo"];
	}

	$abastecedor=true;
	include("../head-foot/header_empresa.php");

	$foto=$foto_err="";
	$dir="../img/produto/";

	$name_button="Cadastrar";

	if(isset($_POST["preview"]))
		include ("../valida/fotos.php");
?>

	<a class="back" href="register_produto.php?cod=<?php echo $_SESSION["cod"]; ?>">
		<i class="bi bi-arrow-left-circle-fill"></i>
	</a>
	
<?php
	if($_SESSION["alterar_produto"]){
		$stmt=$mysqli->prepare("SELECT * FROM fotos_produto WHERE cod_produto=?");
		$stmt->bind_param("s",$param_cod_produto);

		$param_cod_produto=$_SESSION["cod"];
		
		$stmt->execute();

		$stmt_result = $stmt->get_result();

		if ($stmt_result->num_rows==6){
			$img=array();
			$img_original=array();
			while($fotos = $stmt_result->fetch_assoc()){
				array_push($img_original, $fotos["path"]);
				array_push($img, "<img src='".$fotos["path"]."'>");
			}
			echo "
				<h2 class='text-center text-white mb-4'>Fotos originais</h2>
				<div class='mb-5 fotos-originais'>".
					$img[0].
					$img[1].
					$img[2].
					$img[3].
					$img[4].
					$img[5].
				"</div>
			";
		}
	}
?>
		
	<form action="" method="post" enctype="multipart/form-data" class="mb-3 mx-auto d-flex align-items-center flex-column" id="form-register" style="width: 30rem;<?php if(!$_SESSION["alterar_produto"]) echo "margin-top: 8rem"; ?>">
		<label class="mb-2 text-white fs-4">Carregue 6 fotos</label>
		<input multiple type="file" name="foto[]" class="form-control mb-1">
		<span class="text-danger"><?php echo $foto_err; ?></span>
	</form>

	<button type="submit" class="btn-preview-empresa border-0 d-flex align-items-center justify-content-center" form="form-register" text="<?php if($_SESSION["alterar_produto"]) echo "salvar"; else echo "cadastrar"; ?>" name="preview">
		<span class="iconify fs-2 text-white" data-icon="fa:floppy-o"></span>
	</button>
		
<?php

	if(isset($_POST["preview"])){

		include("../config.php");

		if($_SESSION["alterar_produto"])
			$sql="SELECT * FROM fotos_temp WHERE cod_produto=?";
		else
			$sql="SELECT * FROM fotos_produto WHERE cod_produto=?";

		if($stmt=$mysqli->prepare($sql)){
			$stmt->bind_param("s",$param_cod_produto);

			$param_cod_produto=$_SESSION["cod"];
			
			$result=$stmt->execute();

			$stmt_result = $stmt->get_result();

			if ($stmt_result->num_rows==6){
				$img_preview=array();
				$_SESSION["path"]=array();
				
				while($fotos = $stmt_result->fetch_assoc()){
					array_push($img_preview, "<img src='".$fotos["path"]."'>");
					array_push($_SESSION["path"], $fotos["path"]);
				}
			}
		}
		$stmt->close();

		if(count($files["name"])==6){
			$stmt=$mysqli->prepare("SELECT nome_fantasia FROM fornecedores WHERE cod_fornecedor=?");
			$stmt->bind_param("s", $param_cod_fornecedor);
			$param_cod_fornecedor=$_SESSION["fabricante"];
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($fabricante);
			$stmt->fetch();
			$stmt->close();

			if($_SESSION["alterar_produto"])
				$name="Alterar";

			$_SESSION["preview"]="
				<div class='preview-empresa'>
					<div class='p-4 produto'>
						<div class='row'>
							<div class='col'>
								<div class='mb-2'>
									<p class='mb-1'><b class='me-2'>Título:</b>".$_SESSION["titulo"]."</p>
								</div>
								<div class='mb-2'>
									<p class='mb-1 descricao'><b class='me-2'>Descrição:</b>".$_SESSION["descricao"]."</p>
								</div>
								<div class='row mb-2'>
								    <div class='col'>
								        <p class='mb-1'><b class='me-2'>Tipo:</b>".$_SESSION["tipo"]."</p>
								    </div>
								    <div class='col'>
								        <p class='mb-1'><b class='me-2'>Quantidade:</b>".$_SESSION["quantidade"]."</p>
								    </div>
								</div>
								<div class='row mb-2'>
								    <div class='col'>
								        <p class='mb-1'><b class='me-2'>Fabricante:</b>$fabricante</p>
								    </div>
								    <div class='col'>
								        <p class='mb-1'><b class='me-2'>Linha:</b>".$_SESSION["linha"]."</p>
								    </div>
								</div>
								<h5 class='mb-3 border-bottom-pink pb-1'>Preços</h5>
								<div class='row mb-2'>
								    <div class='col'>
								        <p class='mb-1'><b class='me-2'>Preço de compra:</b>R$".$_SESSION["preco_custo"].",00</p>
								    </div>
								    <div class='col'>
								        <p class='mb-1'><b class='me-2'>Preço de revenda:</b>R$".$_SESSION["preco_revenda"].",00</p>
								    </div>
								</div>
								<h5 class='mb-3 border-bottom-pink pb-1'>Dimensões</h5>
								<div class='row mb-2'>
								    <div class='col'>
								        <p class='mb-1'><b class='me-2'>Altura:</b>".$_SESSION["altura"]."cm</p>
								    </div>
								    <div class='col'>
								        <p class='mb-1'><b class='me-2'>Comprimento:</b>".$_SESSION["comprimento"]."cm</p>
								    </div>
								</div>
								<div class='row mb-2'>
								    <div class='col'>
								        <p class='mb-1'><b class='me-2'>Largura:</b>".$_SESSION["largura"]."cm</p>
								    </div>
								    <div class='col'>
								        <p class='mb-1'><b class='me-2'>Peso:</b>".$_SESSION["peso"]."cm</p>
								    </div>
								</div>
								<form action='' method='post' class='row m-0'>
									<button type='submit' class='btn btn-primary col-8' name='cadastrar'>$name_button</button>
									<button type='submit' class='btn text-white col' name='cancelar'>Cancelar</button>
								</form>
							</div>
							<div class='gallery col'>".
								$img_preview[0].
								$img_preview[1].
								$img_preview[2].
								$img_preview[3].
								$img_preview[4].
								$img_preview[5].
							"</div>
						</div>
					</div>
				</div>
			";
		}
	}

	if(isset($_POST["cadastrar"])){
		if($_SESSION["alterar_produto"]){
			for($i=0;$i<count($img_original);$i++){
				unlink($img_original[$i]);
			}

			$mysqli->query("DELETE FROM fotos_temp WHERE cod_produto=".$_SESSION["cod"]);
			$mysqli->query("DELETE FROM fotos_produto WHERE cod_produto=".$_SESSION["cod"]);

			for($i=0;$i<count($_SESSION["path"]);$i++){
				$mysqli->query("INSERT INTO fotos_produto (path, cod_produto) VALUES ('".$_SESSION["path"][$i]."', '".$_SESSION["cod"]."')");
			}

			$mysqli->query("UPDATE `produtos` SET `titulo`='".$_SESSION["titulo"]."', `descricao`='".$_SESSION["descricao"]."', `quantidade`='".$_SESSION["quantidade"]."', `fabricante`='".$_SESSION["fabricante"]."', `linha`='".$_SESSION["linha"]."', `preco_custo`='".$_SESSION["preco_custo"]."', `preco_revenda`='".$_SESSION["preco_revenda"]."', `tipo`='".$_SESSION["tipo"]."', `altura`='".$_SESSION["altura"]."', `comprimento`='".$_SESSION["comprimento"]."', `largura`='".$_SESSION["largura"]."', `peso`='".$_SESSION["peso"]."' WHERE `produtos`.`cod_produto`=".$_SESSION["cod"]);
		}

		$url="profile_produto.php?cod=".$_SESSION["cod"];
		unset(
			$_SESSION["cod"],
			$_SESSION["path"],
			$_SESSION["fabricante"],
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
			$_SESSION["tipo_produto"]
		);
		unset($_SESSION["preview"]);
		echo "
			<script>
				window.location.assign('http://empresa.bufosregulares.com/produto/$url');
			</script>
		";
	}

	if(isset($_POST["cancelar"])){
		$_SESSION["preview"]="";

		for($i=0;$i<count($_SESSION["path"]);$i++){
			unlink($_SESSION["path"][$i]);
		}
		
		if($_SESSION["alterar_produto"])
			$mysqli->query("DELETE FROM fotos_temp WHERE cod_produto=".$_SESSION["cod"]);
		else
			$mysqli->query("DELETE FROM fotos_produto WHERE cod_produto=".$_SESSION["cod"]);

		echo "
			<script>
				window.location.assign('http://empresa.bufosregulares.com/produto/fotos_produto.php');
			</script>
		";
	}

	$mysqli->close();

	if(isset($_SESSION["preview"]))
			echo $_SESSION["preview"];

	include("../head-foot/footer_empresa.php");
?>