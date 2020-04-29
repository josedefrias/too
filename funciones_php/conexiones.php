<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

function conexion_hit(){
	//Conexion
	/*$conexion = new mysqli(
		'localhost',
		'too',
		'too',
		'too'
	);*/

	/*$conexion = new mysqli(
		'testdb.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com',
		'hit',
		'dacasale',
		'too'
	);*/

	$conexion = new mysqli(
		'mysql-too',
		'too',
		'too',
		'too'
	);

	if ($conexion->errno != 0){
		echo('Error en la conexion.');
		exit();
	}
	/*else{
		echo('La conexion se ha realizado correctamente.');
	}*/
		//printf("<br/>");
return $conexion;
}

function enviar_mail($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){

	$email = new PHPMailer();
	$email->From = "josefdefrias@gmail.com";
	$email->FromName = "Toogethere System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAddress($direccion_correo);
	$email->AddAddress('josedefrias@ipsotravel.com');

	//$email->IsHTML(true);

	$email->Send();
	
	/*if(!$email->Send()){
	   $respuesta = "No se pudo enviar el Mensaje.";
	}else{
	   $respuesta = "Mensaje enviado";
	}
	echo $email->ErrorInfo;*/

//return $respuesta;
}

function enviar_mail_reservas($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){

	$email = new PHPMailer();
	$email->From = "josefdefrias@gmail.com";
	//$email->From = "jfrias@panavision-tours.es";
	$email->FromName = "Toogethere System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAddress($direccion_correo);
	$email->AddAddress('josedefrias@ipsotravel.com');
	//$email->AddAddress('info@hitravel.es');
	//$email->AddAddress('carmenfernandez@hitravel.es');
	//$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

return $respuesta;
}

function reenviar_mail_reservas($asunto, $mensaje_html, $direccion_correo, $mail_usuario, $nombre_destinatario){

	$email = new PHPMailer();


		//CON ESTO FUNCIONA
		/*$email->IsSMTP();
		$email->SMTPAuth = true;
		$email->SMTPSecure = "ssl";
		$email->Host = "smtp.gmail.com";
		$email->Port = 465;
		$email->Username = 'josefdefrias@gmail.com';
		$email->From = "josefdefrias@gmail.com";
		$email->Password = "dacasale"; */



	$email->From = "josefdefrias@gmail.com";
	//$email->From = "jfrias@panavision-tours.es";
	$email->FromName = "Toogethere System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAttachment('documentos/seguros/Seguros_pasajeros.xlsx','excel_de_prueba.xlsx');
	//$email->AddAddress($direccion_correo);
	//$email->AddAddress($mail_usuario);
	$email->AddAddress('josedefrias@ipsotravel.com');

	//$email->AddAddress('info@hitravel.es');
	//$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

return $respuesta;
}

function enviar_mail_hotel($asunto, $mensaje_html, $direccion_correo, $mail_usuario, $nombre_destinatario){

	$email = new PHPMailer();
	$email->From = "josefdefrias@gmail.com";
	//$email->From = "jfrias@panavision-tours.es";
	$email->FromName = "Toogethere System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAddress($direccion_correo);
	//$email->AddAddress($mail_usuario);
	$email->AddAddress('josedefrias@ipsotravel.com');
	//$email->AddAddress('ivanmendez@hitravel.es');
	//$email->AddAddress('javierdefrias@hitravel.es');
	//$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

return $respuesta;
}

function enviar_mail_facturas($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){

	$email = new PHPMailer();
	$email->From = "josefdefrias@gmail.com";
	//$email->From = "jfrias@panavision-tours.es";
	$email->FromName = "Toogethere System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAddress($direccion_correo);
	$email->AddAddress('josedefrias@ipsotravel.com');
	//$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

return $respuesta;
}

function enviar_mail_aviso_cobros($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){

	$email = new PHPMailer();
	$email->From = "josefdefrias@gmail.com";
	$email->FromName = "Toogethere System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAddress($direccion_correo);
	$email->AddAddress('josedefrias@ipsotravel.com');
	//$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

return $respuesta;
}

function enviar_mail_resumen_aseguradora($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){

	$email = new PHPMailer();
	$email->From = "josefdefrias@gmail.com";
	//$email->From = "jfrias@panavision-tours.es";
	$email->FromName = "Toogethere System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	$email->AddAttachment('documentos/seguros/Hitravel_Resumen_Pasajeros.xlsx');
	//$email->AddAddress($direccion_correo);
	$email->AddAddress('josedefrias@ipsotravel.com');
	//$email->AddAddress('ivanmendez@hitravel.es');
	//$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

	return $respuesta;
}


function enviar_mail_oferta($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){




	$email = new PHPMailer();
	$email->From = "josefdefrias@gmail.com";
	//$email->From = "jfrias@panavision-tours.es";
	$email->FromName = "Newsletter Toogether";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAttachment('documentos/seguros/Hitravel_Resumen_Pasajeros.xlsx');
	$email->AddAddress($direccion_correo);
	$email->AddAddress('josedefrias@ipsotravel.com');

	$email->IsHTML(true);

	//echo($asunto.'-'.$mensaje_html.'-'.$direccion_correo);
	//$email->Send();
	
	if(!$email->Send()){
		echo('No enviado');
	   $respuesta = "NO";
	}else{
		echo('Enviado');
	   $respuesta = "OK";
	}

	return $respuesta;
}

function enviar_multienvio($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){

	$email = new PHPMailer();
	$email->From = "josefdefrias@gmail.com";
	$email->FromName = "Newsletter Toogethere";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAttachment('documentos/seguros/Hitravel_Resumen_Pasajeros.xlsx');
	//$email->AddAddress($direccion_correo);


	$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

	return $respuesta;
}

?>