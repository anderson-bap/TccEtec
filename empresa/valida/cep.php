<?php
    function valida_cep($cep_num){
        if(!empty($cep_num)){
            $cep_num = preg_replace("/[^0-9]/", "", $cep_num);

            if (strlen($cep_num) != 8)
                return false;
    
            $url = "http://viacep.com.br/ws/$cep_num/xml/";
            $xml = simplexml_load_file($url);
    
            if($xml->erro)
                return false;
            else
                return $xml;
        }else
            return 2;
    }

    $cep=valida_cep($_POST["cep"]);

    if(!$cep)
        $cep_err="Cep inválido";
    elseif($cep==2)
        $cep_err="Preencha o campo";
    elseif($cep){
        $logradouro=$bairro=$localidade=$uf="";

        if($cep->logradouro!="")
        $logradouro="$cep->logradouro, ";
        if($cep->bairro!="")
            $bairro="$cep->bairro, ";
        if($cep->localidade!="")
            $localidade="$cep->localidade, ";
        if($cep->uf!="")
            $uf=$cep->uf;

        $_SESSION["endereco"]=$logradouro.$bairro.$localidade.$uf;
        $cep=$cep->cep;
    }
?>