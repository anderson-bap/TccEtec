<?php
    $email=trim($_POST["email"]);

    if(!empty($email)){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            $email_err="Email inválido";

        if($check_email){
            $sql = "SELECT $cod FROM $table WHERE email = ?";

            if($stmt = $mysqli->prepare($sql)){

                $stmt->bind_param("s", $param_email);
                
                $param_email = $email;
                
                if($stmt->execute()){
                    $stmt->store_result();
                    
                    if($stmt->num_rows>0)
                        $email_err = "Este endereço de email já está em uso";
                }
                else
                    echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";


                $stmt->close();
            }
        }

    }else
        $email_err="Preencha o campo";
?>