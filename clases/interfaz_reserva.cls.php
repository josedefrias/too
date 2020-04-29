<?php

class clsInterfaz_reserva{


	function Politica_cancelacion($interfaz, $alojamiento, $lineas){

		$conexion = $this ->Conexion;

		$interfaz_disponibilidad = array();
		
		if($interfaz == 'RESTEL'){

			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
				$xml2 .= "<tipo>144</tipo>\n";
				$xml2 .= "<nombre>Politica de Gastos de Cancelacion</nombre>\n";
				$xml2 .= "<agencia>Hi Travel</agencia>\n";
				$xml2 .= "<parametros>\n";
					$xml2 .= "<comprimido>2</comprimido>"; 
					$xml2 .= "<datos_reserva>"; 
						$xml2 .= "<hotel>".$alojamiento."</hotel>"; 

						//$lineas .= "D8#1#VR#246.51#0#FB#OK#20160606#20160607#EU#1-0#0#0#0#994214#*D8#1#VR#246.51#0#FB#OK#20160607#20160608#EU#1-0#0#0#0#994214#";

						$cada_linea = explode("*", $lineas);
						for($i=0;$i<count($cada_linea);$i++){
							if($cada_linea[$i] != ""){
								$xml2 .= "<lin>".$cada_linea[$i]."</lin>";
							}
						}
					$xml2 .= "</datos_reserva>"; 
				$xml2 .= "</parametros>\n";
			$xml2 .= "</peticion>";


			//MOSTRAR PETICION PARA CERTIFICACION EN CODIGO FUENTE
			//echo('----------------PETICION POLITICA DE CANCELACION (144) --------------------');
			//echo utf8_encode($xml2);


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
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			//Aqui habria que preguntar si se ha devuelto información y dar error en caso de que no--------------------------------------------------------------------------------
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
			$interfaz_politica_gastos = "";
			if($xml  == ''){
				echo('EN ESTE MOMENTO NO SE HA PODIDO OBTENER LA POLITICA DE CANCELACION');
				$interfaz_politica_gastos = "";
			}else{
				$xml = new SimpleXMLElement($xml);

				//MOSTRAR RESPUESTA PARA CERTIFICACION EN CODIGO FUENTE
				//echo('----------------RESPUESTA--------------------');
				//echo utf8_encode($xml->asXML());


				$no_politica_gastos = count($xml->parametros->politicaCanc);

				$indice = 0;

				if($no_politica_gastos > 0){
					$interfaz_politica_gastos = array();

				         for($j=0;$j<$no_politica_gastos;$j++){

						$politica_gastos = $xml->parametros->politicaCanc[$j];

						$interfaz_politica_gastos[$indice] = array ( 
										"fecha"  => $politica_gastos ->attributes()->fecha,
										"dias_antelacion" => $politica_gastos->dias_antelacion,
										"horas_antelacion" => $politica_gastos->horas_antelacion,
										"noches_gasto" => $politica_gastos->noches_gasto,
										"estCom_gasto" => $politica_gastos->estCom_gasto,
										"concepto" => $politica_gastos->concepto,
										"entra_en_gastos" => $politica_gastos->entra_en_gastos,
										);

						$indice++;

					}
				}
			}
		}

		return $interfaz_politica_gastos;

	}


	function Observaciones_hotel($interfaz, $alojamiento, $entrada, $salida){

		$conexion = $this ->Conexion;

		$interfaz_disponibilidad = array();
		
		if($interfaz == 'RESTEL'){

			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
				$xml2 .= "<tipo>24</tipo>\n";
				$xml2 .= "<nombre>Observaciones</nombre>\n";
				$xml2 .= "<agencia>Hi Travel</agencia>\n";
				$xml2 .= "<parametros>\n";
					//$xml2 .= "<codigo>".$alojamiento."</codigo>"; 
					$xml2 .= "<codigo_cobol>".$alojamiento."</codigo_cobol>"; 
					$xml2 .= "<entrada>".$entrada."</entrada>"; 
					$xml2 .= "<salida>".$salida."</salida>"; 
					$xml2 .= "<comprimido>2</comprimido>"; 
				$xml2 .= "</parametros>\n";
			$xml2 .= "</peticion>";

			//echo htmlentities($xml2);

			//MOSTRAR PETICION PARA CERTIFICACION EN CODIGO FUENTE
			//echo('----------------PETICION OBSERVACIONES HOTEL (24) --------------------');
			//echo utf8_encode($xml2);

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

			//echo($xml2);

			//XML Answer
			$xml = substr($respuesta, strpos($respuesta, "<?xml"));

			//echo($xml);

			//$xml = new SimpleXMLElement($xmlstr); //Simple XML is available from php5
			//-----------------------------------------------------------------------------------------------------------------------------------------------------------
			//Aqui habria que preguntar si se ha devuelto información y dar error en caso de que no--------------------------------------------------------------------------
			//---------------------------------------------------------------------------------------------------------------------------------------------------------

			//echo($xml);

			$interfaz_observaciones = "";
			if($xml  == ''){
				echo('EN ESTE MOMENTO NO SE HA PODIDO OBTENER LAS OBSERVACIONES DDEL HOTEL');
				$interfaz_observaciones = "";
			}else{
				$xml = new SimpleXMLElement($xml);

				//MOSTRAR RESPUESTA PARA CERTIFICACION EN CODIGO FUENTE
				//echo('----------------RESPUESTA--------------------');
				//echo utf8_encode($xml->asXML());


				$no_observaciones = count($xml->parametros->hotel->observaciones->observacion);

				$indice = 0;

				if($no_observaciones > 0){
					$interfaz_observaciones = array();


					for($i=0;$i<$no_observaciones;$i++)
					{
						$observacion = $xml->parametros->hotel->observaciones->observacion[$i];

						$interfaz_observaciones[$indice] = array ( 
										"texto"  => $observacion->obs_texto,
										"desde" => $observacion->obs_desde,
										"hasta" => $observacion->obs_hasta
										);
						$indice++;

					}
				}
			}
		}

		return $interfaz_observaciones;
	}



