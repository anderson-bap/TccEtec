<?php

require 'includes/Exception.php';
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

//Função do email que o contato irá mandar para o cliente ao criar a conta e ao colocar seu email no rodapé e ao comprar um produto.
function sendEmailContato($email,$msg){
    $mail = new PHPMailer();
    $mail->isSMTP();
    //Seta o email como uft-8
    $mail->CharSet = 'UTF-8';
    //Seta o encode do phpMailer como 8bits
    $mail->Encoding = 'base64';
    
    // Email que será usado para enviar o Email
    $mail->Username = "contato@bufosregulares.com";
    $mail->Password = "####";
    $mail->setFrom("contato@bufosregulares.com");
    
    
    // Titulo do email
    $mail->Subject = "noreply";

        //Caso o "$msg" venha vazio, o e-mail irá enviar uma mensagem ja pré feita.
        if(empty($msg)){
            $msg="<h1>Parabens você acaba de participar do TCC Bufos Build!</h1></br><h2>Não perca as novas novidades!!</h2>";
        }
        $mail->isHTML(true);
        // Corpo do email
        $mail->Body = $msg;
        // Quem receberá o email
         if(!empty($email) && $email != "suporte@bufosregulares.com" && $email != "contato@bufosregulares.com"){
        $mail->addAddress($email);
         }

        if($mail -> Send())
            return true;
        else
            return false;
}



// Função que irá envolver todos os processos de suporte da empressa.
function sendSuporte($titulo,$email,$msg){
    $suporteBack = new PHPMailer();
    $suporteBack->isSMTP();
    //Seta o email como uft-8
    $suporteBack->CharSet = 'UTF-8';
    //Seta o encode do phpMailer como 8bits
    $suporteBack->Encoding = 'base64';

    // Email que será usado para enviar o Email
    $suporteBack->Username = "suporte@bufosregulares.com";
    $suporteBack->Password = "####";
    $suporteBack->setFrom("suporte@bufosregulares.com");
     
    // Titulo do email
    $suporteBack->Subject = "$email / ".$titulo;

    $suporteBack->isHTML(true);
    // Corpo do email
    $suporteBack->Body = "<p>$msg</p>";
    // Quem receberá o email
    $suporteBack->addAddress("suporte@bufosregulares.com");


    if( $suporteBack -> Send() ){
        
        $suporteClient = new PHPMailer();
            $suporteClient->isSMTP();
            //Seta o email como uft-8
            $suporteClient->CharSet = 'UTF-8';
            //Seta o encode do phpMailer como 8bits
            $suporteClient->Encoding = 'base64';

            // Email que será usado para enviar o Email
            $suporteClient->Username = "suporte@bufosregulares.com";
            $suporteClient->Password = "####";
            $suporteClient->setFrom("suporte@bufosregulares.com");

            // Titulo do email
            $suporteClient->Subject = "noreply";

            $suporteClient->isHTML(true);
            // Corpo do email
            $suporteClient->Body = "<h1>Nós da Bufos Build ficamos muito grados com o seu contato.</h1>";
            // Quem receberá o email
            $suporteClient->addAddress($email);
            
            $suporteClient -> Send();

    }else{
        echo "Erro Ao Enviar Suporte Email: {$suporteBack->ErrorInfo}";
    }

}

// Chamando a função automatica e colocando a pessoa que irá receber.
    //sendEmailContato("andersonbaptistadesousa@gmail.com","");

// Chamando a função do suporte e colocando o email de quem está enviando o ticket de suporte e a mensagem + titulo.
    // sendSuporte("Cara do Email Ruim","andersonbaptistadesousa@gmail.com","O cara do email fez merda ai amigão");
?>