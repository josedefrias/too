<?php

	include 'funciones_php/conexiones.php';

	$parametrosg = $_GET;
	//$codigo = $parametrosg['codigo'];
	//$nombre_hotel = $parametrosg['nombre'];

	$codigo = '745388';
	$nombre_hotel  = "";

	//XML Request
	$xml = "codigousu=" . 'QDZN';
	$xml .= "&clausu=" . 'xml476346';
	$xml .= "&afiliacio=" . 'RS';
	$xml .= "&secacc=" . '110592';
	$xml .= "&xml=";
	$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$xml2 = "<!DOCTYPE peticion SYSTEM 'http://xml.hotelresb2b.com/xml/dtd/pet_hotobs.dtd'>";
	$xml2 .= "<peticion>\n";
	$xml2 .= "<nombre>Observaciones de Hotel</nombre>\n";
	$xml2 .= "<agencia>Hi Travel</agencia>\n";
	$xml2 .= "<tipo>24</tipo>\n";
	$xml2 .= "<parametros>\n";
		//$xml2 .= "<codhot>690590</codhot>";
		$xml2 .= "<codigo_cobol>".$codigo."</codigo_cobol>";
		$xml2 .= "<entrada>01-01-2016</entrada>";
		$xml2 .= "<salida>31-12-2017</salida>";
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
	$no_observaciones = count($xml->parametros->hotel->observaciones->observacion);
	//echo($no_observaciones);

	/*echo('<pre>');
	print_r($xml->parametros->hotel->observaciones->observacion);
	echo('</pre>');*/

	$datos_observacion = '';



	$datos_observacion .= "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 5.0 Transitional//EN'>
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


	$datos_observacion .= '<strong>OBSERVACIONES DEL HOTEL: '.$nombre_hotel.'</strong><BR><BR>';
	$datos_observacion .= '<TABLE BORDER=1>';

	$datos_observacion .= '<TR>';
	$datos_observacion .= '<TD>TEXTO</TD>';
	$datos_observacion .= '<TD>DESDE</TD>';
	$datos_observacion .= '<TD>HASTA</TD>';

	$datos_observacion .= '</TR>';

	for($i=0;$i<$no_observaciones;$i++)
	{
		//echo($i.'<br>');
		$observacion = $xml->parametros->hotel->observaciones->observacion[$i];


		        $datos_observacion .= '<TR>';
		        $datos_observacion .= '<TD>'.$observacion->obs_texto.'</TD>';
		        $datos_observacion .= '<TD>'.$observacion->obs_desde.'</TD>';
		        $datos_observacion .= '<TD>'.$observacion->obs_hasta.'</TD>';
		        $datos_observacion .= '</TR>';

	}

	$datos_observacion .= '</TABLE></BODY></HTML>';

	echo($datos_observacion);

?>


