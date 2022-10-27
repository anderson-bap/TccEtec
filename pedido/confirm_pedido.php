<?php
    $not_destroy_prices=true;
    $titulo="- Confirmar pedido";
    include("../head-foot/header.php");
?>
    <div class="container mb-5">
       <div class="row">
           <div class="col-12 col-lg-12 col-xl-8">
               <h2 class="text-center mb-5 white-dark">Detalhes do pedido</h2>
               <div class="row">
                   <div class="col-12 col-md-6 produtos-pedido">
                       <h5 class="text-center white-dark mb-4">Produtos</h5>
                       <?php
                            $sql="SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
                            $result=$mysqli->query($sql);
                            $produtos=$result->fetch_assoc();
                            
                            $produtos=$produtos["produtos"];
                			        
        			        $items=explode(";",$produtos);
        			        
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
        			                <div class='d-flex align-items-center mb-3 product-cart white-dark mb-5 mb-md-0'>
                                       <div class='d-flex align-items-center'>
                                          <a href='https://bufosregulares.com/produto.php?cod=".$produto["cod_produto"]."'>
                                            <img src='$foto' alt='".$produto["titulo"]."' class='rounded me-2'>
                                          </a>
                                          <div class='d-flex flex-column'>
                                             <h6 class='white-dark mb-0'>$titulo</h6>
                                             <div>
                                                 <small>$quantidade unidade$unidades <b></b></small>
                                                 <small><b>R$".$produto["preco_revenda"]*$quantidade.",99</b></small>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
        			            ";
        			        }
        			        $stmt->close();
                       ?>
                   </div>
                   <div class="col-12 col-md-6">
                        <?php
                            $xml=simplexml_load_file("http://viacep.com.br/ws/".$_SESSION["cep_pedido"]."/xml/");

                            $logradouro=$bairro=$localidade=$uf="";
                        
                            if(!$xml->erro){
                                if($xml->logradouro!="")
                                    $logradouro="$xml->logradouro, ";
                                if($xml->bairro!="")
                                    $bairro="$xml->bairro, ";
                                if($xml->localidade!="")
                                    $localidade="$xml->localidade - ";
                                if($xml->uf!="")
                                    $uf="$xml->uf, ";
                            }
                           
                            $endereco=$logradouro.$bairro.$localidade.$uf.$_SESSION["cep_pedido"];
                        ?>
                        <p class="white-dark mb-2"><b>Endereço de entrega</b></p>
                        <p class="white-dark mb-3 pb-2 border-bottom-pink"><?php echo $endereco; if($_SESSION["complemento"]!="") echo "<br>".$_SESSION["complemento"]; ?></p>
                        <p class='white-dark mb-2'><b>Forma de pagamento</b></p>
                        <?php
                            if($_SESSION["payment"]==0){
                                $sql="SELECT * FROM cartao WHERE cod_cliente='".$_SESSION["cod_cliente"]."' AND cod_cartao='".$_SESSION["cartao"]."'";
                                $result=$mysqli->query($sql);
                                    
                                $cartao=$result->fetch_assoc();
                                
                                $n_cartao=$cartao["numero_cartao"];
                                
                                if($cartao["bandeira"]=="amex")
                                    $n_cartao=substr($n_cartao,0,4)." ".substr($n_cartao,4,2)."xxxx xxxxx";
                                else
                                    $n_cartao=substr($n_cartao,0,4)." ".substr($n_cartao,4,2)."xx xxxx xxxx";
                                
                                if(file_exists("../img/pay-options/".$cartao["bandeira"].".png"))
                                    $bandeira="<img class='me-2' width='50' src='../img/pay-options/".$cartao["bandeira"].".png' alt='".$cartao["bandeira"]."'>";
                                else
                                    $bandeira="<span class='iconify me-2' data-icon='logos:elo' style='font-size: 2.5rem;'></span>";
                                
                                echo "
                                    <div class='d-flex align-items-center justify-content-between pb-3 border-bottom-pink mb-3'>
                                        <div class='white-dark'>
                                            <h6>".$cartao["apelido"]."</h6>
                                            <span>$n_cartao</span>
                                        </div>
                                        $bandeira
                                    </div>
                                ";
                            }else
                                echo "<p class='white-dark pb-3 border-bottom-pink mb-3'>Boleto</p>";
                        ?>
                        <p class='white-dark mb-2'><b>Parcelas</b></p>
                        <p class="white-dark mb-3 pb-2 border-bottom-pink">
                        <?php
                            if($_SESSION["parcelas"]==1)
                                echo "1x de R$".$_SESSION["total"].",99";
                            else
                                echo $_SESSION["parcelas"]."x de R$".intval($_SESSION["total"]/$_SESSION["parcelas"]).",99";
                        ?>
                        </p>
                   </div>
               </div>
           </div>
           <div class="col-12 col-md-6 col-lg-5 col-xl-4 mx-auto p-3">
               <div class="p-3 service-order bg-white py-4 rounded-3">
                   <h3 class="color-dark pb-3 border-bottom-pink mb-4 text-center">Ordem de serviço</h3>
                   <div class="d-flex align-items-center justify-content-between mb-2">
                       <b>Produtos:</b><span>R$<?php echo $_SESSION["total_produtos"]; ?>,99</span>
                   </div>
                   <div class="d-flex align-items-center justify-content-between mb-2">
                       <b>Impostos:</b><span>R$0,00</span>
                   </div>
                   <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom-pink">
                       <b>Frete:</b><span>R$<?php echo $_SESSION["frete"]; ?></span>
                   </div>
                   <div class="d-flex align-items-center justify-content-between fs-4 mb-4">
                       <b>Total</b><span>R$<span class="total"><?php echo $_SESSION["total"]; ?></span>,99</span>
                   </div>
                   <div class="d-grid mb-4">
                        <form action="" method="post" id="form-payment" class="d-none"></form>
                        <button type="submit" form="form-payment" class="btn background-pink hover text-white d-flex align-items-center justify-content-center fs-6" name="submit">
                            <i class='bi bi-currency-dollar me-2 fs-4 p-0'></i>
                            Comprar
                        </button>
                   </div>
                   <div class="d-flex align-items-center pay-options justify-content-center flex-wrap">
                        <img src="../img/pay-options/visa.png" alt="Visa">
                        <span class="iconify" data-icon="logos:elo" style="font-size: 3rem;"></span>
                        <img src="../img/pay-options/mastercard.png" alt="Boleto">
                        <img src="../img/pay-options/amex.png" alt="amex">
                        <img src="../img/pay-options/boleto-dark.png" class="d-inline" alt="Boleto">
                    </div>
               </div>
           </div>
       </div>
   </div>
   <?php
        if(isset($_POST["submit"])){
            $sql="SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
            $result=$mysqli->query($sql);
            $produtos=$result->fetch_assoc();
            
            $produtos=$produtos["produtos"];
			        
	        $items=explode(";",$produtos);
	        
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
	            $produto_estoque=$result->fetch_assoc();
	            
	            $nova_qtde=intval($produto_estoque["quantidade"])-intval($quantidade);
	            $mysqli->query("UPDATE `produtos` SET `quantidade`='$nova_qtde' WHERE `produtos`.`cod_produto`='$produto'");
	        }
	        
	        $result=$mysqli->query("SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'");
            $detalhe_pedido=$result->fetch_assoc();
            
            $mysqli->query("INSERT INTO pedidos (cod_pedido,produtos,cep,complemento,forma_pagamento,cod_cartao,parcelas,preco,cod_cliente,estado) VALUES (NULL,'".$detalhe_pedido["produtos"]."','".$_SESSION["cep_pedido"]."','".$_SESSION["complemento"]."','".$_SESSION["payment"]."','".$_SESSION["cartao"]."','".$_SESSION["parcelas"]."','".$_SESSION["total"]."','".$_SESSION["cod_cliente"]."','0')");
            $mysqli->query("DELETE FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'");
            
            unset($_SESSION["quantidade_carrinho"]);
            
            echo "
                <script>
                    window.location.assign('https://bufosregulares.com/pedido/view_pedidos.php');
                </script>
            ";
        }
    ?>
<?php
    include("../head-foot/footer.php");
?>