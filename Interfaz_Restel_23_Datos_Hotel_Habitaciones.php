<?php

	include 'funciones_php/conexiones.php';

	/*$parametrosg = $_GET;
	$codigo = $parametrosg['codigo'];
	$nombre_hotel = $parametrosg['nombre'];*/

	$codigo = '091937';
	$nombre_hotel = 'HILTON MADRID AIRPORT';	

	//XML Request
	$xml = "codigousu=" . 'QDZN';
	$xml .= "&clausu=" . 'xml476346';
	$xml .= "&afiliacio=" . 'RS';
	$xml .= "&secacc=" . '110592';
	$xml .= "&xml=";
	$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$xml2 .= "<peticion>\n";
	$xml2 .= "<tipo>23</tipo>\n";
	$xml2 .= "<nombre>Petición de Lista de Hoteles</nombre>\n";
	$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
	$xml2 .= "<parametros>\n";
		//$xml2 .= "<codhot>745388</codhot>";
		$xml2 .= "<codhot>".$codigo."</codhot>";
	$xml2 .= "</parametros>\n";
	$xml2 .= "</peticion>";
	$xml .= urlencode($xml2);
	$length = strlen($xml);

	//XML Connection
	$fp = @fsockopen("xml.hotelresb2b.com", 80);
	fputs($fp, "POST http://xml.hotelresb2b.com/xml/listen_xml.jsp HTTP/1.0\nUser-Agent: PHP XMLRPC
	1.1\r\n");
	fputs($fp, "Host: xml.hotelresb2b.com\n");
	fputs($fp, "Content-Type: application/x-www-form-urlencoded\n");
	fputs($fp, "Content-Length: " . $length . "\n");
	fputs($fp, "\n");
	fputs($fp, $xml);
	$respuesta = "";
	while(!feof($fp)) $respuesta .= fgets($fp);
	fclose ($fp);

	//XML Answer
	$xml = substr($respuesta, strpos($respuesta, "<?xml"));
	//$xml = new SimpleXMLElement($xmlstr); //Simple XML is available from php5
	$xml = new SimpleXMLElement($xml);
	$no_habitaciones = count($xml->parametros->habitaciones->habitacion);

	/*echo('<pre>');
	print_r($xml->parametros->hotel);
	echo('</pre>');*/

	$datos_habitacion = '';



	$datos_habitacion .= "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 5.0 Transitional//EN'>
	<HTML>
	  <link rel='StyleSheet' href='css/interfaces_emergente.css'>
	 <HEAD>
	  <meta charset='utf-8'>
	  <TITLE> Menú </TITLE>
	  <META NAME='Generator' CONTENT='EditPlus'>
	  <META NAME='Author' CONTENT='Jose de Frias'>
	  <META NAME='Keywords' CONTENT=''>
	  <META NAME='Description' CONTENT='>
	  <script type='text/javascript' src='funciones_js/funciones.js'></script>
	 </HEAD>
	<body background='imagenes/fondo.jpg'>";



	$datos_habitacion .= '<strong>DATOS DE HABITACIONES DEL HOTEL: '.$nombre_hotel.'</strong><BR><BR>';
	$datos_habitacion .= '<TABLE BORDER=1>';

	$datos_habitacion .= '<TR>';
	$datos_habitacion .= '<TD>NOMBRE</TD>';
	$datos_habitacion .= '<TD>CODIGO</TD>';
	$datos_habitacion .= '<TD>PREFERENCIA</TD>';
	$datos_habitacion .= '<TD>ADULTOS</TD>';
	$datos_habitacion .= '<TD>NIÑOS</TD>';
	$datos_habitacion .= '<TD>NOMBRE ESPAÑOL</TD>';
	$datos_habitacion .= '<TD>DESCRIPCION ESPAÑOL</TD>';
	$datos_habitacion .= '</TR>';

	for($i=0;$i<$no_habitaciones;$i++)
	{
		
		$habitacion = $xml->parametros->habitaciones->habitacion[$i];
		//$hotel = $xml->parametros->hoteles->hotel[$i];

		        $datos_habitacion .= '<TR>';
		        $datos_habitacion .= '<TD>'.$habitacion->nombre.'</TD>';
		        $datos_habitacion .= '<TD>'.$habitacion->codigo.'</TD>';
		        $datos_habitacion .= '<TD>'.$habitacion->preferencia.'</TD>';
		        $datos_habitacion .= '<TD>'.$habitacion->adultos.'</TD>';
		        $datos_habitacion .= '<TD>'.$habitacion->ninos.'</TD>';
		        $datos_habitacion .= '<TD>'.$habitacion->nombre_idi->nombre_es.'</TD>';
		        $datos_habitacion .= '<TD>'.$habitacion->desc_idi->desc_es.'</TD>';
		        $datos_habitacion .= '</TR>';

	}

	$datos_habitacion .= '</TABLE></BODY></HTML>';

	echo($datos_habitacion);

?>


