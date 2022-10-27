<?php
    $nome=trim($_POST["nome"]);
    if(empty($nome)){
        $nome_err="Preencha o campo";
    }else{
        if($tipo_nome){
            if(preg_match("/[^a-zA-Z.ÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜàáâãäçèéêìíîòóôõùúûü ]/", $nome))
                $nome_err="O nome pode conter apenas letras e espaços em branco";
        }else{
            if(preg_match('/[^a-zA-Z0-9ÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜàáâãäçèéêìíîòóôõùúûü ]/', $nome))
                $nome_err="O nome pode conter apenas letras e espaços em branco";
        }
    }

    if(!$tipo_nome){
        $razao_social=trim($_POST["razao_social"]);
        
        if(empty($razao_social))
            $razao_social_err="Preencha o campo";
        elseif(preg_match('/[^a-zA-Z0-9.ÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜàáâãäçèéêìíîòóôõùúûü ]/', $razao_social))
            $razao_social_err="A razão social pode conter apenas letras e espaços em branco";
    }
?>