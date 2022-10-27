<?php
    if(isset($_FILES["foto"])){
        $file=$_FILES["foto"];
  
        if(!isset($empty_foto)&&$file["error"])
            $foto_err="Falha ao enviar o arquivo";
            
        if($file["size"]>5242880)
            $foto_err="Arquivo muito grande. Max: 5MB";
        
        $new_name=uniqid();
        $original_name=$file["name"];
        $extension=strtolower(pathinfo($original_name,PATHINFO_EXTENSION));

        if(isset($empty_foto)){
            if($file["name"]!=""){
                if($extension!="jpg"&&$extension!="jpeg"&&$extension!="png"&&$extension!="jfif")
                    $foto_err="Tipo de arquivo não aceito";
                else{
                    $extension="png";
                    $path=$dir.$new_name.".".$extension;
                }
            }else
                $path=$cliente["foto"];
        }else{
            if($extension!="jpg"&&$extension!="jpeg"&&$extension!="png"&&$extension!="jfif")
                $foto_err="Tipo de arquivo não aceito";
            else{
                $extension="png";
                $path=$dir.$new_name.".".$extension;
            }
        }
    }
?>