<?php
    $session_start=session_start();
    include("../config.php");
    $not_destroy_payment_pedido=true;
    if(isset($_GET["cod"])){
        $sql="SELECT * FROM cartao WHERE cod_cartao='".$_GET["cod"]."' AND cod_cliente='".$_SESSION["cod_cliente"]."'";
        $result=$mysqli->query($sql);
        if($result->num_rows==0)
            die;
        else{
            $cartao=$result->fetch_assoc();
            $titulo="- Alterar dados do cartão";
            $name_button="Alterar";
        }
    }else{
        $titulo="Cadastrar novo cartão";
        $name_button="Cadastrar";
    }
    
    include("../head-foot/header.php");
    
    if(!isset($_SESSION["cod_cliente"])){
        echo "
            <script>
                window.location.assign('https://bufosregulares.com/login.php');
            </script>
        ";
    }
    
    if(isset($_SESSION["num_cartao"])&&!isset($_GET["cod"])){
		$_SESSION["num_cartao"]=null;
		$_SESSION["cvv"]=null;
		$_SESSION["data_validade"]=null;
		$_SESSION["nome_impresso"]=null;
		$_SESSION["apelido"]=null;
	}
	
	$num_cartao=$cvv=$bandeira=$data_validade=$nome=$apelido="";
	$num_cartao_err=$cvv_err=$bandeira_err=$data_validade_err=$nome_err=$apelido_err="";
	
	$tipo_nome=true;
	
	if(isset($_POST["submit_cartao"])){
	    include ("../valida/nome.php");
	    
	    if(strlen($nome)>30)
	        $nome_err="Nome inválido";
	    
	    $num_cartao=trim($_POST["num_cartao"]);
	    $num_cartao=str_replace(" ","",$num_cartao);
	    
        if(
	        (strlen($num_cartao)!=15&&strlen($num_cartao)!=16)||
	        preg_match("/[^0-9]/",$num_cartao)
        )
	        $num_cartao_err="Número inválido";
	    
	    $cvv=trim($_POST["cvv"]);
	    
	    if(
	        (strlen($cvv)!=3&&strlen($cvv)!=4)||
	        preg_match("/[^0-9]/",$cvv)
        )
	        $cvv_err="CVV inválido";
	        
	   $bandeira=trim($_POST["bandeira"]);
	   
	   if($bandeira!="visa"&&$bandeira!="mastercard"&&$bandeira!="amex"&&$bandeira!="maestro")
	        $bandeira_err="bandeira inválida";
	        
       $data_validade=trim($_POST["data_validade"]);
       
       if(empty($data_validade))
            $data_validade_err="Preencha o campo";
       elseif(
           strlen($data_validade)!=5||
           intval(substr($data_validade,0,2))<1||
           intval(substr($data_validade,0,2))>12||
           intval(substr($data_validade,3,2))<21||
           intval(substr($data_validade,3,2))>29
       )
            $data_validade_err="Data inválida";
        
        $apelido=trim($_POST["apelido"]);
        
        if(empty($apelido))
            $apelido_err="Preencha o campo";
        elseif(preg_match('/[^a-zA-ZÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜàáâãäçèéêìíîòóôõùúûü ]/',$apelido)||strlen($apelido>30))
            $apelido_err="Apelido inválido";
	    
	    $_SESSION["num_cartao"]=$_POST["num_cartao"];
	    $_SESSION["cvv"]=$_POST["cvv"];
	    $_SESSION["data_validade"]=$_POST["data_validade"];
	    $_SESSION["nome_impresso"]=$_POST["nome"];
	    $_SESSION["apelido"]=$_POST["apelido"];
	    
	    
	    if(
	        empty($num_cartao_err)&&
	        empty($cvv_err)&&
	        empty($bandeira_err)&&
	        empty($data_validade_err)&&
	        empty($nome_err)&&
	        empty($apelido_err)
        ){
            if(isset($_GET["cod"]))
                $sql="UPDATE `cartao` SET `numero_cartao`='$num_cartao',`data_validade`='$data_validade',`bandeira`='$bandeira',`cvv`='$cvv',`nome_impresso`='$nome',`apelido`='$apelido' WHERE `cartao`.`cod_cartao`='".$_GET["cod"]."'";
            else
                $sql="INSERT INTO cartao (cod_cartao,numero_cartao, data_validade, bandeira, cvv, nome_impresso, apelido,cod_cliente) VALUES (NULL,'$num_cartao','$data_validade','$bandeira','$cvv','$nome','$apelido','".$_SESSION["cod_cliente"]."')";
                
            if($mysqli->query($sql)){
                if(isset($_SESSION["payment_pedido"])){
                    echo "
                        <script>
                            window.location.assign('https://bufosregulares.com/pedido/select_parcelas.php');
                        </script>
                    ";
                }else{
                    echo "
                        <script>
                            window.location.assign('https://bufosregulares.com/cliente/pay_options.php');
                        </script>
                    ";
                }
            }
        }
	}
