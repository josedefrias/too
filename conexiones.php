<?php

require("class.phpmailer.php");

function conexion_hit(){
	//Conexion
	/*$conexion = new mysqli(
		'localhost',
		'hit',
		'hit',
		'hit'
	);*/

	$conexion = new mysqli(
		'bikefriendly.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com',
		'hit',
		'bikef2015',
		'hit'
	);

	if ($conexion->errno != 0){
		echo('Error en la conexion.');
		exit();
	}
	/*else{
		echo('La conexion se ha realizado correctamente.Ñ');
	}*/
		//printf("<br/>");
return $conexion;
}

function enviar_mail($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){

	$email = new PHPMailer();
	$email->From = "info@Bikefriendly.es";
	$email->FromName = "Bikefriendly System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAddress($direccion_correo);
	$email->AddAddress('info@Bikefriendly.es');
	//$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

return $respuesta;
}

function enviar_mail_reservas($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){

	

	$email = new PHPMailer();
	$email->From = "info@Bikefriendly.es";
	$email->FromName = "Bikefriendly System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAddress($direccion_correo);
	$email->AddAddress('info@Bikefriendly.es');
	//$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

return $respuesta;
}

function enviar_mail_hotel($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){

	$email = new PHPMailer();
	$email->From = "info@Bikefriendly.es";
	$email->FromName = "Bikefriendly System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAddress($direccion_correo);
	$email->AddAddress('info@Bikefriendly.es');
	//$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

return $respuesta;
}


function enviar_mail_seguro_opcional($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario){

	$email = new PHPMailer();
	$email->From = "info@Bikefriendly.es";
	$email->FromName = "Bikefriendly System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	//$email->AddAddress($direccion_correo);
	$email->AddAddress('info@Bikefriendly.es');
	//$email->IsHTML(true);

	//$email->Send();
	
	if(!$email->Send()){
	   $respuesta = "NO";
	}else{
	   $respuesta = "OK";
	}

return $respuesta;
}


?>