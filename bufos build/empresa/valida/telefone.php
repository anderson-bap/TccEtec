<?php
    function valida_tel($tel){
        if(empty($tel))
            return 0;
        else{
            $tel=str_replace("-", "", $tel);
            $tel=str_replace("(", "", $tel);
            $tel=str_replace(")", "", $tel);
            $tel=str_replace("_", "", $tel);
            $tel=str_replace(" ", "", $tel);
            $tel=str_replace("+", "", $tel);
            
            $inicio=substr($tel, 0, 2);

            $ddd=array("11","12","13","14","15","16","17","18","19","21","22","24","27","28","31","32","33","34","35","37","38","41","42","43","44","45","46","47","48","49","51","53","54","55","61","62","63","64","65","66","67","68","69","71","73","74","75","77","79","81","82","83","84","85","86","87","88","89","91","92","93","94","95","96","97","98","99");
            
            $tel=trim($tel);
            
            if(in_array($inicio, $ddd)){
                if(strlen($tel)==10)
                    return $tel;
                else
                    return 1;
            }else
                return 2;
        }
    }

    function valida_cel($cel){
        if(empty($cel))
            return 0;
        else{
            $cel=str_replace("-", "", $cel);
            $cel=str_replace("(", "", $cel);
            $cel=str_replace(")", "", $cel);
            $cel=str_replace("_", "", $cel);
            $cel=str_replace(" ", "", $cel);
            $cel=str_replace("+", "", $cel);
            
            $inicio=substr($cel, 0, 2);
            $valida_cel=substr($cel,2,1);
            $cel_real=array("9","8","7","6","5","4");

            $ddd=array("11","12","13","14","15","16","17","18","19","21","22","24","27","28","31","32","33","34","35","37","38","41","42","43","44","45","46","47","48","49","51","53","54","55","61","62","63","64","65","66","67","68","69","71","73","74","75","77","79","81","82","83","84","85","86","87","88","89","91","92","93","94","95","96","97","98","99");

            
            if(strlen($cel)==10){ 
                $fim=substr($cel, 2, 11);
                $cel=$inicio.'9'.$fim;
            }
            
            $cel=trim($cel);
        
            if(in_array($inicio, $ddd)){
                if(strlen($cel)==11&&in_array($valida_cel, $cel_real))
                    return $cel;
                else
                    return 1;
            }else
                return 2;
        }
    }

    $telefone=valida_tel($_POST["telefone"]);
    $celular=valida_cel($_POST["celular"]);

    if($telefone==0)
        $telefone=null;
    elseif($telefone==1)
        $telefone_err="Telefone inválido";
    elseif($telefone==2)
        $telefone_err="DDD inválido";
    else
        $telefone_err="";

    if($celular==0)
        $celular=null;
    elseif($celular==1)
        $celular_err="Celular inválido";
    elseif($celular==2)
        $celular_err="DDD inválido";
    else
        $celular_err="";

    if($telefone==0&&$celular==0){
        $telefone_err="Preencha o campo";
        $celular_err="Preencha o campo";
    }
?>