	function Prereserva($interfaz, $alojamiento, $lineas, $nombre_titular, $expediente, $observaciones){

		$conexion = $this ->Conexion;

		$interfaz_disponibilidad = array();
		
		if($interfaz == 'RESTEL'){

			//XML Request

			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
			$xml2 .= "<tipo>202</tipo>\n";
			$xml2 .= "<nombre>Petición de Reserva</nombre>\n";
			$xml2 .= "<agencia>Hi Travel</agencia>\n";
			$xml2 .= "<parametros>\n";

			$xml2 .= "<codigo_hotel>".$alojamiento."</codigo_hotel>"; 
			$xml2 .= "<nombre_cliente>".$nombre_titular."</nombre_cliente>";
			$xml2 .= "<observaciones>".$observaciones."</observaciones>";
			$xml2 .= "<num_mensaje></num_mensaje>";
			$xml2 .= "<num_expediente>".$expediente."</num_expediente>";

			$xml2 .= "<forma_pago>44</forma_pago>"; //12: Pago Directo, 25:Credito, 44:Prepago
 			$xml2 .= "<comprimido>2</comprimido>"; 
			/*$xml2 .= "<tipo_targeta>".$tipo_targeta."</tipo_targeta>"; //MasterCard: Mastercard
												   //VisaCard: Visa
												   //AmExCard: American Express
												   //DinersClubCard: Diners Club
												   //enRouteCard: enRoute
												   //DiscoverCard: Discover
												   //JCBCard: JCB
			$xml2 .= "<num_targeta>".$num_targeta."</num_targeta>";
			$xml2 .= "<cvv_targeta>".$cvv_targeta."</CVV_targeta>"; //Tres últimos dígitos luego del número de la 
												//tarjeta en el área de la firma.
												//En American Express, se trata de cuatro dígitos, 
												//y se encuentran en el frente de la tarjeta
			$xml2 .= "<mes_expiracion_targeta>".$mes_expiracion_targeta."</mes_expiracion_targeta>";
			$xml2 .= "<ano_expiracion_targeta>".$ano_expiracion_targeta."</ano_expiracion_targeta>";
			$xml2 .= "<titular_targeta>".$titular_targeta."</titular_targeta>";*/

			/*$xml2 .= "<email>".$email."</email>"; //Email de contacto del titular de la reserva.
			$xml2 .= "<telefono>".$telefono."</telefono>"; //Teléfono de contacto del titular de la reserva.*/


			//$lineas .= "D8#1#VR#246.51#0#FB#OK#20160606#20160607#EU#1-0#0#0#0#994214#*D8#1#VR#246.51#0#FB#OK#20160607#20160608#EU#1-0#0#0#0#994214#";

			$xml2 .= "<res>"; 

				$cada_linea = explode("*", $lineas);
				for($i=0;$i<count($cada_linea);$i++){
					$xml2 .= "<lin>".$cada_linea[$i]."</lin>";
				}

			$xml2 .= "</res>"; 

		
			$xml2 .= "</parametros>\n";
			$xml2 .= "</peticion>";

			//echo htmlentities($xml2);

			//MOSTRAR PETICION PARA CERTIFICACION EN CODIGO FUENTE
			//echo('----------------PETICION PRERESERVA (202) --------------------');
			//echo utf8_encode($xml2);

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

			//echo($xml2);


			//echo('<table><tr><td>'.$respuesta.'</td></tr></table>');

			//XML Answer
			$xml = substr($respuesta, strpos($respuesta, "<?xml"));

			//echo($xml);

			//$xml = new SimpleXMLElement($xmlstr); //Simple XML is available from php5
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			//Aqui habria que preguntar si se ha devuelto información y dar error en caso de que no--------------------------------------------------------------------------------
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

			

			if($xml  == ''){
				echo('EN ESTE MOMENTO NO SE HAN PODIDO BLOQUEAR LAS PLAZAS DE HOTEL');
				$interfaz_prereserva = "";
			}else{
				$xml = new SimpleXMLElement($xml);

				//MOSTRAR RESPUESTA PARA CERTIFICACION EN CODIGO FUENTE
				//echo('----------------RESPUESTA--------------------');
				//echo utf8_encode($xml->asXML());

				$no_prereserva = count($xml->parametros->estado);

				if($no_prereserva > 0){
					$interfaz_prereserva = array();
					$prereserva = $xml->parametros;

					$interfaz_prereserva['estado'] = $prereserva->estado;
					$interfaz_prereserva['n_localizador'] = $prereserva->n_localizador;
					$interfaz_prereserva['importe_total_reserva'] = $prereserva->importe_total_reserva;
					$interfaz_prereserva['n_mensaje'] = $prereserva->n_mensaje;
					$interfaz_prereserva['n_expediente'] = $prereserva->n_expediente;
					$interfaz_prereserva['observaciones'] = $prereserva->observaciones;
				}
			}
		}

		return $interfaz_prereserva;

	}


	function Confirmar_prereserva($interfaz, $localizador){//Servicio 3

		$conexion = $this ->Conexion;

		$interfaz_disponibilidad = array();
		
		if($interfaz == 'RESTEL'){

			//XML Request

			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
				$xml2 .= "<tipo>3</tipo>\n";
				$xml2 .= "<nombre>Confirmacion de Reserva</nombre>\n";
				$xml2 .= "<agencia>Hi Travel</agencia>\n";
				$xml2 .= "<parametros>\n";
					$xml2 .= "<localizador>".$localizador."</localizador>\n"; 
					$xml2 .= "<accion>AE</accion>\n";
					$xml2 .= "<comprimido>2</comprimido>"; 
				$xml2 .= "</parametros>\n";
			$xml2 .= "</peticion>";

			//echo htmlentities($xml2);

			//MOSTRAR PETICION PARA CERTIFICACION EN CODIGO FUENTE
			//echo('----------------PETICION CONFIRMACION PRERESERVA (3 - ACCION:AE) --------------------');
			//echo utf8_encode($xml2);

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

			//echo($xml2);

			//XML Answer
			$xml = substr($respuesta, strpos($respuesta, "<?xml"));
			//$xml = new SimpleXMLElement($xmlstr); //Simple XML is available from php5
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			//Aqui hay que preguntar si se ha devuelto información y dar error en caso de que no reciibirla--------------------------------------------------------------------------------
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			if($xml  == ''){
				echo('EN ESTE MOMENTO NO SE HA PODIDO COMPLETAR LA CONFIRMACION DE LA RESERVA');
				$interfaz_confirmacion = "";
			}else{
				$xml = new SimpleXMLElement($xml);

				//MOSTRAR RESPUESTA PARA CERTIFICACION EN CODIGO FUENTE
				//echo('----------------RESPUESTA--------------------');
				//echo utf8_encode($xml->asXML());

				$no_confirmacion = count($xml->parametros->estado);

				if($no_confirmacion > 0){
					$interfaz_confirmacion = array();
					$confirmacion = $xml->parametros;

					$interfaz_confirmacion['estado'] = $confirmacion->estado;
					$interfaz_confirmacion['localizador'] = $confirmacion->localizador;
					$interfaz_confirmacion['localizador_corto'] = $confirmacion->localizador_corto;
				}
			}
		}

		return $interfaz_confirmacion;

	}





