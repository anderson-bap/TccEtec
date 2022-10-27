<?php
   $titulo=" Regulares";
   include ("head-foot/header.php");
   
   unset($_SESSION["last_product"]);
?>

<!-- OPÇÕES -->
   <div class="options d-flex mb-4 container">
      <a href="search.php?search=computadores" class="flex-grow-1 text-center">
         <div>
            <i class="bi bi-pc-display"></i> Computadores
         </div>
      </a>
      <a href="guiada/tipo.php" class="flex-grow-1 text-center">
         <div>
            <i class="bi bi-map"></i> Escolha seu pc
         </div>
      </a>
      <a href="search.php?search=gamer" class="flex-grow-1 text-center">
         <div>
            <i class="bi bi-controller"></i> Gamer
         </div>
      </a>
      <a href="empresa.php" class="flex-grow-1 text-center">
         <div>
            <i class="bi bi-people-fill"></i> Corporativo
         </div>
      </a>
   </div>

   <!-- SLIDE DE FOTOS -->
   <div class="container mb-3">
      <div class="carousel inicial slide" data-bs-ride="carousel" id="apresentation">
         <div class="carousel-indicators">
           <button type="button" data-bs-target="#apresentation" data-bs-slide-to="0" class="active"></button>
           <button type="button" data-bs-target="#apresentation" data-bs-slide-to="1"></button>
           <button type="button" data-bs-target="#apresentation" data-bs-slide-to="2"></button>
           <button type="button" data-bs-target="#apresentation" data-bs-slide-to="3"></button>
         </div>
     
         <div class="carousel-inner">
           <div class="carousel-item active">
             <a href="">
                <img src="img/slider/1/1.png" alt="slide-1">
                <div class="carousel-caption mb-5">
                   <h1>Novidade! Sistema de montagem de PC guiada</h1>
                </div>
             </a>
           </div>
           <div class="carousel-item">
             <a href="">
                <img src="img/slider/1/2.png" alt="slide-2">
                <div class="carousel-caption mb-5">
                   <h1>Montagem grátis efetuada por especialistas</h1>
                </div>
             </a>
           </div>
           <div class="carousel-item">
             <a href="">
                <img src="img/slider/1/3.png" alt="slide-3">
                <div class="carousel-caption mb-5">
                   <h1>Empresa com credibilidade no mercado</h1>
                </div>
             </a>
           </div>
           <div class="carousel-item">
             <a href="">
                <img src="img/slider/1/4.png" alt="slide-4">
                <div class="carousel-caption mb-5">
                   <h1>Nota 1000 no reclame aqui</h1>
                </div>
             </a>
           </div>
         </div>
       </div>
   </div>

   <!-- BENEFÍCIOS -->
   <div class="container-fluid features py-4 mb-5">
      <div class="container">
         <div class="d-flex align-items-center">
            <i class="bi bi-upc"></i>
            <div class="bar"></div>
            <div>
               <h5>18% de desconto</h5>
               <span>à vista</span>
            </div>
         </div>
         <div class="d-flex align-items-center">
            <i class="bi bi-wrench-adjustable-circle"></i>
            <div class="bar"></div>
            <div>
               <h5>Montagem grátis</h5>
               <span>feita em até 48h</span>
            </div>
         </div>
         <div class="d-flex align-items-center">
            <i class="bi bi-credit-card"></i>
            <div class="bar"></div>
            <div>
               <h5>10X sem juros</h5>
               <span>no cartão de crédito</span>
            </div>
         </div>
         <div class="d-flex align-items-center">
            <i class="bi bi-building"></i>
            <div class="bar"></div>
            <div>
               <h5>Atendimento para PJ</h5>
               <span>cadastre-se com o cnpj</span>
            </div>
         </div>
      </div>
   </div>

   <!-- TIPOS DE MONTAGENS -->
   <div class="container mb-5 d-flex justify-content-around build-options">
      <a href="" class="text-decoration-none">
         <img src="img/tecnica.png" alt="Montagem técnica">
         <div>
            <h4 class="white-dark">Montagem técnica</h4>
            <span>Voce escolhe as peças</span>
         </div>
      </a>
      <a href="guiada/tipo.php" class="text-decoration-none">
         <img src="img/guiada.png" alt="Montagem guiada">
         <div>
            <h4 class="white-dark">Montagem guiada</h4>
            <span>O sistema te ajuda</span>
         </div>
      </a>
   </div>

   <!-- PRODUTOS PRINCIPAIS -->
   <div class="featured-products d-flex flex-column container mb-5">
      <!-- SELECIONAR PRODUTOS PRINCIPAIS -->
      <ul class="nav nav-justified mb-5">
         <li class="nav-item">
            <a href="#news" class="nav-link" data-bs-toggle="tab">
               <div>Novidades</div>
            </a>
         </li>
         <li class="nav-item">
            <a href="#best-sellers" class="nav-link active" data-bs-toggle="tab">
               <div>Sucessos</div>
            </a>
         </li>
         <li class="nav-item">
            <a href="#gamer" class="nav-link" data-bs-toggle="tab">
               <div>Gamer</div>
            </a>
         </li>
      </ul>
      <div class="tab-content">
         <!-- NOVIDADES -->
         <div class="tab-pane fade" id="news">
            <div class="container-every">
            <?php
                  $sql="SELECT * FROM `produtos` ORDER BY `produtos`.`cod_produto` DESC";
                  $result=$mysqli->query($sql);

                  for($i=0;$i<4;$i++){
                     $produto=$result->fetch_assoc();

                     $stmt=$mysqli->prepare("SELECT path FROM fotos_produto WHERE cod_produto=?");
                     $stmt->bind_param("s", $param_cod_produto);
                     $param_cod_produto=$produto["cod_produto"];
                     $stmt->execute();
                     $stmt->store_result();
                     $stmt->bind_result($foto);
                     $stmt->fetch();

                     if(preg_match("$../$",$foto))
                        $foto=str_replace("../","empresa/",$foto);

                     $installment_price=$produto["preco_revenda"];
                     $juros=(18*$installment_price)/100;
                     $installment_price+=$juros;
                     $installment_price=intval($installment_price);

                     $price_divided=$installment_price/12;
                     $price_divided=intval($price_divided);

                     $titulo=$produto["titulo"];
                     if(preg_match("/Ã£/",$titulo))
                        $titulo=str_replace("Ã£","ã",$titulo);
                        
                     if(isset($_SESSION["cod_cliente"]))
                        $cod_cliente=$_SESSION["cod_cliente"];
                    else
                        $cod_cliente=-1;
                    
                    $checked="";
                        
                    $result_favorite=$mysqli->query("SELECT * FROM favoritos WHERE cod_produto='".$produto["cod_produto"]."' AND cod_cliente='".$_SESSION["cod_cliente"]."'");
                    if($result_favorite->num_rows==1)
                        $checked="checked";

                     echo "
                        <div class='container-card'>
                        <button type='button' class='btn-favorite bg-transparent border-0'>
                           <i class='bi bi-heart heart' $checked onmouseover='favoriteOver(this)' onmouseout='favoriteOut(this)' onclick='favorite(this,".$produto["cod_produto"].",$cod_cliente)'></i>
                        </button>
                        <a href='produto.php?cod=".$produto["cod_produto"]."' class='text-decoration-none mx-auto'>
                           <div class='card'>
                              <header class='card-header'>
                                 <img src='$foto' alt='$titulo'>
                              </header>
                              <div class='card-body'>
                                 <p class='text-center name-product mb-2'>$titulo</p>
                                 <div class='d-flex flex-column pay-options'>
               
                                    <div class='d-flex align-items-center'>
                                       <i class='bi bi-upc'></i>
                                       <span>
                                          <span class='h5 price'>".$produto["preco_revenda"]."</span>
                                          <small>à vista</small>
                                       </span>
                                    </div>
               
                                    <div class='d-flex align-items-center'>
                                       <i class='bi bi-credit-card'></i>
                                       <span>
                                          <span class='h5 price'>$installment_price</span>
                                          <small>
                                             em até 
                                             <strong>12x</strong>
                                             de 
                                             <strong class='price'>$price_divided</strong>
                                          </small>
                                       </span>
                                    </div>
               
                                 </div>
                              </div>
                           </div>
                        </a>
                     </div>
                     ";
                  }
               ?>
            </div>
         </div>
         <!-- MAIS VENDIDOS -->
         <div class="tab-pane active" id="best-sellers">
            <div class="container-every">
               <?php
                  $sql="SELECT * FROM produtos";
                  $result=$mysqli->query($sql);

                  for($i=0;$i<4;$i++){
                     $produto=$result->fetch_assoc();

                     $stmt=$mysqli->prepare("SELECT path FROM fotos_produto WHERE cod_produto=?");
                     $stmt->bind_param("s", $param_cod_produto);
                     $param_cod_produto=$produto["cod_produto"];
                     $stmt->execute();
                     $stmt->store_result();
                     $stmt->bind_result($foto);
                     $stmt->fetch();

                     if(preg_match("$../$",$foto))
                        $foto=str_replace("../","empresa/",$foto);

                     $installment_price=$produto["preco_revenda"];
                     $juros=(18*$installment_price)/100;
                     $installment_price+=$juros;
                     $installment_price=intval($installment_price);

                     $price_divided=$installment_price/12;
                     $price_divided=intval($price_divided);

                     $titulo=$produto["titulo"];
                     if(preg_match("/Ã£/",$titulo))
                        $titulo=str_replace("Ã£","ã",$titulo);
                        
                     if(isset($_SESSION["cod_cliente"]))
                        $cod_cliente=$_SESSION["cod_cliente"];
                    else
                        $cod_cliente=-1;
                    
                    $checked="";
                        
                    $result_favorite=$mysqli->query("SELECT * FROM favoritos WHERE cod_produto='".$produto["cod_produto"]."' AND cod_cliente='".$_SESSION["cod_cliente"]."'");
                    if($result_favorite->num_rows==1)
                        $checked="checked";

                     echo "
                        <div class='container-card'>
                        <button type='button' class='btn-favorite bg-transparent border-0'>
                           <i class='bi bi-heart heart' $checked onmouseover='favoriteOver(this)' onmouseout='favoriteOut(this)' onclick='favorite(this,".$produto["cod_produto"].",$cod_cliente)'></i>
                        </button>
                        <a href='produto.php?cod=".$produto["cod_produto"]."' class='text-decoration-none mx-auto'>
                           <div class='card'>
                              <header class='card-header'>
                                 <img src='$foto' alt='$titulo'>
                              </header>
                              <div class='card-body'>
                                 <p class='text-center name-product mb-2'>$titulo</p>
                                 <div class='d-flex flex-column pay-options'>
               
                                    <div class='d-flex align-items-center'>
                                       <i class='bi bi-upc'></i>
                                       <span>
                                          <span class='h5 price'>".$produto["preco_revenda"]."</span>
                                          <small>à vista</small>
                                       </span>
                                    </div>
               
                                    <div class='d-flex align-items-center'>
                                       <i class='bi bi-credit-card'></i>
                                       <span>
                                          <span class='h5 price'>$installment_price</span>
                                          <small>
                                             em até 
                                             <strong>12x</strong>
                                             de 
                                             <strong class='price'>$price_divided</strong>
                                          </small>
                                       </span>
                                    </div>
               
                                 </div>
                              </div>
                           </div>
                        </a>
                     </div>
                     ";
                  }
               ?>
            </div>
         </div>
         <!-- gamer -->
         <div class="tab-pane fade" id="gamer">
            <div class="container-every">
               <?php
                    $sql="SELECT * FROM produtos WHERE tipo='notebook' AND titulo LIKE '%gamer%' OR tipo='notebook' AND descricao LIKE '%gamer%' OR tipo='desktop' AND titulo LIKE '%gamer%' OR tipo='desktop' AND descricao LIKE '%gamer%'";
            	    $result=$mysqli->query($sql);
            	
                    $n_rand=rand(4,$result->num_rows);
                   
                   for($i=0;$i<$n_rand;$i++){
                     $produto=$result->fetch_assoc();
                     
                     if($i>=$n_rand-4){
                         $stmt=$mysqli->prepare("SELECT path FROM fotos_produto WHERE cod_produto=?");
                         $stmt->bind_param("s", $param_cod_produto);
                         $param_cod_produto=$produto["cod_produto"];
                         $stmt->execute();
                         $stmt->store_result();
                         $stmt->bind_result($foto);
                         $stmt->fetch();
                
                         if(preg_match("$../$",$foto))
                            $foto=str_replace("../","empresa/",$foto);
                
                         $installment_price=$produto["preco_revenda"];
                         $juros=(18*$installment_price)/100;
                         $installment_price+=$juros;
                         $installment_price=intval($installment_price);
                
                         $price_divided=$installment_price/12;
                         $price_divided=intval($price_divided);
                
                         $titulo=$produto["titulo"];
                         if(preg_match("/Ã£/",$titulo))
                            $titulo=str_replace("Ã£","ã",$titulo);
                
                         if(isset($_SESSION["cod_cliente"]))
                            $cod_cliente=$_SESSION["cod_cliente"];
                        else
                            $cod_cliente=-1;
                        
                        $checked="";
                            
                        $result_favorite=$mysqli->query("SELECT * FROM favoritos WHERE cod_produto='".$produto["cod_produto"]."' AND cod_cliente='".$_SESSION["cod_cliente"]."'");
                        if($result_favorite->num_rows==1)
                            $checked="checked";
                
                         echo "
                            <div class='container-card'>
                                <button type='button' class='btn-favorite bg-transparent border-0'>
                                   <i class='bi bi-heart heart' $checked onmouseover='favoriteOver(this)' onmouseout='favoriteOut(this)' onclick='favorite(this,".$produto["cod_produto"].",$cod_cliente)'></i>
                                </button>
                                <a href='produto.php?cod=".$produto["cod_produto"]."' class='text-decoration-none mx-auto'>
                                   <div class='card'>
                                      <header class='card-header'>
                                         <img src='$foto' alt='$titulo'>
                                      </header>
                                      <div class='card-body'>
                                         <p class='text-center name-product mb-2'>$titulo</p>
                                         <div class='d-flex flex-column pay-options'>
                       
                                            <div class='d-flex align-items-center'>
                                               <i class='bi bi-upc'></i>
                                               <span>
                                                  <span class='h5 price'>".$produto["preco_revenda"]."</span>
                                                  <small>à vista</small>
                                               </span>
                                            </div>
                       
                                            <div class='d-flex align-items-center'>
                                               <i class='bi bi-credit-card'></i>
                                               <span>
                                                  <span class='h5 price'>$installment_price</span>
                                                  <small>
                                                     em até 
                                                     <strong>12x</strong>
                                                     de 
                                                     <strong class='price'>$price_divided</strong>
                                                  </small>
                                               </span>
                                            </div>
                       
                                         </div>
                                      </div>
                                   </div>
                                </a>
                             </div>
                         ";
                        }
                    }
               ?>
            </div>
         </div>
      </div>
   </div>

   <!-- COMENTÁRIOS -->
   <div class="container d-flex flex-column align-items-center comments">
      <h2 class="mb-4">Comentários</h2>
      <div class="align-self-start comment mb-4">
         <div class="d-flex align-items-center mb-2">
            <img src="img/persons/person-1.jpg" alt="alessandra">
            <div class="d-flex flex-column">
               <h5>Alessandra Pizaninni</h5>
               <div class="d-flex align-items-center">
                  <div>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                  </div>
               </div>
            </div>
         </div>
         <blockquote class="mx-0 mx-md-5">
            Dentre as várias empresas que pesquisei, essa foi a que mais me prestou serviço. Qualidade impecável, ouve as vontades do consumidor e sempre disposta a solucionar as dúvidas. O sucesso perdurará por muito tempo
         </blockquote>
      </div>
      <div class="align-self-start comment mb-4">
         <div class="d-flex align-items-center mb-2">
            <img src="img/persons/person-2.jpg" alt="alessandra">
            <div class="d-flex flex-column">
               <h5>Leandro Nogueira</h5>
               <div class="d-flex align-items-center">
                  <div>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                  </div>
               </div>
            </div>
         </div>
         <blockquote class="mx-0 mx-md-5">
            Sou cliente da Bufos a anos e sempre estou satisfeito com a qualidade do serviço, do atendimento e dos produtos oferecidos, tive a oportunidade de conhecer a equipe desenvolvedora e posso dizer com total segurança que são ótimos profissionais
         </blockquote>
      </div>
      <div class="align-self-start comment mb-4">
         <div class="d-flex align-items-center mb-2">
            <img src="img/persons/person-3.jpg" alt="alessandra">
            <div class="d-flex flex-column">
               <h5>Felipe Rodrigues</h5>
               <div class="d-flex align-items-center">
                  <div>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                     <i class="bi bi-star-fill"></i>
                  </div>
               </div>
            </div>
         </div>
         <blockquote class="mx-0 mx-md-5">
            Já havia pesquisado em diversos sites atrás de um notebook pra estudar mas todos não me auxiliavam e quando tentei pesquisar sobre me perdi ainda mais, até que conheci a Bufos e seu sistema de montagem guiada super didático, desde então sempre recomendo a amigos e familiares pois além desse auxílio, os produtos tem preços justos e ótima qualidade
         </blockquote>
      </div>
   </div>

<?php
   include ("head-foot/footer.php");
?>