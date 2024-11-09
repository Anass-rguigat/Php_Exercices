<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('config.php');
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

 
$mail_app_pwd = $_ENV['mail_app_pwd'];
//echo $mail_app_pwd;exit;
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 2;                      // Enable verbose debug output
    $mail->isSMTP();                           // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';      // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                  // Enable SMTP authentication
    $mail->Username   = 'anass.randy00@gmail.com';// SMTP username
    $mail->Password   = $mail_app_pwd;       // SMTP password
    $mail->SMTPSecure = 'tls';                 // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                   // TCP port to connect to

    //Recipients
    $mail->setFrom('anass.randy00@gmail.com', 'Anass Randy');
    $mail->addAddress('anass.randy00@gmail.com', 'Anass Randy');    // Add a recipient
    $mail->addReplyTo('anass.randy00@gmail.com', 'Anass Randy');
    $mail->addCC('anass.randy00@gmail.com');
    $mail->addBCC('anass.randy00@gmail.com');

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