	function Anular_prereserva($interfaz, $localizador){//Servicio 3

		$conexion = $this ->Conexion;

		$interfaz_disponibilidad = array();
		
		if($interfaz == 'RESTEL'){

			//XML Request

			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
				$xml2 .= "<tipo>3</tipo>\n";
				$xml2 .= "<nombre>Anulacion de Reserva</nombre>\n";
				$xml2 .= "<agencia>Hi Travel</agencia>\n";
				$xml2 .= "<parametros>\n";
					$xml2 .= "<localizador>".$localizador."</localizador>\n"; 
					$xml2 .= "<accion>AI</accion>\n";
					$xml2 .= "<comprimido>2</comprimido>"; 
				$xml2 .= "</parametros>\n";
			$xml2 .= "</peticion>";

			//echo htmlentities($xml2);

			//MOSTRAR PETICION PARA CERTIFICACION EN CODIGO FUENTE
			//echo('----------------PETICION ANULACION PRERESERVA (3 - ACCION:AI) --------------------');
			//echo utf8_encode($xml2);


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

			//echo($xml2);

			//XML Answer
			$xml = substr($respuesta, strpos($respuesta, "<?xml"));
			//$xml = new SimpleXMLElement($xmlstr); //Simple XML is available from php5
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			//Aqui hay que preguntar si se ha devuelto información y dar error en caso de que no reciibirla--------------------------------------------------------------------------------
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			if($xml  == ''){
				echo('EN ESTE MOMENTO NO SE HA PODIDO COMPLETAR LA CONFIRMACION DE LA RESERVA');
				$interfaz_anulacion = "";
			}else{
				$xml = new SimpleXMLElement($xml);

				//MOSTRAR RESPUESTA PARA CERTIFICACION EN CODIGO FUENTE
				//echo('----------------RESPUESTA--------------------');
				//echo utf8_encode($xml->asXML());
				
				$no_anulacion = count($xml->parametros->estado);

				if($no_anulacion > 0){
					$interfaz_anulacion = array();
					$anulacion = $xml->parametros;

					$interfaz_anulacion['estado'] = $anulacion->estado;
					$interfaz_anulacion['localizador'] = $anulacion->localizador;
					$interfaz_anulacion['localizador_corto'] = $anulacion->localizador_corto;
				}
			}
		}

		return $interfaz_anulacion;

	}

	function Info_Gastos_cancelacion_reserva($interfaz, $localizador){

		$conexion = $this ->Conexion;

		$interfaz_disponibilidad = array();
		
		if($interfaz == 'RESTEL'){

			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
				$xml2 .= "<tipo>142</tipo>\n";
				$xml2 .= "<nombre>Informacion de los Gastos de Cancelacion</nombre>\n";
				$xml2 .= "<agencia>Hi Travel</agencia>\n";
				$xml2 .= "<parametros>\n";
					$xml2 .= "<usuario>D61366</usuario>\n"; 
					$xml2 .= "<localizador>".$localizador."</localizador>\n";
					$xml2 .= "<idioma>1</idioma>\n";
					$xml2 .= "<comprimido>2</comprimido>"; 
				$xml2 .= "</parametros>\n";
			$xml2 .= "</peticion>";


			//MOSTRAR PETICION PARA CERTIFICACION EN CODIGO FUENTE
			//echo('----------------PETICION INFO DE GASTOS DE CANCELACION CANCELACION (142) --------------------');
			//echo utf8_encode($xml2);


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
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			//Aqui habria que preguntar si se ha devuelto información y dar error en caso de que no--------------------------------------------------------------------------------
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
			$interfaz_politica_cancelacion = "";
			if($xml  == ''){
				echo('EN ESTE MOMENTO NO SE HA PODIDO OBTENER LA POLITICA DE CANCELACION');
				$interfaz_politica_cancelacion = "";
			}else{
				$xml = new SimpleXMLElement($xml);

				//MOSTRAR RESPUESTA PARA CERTIFICACION EN CODIGO FUENTE
				//echo('----------------RESPUESTA--------------------');
				//echo utf8_encode($xml->asXML());


				$no_politica_cancelacion = count($xml->parametros->politicaCanc);

				$indice = 0;

				if($no_politica_cancelacion > 0){
					$interfaz_politica_cancelacion = array();

				         for($j=0;$j<$no_politica_cancelacion;$j++){

						$politica_cancelacion = $xml->parametros->politicaCanc[$j];

						$interfaz_politica_cancelacion[$indice] = array ( 
										"localizador"  => $politica_cancelacion ->attributes()->localizador,
										"dias_antelacion" => $politica_cancelacion->dias_antelacion,
										"horas_antelacion" => $politica_cancelacion->horas_antelacion,
										"noches_gasto" => $politica_cancelacion->noches_gasto,
										"estCom_gasto" => $politica_cancelacion->estCom_gasto, //Si este valor es cero no hay gastos de cancelacion
										"desc" => $politica_cancelacion->desc
										);

						$indice++;

					}
				}
			}
		}

		return $interfaz_politica_cancelacion;

	}

	function Cancelar_reserva($interfaz, $localizador, $localizador_corto){

		$conexion = $this ->Conexion;

		$interfaz_disponibilidad = array();
		
		if($interfaz == 'RESTEL'){

			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
				$xml2 .= "<tipo>401</tipo>\n";
				$xml2 .= "<nombre>Cancelacion</nombre>\n";
				$xml2 .= "<agencia>Hi Travel</agencia>\n";
				$xml2 .= "<parametros>\n";
					$xml2 .= "<localizador_largo>".$localizador."</localizador_largo>"; 
					$xml2 .= "<localizador_corto>".$localizador_corto."</localizador_corto>"; 
					$xml2 .= "<comprimido>2</comprimido>"; 
				$xml2 .= "</parametros>\n";
			$xml2 .= "</peticion>";

			//echo htmlentities($xml2);

			//MOSTRAR PETICION PARA CERTIFICACION EN CODIGO FUENTE
			//echo('----------------PETICION CANCELACION RESERVA (401) --------------------');
			//echo utf8_encode($xml2);

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

			//echo($xml2);

			//XML Answer
			$xml = substr($respuesta, strpos($respuesta, "<?xml"));

			//echo($xml);

			//$xml = new SimpleXMLElement($xmlstr); //Simple XML is available from php5
			//-----------------------------------------------------------------------------------------------------------------------------------------------------------
			//Aqui habria que preguntar si se ha devuelto información y dar error en caso de que no--------------------------------------------------------------------------
			//---------------------------------------------------------------------------------------------------------------------------------------------------------

			//echo($xml);

			$interfaz_cancelacion = "";
			if($xml  == ''){
				echo('EN ESTE MOMENTO NO SE HA PODIDO OBTENER LAS OBSERVACIONES DDEL HOTEL');
				$interfaz_cancelacion = "";
			}else{
				$xml = new SimpleXMLElement($xml);

				//MOSTRAR RESPUESTA PARA CERTIFICACION EN CODIGO FUENTE
				//echo('----------------RESPUESTA--------------------');
				//echo utf8_encode($xml->asXML());


				$no_cancelacion = count($xml->parametros);

				$indice = 0;

				if($no_cancelacion > 0){
					$interfaz_cancelacion = array();

					$cancelacion = $xml->parametros;

					$interfaz_cancelacion['estado'] = $cancelacion->estado;
					$interfaz_cancelacion['localizador'] = $cancelacion->localizador;
					$interfaz_cancelacion['localizador_baja'] = $cancelacion->localizador_baja;

				}
			}
		}

		return $interfaz_cancelacion;

	}

