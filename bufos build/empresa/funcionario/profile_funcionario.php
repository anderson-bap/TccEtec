<?php
   include("../config.php");

   if(isset($_GET["cod"])){
      $sql="SELECT * FROM funcionarios WHERE cod_funcionario=".$_GET["cod"];
      $result=$mysqli->query($sql);

      if($result->num_rows>0){
         $funcionario=$result->fetch_assoc();
         $titulo="Dados: ".$funcionario["nome"]." ".$funcionario["sobrenome"];
      }
   }else
      die("404 NOT FOUND");

   $adm=true;
   include("../head-foot/header_empresa.php");

   unset(
      $_SESSION["nome"],
      $_SESSION["sobrenome"],
      $_SESSION["data_nasc"],
      $_SESSION["telefone"],
      $_SESSION["celular"],
      $_SESSION["cpf"],
      $_SESSION["cep"],
      $_SESSION["complemento"],
      $_SESSION["email"],
      $_SESSION["login"],
      $_SESSION["senha"],
      $_SESSION["confirm_senha"],
      $_SESSION["foto"],
      $_SESSION["endereco"],
      $_SESSION["foto_original"],
      $_SESSION["estado_civil"],
      $_SESSION["cargo"]
   );

   if(isset($_POST["submit"])){
      if($_POST["delete"]=="TYUBNV134"){
         $sql="DELETE FROM funcionarios WHERE cod_funcionario=".$_GET["cod"];
         $mysqli->query($sql);
         header("location: view_funcionario.php");
      }else
         $delete_err="Senha de acesso inválida";
   }
?>

    <a href="view_funcionario.php" class="back">
      <i class="bi bi-arrow-left-circle-fill"></i>
    </a>

