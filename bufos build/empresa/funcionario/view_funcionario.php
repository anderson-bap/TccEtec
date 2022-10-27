<?php
   $titulo="Funcionários";
   $adm=true;
   include("../head-foot/header_empresa.php");
   
   unset(
      $_SESSION["nome"],
      $_SESSION["sobrenome"],
      $_SESSION["data_nasc"],
      $_SESSION["telefone"],
      $_SESSION["celular"],
      $_SESSION["cpf"],
      $_SESSION["cep"],
      $_SESSION["complemento"],
      $_SESSION["email"],
      $_SESSION["login"],
      $_SESSION["senha"],
      $_SESSION["confirm_senha"],
      $_SESSION["foto"],
      $_SESSION["endereco"],
      $_SESSION["foto_original"],
      $_SESSION["estado_civil"],
      $_SESSION["cargo"]
   );
?>
   <a href="register_funcionario.php" class="plus" text="cadastrar">
      <i class="bi bi-plus-circle-fill"></i>
   </a>
   <a href="../adm.php" class="back">
      <i class="bi bi-arrow-left-circle-fill"></i>
   </a>

   <div class="container px-5">
      <form action="" method="get" class="mb-3">
         <input type="search" class="form-control" placeholder="Pesquisar funcionários" name="search" value="<?php if(isset($_GET["search"])) echo $_GET["search"]; ?>" id="search">
      </form>
   </div>
<?php
   if(isset($_GET["search"])&&$_GET["search"]!=""){
      $search=trim($_GET["search"]);
      $sql="SELECT * FROM `funcionarios` WHERE `nome` LIKE '%$search%' OR `sobrenome` LIKE '%$search%' OR `cpf` LIKE '%$search%' OR `telefone` LIKE '%$search%' OR `celular` LIKE '%$search%' OR `email` LIKE '%$search%'";
   }else
      $sql="SELECT * FROM funcionarios";

   $result=$mysqli->query($sql);

   if($result){
      if ($result->num_rows>0){
         echo "
      <div class='container px-5'>
         <div class='list'>
             <header class='mb-2 pb-3 funcionario'>
                <div></div>
                <div class='text-center text-white'>Nome</div>
                <div class='text-center text-white'>CPF</div>
                <div class='text-center text-white'>Cargo</div>
             </header>
             <main>
         ";
 
         while($funcionario=$result->fetch_assoc()){
             $stmt=$mysqli->prepare("SELECT nome_cargo FROM cargos WHERE cod_cargo=?");
             $stmt->bind_param("s", $param_cod_cargo);
             $param_cod_cargo=$funcionario["cod_cargo"];
             $stmt->execute();
             $stmt->store_result();
             $stmt->bind_result($nome_cargo);
             $stmt->fetch();
             $stmt->close();
 
             $cpf=$funcionario["cpf"];
             $cpf=substr($cpf,0,3).".".substr($cpf,3,3).".".substr($cpf,6,3)."-".substr($cpf,9,2);
 
             echo "
                <a href='profile_funcionario.php?cod=".$funcionario["cod_funcionario"]."' class='link-list mb-2 pb-2 funcionario text-decoration-none align-items-center'>
                   <div>
                      <img src='".$funcionario["foto"]."'>
                   </div>
                   <div class='text-center'>".$funcionario["nome"]." ".$funcionario["sobrenome"]."</div>
                   <div class='text-center'>$cpf</div>
                   <div class='text-center'>$nome_cargo</div>
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
            echo "<div class='alert alert-danger mx-auto text-center' style='width: 20rem'>Funcionário não encontrado</div>";
         else
            echo "<div class='alert alert-danger mx-auto' style='width: 20rem'>Não existem funcionários cadastrados</div>";

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