	function Ultimas_reservas($interfaz, $alojamiento, $lineas, $nombre_titular, $observaciones){
	}//Servicio 8

	function Lineas_reserva($interfaz, $alojamiento, $lineas, $nombre_titular, $observaciones){
	}//Servicio 9

	function Informacion_reserva($interfaz, $alojamiento, $lineas, $nombre_titular, $observaciones){
	}//Servicio 11

	function Bono_reserva($interfaz, $localizador_restel, $localizador_hits){
			$conexion = $this ->Conexion;

		//$interfaz_disponibilidad = array();
		$html_bono_reserva = '';

		if($interfaz == 'RESTEL'){

			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
				$xml2 .= "<tipo>12</tipo>\n";
				$xml2 .= "<nombre>Datos del Bono de la Reserva</nombre>\n";
				$xml2 .= "<agencia>Hi Travel</agencia>\n";
				$xml2 .= "<parametros>\n";
					$xml2 .= "<idioma>1</idioma>\n"; 
					$xml2 .= "<localizador>".$localizador_restel."</localizador>\n";
					$xml2 .= "<comprimido>2</comprimido>"; 
				$xml2 .= "</parametros>\n";
			$xml2 .= "</peticion>";


			//MOSTRAR PETICION PARA CERTIFICACION EN CODIGO FUENTE
			//echo('----------------PETICION INFO DE GASTOS DE CANCELACION CANCELACION (142) --------------------');
			//echo utf8_encode($xml2);

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
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			//Aqui habria que preguntar si se ha devuelto información y dar error en caso de que no--------------------------------------------------------------------------------
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
			$interfaz_bono_reserva = "";
			if($xml  == ''){
				echo('EN ESTE MOMENTO NO SE HAN PODIDO OBTENER LOS DATOS DEL BONO DE LA RESERVA');
				$interfaz_bono_reserva = "";
			}else{
				$xml = new SimpleXMLElement($xml);

				//MOSTRAR RESPUESTA PARA CERTIFICACION EN CODIGO FUENTE
				//echo('----------------RESPUESTA--------------------');
				//echo utf8_encode($xml->asXML());

				$no_bono_reserva = count($xml->parametros->reserva);

				$indice = 0;

				if($no_bono_reserva > 0){

				        	 for($j=0;$j<$no_bono_reserva;$j++){

						$bono_reserva = $xml->parametros->reserva[$j];

						/*echo($bono_reserva ->afiliacion.'<br>'.
							'localizador_largo'.$bono_reserva ->localizador_largo.'<br>'.
							'localizador_corto'.$bono_reserva ->localizador_corto.'<br>'.
							'Localizador_externo'.$bono_reserva ->Localizador_externo.'<br>'.
							'imptotres'.$bono_reserva ->imptotres.'<br>'.
							'forma_pago'.$bono_reserva ->forma_pago.'<br>'.
							'observaciones'.$bono_reserva ->observaciones.'<br>'.
							'bono'.$bono_reserva ->bono.'<br>'.
							'divisa'.$bono_reserva ->divisa.'<br>'.
							'priultserv'.$bono_reserva ->priultserv.'<br>'.
							'valoranyadido'.$bono_reserva ->valoranyadido.'<br>'.
							'valor'.$bono_reserva ->valor.'<br>'.
							'data_ini'.$bono_reserva ->data_ini.'<br>'.
							'data_fi'.$bono_reserva ->data_fi.'<br>'.
							'total_n_hab'.$bono_reserva ->total_n_hab.'<br>'.
							'city_tax'.$bono_reserva ->city_tax.'<br>'
							);*/

						$datos_salida = date("d-m-Y",strtotime($bono_reserva ->data_ini));
						$datos_regreso = date("d-m-Y",strtotime($bono_reserva ->data_fi));

						$agencia_datos = $bono_reserva->agencia_datos[$j];
						/*echo(
							'ag_nombre'.$agencia_datos ->ag_nombre.'<br>'.
							'ag_direccion'.$agencia_datos ->ag_direccion.'<br>'.
							'ag_codigo_postal'.$agencia_datos ->ag_codigo_postal.'<br>'.
							'ag_telefono'.$agencia_datos ->ag_telefono.'<br>'.
							'ag_fax'.$agencia_datos ->ag_fax.'<br>'.
							'ag_agente'.$agencia_datos ->ag_agente);*/

						$hotel_datos = $bono_reserva->hotel_datos[$j];
						/*echo($hotel_datos ->hot_afiliacion.'<br>'.
							$hotel_datos ->hot_codcobol.'<br>'.
							'hot_nombre'.$hotel_datos ->hot_nombre.'<br>'.
							'hot_direccion'.$hotel_datos ->hot_direccion.'<br>'.
							'hot_poblacion'.$hotel_datos ->hot_poblacion.'<br>'.
							'hot_telefono'.$hotel_datos ->hot_telefono.'<br>'.
							'hot_plano'.$hotel_datos ->hot_plano.'<br>'.
							'hot_logo'.$hotel_datos ->hot_logo.'<br>'.
							'hot_descripciones'.$hotel_datos ->hot_descripciones.'<br>'.
							'hot_como_llegar'.$hotel_datos ->hot_como_llegar.'<br>'.
							'hot_fax'.$hotel_datos ->hot_fax.'<br>');*/

						//http://www.hotelresb2b.com/planos/745388_plano_1847.jpg


						//Lineas del Hotel. De momento usamos las de la reserva y no las que nos da el interfaz
						/*$no_hotel_lineas = count($bono_reserva->lineas);
						echo('lineas: '.$no_hotel_lineas.'<br>');

						for($k=0;$k<=$no_hotel_lineas+1;$k++){

							$hotel_linea = $bono_reserva->lineas[$j]->linea[$k];

							echo(
								'lin_fecha'.$hotel_linea ->lin_fecha.'<br>'.
								'lin_fecha2'.$hotel_linea ->lin_fecha2.'<br>'.
								'lin_tipo_habitaciones'.$hotel_linea ->lin_tipo_habitaciones.'<br>'.
								'lin_n_habitaciones'.$hotel_linea ->lin_n_habitaciones.'<br>'.
								'lin_adultos'.$hotel_linea ->lin_adultos.'<br>'.
								'lin_ninos'.$hotel_linea ->lin_ninos.'<br>'.
								'lin_tipo_hab_cod'.$hotel_linea ->lin_tipo_hab_cod.'<br>'.
								'lin_cod_regimen'.$hotel_linea ->lin_cod_regimen.'<br>');

						}*/


					//OBTENEMOS DATOS RESERVA
					$datos_salida_viaje =$conexion->query("select DATE_FORMAT(fecha_salida, '%d-%m-%Y') AS fecha_salida, DATE_FORMAT(fecha_regreso, '%d-%m-%Y') AS fecha_regreso,  DATE_FORMAT(fecha_reserva, '%d-%m-%Y') AS fecha_reserva,
					case situacion 
						when 'P' then 'Servicios pendientes'
						when 'F' then 'Servicios confirmados'
					end situacion
					from hit_reservas where localizador = '".$localizador_hits."'");
					$odatos_salida_viaje = $datos_salida_viaje->fetch_assoc();
					$datos_fecha_reserva = $odatos_salida_viaje['fecha_reserva'];


					//OBTENEMOS TELEFONO DE CONTACTO HITRAVEL
					$datos_contacto_hits =$conexion->query("select d.TELEFONO_CONTACTO telefono
					from hit_reservas r, hit_producto_cuadros c, hit_destinos d
					where 
					r.FOLLETO = c.FOLLETO
					and r.CUADRO = c.CUADRO
					and c.DESTINO = d.CODIGO
					and localizador = '".$localizador_hits."'");
					$odatos_contacto_hits = $datos_contacto_hits->fetch_assoc();
					if($odatos_contacto_hits['telefono'] != ""){
						$telefono_contacto_hits = "   (Telefono de contacto: ".$odatos_contacto_hits['telefono'].")";
					}else{
						$telefono_contacto_hits = "";
					}


					//CONTROL HAY ALOJAMIENTOS
					$datos_hay_alojamientos =$conexion->query("select count(*) hay_alojamientos from hit_reservas_alojamientos where localizador = ".$localizador_hits);
					$odatos_hay_alojamientos = $datos_hay_alojamientos->fetch_assoc();
					$hay_alojamientos = $odatos_hay_alojamientos['hay_alojamientos'];


					$oReservas_fin = new clsReservas_fin($conexion, $localizador_hits);
					$sdatos_agencia = $oReservas_fin->Cargar_datos_agencia($localizador_hits);
					$sdatos_pasajeros = $oReservas_fin->Cargar_pasajeros();

					
					// !!OJO¡¡ estos datos de alojamiento ahora valen porque son estancias en un solo hotel. cuando esto cambie habrá que mostrarlo en bucle
					// leyendo la tabla de alojamientos de la reserva Y sacando toda la informacion de cada hotel
					$sNombre_hotel = $oReservas_fin->Cargar_nombre_hotel();
					$sPeriodo_hotel = $oReservas_fin->Cargar_periodo_hotel();
					$shoteles = $oReservas_fin->Cargar_hoteles();
					

					$html_bono_reserva = "";


					$texto_confirmacion = 'BONO PARA ENTREGAR A SU LLEGADA AL HOTEL';	


					$html_bono_reserva .= "<body bgcolor='#f5f5f5' align='center'>
									<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td align='center'>
										<table width='770' border='0' align='center' cellpadding='0' cellspacing='0'><tr><td bgcolor='#FFFFFF'><img src='imagenes/cab.jpg' alt='HI TRAVEL' border='1'></a></td></tr>"; 
					// Nombre del viaje y fecha de envio
					$html_bono_reserva .= "<tr><td height='40' bgcolor='#2D5E47' style='padding:5px 0 5px 60px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif;'><span style='font-size: 20px;'>BONO PARA ENTREGAR A SU LLEGADA AL HOTEL</span></p></td></tr>

						<tr><td height='40' valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
					    	<tr>
					      <td valign='middle' style='padding:15px 0 15px 20px; color:#b9d305; font-family: Verdana, Geneva, sans-serif; font-size: 20px; font-weight:bold;'>".$hotel_datos ->hot_nombre;


					 $html_bono_reserva .= "</td>
					      <td align='right' valign='middle' style='padding: 15px 5px 15px 0px; color:#361DB7; font-family: Verdana, Geneva, sans-serif; font-size: 14px; '>Reservado el ".$datos_fecha_reserva."</td>
					    	</tr>
					  </table></td>
					</tr>";


					$html_bono_reserva .= "<tr>
					  <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
					    <tr>

					      <td height='40' bgcolor='#361DB7' align='center' style='padding:0px 0px 0px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 20px;'>Localizador Reserva: 
					        <span style='color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 25px; font-weight:bold;'>".$bono_reserva ->localizador_largo."</span>
					      </td>

					      <td height='40' bgcolor='#b9d305' align='center' style='padding:0px 0px 0px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 25px;'>Pago por Restel</td>
					    </tr>
					  </table></td>
					</tr>";


				     	 // DATOS GENERALES
				     	 $html_bono_reserva .= "<tr><td align='center' bgcolor='#FFFFFF'>
				      						<table width='722' border='0' cellspacing='0' cellpadding='0'>
				      				      			<tr><td>&nbsp;</td></tr>
				      				      			<tr><td valign='middle' height='50' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 24px;'><img src='imagenes/ico_datos_generales_3.jpg' border='0' width='30' height='30'>&nbsp;Datos generales de la reserva</td></tr>";


					 // PASAJEROS
					     $html_bono_reserva .= "<tr><td  bgcolor='#F3F4EB' style='padding: 20px 30px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif;'><span  style='font-size:15px;'>Sres:</span><br><span  style='font-size: 15px;'>";
								
						for ($i = 0; $i < count($sdatos_pasajeros); $i++) {
							$html_bono_reserva .= $sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." (".$sdatos_pasajeros[$i]['pax_tipo'];

								if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
									$html_bono_reserva .= " - ".$sdatos_pasajeros[$i]['pax_edad']." años";
								}
								$html_bono_reserva .= ")<br>";
						}


					      $html_bono_reserva .= "</span></td></tr><tr><td valign='top'>&nbsp;</td></tr>";

					 //FECHAS
					     $html_bono_reserva .= "<tr><td valign='top'><table width='722' border='0' cellspacing='0' cellpadding='0'>";
					      // Fecha de salida
					      $html_bono_reserva .= "<tr><td width='200' style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Localizador Hi Travel</td><td width='522' bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$localizador_hits.$telefono_contacto_hits."</td></tr>";		
					      			   
					      $html_bono_reserva .= "<tr><td width='200' style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Fecha de Entrada</td><td width='522' bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$datos_salida."</td></tr>";

					      $html_bono_reserva .= "<tr><td width='200' style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Fecha de Salida</td><td width='522' bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$datos_regreso."</td></tr>";
					     

					$html_bono_reserva .= "</table></td></tr>";



					// DATOS AGENCIA
					 $html_bono_reserva .="<tr><td valign='top'>&nbsp;</td></tr><tr><td valign='top'><table width='722' border='0' cellspacing='0' cellpadding='0'>
					 						<tr><td width='200' style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Agencia</td><td width='522' bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_nombre']."</td>
					 						</tr>";

					      $html_bono_reserva .="<tr><td style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Oficina</td><td bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_direccion']." - (".$sdatos_agencia[0]['a_c_postal'].") ".$sdatos_agencia[0]['a_localidad']."</td></tr>";

					      $html_bono_reserva .="<tr><td style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Agente / Tfno / Email</td><td bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_agente']." / ".$sdatos_agencia[0]['a_telefono']." / ".$sdatos_agencia[0]['a_email']."</td></tr>";

					      $html_bono_reserva .="<tr><td style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Referencia Agencia</td><td bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_referencia_agencia']."</td></tr>";

					      $html_bono_reserva .="<tr><td style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Observaciones</td><td bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_observaciones']."</td></tr>";

					$html_bono_reserva .="</tr></table></td></tr>";



					// ALOJAMIENTO ESTANCIA
					if($hay_alojamientos > 0){
						$html_bono_reserva .= "
						<tr>
							<td valign='top'>&nbsp;</td></tr><tr><td height='50' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 24px;'><img src='imagenes/ico_alojamiento_4.jpg' border='0' width='30' height='30'>&nbsp;Alojamiento Estancia</td>
						</tr>
						<tr>
						  <td valign='top'>
						  	<table width='100%' border='1' cellspacing='0' cellpadding='0'>
						    		<tr>
						    			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Hotel</td>
						      			<td colspan='3' width='60%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Estancia</td>
						    		</tr>";

						 			$html_bono_reserva .= "<tr>   		
										<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px; font-weight:bold;'>".$sNombre_hotel[0]['h_nombre']."<br></td>
										<td colspan='3' style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px;'>".$sPeriodo_hotel[0]['h_periodo']."<br></td>
								    		</tr>";		

									$html_bono_reserva .= "<tr>
								      			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Habitación</td>
								      			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Distribución</td>
								      			<td width='5%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0px 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Rég</td>
								      			<td width='15%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0px 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Estado</td>
								    		</tr>";



								for ($i = 0; $i < count($shoteles); $i++) {

						        			$html_bono_reserva .= "<tr>
										<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_caracteristica']."<br></td>
										<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_pax']." ".$shoteles[$i]['h_desglose_pax']."<br></td>
										<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_regimen_siglas']."<br></td>
						  				<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>".$shoteles[$i]['h_estado']."</td>
						  				</tr>";	
				

								}


						$html_bono_reserva .= "</table></td></tr>";
					}


					// ALOJAMIENTO DATOS
					if($hay_alojamientos > 0){
						$html_bono_reserva .= "
						<tr>
							<td valign='top'>&nbsp;</td></tr><tr><td height='50' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 24px;'><img src='imagenes/ico_alojamiento_5.png' border='0' width='30' height='30'>&nbsp;Datos del Alojamiento</td>
						</tr>
						<tr>
						  <td valign='top'>
						  	<table width='100%' border='1' cellspacing='0' cellpadding='0'>
						    		<tr>
						    			<td colspan='2' width='50%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Dirección</td>

						      			<td colspan='1' width='30%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Poblacion</td>

						      			<td colspan='1' width='20%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Teléfono</td>

						    		</tr>

						 		<tr>   		
									<td colspan='2' style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px; font-weight:bold;'>".$hotel_datos ->hot_direccion."<br></td>
									<td colspan='1' style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px;'>".$hotel_datos ->hot_poblacion."<br></td>
									<td colspan='1' style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px;'>".$hotel_datos ->hot_telefono."<br></td>
								    </tr>	

								<tr>
								      	<td colspan='4' width='100%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Breve Descripción</td>
								  </tr>

						        		<tr>
									<td colspan='4' style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$hotel_datos ->hot_descripciones."<br></td>
						  		</tr>

						  		<tr>
								      	<td colspan='2' width='50%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Como llegar</td>

								      	<td colspan='2' width='50%' bgcolor='#2D5E47' style='padding:5px 0 5px 40px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>SELLO DE LA AGENCIA EMISORA</td>
								  </tr>

						        		<tr>
									<td colspan='2' valign='top' style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$hotel_datos ->hot_como_llegar."<br></td>

									<td colspan='2' style='padding:10px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'><img src='imagenes/Sello_Agencia_Emisora_blanco.jpg' width='380' height='150'>
									</td>
						  		</tr>";	

						 if($bono_reserva ->city_tax != ''){
						$html_bono_reserva .= "
						<tr>
							<td colspan='4' style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>Nota: ".$bono_reserva ->city_tax."<br></td>
						  </tr>";

						}


						$html_bono_reserva .= "</table></td></tr>";
					}


					//PIE 

					$html_bono_reserva .= "<tr>
						<td valign='top'><h1 style='font-size:1.5em;line-height:120%;color:#2D5E47;margin:20px 40px 10px;font-weight:normal;text-align:left;clear:both;	display:block;'><strong>TELEFONO DE EMERGENCIAS 24 HORAS: (+34) 91 310 85 07</strong></h1></td></tr>";


					$html_bono_reserva .= "</table><p>&nbsp;</p></td></tr></table></body>";


					}
				}
			}

		}

		return $html_bono_reserva;

	}//Servicio 12



	function Bono_reserva_pdf($interfaz, $localizador_restel, $localizador_hits){
			$conexion = $this ->Conexion;

		//$interfaz_disponibilidad = array();
		
		if($interfaz == 'RESTEL'){

			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
				$xml2 .= "<tipo>12</tipo>\n";
				$xml2 .= "<nombre>Datos del Bono de la Reserva</nombre>\n";
				$xml2 .= "<agencia>Hi Travel</agencia>\n";
				$xml2 .= "<parametros>\n";
					$xml2 .= "<idioma>1</idioma>\n"; 
					$xml2 .= "<localizador>".$localizador_restel."</localizador>\n";
					$xml2 .= "<comprimido>2</comprimido>"; 
				$xml2 .= "</parametros>\n";
			$xml2 .= "</peticion>";


			//MOSTRAR PETICION PARA CERTIFICACION EN CODIGO FUENTE
			//echo('----------------PETICION INFO DE GASTOS DE CANCELACION CANCELACION (142) --------------------');
			//echo utf8_encode($xml2);

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
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			//Aqui habria que preguntar si se ha devuelto información y dar error en caso de que no--------------------------------------------------------------------------------
			//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			
			$html_bono_reserva = "";

			$interfaz_bono_reserva = "";
			if($xml  == ''){
				echo('EN ESTE MOMENTO NO SE HAN PODIDO OBTENER LOS DATOS DEL BONO DE LA RESERVA');
				$interfaz_bono_reserva = "";
			}else{
				$xml = new SimpleXMLElement($xml);

				$no_bono_reserva = count($xml->parametros->reserva);

				$indice = 0;

				if($no_bono_reserva > 0){

				        	 for($j=0;$j<$no_bono_reserva;$j++){

						$bono_reserva = $xml->parametros->reserva[$j];

						$datos_salida = date("d-m-Y",strtotime($bono_reserva ->data_ini));
						$datos_regreso = date("d-m-Y",strtotime($bono_reserva ->data_fi));

						$agencia_datos = $bono_reserva->agencia_datos[$j];

						$hotel_datos = $bono_reserva->hotel_datos[$j];


					//OBTENEMOS DATOS RESERVA
					$datos_salida_viaje =$conexion->query("select DATE_FORMAT(fecha_salida, '%d-%m-%Y') AS fecha_salida, DATE_FORMAT(fecha_regreso, '%d-%m-%Y') AS fecha_regreso,  DATE_FORMAT(fecha_reserva, '%d-%m-%Y') AS fecha_reserva,
					case situacion 
						when 'P' then 'Servicios pendientes'
						when 'F' then 'Servicios confirmados'
					end situacion
					from hit_reservas where localizador = '".$localizador_hits."'");
					$odatos_salida_viaje = $datos_salida_viaje->fetch_assoc();
					$datos_fecha_reserva = $odatos_salida_viaje['fecha_reserva'];


					//OBTENEMOS TELEFONO DE CONTACTO HITRAVEL
					$datos_contacto_hits =$conexion->query("select d.TELEFONO_CONTACTO telefono
					from hit_reservas r, hit_producto_cuadros c, hit_destinos d
					where 
					r.FOLLETO = c.FOLLETO
					and r.CUADRO = c.CUADRO
					and c.DESTINO = d.CODIGO
					and localizador = '".$localizador_hits."'");
					$odatos_contacto_hits = $datos_contacto_hits->fetch_assoc();
					if($odatos_contacto_hits['telefono'] != ""){
						$telefono_contacto_hits = "   (Telefono de contacto: ".$odatos_contacto_hits['telefono'].")";
					}else{
						$telefono_contacto_hits = "";
					}


					//CONTROL HAY ALOJAMIENTOS
					$datos_hay_alojamientos =$conexion->query("select count(*) hay_alojamientos from hit_reservas_alojamientos where localizador = ".$localizador_hits);
					$odatos_hay_alojamientos = $datos_hay_alojamientos->fetch_assoc();
					$hay_alojamientos = $odatos_hay_alojamientos['hay_alojamientos'];

					$oReservas_fin = new clsReservas_fin($conexion, $localizador_hits);
					$sdatos_agencia = $oReservas_fin->Cargar_datos_agencia($localizador_hits);
					$sdatos_pasajeros = $oReservas_fin->Cargar_pasajeros();
				
					$sNombre_hotel = $oReservas_fin->Cargar_nombre_hotel();
					$sPeriodo_hotel = $oReservas_fin->Cargar_periodo_hotel();
					$shoteles = $oReservas_fin->Cargar_hoteles();
					

					$html_bono_reserva = "";

					$texto_confirmacion = 'BONO PARA ENTREGAR A SU LLEGADA AL HOTEL';	


				$html_bono_reserva .= "<html lang='es'>
				<head>	
					<meta charset='utf-8'/>
				</head>
				<body bgcolor='#f5f5f5' align='center'>

				<div>";

					// Nombre del viaje y fecha de envio
		$html_bono_reserva .= "<table width='100%'>
						<tr>
							<td width='25%' height='20' bgcolor='#FFFFFF'>
								<img src='imagenes/Logo_Mail2.jpg' alt='HI TRAVEL' border='0' width='170px' height='30px'>
							</td>
							<td width='75%' height='20' bgcolor='#2D5E47' style='padding:0px 0px 0px 50px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif;'><span style='font-size: 18px;'><strong>BONO PARA ENTREGAR A SU LLEGADA AL HOTEL</strong></span></p>
							</td>
						</tr>
					</table>

					<table width='100%' height='20'  valign='top border='0' cellspacing='0' cellpadding='0'>
			    			<tr>
			      				<td valign='middle' style='padding:5px 0 5px 20px; color:#b9d305; font-family: Verdana, Geneva, sans-serif; font-size: 20px; font-weight:bold;'>".$hotel_datos ->hot_nombre."</td>
			      				<td align='right' valign='middle' style='padding: 5px 5px 5px 0px; color:#361DB7; font-family: Verdana, Geneva, sans-serif; font-size: 14px; '>Reservado el ".$datos_fecha_reserva."</td>
			    			</tr>
			  		</table>";


		$html_bono_reserva .= "<table width='100%' border='0' cellspacing='0' cellpadding='0'>
		    				<tr>
		      					<td height='30' bgcolor='#361DB7' align='center' style='padding:0px 0px 0px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 20px;'>Localizador Reserva:&nbsp;&nbsp;
		        						<span style='color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 25px; font-weight:bold;'>".$bono_reserva ->localizador_largo."</span>
		      					</td>

		      					<td height='30' bgcolor='#b9d305' align='center' style='padding:0px 0px 0px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 25px;'>Pago por Restel
		      					</td>
		    				</tr>
		  			</table>";


				     	 // DATOS GENERALES
		 $html_bono_reserva .= "<table width='100%' border='0' cellspacing='0' cellpadding='0'>
		 				<tr>
		 					<td align='center' bgcolor='#FFFFFF'>
				      				<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#FFFFFF'>

				      				      	<tr>
				      				      		<td valign='middle' height='40' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 20px;'><img src='imagenes/ico_datos_generales_3.jpg' border='0' width='25' height='25'>&nbsp;Datos generales de la reserva
				      				      		</td>
				      				      	</tr>";


					 // PASAJEROS
					     	$html_bono_reserva .= "<tr>
					     					<td  bgcolor='#F3F4EB' style='padding: 5px 30px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif;'>
					     						<span  style='font-size:14px;'>Sres:</span>
					     						<br>
					     						<span  style='font-size: 14px;'>";
								
												for ($i = 0; $i < count($sdatos_pasajeros); $i++) {
													$html_bono_reserva .= $sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." (".$sdatos_pasajeros[$i]['pax_tipo'];

														if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
															$html_bono_reserva .= " - ".$sdatos_pasajeros[$i]['pax_edad']." años";
														}
														$html_bono_reserva .= ")<br>";
												}


					      			$html_bono_reserva .= "</span>
					      					</td>
					      				</tr>
					      				<tr>
					      					<td valign='top'>&nbsp;
					      					</td>
					      				</tr>";

					 //FECHAS
					     	$html_bono_reserva .= "<tr>
					     					<td valign='top'>
					     						<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
								      // Fecha de salida
								      $html_bono_reserva .= "<tr>
									      				<td width='25%' style='padding: 0px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Localizador Hi Travel
									      				</td>
									      				<td width='75%' bgcolor='#F3F4EB' style='padding: 0px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$localizador_hits.$telefono_contacto_hits."
									      				</td>
								      				</tr>";		
								      			   
								      $html_bono_reserva .= "<tr>
								      					<td width='25%' style='padding: 0px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Fecha de Entrada
								      					</td>
								      					<td width='75%' bgcolor='#F3F4EB' style='padding: 0px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$datos_salida."
								      					</td>
								      				</tr>";

								      $html_bono_reserva .= "<tr>
								      					<td width='25%'' style='padding: 0px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Fecha de Salida
								      					</td>
								      					<td width='75%' bgcolor='#F3F4EB' style='padding: 0px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$datos_regreso."
								      					</td>
								      				</tr>";

								$html_bono_reserva .= "</table>
										</td>
									</tr>";



					// DATOS AGENCIA
						$html_bono_reserva .="<tr>
										<td valign='top'>&nbsp;
										</td>
									</tr>
									<tr>
										<td valign='top'>
											<table width='100%' border='0' cellspacing='0' cellpadding='0'>
					 							<tr>
					 								<td width='25%' style='padding: 0px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Agencia
					 								</td>
					 								<td width='75%' bgcolor='#F3F4EB' style='padding: 0px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_nombre']."
					 								</td>
					 							</tr>";

					      				$html_bono_reserva .="<tr>
					      								<td width='25%' style='padding: 0px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Oficina
					      								</td>
					      								<td width='75%' bgcolor='#F3F4EB' style='padding: 0px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_direccion']." - (".$sdatos_agencia[0]['a_c_postal'].") ".$sdatos_agencia[0]['a_localidad']."
					      								</td>
					      							</tr>";

					      				$html_bono_reserva .="<tr>
					      								<td width='25%' style='padding: 0px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Agente / Tfno / Email
					      								</td>
					      								<td width='75%' bgcolor='#F3F4EB' style='padding: 0px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_agente']." / ".$sdatos_agencia[0]['a_telefono']." / ".$sdatos_agencia[0]['a_email']."
					      								</td>
					      							</tr>";

					      				$html_bono_reserva .="<tr>
					      								<td width='25%' style='padding: 0px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Referencia Agencia
					      								</td>
					      								<td width='75%' bgcolor='#F3F4EB' style='padding: 0px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_referencia_agencia']."
					      								</td>
					      							</tr>";

					      				$html_bono_reserva .="<tr>
					      								<td width='25%' style='padding: 0px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Observaciones
					      								</td>
					      								<td width='75%' bgcolor='#F3F4EB' style='padding: 0px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_observaciones']."

					      								</td>
					      							</tr>";

								$html_bono_reserva .="</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>";




						/*<td align='center' bgcolor='#FFFFFF'>
				      				<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#FFFFFF'>

				      				      	<tr>
				      				      		<td valign='middle' height='40' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 20px;'><img src='imagenes/ico_datos_generales_3.jpg' border='0' width='25' height='25'>&nbsp;Datos generales de la reserva
				      				      		</td>
				      				      	</tr>";*/


	// ALOJAMIENTO ESTANCIA
	if($hay_alojamientos > 0){
		$html_bono_reserva .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#FFFFFF'>
						<tr>
							<td height='40' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 20px;'><img src='imagenes/ico_alojamiento_4.jpg' border='0' width='25' height='25'>&nbsp;Estancia
							</td>
						</tr>
						<tr>
						  <td valign='top'>
						  	<table width='100%' border='1' cellspacing='0' cellpadding='0'>
						    		<tr>
						    			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Hotel</td>
						      			<td colspan='3' width='60%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Estancia</td>
						    		</tr>";

					$html_bono_reserva .= "<tr>   		
									<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px; font-weight:bold;'>".$sNombre_hotel[0]['h_nombre']."<br></td>
									<td colspan='3' style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px;'>".$sPeriodo_hotel[0]['h_periodo']."<br></td>
								    </tr>";		

					$html_bono_reserva .= "<tr>
						      			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Habitación</td>
						      			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Distribución</td>
						      			<td width='5%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0px 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Rég</td>
						      			<td width='15%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0px 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Estado</td>
								 </tr>";



				for ($i = 0; $i < count($shoteles); $i++) {

		        			$html_bono_reserva .= "<tr>
									<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_caracteristica']."<br></td>
									<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_pax']." ".$shoteles[$i]['h_desglose_pax']."<br></td>
									<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_regimen_siglas']."<br></td>
					  				<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>".$shoteles[$i]['h_estado']."</td>
				  				</tr>";	
		

						}
				$html_bono_reserva .= "</table>
						</td>
					</tr>
				</table>";
				}


// ALOJAMIENTO DATOS
if($hay_alojamientos > 0){
	$html_bono_reserva .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#FFFFFF'>

					<tr>
						<td height='40' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 20px;'><img src='imagenes/ico_alojamiento_5.png' border='0' width='25' height='25'>&nbsp;Datos del Alojamiento
						</td>
					</tr>
					<tr>
						  <td valign='top'>
						  	<table width='100%' border='1' cellspacing='0' cellpadding='0'>
						    		<tr>
						    			<td colspan='2' width='50%' bgcolor='#2D5E47' style='padding:2px 0 2px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Dirección
						    			</td>

						      			<td colspan='1' width='30%' bgcolor='#2D5E47' style='padding:2px 0px 2px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Poblacion
						      			</td>

						      			<td colspan='1' width='20%' bgcolor='#2D5E47' style='padding:2px 0px 2px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Teléfono
						      			</td>

						    		</tr>

						 		<tr>   		
									<td colspan='2' style='padding:2px 0px 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px; font-weight:bold;'>".$hotel_datos ->hot_direccion."<br>
									</td>
									<td colspan='1' style='padding:2px 0px 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px;'>".$hotel_datos ->hot_poblacion."<br>
									</td>
									<td colspan='1' style='padding:2px 0px 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px;'>".$hotel_datos ->hot_telefono."<br>
									</td>
								</tr>	

						  		<tr>
								      	<td colspan='2' width='50%' bgcolor='#2D5E47' style='padding:2px 0px 2px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Como llegar / Breve Descripción
								      	</td>

								      	<td colspan='2' width='50%' bgcolor='#2D5E47' style='padding:2px 0px 2px 40px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>SELLO DE LA AGENCIA EMISORA
								      	</td>
								</tr>

						        		<tr>
									<td colspan='2' valign='top' style='padding:2px 0px 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$hotel_datos ->hot_como_llegar."<br>".$hotel_datos ->hot_descripciones."
									</td>

									<td colspan='2' style='padding:2px 0px 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'><img src='imagenes/Sello_Agencia_Emisora_blanco.jpg' width='380' height='130'>
									</td>
						  		</tr>";	

								 if($bono_reserva ->city_tax != ''){
					$html_bono_reserva .= "<tr>
									<td colspan='4' style='padding:2px 0px 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>Nota: ".$bono_reserva ->city_tax."<br>
									</td>
								  </tr>";

								}


				$html_bono_reserva .= "</table>
						</td>
					</tr>
				</table>";
					}


					//PIE 

		$html_bono_reserva .= "<table width='100%' border='0' cellspacing='0' cellpadding='0'>
						<tr>
							<td valign='top'>
								<h1 style='font-size:14px; line-height:120%;color:#2D5E47;margin:10px 20px 0px;font-weight:normal;text-align:left;clear:both; display:block;'><strong>TELEFONO DE EMERGENCIAS 24 HORAS: (+34) 91 310 85 07</strong>
								</h1>
							</td>
						</tr>
					</table>

					<p>&nbsp;</p>

				</div>
				</body>
				</html>";


					}
				}
			}

		}

		return $html_bono_reserva;

	}//Servicio 12





	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsInterfaz_reserva($conexion){
		$this->Conexion = $conexion;
	}
}

?>