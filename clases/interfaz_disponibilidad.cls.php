<?php

class clsInterfaz_disponibilidad{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_interfaz;
	var $buscar_provincia;
	var $buscar_poblacion;
	var $buscar_categoria;
	var $buscar_fecha_entrada;
	var $buscar_fecha_salida;
	var $buscar_hotel;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		$buscar_interfaz = $this ->Buscar_interfaz;
		$buscar_provincia = $this ->Buscar_provincia;
		$buscar_poblacion = $this ->Buscar_poblacion;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_fecha_entrada	= $this->Buscar_fecha_entrada;
		$buscar_fecha_salida = $this->Buscar_fecha_salida;
		$buscar_hotel = $this->Buscar_hotel;
		$buscar_habitaciones = $this->Buscar_habitaciones;
		$buscar_adultos = $this->Buscar_adultos;
		$buscar_ninos = $this->Buscar_ninos;


		if($buscar_habitaciones == ''){
			$buscar_habitaciones = 1;
		}

		if($buscar_adultos == ''){
			$buscar_adultos = 2;
		}

		if($buscar_ninos == ''){
			$buscar_ninos = 0;
		}


		$fecha_in = date("m/d/Y",strtotime($buscar_fecha_entrada));
		$fecha_out = date("m/d/Y",strtotime($buscar_fecha_salida));

		$pais = substr($buscar_provincia,0,2);

		//echo($buscar_interfaz."-".$buscar_provincia."-".$buscar_poblacion."-".$buscar_categoria."-".$fecha_in."-".$fecha_out."-".$buscar_hotel."-".$buscar_habitaciones."-".$buscar_adultos."-".$buscar_ninos);
		$interfaz_disponibilidad = array();
		
