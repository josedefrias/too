<?php
	include 'funciones_php/conexiones.php';

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
	$xml2 .= "<tipo>17</tipo>\n";

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
	//hay 168282 hoteles en restel a dia 19 de Mayo de 2016

	$conexion = conexion_hit();

	ECHO($no_hotels.'<br><br><br>');
	for($i=0;$i<$no_hotels;$i++)
	{
		$hotel = $xml->parametros->hoteles->hotel[$i];
		//print('CODIGO PAIS+PROVINCIA:'.$hotel->codesthot.' -  '.'POBLACION:'.$hotel->codpobhot.' -  '.'CODIGO CORTO:'.$hotel->hot_codigo.' -  '.'CODIGO_COBOL:'.$hotel->hot_codcobol.' -  '.'CODIGO DE DUPLICADO:'.$hotel->hot_coddup.' -  '.'AFILIACION:'.$hotel->hot_afiliacion.'<br>'); // prints te hotelname etc.

		/*$existe =$conexion->query("SELECT count(*) existe FROM hit_interfaces_codigos_hoteles WHERE codigo = '".$hotel->hot_codcobol."'");

		$Oexiste = $existe->fetch_assoc();								
		$Oexiste['existe'];

		if($Oexiste['existe'] == 0){*/

			$query = "INSERT INTO hit_interfaces_codigos_hoteles (PROVINCIA, POBLACION, CODIGO_CORTO, CODIGO, CODIGO_DUPLICADOS, CODIGO_AFILIACION) VALUES (";
			$query .= "'".$hotel->codesthot."',";
			$query .= "'".$hotel->codpobhot."',";
			$query .= "'".$hotel->hot_codigo."',";
			$query .= "'".$hotel->hot_codcobol."',";
			$query .= "'".$hotel->hot_coddup."',";
			$query .= "'".$hotel->hot_afiliacion."')";

			$resultadoi =$conexion->query($query);
		/*}*/




	}
	print($i.'INSERTS REALIZADOS - EL PROCESO HA TERMINADO');
?>