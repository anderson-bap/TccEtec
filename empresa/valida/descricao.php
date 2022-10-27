<?php
    $descricao=trim($_POST["descricao"]);

    if(empty($descricao))
        $descricao_err="Preencha o campo";
    else{
        if($tipo_descricao){
            if(preg_match('$[^a-zA-ZÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜàáâãäçèéêìíîòóôõùúûü0-9,./:ªº;
            ]-()$', $descricao))
                $descricao_err="Preencha o campo somente com letras, espaços e números";
        }else{
            if(preg_match('/[^a-zA-Z.,ÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜàáâãäçèéêìíîòóôõùúûü ]/', $descricao))
                $descricao_err="Preencha o campo somente com letras, espaços e números";
        }
    }
?>