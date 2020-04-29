<?php

	$provincia = 'ESSOR';

	//XML Request
	$xml = "codigousu=" . 'QDZN';
	$xml .= "&clausu=" . 'xml476346';
	$xml .= "&afiliacio=" . 'RS';
	$xml .= "&secacc=" . '110592';
	$xml .= "&xml=";
	$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$xml2 .= "<peticion>\n";
	$xml2 .= "<tipo>701</tipo>\n";
	$xml2 .= "<nombre>Petición de Lista de Hoteles</nombre>\n";
	$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
	$xml2 .= "<parametros>\n";
	/*$xml2 .= "\t<hotel>SORIA</hotel>\n";*/
	$xml2 .= "\t<pais>" . sprintf("%-5s", $provincia) . "</pais>\n";
	echo(sprintf("%-5s", $provincia).'<br>' );
	$xml2 .= "\t<radio>1</radio>\n";
	$xml2 .= "\t<idioma>1</idioma>\n";
	//$xml2 .= "\t<afiliacion>" . 'RS' . "</afiliacion>\n";
	$xml2 .= "\t<usuario>" . 'D61366' . "</usuario>\n";
	//$xml2 .= "\t<marca>" . 'RS' . "</marca>\n";
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
	$no_hotels = count($xml->parametros->hoteles->hotel);
	//ECHO($respuesta.'<br><br><br>');
	//ECHO($no_hotels.'<br><br><br>');
	for($i=0;$i<$no_hotels;$i++)
	{
			//echo(sprintf("%-5s", $provincia).'<br>' );
		$hotel = $xml->parametros->hoteles->hotel[$i];
		print('CODIGO HOTEL:'.$hotel->codigo_cobol.'<br>'.
		         'CODIGO INERNO:'.$hotel->codigo.'<br>'.
		         'AFILIACION:'.$hotel->afiliacion.'<br>'.
		         '<strong>NOMBRE:'.$hotel->nombre_h.'</strong><br>'.
		         'NOMBRE PROVINCIA:'.$hotel->provincia_nombre.'<br>'.
		         'PROBLACION:'.$hotel->poblacion.'<br>'.
		         'NUM (HOTLS):'.$hotel->num.'<br>'.
		         'CATEGORIA:'.$hotel->categoria.'<br>'.
		         'CALIDAD:'.$hotel->calidad.'<br>'.
		         'MARCA:'.$hotel->marca.'<br>'.
		         'WEB:'.$hotel->web.'<br>'.
		         'IDENTIFICADOR DE LA PETICION:'.$hotel->id.'<br>---------------------------------------------------------------<br>'); // prints te hotelname etc.
		//print($i);

	}
	//print($i);
?>

