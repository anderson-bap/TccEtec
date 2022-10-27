<?php
    function enviar_arquivo($error,$size,$name,$tmp_name){
        include("../config.php");

        global $foto_err,$dir;
  
        if($error)
           $foto_err="Falha ao enviar o arquivo";
  
        if($size>5242880)
           $foto_err="Arquivo muito grande. Max: 5MB";

        $original_name=$name;
        $new_name=uniqid();
        $extension=strtolower(pathinfo($original_name,PATHINFO_EXTENSION));
        
        if($extension!="jpg"&&$extension!="jpeg"&&$extension!="png"&&$extension!="jfif")
           $foto_err="Tipo de arquivo não aceito";

        $extension="png";

        $path=$dir.$new_name.".".$extension;
        
        if($_SESSION["alterar_produto"])
            $mysqli->query("INSERT INTO fotos_temp (path, cod_produto) VALUES ('$path','".$_SESSION["cod"]."')");
        else
            $mysqli->query("INSERT INTO fotos_produto (path, cod_produto) VALUES ('$path','".$_SESSION["cod"]."')");

        $deu_certo=move_uploaded_file($tmp_name,$path);
  
        if($deu_certo){
           return true;
        }else
           return false;
     }

    $files=$_FILES["foto"];

    $tudo_certo=true;

    if(count($files["name"])!=6)
        $foto_err="Carregue 6 fotos";
    else{
        if(isset($_POST["preview"])&&!$_SESSION["alterar_produto"]){
            $sql="SELECT * FROM fotos_produto WHERE cod_produto=?";

            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param("s",$param_cod_produto);
    
                $param_cod_produto=$_SESSION["cod"];
                
                $result=$stmt->execute();
    
                $stmt_result = $stmt->get_result();
    
                if ($stmt_result->num_rows>0){
                    while($fotos = $stmt_result->fetch_assoc()) {
                        if(is_file($fotos["path"]))
                            unlink($fotos["path"]);
                    }
                }
            }
    
            $stmt->close();
    
            $delete="DELETE FROM fotos_produto WHERE cod_produto=".$_SESSION["cod"];
            $mysqli->query($delete);
        }
    
        foreach($files["name"] as $index => $arq){
            $mover_arquivos=enviar_arquivo($files["error"][$index],$files["size"][$index],$files["name"][$index],$files["tmp_name"][$index]);
    
            if(!$mover_arquivos)
                $tudo_certo=false;
        }
    
        if(!$tudo_certo)
            $foto_err="Falha em um ou mais arquivos";
    }
?>