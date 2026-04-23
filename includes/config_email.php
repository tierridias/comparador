<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../lib/PHPMailer/Exception.php';
require '../lib/PHPMailer/PHPMailer.php';
require '../lib/PHPMailer/SMTP.php';

function enviarEmail($destinatario, $assunto, $mensagem) {
    $mail = new PHPMailer(true);
    //$mail->SMTPDebug = 2; // Ativa a saída detalhada de erros

    try {
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Port       = 2525;
        $mail->Username   = '814afe85cfb1c8';
        $mail->Password   = 'fa1fafc26b763f';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->setFrom('sistema@comparador.com', 'Comparador PAP');
        $mail->addAddress($destinatario);

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $mensagem;
        $mail->CharSet = 'UTF-8';

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Para debug, se falhar, podes descomentar a linha abaixo:
        // echo "Erro: {$mail->ErrorInfo}"; 
        return false;
    }
}