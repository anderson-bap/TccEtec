<?php
    function valida_cpf($cpf_num) {
        global $cod,$table,$check_cpf;
        if(empty($cpf_num))
            return 0;
        else{
            include ("../config.php");

            $cpf_num=preg_replace('/[^0-9]/is','',$cpf_num);
            
            if (strlen($cpf_num)!=11)
                return 1;
        
            if (preg_match('/(\d)\1{10}/',$cpf_num))
                return 1;
        
            for($t=9;$t<11;$t++) {
                for($d=0,$c=0;$c<$t;$c++) {
                    $d+=$cpf_num[$c]*(($t+1)-$c);
                }
                $d=((10*$d)%11)%10;
                
                if($cpf_num[$c]!=$d)
                    return 1;
            }

            if($check_cpf){
                $sql = "SELECT $cod FROM $table  WHERE cpf = ?";

                if($stmt = $mysqli->prepare($sql)){

                    $stmt->bind_param("s", $param_cpf);
                    
                    $param_cpf = $cpf_num;
                    
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

            return $cpf_num;
        }
    }

    $cpf=valida_cpf($_POST["cpf"]);

    if($cpf==0)
        $cpf_err="Preencha o campo";
    elseif($cpf==1)
        $cpf_err="Cpf inválido";
    elseif($cpf==2)
        $cpf_err="Este cpf já está em uso";
?>