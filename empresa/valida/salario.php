<?php
    $salario=trim($_POST["salario"]);

    if(strlen($salario)<4)
        $salario_err="O campo deve conter 4 dígitos";
    elseif(intval($salario)<1212)
        $salario_err="O salário não deve ser inferior a R$1212,00";
    
    if(preg_match('/[^0-9]/', $salario))
        $salario_err="O campo deve conter apenas números";
?>