		if($buscar_interfaz == 'RESTEL'){



			//XML Request

			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
			$xml2 .= "<tipo>110</tipo>\n";
			$xml2 .= "<nombre>Petición de Disponibilidad</nombre>\n";
			$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
			$xml2 .= "<parametros>\n";




			$xml2 .= "<hotel>".$buscar_hotel."</hotel>"; //Para consultar varios hoteles  por código en vez de por nobre hay que poner cada código seguido de # 
			$xml2 .= "<pais>".$pais."</pais>";
			$xml2 .= "<provincia>".$buscar_provincia."</provincia>";

			$xml2 .= "<poblacion>".$buscar_poblacion."</poblacion>";

			$xml2 .= "<categoria>".$buscar_categoria."</categoria>";
			$xml2 .= "<radio>9</radio>";

			/*$xml2 .= "<fechaentrada>01/22/2016</fechaentrada>";
			$xml2 .= "<fechasalida>01/24/2016</fechasalida>";*/

			$xml2 .= "<fechaentrada>".$fecha_in."</fechaentrada>";
			$xml2 .= "<fechasalida>".$fecha_out."</fechasalida>";


			$xml2 .= "<afiliacion>RS</afiliacion>";
			$xml2 .= "<usuario>D61366</usuario>";

			$xml2 .= "<numhab1>".$buscar_habitaciones."</numhab1>"; //Número de habitaciones tipo 1.
			$xml2 .= "<paxes1>".$buscar_adultos."-".$buscar_ninos."</paxes1>"; //Formato: adulto-niño. Ejem: Para pedir dos adultos y un niño sería: 2-1.

			//$xml2 .= "<numhab2>".$codigo."</numhab2>";
			//$xml2 .= "<paxes2>".$codigo."</paxes2>";

			//$xml2 .= "<paxes2>".$codigo."</paxes2>";
			//$xml2 .= "<paxes2>".$codigo."</paxes2>";

			//$xml2 .= "<restricciones>1</restricciones>";
			//Opción para mostrar los hoteles con restricciones (estancias mínimas).

			$xml2 .= "<idioma>1</idioma>";

			$xml2 .= "<duplicidad>1</duplicidad>";   
			//La duplicidad nos servirá para filtrar duplicados, es decir, en ciertas ocasiones, un hotel nos puede ofrecer diferentes ofertas, si queremos que aparezcan todas ellas en el listado bastará con no incluir este tag o dejarlo a cero, si queremos que en el listado aparezca sólo la mejor de las ofertas dejaremos el tag en valor 1 (el criterio de mejor oferta se basa en este orden: mejor disponibilidad/mejor precio de la primera habitacion/regimen que encuentre).

			$xml2 .= "<comprimido>2</comprimido>"; 
			$xml2 .= "<informacion_hotel>0</informacion_hotel>"; 
			$xml2 .= "<tarifas_reembolsables>2</tarifas_reembolsables>";  //Ojo este tag indica las opciones de tarifas. Mirar bien antes de poner en produccion. 0:Tarifa mas barata, 1:Tarifa alternativa, 2:Tarifas reembolsables, 3:Tarifas indicando entrada en gastos.


			$xml2 .= "</parametros>\n";
			$xml2 .= "</peticion>";


			//echo htmlentities($xml2);


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

			/*$xml3 = simplexml_load_string($xml);
			echo $xml3;*/

			while(!feof($fp)) $respuesta .= fgets($fp);
			fclose ($fp);

			



			//XML Answer
			//echo($respuesta);
			$xml = substr($respuesta, strpos($respuesta, "<?xml"));
			
			//echo($xml);
			

			//$xml = new SimpleXMLElement($xmlstr); //Simple XML is available from php5
			$xml = new SimpleXMLElement($xml);
			
		


			$no_disp = $xml->param->hotls->attributes()->num;

			$interfaz_disponibilidad = array();

			$indice = 0;
			$hotel_vuelta = 0;
			$habitacion_vuelta = '';

			for($i=0;$i<$no_disp;$i++)
			{

				$dispo = $xml->param->hotls->hot[$i];

				        /*$datos_dispo .= "<BR><BR><strong>".$dispo->nom."</strong> (".count($dispo->res->pax->hab)." Tipos de Habitacion)<BR><BR>";*/

				       $no_hab = count($dispo->res->pax->hab);
				       for($j=0;$j<$no_hab;$j++){

				        	       $habit = $dispo->res->pax->hab[$j];


					        $no_reg = count($habit->reg);
				       	        for($k=0;$k<$no_reg;$k++){

				       	        	$regi = $habit->reg[$k];

						 if($regi ->attributes()->nr == 0){
						 	$gastos = 'Sin gastos Restrictivos';
						 }elseif($regi ->attributes()->nr == 1){
						 	$gastos = 'Tarifa con 100%';
						 }else{
						 	$gastos = 'La reserva ya entra en gastos';
						 }

						 /*$datos_dispo .= "<TD align='left'>".$regi ->lin[0]."</TD>";
						 $datos_dispo .= "<TD align='left'>".$regi ->lin[1]."</TD>";*/

						 if($hotel_vuelta != $dispo->cod){

							$interfaz_disponibilidad[$indice] = array ( 
											"numero" => $i + 1,
											"codigo" => $dispo->cod,
											"afiliacion" => $dispo->afi,
											"nombre" => $dispo->nom,
											"provincia" => $dispo->prn,
											"poblacion" => $dispo->pob,
											"categoria" => $dispo->cat,
											"entrada" => date("d-m-Y",strtotime($dispo->fen)),
											"salida" => date("d-m-Y",strtotime($dispo->fsa)),
											"pdr" => $dispo->pdr,  //pago directo
											"calidad" => $dispo->cal,
											"marca" => $dispo->mar,
											"edad_ninos_desde" => $dispo->end,
											"edad_ninos_hasta" => $dispo->enh,

											"hab_tipo" => $habit ->attributes()->cod,
											"hab_descripcion" => $habit ->attributes()->desc,

											"regimen" => $regi ->attributes()->cod,
											"precio" => $regi ->attributes()->prr,
											"divisa" => $regi ->attributes()->div,
											"estado" => $regi ->attributes()->esr,
											"pvp_min" => $regi ->attributes()->pvp,
											"gastos" => $gastos,
											);
						}else{
							if($habitacion_vuelta != $habit ->attributes()->desc){
								$interfaz_disponibilidad[$indice] = array ( 
												"numero" => "",
												"codigo" => "",
												"afiliacion" => "",
												"nombre" => "",
												"provincia" => "",
												"poblacion" => "",
												"categoria" => "",
												"entrada" => "",
												"salida" => "",
												"pdr" => "",  //pago directo
												"calidad" => "",
												"marca" => "",
												"edad_ninos_desde" =>  "",
												"edad_ninos_hasta" => "",

												"hab_tipo" => $habit ->attributes()->cod,
												"hab_descripcion" => $habit ->attributes()->desc,

												"regimen" => $regi ->attributes()->cod,
												"precio" => $regi ->attributes()->prr,
												"divisa" => $regi ->attributes()->div,
												"estado" => $regi ->attributes()->esr,
												"pvp_min" => $regi ->attributes()->pvp,
												"gastos" => $gastos,
												);
							}else{
								$interfaz_disponibilidad[$indice] = array ( 
												"numero" => "",
												"codigo" => "",
												"afiliacion" => "",
												"nombre" => "",
												"provincia" => "",
												"poblacion" => "",
												"categoria" => "",
												"entrada" => "",
												"salida" => "",
												"pdr" => "",  //pago directo
												"calidad" => "",
												"marca" => "",
												"edad_ninos_desde" =>  "",
												"edad_ninos_hasta" => "",

												"hab_tipo" => "",
												"hab_descripcion" => "",

												"regimen" => $regi ->attributes()->cod,
												"precio" => $regi ->attributes()->prr,
												"divisa" => $regi ->attributes()->div,
												"estado" => $regi ->attributes()->esr,
												"pvp_min" => $regi ->attributes()->pvp,
												"gastos" => $gastos,
												);

							}
						}

						$indice++;
						$hotel_vuelta = $dispo->cod;
						$habitacion_vuelta = $habit ->attributes()->desc;

				       	        }

				        	       /*echo('<pre>');
					        print_r($habit);
					       echo('</pre>');*/

				        }

			}

			//print($i);

		}

		/*echo('<pre>');
		print_r($interfaz_disponibilidad);
		echo('</pre>');*/

		return $interfaz_disponibilidad;

	}


	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsInterfaz_disponibilidad($conexion, $filadesde, $usuario, $buscar_interfaz, $buscar_provincia, $buscar_poblacion, $buscar_categoria, $buscar_fecha_entrada, $buscar_fecha_salida, $buscar_hotel, $buscar_habitaciones, $buscar_adultos, $buscar_ninos){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_interfaz = $buscar_interfaz;
		$this->Buscar_provincia = $buscar_provincia;
		$this->Buscar_poblacion = $buscar_poblacion;
		$this->Buscar_categoria = $buscar_categoria;
		$this->Buscar_fecha_entrada = $buscar_fecha_entrada;
		$this->Buscar_fecha_salida = $buscar_fecha_salida;
		$this->Buscar_hotel = $buscar_hotel;
		$this->Buscar_habitaciones = $buscar_habitaciones;
		$this->Buscar_adultos = $buscar_adultos;
		$this->Buscar_ninos = $buscar_ninos;
	}
}

?>