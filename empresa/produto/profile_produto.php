<?php
	include("../config.php");

	if(isset($_GET["cod"])){
		$sql="SELECT * FROM produtos WHERE cod_produto=".$_GET["cod"];
		$result=$mysqli->query($sql);

		if($result->num_rows>0){
			$produto=$result->fetch_assoc();
			$titulo="Dados: ".$produto["titulo"];
			$titulo=str_replace("Ã£","ã",$titulo);
		}
	}else
		die("404 NOT FOUND");

	$abastecedor=true;
	include("../head-foot/header_empresa.php");

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

	if(isset($produto)){
		switch($produto["tipo"]){
			case "Placa mãe":
				$cod=0;
				break;
			case "CPU":
				$cod=1;
				break;
			case "Memória RAM":
				$cod=2;
				break;
			case "GPU":
				$cod=3;
				break;
			case "HD":
				$cod=4;
				break;
			case "SSD":
				$cod=5;
				break;
			case "Cooler":
				$cod=6;
				break;
			case "Fonte":
				$cod=7;
				break;
			case "Gabinete":
				$cod=8;
				break;
			case "Kit de fans":
				$cod=9;
				break;
			case "Mouse":
				$cod=10;
				break;
			case "Teclado":
				$cod=11;
				break;
			case "Monitor":
				$cod=12;
				break;
			case "Periféricos":
				$cod=13;
				break;
			case "Desktop":
				$cod=14;
				break;
			case "Notebook":
				$cod=15;
				break;
		}
	}

	if(isset($_POST["submit"])){
		$sql="DELETE FROM produtos WHERE cod_produto=".$_GET["cod"];
		$mysqli->query($sql);

		$sql="SELECT * FROM fotos_produto WHERE cod_produto=".$_GET["cod"];
		$result=$mysqli->query($sql);
		while($fotos=$result->fetch_assoc()){
			unlink($fotos["path"]);
			$mysqli->query("DELETE FROM fotos_produto WHERE cod_produto=".$_GET["cod"]);
		}

		header("location: view_produto.php?tipo=$cod");
	}
?>
	<a href="<?php
				if(isset($produto)){
					echo "view_produto.php?tipo=$cod";
				}else
					echo "estoque.php";
			?>"
	class="back">
		<i class="bi bi-arrow-left-circle-fill"></i>
	</a>
<?php
	if(!isset($produto)){
		echo "
			<div class='d-flex justify-content-center alert-empresa'>
				<div class='alert alert-danger'>Produto inexistente!</div>
			</div>
		";
	}else{
		if($stmt=$mysqli->prepare("SELECT * FROM fotos_produto WHERE cod_produto=?")){
			$stmt->bind_param("s",$param_cod_produto);

			$param_cod_produto=$_GET["cod"];
			
			$stmt->execute();

			$stmt_result = $stmt->get_result();

			if ($stmt_result->num_rows==6){
				$img=array();
				while($fotos = $stmt_result->fetch_assoc()){
					array_push($img, "<img src='".$fotos["path"]."' alt='".$produto["titulo"]."'>");
				}
			}
		}
		$stmt=$mysqli->prepare("SELECT nome_fantasia FROM fornecedores WHERE cod_fornecedor=?");
		$stmt->bind_param("s", $param_cod_fornecedor);
		$param_cod_fornecedor=$produto["fabricante"];
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($fabricante);
		$stmt->fetch();
		$stmt->close();
		
		echo "
			<h2 class='text-center mb-3 text-white'>Dados do produto</h2>
			<div class='dados produto'>
				<div class='mb-5 gallery-empresa'>".
					$img[0].
					$img[1].
					$img[2].
					$img[3].
					$img[4].
					$img[5].
				"</div>
				<div class='mb-3'>
					<span><b class='me-2'>Título:</b>".$produto["titulo"]."</span>
				</div>
				<div class='row mb-3'>
					<div class='col'>
						<span><b class='me-2'>Fabricante:</b>$fabricante</span>
					</div>
					<div class='col'>
						<span><b class='me-2'>Linha:</b>".$produto["linha"]."</span>
					</div>
					<div class='col'>
						<span><b class='me-2'>Tipo:</b>".$produto["tipo"]."</span>
					</div>
					<div class='col'>
						<span><b class='me-2'>Quantidade:</b>".$produto["quantidade"]."</span>
					</div>
				</div>
				<div class='mb-5'>
					<span><b class='me-2'>Descrição:</b>".$produto["descricao"]."</span>
				</div>
				<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Preços</h3>
				<div class='row mb-5'>
					<div class='col'>
						<span><b class='me-2'>Preço de compra:</b>R$".$produto["preco_custo"].",00</span>
					</div>
					<div class='col'>
						<span><b class='me-2'>Preço de revenda:</b>R$".$produto["preco_revenda"].",00</span>
					</div>
				</div>
				<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Dimensões</h3>
				<div class='row mb-5'>
					<div class='col'>
						<span><b class='me-2'>Altura:</b>".$produto["altura"]."cm</span> 
					</div>
					<div class='col'>
						<span><b class='me-2'>Comprimento:</b>".$produto["comprimento"]."cm</span>
					</div>
					<div class='col'>
						<span><b class='me-2'>Largura:</b>".$produto["largura"]."cm</span> 
					</div>
					<div class='col'>
						<span><b class='me-2'>Peso:</b>".$produto["peso"]."kg</span>
					</div>
				</div>
			</div>
			<a href='register_produto.php?cod=".$produto["cod_produto"]."' class='alterar-empresa text-decoration-none d-flex align-items-center justify-content-center' text='alterar'>
				<i class='bi bi-pen text-white fs-2'></i>
			</a>
			<button type='button' class='excluir-empresa text-decoration-none d-flex align-items-center justify-content-center border-0' text='excluir' data-bs-toggle='modal' data-bs-target='#delete'>
				<i class='bi bi-trash text-white fs-2'></i>
			</button>
		";
	}

	if(isset($produto)):
?>
	<div class="modal fade" id="delete">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<header class="modal-header pe-4">
					<h4 class="modal-title">Excluir produto</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" name="close"></button>
				</header>
				<div class="modal-body text-center pb-3 pt-4">
					<p class="text-center">Deseja excluir o produto?</p>
				</div>
				<footer class="modal-footer">
					<form action="" method="post" class="col-7 d-grid">
						<button type="submit" class="btn btn-outline-primary" name="submit">Confirmar</button>
					</form>
					<button type="button" class="col btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
				</footer>
			</div>
		</div>
	</div>
<?php
	endif;

	include("../head-foot/footer_empresa.php");

	$mysqli->close();
?>