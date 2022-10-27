<?php
    $titulo="- Formas de pagamento";
    include("../head-foot/header.php");
    
    if(!isset($_SESSION["cod_cliente"])){
        echo "
            <script>
                window.location.assign('https://bufosregulares.com/login.php');
            </script>
        ";
    }
?>
<h2 class="text-center mb-5 white-dark">Seus cartões</h2>
<?php
    $sql="SELECT * FROM cartao WHERE cod_cliente='".$_SESSION["cod_cliente"]."'";
    $result=$mysqli->query($sql);
    
    if($result->num_rows>0){
        echo "
            <div class='row container mx-auto' style='margin-bottom: 10rem'>
                <div class='d-none d-lg-block col order-1'></div>
                <div class='d-none d-lg-block col order-3'></div>
                <div class='col-12 col-lg-6 order-2'>
        ";
        while($cartao=$result->fetch_assoc()){
            $n_cartao=$cartao["numero_cartao"];
            
            if($cartao["bandeira"]=="amex")
                $n_cartao=substr($n_cartao,0,4)." ".substr($n_cartao,4,2)."xxxx xxxxx";
            else
                $n_cartao=substr($n_cartao,0,4)." ".substr($n_cartao,4,2)."xx xxxx xxxx";
            
            if(file_exists("../img/pay-options/".$cartao["bandeira"].".png"))
                $bandeira="<img width='50' src='../img/pay-options/".$cartao["bandeira"].".png' alt='".$cartao["bandeira"]."'>";
            else
                $bandeira="<span class='iconify' data-icon='logos:elo' style='font-size: 2.5rem;'></span>";

            
            echo "
                <div class='modal fade' id='cartao-".$cartao["cod_cartao"]."'>
                    <div class='modal-dialog modal-dialog-centered'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title'>Excluir cartão</h5>
                                <button type='button' class='btn-close me-1' data-bs-dismiss='modal'></button>
                            </div>
                            <div class='modal-body'>
                                <p class='text-center fs-5 m-0'>Deseja excluir o cartão: ".$cartao["apelido"]."?</p>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-primary col' data-bs-dismiss='modal'
                                onclick=\"
        						    var cartao=document.querySelectorAll('.credit-card');
        						    for(var i=0;i<cartao.length;i++){
        						        if(cartao[i].getAttribute('data-cod')==".$cartao["cod_cartao"]."){
        						            cartao[i].innerHTML='';
        						            deleteCartao(".$cartao["cod_cartao"].");
        						            cartao[i].remove();
        						        }
        						    }
    						    \">Excluir</button>
                                <button type='button' class='btn background-pink hover text-white col' data-bs-dismiss='modal'>Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div data-cod='".$cartao["cod_cartao"]."' class='credit-card border-pink p-2 d-flex justify-content-between align-items-center mb-4'>
                    <div class='d-flex align-items-center'>
                        <div class='white-dark ps-2 me-4'>
                            <h5 class='text-capitalize'>".$cartao["apelido"]."</h5>
                            <small>$n_cartao</small>
                        </div>
                        $bandeira
                    </div>
                    <div class='dropdown'>
    					<button type='button' class='btn bg-transparent border-0' data-bs-toggle='dropdown'>
    						<i class='bi bi-three-dots-vertical fs-4 white-dark'></i>
    					</button>
    					<ul class='dropdown-menu dropdown-menu-end'>
    						<li class='dropdown-item p-0'>
    						    <a href='register_cartao.php?cod=".$cartao["cod_cartao"]."' class='bg-transparent border-0 py-2 ps-4 text-decoration-none d-block text-black'>
    						        <i class='bi bi-pen-fill color-pink'></i>
    						        Alterar
    						    </a>
    						</li>
    						<li class='dropdown-item py-0' data-bs-toggle='modal' data-bs-target='#cartao-".$cartao["cod_cartao"]."'>
    						    <button type='button' class='bg-transparent border-0 py-2'>
    						        <i class='bi bi-trash-fill color-pink'></i>
    						        Excluir
    						    </button>
    						</li>
    					</ul>
    				</div>
                </div>
            ";
        }
        echo "
                <p class='text-center order-5 mt-5'><a class='white-dark color-pink hover' href='register_cartao.php'>Cadastrar novo cartão</a></p>
                </div>
            </div>
        ";
    }else{
        echo "<p class='text-center white-dark' style='margin-block: 7rem'>Você ainda não tem cartões cadastrados, <a class='white-dark color-pink hover' href='register_cartao.php'>cadastre agora</a></p>";
    }
?>
    <script>
        function deleteCartao(cartao){
		    const xhttp=new XMLHttpRequest();
			xhttp.open("GET","../ajax/delete_cartao.php?cartao="+cartao+"&cliente=<?php echo $_SESSION["cod_cliente"]; ?>");
			xhttp.send();
		}
    </script>
<?php
    include("../head-foot/footer.php");
?>