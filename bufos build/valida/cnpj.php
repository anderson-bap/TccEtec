<?php
    function valida_cnpj($cnpj_num){
        global $cod,$table,$check_cnpj;
        if(empty($_POST["cnpj"]))
            return 0;
        else{
            include ("../config.php");
                
            $cnpj_num=preg_replace('/[^0-9]/','',(string)$cnpj_num);


            if(strlen($cnpj_num)!=14)
                return 1;

            if(preg_match('/(\d)\1{13}/',$cnpj_num))
                return 1;	

            for($i=0,$j=5,$soma=0;$i<12;$i++){
                $soma+=$cnpj_num[$i]*$j;
                $j=($j==2)?9:$j-1;
            }

            $resto=$soma % 11;

            if($cnpj_num[12]!=($resto<2? 0:11-$resto))
                return 1;

            for($i=0,$j=6,$soma=0;$i<13;$i++){
                $soma+=$cnpj_num[$i]*$j;
                $j=($j==2)?9:$j-1;
            }

            $resto=$soma%11;

            if($check_cnpj){
                $sql = "SELECT $cod FROM $table WHERE cnpj = ?";

                if($stmt = $mysqli->prepare($sql)){

                    $stmt->bind_param("s", $param_cnpj);
                    
                    $param_cnpj = $cnpj_num;
                    
                    if($stmt->execute()){
                        $stmt->store_result();
                        
                        if($stmt->num_rows>0)
                            return 2;
                    }
                    else
                        echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";


                    $stmt->close();
                }
            }

            $seg_dig=$cnpj_num[13]==($resto<2?0:11-$resto);
            if($seg_dig)
                return $cnpj_num;
        }
    }

    $cnpj=valida_cnpj($_POST["cnpj"]);

    if($cnpj==0)
        $cnpj_err="Preencha o campo";
    elseif($cnpj==1)
        $cnpj_err="Cnpj inválido";
    elseif($cnpj==2)
        $cnpj_err="Este cnpj já está em uso";
?>