<?php

class clsAmadeus_api{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_fecha;
	var	$buscar_origen;
	var	$buscar_destino;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
	


		if($buscar_fecha != null){
			$orig = " AND ORIGEN = '".$buscar_origen."'";
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'";
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}

		}elseif($buscar_origen != null){
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE ORIGEN = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
		}elseif($buscar_destino != null){
			$CADENA_BUSCAR = " WHERE DESTINO = '".$buscar_destino."'";
		}else{
			$CADENA_BUSCAR = "";
		}

		$resultado =$conexion->query("SELECT id,
			DATE_FORMAT(a.fecha, '%d-%m-%Y') AS fecha,
			origen, destino, cia, vuelo, 
			time_format(hora_salida, '%H:%i') AS hora_salida,
			time_format(hora_llegada, '%H:%i') AS hora_llegada,
			clase1,disp1,clase2,disp2,clase3,disp3,clase4,disp4,clase5,disp5, clase6,disp6, 
			clase7,disp7,clase8,disp8,clase9,disp9,clase10,disp10, clase11,disp11,clase12, 
			disp12,clase13,disp13,clase14,disp14,clase15,disp15, clase16,disp16, clase17,disp17, 
			clase18,disp18, clase19,disp19, clase20,disp20, clase21,disp21, clase22,disp22, 
			clase23,disp23, clase24,disp24, clase25,disp25, 
			DATE_FORMAT(ultima_actualizacion, '%d-%m-%Y %H:%i:%S') AS ultima_actualizacion
									  FROM hit_amadeus_api a ".$CADENA_BUSCAR." ORDER BY a.ultima_actualizacion");


		//var_dump($resultado);
		/*echo('<pre>');
		print_r($resultado);
		echo('</pre>');*/

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'AMADEUS_API' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$amadeus_api = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['id'] == '' and $num_fila != $Filadesde-1){
				break;
			}
			array_push($amadeus_api,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		/*echo('<pre>');
		print_r($amadeus_api);
		echo('</pre>');*/

		return $amadeus_api;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
	


		if($buscar_fecha != null){
			$orig = " AND ORIGEN = '".$buscar_origen."'";
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'";
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}

		}elseif($buscar_origen != null){
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE ORIGEN = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
		}elseif($buscar_destino != null){
			$CADENA_BUSCAR = " WHERE DESTINO = '".$buscar_destino."'";
		}else{
			$CADENA_BUSCAR = "";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_amadeus_api'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/
		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'AMADEUS_API' AND USUARIO = '".$Usuario."'");
			$Nfilas	 = $num_filas->fetch_assoc();																	  //------
			$cada = $Nfilas['LINEAS_MODIFICACION'] + 1;
			$combo_select = array();
			for ($num_fila = 1; $num_fila <= $numero_filas; $num_fila++) {
				
				if($num_fila == $cada){
					$combo_select[$cada - $Nfilas['LINEAS_MODIFICACION']] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $cada - 1);
					$cada = $cada + $Nfilas['LINEAS_MODIFICACION'];
				}
				if($num_fila == $numero_filas){
					$combo_select[$cada - $Nfilas['LINEAS_MODIFICACION']] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $numero_filas);
				}
			}
			$num_filas->close();
		}else{
			$combo_select[1] = array ( "inicio" => 1, "fin" => 0);
			$resultadoc->close();
		}
		return $combo_select;											
	}

	function Botones_selector($filadesde, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
		
		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
	


		if($buscar_fecha != null){
			$orig = " AND ORIGEN = '".$buscar_origen."'";
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'";
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}

		}elseif($buscar_origen != null){
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE ORIGEN = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
		}elseif($buscar_destino != null){
			$CADENA_BUSCAR = " WHERE DESTINO = '".$buscar_destino."'";
		}else{
			$CADENA_BUSCAR = "";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_amadeus_api'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'AMADEUS_API' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $num_filas->fetch_assoc();
		
		if($boton == 1){
			$selector = 1;
		}elseif($boton == 2){
			$selector = $filadesde - $Nfilas['LINEAS_MODIFICACION'];
		}elseif($boton == 3){
			$selector = $filadesde + $Nfilas['LINEAS_MODIFICACION'];		
		}else{

			$cada = $Nfilas['LINEAS_MODIFICACION'] + 1;
			for ($num_fila = 1; $num_fila <= $numero_filas; $num_fila++) {
				
				if($num_fila == $cada){
					$cada = $cada + $Nfilas['LINEAS_MODIFICACION'];
				}
				if($num_fila == $numero_filas){
					//$combo_select[$num_fila] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $numero_filas);
					$selector = $cada - $Nfilas['LINEAS_MODIFICACION'];
				}
			}
		}

		$resultadoc->close();
		$num_filas->close();
		return $selector;											
	}




	function Cargar_Disponibilidad(){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;


		//PROCEDIMIENTO OBTENIENDO EL XML Y CARGANDOLO EN UN ARRAY
		//El proceso es muy sencillo, necesitas la url del webservice, el método al que vas a llamar y los parámetros a pasarle y, como en cualquier servicio SOAP, te devolverá un XML.

		/*$servicio="http://wwwaps.panavision-tours.es:8089/amadeusgw/dReserva.asp";
		$parametros=array(); //parametros de la llamada
		$parametros['xml']="<PoweredAir_MultiAvailability><messageActionDetails><functionDetails><businessFunction>1</businessFunction><actionCode>44</actionCode></functionDetails></messageActionDetails><requestSection><availabilityProductInfo><availabilityDetails><departureDate>010815</departureDate></availabilityDetails><departureLocationInfo><cityAirport>MAD</cityAirport></departureLocationInfo><arrivalLocationInfo><cityAirport>TCI</cityAirport></arrivalLocationInfo></availabilityProductInfo>textoClasetextoCompania<availabilityOptions><productTypeDetails><typeOfRequest>TN</typeOfRequest></productTypeDetails></availabilityOptions></requestSection></PoweredAir_MultiAvailability>";
		//$client = new SoapClient($servicio, $parametros);
		//$result = $client->getNoticias($parametros);//llamamos al métdo que nos interesa con los parámetros
		$client = new SoapClient($servicio, $parametros);
		$result = $client->call('sayHi', $parametros);  //Solucion obtenida de la internet en: http://www.forosdelweb.com/f45/como-consumir-servicio-web-java-desde-php-437671/
		var_dump($result);*/



		//Borramos la tabla 
		$queryborrar = "delete from hit_amadeus_api";
		$resultadoborrar =$conexion->query($queryborrar);

		if ($resultadoborrar == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
		}

		//OBSTENEMOS DATOS VUELOS DE LOS CONTRATOS/CONTRATOS A ACTUALIZAR
					/*$resultado_vuelos =$conexion->query("select DATE_FORMAT(c.FECHA, '%d%m%y') fecha,
																 c.ORIGEN origen,
																 c.DESTINO destino,
																 c.CIA cia,
																 c.VUELO vuelo
														from hit_transportes_cupos c, hit_transportes_acuerdos a
														where
															c.CIA = a.CIA
															and c.ACUERDO = a.ACUERDO
															and c.SUBACUERDO = a.SUBACUERDO
															and a.TIPO = 'RAC'
															and ((c.ORIGEN = 'MAD' and c.DESTINO = 'TFN')
																  or
																  (c.ORIGEN = 'TFN' and c.DESTINO = 'MAD'))
														   and c.FECHA between curdate() + 1 and DATE_ADD(curdate(),INTERVAL 234 DAY)");*/


					$resultado_vuelos =$conexion->query("select DATE_FORMAT(c.FECHA, '%d%m%y') fecha,
																 c.ORIGEN origen,
																 c.DESTINO destino,
																 c.CIA cia,
																 c.VUELO vuelo
														from hit_transportes_cupos c, hit_transportes_acuerdos a
														where
															c.CIA = a.CIA
															and c.ACUERDO = a.ACUERDO
															and c.SUBACUERDO = a.SUBACUERDO
															and a.TIPO = 'RAC'
															and a.ACUERDO = 5100
														    and c.FECHA between DATE_ADD(curdate(),INTERVAL 1 DAY) and DATE_ADD(curdate(),INTERVAL 150 DAY)
															order by c.FECHA");

					$vuelos = array();
					for ($num_fila = 0; $num_fila < $resultado_vuelos->num_rows; $num_fila++) {
						$resultado_vuelos->data_seek($num_fila);
						$fila = $resultado_vuelos->fetch_assoc();
						array_push($vuelos,$fila);
					}


		//PROBAMOS CON LA FUNCION PARA XML DE MYSQL
		//for($i=0; $i<10; $i++){
		for ($i = 0; $i < count($vuelos); $i++) {
			//echo($vuelos[$i]['fecha'].'-'.$vuelos[$i]['origen'].'-'.$vuelos[$i]['destino'].'-'.$vuelos[$i]['cia'].'-'.$vuelos[$i]['vuelo'].'<br>');
			//$ch1 = curl_init('http://wwwaps.panavision-tours.es:8089/amadeusgw/dReserva.asp?xml=<PoweredAir_MultiAvailability><messageActionDetails><functionDetails><businessFunction>1</businessFunction><actionCode>44</actionCode></functionDetails></messageActionDetails><requestSection><availabilityProductInfo><availabilityDetails><departureDate>010815</departureDate></availabilityDetails><departureLocationInfo><cityAirport>MAD</cityAirport></departureLocationInfo><arrivalLocationInfo><cityAirport>TFN</cityAirport></arrivalLocationInfo></availabilityProductInfo><airlineOrFlightOption><flightIdentification><airlineCode>IB</airlineCode><number>3942</number></flightIdentification></airlineOrFlightOption><availabilityOptions><productTypeDetails><typeOfRequest>TN</typeOfRequest></productTypeDetails></availabilityOptions></requestSection></PoweredAir_MultiAvailability>');
			$ch1 = '';
			$ch1 = curl_init("http://wwwaps.panavision-tours.es:8089/amadeusgw/dReserva.asp?xml=<PoweredAir_MultiAvailability><messageActionDetails><functionDetails><businessFunction>1</businessFunction><actionCode>44</actionCode></functionDetails></messageActionDetails><requestSection><availabilityProductInfo><availabilityDetails><departureDate>".$vuelos[$i]['fecha']."</departureDate></availabilityDetails><departureLocationInfo><cityAirport>".$vuelos[$i]['origen']."</cityAirport></departureLocationInfo><arrivalLocationInfo><cityAirport>".$vuelos[$i]['destino']."</cityAirport></arrivalLocationInfo></availabilityProductInfo><airlineOrFlightOption><flightIdentification><airlineCode>".$vuelos[$i]['cia']."</airlineCode><number>".$vuelos[$i]['vuelo']."</number></flightIdentification></airlineOrFlightOption><availabilityOptions><productTypeDetails><typeOfRequest>TN</typeOfRequest></productTypeDetails></availabilityOptions></requestSection></PoweredAir_MultiAvailability>");

			/*$ch1 = curl_init("http://94.142.204.14:8089/amadeusgw/dReserva.asp?xml=<PoweredAir_MultiAvailability><messageActionDetails><functionDetails><businessFunction>1</businessFunction><actionCode>44</actionCode></functionDetails></messageActionDetails><requestSection><availabilityProductInfo><availabilityDetails><departureDate>".$vuelos[$i]['fecha']."</departureDate></availabilityDetails><departureLocationInfo><cityAirport>".$vuelos[$i]['origen']."</cityAirport></departureLocationInfo><arrivalLocationInfo><cityAirport>".$vuelos[$i]['destino']."</cityAirport></arrivalLocationInfo></availabilityProductInfo><airlineOrFlightOption><flightIdentification><airlineCode>".$vuelos[$i]['cia']."</airlineCode><number>".$vuelos[$i]['vuelo']."</number></flightIdentification></airlineOrFlightOption><availabilityOptions><productTypeDetails><typeOfRequest>TN</typeOfRequest></productTypeDetails></availabilityOptions></requestSection></PoweredAir_MultiAvailability>");*/

			$a = '';
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			$a = curl_exec($ch1);
			//$b = curl_multi_getcontent($ch);
			//var_dump($a === $b);
			curl_close($ch1);
			$c = '';
			//$c= substr($a,38,50000);
			$c= substr($a,38);

			//echo($c);
			//ECHO(strlen($c));
			//var_dump($c);


			$query = "INSERT INTO hit_amadeus_api 
			(FECHA,HORA_SALIDA,HORA_LLEGADA,ORIGEN,DESTINO,CIA,VUELO,CLASE1,CLASE2,CLASE3,CLASE4,CLASE5,CLASE6,CLASE7,CLASE8,CLASE9,CLASE10,CLASE11,CLASE12,CLASE13,CLASE14,CLASE15,CLASE16,CLASE17,CLASE18,CLASE19,CLASE20,CLASE21,CLASE22,CLASE23,CLASE24,CLASE25,DISP1,DISP2,DISP3,DISP4,DISP5,DISP6,DISP7,DISP8,DISP9,DISP10,DISP11,DISP12,DISP13,DISP14,DISP15,DISP16,DISP17,DISP18,DISP19,DISP20,DISP21,DISP22,DISP23,DISP24,DISP25) 
			VALUES (
				
				concat(
					'20',
					substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/flightDetails/departureDate'),5,2),
					'-',
					substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/flightDetails/departureDate'),3,2),
					'-',
					substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/flightDetails/departureDate'),1,2)),


				concat(
					substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/flightDetails/departureTime'),1,2),
					':',
					substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/flightDetails/departureTime'),3,2)),

				concat(
					substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/flightDetails/arrivalTime'),1,2),
					':',
					substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/flightDetails/arrivalTime'),3,2)),

				ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/departureLocation/cityAirport'),
				ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/arrivalLocation/cityAirport'),
				ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/marketingCompany/identifier'),
				ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/basicFlightInfo/flightIdentification/number'),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),1,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),3,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),5,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),7,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),9,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),11,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),13,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),15,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),17,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),19,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),21,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),23,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),25,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),27,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),29,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),31,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),33,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),35,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),37,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),39,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),41,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),43,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),45,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),47,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/serviceClass'),49,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),1,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),3,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),5,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),7,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),9,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),11,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),13,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),15,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),17,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),19,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),21,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),23,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),25,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),27,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),29,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),31,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),33,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),35,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),37,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),39,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),41,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),43,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),45,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),47,1),
				substr(ExtractValue('".$c."', 'XMLResponse/PoweredAir_MultiAvailabilityReply/singleCityPairInfo/flightInfo/infoOnClasses/productClassDetail/availabilityStatus'),49,1)
				)";

			$resultadoi =$conexion->query($query);

			if ($resultadoi == FALSE){
				$respuesta .= $conexion->error;
			}else{
				$respuesta = 'OK';
			}
			//echo($respuesta);
		}

		if($respuesta = 'OK'){
				//Si se han obtenido los datos correctamente llamamos al procedimietno para actualizar los cupos de los acuardos afectados
				$actualiza_cupos_amadeus = "CALL `PROVEEDORES_AMADEUS_ACTUALIZA_CUPOS`()";
				$resultadoactualiza_cupos_amadeus =$conexion->query($actualiza_cupos_amadeus);
								
					if ($resultadoactualiza_cupos_amadeus == FALSE){
						$respuesta .= $conexion->error;
					}else{
						$respuesta = 'OK';
					}	
		}





		return $respuesta;
	}


	function Cryptic($comando){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;




		//PROBAMOS CON LA FUNCION PARA XML DE MYSQL
		//for($i=0; $i<10; $i++){

			$ch1 = '';
			$ch1 = curl_init("http://wwwaps.panavision-tours.es:8089/amadeusgw/dReserva.asp?xml=<Cryptic_GetScreen_Query><Command>".$comando."</Command></Cryptic_GetScreen_Query>");

			//$ch1 = curl_init("http://192.254.254.101:8089/amadeusgw/dReserva.asp?xml=<Cryptic_GetScreen_Query><Command>".$comando."</Command></Cryptic_GetScreen_Query>");

			//$ch1 = curl_init("http://94.142.204.14:8089/amadeusgw/dReserva.asp?xml=<Cryptic_GetScreen_Query><Command>".$comando."</Command></Cryptic_GetScreen_Query>");


			$a = '';
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			$a = curl_exec($ch1);
			//$b = curl_multi_getcontent($ch);
			//var_dump($a === $b);
			curl_close($ch1);
			$c = '';
			$respuesta = $a;
			//$c= substr($a,38);

		return $respuesta;
	}




	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsAmadeus_api($conexion, $filadesde, $usuario, $buscar_fecha, $buscar_origen, $buscar_destino){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_fecha = $buscar_fecha;
		$this->Buscar_origen = $buscar_origen;
		$this->Buscar_destino = $buscar_destino;
	}
}

?>