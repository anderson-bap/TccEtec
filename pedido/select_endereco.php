<?php
    $not_destroy_prices=true;
    $titulo="- Selecionar endereço de entrega";
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
    
    $xml=simplexml_load_file("http://viacep.com.br/ws/".$cliente["cep"]."/xml/");

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
   
    $endereco=$logradouro.$bairro.$localidade.$uf.$cliente["cep"];
    
    $cep=$complemento="";
    $cep_err=$complemento_err="";
    
    if(isset($_POST["submit"])){
        include ("../valida/cep.php");
        include ("../valida/complemento-endereco.php");
        
        if($complemento==null&&$cep==$cliente["cep"])
            $_SESSION["complemento"]=$cliente["complemento"];
        elseif($complemento!=null&&$cep!=$cliente["cep"])
            $_SESSION["complemento_pedido"]=$complemento;
        
        $_SESSION["cep_pedido"]=(string)$cep;
        
        if($_SESSION["cep_pedido"]!=""){
            $cep_origem="08253000";
            $cep_destino=$_SESSION["cep_pedido"];
        
            if(preg_match("$-$",$cep_destino))
                $cep_destino=str_replace("-","",$cep_destino);
                
            $sql="SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
		    $result=$mysqli->query($sql);
		    $carrinho=$result->fetch_assoc();
		    $item=$carrinho["produtos"];
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
                
            $xml=simplexml_load_file("http://viacep.com.br/ws/".$_SESSION["cep_pedido"]."/xml/");
    
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
            
                $_SESSION["frete"]=(string)$frete->Valor;
                
                echo "
                    <script>
                        window.location.assign('https://bufosregulares.com/pedido/select_payment.php');
                    </script>
                ";
            }
        }
    }
?>
    <h2 class="text-center mb-5 white-dark px-3">Selecione o endereço de entrega</h2>
    <form action="" method="post" class="container mx-auto mb-5 d-flex flex-column">
        <div class="col-12 col-md-8 col-lg-7 mx-auto">
            <div class="form-check d-flex align-items-center mb-3" id="form-check-cliente">
                <input type="radio" id="endereco-cliente" style="transform: scale(1.6)" class="me-3" name="cep" value="<?php echo $cliente["cep"]; ?>" checked>
                <label for="endereco-cliente" class="border-pink p-4 d-block">
                    <h6 class="white-dark"><?php echo $endereco; ?></h6>
                    <p class="white-dark p-0 m-0"><?php echo $cliente["complemento"]; ?></p>
                </label>
            </div>
            <div class="form-check d-flex align-items-center col-12 mb-4" id="form-check-custom">
                <input type="radio" id="endereco-custom" style="transform: scale(1.6)" name="cep" class="me-3">
                <label for="endereco-custom" class="border-pink p-4 w-100 d-block">
                    <label class="white-dark h6" for="input-endereco">Outro endereço</label>
                    <input type="text" id="cep" class="form-control mb-2" placeholder="Insira o CEP (00000-000)">
                    <span class="color-pink"><?php echo $cep_err; ?></span>
                    <input type="text" id="endereco" class="form-control mb-2" placeholder="Endereço (preencha o CEP)" readonly>
                    <input type="text" id="complemento" name="complemento" class="form-control" placeholder="Complemento">
                    <span class="color-pink"><?php echo $complemento_err; ?></span>
                </label>
            </div>
            <div id="result-frete" class="white-dark"></div>
            <div class="d-grid">
                <button type="submit" name="submit" class="btn background-pink hover text-white d-flex align-items-center justify-content-center py-1">
                    <i class="bi bi-arrow-right fs-4 me-2"></i>
                    Avançar
                </button>
            </div>
        </div>
    </form>
    <script>
        $('#cep').mask('00000-000');
        
        const cliente=document.getElementById("endereco-cliente");
        const custom=document.getElementById("endereco-custom");
        
        const resultFrete=document.getElementById("result-frete");
        
        const inputCep=document.getElementById("cep");
        const inputEndereco=document.getElementById("endereco");
        const inputComplemento=document.getElementById("complemento");
        
        const formCheckCliente=document.getElementById("form-check-cliente");
        const formCheckCustom=document.getElementById("form-check-custom");
        
        function sendCep(cep){
           const xhttp=new XMLHttpRequest();
           xhttp.onload=function(){
              inputEndereco.value=this.responseText;
           }
           xhttp.open("GET","../ajax/cep.php?cep="+cep);
           xhttp.send();
        }
        
        inputCep.addEventListener("keyup",function(){
           sendCep(this.value);
        });
        inputCep.addEventListener("paste",function(){
           sendCep(this.value);
        });
        
        function calcFrete(cep){
			const xhttp=new XMLHttpRequest();
			xhttp.onload=function(){
				resultFrete.innerHTML=this.responseText;
			}
			<?php
			    $sql="SELECT * FROM detalhe_pedido WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
			    $result=$mysqli->query($sql);
			    $carrinho=$result->fetch_assoc();
			    $item=$carrinho["produtos"];
			    $item=explode(";",$item);
			    $item=explode("&",$item[0]);
			    $item=str_replace("p","",$item[0]);
			?>
			xhttp.open("GET","../ajax/frete.php?cep="+cep+"&produto=<?php echo $item; ?>");
			xhttp.send();
		}
		
		custom.addEventListener("click",function(){
		    cliente.removeAttribute("checked");
		    this.setAttribute("checked","");
		});
		
		function toggleEndereco(){
            cliente.removeAttribute("checked");
            custom.setAttribute("checked","");
        }
		
		inputCep.addEventListener("keyup",function(){
		    document.getElementById('endereco-custom').value=this.value;
		    calcFrete(this.value);
		});
		
		inputCep.addEventListener("paste",function(){
		    document.getElementById('endereco-custom').value=this.value;
		    calcFrete(this.value);
		});
		
		formCheckCustom.addEventListener("click",function(){
            toggleEndereco();
            if(cliente.getAttribute("checked")=="")
	            resultFrete.innerHTML="";
		});
		
		window.addEventListener("load",function(){
		    calcFrete("<?php echo $cliente["cep"] ?>");
		});
		
		cliente.addEventListener("click",function(){
	        calcFrete("<?php echo $cliente["cep"] ?>");
		    custom.removeAttribute("checked");
		    this.setAttribute("checked","");
		    inputCep.value="";
	        inputEndereco.value="";
	        inputComplmento.value="";
		});
		
		formCheckCliente.addEventListener("click",function(){
	        calcFrete("<?php echo $cliente["cep"] ?>");
            custom.removeAttribute("checked");
	        cliente.setAttribute("checked","");
	        inputCep.value="";
	        inputEndereco.value="";
	        inputComplmento.value="";
		});
    </script>
<?php
    include("../head-foot/footer.php");
?>