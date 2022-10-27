<?php
   if(isset($_GET["tipo"])){
      $session_start=session_start();

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
         
      include ("../config.php");

      switch($_GET["tipo"]){
         case 0:
            $_SESSION["tipo"]="Placa mãe";
            break;
         case 1:
            $_SESSION["tipo"]="CPU";
            break;
         case 2:
            $_SESSION["tipo"]="Memória RAM";
            break;
         case 3:
            $_SESSION["tipo"]="GPU";
            break;
         case 4:
            $_SESSION["tipo"]="HD";
            break;
         case 5:
            $_SESSION["tipo"]="SSD";
            break;
         case 6:
            $_SESSION["tipo"]="Cooler";
            break;
         case 7:
            $_SESSION["tipo"]="Fonte";
            break;
         case 8:
            $_SESSION["tipo"]="Gabinete";
            break;
         case 9:
            $_SESSION["tipo"]="Kit de fans";
            break;
         case 10:
            $_SESSION["tipo"]="Mouse";
            break;
         case 11:
            $_SESSION["tipo"]="Teclado";
            break;
         case 12:
            $_SESSION["tipo"]="Monitor";
            break;
         case 13:
            $_SESSION["tipo"]="Periféricos";
            break;
         case 14:
            $_SESSION["tipo"]="Desktop";
            break;
         case 15:
            $_SESSION["tipo"]="Notebook";
            break;
      }
   }else
      die("404 NOT FOUND");

      if(preg_match("/Ã£/",$_SESSION["tipo"]))
         $titulo=str_replace("Ã£","ã",$_SESSION["tipo"]);
      else
         $titulo=$_SESSION["tipo"];

      $abastecedor=true;
      include("../head-foot/header_empresa.php");
     
    if(isset($_GET["search"])&&$_GET["search"]!=""){
      $search=trim($_GET["search"]);
      $sql="SELECT * FROM `produtos` WHERE `tipo`='".$_SESSION["tipo"]."' AND `titulo` LIKE '%$search%' OR `tipo`='".$_SESSION["tipo"]."' AND `descricao` LIKE '%$search%' OR `tipo`='".$_SESSION["tipo"]."' AND `linha` LIKE '%$search%'";
    }else
      $sql="SELECT * FROM produtos WHERE `tipo`='".$_SESSION["tipo"]."'";
      
      $result=$mysqli->query($sql);
?>
   <a href="register_produto.php?tipo=<?php echo $_GET["tipo"]; ?>" class="plus" text="Cadastrar">
      <i class="bi bi-plus-circle-fill"></i>
   </a>
   <a href="estoque.php" class="back">
      <i class="bi bi-arrow-left-circle-fill"></i>
   </a>

   <h2 class="mb-3 text-center text-white"><?php
                                             if(preg_match("/Ã£/",$_SESSION["tipo"]))
                                                echo $titulo=str_replace("Ã£","ã",$_SESSION["tipo"]);
                                             else
                                                echo $_SESSION["tipo"];

                                             echo ": ".$result->num_rows." modelos";
                                          ?>
   </h2>

   <div class="container px-5">
      <form action="" method="get" class="mb-4">
         <input type="hidden" name="tipo" value="<?php echo $_GET["tipo"]; ?>">
         <input type="search" class="form-control" placeholder="Pesquisar produtos" name="search" value="<?php if(isset($_GET["search"])) echo $_GET["search"]; ?>" id="search">
         <button type="submit" class="d-none">Pesquisar</button>
      </form>
   </div>
   <?php
      if($result){
         if($result->num_rows>0){
            echo "
         <div class='container px-5'>
            <div class='list'>
                  <header class='mb-2 pb-3 produto'>
                     <div></div>
                     <div class='text-center text-white'>Nome</div>
                     <div class='text-center text-white'>Preço de custo</div>
                     <div class='text-center text-white'>Preço de revenda</div>
                     <div class='text-center text-white'>Fabricante</div>
                     <div class='text-center text-white'>Linha</div>
                  </header>
                  <main>
            ";
   
            while($produto=$result->fetch_assoc()){
               $stmt=$mysqli->prepare("SELECT path FROM fotos_produto WHERE cod_produto=?");
               $stmt->bind_param("s", $param_cod_produto);
               $param_cod_produto=$produto["cod_produto"];
               $stmt->execute();
               $stmt->store_result();
               $stmt->bind_result($foto);
               $stmt->fetch();
               $stmt=$mysqli->prepare("SELECT nome_fantasia FROM fornecedores WHERE cod_fornecedor=?");
               $stmt->bind_param("s", $param_cod_fornecedor);
               $param_cod_fornecedor=$produto["fabricante"];
               $stmt->execute();
               $stmt->store_result();
               $stmt->bind_result($fabricante);
               $stmt->fetch();
               $stmt->close();

               $titulo=$produto["titulo"];
               if(preg_match("/Ã£/",$titulo))
                  $titulo=str_replace("Ã£","ã",$titulo);

					if(strlen($titulo)>60)
						$descricao=substr($descricao,0,50)."...";
   
               echo "
                  <a href='profile_produto.php?cod=".$produto["cod_produto"]."' class='link-list mb-2 pb-2 produto text-decoration-none align-items-center'>
                     <div>
                        <img src='$foto'>
                     </div>
                     <div class='text-center'>$titulo</div>
                     <div class='text-center'>R$".$produto["preco_custo"].",00</div>
                     <div class='text-center'>R$".$produto["preco_revenda"].",00</div>
                     <div class='text-center'>$fabricante</div>
                     <div class='text-center'>".$produto["linha"]."</div>
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
               echo "<div class='alert alert-danger mx-auto text-center' style='width: 20rem'>Produto não encontrado</div>";
            else
               echo "<div class='alert alert-danger mx-auto' style='width: 20rem'>Não existem produtos cadastrados</div>";
   
            echo "
               <script>
                  document.getElementById('search').focus();
                  document.getElementById('search').value='';
               </script>
            ";
         }
      }

      $mysqli->close();
   ?>
</body>
</html>