<?php

   if(!isset($funcionario)){
      echo "
         <div class='d-flex justify-content-center alert-empresa'>
               <div class='alert alert-danger'>Funcionário inexistente!</div>
         </div>
      ";
   }else{
      $data_nasc=$funcionario["data_nasc"];
      $dia_data=substr($data_nasc,8,2);
      $mes_data=substr($data_nasc,5,2);
      $ano_data=substr($data_nasc,0,4);
      $data_nasc=$dia_data."/".$mes_data."/".$ano_data;

      $cpf=$funcionario["cpf"];
      $cpf=substr($cpf,0,3).".".substr($cpf,3,3).".".substr($cpf,6,3)."-".substr($cpf,9,2);

      $telefone=$funcionario["telefone"];

      $ddd_telefone=substr($telefone,0,2);
      $inicio_telefone=substr($telefone,2,4);
      $final_telefone=substr($telefone,6,4);

      $telefone="($ddd_telefone) $inicio_telefone-$final_telefone";

      $celular=$funcionario["celular"];

      $ddd_celular=substr($celular,0,2);
      $inicio_celular=substr($celular,2,5);
      $final_celular=substr($celular,7,4);

      $celular="($ddd_celular) $inicio_celular-$final_celular";

      $estado_civil=$funcionario["estado_civil"];
      if($estado_civil==0)
         $estado_civil="Solteiro(a)";
      elseif($estado_civil==1)
         $estado_civil="Casado(a)";
      elseif($estado_civil==2)
         $estado_civil="Divorciado(a)";
      elseif($estado_civil==3)
         $estado_civil="Viúvo(a)";
      else
         $estado_civil="Separado Judicialmente";

      $stmt=$mysqli->prepare("SELECT nome_cargo FROM cargos WHERE cod_cargo=?");
      $stmt->bind_param("s", $param_cod_cargo);
      $param_cod_cargo=$funcionario["cod_cargo"];
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($cargo);
      $stmt->fetch();
      $stmt->close();

      $xml=simplexml_load_file("http://viacep.com.br/ws/".$funcionario["cep"]."/xml/");

      $logradouro=$bairro=$localidade=$uf="";

      if(!$xml->erro){
         if($xml->logradouro!="")
               $logradouro="$xml->logradouro, ";
         if($xml->bairro!="")
               $bairro="$xml->bairro, ";
         if($xml->localidade!="")
               $localidade="$xml->localidade, ";
         if($xml->uf!="")
               $uf=$xml->uf;
      }
      
      $endereco=$logradouro.$bairro.$localidade.$uf;

      $sobrenome_title=explode(" ",$funcionario["sobrenome"]);

      echo "
         <h2 class='text-center text-white mb-5'>Dados de <span class='display-6 fs-2'>".$funcionario["nome"]." ".$sobrenome_title[0]."</span></h2>
         <div class='dados funcionario'>
            <div class='row mb-4'>
               <div class='col'>
                  <p class='mb-2'><b class='me-2'>Nome:</b>".$funcionario["nome"]." ".$funcionario["sobrenome"]."</p>
                  <p class='mb-4'><b class='me-2'>cargo:</b>$cargo</p>
                  <p class='mb-2'><b class='me-2'>CPF:</b>$cpf</p>
                  <p class='mb-2'><b class='me-2'>Nascido em:</b>$data_nasc</p>
                  <p class='mb-2'><b class='me-2'>Estado civil:</b>$estado_civil</p>
               </div>
               <div class='col-3'>
                  <img class='img-fluid' src='".$funcionario["foto"]."' alt='".$funcionario["nome"]." ".$funcionario["sobrenome"]."'>
               </div>
            </div>
            <h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Contato</h3>
            <div class='row mb-5'>
               <div class='col-5'>
                  <p class='mb-2'><b class='me-2'>Telefone:</b>$telefone</p>
                  <p class='mb-2'><b class='me-2'>Celular:</b>$celular</p>
               </div>
               <div class='col'>
                  <p><b class='me-2'>Email:</b>".$funcionario["email"]."</p>
               </div>
            </div>
            <h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Endereço</h3>
            <div class='row mb-5'>
               <div class='col-3'>
                  <p class='mb-2'><b class='me-2'>CEP:</b>".$funcionario["cep"]."</p>
               </div>
               <div class='col'>
                  <p class='mb-2'><b class='me-2'>Complemento:</b>".$funcionario["complemento"]."</p>
               </div>
               <p class='mb-2'><b class='me-2'>Endereço:</b>$endereco</p>
            </div>
            <h3 class='text-center text-white border-bottom-pink mb-4 pb-3'>Login</h3>
            <div class='row mb-5'>
               <div class='col'>
                  <p><b class='me-2'>Nome de usuário:</b>".$funcionario["login"]."</p>
               </div>
               <div class='col'>
                  <p><b class='me-2'>Senha:</b>".$funcionario["senha_original"]."</p>
               </div>
            </div>
         </div>
         <a href='register_funcionario.php?cod=".$funcionario["cod_funcionario"]."' class='alterar-empresa text-decoration-none d-flex align-items-center justify-content-center' text='alterar'>
            <i class='bi bi-pen text-white fs-2'></i>
         </a>
         <button type='button' class='excluir-empresa text-decoration-none d-flex align-items-center justify-content-center border-0' text='excluir' data-bs-toggle='modal' data-bs-target='#delete'>
				<i class='bi bi-trash text-white fs-2'></i>
			</button>
      ";
   }

   if(isset($funcionario)):

		if(isset($_POST["close"]))
			unset($delete_err);
?>
   <div class="modal fade <?php
										if(isset($delete_err)&&!isset($_POST["close"]))
											echo "show";
									?>"
	id="delete"
	style="<?php
				if(isset($delete_err)&&!isset($_POST["close"]))
					echo "display: block;";
				else
					echo "display: none;";
			?>">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<header class="modal-header pe-4">
					<h4 class="modal-title">Excluir funcionário</h4>
					<form action="" method="post">
						<button type="submit" class="btn-close" data-bs-dismiss="modal" name="close"></button>
					</form>
				</header>
				<div class="modal-body text-center pb-3 pt-4">
					<p class="text-center">Preencha o campo com a chave de acesso</p>
					<form action="" method="post" id="form-delete">
						<input type="password" name="delete" class="form-control">
						<span class="text-danger"><?php if(isset($delete_err)) echo $delete_err; ?></span>
					</form>
				</div>
				<footer class="modal-footer">
					<button type="submit" class="col btn btn-outline-primary" form="form-delete" name="submit">Confirmar</button>
					<form action="" method="post">
						<button class="col btn btn-outline-danger" data-bs-dismiss="modal" name="close">Cancelar</button>
					</form>
				</footer>
			</div>
		</div>
	</div>
	<script src="../scripts/main.js?4"></script>
<?php
   endif;

   include("../head-foot/footer_empresa.php");

   $mysqli->close();
?>