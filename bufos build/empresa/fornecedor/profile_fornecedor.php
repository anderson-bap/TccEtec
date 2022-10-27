<?php
	include("../config.php");

	if(isset($_GET["cod"])){
		$sql="SELECT * FROM fornecedores WHERE cod_fornecedor=".$_GET["cod"];
		$result=$mysqli->query($sql);

		if($result->num_rows>0){
			$fornecedor=$result->fetch_assoc();
			$titulo="Dados: ".$fornecedor["nome_fantasia"];
		}
	}else
		die("404 NOT FOUND");

	$adm=true;
	include("../head-foot/header_empresa.php");

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

	if(isset($_POST["submit"])){
		if($_POST["delete"]=="TYUBNV134"){
			$sql="DELETE FROM fornecedores WHERE cod_fornecedor=".$_GET["cod"];
			$mysqli->query($sql);
			header("location: view_fornecedor.php");
		}else
			$delete_err="Senha de acesso inválida";
	}
?>

	<a href="view_fornecedor.php" class="back">
		<i class="bi bi-arrow-left-circle-fill"></i>
	</a>
<?php
	if(!isset($fornecedor)){
		echo "
			<div class='d-flex justify-content-center alert-empresa'>
					<div class='alert alert-danger'>Fornecedor inexistente!</div>
			</div>
		";
	}else{
		$cnpj=$fornecedor["cnpj"];

		$cnpj=substr($cnpj,0,2).".".substr($cnpj,2,3).".".substr($cnpj,5,3)."/".substr($cnpj,8,4)."-".substr($cnpj,12,2);

		$telefone=$fornecedor["telefone"];

		$ddd_telefone=substr($telefone,0,2);
		$inicio_telefone=substr($telefone,2,4);
		$final_telefone=substr($telefone,6,4);

		$telefone="($ddd_telefone) $inicio_telefone-$final_telefone";

		$celular=$fornecedor["celular"];

		$ddd_celular=substr($celular,0,2);
		$inicio_celular=substr($celular,2,5);
		$final_celular=substr($celular,7,4);

		$celular="($ddd_celular) $inicio_celular-$final_celular";

		$xml=simplexml_load_file("http://viacep.com.br/ws/".$fornecedor["cep"]."/xml/");

		$logradouro=$bairro=$localidade=$uf="";

		if(!$xml->erro){
			if($xml->logradouro!="")
					$logradouro="$xml->logradouro, ";
			if($xml->bairro!="")
					$bairro="$xml->bairro, ";
			if($xml->localidade!="")
					$localidade="$xml->localidade, ";
			if($xml->uf!="")
					$uf=$xml->uf;
		}
		
		$endereco=$logradouro.$bairro.$localidade.$uf;

		echo "
			<h2 class='text-center text-white mb-5'>Dados do fornecedor</h2>
			<div class='dados fornecedor'>
					<div class='row mb-4'>
						<div class='col mt-4'>
							<p class='mb-2'><b class='me-2'>Nome:</b>".$fornecedor["nome_fantasia"]."</p>
							<p class='mb-4'><b class='me-2'>Razão Social:</b>".$fornecedor["razao_social"]."</p>
							<p><b class='me-2'>CNPJ:</b>$cnpj</p>
						</div>
						<div class='col-3'>
							<img class='img-fluid' src='".$fornecedor["foto"]."' alt='".$fornecedor["nome_fantasia"]."'>
						</div>
					</div>
					<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Contato</h3>
					<div class='row mb-5'>
						<div class='col-5'>
							<p class='mb-2'><b class='me-2'>Telefone:</b>$telefone</p>
							<p class='mb-2'><b class='me-2'>Celular:</b>$celular</p>
						</div>
						<div class='col'>
							<p class='mb-2'><b class='me-2'>Website:</b><a href='".$fornecedor["website"]."' target='_blank'>".$fornecedor["website"]."</a></p>
							<p class='mb-2'><b class='me-2'>Email:</b>".$fornecedor["email"]."</p>
						</div>
					</div>
					<h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Endereço</h3>
					<div class='row mb-2'>
						<div class='col-3'>
							<p class='mb-2'><b class='me-2'>CEP:</b>".$fornecedor["cep"]."</p>
						</div>
						<div class='col'>
							<p class='mb-2'><b class='me-2'>Complemento:</b>".$fornecedor["complemento"]."</p>
						</div>
					</div>
					<p><b class='me-2'>Endereço:</b>$endereco</p>
			</div>
			<a href='register_fornecedor.php?cod=".$fornecedor["cod_fornecedor"]."' class='alterar-empresa text-decoration-none d-flex align-items-center justify-content-center' text='alterar'>
				<i class='bi bi-pen text-white fs-2'></i>
			</a>
			<button type='button' class='excluir-empresa text-decoration-none d-flex align-items-center justify-content-center border-0' text='excluir' data-bs-toggle='modal' data-bs-target='#delete'>
				<i class='bi bi-trash text-white fs-2'></i>
			</button>
		";
	}
	if(isset($fornecedor)):

		if(isset($_POST["close"]))
			unset($delete_err);
?>
	<div class="modal fade <?php
										if(isset($delete_err)&&!isset($_POST["close"]))
											echo "show";
									?>"
	id="delete"
	style="<?php
				if(isset($delete_err)&&!isset($_POST["close"]))
					echo "display: block;";
				else
					echo "display: none;";
			?>">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<header class="modal-header pe-4">
					<h4 class="modal-title">Excluir fornecedor</h4>
					<form action="" method="post">
						<button type="submit" class="btn-close" data-bs-dismiss="modal" name="close"></button>
					</form>
				</header>
				<div class="modal-body text-center pb-3 pt-4">
					<p class="text-center">Preencha o campo com a chave de acesso</p>
					<form action="" method="post" id="form-delete">
						<input type="password" name="delete" class="form-control">
						<span class="text-danger"><?php if(isset($delete_err)) echo $delete_err; ?></span>
					</form>
				</div>
				<footer class="modal-footer">
					<button type="submit" class="col btn btn-outline-primary" form="form-delete" name="submit">Confirmar</button>
					<form action="" method="post">
						<button class="col btn btn-outline-danger" data-bs-dismiss="modal" name="close">Cancelar</button>
					</form>
				</footer>
			</div>
		</div>
	</div>
	<script src="../scripts/main.js"></script>
<?php
	endif;

	$mysqli->close();

	include("../head-foot/footer_empresa.php");
?>