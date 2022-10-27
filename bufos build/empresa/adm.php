<?php
	$titulo="Área administrativa";
	$adm=true;
	$adm_main_page=false;
	include("head-foot/header_empresa.php");
?>
	<h1 class="text-center text-white mb-5 title-adm">Área administrativa</h1>
	<div class="container mx-auto justify-content-center adm-options">
		<a href="cargo/view_cargo.php" class="background-pink text-decoration-none">
			<div class="text-white d-flex justify-content-center align-items-center flex-column py-4">
				<i class="bi bi-credit-card-2-front mb-3"></i>
				<span class="fs-3">Cargos</span>
			</div>
		</a>
		<a href="fornecedor/view_fornecedor.php" class="background-pink text-decoration-none">
			<div class="text-white d-flex justify-content-center align-items-center flex-column py-4">
				<i class="bi bi-building mb-3"></i>
				<span class="fs-3">Fornecedores</span>
			</div>
		</a>
		<a href="funcionario/view_funcionario.php" class="background-pink text-decoration-none">
			<div class="text-white d-flex justify-content-center align-items-center flex-column py-4">
				<i class="bi bi-person-badge mb-3"></i>
				<span class="fs-3">Funcionários</span>
			</div>
		</a>
	</div>
<?php
	include("head-foot/footer_empresa.php");
?>