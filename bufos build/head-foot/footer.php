    <!-- CARRINHO DE COMPRAS -->
    <div class="cart-shop d-none">
      <div class="p-3 rounded-3 d-flex flex-column">
         <header class="d-flex align-items-center justify-content-between border-bottom-pink pb-3 mb-3">
            <h5 class="text-dark m-0">Seu carrinho</h5>
            <button type="button" class="bt btn-close" id="btn-close-cart-shop"></button>
         </header>
         <div class="cart-shop-body mb-3 border-bottom-pink">
             <?php
                if(!isset($_SESSION["cod_cliente"])||!isset($_SESSION["quantidade_carrinho"])||$_SESSION["quantidade_carrinho"]==0)
                    echo "<h1 class='color-secondary-light text-center my-5'>Adicione produtos</h1>";
                else{
                    $sql="SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
                    $result=$mysqli->query($sql);
                    $produtos=$result->fetch_assoc();
                    $total;
                    
                    $produtos=$produtos["produtos"];
        			        
			        $items=explode(";",$produtos);
			        
			        $n=0;
			        
			        foreach($items as $item){
			            if($item=="")
			                break;
			                
			            $item=explode("&",$item);
			            $produto=$item[0];
			            $produto=str_replace("p","",$produto);
			            $quantidade=$item[1];
			            $quantidade=str_replace("q","",$quantidade);
			            
			            $sql="SELECT * FROM produtos WHERE cod_produto='$produto'";
			            $result=$mysqli->query($sql);
			            $produto=$result->fetch_assoc();
			            
			            $stmt=$mysqli->prepare("SELECT path FROM fotos_produto WHERE cod_produto=?");
                        $stmt->bind_param("s", $param_cod_produto);
                        $param_cod_produto=$produto["cod_produto"];
                        $stmt->execute();
                        $stmt->store_result();
                        $stmt->bind_result($foto);
                        $stmt->fetch();
                        
                        if(preg_match("$../$",$foto))
                            $foto=str_replace("../","../empresa/",$foto);
                            
                        $titulo=$produto["titulo"];
                        
                        if(strlen($titulo)>50)
                            $titulo=substr($titulo,0,50)."...";
                            
                        $unidades="";
                        if($quantidade!=1)
                            $unidades="s";
			            
			            echo "
			                <div class='d-flex align-items-center justify-content-between mb-3 product-cart' id='product-$n'>
                               <div class='d-flex align-items-center'>
                                  <a href='https://bufosregulares.com/produto.php?cod=".$produto["cod_produto"]."'>
                                    <img src='$foto' alt='".$produto["titulo"]."' class='rounded me-2' width='50'>
                                  </a>
                                  <div class='d-flex flex-column'>
                                     <h6 class='text-dark mb-0'>$titulo</h6>
                                     <div>
                                         <small>$quantidade unidade$unidades <b></b></small>
                                         <small><b>R$".$produto["preco_revenda"]*$quantidade.",99</b></small>
                                     </div>
                                  </div>
                               </div>
                               <button type='button' class='border-0 bg-transparent color-pink me-4 me-sm-0'>
                                  <i class='bi bi-dash-circle fs-3' onmouseover='removeProductOver(this)' onmouseout='removeProductOut(this)'
                                  onclick=\"removeProductCart(document.getElementById('product-$n'),".$produto[preco_revenda]*$quantidade.",".$produto["cod_produto"].")\"></i>
                               </button>
                            </div>
			            ";
			            
			            $total=$total+($produto["preco_revenda"]*$quantidade);
			            $n++;
			        }
			        $stmt->close();
                }
             ?>
         </div>
         <footer class="cart-shop-footer">
            <div class="d-flex justify-content-between align-items-center mb-3">
               <b>Subtotal:</b>
               <span>R$<span id="subtotal"><?php
                        if(isset($total))
                            echo $total;
                        else
                            echo 0;
                    ?></span><?php
                                if(isset($total))
                                    echo ",99";
                                else
                                    echo ",00";
                            ?>
               </span>
            </div>
            <?php
                if(!isset($_SESSION["cod_cliente"])&&!isset($_SESSION["quantidade_carrinho"])){
                    echo "
                        <a href='https://bufosregulares.com/login.php' class='btn background-pink hover text-white col-12 d-flex align-items-center fs-6 justify-content-center py-1'>
                            <i class='bi bi-box-arrow-in-right me-2 fs-4 p-0'></i>
                            Entrar
                        </a>
                    ";
                }elseif(isset($_SESSION["cod_cliente"])&&$_SESSION["quantidade_carrinho"]!=0){
                    echo "
                         <a href='https://bufosregulares.com/pedido/select_endereco.php' class='btn background-pink hover text-white col-12 d-flex align-items-center fs-6 justify-content-center py-1'>
                            <i class='bi bi-arrow-right me-2 fs-4 p-0'></i>
                            Avançar
                         </a>
                    ";
                }
            ?>
         </footer>
      </div>
   </div>
   
   <script>
        var qtdeCart=document.getElementById("qtde-cart");
        qtdeCart.innerHTML="<?php if(isset($_SESSION["quantidade_carrinho"])) echo $_SESSION["quantidade_carrinho"]; else echo 0; ?>";
   
        function removeProductCart(obj,price,cod){
            obj.innerHTML='';
            obj.remove();
            var subtotal=document.getElementById('subtotal');
            var subtotalValue=parseInt(subtotal.innerHTML);
            subtotalValue-=price;
            subtotal.innerHTML=subtotalValue;
            var qtdeCartValue=parseInt(qtdeCart.innerHTML);
            qtdeCartValue-=1;
            qtdeCart.innerHTML=qtdeCartValue;
            const xhttp=new XMLHttpRequest();
            <?php
                if(file_exists("ajax/remove_product_cart.php"))
                    echo "xhttp.open('GET','ajax/remove_product_cart.php?cod='+cod);";
                else
                    echo "xhttp.open('GET','../ajax/remove_product_cart.php?cod='+cod);";
            ?>
            xhttp.send();
        }
   
        function removeProductOver(obj){
            obj.classList.remove("bi-dash-circle");
            obj.classList.add("bi-dash-circle-fill");
        }
        
        function removeProductOut(obj){
            obj.classList.remove("bi-dash-circle-fill");
            obj.classList.add("bi-dash-circle");
        }
        
        const btnOpenCart=document.getElementById("btn-open-cart-shop");
        const btnCloseCart=document.getElementById("btn-close-cart-shop");
        const cartShop=document.querySelector(".cart-shop");
        
        btnOpenCart.addEventListener("click",function(){
            cartShop.classList.toggle("d-none");
        });
        
        btnCloseCart.addEventListener("click",function(){
            cartShop.classList.toggle("d-none");
        });
        
        function checkCardBody(){
            var cartBody=document.querySelector(".cart-shop-body");
            var cartFooter=document.querySelector(".cart-shop-footer");
            if(cartBody.innerHTML.length==59){
                cartBody.innerHTML="<h1 class='color-secondary-light text-center my-5'>Adicione produtos</h1>";
                cartFooter.innerHTML="<div class='d-flex justify-content-between align-items-center mb-3'><b>Subtotal:</b><span>R$0,00</span></div>";
            }
            requestAnimationFrame(checkCardBody);
        }
        checkCardBody();
   </script>
   
   <!-- RODAPÉ DO SITE -->
   <footer class="container-fluid footer-site py-5">
      <div class="container">
         <div class="row footer-top justify-content-between align-items-start mb-4">
            <div class="col-12 col-md-3 col-lg-4 d-flex justify-content-center align-items-center">
               <div class="d-flex flex-column align-items-center px-lg-5">
                  <i class="bi bi-telephone-fill mb-4"></i>
                  <span>(11) 99430-4367</span>
               </div>
            </div>
            <div class="col-12 col-md-5 col-lg-4 d-flex justify-content-center align-items-center card-center">
               <div class="d-flex flex-column align-items-center px-lg-5">
                  <i class="bi bi-at mb-4"></i>
                  <span>suporte@bufosregulares.com</span>
               </div>
            </div>
            <div class="col-12 col-md-3 col-lg-4 d-flex justify-content-center align-items-center">
               <div class="d-flex flex-column align-items-center px-lg-5">
                  <i class="bi bi-geo-alt-fill mb-4"></i>
                  <span class="text-center">R. Virgínia Ferni, 400 - Itaquera, São Paulo - SP, 08253-000</span>
               </div>
            </div>
         </div>
         <div class="row align-items-start footer-medium pb-4">
            <div class="col-12 d-flex justify-content-center align-items-center flex-column mb-5">
               <h4>Formas de pagamento</h4>
               <?php
                  $return_path="";

                  if(!file_exists("img/pay-options/visa.png"))
                     $return_path="../";
               ?>
               <div class="d-flex align-items-center pay-options justify-content-center flex-wrap">
                  <img src="<?php echo $return_path; ?>img/pay-options/visa.png" alt="Visa">
                  <span class="iconify" data-icon="logos:elo" style="font-size: 3rem;"></span>
                  <img src="<?php echo $return_path; ?>img/pay-options/mastercard.png" alt="Boleto">
                  <img src="<?php echo $return_path; ?>img/pay-options/amex.png" alt="amex">
                  <img src="<?php echo $return_path; ?>img/pay-options/boleto-light.png" alt="Boleto">
                  <img src="<?php echo $return_path; ?>img/pay-options/boleto-dark.png" alt="Boleto">
               </div>
            </div>
            <div class="col-12 col-md d-flex flex-column mb-5 mb-md-0">
               <h4 class="mb-4">Sobre</h4>
               <ul class="nav flex-column">
                  <li class="nav-item">
                     <a href="<?php if(!file_exists("empresa.php")) echo "../"; ?>empresa.php#historia" class="nav-link">Nossa história</a>
                  </li>
                  <li class="nav-item">
                     <a href="" class="nav-link">Premios</a>
                  </li>
                  <li class="nav-item">
                     <a href="<?php if(!file_exists("empresa.php")) echo "../"; ?>empresa.php#creators" class="nav-link">Nossa Equipe</a>
                  </li>
               </ul>
            </div>
            <div class="col-12 col-md d-flex flex-column mb-5 mb-md-0">
               <h4 class="mb-4">Políticas</h4>
               <ul class="nav flex-column">
                  <li class="nav-item">
                     <a href="<?php if(!file_exists("empresa.php")) echo "../"; ?>empresa.php" class="nav-link">Corporativo</a>
                  </li>
                  <li class="nav-item">
                     <a href="" class="nav-link">Termos de serviços</a>
                  </li>
                  <li class="nav-item">
                     <a href="" class="nav-link">Política de privacidade</a>
                  </li>
               </ul>
            </div>
            <div class="col-12 col-md d-flex flex-column mb-5 mb-md-0">
               <h4 class="mb-4">Compania</h4>
               <ul class="nav flex-column">
                  <li class="nav-item">
                     <a href="" class="nav-link">Nossos serviços</a>
                  </li>
                  <li class="nav-item">
                     <a href="" class="nav-link">Clientes</a>
                  </li>
                  <li class="nav-item">
                     <a href="" class="nav-link">Contato</a>
                  </li>
               </ul>
            </div>
            <div class="col-xl-5 d-flex flex-column subscribe mt-md-4 mt-xl-0">
               <h4 class="mb-4">Inscreva-se</h4>
               <form action="" method="post" id="form-subscribe">
                  <div class="input-group mb-2">
                     <input type="email" class="form-control" placeholder="coloque seu email" name="email_news">
                     <button type="submit" class="btn background-pink border-0" name="submit_email_news">
                        <i class="bi bi-send text-white"></i>
                     </button>
                  </div>
               </form>
               <span class="color-secondary-light-dark d-flex align-items-center">Receba novidades em sua caixa de email
                    <?php
                        if(isset($_POST["submit_email_news"])&&!empty($_POST["email_news"])){
                            if(file_exists("phpmailer/send_email.php"))
                                include("phpmailer/send_email.php");
                            else
                                include("../phpmailer/send_email.php");
                                
                            if(sendEmailContato($_POST["email_news"],""))
                                echo "<span class='color-pink d-flex align-items-center'><i class='bi bi-check fs-5'></i> Enviado</span>";
                            else
                                echo "<span class='color-pink d-flex align-items-center'><i class='bi bi-x fs-5'></i> Não enviado</span>";
                                
                            echo "
                                <script>
                	                if(window.location.href.search('#form-subscribe')==-1)
                	                    window.location.assign(location+'#form-subscribe');
                	            </script>
                            ";
                        }
                    ?></span>
            </div>
         </div>
         <div class="footer-bottom d-flex justify-content-between align-items-center py-4">
            <p class="text-center">Bufos Regulares ©<?php echo date("Y"); ?> todos direitos reservados</p>
            <div class="social d-flex">
               <a href="https://www.facebook.com/profile.php?id=100075004281849" target="_blank">
                  <i class="bi bi-facebook"></i>
               </a>
               <a href="https://www.instagram.com/bufos_regulares/" target="_blank">
                  <i class="bi bi-instagram"></i>
               </a>
               <a href="https://twitter.com/BufosRegulares2" target="_blank">
                  <i class="bi bi-twitter"></i>
               </a>
               <a href="https://www.youtube.com/channel/UCJIJZ-ouLaacVihH3uMRCdw" target="_blank">
                  <i class="bi bi-youtube"></i>
               </a>
            </div>
         </div>
      </div>
   </footer>
   <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
   <script src="<?php if(!file_exists("scripts/main.js")) echo "../"; ?>scripts/main.js?10"></script>
</body>
</html>
<?php $mysqli->close() ?>