<?php
    $titulo="- Pedidos";
    include("../head-foot/header.php");
?>
    <h2 class="text-center white-dark mb-5">Seus pedidos</h2>
    
    <div class="container mb-5 px-md-5">
        <?php
            $sql="SELECT * FROM pedidos WHERE cod_cliente='".$_SESSION["cod_cliente"]."' ORDER BY `pedidos`.`cod_pedido` DESC";
            $result=$mysqli->query($sql);
            if($result->num_rows>0){
                while($pedido=$result->fetch_assoc()){
                            
                    $produtos=$pedido["produtos"];
                    
                    $estado=intval($pedido["estado"]);
                    
                    if($estado==0)
                        $estado="A caminho";
                    else
                        $estado="Cancelado";
                    
                    echo "
                        <div class='pedido mb-3 border-pink p-3' style='cursor: pointer' onclick=\"window.location.assign('profile_pedido.php?cod=".$pedido["cod_pedido"]."')\">
                            <header class='white-dark mb-3 d-flex justify-content-between'>
                                <span>Pedido: ".$pedido["cod_pedido"]."</span>
                                <span>$estado</span>
                            </header>
                            <div class='produtos-pedido'>
                    ";
        			        
    		        $items=explode(";",$produtos);
    		        
    		        foreach($items as $item){
    		            if($item=="")
    		                break;
    		                
    		            $item=explode("&",$item);
    		            $produto=$item[0];
    		            $produto=str_replace("p","",$produto);
    		            $quantidade=$item[1];
    		            $quantidade=str_replace("q","",$quantidade);
    		            
    		            $result_produto=$mysqli->query("SELECT * FROM produtos WHERE cod_produto='$produto'");
    		            $produto=$result_produto->fetch_assoc();
    		            
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
                               <div class='d-flex align-items-center mb-2'>
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
    		        
    		        echo "
        		            </div>
    		            </div>
    		        ";
                }
            }else{
                echo "<p class='text-center white-dark mb-5'>você ainda não realizou nenhum pedido</p>";
            }
        ?>
    </div>
<?php
    include("../head-foot/footer.php");
?>