<?php
    $titulo=trim($_POST["titulo"]);

    if(empty($titulo))
        $titulo_err="Preencha o campo";
    elseif(strlen($titulo)>60)
        $titulo_err="O campo não deve conter mais que 60 caracteres";
    elseif(preg_match('$[^a-zA-ZÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜàáâãäçèéêìíîòóôõùúûü0-9,./:ªº;
    ]-()$',$titulo))
        $titulo_err="O campo deve conter apenas letras, números e espaços";
    

    $linha=trim($_POST["linha"]);

    if(empty($linha))
        $linha_err="Preencha o campo";
    elseif(preg_match('$[^a-zA-ZÀÁÂÃÇÈÉÊÌÍÎÒÓÔÕÙÚÛÜàáâãäçèéêìíîòóôõùúûü0-9,./:ªº;
    ]-()$',$linha))
        $linha_err="O campo deve conter apenas letras, números e espaços";

    $preco_custo=trim($_POST["preco_custo"]);

    if(empty($preco_custo))
        $preco_custo_err="Preencha o campo";
    elseif(preg_match('/[^0-9]/', $preco_custo))
        $preco_custo_err="O campo deve conter apenas números";
    elseif(floatval($preco_custo)==0)
        $preco_custo_err="O preço não pode ser R$0,00";

    $preco_revenda=trim($_POST["preco_revenda"]);

    if(empty($preco_revenda))
        $preco_revenda_err="Preencha o campo";
    elseif(preg_match('/[^0-9]/', $preco_revenda))
        $preco_revenda_err="O campo deve conter apenas números";
    elseif(floatval($preco_revenda)==0)
        $preco_revenda_err="O preço não pode ser R\$0,00";
    elseif(floatval($preco_revenda)<floatval($preco_custo))
        $preco_revenda_err="O preço de revenda não pode ser menor que o preço de custo";
    
    $quantidade=trim($_POST["quantidade"]);

    if(empty($quantidade))
        $quantidade_err="Preencha o campo";
    elseif(strlen($quantidade)>5)
        $quantidade_err="O campo deve ter no máximo 5 digitos";
    elseif(floatval($quantidade)==0)
        $quantidade_err="Coloque pelo menos uma unidade";

    $altura=trim($_POST["altura"]);

    if(empty($altura))
        $altura_err="Preencha o campo";
    elseif(strlen($altura)>4)
        $altura_err="O campo deve ter no máximo 4 digitos";
    elseif(floatval($altura)==0)
        $altura_err="Coloque pelo menos uma unidade";
    elseif(preg_match("/[^0-9.,]/",$altura))
        $altura_err="O campo deve conter apenas números";
    if(preg_match("/,/",$altura))
        str_replace(",",".",$altura);

    $comprimento=trim($_POST["comprimento"]);

    if(empty($comprimento))
        $comprimento_err="Preencha o campo";
    elseif(strlen($comprimento)>5)
        $comprimento_err="O campo deve ter no máximo 5 digitos";
    elseif(floatval($comprimento)==0)
        $comprimento_err="Coloque pelo menos uma unidade";
    elseif(preg_match("/[^0-9.,]/",$comprimento))
        $comprimento_err="O campo deve conter apenas números";
    if(preg_match("/,/",$comprimento))
        str_replace(",",".",$comprimento);

    $largura=trim($_POST["largura"]);

    if(empty($largura))
        $largura_err="Preencha o campo";
    elseif(strlen($largura)>5)
        $largura_err="O campo deve ter no máximo 5 digitos";
    elseif(floatval($largura)==0)
        $largura_err="Coloque pelo menos uma unidade";
    elseif(preg_match("/[^0-9.,]/",$largura))
        $largura_err="O campo deve conter apenas números";
    if(preg_match("/,/",$largura))
        str_replace(",",".",$largura);

    $peso=trim($_POST["peso"]);
    
    if(empty($peso))
        $peso_err="Preencha o campo";
    elseif(strlen($peso)>5)
        $peso_err="O campo deve ter no máximo 5 digitos";
    elseif(floatval($peso)==0)
        $peso_err="Coloque pelo menos uma unidade";
    elseif(preg_match("/[^0-9.,]/",$peso))
        $peso_err="O campo deve conter apenas números";
    if(preg_match("/,/",$peso))
        str_replace(",",".",$peso);
?>