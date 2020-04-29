<?php

	include 'funciones_php/conexiones.php';

	$conexion = conexion_hit();

	$numero_zonas = 0;

	$resultado =$conexion->query("select codigo from hit_provincias where pais = 'ESP' and visible_web = 'S' and codigo not in (select destino from hit_zonas)");


	for ($k = 0; $k <= $resultado->num_rows; $k++) {
		$resultado->data_seek($k);
		$fila = $resultado->fetch_assoc();
		//echo $fila['clave']." X ".$fila['nombre']."<br/><br/>";
		//echo($fila['codigo']."<br>");
	
		$provincia = $fila['codigo'];

		//XML Request
		$xml = "codigousu=" . 'QDZN';
		$xml .= "&clausu=" . 'xml476346';
		$xml .= "&afiliacio=" . 'RS';
		$xml .= "&secacc=" . '110592';
		$xml .= "&xml=";
		$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
		$xml2 .= "<peticion>\n";

		$xml2 .= "<nombre>Petición de Lista de Poblaciones</nombre>\n";
		$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
		$xml2 .= "<tipo>18</tipo>\n";

		$xml2 .= "<parametros>\n";
			$xml2 .= "\t<codest>" . sprintf("%-5s", $provincia) . "</codest>\n";
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
		$no_hotels = count($xml->parametros->poblaciones->poblacion);
		//ECHO($respuesta.'<br><br><br>');
		//ECHO($no_hotels.'<br><br><br>');
		for($i=0;$i<$no_hotels;$i++)
		{

			$poblacion = $xml->parametros->poblaciones->poblacion[$i];

			$poblacion_revisado = str_replace("'", "", $poblacion->poblidinom1);

			print('CODIGO DE ESTADO: '.$poblacion->deppob.'-'.
			        ' CODIGO DE POBLACION: '.$poblacion->codpob.'-'.
			        ' NOMBRE: '.$poblacion_revisado.'<br>'); // prints te hotelname etc.
			//print($i);

			$query = "INSERT INTO hit_zonas (CODIGO, NOMBRE, DESTINO, VISIBLE, VISIBLE_GRUPOS) VALUES (";
			$query .= "'".$poblacion->codpob."',";
			$query .= "'".$poblacion->poblidinom1."',";
			$query .= "'".$poblacion->deppob."',";
			$query .= "'S',";
			$query .= "'S')";

			$resultadoi =$conexion->query($query);


			$numero_zonas++;
		}

	}
	print("<br>Numero de localidades:".$numero_zonas);
?>