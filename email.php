<?php
if(!isset($EMAIL)) { die; }
require("phpmailer/PHPMailerAutoload.php");

function sendEmail($to, $toName, $subject, $body)
{
	$username = "septica.noreply@gmail.com";
	$password = "p@ssword1";

	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->SMTPAuth = true; 
	$mail->Username = $username;
	$mail->Password = $password;
	$mail->SMTPSecure = 'tls';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;

	$mail->setFrom($username, 'Septica NO REPLY');
	$mail->addAddress($to, $toName);
	$mail->isHTML(true);

	$mail->Subject = $subject;
	$mail->Body    = $body;
	$mail->AltBody = str_replace("<br/>", "\n", $body);

	if(!$mail->send()) 
	{
	    error_log( 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
		return 0;
	} 
	else 
	{
		return 1;
	}
}
?>