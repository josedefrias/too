<?php

	include 'funciones_php/conexiones.php';

	/*$parametrosg = $_GET;
	$codigo = $parametrosg['codigo'];*/
	$conexion = conexion_hit();

	$cantidad = 0;
	$cantidad_x = 0;

	$resultado =$conexion->query("select codigo from hit_interfaces_codigos_hoteles where interfaz = 'RESTEL' and NOMBRE IS NULL LIMIT 10000");

	//$resultado =$conexion->query("select codigo from hit_interfaces_codigos_hoteles where substr(provincia,1,2) = 'MV'");

	/*$resultado =$conexion->query("select codigo from hit_interfaces_codigos_hoteles where interfaz = 'RESTEL' and provincia=  'ESCAC' and NOMBRE IS NULL");*/

	for ($k = 0; $k <= $resultado->num_rows; $k++) {
		$resultado->data_seek($k);
		$fila = $resultado->fetch_assoc();


		$codigo = $fila['codigo'];


		try {


			//LLAMADA AL SERVICIO 15 DE DATOS DE HOTEL Y ACTUALIZACION DE LOS CAMPOS DE INTERFACES_CODIGOS_HOTELES
			//XML Request
			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
			$xml2 .= "<tipo>15</tipo>\n";
			$xml2 .= "<nombre>Petición de Lista de Hoteles</nombre>\n";
			$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
			$xml2 .= "<parametros>\n";
				//$xml2 .= "<codigo>746438</codigo>";
				//$xml2 .= "<codigo>745388</codigo>";
				$xml2 .= "<codigo>".$codigo."</codigo>";
				$xml2 .= "<idioma>1</idioma>";
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
			$no_hotels = count($xml->parametros->hotel);

			/*echo('<pre>');
			print_r($xml->parametros->hotel);
			echo('</pre>');*/





			for($i=0;$i<$no_hotels;$i++)
			{
				$hotel = $xml->parametros->hotel[$i];

				        $nombre = $hotel->nombre_h;
				        $pais = $hotel->pais;
				        $codigo_hotel = $hotel->codigo_hotel;
				        $codigo_interno = $hotel->codigo;
				        $categoria = $hotel->categoria;
				        $direccion = $hotel->direccion;
				        $provincia = $hotel->provincia;
				        $poblacion = $hotel->poblacion;
				        $cp = $hotel->cp;
				        $web = $hotel->web;
				        $url_imagen = $hotel->fotos->foto[0];

				        /*for($j=0;$j<=20;$j++){
				        	if($hotel->fotos->foto[$j] != ''){
					        $datos_hotel .= '<TR><TD>FOTO '.$j.' URL:'.$hotel->fotos->foto[$j].'</TD></TR>';
					        $datos_hotel .= '<TR><TD><img src=\''.$hotel->fotos->foto[$j].'\'</img></TD></TR>';
					 }
				        }*/

				        $descripcion = $hotel->desc_hotel;
				        $tipo = $hotel->tipo_establecimiento;
				        $latitud = $hotel->latitud;
				        $longitud = $hotel->longitud;


					$query = "UPDATE hit_interfaces_codigos_hoteles SET ";
					$query .= "NOMBRE = '".mysql_real_escape_string($nombre)."'";
					$query .= ", DIRECCION = '".mysql_real_escape_string($direccion)."'";
					$query .= ", POBLACION_NOMBRE = '".mysql_real_escape_string($poblacion)."'";
					$query .= ", PROVINCIA_NOMBRE = '".mysql_real_escape_string($provincia)."'";
					$query .= ", PAIS_NOMBRE = '".mysql_real_escape_string($pais)."'";
					$query .= ", CODIGO_POSTAL = '".mysql_real_escape_string($cp)."'";
					$query .= ", CATEGORIA = '".mysql_real_escape_string($categoria)."'";
					$query .= ", TIPO_ALOJAMIENTO = '".mysql_real_escape_string($tipo)."'";
					$query .= ", DESCRIPCION = '".mysql_real_escape_string($descripcion)."'";
					$query .= ", LATITUD = '".$latitud."'";
					$query .= ", LONGITUD = '".$longitud."'";
					$query .= ", URL_IMAGEN = '".mysql_real_escape_string($url_imagen)."'";
					$query .= ", WEB = '".mysql_real_escape_string($web)."'";
					$query .= " WHERE CODIGO = '".$codigo."'";
					$query .= " AND INTERFAZ = 'RESTEL'";
					$resultado_cod=$conexion->query($query);

					if ($resultado_cod == FALSE){
						$respuesta = $conexion->error;
						echo('ACTUALIZADO DATOS ERROR:<br>'.$respuesta.'<br>');
					}else{
						$respuesta = 'OK';
						$cantidad++;
					}
					

			}

		} catch (Exception $e) {

			$query = "UPDATE hit_interfaces_codigos_hoteles SET ";
			$query .= " NOMBRE = 'X'";
			$query .= " WHERE CODIGO = '".$codigo."'";
			$query .= " AND INTERFAZ = 'RESTEL'";
			$resultado_x=$conexion->query($query);

			if ($resultado_x == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
				$cantidad_x++;
				echo('ACTUALIZADO X<br>'.$respuesta.'<br>');
			}

			/*echo 'Exception' . $e->getMessage();*/
		}	
		//echo('<BR>intento'.$k.'<br>');
	}
	
	echo('<BR> CANTIDAD DE ACTUALIZADOS: '.$cantidad.' CANTIDAD DE NO ENCONTRADOS: '.$cantidad_x);

	$resultado->close();

?>


