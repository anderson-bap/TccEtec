<?php
    $session_start=session_start();
    
    include("../config.php");
    
    if(!isset($_GET["cod"]))
        die;
    else{
        $sql="SELECT * FROM pedidos WHERE cod_pedido='".$_GET["cod"]."' AND cod_cliente='".$_SESSION["cod_cliente"]."'";
        $result=$mysqli->query($sql);
        if($result->num_rows>0){
            $pedido=$result->fetch_assoc();
            $titulo="- Pedido ".$_GET["cod"];
            include("../head-foot/header.php");
        }else
            die;
    }
?>
    <h2 class="text-center white-dark mb-5">Pedido <?php echo $pedido["cod_pedido"]; ?></h2>
    <div class="container mb-5">
       <div class="row">
           <div class="col-12 col-lg-12 col-xl-8">
               <div class="row">
                   <div class="col-12 col-md-6 produtos-pedido">
                       <h5 class="text-center white-dark mb-4">Produtos</h5>
                       <?php
                            $produtos=$pedido["produtos"];
                			        
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
                                       <div class='d-flex align-items-center mb-3'>
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
                            $xml=simplexml_load_file("http://viacep.com.br/ws/".$pedido["cep"]."/xml/");

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
                           
                            $endereco=$logradouro.$bairro.$localidade.$uf.$pedido["cep"];
                            
                            $cep_origem="08253000";
                            $cep_destino=$pedido["cep"];
                        
                            if(preg_match("$-$",$cep_destino))
                                $cep_destino=str_replace("-","",$cep_destino);
                                
                		    $item=$pedido["produtos"];
                		    $item=explode(";",$item);
                		    $item=explode("&",$item[0]);
                		    $item=str_replace("p","",$item[0]);
                		    
                            $sql="SELECT * FROM produtos WHERE cod_produto='$item'";
                            $result=$mysqli->query($sql);
                            $produto=$result->fetch_assoc();
                        
                            $peso=$produto["peso"];
                            $valor=$produto["preco_revenda"];
                            $tipo_do_frete='40010';
                            $altura=$produto["altura"];
                            $largura=$produto["largura"];
                            $comprimento=$produto["comprimento"];
                            
                            if(intval($comprimento)<16)
                                $comprimento=16;
                        
                            if(intval($largura)<11)
                                $largura=11;
                                
                            if(intval($altura)<2)
                                $altura=2;
                                
                            $xml=simplexml_load_file("http://viacep.com.br/ws/$cep_destino/xml/");
                    
                            $logradouro=$bairro=$localidade=$uf="";
                        
                            if(!$xml->erro){
                                $url="http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
                                $url.="nCdEmpresa=";
                                $url.="&sDsSenha=";
                                $url.="&sCepOrigem=".$cep_origem;
                                $url.="&sCepDestino=".$cep_destino;
                                $url.="&nVlPeso=".$peso;
                                $url.="&nVlLargura=".$largura;
                                $url.="&nVlAltura=".$altura;
                                $url.="&nCdFormato=1";
                                $url.="&nVlComprimento=".$comprimento;
                                $url.="&sCdMaoProria=n";
                                $url.="&nVlValorDeclarado=".$valor;
                                $url.="&sCdAvisoRecebimento=n";
                                $url.="&nCdServico=".$tipo_do_frete;
                                $url.="&nVlDiametro=0";
                                $url.="&StrRetorno=xml";
                                $xml=simplexml_load_file($url);
                            
                                $frete=$xml->cServico;
                            
                                $valor=(string)$frete->Valor;
                                $prazo=(string)$frete->PrazoEntrega;
                            }
                        ?>
                        <h5 class="text-center white-dark mb-4">Dados</h5>
                        <p class="white-dark mb-2"><b>Estado</b></p>
                        <p class="white-dark mb-3 pb-2 border-bottom-pink d-flex justify-content-between"><?php if($pedido["estado"]==0) echo "<span>A caminho</span><span>$prazo dias</span>"; else echo "Cancelado"; ?></p>
                        <p class="white-dark mb-2"><b>Endereço de entrega</b></p>
                        <p class="white-dark mb-3 pb-2 border-bottom-pink"><?php echo $endereco; if($pedido["complemento"]!="") echo "<br>".$pedido["complemento"]; ?></p>
                        <p class='white-dark mb-2'><b>Forma de pagamento</b></p>
                        <?php
                            if($pedido["forma_pagamento"]==0){
                                $sql="SELECT * FROM cartao WHERE cod_cliente='".$_SESSION["cod_cliente"]."' AND cod_cartao='".$pedido["cod_cartao"]."'";
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
                   </div>
               </div>
           </div>
           <div class="col-12 col-md-6 col-lg-5 col-xl-4 mx-auto mt-4 mt-xl-0">
               <h5 class="text-center white-dark mb-4">Preços</h5>
               
               <p class='white-dark mb-2 d-flex justify-content-between'><b>Total</b><span>R$<?php echo $pedido["preco"]; ?>,99</span></p>
               <p class='white-dark mb-2 d-flex justify-content-between'><b>Parcelas</b><span><?php
                            if($pedido["parcelas"]==1)
                                echo "1x de R$".$pedido["preco"].",99";
                            else
                                echo $pedido["parcelas"]."x de R$".intval($pedido["preco"]/$pedido["parcelas"]).",99";
                        ?></span></p>
                <p class='white-dark mb-2 d-flex justify-content-between mb-5'><b>Frete</b><span>R$<?php echo $valor; ?></span></p>
                
                <?php if($pedido["estado"]==0): ?>
                
                <button type="button" class="btn background-pink hover text-white d-flex align-items-center justify-content-center col-12" data-bs-toggle='modal' data-bs-target='#cancelar-pedido'>
                    <i class="bi bi-x fs-4"></i> Cancelar pedido
                </button>
		        
	            <div class='modal fade' id='cancelar-pedido'>
                   <div class='modal-dialog modal-dialog-centered'>
                     <div class='modal-content'>
                       <div class='modal-header'>
                         <h5 class='modal-title'>Cancelar pedido</h5>
                         <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                       </div>
                       <div class='modal-body d-flex align-items-center justify-content-center'>
                         <p class='m-0'>Deseja cancelar o pedido?</p>
                       </div>
                       <div class='modal-footer row'>
                           <form action="" method="post" class="col m-0 p-0 ps-1">
                                <button type='submit' name="submit" class='btn background-pink hover text-white text-center col-12'>Cancelar</button>
                           </form>
                           <?php
                                if(isset($_POST["submit"])){
                                    $mysqli->query("UPDATE `pedidos` SET `estado`='1' WHERE `pedidos`.`cod_pedido`='".$pedido["cod_pedido"]."'");
                                    echo "
                                        <script>
                                            window.location.assign('view_pedidos.php');
                                        </script>
                                    ";
                                }
                           ?>
                         <button type='button' class='btn btn-primary text-white text-center col' data-bs-dismiss='modal'>Fechar</button>
                       </div>
                     </div>
                   </div>
                 </div>
                 
                <?php
                    endif;
                ?>
                
           </div>
       </div>
   </div>
<?php
    include("../head-foot/footer.php");
?>