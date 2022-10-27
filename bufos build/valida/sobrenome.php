<?php
    $sobrenome=$_POST["sobrenome"];

    if(empty(trim($sobrenome)))
        $sobrenome_err="Preencha com seu sobrenome";
    elseif(preg_match('/[^a-zA-ZÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜàáâãäçèéêìíîòóôõùúûü ]/', trim($sobrenome)))
        $sobrenome_err="O sobrenome pode conter apenas letras e espaços em branco";
?>