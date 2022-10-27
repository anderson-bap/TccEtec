<?php
    $not_destroy_prices=true;
    $titulo="- Selecionar forma de pagamento";
    include("../head-foot/header.php");
    
    if(!isset($_SESSION["cod_cliente"])){
        echo "
            <script>
                window.location.assign('https://bufosregulares.com/login.php');
            </script>
        ";
    }
    
    $sql="SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
    $result=$mysqli->query($sql);
    
    if($result->num_rows==0){
        echo "
            <script>
                window.location.assign('https://bufosregulares.com/index.php');
            </script>
        ";
    }
    
    unset($_SESSION["total_produtos"],$_SESSION["subtotal"]);
    
    if(isset($_POST["submit"])&&$_POST["payment"]!=null){
        if($_POST["payment"]==0){
            $_SESSION["payment"]=0;
        }else{
            $_SESSION["payment"]=1;
        }
        echo "
            <script>
                window.location.assign('https://bufosregulares.com/pedido/select_parcelas.php');
            </script>
        ";
    }
?>
<div class="container mb-5">
       <div class="row">
           <div class="col-12 col-lg-7 col-xl-8">
               <form action="" method="post" id="form-payment">
                    <div class="d-flex flex-column align-items-center p-3">
                        <h2 class="white-dark mb-5 text-center">Escolha um método de pagamento</h2>
                        <div class="border-pink rounded-3 col-12 col-md-6 col-lg-7 mb-4">
                            <label for="cartao" class="px-2 py-4 d-flex align-items-center">
                                <div class="col-2 d-flex align-items-center justify-content-center">
                                    <input type="radio" name="payment" style="transform: scale(1.5);" id="cartao" value="0" checked>
                                </div>
                                <div class="col-10">
                                    <div class="d-flex align-items-center pay-options justify-content-center flex-wrap">
                                        <img src="../img/pay-options/visa.png" alt="Visa">
                                        <span class="iconify" data-icon="logos:elo" style="font-size: 3rem;"></span>
                                        <img src="../img/pay-options/mastercard.png" alt="Boleto">
                                        <img src="../img/pay-options/amex.png" alt="amex">
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="border-pink rounded-3 col-12 col-md-6 col-lg-7">
                            <label for="boleto" class="px-2 py-4 d-flex align-items-center justify-content-center">
                                <div class="col-2 d-flex align-items-center justify-content-center">
                                    <input type="radio" name="payment" style="transform: scale(1.5);" id="boleto" value="1">
                                </div>
                                <div class="col-10">
                                    <div class="d-flex align-items-center pay-options justify-content-center flex-wrap">
                                        <img src="../img/pay-options/boleto-light.png" alt="Boleto">
                                        <img src="../img/pay-options/boleto-dark.png" alt="Boleto">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
               </form>
           </div>
           <div class="col-12 col-md p-3">
               <div class="p-3 service-order bg-white py-4 rounded-3">
                   <h3 class="color-dark pb-3 border-bottom-pink mb-4 text-center">Ordem de serviço</h3>
                   <?php
                        $sql="SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
                        $result=$mysqli->query($sql);
                        $produtos=$result->fetch_assoc();
                        $total;
                        
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
    			            
    			            $_SESSION["total_produtos"]=$_SESSION["total_produtos"]+($produto["preco_revenda"]*$quantidade);
    			        }
    			        $_SESSION["subtotal"]=$_SESSION["total_produtos"]+intval($_SESSION["frete"]);
                   ?>
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
                       <b>Subtotal</b><span>R$<span class="total"><?php echo $_SESSION["subtotal"]; ?></span>,99</span>
                   </div>
                   <div class="d-grid mb-4">
                        <button type="submit" form="form-payment" class="btn background-pink hover text-white d-flex align-items-center justify-content-center fs-6" name="submit">
                            <i class='bi bi-arrow-right me-2 fs-4 p-0'></i>
                            Avançar
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
    include("../head-foot/footer.php");
?>