<?php

require("class.phpmailer.php"); 

function conexion_hit(){
	//Conexion
	$conexion = new mysqli(
		'localhost',
		'hit',
		'hit',
		'hit'
	);

	/*$conexion = new mysqli(
		'testdb.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com',
		'hit',
		'dacasale',
		'hit'
	);*/

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
	/*$email->IsSMTP();
	$email->SMTPAuth = true;
	$email->SMTPSecure = "tls";
	$email->Host = "email-smtp.eu-west-1.amazonaws.com";
	$email->Port = 465;
	$email->Username = "AKIAI5XDFYGZO4PMJFUA";
	$email->Password = "As2KaNeIJQACEI+4Ewl6NGw2mo8/X9Y29FX1pZ2Elqg9";*/


	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------CONFIGURAR PHP.INI Y SENDMAIL.INI--------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	//Esto lo evitamos configurando php.ini y sendmail.ini

	//en php.ini se debe poner en la funcion [mail function]
	/*; XAMPP: Comment out this if you want to work with an SMTP Server like Mercury
	SMTP = smtp.gmail.com
	smtp_port = 465

	; For Win32 only.
	; http://php.net/sendmail-from
	sendmail_from = josefdefrias@gmail.com

	; XAMPP IMPORTANT NOTE (1): If XAMPP is installed in a base directory with spaces (e.g. c:\program filesC:\xampp) fakemail and mailtodisk do not work correctly.
	; XAMPP IMPORTANT NOTE (2): In this case please copy the sendmail or mailtodisk folder in your root folder (e.g. C:\sendmail) and use this for sendmail_path.  
	 
	; XAMPP: Comment out this if you want to work with fakemail for forwarding to your mailbox (sendmail.exe in the sendmail folder)
	sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"

	; XAMPP: Comment out this if you want to work with mailToDisk, It writes all mails in the C:\xampp\mailoutput folder
	;sendmail_path = "C:\xampp\mailtodisk\mailtodisk.exe"

	; Force the addition of the specified parameters to be passed as extra parameters
	; to the sendmail binary. These parameters will always replace the value of
	; the 5th parameter to mail(), even in safe mode.
	;mail.force_extra_parameters =

	; Add X-PHP-Originating-Script: that will include uid of the script followed by the filename
	mail.add_x_header = Off

	; Log all mail() calls including the full path of the script, line #, to address and headers
	;mail.log = "C:\xampp\php\logs\php_mail.log"*/

	//en sendmail.ini.ini asegurarse de que estas lineas estan así:
	//smtp_server=smtp.gmail.com
	//smtp_port=465
	//smtp_ssl=auto
	//auth_username=josefdefrias@gmail.com
	//auth_password=dacasale

	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------


	/*$email->From = "jfrias41@hotmail.es";
	$email->FromName = "It-Hits";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	$email->AddAddress($direccion_correo);*/

	$email->From = "it@hitravel.es";
	$email->FromName = "Hitravel System";
	$email->Subject = $asunto;
	$email->MsgHTML($mensaje_html);
	$email->AddAddress($direccion_correo);

	//$email->IsHTML(true);

	$email->Send();
	
	/*if(!$email->Send()){
	   $respuesta = "No se pudo enviar el Mensaje.";
	}else{
	   $respuesta = "Mensaje enviado";
	}*/

//return $respuesta;
}

?>