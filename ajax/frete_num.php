<?php
    if(strlen($_GET["cep"])==9){
        include("../config.php");

        $cep_origem="08253000";
        $cep_destino=$_GET['cep'];
    
        if(preg_match("$-$",$cep_destino))
            $cep_destino=str_replace("-","",$cep_destino);
    
        $sql="SELECT * FROM produtos WHERE cod_produto=".$_GET["produto"];
        $result=$mysqli->query($sql);
        $produto=$result->fetch_assoc();
    
        $peso=$produto["peso"];
        $valor=$produto["preco_revenda"];
        $tipo_do_frete='40010';
        $altura=$produto["altura"];
        $largura=$produto["largura"];
        $comprimento=$produto["comprimento"];
        
        if(intval($comprimento)<16)
            $comprimento=16;
    
        if(intval($largura)<11)
            $largura=11;
            
        if(intval($altura)<2)
            $altura=2;
            
        $xml=simplexml_load_file("http://viacep.com.br/ws/".$_GET['cep']."/xml/");

        $logradouro=$bairro=$localidade=$uf="";
    
        if(!$xml->erro){
            $url="http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
            $url.="nCdEmpresa=";
            $url.="&sDsSenha=";
            $url.="&sCepOrigem=".$cep_origem;
            $url.="&sCepDestino=".$cep_destino;
            $url.="&nVlPeso=".$peso;
            $url.="&nVlLargura=".$largura;
            $url.="&nVlAltura=".$altura;
            $url.="&nCdFormato=1";
            $url.="&nVlComprimento=".$comprimento;
            $url.="&sCdMaoProria=n";
            $url.="&nVlValorDeclarado=".$valor;
            $url.="&sCdAvisoRecebimento=n";
            $url.="&nCdServico=".$tipo_do_frete;
            $url.="&nVlDiametro=0";
            $url.="&StrRetorno=xml";
            $xml=simplexml_load_file($url);
        
            $frete=$xml->cServico;
            
            echo $frete->Valor;
        }
    }
?>