<?php
   $session_start=session_start();
   
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
   
   if(!isset($_SESSION["loggedin"])){
      header("location: ../login.php");
      exit;
   }

   include("../config.php");

   $sql="SELECT * FROM clientes WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";

   $result=$mysqli->query($sql);
   $cliente=$result->fetch_assoc();

   $titulo="- Perfil: ".$cliente["nome"]." ".$cliente["sobrenome"];

   include("../head-foot/header.php");
?>
   <div class="row container mx-auto profile-cliente">
      <div class="col-12 col-lg-8">
         <a href="favorite.php" class="border-pink px-3 py-2 fs-4 d-flex align-items-center justify-content-between text-decoration-none mb-3">
            Favoritos <i class="bi bi-heart-fill color-pink"></i>
         </a>
         <a href="../pedido/view_pedidos.php" class="border-pink px-3 py-2 fs-4 d-flex align-items-center justify-content-between text-decoration-none mb-3">
            Pedidos <i class="bi bi-box2-fill color-pink"></i>
         </a>
         <a href="comments.php" class="border-pink px-3 py-2 fs-4 d-flex align-items-center justify-content-between text-decoration-none mb-3">
            Comentários <i class="bi bi-chat-dots-fill color-pink"></i>
         </a>
         <div class="border-pink px-3 py-2 mb-5 mb-lg-0">
            <header class="d-flex align-items-center justify-content-between fs-4 mb-3">
               Seu cadastro
               <i class="bi bi-person-fill color-pink"></i>
            </header>
            <div class="d-flex flex-column align-items-start">
               <a href="pay_options.php" class="text-decoration-none mb-4 mb-md-2">Formas de pagamento</a>
               <a href="alter_cliente.php" class="text-decoration-none mb-4 mb-md-2">Alterar dados cadastrais</a>
               <a href="" class="text-decoration-none mb-4 mb-md-2">Alterar senha</a>
            </div>
         </div>
         <button type="button" class="btn background-pink hover text-white w-100 align-items-center justify-content-center py-0 fs-5 d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#logout">
            Sair
            <i class="bi bi-box-arrow-right ms-1 fs-4"></i>
         </button>
      </div>
      <div class="col-12 col-lg d-flex align-items-center flex-column-reverse flex-lg-column mb-4 mb-lg-0">
         <div class="view-foto mb-3">
            <button type="button" id="open-img-full-screen">
               <i class="bi bi-image"></i>
            </button>
            <img src="<?php echo $cliente["foto"]; ?>" alt="<?PHP echo $cliente["nome"]." ".$cliente["sobrenome"]; ?>" class="img-cliente">
         </div>
         <div class="mb-3 w-100 data-cliente">
            <p class="border-bottom-pink mb-2 pb-1"><?php echo $cliente["nome"]." ".$cliente["sobrenome"]; ?></p>
            <?php
               $xml=simplexml_load_file("http://viacep.com.br/ws/".$cliente["cep"]."/xml/");

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

               if(strlen($endereco)>40)
						$endereco=substr($endereco,0,40);
            ?>
            <p class="border-bottom-pink mb-2 pb-1"><?php echo $cliente["email"]; ?></p>
            <p class="border-bottom-pink mb-2 pb-1"><?php echo $endereco; ?></p>
            <p class="border-bottom-pink mb-2 pb-1">
               <?php
                  if(!empty($cliente["cpf"])){
                     $cpf=$cliente["cpf"];
                     $cpf=substr($cpf,0,3).".".substr($cpf,3,3).".".substr($cpf,6,3)."-".substr($cpf,9,2);
                     echo $cpf;
                  }else{
                     $cnpj=$cliente["cnpj"];
                     $cnpj=substr($cnpj,0,2).".".substr($cnpj,2,3).".".substr($cnpj,5,3)."/".substr($cnpj,8,4)."-".substr($cnpj,12,2);
                     echo $cnpj;
                  }
               ?>
            </p>
         </div>
         <button type="button" class="btn background-pink hover text-white w-100 align-items-center justify-content-center py-0 fs-5 d-none d-lg-flex" data-bs-toggle="modal" data-bs-target="#logout">
            Sair
            <i class="bi bi-box-arrow-right ms-1 fs-4"></i>
         </button>
      </div>
   </div>

   <div class="modal fade" id="logout">
      <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
         <div class="modal-content">
            <header class="modal-header pe-4">
               <h4 class="modal-title">Logout</h4>
               <button type="submit" class="btn-close" data-bs-dismiss="modal" name="close"></button>
            </header>
            <div class="modal-body text-center pb-3 pt-4">
               <p class="text-center">Deseja sair?</p>
            </div>
            <footer class="modal-footer">
               <a href="../logout.php" class="col btn btn-primary">Sair</a>
               <button class="col btn background-pink hover text-white" data-bs-dismiss="modal">Cancelar</button>
            </footer>
         </div>
      </div>
   </div>

   <div class="img-full-screen d-none">
      <button type="button" id="close-img-full-screen" class="bg-transparent border-0 p-0">
         <i class="bi bi-x"></i>
      </button>
      <img src="<?php echo $cliente["foto"]; ?>" alt="<?PHP echo $cliente["nome"]." ".$cliente["sobrenome"]; ?>">
   </div>
   <script>
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
<?php
   include("../head-foot/footer.php");
?>