<?php
	$titulo="Estoque";
   $abastecedor=true;
	include("../head-foot/header_empresa.php");
	
	$sql="SELECT * FROM produtos";
   $result=$mysqli->query($sql);

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
?>
   <h2 class="mb-3 text-center text-white">ESTOQUE: <?php echo $result->num_rows." produtos"; ?></h2>
   <div class="container estoque">
      <a class="fs-3" href="view_produto.php?tipo=0">
         <i class="bi bi-motherboard"></i>
         Placa mãe
      </a>
      <a class="fs-3" href="view_produto.php?tipo=1">
         <i class="bi bi-cpu"></i>
         CPU
      </a>
      <a class="fs-3" href="view_produto.php?tipo=10">
         <i class="fs-1 bi bi-mouse"></i>
         Mouse
      </a>
      <a class="fs-3" href="view_produto.php?tipo=11">
         <i class="fs-1 bi bi-keyboard"></i>
         Teclado
      </a>
      <a class="fs-3" href="view_produto.php?tipo=4">
         <i class="fs-1 bi bi-device-hdd"></i>
         HD
      </a>
      <a class="fs-3" href="view_produto.php?tipo=5">
         <i class="fs-1 bi bi-device-ssd"></i>
         SSD
      </a>
      <a class="fs-3" href="view_produto.php?tipo=2">
         <i class="bi bi-memory"></i>
         RAM
      </a>
      <a class="fs-3" href="view_produto.php?tipo=3">
         <i class="bi bi-gpu-card"></i>
         GPU
      </a>
      <a class="fs-3" href="view_produto.php?tipo=6">
         <img src="data:image/png;base64,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">
         Cooler
      </a>
      <a class="fs-3" href="view_produto.php?tipo=9">
         <i class="fs-1 bi bi-fan"></i>
         Kit de fans
      </a>
      <a class="fs-3" href="view_produto.php?tipo=8">
         <i class="fs-1 bi bi-pc"></i>
         Gabinete
      </a>
      <a class="fs-3" href="view_produto.php?tipo=12">
         <i class="fs-1 bi bi-display"></i>
         Monitor
      </a>
      <a class="fs-3" href="view_produto.php?tipo=7">
         <i class="fs-1 bi bi-lightning-charge"></i>
         Fonte
      </a>
      <a class="fs-3" href="view_produto.php?tipo=13">
         <i class="fs-1 bi bi-usb-symbol"></i>
         Periféricos
      </a>
      <a class="fs-3" href="view_produto.php?tipo=14">
         <i class="fs-1 bi bi-pc-display"></i>
         Desktop
      </a>
      <a class="fs-3" href="view_produto.php?tipo=15">
         <i class="fs-1 bi bi-laptop"></i>
         Notebook
      </a>
   </div>
<?php
	include("../head-foot/footer_empresa.php");
?>