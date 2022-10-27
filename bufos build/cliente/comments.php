<?php
    $titulo="- Seus comentários";
    include("../head-foot/header.php");
    
    if(!isset($_SESSION["cod_cliente"])){
        echo "
            <script>
                window.location.assign('https://bufosregulares.com/login.php');
            </script>
        ";
    }
?>
    <h2 class="text-center white-dark mb-5">Seus comentários</h2>
<?php
    $sql="SELECT * FROM comentarios WHERE cod_cliente='".$_SESSION["cod_cliente"]."'  ORDER BY `comentarios`.`cod_comentario` DESC";
    $result_comment=$mysqli->query($sql);
    
    if($result_comment->num_rows==0){
        echo "<p class='text-center white-dark' style='margin-block: 8rem'>Você ainda não comentou em nenhum produto</p>";
    }else{
        echo "<div class='container d-flex flex-column comments mb-5'>";
        while($comentario=$result_comment->fetch_assoc()):
            
            $sobrenome=$cliente["sobrenome"];
            $sobrenome=explode(" ",$sobrenome);
            $sobrenome=$sobrenome[0];
	        
	        echo "
              <a href='../produto.php?cod=".$comentario["cod_produto"]."#form-comment' class='comment mb-4 text-decoration-none'>
                 <div class='d-flex align-items-center mb-2'>
                    <img src='$foto' alt='".$cliente["nome"]." ".$cliente["sobrenome"]."'>
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
              </a>
    	        ";
            endwhile;
        echo "</div>";
        }
    
    include("../head-foot/footer.php");
?>