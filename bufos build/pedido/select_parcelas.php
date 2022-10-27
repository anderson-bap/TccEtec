<?php
    $not_destroy_prices=true;
    $titulo="- Parcelas";
    include("../head-foot/header.php");
    $cartao_err="";
    
    $preco_juros=intval(((intval($_SESSION["subtotal"])*18)/100)+intval($_SESSION["subtotal"]));
    
    if($_SESSION["payment"]==1)
        unset($_SESSION["cartao"]);
    
    if(isset($_POST["submit"])){
        if($_SESSION["payment"]==0){
            if(!empty($_POST["cartao"])){
                $_SESSION["cartao"]=$_POST["cartao"];
                $_SESSION["parcelas"]=$_POST["parcelas"];
                
                if($_SESSION["parcelas"]==1)
                    $_SESSION["total"]=$_SESSION["subtotal"];
                else
                    $_SESSION["total"]=$preco_juros;
                
                echo "
                    <script>
                        window.location.assign('https://bufosregulares.com/pedido/confirm_pedido.php');
                    </script>
                ";
            }else
                $cartao_err="Selecione um cartão";
        }else{
            $_SESSION["parcelas"]=$_POST["parcelas"];
                
            if($_SESSION["parcelas"]==1)
                $_SESSION["total"]=$_SESSION["subtotal"];
            else
                $_SESSION["total"]=$preco_juros;
                
            echo "
                <script>
                    window.location.assign('https://bufosregulares.com/pedido/confirm_pedido.php');
                </script>
            ";
        }
    }
?>
    <div class="container mb-5">
       <div class="row">
           <div class="col-12 col-lg-7 col-xl-8 d-flex flex-column align-items-center">
               <form action='' method='post' id="form-payment" class="col-12 d-flex flex-column align-items-center">
                <?php
                    $margin="";
                    if($_SESSION["payment"]==0){
                        echo "<h2 class='text-center white-dark mb-5'>Selecionar cartão</h2>";
                        
                        $sql="SELECT * FROM cartao WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
                        $result=$mysqli->query($sql);
                        
                        if($result->num_rows>0){
                            $n=0;
                            while($cartao=$result->fetch_assoc()){
                                    
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
                                    <label for='cartao-$n' class='col-12 col-sm-9 col-lg-10 col-xl-6 col-md-6 d-flex align-items-center border-pink rounded-3 p-2 mb-3'>
                                        <div class='col-2 d-flex align-items-center justify-content-center'>
                                            <input type='radio' style='transform: scale(1.5)' name='cartao' value='".$cartao["cod_cartao"]."' id='cartao-$n' $checked>
                                        </div>
                                        <div class='col-10'>
                                            <div class='d-flex justify-content-between align-items-center'>
                                                <div class='d-flex align-items-center'>
                                                    <div class='white-dark ps-2 me-4'>
                                                        <h5 class='text-capitalize'>".$cartao["apelido"]."</h5>
                                                        <small>$n_cartao</small>
                                                    </div>
                                                </div>
                                                $bandeira
                                            </div>
                                       </div>
                                    </label>
                                ";
                                $n++;
                            }
                            echo "<p class='text-center color-pink'>$cartao_err</p>";
                        }else{
                            $_SESSION["payment_pedido"]=true;
                            echo "<p class='text-center white-dark' style='margin-top: 5rem'>Você ainda não tem cartões cadastrados, <a class='white-dark color-pink hover' href='../cliente/register_cartao.php'>cadastre agora</a></p>";
                        }
                        $margin="mt-5";
                    }
                    echo "
                        <h2 class='text-center white-dark mb-5 $margin'>Quantidade de parcelas</h2>
                        <select class='form-control' name='parcelas'>
                            <option value='1'>1x R$".$_SESSION["subtotal"].",99 (18% de desconto)</option>
                    ";
                    
                    for($i=2;$i<=10;$i++){
                        $parcela=intval($preco_juros/$i);
                        echo "<option value='$i'>".$i."x R$$parcela,99 (sem juros)</option>";
                    }
                    echo "</select>";
                ?>
                </form>
           </div>
           <div class="col-12 col-md p-3">
               <div class="p-3 service-order bg-white py-4 rounded-3">
                   <h3 class="color-dark pb-3 border-bottom-pink mb-4 text-center">Ordem de serviço</h3>
                   <div class="d-flex align-items-center justify-content-between mb-2">
                       <b>Produtos:</b><span>R$<?php echo $_SESSION["total_produtos"]; ?>,99</span>
                   </div>
                   <div class="d-flex align-items-center justify-content-between mb-2">
                       <b>Impostos:</b><span>R$0,00</span>
                   </div>
                   <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom-pink">
                       <b>Frete:</b><span>R$<?php echo $_SESSION["frete"] ?></span>
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