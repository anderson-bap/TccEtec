<?php
    if(isset($_FILES["foto"])){
        $file=$_FILES["foto"];
  
        if($file["error"])
            $foto_err="Falha ao enviar o arquivo";
            
        if($file["size"]>5242880)
            $foto_err="Arquivo muito grande. Max: 5MB";
        
        $new_name=uniqid();
        $original_name=$file["name"];
        $extension=strtolower(pathinfo($original_name,PATHINFO_EXTENSION));
        if($extension!="jpg"&&$extension!="jpeg"&&$extension!="png"&&$extension!="jfif")
            $foto_err="Tipo de arquivo não aceito";

        $extension="png";

        $path=$dir.$new_name.".".$extension;
        
    }
?>