?>
    <div class="register-cliente cartao container mb-5">
        <h2 class="white-dark mb-5 text-center"><?php if(isset($_GET["cod"])) echo "Alterar cartão"; else echo "cadastrar cartão"; ?></h2>
        <p class="d-flex align-items-center">Bandeiras aceitas
            <img src="../img/pay-options/visa.png" width="60" alt="visa" class="ms-2">
            <img src="../img/pay-options/amex.png" width="60" alt="amex">
            <img src="../img/pay-options/mastercard.png" width="60" alt="mastercard">
            <span class='iconify' data-icon='logos:elo' style='font-size: 3rem;'></span>
        </p>
        <form action="" method="post">
            <input type="hidden" id="input-bandeira" name="bandeira"
            value="<?php
					if((isset($cartao)&&!isset($_SESSION["bandeira"]))||(isset($cartao)&&isset($_SESSION["bandeira"])&&$_SESSION["bandeira"]==""))
						echo $cartao["bandeira"];
						
					if(isset($_SESSION["bandeira"])&&$_SESSION["bandeira"]!="")
						echo $_SESSION["bandeira"];
				?>">
            <div class="row mb-3 align-items-start">
                <div class="col-12 col-xl-5 mb-3 row align-items-center">
                    <div class="col-11">
                        <div class="form-floating">
    						<input type="text" name="num_cartao" id="cc"
        						class="form-control <?php echo (!empty($num_cartao_err)) ? 'is-invalid' : ''; ?>"
        						value="<?php
        									if((isset($cartao)&&!isset($_SESSION["num_cartao"]))||(isset($cartao)&&isset($_SESSION["num_cartao"])&&$_SESSION["num_cartao"]==""))
        										echo $cartao["numero_cartao"];
        										
        									if(isset($_SESSION["num_cartao"])&&$_SESSION["num_cartao"]!="")
        										echo $_SESSION["num_cartao"];
        								?>"
        						placeholder="número do cartão">
    						<label class="d-flex">Número (0000 0000 0000 0000)<i class="bi bi-asterisk color-pink ms-1"></i></label>
    						<span class="color-pink"><?php echo $num_cartao_err; ?></span>
    					</div>
                    </div>
					<div class="col-1 p-0 d-flex justify-content-center">
    				    <img class="bandeira" src="" width="40">
    					<div class="icon">
					</div>
					</div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-3 row align-items-center">
                    <div class="col-5 col-md-8">
                        <div class="form-floating">
    						<input type="text" name="cvv" id="cvv"
        						class="form-control <?php echo (!empty($cvv_err)) ? 'is-invalid' : ''; ?>"
        						value="<?php
        									if((isset($cartao)&&!isset($_SESSION["cvv"]))||(isset($cartao)&&isset($_SESSION["cvv"])&&$_SESSION["cvv"]==""))
        										echo $cartao["cvv"];
        										
        									if(isset($_SESSION["cvv"])&&$_SESSION["cvv"]!="")
        										echo $_SESSION["cvv"];
        								?>"
        						placeholder="cvv">
    						<label class="d-flex">CVV<i class="bi bi-asterisk color-pink ms-1"></i></label>
    						<span class="color-pink"><?php echo $cvv_err; ?></span>
    					</div>
                    </div>
                    <div class="col">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAEOklEQVRoge2ZS2hcVRzGv5O2SQcTKTXaNjIWEVNb7EolJJjaFheCqQrutOqi4Kt0YUAUVFBbXOmqqCtFBFGoghRttdDEKRIfffhYiFZtSxWjbto6iY1i5ufi/K85mU5m7r1zJxNhPgj33vN/fd+c572RWmihhRZaaCFDAFuAUWCChYcJYAQYqiXiuSYTTYJdIXcX9oSkvY3p54Zhi3PuPUlqCxqHm0SmHjwS3YQ9UpTU2RQ66VF0zl0szRZC8/ikh3POSbOH1v8ai8sbIoULHeUj6AIhdSa/SNLNkjZJ6pPUI6lbEpLGJf0m6aik/ZIKzrnzWdaPSJB2ngCrgReAMwn2gSLwPLAiU75phABLgMeASQsv4U8Fw8BGYBWw2P5WWduw+ZQsZhJ4CljUFCFAHvjcwqaB14DeBPG9FjNtOUaBngTx1YWkwLdAX1wCFer2WY5UyErIB0BXWhFB7S7L1RQhB4COekUE9Tss57wKOQ4sy0pEwGGZ5U4sJM3OPi3pPufcWWAbM6tP3ZB0Rn6vaZPUIel0ml8jLl41/7XA+axElOFuq/FQLce0QkrAGvP/qB6mNfC11egC/qzmGPFPOrTGnHPfATdIuilxt8fHemDAOVeU9H6cgKRC9tn1zoRxaXCLXQtxnJMK+cyu/UHbcUmb5SdnXtIr1v6DpKskHbDnKUnrJO2RdMxsn5rtrKSrJX0Y5B2065FEDGOO3dXmO27P/wDrgRuBT4DdQBtw2Oy9wIN2vx9YBPxqcSuBx832JpDDHyQjnLJaPXHmSFIhS803Wq2+seexwGcv8L3dP4E/k5WAHfhDY4Tt+B8B4B7gjrJak5Y71wghXeYbnXYP2fOJOfy/MvuXwJXAi4GtYLYfgW7gjfkU0mu+p+x5HD+UIhJ/A/fiT7IR1gBbze+XoH0af7TfCiwFzpXVaujQ2mS+7wZtDwCd+LkwAFzGzBwCeNJiNlTIt8Nst1WwjZitP46Q8FV3QrU/Bw1KGpU0Iul2a3tZfhUbk3/FfUvSyiBmG9ApaaBCvu3A5RZXjo/tel0VPsULWvDfVGvhqPl2A1Mx/OtBv9XaU8XnYCUhQzGSh0eU3VmwnQPRIlHriHJrxX4CdsUo8rb5XgL8nAHpSrjLajxcxWdnlSH3X88cZPbmFKIEDJjvRvzmliUOAQ5oB06X2YrGrXJPxAXwrCU8CVxqbfeT4TtJgGfqIltDSDtwzAoVgPYMc+eY2X+OAEuyyj1XwTwzc6MQ9UydOZcHIn7CL8mNB3BtIOYENmdS5hq0oQp+TqzLkmscAvlgmJWAd4C1CeKvAV5n5gPd4XnriQpk2oGdwF/BJP0CeBrYbGQ78Z96VuCPKY/iV6ZogZgy/8bOiZiCrgBeAv6otRQFOGcx+Sw4ZPq/ECAnaUjSBknXy78xLpeUk/S7pJPyb4ejkvY55yazrN/CQsK/ICw22s5Zg7UAAAAASUVORK5CYII=" class="cvv-img-white p-0 m-0">
					    <img src="https://img.icons8.com/ios-filled/50/undefined/card-verification-value.png" class="cvv-img-dark p-0 m-0">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-xl">
                    <div class="form-floating">
						<input type="text" name="data_validade" id="data-validade"
    						class="form-control <?php echo (!empty($data_validade_err)) ? 'is-invalid' : ''; ?>"
    						value="<?php
    									if((isset($cartao)&&!isset($_SESSION["data_validade"]))||(isset($cartao)&&isset($_SESSION["data_validade"])&&$_SESSION["data_validade"]==""))
    										echo $cartao["data_validade"];
    										
    									if(isset($_SESSION["data_validade"])&&$_SESSION["data_validade"]!="")
    										echo $_SESSION["data_validade"];
    								?>"
    						placeholder="validade">
						<label class="d-flex">Validade (00/00)<i class="bi bi-asterisk color-pink ms-1"></i></label>
						<span class="color-pink"><?php echo $data_validade_err; ?></span>
					</div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 col-md-6 mb-3">
                    <div class="form-floating">
						<input type="text" name="nome" oninput="this.value=this.value.toUpperCase()" maxlength="30"
    						class="form-control <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>"
    						value="<?php
    									if((isset($cartao)&&!isset($_SESSION["nome_impresso"]))||(isset($cartao)&&isset($_SESSION["nome_impresso"])&&$_SESSION["nome_impresso"]==""))
    										echo $cartao["nome_impresso"];
    										
    									if(isset($_SESSION["nome_impresso"])&&$_SESSION["nome_impresso"]!="")
    										echo $_SESSION["nome_impresso"];
    								?>"
    						placeholder="nome impresso">
						<label class="d-flex">Nome impresso<i class="bi bi-asterisk color-pink ms-1"></i></label>
						<span class="color-pink"><?php echo $nome_err; ?></span>
					</div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-floating">
						<input type="text" name="apelido" maxlength="30"
    						class="form-control <?php echo (!empty($apelido_err)) ? 'is-invalid' : ''; ?>"
    						value="<?php
    									if((isset($cartao)&&!isset($_SESSION["apelido"]))||(isset($cartao)&&isset($_SESSION["apelido"])&&$_SESSION["apelido"]==""))
    										echo $cartao["apelido"];
    										
    									if(isset($_SESSION["apelido"])&&$_SESSION["apelido"]!="")
    										echo $_SESSION["apelido"];
    								?>"
    						placeholder="nome impresso">
						<label class="d-flex">Apelido do cartão<i class="bi bi-asterisk color-pink ms-1"></i></label>
						<span class="color-pink"><?php echo $apelido_err; ?></span>
					</div>
                </div>
            </div>
            <div class="d-grid">
                <button class="btn submit background-pink hover text-white" disabled name="submit_cartao"><?php echo $name_button; ?></button>
            </div>
        </form>
    </div>
    <script>
        $('#cc').mask('0000 0000 0000 0000');
        $('#cvv').mask('0000');
        $('#data-validade').mask('00/00');
        
        var acceptedCreditCards = {
         visa: /^4[0-9]{12}(?:[0-9]{3})?$/,
         mastercard: /^5[1-5][0-9]{14}$|^2(?:2(?:2[1-9]|[3-9][0-9])|[3-6][0-9][0-9]|7(?:[01][0-9]|20))[0-9]{12}$/,
         amex: /^3[47][0-9]{13}$/,
         maestro: /^(50|56|57|58|61|67|68|69)[0-9]{8,15}$/
      };

      
      $('#cc, #cvv').on('input', function(){
         if (validateCard($('#cc').val()) && validateCVV($('#cc').val(), $('#cvv').val())) {
            $('.submit').prop('disabled', false);
         } else {
            $('.submit').prop('disabled', true);
         }
      });
      
      $(window).on('load', function(){
         if (validateCard($('#cc').val()) && validateCVV($('#cc').val(), $('#cvv').val())) {
            $('.submit').prop('disabled', false);
         } else {
            $('.submit').prop('disabled', true);
         }
      });
      
      const inputBandeira=document.getElementById("input-bandeira");
      
      const cc=document.getElementById("cc");
      
      window.addEventListener("load",checkBandeira);
      cc.addEventListener("keyup",checkBandeira);
      cc.addEventListener("paste",checkBandeira);
      inputBandeira.addEventListener("change",checkBandeira);

      function checkBandeira(){
         var icon=document.querySelector(".icon");
         var img=document.querySelector(".bandeira");

         if(inputBandeira.value!=""){
            if(inputBandeira.value=="visa"){
               img.src="../img/pay-options/visa.png";
               icon.innerHTML="";
            }else if (inputBandeira.value=="amex"){
               img.src="../img/pay-options/amex.png";
               icon.innerHTML="";
            }else if (inputBandeira.value=="mastercard"){
               img.src="../img/pay-options/mastercard.png";
               icon.innerHTML="";
            }else if (inputBandeira.value=="maestro"){
               img.src="";
               icon.innerHTML="<span class='iconify' data-icon='logos:elo' style='font-size: 2rem; margin-top: -1.5rem'></span>";
            }
         }else{
            img.src="";
            icon.innerHTML="";
         }
      }

      function validateCard(value) {
         // remove all non digit characters
         var value = value.replace(/\D/g, '');
         var sum = 0;
         var shouldDouble = false;
         // loop through values starting at the rightmost side
         for (var i = value.length - 1; i >= 0; i--) {
            var digit = parseInt(value.charAt(i));

            if (shouldDouble) {
               if ((digit *= 2) > 9) digit -= 9;
            }

            sum += digit;
            shouldDouble = !shouldDouble;
         }
         
         var valid = (sum % 10) == 0;
         var accepted = false;
         inputBandeira.value="";
         
         // loop through the keys (visa, mastercard, amex, etc.)
         Object.keys(acceptedCreditCards).forEach(checkAccepted);

         function checkAccepted(item) {
            var regex = acceptedCreditCards[item];
            if (regex.test(value)) {
               accepted = true;
               var cc=document.getElementById("cc").value;
               if(item=="amex"&&cc.length==18)
                  inputBandeira.value=item;
               else if (item!="amex"&&cc.length==19)
                  inputBandeira.value=item;
            }
         }

         if(valid&&accepted){
            return true;
         }else{
            return false;
         }
      }

      function validateCVV(creditCard, cvv) {
         // remove all non digit characters
         var creditCard = creditCard.replace(/\D/g, '');
         var cvv = cvv.replace(/\D/g, '');
         // american express and cvv is 4 digits
         if ((acceptedCreditCards.amex).test(creditCard)) {
            if((/^\d{4}$/).test(cvv))
               return true;
         } else if ((/^\d{3}$/).test(cvv)) { // other card & cvv is 3 digits
            return true;
         }
         return false;
      }
    </script>
<?php
    include("../head-foot/footer.php");
?>