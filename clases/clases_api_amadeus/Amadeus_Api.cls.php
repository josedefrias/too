<?php

class clsAmadeus_Api{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var	$buscar_nombre;
	//--------------------------------------------------

	function Cargar_Disponibilidad(){

		$conexion = $this ->Conexion;

		//Consulta
		$Usuario = $this ->Usuario;



		//El proceso es muy sencillo, necesitas la url del webservice, el método al que vas a llamar y los parámetros a pasarle y, como en cualquier servicio SOAP, te devolverá un XML.

		/*$servicio="http://wwwaps.panavision-tours.es:8089/amadeusgw/dReserva.asp";
		$parametros=array(); //parametros de la llamada
		$parametros['xml']="<PoweredAir_MultiAvailability><messageActionDetails><functionDetails><businessFunction>1</businessFunction><actionCode>44</actionCode></functionDetails></messageActionDetails><requestSection><availabilityProductInfo><availabilityDetails><departureDate>010815</departureDate></availabilityDetails><departureLocationInfo><cityAirport>MAD</cityAirport></departureLocationInfo><arrivalLocationInfo><cityAirport>TCI</cityAirport></arrivalLocationInfo></availabilityProductInfo>textoClasetextoCompania<availabilityOptions><productTypeDetails><typeOfRequest>TN</typeOfRequest></productTypeDetails></availabilityOptions></requestSection></PoweredAir_MultiAvailability>";
		//$client = new SoapClient($servicio, $parametros);
		//$result = $client->getNoticias($parametros);//llamamos al métdo que nos interesa con los parámetros
		$client = new SoapClient($servicio, $parametros);
		$result = $client->call('sayHi', $parametros);  //Solucion obtenida de la internet en: http://www.forosdelweb.com/f45/como-consumir-servicio-web-java-desde-php-437671/
		var_dump($result);*/

		//Ejemplo de varios vuelos
		//$ch = curl_init('http://wwwaps.panavision-tours.es:8089/amadeusgw/dReserva.asp?xml=<PoweredAir_MultiAvailability><messageActionDetails><functionDetails><businessFunction>1</businessFunction><actionCode>44</actionCode></functionDetails></messageActionDetails><requestSection><availabilityProductInfo><availabilityDetails><departureDate>010815</departureDate></availabilityDetails><departureLocationInfo><cityAirport>MAD</cityAirport></departureLocationInfo><arrivalLocationInfo><cityAirport>TCI</cityAirport></arrivalLocationInfo></availabilityProductInfo>textoClasetextoCompania<availabilityOptions><productTypeDetails><typeOfRequest>TN</typeOfRequest></productTypeDetails></availabilityOptions></requestSection></PoweredAir_MultiAvailability>');
		//Ejemplo de un vuelos


		for($i=0; $i<10; $i++){

			$ch = curl_init('http://wwwaps.panavision-tours.es:8089/amadeusgw/dReserva.asp?xml=<PoweredAir_MultiAvailability><messageActionDetails><functionDetails><businessFunction>1</businessFunction><actionCode>44</actionCode></functionDetails></messageActionDetails><requestSection><availabilityProductInfo><availabilityDetails><departureDate>010520</departureDate></availabilityDetails><departureLocationInfo><cityAirport>MAD</cityAirport></departureLocationInfo><arrivalLocationInfo><cityAirport>TCI</cityAirport></arrivalLocationInfo></availabilityProductInfo><airlineOrFlightOption><flightIdentification><airlineCode>IB</airlineCode><number>3942</number></flightIdentification></airlineOrFlightOption><availabilityOptions><productTypeDetails><typeOfRequest>TN</typeOfRequest></productTypeDetails></availabilityOptions></requestSection></PoweredAir_MultiAvailability>');

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$a = curl_exec($ch);
			//$b = curl_multi_getcontent($ch);
			//var_dump($a === $b);
			curl_close($ch);
			$c = '';
			$c= substr($a,847,3200);


			//echo($c);
			//ECHO(strlen($c));
			//var_dump($c);

			$query = "INSERT INTO hit_amadeus_api (CADENA) VALUES (";
			$query .= "'".$c."')";

			//$query = "INSERT INTO hit_amadeus_api (CADENA) VALUES (ExtractValue('".$a."', '//departureTime[1]'))";


			$resultadoi =$conexion->query($query);

			if ($resultadoi == FALSE){
				$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
			}else{
				$respuesta = 'OK';
			}
			echo($respuesta);
			//return $respuesta;



		}




		//Con estas sencillas instrucciones ya tenemos en $result el XML resultado de la llamada al servicio. Como trabajar con el XML es un poco engorroso, lo convertimos a un array asociativo de manera que nos sea más sencillo procesar los datos, para ello utilizamos la función obj2array que indico a continuación.

		/*$result = obj2array($result);
		$noticias=$result['resultado']['noticias'];
		$n=count($noticias);

		//procesamos el resultado como con cualquier otro array
		for($i=0; $i<$n; $i++){
		    $noticia=$noticias[$i];
		    $id=$noticia['id'];
		    //aquí iría el resto de tu código donde procesas los datos recibidos
		}

		function obj2array($obj) {
		  $out = array();
		  foreach ($obj as $key => $val) {
		    switch(true) {
		        case is_object($val):
		         $out[$key] = obj2array($val);
		         break;
		      case is_array($val):
		         $out[$key] = obj2array($val);
		         break;
		      default:
		        $out[$key] = $val;
		    }
		  }
		  return $out;
		}*/

		//En la segunda línea nos quedamos con los elementos del array que nos interesa procesar. Si no sabes qué devuelve tu webservice puedes hacer un var_dump($result) y verás todo el resultado. En nuestro caso, como es una secuencia de noticias, nos quedamos con el elemento que tiene esas noticias.







		//CODIGO ANTIGUO DE LA CLASE
		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		/*$buscar_codigo = $this ->Buscar_codigo;
		$buscar_nombre = $this ->Buscar_nombre;
	
		if($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}else{
			$CADENA_BUSCAR = "";
		}

		$resultado =$conexion->query("SELECT codigo,nombre
									  FROM hit_cargos ".$CADENA_BUSCAR." ORDER BY NOMBRE");*/

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//EJEMPLO PARA GUARDAR EN UN ARRAY EL RESULTADO DE LA LLAMADA AL SERVICIO WEB
		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		/*$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CARGOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$cargos = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['codigo'] == '' and $num_fila != $Filadesde-1){
				break;
			}
			array_push($cargos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $cargos;		*/	





	}

	/*function Insertar($codigo, $nombre){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_cargos (CODIGO, NOMBRE) VALUES (";
		$query .= "'".$codigo."',";
		$query .= "'".$nombre."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}*/


	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsAmadeus_Api($conexion, $usuario, $buscar_codigo, $buscar_nombre){
		$this->Conexion = $conexion;
		$this->Usuario = $usuario;
		$this->Buscar_codigo = $buscar_codigo;
		$this->Buscar_nombre = $buscar_nombre;
	}
}

?>