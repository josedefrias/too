<?php

	include 'funciones_php/conexiones.php';

	$conexion = conexion_hit();


	//XML Request
	$xml = "codigousu=" . 'QDZN';
	$xml .= "&clausu=" . 'xml476346';
	$xml .= "&afiliacio=" . 'RS';
	$xml .= "&secacc=" . '110592';
	$xml .= "&xml=";
	$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$xml2 .= "<peticion>\n";

	$xml2 .= "<nombre>Petición de Lista de Paises</nombre>\n";
	$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
	$xml2 .= "<tipo>5</tipo>\n";


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
	$no_hotels = count($xml->parametros->paises->pais);
	//ECHO($respuesta.'<br><br><br>');
	ECHO($no_hotels.'<br><br><br>');
	for($i=0;$i<$no_hotels;$i++)
	{
		$pais = $xml->parametros->paises->pais[$i];
		print('CODIGO:'.$pais->codigo_pais.' -  '.'NOMBRE:'.$pais->nombre_pais.'<br>'); // prints te hotelname etc.


		/*$query = "INSERT INTO hit_interfaces_paises (INTERFAZ, CODIGO, NOMBRE) VALUES (";
		$query .= "'RESTEL',";
		$query .= "'".$pais->codigo_pais."',";
		$query .= "'".$pais->nombre_pais."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}*/


	}
	print($i);
?>