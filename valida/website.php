<?php
    $website=trim($_POST["website"]);
    if(empty($website))
        $website_err="Preencha o campo";
    else{
        $website=filter_var($website, FILTER_SANITIZE_URL);

        if(!preg_match("#https://www.#",$website)&&!preg_match("#http://www.#",$website))
            $website="https://www.".$website;
        elseif(!preg_match("#https://#",$website)&&!preg_match("#http://#",$website))
            $website="https://".$website;

        if(!filter_var($website, FILTER_VALIDATE_URL))
            $website_err="URL inválido";
    }
?>