<?php
   $titulo="Fornecedores";
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
?>
   <a href="register_fornecedor.php" class="plus" text="cadastrar">
      <i class="bi bi-plus-circle-fill"></i>
   </a>
   <a href="../adm.php" class="back">
      <i class="bi bi-arrow-left-circle-fill"></i>
   </a>

   <div class="container px-5">
      <form action="" method="get" class="mb-3">
         <input type="search" class="form-control" placeholder="Pesquisar fornecedores" name="search" value="<?php if(isset($_GET["search"])) echo $_GET["search"]; ?>" id="search">
      </form>
   </div>
<?php

   if(isset($_GET["search"])&&$_GET["search"]!=""){
      $search=trim($_GET["search"]);
      
      $sql="SELECT * FROM `fornecedores` WHERE `nome_fantasia` LIKE '%$search%' OR `razao_social` LIKE '%$search%' OR `cnpj` LIKE '%$search%' OR `telefone` LIKE '%$search%' OR `celular` LIKE '%$search%' OR `website` LIKE '%$search%' OR `email` LIKE '%$search%'";
   }else
      $sql="SELECT * FROM fornecedores";

   $result=$mysqli->query($sql);

   if($result){
      if($result->num_rows>0){
         echo "
      <div class='container px-5'>
         <div class='list'>
            <header class='mb-2 pb-3 fornecedor'>
               <div></div>
               <div class='text-center text-white'>Nome</div>
               <div class='text-center text-white'>CNPJ</div>
               <div class='text-center text-white'>CEP</div>
               <div class='text-center text-white'>Telefone</div>
               <div class='text-center text-white'>Email</div>
            </header>
            <main>
         ";

         while($fornecedor=$result->fetch_assoc()){
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

            echo "
               <a href='profile_fornecedor.php?cod=".$fornecedor["cod_fornecedor"]."' class='link-list mb-2 pb-2 fornecedor text-decoration-none align-items-center'>
                  <div>
                     <img src='".$fornecedor["foto"]."'>
                  </div>
                  <div class='text-center'>".$fornecedor["nome_fantasia"]."</div>
                  <div class='text-center'>$cnpj</div>
                  <div class='text-center'>".$fornecedor["cep"]."</div>
                  <div class='text-center'>$telefone</div>
                  <div class='text-center'>".$fornecedor["email"]."</div>
               </a>
            ";
         }
         echo "
            </main>
         </div>
      </div>
         ";
      }else{
         if(isset($_GET["search"]))
            echo "<div class='alert alert-danger mx-auto text-center' style='width: 20rem'>Fornecedor não encontrado</div>";
         else
            echo "<div class='alert alert-danger mx-auto' style='width: 20rem'>Não existem fornecedores cadastrados</div>";

         echo "
            <script>
               document.getElementById('search').focus();
               document.getElementById('search').value='';
            </script>
         ";
      }
   }

   $mysqli->close();

   include("../head-foot/footer_empresa.php");
?>