<?php
    $login=trim($_POST["login"]);

    if(empty($login))
        $login_err="Preencha com seu login";
    elseif(preg_match('/[^a-zA-Z0-9_@ ]/', $login))
        $login_err="O login pode conter apenas letras, espeços em branco, _ e @";
?>