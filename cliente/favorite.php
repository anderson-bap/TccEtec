<?php
    $titulo="- Favoritos";
    include("../head-foot/header.php");
    $sql="SELECT * FROM favoritos WHERE cod_cliente='".$_SESSION["cod_cliente"]."' ORDER BY `favoritos`.`cod_favorito` DESC";
    $result=$mysqli->query($sql);
    
    if(!isset($_SESSION["cod_cliente"])){
        echo "
            <script>
                window.location.assign('https://bufosregulares.com/login.php');
            </script>
        ";
    }
?>
    <h2 class="text-center mb-5 white-dark">Produtos salvos</h2>
    <div class="d-flex flex-column">
        <div class='featured-products d-flex flex-column container mb-5'>
            <p class="white-dark text-center" style="margin-block: 6rem">Você ainda não tem produtos salvos</p>
            <div class='container-every column' <?php if($result->num_rows==0||$result->num_rows==1) echo "style='grid-template-columns: auto'"; ?>>
<?php
    if($result->num_rows>0){
        for($i=0;$i<$result->num_rows;$i++){
             $favorite=$result->fetch_assoc();
    
            $result_produto=$mysqli->query("SELECT * FROM produtos WHERE cod_produto='".$favorite["cod_produto"]."'");
            
            if($produto=$result_produto->fetch_assoc()){
                
                 $stmt=$mysqli->prepare("SELECT path FROM fotos_produto WHERE cod_produto=?");
                 $stmt->bind_param("s", $param_cod_produto);
                 $param_cod_produto=$produto["cod_produto"];
                 $stmt->execute();
                 $stmt->store_result();
                 $stmt->bind_result($foto);
                 $stmt->fetch();
        
                 if(preg_match("$../$",$foto))
                    $foto=str_replace("../","../empresa/",$foto);
        
                 $installment_price=$produto["preco_revenda"];
                 $juros=(18*$installment_price)/100;
                 $installment_price+=$juros;
                 $installment_price=intval($installment_price);
        
                 $price_divided=$installment_price/12;
                 $price_divided=intval($price_divided);
        
                 $titulo=$produto["titulo"];
                 if(preg_match("/Ã£/",$titulo))
                    $titulo=str_replace("Ã£","ã",$titulo);
                
                
                 echo "
                    <div class='container-card' data-cod='".$produto["cod_produto"]."'>
                    <button type='button' class='btn-favorite bg-transparent border-0'>
                       <i class='bi bi-heart heart' checked onmouseover='favoriteOver(this)' onmouseout='favoriteOut(this)'
                       onclick=\"
                            var card=document.querySelectorAll('.container-card');
                            
                            for(var i=0;i<card.length;i++){
                                if(card[i].getAttribute('data-cod')==".$produto["cod_produto"]."){
                                    card[i].innerHTML='';
                                    removeFavoriteCard(".$_SESSION["cod_cliente"].",".$produto["cod_produto"].");
                                    card[i].remove();
                                }
                            }
                       \"></i>
                    </button>
                    <a href='../produto.php?cod=".$produto["cod_produto"]."' class='text-decoration-none mx-auto'>
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
    }
?>
        </div>
    </div>
</div>
<script>
    const containerEvery=document.querySelector(".container-every");
    const p=document.querySelector("p.white-dark");
    
    function checkEmptyFavorite(){
        if(containerEvery.innerHTML.length!=9){
            p.innerHTML="";
            p.style.marginBlock="0";
        }
        if(containerEvery.innerHTML.length==9||containerEvery.innerHTML.length==48){
            p.innerHTML="Você ainda não tem produtos salvos";
            p.style.marginBlock="6rem";
        }
        requestAnimationFrame(checkEmptyFavorite);
    }
    checkEmptyFavorite();
</script>
<?php
    include("../head-foot/footer.php");
?>