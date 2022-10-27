<?php
    $complemento=trim($_POST["complemento"]);

    if(empty($complemento))
        $complemento=null;
    elseif(preg_match('/[^a-zA-Z0-9,.ÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜàáâãäçèéêìíîòóôõùúûü ]/', $complemento))
        $complemento_err="Preencha o campo somente com letras, espaços e números";
?>