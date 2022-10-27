<?php
    if(!isset($_GET["tipo"])||!isset($_GET["nivel"]))
        die;
        
    $titulo="- Resultado";
    include("../head-foot/header.php");
?>
    <h2 class="text-center white-dark mb-5">Te recomendamos estes produtos</h2>
    
    <div class="d-flex flex-column">
        <div class='featured-products d-flex flex-column container mb-5'>
            <div class='container-every column'>
            <?php
                if($_GET["tipo"]==0)
                    $tipo="desktop";
                else
                    $tipo="notebook";
                    
                if($_GET["nivel"]==0)
                    $nivel="i3-1";
                elseif($_GET["nivel"]==1)
                    $nivel="i5";
                elseif($_GET["nivel"]==2&&$_GET["tipo"]==0)
                    $nivel="i7 10700F";
                elseif($_GET["nivel"]==2&&$_GET["tipo"]==1)
                    $nivel="i7 1165G7";
                    
                $sql="SELECT * FROM produtos WHERE tipo='$tipo' AND titulo LIKE '%$nivel%' OR tipo='$tipo' AND descricao LIKE '%$nivel%'";
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
                 
                if($_GET["nivel"]==0&&$_GET["tipo"]==0)
                    $nivel="ryzen 3";
                elseif($_GET["nivel"]==0&&$_GET["tipo"]==1)
                    $nivel="atom";
                elseif($_GET["nivel"]==1)
                    $nivel="ryzen 5";
                elseif($_GET["nivel"]==2&&$_GET["tipo"]==0)
                    $nivel="5 3600";
                elseif($_GET["nivel"]==2&&$_GET["tipo"]==1)
                    $nivel="Modelo do Processador -1165G7";
                    
                $sql="SELECT * FROM produtos WHERE tipo='$tipo' AND titulo LIKE '%$nivel%' OR tipo='$tipo' AND descricao LIKE '%$nivel%'";
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
            ?>    
            </div>
        </div>
    </div>
<?php
    include("../head-foot/footer.php");
?>