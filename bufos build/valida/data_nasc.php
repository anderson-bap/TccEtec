<?php
    $data_nasc=$_POST["data_nasc"];
    $year=strtotime("-18 Years");    
    if(!empty($data_nasc)){

        if(date('Y', strtotime($data_nasc))>date("Y", $year))
            $data_nasc_err="Data inválida";
            
    }else
        $data_nasc_err="Preencha a data de nascimento";
?>