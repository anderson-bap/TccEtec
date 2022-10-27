<?php
    $senha=trim($_POST["senha"]);

    if(empty($senha))
        $senha_err="Preencha o campo";     
    else{
        $uppercase=preg_match('/[A-ZÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜ]/', $senha);
        $lowercase=preg_match('/[a-zàáâãäçèéêìíîòóôõùúûü]/', $senha);
        $number=preg_match('$[0-9]$', $senha);
        $special_char=preg_match('$[/?;:.,<>\|}{`~=+-_()*&¨%#@!\'"]$', $senha);
        if(!$uppercase||!$lowercase||!$number||!$special_char||strlen($senha)<8)
            $senha_err="Senha inválida";
        else
            $senha=$senha;
    }
    
    $confirm_senha=trim($_POST["confirm_senha"]);

    if(empty($confirm_senha))
        $confirm_senha_err="Confirme a senha";     
    else{
        $confirm_senha=$confirm_senha;
        if(empty($senha_err) && ($senha != $confirm_senha))
            $confirm_senha_err="A senha não corresponde";
    }
?>