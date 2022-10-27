<?php
	if(!isset($_GET["cod"])){
		die;
	}else{
		include("config.php");

		$sql="SELECT * FROM produtos WHERE cod_produto=".$_GET["cod"];
		$result=$mysqli->query($sql);

		if($result->num_rows==1){
			$produto=$result->fetch_assoc();

			$titulo="- ".$produto["titulo"];
			$not_destroy_last_product=true;
			include ("head-foot/header.php");
		}else
			die;
	}
	
	unset($_SESSION["last_product"]);
?>

	<link rel="stylesheet" href="CSS/easyzoom.css?4" />

<?php
	$sql="SELECT * FROM fotos_produto WHERE cod_produto=".$_GET["cod"];
	$result=$mysqli->query($sql);
	$fotos=array();
	while($path=$result->fetch_assoc()){
		$foto=$path["path"];
		$foto=str_replace("../","empresa/",$foto);
		array_push($fotos,$foto);
	}

	$installment_price=$produto["preco_revenda"];
	$juros=(18*$installment_price)/100;
	$installment_price=intval($produto["preco_revenda"]+$juros);
	
?>

	<div class="d-flex flex-column flex-lg-row gap-4 mb-5 container mx-auto">
		<div class="align-self-md-center align-self-lg-stretch">
			<div class="col d-none d-md-flex">
				<div class="order-2">
					<div class="easyzoom easyzoom--overlay easyzoom--with-thumbnails d-none d-md-inline-block">
						<a href="<?php echo $fotos[0]; ?>">
							<img src="<?php echo $fotos[0]; ?>" alt="<?php echo $produto["titulo"]; ?>" id="photo-product-lg">
						</a>
					</div>
				</div>
				<div class="thumbnails d-none d-md-inline order-1 me-2">
					<?php
						foreach($fotos as $foto){
							echo "
								<div>
									<a href='$foto' data-standard='$foto'>
										<img src='$foto' alt='".$produto["titulo"]."' class='photo-product-sm mb-1'>
									</a>
								</div>
							";
						}
					?>
				</div>
			</div>
			<div class="carousel slide carousel-dark produto d-md-none" data-bs-ride="carousel" id="produto">
				<div class="carousel-indicators">
					<button type="button" data-bs-target="#produto" data-bs-slide-to="0" class="active"></button>
					<button type="button" data-bs-target="#produto" data-bs-slide-to="1"></button>
					<button type="button" data-bs-target="#produto" data-bs-slide-to="2"></button>
					<button type="button" data-bs-target="#produto" data-bs-slide-to="3"></button>
					<button type="button" data-bs-target="#produto" data-bs-slide-to="4"></button>
					<button type="button" data-bs-target="#produto" data-bs-slide-to="5"></button>
				</div>
			
				<div class="carousel-inner rounded">
					<div class="carousel-item active">
						<img src="<?php echo $fotos[0]; ?>" alt="<?php echo $produto["titulo"]; ?>">
					</div>
					<div class="carousel-item">
						<img src="<?php echo $fotos[1]; ?>" alt="<?php echo $produto["titulo"]; ?>">
					</div>
					<div class="carousel-item">
						<img src="<?php echo $fotos[2]; ?>" alt="<?php echo $produto["titulo"]; ?>">
					</div>
					<div class="carousel-item">
						<img src="<?php echo $fotos[3]; ?>" alt="<?php echo $produto["titulo"]; ?>">
					</div>
					<div class="carousel-item">
						<img src="<?php echo $fotos[4]; ?>" alt="<?php echo $produto["titulo"]; ?>">
					</div>
					<div class="carousel-item">
						<img src="<?php echo $fotos[5]; ?>" alt="<?php echo $produto["titulo"]; ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="col data-produto position-relative">
		    <?php
		        if(intval($produto["quantidade"]==0)){
		            echo "
		                <div class='produto-indisponivel position-absolute w-100 h-100'>
            		        <h2 class='text-white text-center mt-4'>Produto indisponível</h2>
            		    </div>
		            ";
		        }
		    ?>
			<h4 class=" display-1 fs-4 lh-base mb-2"><?php echo $produto["titulo"]; ?></h4>
			<div class="rating d-flex align-items-center justify-content-between mb-3">
				<div>
				    <?php
				        $star1;
                        $star2;
                        $star3;
                        $star4;
                        $star5;
                        $media_stars;
                        
                        function checkRate($n){
                            global $star1,$star2,$star3,$star4,$star5,$mysqli,$media_stars;
        			        $star1=$mysqli->query("SELECT * FROM comentarios WHERE cod_produto='".$_GET["cod"]."' AND estrelas='1'");
        			        $star2=$mysqli->query("SELECT * FROM comentarios WHERE cod_produto='".$_GET["cod"]."' AND estrelas='2'");
        			        $star3=$mysqli->query("SELECT * FROM comentarios WHERE cod_produto='".$_GET["cod"]."' AND estrelas='3'");
        			        $star4=$mysqli->query("SELECT * FROM comentarios WHERE cod_produto='".$_GET["cod"]."' AND estrelas='4'");
        			        $star5=$mysqli->query("SELECT * FROM comentarios WHERE cod_produto='".$_GET["cod"]."' AND estrelas='5'");
        			        if($star1->num_rows==0&&$star2->num_rows==0&&$star3->num_rows==0&&$star4->num_rows==0&&$star5->num_rows==0){
        			            for($i=0;$i<5;$i++)
        			                echo "<i class='bi bi-star color-pink'></i>";
        			        }else{
        				        $media_stars=max($star1->num_rows,$star2->num_rows,$star3->num_rows,$star4->num_rows,$star5->num_rows);
            			        if($n<2)
            			            checkRate($n+1);
            			        else{
            			             if($media_stars==$star1->num_rows){
            				            echo "<i class='bi bi-star-fill color-pink'></i>";
            				            for($i=0;$i<4;$i++)
            				                echo "<i class='bi bi-star color-pink'></i>";
            				        }elseif($media_stars==$star2->num_rows){
            				            for($i=0;$i<2;$i++)
                				            echo "<i class='bi bi-star-fill color-pink'></i>";
            				            for($i=0;$i<3;$i++)
            				                echo "<i class='bi bi-star color-pink'></i>";
            				        }elseif($media_stars==$star3->num_rows){
            				            for($i=0;$i<3;$i++)
                				            echo "<i class='bi bi-star-fill color-pink'></i>";
            				            for($i=0;$i<2;$i++)
            				                echo "<i class='bi bi-star color-pink'></i>";
            				        }elseif($media_stars==$star4->num_rows){
            				            for($i=0;$i<4;$i++)
                				            echo "<i class='bi bi-star-fill color-pink'></i>";
            			                echo "<i class='bi bi-star color-pink'></i>";
            				        }elseif($media_stars==$star5->num_rows){
            				            for($i=0;$i<5;$i++)
                				            echo "<i class='bi bi-star-fill color-pink'></i>";
            				        }
            			        }
        			        }
                        }
                        checkRate(0);
				    ?>
					(<?php
					    $result_rating=$mysqli->query("SELECT * FROM comentarios WHERE cod_produto='".$_GET["cod"]."'");
					    echo $result_rating->num_rows;
					?>)
				</div>
				<button type='button' class='btn-favorite bg-transparent border-0'>
					<i class='bi bi-heart color-pink fs-4'
					    onmouseover='favoriteOver(this)'
					    onmouseout='favoriteOut(this)'
					    <?php
					        if(isset($_SESSION["loggedin"])){
					            $result=$mysqli->query("SELECT * FROM favoritos WHERE cod_produto='".$_GET["cod"]."' AND cod_cliente='".$_SESSION["cod_cliente"]."'");
					            if($result->num_rows==1)
					                echo "checked";
					        }
					    ?>
					    ></i>
				</button>
			</div>
			<div class="d-flex justify-content-between border-bottom-pink pb-2 mb-4 fabricante-linha pe-5 pe-md-0">
				<span><b class="me-2">Linha:</b><?php echo $produto["linha"]; ?></span>
				<span>
					<b class="me-2">Unidades:</b>
					<?php
						echo $produto["quantidade"];
					?>
				</span>
			</div>
			<div class="d-flex justify-content-between align-items-xl-center prices border-bottom-pink pb-3 mb-4 mb-md-3 flex-column flex-xl-row">
				<div class="d-flex flex-column mb-3 mb-xl-0">
					<div class="d-flex align-items-center mb-2">
						<i class="bi bi-cash color-pink me-3"></i>
						<div class="d-flex flex-column align-items-start">
							<small>à vista</small>
							<h5>R$<?php echo $produto["preco_revenda"]; ?>,99</h5>
						</div>
					</div>
					<small class="ms-5 mb-3 ou">ou</small>
					<div class="d-flex align-items-center mb-2">
						<i class="bi bi-credit-card color-pink me-3"></i>
						<div class="d-flex flex-column align-items-start">
							<h5>R$<?php echo $installment_price; ?>,99</h5>
							<small>
								em até 10x de <?php echo intval($installment_price/10); ?>,99<br>
								sem juros no cartão</small>
						</div>
					</div>
				</div>
				<button type="submit" class="btn border-0 background-pink hover text-white d-flex align-items-center justify-content-center" form="add-produto" name="submit_add_produto">
					<i class="bi bi-plus-circle me-2"></i> Adicionar ao carrinho
				</button>
			</div>
			<div class="border-bottom-pink mb-3 pb-3">
			    <form action="produto.php?cod=<?php echo $_GET["cod"]; ?>" method="post" id="add-produto">
    				<div class="form-floating" style="width: 8.5rem">
    					<input type="number" min="1" max="<?php echo $produto["quantidade"]; ?>" class="form-control" placeholder="quantidade" value="1" name="quantidade">
    					<label class="d-inline">Quantidade</label>
    				</div>
			    </form>
			</div>
			<div class="border-bottom-pink mb-3 calc-frete">
				<h5>Calcule o frete</h5>
				<div class="d-flex align-items-center mb-2">
					<div class="col-8 col-lg-9 col-xl-10">
						<div class="form-floating mb-2 me-3">
							<input type="text" id="cep" placeholder="cep" class="form-control">
							<label>CEP (00000-000)</label>
						</div>
					</div>
					<div class="col">
						<button onclick="calcFrete(document.getElementById('cep').value);" class="btn text-white background-pink hover mb-2">Calcular</button>
					</div>
				</div>
				<div id="result-frete"></div>
			</div>
			<div class="d-flex flex-column portions">
				<h5>Parcelas</h5>
				<div class="d-flex rounded mx-0 py-2 container flex-column flex-xl-row mb-3">
					<div class="d-flex flex-column">
						<small class='mb-1'>1x de R$<?php echo  $produto["preco_revenda"]; ?>,99 (15% desconto)</small>
						<?php
							for($i=2;$i<=5;$i++){
								echo "<small class='mb-1'>".$i."x de R$".intval($installment_price/$i).",99 (sem juros)</small>";
							}
						?>
					</div>
					<div class="d-flex flex-column">
						<?php
							for($i=6;$i<=10;$i++){
								echo "<small class='mb-1'>".$i."x de R$".intval($installment_price/$i).",99 (sem juros)</small>";
							}
						?>
					</div>
				</div>
				<div class="d-flex justify-content-between align-items-center pay-options bg-transparent">
					<img src="img/pay-options/visa.png" alt="Visa">
					<span class="iconify" data-icon="logos:elo" style="font-size: 3rem;"></span>
					<img src="img/pay-options/mastercard.png" alt="masterdard">
					<img src="img/pay-options/amex.png" alt="amex">
					<img src="img/pay-options/boleto-light.png" alt="Boleto">
					<img src="img/pay-options/boleto-dark.png" alt="Boleto">
				</div>
			</div>
		</div>
	</div>
	<div class="description container mb-5">
		<h4 class="border-bottom-pink pb-2 mb-4">Descrição</h4>
		<pre style="font-family: Poppins"><?php echo $produto["descricao"]; ?></pre>
	</div>
	
	<div class="container">
	    <h2 class="text-center pb-2 mb-5 other-products">
	        <?php
	            if($produto["tipo"]=="Placa mãe")
	                echo "Outras Placas mãe";
	            elseif($produto["tipo"]=="Memória RAM")
	                echo "Outras Memórias";
	            elseif($produto["tipo"]=="GPU")
	                echo "Outras Placas de vídeo";
	            elseif($produto["tipo"]=="Kit de fans")
	                echo "Outros kit de fans";
	            elseif($produto["tipo"]=="Fonte")
	                echo "Outras fontes";
	            else
	                echo "Outros ".$produto["tipo"]."s";
	        ?>
	    </h2>
	</div>
	<?php
    	echo "
        <div class='d-flex flex-column'>
            <div class='featured-products d-flex flex-column container'>
                <div class='container-every column mb-5'>
        ";
       
        $sql="SELECT * FROM produtos WHERE tipo='".$produto["tipo"]."'";
	    $result=$mysqli->query($sql);
	
        $n_rand=rand(12,$result->num_rows);
       
       for($i=0;$i<$n_rand;$i++){
         $produto=$result->fetch_assoc();
         
         if($i>=$n_rand-12){
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
        echo "
                <div>
            </div>
        </div>
      ";
      
      $comment_err=$stars_err="";
      
    if(isset($_POST["submit"])){
        
        if(isset($_SESSION["cod_cliente"])){
            $sql="SELECT * FROM comentarios WHERE cod_cliente='".$_SESSION["cod_cliente"]."' AND cod_produto='".$_GET["cod"]."'";
            $result=$mysqli->query($sql);
            
            $comment=strtolower($_POST["comment"]);
            
            if($result->num_rows<2){
                if(empty($comment))
	                $comment_err="Preencha o campo";
	            else{
                    $palavroes=array("/alienado/","/animal/","/arregassado/","/arrombado/","/babaca/","/baitola/","/baleia/","/barril/","/biba/","/bicha/","/bixa/","/biroska/","/birosca/","/bobo/","/bolagato/","/boquet/","/bosta/","/buceta/","/bund/","/burro/","/bronha/","/cabaço/","/cacete/","/cadela/","/cadelona/","/cafona/","/cambista/","/capiroto/","/caralho/","/catraia/","/cepo/","/cocodrilo/","/cocozento/","/ cu /","/debil/","/demente/","/desciclope/","/desgraç/","/disgraç/","/drogado/","/diabo/","/demônio/","/endemoniado/","/energumeno/","/engole/","/escroto/","/esdruxulo/","/esporrado/","/estigalhado/","/estrume/","/estrunxado/","/fdp/","/fi duma egua/","/filho da puta/","/fiofo/","/fiofó/","/foda/","/fuder/","/fudido/","/fulera/","/galinha/","/gonorreia/","/gozado/","/herege/","/idiota/","/imbecil/","/imundo/","/inascivel/","/inseto/","/invertebrado/","/jacu/","/jegue/","/jumento/","/kct/","/komodo/","/ ku /","/lazarento/","/lazaro/","/lepra/","/leproso/","/lerdo/","/lesma/","/lezado/","/lico/","/lixo/","/lombriga/","/macaco/","/merda/","/meretriz/","/mocorongo/","/morfetico/","/mulambo/","/n00b/","/noob/","/nazista/","/nazi/","/newbie/","/nhaca/","/nonsense/","/ogro/","/otario/","/palhaço/","/panaca/","/paraguaio/","/passaralho/","/pau no cu/","/periquita/","/piriquita/","/pimenteira/","/piranha/","/piroca/","/pistoleira/","/porra/","/prostituta/","/punheta/","/puta/","/puto/","/puta que pariu/","/pqp/","/quenga/","/rampero/","/rapariga/","/raspadinha/","/retardado/","/rusguento/","/sanguesuga/","/sujo/","/tarado/","/tesao/","/tetuda/","/tetudo/","/tosco/","/tragado/","/travesti/","/trepadeira/","/troglodita/","/urubu/","/vaca/","/vadia/","/vagabundo/","/vagaranha/","/vascaino/","/verme/","/viado/","/xavasca/","/xereca/","/xixizento/","/xoxota/","/xupetinha/","/xupisco/","/xurupita/","/xuxexo/","/xxt/","/xxx/","/ziguizira/","/zina/","/zoiudo/","/zoneira/","/zuera/","/zulu/","/zureta/","/matar/","/matei/","/assassin/","/caralh@/","/car@lh@/","/c@ralh@/","/c@r@lho/","/p@rra/","/porr@/","/fuck/","/nigger/","/nigger/","/sexo/","/sequiço/","/sekiço/","/porno/","/vai toma no cu/","/vai tomar no cu/","/vtnc/","/mulato/","/lgbt/","/acéfalo/","/acefalo/","/mongo/","/bolsonaro/","/bolsomito/","/lula/","/ pt /","/shit/","/ass/","bit me","/dick/","/twat/","/jerk/","/prick/","/suck/","/faggot/","/maggot/","/wanker/","/bitch/","/capeta/","/xvideo/","/pornhub/","/redtube/","/hentai/","/loli/","/e621/","/rule 34/","/xnxx/","/porn/","/cock/","/maconha/","/cocaína/","/cocaina/","/crack/","/lança perfume/","/lsd/","/heroína/","/exastase/","/vacilaum/","/vacilao/","/vacilão/","/furry/","/horny/","/nuds/","/nude/","/nudz/","/mama/","/mammamia/","/mamamia/","/mmamamia/","/khalifa/","/diamond jackson/","/futa/","/femboy/","/yaoi/");
                    
                    foreach($palavroes as $palavrao){
                        if(preg_match("$palavrao",$comment))
                            $comment_err="Foi detectado palavras de baixo calão";
                    }
                    
                    $result_comment_exists=$mysqli->query("SELECT * FROM comentarios WHERE cod_cliente='".$_SESSION["cod_cliente"]."' AND comentario='".$_POST["comment"]."' AND cod_produto='".$_GET["cod"]."'");
	            
    	            if($result_comment_exists->num_rows>0)
    	                $comment_err="Comentário já existente";
	            }
                
	            if(empty($_POST["stars"]))
	                $stars_err="Selecione a quantidade de estrelas";
    	        
    	        if(empty($stars_err)&&empty($comment_err)){
    	            $sql="INSERT INTO comentarios (cod_comentario,comentario,estrelas,cod_produto,cod_cliente) VALUES (NULL,'".$_POST["comment"]."','".$_POST["stars"]."','".$_GET["cod"]."','".$_SESSION["cod_cliente"]."')";
    	            $mysqli->query($sql);
    	        }
    	        
    	    }else
    	        $stars_err="Max. dois comentários";
    	    
	        echo "
	            <script>
	                if(window.location.href.search('#form-comment')==-1){
	                    window.location.assign(location+'#form-comment');
	                }
	            </script>
	        ";
        }else{
            echo "
                <script>
                    window.location.assign('https://bufosregulares.com/login.php');
                </script>
            ";
        }
    }
?>
	<div class="container d-flex flex-column align-items-center comments">
      <h2 class="mb-4" id="form-comment">Comentários</h2>
      <h4 class="align-self-start mb-2">Quantidade de estrelas</h4>
		<div class="rating-comment d-flex mb-3 align-self-start align-items-center">
			<i class="bi bi-star color-pink star" id="star-1"></i>
			<i class="bi bi-star color-pink star" id="star-2"></i>
			<i class="bi bi-star color-pink star" id="star-3"></i>
			<i class="bi bi-star color-pink star" id="star-4"></i>
			<i class="bi bi-star color-pink star me-3" id="star-5"></i>
			<span class="color-pink fs-6"><?php echo $stars_err; ?></span>
		</div>
    	<form action="" class="mb-5 d-block w-100" method="post">
    	    <input type="hidden" id="input-stars" name="stars">
    		<div class="form-floating">
    			<textarea name="comment" class="form-control" style="height: 15rem;" placeholder="comentário" maxlength="700"></textarea>
    			<label>Escreva um comentário</label>
    		</div>
    		<span class="color-pink"><?php echo $comment_err; ?></span>
    		<div class="d-grid">
    			<button type="submit" class="btn background-pink hover text-white mt-4" name="submit">
    				Comentar
    			</button>
    		</div>
    	</form>
    	<?php
	        $sql="SELECT * FROM comentarios WHERE cod_cliente='".$_SESSION["cod_cliente"]."' AND cod_produto='".$_GET["cod"]."' ORDER BY `comentarios`.`cod_comentario` DESC";
	        $result=$mysqli->query($sql);
    	    if($result->num_rows>0):
    	        while($comentario=$result->fetch_assoc()):
    	        
        	        $foto=$cliente["foto"];
    
                    if(!file_exists($foto))
                       $foto=str_replace("../","",$foto);
                       
                    $sobrenome=$cliente["sobrenome"];
                    $sobrenome=explode(" ",$sobrenome);
                    $sobrenome=$sobrenome[0];
        	        
        	        echo "
    	              <div class='w-100 comment mb-4' data-cod='".$comentario["cod_comentario"]."'>
    	                <div class='dropdown'>
        					<button type='button' class='btn bg-transparent border-0' data-bs-toggle='dropdown'>
        						<i class='bi bi-three-dots-vertical fs-4'></i>
        					</button>
        					<ul class='dropdown-menu dropdown-menu-end'>
        						<li class='dropdown-item py-0' onclick=\"
        						        var comment=document.querySelectorAll('.comment');
        						        
        						        for(var i=0;i<comment.length;i++){
                                            if(comment[i].getAttribute('data-cod')==".$comentario["cod_comentario"]."){
                                                comment[i].innerHTML='';
                                                deleteComment(".$comentario["cod_comentario"].");
                                                comment[i].remove();
                                            }
                                        }
        						    \">
        						    <button type='button' class='bg-transparent border-0 py-2'>
        						        <i class='bi bi-trash-fill color-pink'></i>
        						        Excluir
        						    </button>
        						</li>
        					</ul>
        				</div>
                         <div class='d-flex align-items-center mb-2'>
                            <img src='$foto' alt='".$cliente["nome"]." $sobrenome'>
                            <div class='d-flex flex-column'>
                               <h5>".$cliente["nome"]." $sobrenome</h5>
                               <div class='d-flex align-items-center'>
                                  <div>";
    ?>
                                    <i class='bi bi-star<?php if($comentario["estrelas"]>=1) echo "-fill"; ?>'></i>
                                    <i class='bi bi-star<?php if($comentario["estrelas"]>=2) echo "-fill"; ?>'></i>
                                    <i class='bi bi-star<?php if($comentario["estrelas"]>=3) echo "-fill"; ?>'></i>
                                    <i class='bi bi-star<?php if($comentario["estrelas"]>=4) echo "-fill"; ?>'></i>
                                    <i class='bi bi-star<?php if($comentario["estrelas"]==5) echo "-fill"; ?>'></i>
    <?php
                    echo "
                                  </div>
                               </div>
                            </div>
                         </div>
                         <blockquote class='mx-0 mx-md-5'>".$comentario["comentario"]."</blockquote>
                      </div>
        	        ";
    	        endwhile;
    	    endif;
    	    
    	    if(isset($_SESSION["cod_cliente"]))
    	        $sql_comment="SELECT * FROM comentarios WHERE cod_cliente<>'".$_SESSION["cod_cliente"]."' AND cod_produto='".$_GET["cod"]."' ORDER BY `comentarios`.`cod_comentario` DESC";
    	    else
    	        $sql_comment="SELECT * FROM comentarios WHERE cod_produto='".$_GET["cod"]."' ORDER BY `comentarios`.`cod_comentario` DESC";
	        $result_comment=$mysqli->query($sql_comment);
    	    if($result_comment->num_rows>0):
    	        for($i=0;$i<$result_comment->num_rows;$i++):
    	            $comentario=$result_comment->fetch_assoc();
    	            
    	            $result=$mysqli->query("SELECT * FROM clientes WHERE cod_cliente='".$comentario["cod_cliente"]."'");
    	            $cliente=$result->fetch_assoc();
    	        
        	        $foto=$cliente["foto"];
    
                    if(!file_exists($foto))
                       $foto=str_replace("../","",$foto);
                       
                    $sobrenome=$cliente["sobrenome"];
                    $sobrenome=explode(" ",$sobrenome);
                    $sobrenome=$sobrenome[0];
        	        
        	        echo "
    	              <div class='align-self-start comment mb-4'>
                         <div class='d-flex align-items-center mb-2'>
                            <img src='$foto' alt='".$cliente["nome"]." $sobrenome'>
                            <div class='d-flex flex-column'>
                               <h5>".$cliente["nome"]." $sobrenome</h5>
                               <div class='d-flex align-items-center'>
                                  <div>";
    ?>
                                    <i class='bi bi-star<?php if($comentario["estrelas"]>=1) echo "-fill"; ?>'></i>
                                    <i class='bi bi-star<?php if($comentario["estrelas"]>=2) echo "-fill"; ?>'></i>
                                    <i class='bi bi-star<?php if($comentario["estrelas"]>=3) echo "-fill"; ?>'></i>
                                    <i class='bi bi-star<?php if($comentario["estrelas"]>=4) echo "-fill"; ?>'></i>
                                    <i class='bi bi-star<?php if($comentario["estrelas"]==5) echo "-fill"; ?>'></i>
    <?php
                echo "
                              </div>
                           </div>
                        </div>
                     </div>
                     <blockquote class='mx-0 mx-md-5'>".$comentario["comentario"]."</blockquote>
                  </div>
    	        ";
	        endfor;
	    endif;
	?>
   </div>
   
   <div class="container mt-5">
	    <h2 class="text-center pb-2 mb-5 other-products">Outros produtos</h2>
	</div>
	<?php
    	echo "
        <div class='d-flex flex-column'>
            <div class='featured-products d-flex flex-column container'>
                <div class='container-every column mb-5'>
       ";
       
        $sql="SELECT * FROM produtos";
	    $result=$mysqli->query($sql);
	
        $n_rand=rand(36,$result->num_rows);
       
       for($i=0;$i<$n_rand;$i++){
         $produto=$result->fetch_assoc();
         
         if($i>=$n_rand-36){
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
        echo "
                <div>
            </div>
        </div>
      ";
  ?>
  
  <?php
    if(isset($_POST["submit_add_produto"])){
        if(isset($_SESSION["cod_cliente"])){
            $sql="SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
	        $result=$mysqli->query($sql);
	        
	        if($result->num_rows>0){
	            $detalhe_pedido=$result->fetch_assoc();
		        $produtos=$detalhe_pedido["produtos"];
		        
		        $items=explode(";",$produtos);
		        $produtos_result="";
		        
		        $search_produto="/p".$_GET["cod"]."/";
		        
		        foreach($items as $item){
		            if($item=="")
		                break;
		            
		            if(preg_match("$search_produto",$item)){
	                   $item="p".$_GET["cod"]."&q".$_POST["quantidade"];
		            }
		                
		            $item=explode("&",$item);
		            $produto_cart=$item[0];
		            $produto_cart=str_replace("p","",$produto_cart);
		            $quantidade_cart=$item[1];
		            $quantidade_cart=str_replace("q","",$quantidade_cart);
		            
		            $produtos_result=$produtos_result.$item[0]."&".$item[1].";";
		        }
		        
		        if(preg_match("$search_produto",$produtos_result))
		            $sql="UPDATE `detalhe_pedido` SET `produtos`='$produtos_result' WHERE `detalhe_pedido`.`cod_detalhe_pedido`='".$detalhe_pedido["cod_detalhe_pedido"]."'";
		        else
		            $sql="UPDATE `detalhe_pedido` SET `produtos`='$produtos_result"."p".$_GET["cod"]."&q".$_POST["quantidade"].";' WHERE `detalhe_pedido`.`cod_detalhe_pedido`='".$detalhe_pedido["cod_detalhe_pedido"]."'";
		            
		        $mysqli->query($sql);
		        
		        $quantidade_produtos_cart=$mysqli->query("SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'");
		        $quantidade_produtos_cart=$quantidade_produtos_cart->fetch_assoc();
		        $quantidade_produtos_cart=$quantidade_produtos_cart["produtos"];
		        $quantidade_produtos_cart=explode(";",$quantidade_produtos_cart);
		        $_SESSION["quantidade_carrinho"]=count($quantidade_produtos_cart)-1;
		        
		        echo "
		            <button type='button' class='d-none' id='btn-added-product' data-bs-toggle='modal' data-bs-target='#added-product'></button>
		        
		            <div class='modal fade' id='added-product'>
                       <div class='modal-dialog modal-dialog-centered'>
                         <div class='modal-content'>
                           <div class='modal-header'>
                             <h5 class='modal-title'>Produto adicionado</h5>
                             <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                           </div>
                           <div class='modal-body d-flex align-items-center justify-content-center'>
                             <p class='m-0'>Produto adionado com sucesso!</p>
                           </div>
                           <div class='modal-footer'>
                             <button type='button' class='btn background-pink hover text-white text-center col-12' data-bs-dismiss='modal'>Fechar</button>
                           </div>
                         </div>
                       </div>
                     </div>
		        ";
	        }else{
	            $sql="INSERT INTO detalhe_pedido (cod_detalhe_pedido,produtos,cod_cliente) VALUES (NULL,'p".$_GET["cod"]."&q".$_POST["quantidade"].";','".$_SESSION["cod_cliente"]."')";
	            $mysqli->query($sql);
	            $quantidade_produtos_cart=$mysqli->query("SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'");
		        $quantidade_produtos_cart=$quantidade_produtos_cart->fetch_assoc();
		        $quantidade_produtos_cart=$quantidade_produtos_cart["produtos"];
		        $quantidade_produtos_cart=explode(";",$quantidade_produtos_cart);
		        $_SESSION["quantidade_carrinho"]=count($quantidade_produtos_cart)-1;
		        
		        echo "
		            <button type='button' class='d-none' id='btn-added-product' data-bs-toggle='modal' data-bs-target='#added-product'></button>
		        
		            <div class='modal fade' id='added-product'>
                       <div class='modal-dialog modal-dialog-centered'>
                         <div class='modal-content'>
                           <div class='modal-header'>
                             <h5 class='modal-title'>Produto adicionado</h5>
                             <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                           </div>
                           <div class='modal-body d-flex align-items-center justify-content-center'>
                             <p class='m-0'>Produto adionado com sucesso!</p>
                           </div>
                           <div class='modal-footer'>
                             <button type='button' class='btn background-pink hover text-white text-center col-12' data-bs-dismiss='modal'>Fechar</button>
                           </div>
                         </div>
                       </div>
                     </div>
		        ";
	        }
        }else{
            echo "
                <script>
                    window.location.assign('https://bufosregulares.com/login.php');
                </script>
            ";
        }
    }
?>
	
	<?php
		$sql="SELECT * FROM produtos";
		$result=$mysqli->query($sql);
		$produto=$result->fetch_assoc();
	?>

	<script src="scripts/easyzoom.js"></script>
	<script>
	    $('#btn-added-product').trigger('click');
	
		$('#cep').mask('00000-000');

		var $easyzoom=$('.easyzoom').easyZoom();
		var api1=$easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

		$('.thumbnails').on('click','a',function(e) {
			var $this=$(this);
			e.preventDefault();
			api1.swap($this.data('standard'),$this.attr('href'));
		});

		var api2=$easyzoom.filter('.easyzoom--with-toggle').data('easyZoom');

		$('.toggle').on('click',function() {
			var $this=$(this);
			if($this.data("active")===true) {
				$this.text("Switch on").data("active", false);
				api2.teardown();
			}else{
				$this.text("Switch off").data("active", true);
				api2._init();
			}
		});

		const inputCep=document.getElementById("cep");
		const resultFrete=document.getElementById("result-frete");

		function calcFrete(cep){
			const xhttp=new XMLHttpRequest();
			xhttp.onload=function(){
				resultFrete.innerHTML=this.responseText;
			}
			xhttp.open("GET","ajax/frete.php?cep="+cep+"&produto=<?php echo $_GET["cod"]; ?>");
			xhttp.send();
		}
		
		function addFavorite(){
			const xhttp=new XMLHttpRequest();
			xhttp.open("GET","ajax/add_favorite.php?cliente=<?php echo $_SESSION["cod_cliente"]; ?>&produto=<?php echo $_GET["cod"]; ?>");
			xhttp.send();
		}
		
		function removeFavorite(){
			const xhttp=new XMLHttpRequest();
			xhttp.open("GET","ajax/remove_favorite.php?cliente=<?php echo $_SESSION["cod_cliente"]; ?>&produto=<?php echo $_GET["cod"]; ?>");
			xhttp.send();
		}
		
		function deleteComment(comentario){
		    const xhttp=new XMLHttpRequest();
			xhttp.open("GET","ajax/delete_comment.php?comentario="+comentario);
			xhttp.send();
		}
		
        const icon=document.querySelector("i.bi-heart");

        icon.addEventListener("mouseover",function(){
            if(icon.hasAttribute("checked")==false){
                icon.classList.remove("bi-heart");
                icon.classList.add("bi-heart-fill");
            }
        });

        icon.addEventListener("mouseout",function(){
            if(icon.hasAttribute("checked")==false){
                icon.classList.remove("bi-heart-fill");
                icon.classList.add("bi-heart");
            }
        });

        icon.addEventListener("click",function(){
        <?php
            if(isset($_SESSION["loggedin"])){
                echo "
                    if(icon.hasAttribute('checked')==false){
                        icon.setAttribute('checked','');
                        addFavorite();
                    }else{
                        icon.removeAttribute('checked');
                        removeFavorite();
                    }
                ";
            }else{
                $_SESSION["last_product"]=$_GET["cod"];
                echo "window.location.assign('https://bufosregulares.com/login.php');";   
            }
        ?>
        });

        if(icon.hasAttribute("checked")==true){
            icon.classList.remove("bi-heart");
            icon.classList.add("bi-heart-fill");
        }
        
      const star1=document.getElementById("star-1");
      const star2=document.getElementById("star-2");
      const star3=document.getElementById("star-3");
      const star4=document.getElementById("star-4");
      const star5=document.getElementById("star-5");
      const inputStars=document.getElementById("input-stars");

      var stars=[
         star1,
         star2,
         star3,
         star4,
         star5
      ];

      star1.addEventListener("mouseover",function(){
         this.classList.remove("bi-star");
         this.classList.add("bi-star-fill");
         for(var i=1;i<stars.length;i++){
            stars[i].classList.remove("bi-star-fill");
            stars[i].classList.add("bi-star");
         }
      });

      star1.addEventListener("click",function(){
         this.setAttribute("checked","");
         for(var i=1;i<stars.length;i++){
            stars[i].removeAttribute("checked");
            stars[i].classList.remove("bi-star-fill");
            stars[i].classList.add("bi-star");
         }
         inputStars.value=1;
      });

      star2.addEventListener("mouseover",function(){
         this.classList.remove("bi-star");
         this.classList.add("bi-star-fill");
         for(var i=0;i<stars.length;i++){
            if(i!=0&&i!=1){
               stars[i].classList.remove("bi-star-fill");
               stars[i].classList.add("bi-star");
            }else{
               stars[i].classList.remove("bi-star");
               stars[i].classList.add("bi-star-fill");
            }
         }
      });

      star2.addEventListener("click",function(){
         star1.setAttribute("checked","");
         this.setAttribute("checked","");
         for(var i=2;i<stars.length;i++){
            stars[i].removeAttribute("checked");
            stars[i].classList.remove("bi-star-fill");
            stars[i].classList.add("bi-star");
         }
         inputStars.value=2;
      });

      star3.addEventListener("mouseover",function(){
         this.classList.remove("bi-star");
         this.classList.add("bi-star-fill");
         for(var i=0;i<stars.length;i++){
            if(i!=0&&i!=1&&i!=2){
               stars[i].classList.remove("bi-star-fill");
               stars[i].classList.add("bi-star");
            }else{
               stars[i].classList.remove("bi-star");
               stars[i].classList.add("bi-star-fill");
            }
         }
      });

      star3.addEventListener("click",function(){
         for(var i=0;i<=1;i++)
            stars[i].setAttribute("checked","");
         this.setAttribute("checked","");
         for(var i=3;i<stars.length;i++){
            stars[i].removeAttribute("checked");
            stars[i].classList.remove("bi-star-fill");
            stars[i].classList.add("bi-star");
         }
         inputStars.value=3;
      });

      star4.addEventListener("mouseover",function(){
         this.classList.remove("bi-star");
         this.classList.add("bi-star-fill");
         for(var i=0;i<stars.length;i++){
            if(i!=0&&i!=1&&i!=2&&i!=3){
               stars[i].classList.remove("bi-star-fill");
               stars[i].classList.add("bi-star");
            }else{
               stars[i].classList.remove("bi-star");
               stars[i].classList.add("bi-star-fill");
            }
         }
      });

      star4.addEventListener("click",function(){
         for(var i=0;i<=2;i++)
            stars[i].setAttribute("checked","");
         this.setAttribute("checked","");
         for(var i=4;i<stars.length;i++){
            stars[i].removeAttribute("checked");
            stars[i].classList.remove("bi-star-fill");
            stars[i].classList.add("bi-star");
         }
         inputStars.value=4;
      });

      star5.addEventListener("mouseover",function(){
         this.classList.remove("bi-star");
         this.classList.add("bi-star-fill");
         for(var i=0;i<stars.length;i++){
            if(i!=0&&i!=1&&i!=2&&i!=3&&i!=4){
               stars[i].classList.remove("bi-star-fill");
               stars[i].classList.add("bi-star");
            }else{
               stars[i].classList.remove("bi-star");
               stars[i].classList.add("bi-star-fill");
            }
         }
      });

      star5.addEventListener("click",function(){
         this.setAttribute("checked","");
         for(var i=0;i<stars.length;i++)
            stars[i].setAttribute("checked","");
         inputStars.value=5;
      });

      document.querySelector(".rating-comment").addEventListener("mouseleave",function(){
         for(var i=0;i<stars.length;i++){
            stars[i].classList.remove("bi-star-fill");
            stars[i].classList.add("bi-star");
         }
      })

      function checkStars(){
         for(var i=0;i<stars.length;i++){
            if(stars[i].hasAttribute("checked")){
               stars[i].classList.add("bi-star-fill");
               stars[i].classList.remove("bi-star");
            }
         }
         requestAnimationFrame(checkStars)
      }

      checkStars();
	</script>
<?php
	include ("head-foot/footer.php");
?>