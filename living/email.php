<?php
require '../../phpMyAdmin/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

function welcomeEmail($mailTo, $name){
    $mail = new PHPMailer();
    $mail->CharSet = 'utf-8';
    $mail->IsSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "";
    $mail->Password = "";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->AddAddress($mailTo);
    $mail->SMTPDebug  = 1;   
    $mail->isHTML(true);                                  
    $mail->Subject = 'Welcome to living, more than a network, it\'s a way of life ';
    $mail->Body    = "Welcome $name,\n\n this is a site for you: meet new people, connect old friends and enjoy you life.";
    $mail->AltBody = "Welcome $name,\n\n this is a site for you: meet new people, connect old friends and enjoy you life.";


    if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
    echo 'Message has been sent';
    }
}

function recoveryEmail($mailTo, $name, $code){
    $mail = new PHPMailer();
    $mail->CharSet = 'utf-8';
    $mail->IsSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "";
    $mail->Password = "";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->AddAddress($mailTo);
    $mail->SMTPDebug  = 1;   
    $mail->isHTML(true);                                  
    $mail->Subject = 'Recovery code-living, more than a network, it\'s a way of life ';
    $mail->Body    = "Hello $name,\n\n this is the recovery code:\n $code";
    $mail->AltBody = "Hello $name,\n\n this is the recovery code:\n $code";

    if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
    echo 'Message has been sent';
    }
}

function validateEmail($str) {
    if (empty($str)){
        return(false);
    }else{
        $matches = null;
        return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
    }
}


?>
