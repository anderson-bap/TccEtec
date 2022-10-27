<?php
	$cep=$_REQUEST["cep"];
	if(strlen($cep)==9){
		$cep = preg_replace("/[^0-9]/", "", $cep);

		$url = "http://viacep.com.br/ws/$cep/xml/";
		$xml = simplexml_load_file($url);

		$logradouro=$bairro=$localidade=$uf="";

		if(!$xml->erro){
			if($xml->logradouro!="")
					$logradouro="$xml->logradouro, ";
			if($xml->bairro!="")
					$bairro="$xml->bairro, ";
			if($xml->localidade!="")
					$localidade="$xml->localidade, ";
			if($xml->uf!="")
					$uf=$xml->uf;
		}
			echo $logradouro.$bairro.$localidade.$uf;
	}
?>