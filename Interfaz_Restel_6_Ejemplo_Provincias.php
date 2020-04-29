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

	$xml2 .= "<nombre>Petición de Lista de Provincias</nombre>\n";
	$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
	$xml2 .= "<tipo>6</tipo>\n";

	/*$xml2 .= "<parametros>\n";
	$xml2 .= "\t<pais>" . sprintf("%-5s", $provincia) . "</pais>\n";
	$xml2 .= "\t<radio>9</radio>\n";
	$xml2 .= "\t<idioma>1</idioma>\n";
	$xml2 .= "\t<afiliacion>" . 'RS' . "</afiliacion>\n";
	$xml2 .= "\t<usuario>" . 'D61366' . "</usuario>\n";
	$xml2 .= "\t<marca>" . 'RS' . "</marca>\n";
	$xml2 .= "</parametros>\n";*/


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
	$no_hotels = count($xml->parametros->provincias->provincia);
	//ECHO($respuesta.'<br><br><br>');
	ECHO($no_hotels.'<br><br><br>');
	for($i=0;$i<$no_hotels;$i++)
	{
		$provincia = $xml->parametros->provincias->provincia[$i];
		print('CODIGO PAIS:'.$provincia->codigo_pais.' -  '.'CODIGO PROVINCIA:'.$provincia->codigo_provincia.' -  '.'NOMBRE:'.$provincia->nombre_provincia.'<br>'); // prints te hotelname etc.
		//print($i);

		/*$query = "INSERT INTO hit_interfaces_provincias (INTERFAZ, CODIGO, PAIS, NOMBRE) VALUES (";
		$query .= "'RESTEL',";
		$query .= "'".$provincia->codigo_provincia."',";
		$query .= "'".$provincia->codigo_pais."',";
		$query .= "'".$provincia->nombre_provincia."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}*/



	}
	print($i);
?>