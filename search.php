<?php
   if(!isset($_GET["search"]))
      die;

   if(empty($_GET["search"]))
      $titulo="- Pesquise algo";
   else{
      $titulo="- Pesquisa: ".$_GET["search"];
      $search=trim($_GET["search"]);
      $search=strtolower($search);
   }

   include ("head-foot/header.php");

   if(!empty($_GET["search"])){
        if($search=="cpu")
            $sql="SELECT * FROM produtos WHERE tipo='cpu'";
            
        elseif($search=="gpu"||$search=="placa de vídeo"||$search=="placa de video")
            $sql="SELECT * FROM produtos WHERE tipo='gpu'";
            
        elseif($search=="motherboard"||$search=="placa mãe"||$search=="placa mae"||$search=="mother")
            $sql="SELECT * FROM produtos WHERE tipo='placa mãe'";
            
        elseif($search=="ssd"||$search=="solid"||$search=="solid state drive")
            $sql="SELECT * FROM produtos WHERE tipo='ssd'";
            
        elseif($search=="hd"||$search=="hdd"||$search=="disco rígido"||$search=="hard drive"||$search=="hard disk")
            $sql="SELECT * FROM produtos WHERE tipo='hd'";
            
        elseif($search=="teclado"||$search=="keyboard")
            $sql="SELECT * FROM produtos WHERE tipo='teclado'";
            
        elseif($search=="mouse"||$search=="rato")
            $sql="SELECT * FROM produtos WHERE tipo='mouse'";
            
        elseif($search=="fonte")
            $sql="SELECT * FROM produtos WHERE tipo='fonte'";
            
        elseif($search=="ram"||$search=="memória"||$search=="memória ram")
            $sql="SELECT * FROM produtos WHERE tipo='memória ram'";
            
        elseif($search=="computadores"||$search=="computador"||$search=="pc")
            $sql="SELECT * FROM produtos WHERE tipo='notebook' OR tipo='desktop'";
            
        elseif($search=="teclado gamer")
            $sql="SELECT * FROM produtos WHERE tipo='teclado' AND titulo LIKE '%teclado gamer%' OR tipo='teclado' AND descricao LIKE '%teclado gamer%'";
            
        elseif($search=="mouse gamer")
            $sql="SELECT * FROM produtos WHERE tipo='mouse' AND titulo LIKE '%mouse gamer%' OR tipo='mouse' AND descricao LIKE '%mouse gamer%'";
            
        elseif($search=="mouse wireless"||$search=="mouse wire"||$search=="mouse bluetooth")
            $sql="SELECT * FROM produtos WHERE tipo='mouse' AND titulo LIKE '%mouse sem fio%' OR tipo='mouse' AND descricao LIKE '%mouse sem fio%'";
            
        elseif($search=="desktop")
            $sql="SELECT * FROM produtos WHERE tipo='desktop'";
            
        else
            $sql="SELECT * FROM produtos WHERE titulo LIKE '%$search%' OR descricao LIKE '%$search%'";
   }else
      $sql="SELECT * FROM `produtos` ORDER BY `produtos`.`cod_produto` DESC";
   
   $result=$mysqli->query($sql);
   if($result->num_rows>0):
?>
<p class="container mb-5 text-center result-search">Mostrar resultados para: <?php echo $_GET["search"]; ?></p>
<div class="d-flex flex-column">
    <div class='featured-products d-flex flex-column container mb-5'>
        <div class='container-every column'>
<?php
    if(!empty($_GET["search"])){
      for($i=0;$i<$result->num_rows;$i++){
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
    }else{
        for($i=0;$i<28;$i++){
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
    }
?>
        </div>
    </div>
</div>
<?php
   endif;
   
   if($result->num_rows==0){
       echo "
        <p class='container mb-5 text-center result-search'>Não foram encontrados resultados para: ".$_GET["search"]."</p>
        <div class='d-flex flex-column'>
            <div class='featured-products d-flex flex-column container mb-5'>
                <div class='container-every column'>
       ";
       
        $sql="SELECT * FROM produtos";
	    $result=$mysqli->query($sql);
	
        $n_rand=rand(20,$result->num_rows);
       
       for($i=0;$i<$n_rand;$i++){
         $produto=$result->fetch_assoc();
         
         if($i>=$n_rand-20){
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
      echo "
            <div>
        </div>
    </div>
      ";
   }
   
   $stmt->close();

   include ("head-foot/footer.php");
?>