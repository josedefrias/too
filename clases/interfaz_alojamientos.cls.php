<?php

require 'clases/alojamientos_acuerdos.cls.php';

class clsInterfaz_alojamientos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_interfaz;
	var $buscar_provincia;
	var $buscar_poblacion;
	var $buscar_categoria;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		$buscar_interfaz = $this ->Buscar_interfaz;
		$buscar_provincia = $this ->Buscar_provincia;
		$buscar_poblacion = $this ->Buscar_poblacion;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_hotel = $this ->Buscar_hotel;

		//echo($buscar_interfaz."-".$buscar_provincia."-".$buscar_poblacion);
		$interfaz_alojamientos = array();
		
		if($buscar_interfaz == 'RESTEL'){

			//$provincia = 'ESSOR';
			//$provincia = 'FRDIS';
			//$provincia = 'FRPAR';
			$provincia = $buscar_provincia;

			//XML Request
			$xml = "codigousu=" . 'QDZN';
			$xml .= "&clausu=" . 'xml476346';
			$xml .= "&afiliacio=" . 'RS';
			$xml .= "&secacc=" . '110592';
			$xml .= "&xml=";
			$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
			$xml2 .= "<peticion>\n";
			$xml2 .= "<tipo>7</tipo>\n";
			$xml2 .= "<nombre>Petición de Lista de Hoteles</nombre>\n";
			$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
			$xml2 .= "<parametros>\n";
			$xml2 .= "\t<hotel>".$buscar_hotel."</hotel>\n";

			$xml2 .= "\t<pais>" . sprintf("%-5s", $provincia) . "</pais>\n";
			//echo(sprintf("%-5s", $provincia).'<br>' );
			//$xml2 .= "\t<pais>'ES'</pais>\n";
			//$xml2 .= "\t<provincia>'SOR'</provincia>\n";
			//$xml2 .= "\t<poblacion>SORIA</poblacion>\n";
			//$xml2 .= "\t<poblacion>PARIS</poblacion>\n";
			if($buscar_poblacion != ''){
				$xml2 .= "\t<poblacion>".$buscar_poblacion."</poblacion>\n";	
			}
			if($buscar_categoria != ''){
				$xml2 .= "\t<categoria>".$buscar_categoria."</categoria>\n";	
			}

			$xml2 .= "\t<radio>9</radio>\n";
			$xml2 .= "\t<idioma>1</idioma>\n";
			$xml2 .= "\t<afiliacion>" . 'RS' . "</afiliacion>\n";
			$xml2 .= "\t<usuario>" . 'D61366' . "</usuario>\n";
			$xml2 .= "\t<marca>" . 'RS' . "</marca>\n";
			$xml2 .= "\t<duplicidad>0</duplicidad>\n"; //0 PERMITE, 1 NO PERMITE DUPLICADOS
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

			if($xml  == ''){
				echo('EN ESTE MOMENTO NO SE HAN PODIDO OBTENER ALOJAMIENTOS');
				$interfaz_alojamientos = "";
			}else{

				$xml = new SimpleXMLElement($xml);
				$no_hotels = count($xml->parametros->hoteles->hotel);
				//ECHO($respuesta.'<br><br><br>');
				//ECHO($no_hotels.'<br><br><br>');

				$interfaz_alojamientos = array();

				for($i=0;$i<$no_hotels;$i++)
				{
					$hotel = $xml->parametros->hoteles->hotel[$i];

					$categoria2_nombre='';
					switch ($hotel->categoria2) {
					   case 'BA':
					         $categoria2_nombre = 'Basic';
					         break;
					   case 'CO':
					         $categoria2_nombre = 'Confort';
					         break;
					   case 'PE':
					         $categoria2_nombre = 'Premier';
					         break;
					   case 'EX':
					         $categoria2_nombre = 'Excellent';
					         break;
					   case 'LC':
					         $categoria2_nombre = 'Luxury Class';
					         break;
					}

					$deshabilita = '';
					$existe =$conexion->query("SELECT count(*) existe FROM hit_alojamientos_interfaces WHERE codigo_interfaz = 'RESTEL' and ( codigo_externo = '".$hotel->codigo_cobol."' or codigo_externo_2 = '".$hotel->codigo_cobol."' or codigo_externo_3 = '".$hotel->codigo_cobol."' or codigo_externo_4 = '".$hotel->codigo_cobol."' or codigo_externo_5 = '".$hotel->codigo_cobol."' or codigo_externo_6 = '".$hotel->codigo_cobol."' or codigo_externo_7 = '".$hotel->codigo_cobol."')");

					$Oexiste = $existe->fetch_assoc();								
					$Oexiste['existe'];
					if($Oexiste['existe'] > 0){
						$deshabilita = 'disabled';
					}			

					$interfaz_alojamientos[$i] = array ( "valor" => 'L', "mostrar" => 'Lunes',
									"numero" => $i + 1,
									"codigo" => $hotel->codigo_cobol,
									"codigo_interno" => $hotel->codigo,
									"afiliacion" => $hotel->afiliacion,
									"nombre" => $hotel->nombre_h,
									"direccion" => $hotel->direccion,
									"provincia" => $hotel->provincia,
									"provincia_nombre" => $hotel->provincia_nombre,
									"poblacion" => $hotel->poblacion,
									"descripcion" => $hotel->descripcion,
									"como_llegar" => $hotel->como_llegar,
									"categoria" => $hotel->categoria,
									"foto" => $hotel->foto,
									"calidad" => $hotel->calidad,
									"marca" => $hotel->marca,
									"longitud" => $hotel->longitud,
									"latitud" => $hotel->latitud,
									"categoria2" => $hotel->categoria2,
									"categoria2_nombre" => $categoria2_nombre,
									"divisa" => $hotel->currency,
									"habilitado" => $deshabilita);


				}
				//print($i);
			}
		}

		return $interfaz_alojamientos;

	}

	function Cargar_lineas_nuevas(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'INTERFAZ_ALOJAMIENTOS' AND USUARIO = '".$Usuario."'");
		if ($num_filas == FALSE){
			echo('error al leer lineas nuevas');
			$Nfilasnuevas = 2;
		}
		$Nfilasnuevas	 = $num_filas->fetch_assoc();																	  //------
		$combo_nuevas = array();
		for ($num_fila = 0; $num_fila <= $Nfilasnuevas['LINEAS_NUEVAS']-1; $num_fila++) {
			$combo_nuevas[$num_fila] = array ("linea" => $num_fila);
		}

		$num_filas->close();
		return $combo_nuevas;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		$buscar_interfaz = $this ->Buscar_interfaz;
		$buscar_provincia = $this ->Buscar_provincia;
		$buscar_poblacion = $this ->Buscar_poblacion;
	
		if($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}else{
			$CADENA_BUSCAR = "";
		}												
		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_interfaz_alojamientos'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'INTERFAZ_ALOJAMIENTOS' AND USUARIO = '".$Usuario."'");
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
												
		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_nombre = $this ->Buscar_nombre;
	
		if($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}else{
			$CADENA_BUSCAR = "";
		}												
		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_interfaz_alojamientos'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'INTERFAZ_ALOJAMIENTOS' AND USUARIO = '".$Usuario."'");
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

	/*function Modificar($codigo, $nombre, $area, $pais, $departamento, $provincia){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_interfaz_alojamientos SET ";
		$query .= " NOMBRE = '".$nombre."'";
		//$query .= ", ZONA_HORARIA = '".$zona_horaria."'";
		$query .= ", AREA = '".$area."'";
		$query .= ", PAIS = '".$pais."'";
		$query .= ", DEPARTAMENTO = '".$departamento."'";
		$query .= ", PROVINCIA = '".$provincia."'";
		$query .= " WHERE CODIGO = '".$codigo."'";

		//echo($query);

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}*/

	/*function Borrar($codigo){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_interfaz_alojamientos WHERE CODIGO = '".$codigo."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}*/

	function Insertar($codigo,$ciudad_provincia,$alojamiento_hits,$divisa){

		$buscar_interfaz = $this ->Buscar_interfaz;
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		//echo($alojamiento_hits);

		if($buscar_interfaz == 'RESTEL'){


			if($alojamiento_hits == null){
				//echo('nuevo hotel');

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
					$datos_hotel = '';
					$hotel = $xml->parametros->hotel[$i];

					        /*$datos_hotel .= '<strong>DATOS PRINCIPALES</strong><BR><BR>';

					        $datos_hotel .= '<TABLE BORDER=1>';
					        $datos_hotel .= '<TR><TD><strong>NOMBRE:</strong></TD><TD><strong>'.$hotel->nombre_h.'</strong></TD></TR>';//ok
					        $datos_hotel .= '<TR><TD>PAIS:</TD><TD>'.$hotel->pais.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>CODIGO HOTEL:</TD><TD>'.$hotel->codigo_hotel.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>CODIGO INTERNO:</TD><TD>'.$hotel->codigo.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>AFILIACION:</TD><TD>'.$hotel->hot_afiliacion.'</TD></TR>';
					        $datos_hotel .=  '<TR><TD>CATEGORIA:</TD><TD>'.$hotel->categoria.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>DIRECCION:</TD><TD>'.$hotel->direccion.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>COD PROVINCIA:</TD><TD>'.$hotel->codprovincia.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>NOMBRE PROVINCIA:</TD><TD>'.$hotel->provincia.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>COD POBLACION:</TD><TD>'.$hotel->codpoblacion.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>POBLACION:</TD><TD>'.$hotel->poblacion.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>CODIGO POSTAL:</TD><TD>'.$hotel->cp.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>COD DUPLICADO:</TD><TD>'.$hotel->coddup.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>MAIL:</TD><TD>'.$hotel->mail.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>WEB:</TD><TD>'.$hotel->web.'</TD></TR>';
					        $datos_hotel .= '</TABLE>';


					        $datos_hotel .= '<BR><BR><strong>FOTOS</strong><BR><BR>';

					        $datos_hotel .= '<TABLE BORDER=1>';
					        for($j=0;$j<=20;$j++){
					        	if($hotel->fotos->foto[$j] != ''){
						        $datos_hotel .= '<TR><TD>FOTO '.$j.' URL:'.$hotel->fotos->foto[$j].'</TD></TR>';
						        $datos_hotel .= '<TR><TD><img src=\''.$hotel->fotos->foto[$j].'\'</img></TD></TR>';
						 }
					        }
					        $datos_hotel .= '</TABLE>';

					        $datos_hotel .= '<BR><BR><strong>DATOS GENERALES</strong><BR><BR>';

					        $datos_hotel .= '<TABLE BORDER=1>';
					        $datos_hotel .= '<TR><TD>URL PLANO:</TD><TD>'.$hotel->plano.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>PLANO:</TD><TD><img src=\''.$hotel->plano.'\'</img></TD></TR>';
					        $datos_hotel .= '<TR><TD>DESCRIPCION BREVE:</TD><TD>'.$hotel->desc_hotel.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>NUMERO DE HABITACIONES:</TD><TD>'.$hotel->num_habitaciones.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>COMO LLEGAR:</TD><TD>'.$hotel->como_llegar.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>TIPO DE ESTABLECIMIENTO:</TD><TD>'.$hotel->tipo_establecimiento.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>CATEGORIA 2:</TD><TD>'.$hotel->categoria2.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>URL LOGO:</TD><TD>'.$hotel->logo_h.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>LOGO:</TD><TD><img src=\''.$hotel->logo_h.'\'</img></TD></TR>';
					        $datos_hotel .= '<TR><TD>CHECK IN:</TD><TD>'.$hotel->checkin.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>CHECK OUT:</TD><TD>'.$hotel->checkout.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>EDAD NIÑOS DESDE:</TD><TD>'.$hotel->edadnindes.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>EDAD NIÑOS HASTA:</TD><TD>'.$hotel->edadninhas.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>DIVISA:</TD><TD>'.$hotel->currency.'</TD></TR>';


					        $datos_hotel .= '<TR><TD>MARCA:</TD><TD>'.$hotel->marca.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>LONGITUD:</TD><TD>'.$hotel->longitud.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>LATITUD:</TD><TD>'.$hotel->latitud.'</TD></TR>';
					        $datos_hotel .= '<TR><TD>CITY TAX:</TD><TD>'.$hotel->city_tax.'<b/TD></TRr>'; 
					        $datos_hotel .= '</TABLE>';



					        $datos_hotel .= '<BR><BR><strong>SERVICIOS HOTEL</strong><BR><BR>';
					        $datos_hotel .= '<TABLE BORDER=1><TR><TD>SERVICIO</TD><TD>CODIGO</TD><TD>DESCRIPCION</TD></TR>';
					        for($k=1;$k<=20;$k++){
					        	if(@$hotel->servicios->servicio[$k]->codigo_servicio){
						        $datos_hotel .= '<TR><TD>'.$k.'</TD><TD>'.$hotel->servicios->servicio[$k]->codigo_servicio.'</TD><TD>'.$hotel->servicios->servicio[$k]->desc_serv.'</TD></TR>';
						 }
					        }
					        $datos_hotel .= '</TABLE>';


					        $datos_hotel .= '<BR><BR><strong>SERVICIOS HABITACION</strong><BR><BR>';
					        $datos_hotel .= '<TABLE BORDER=1><TR><TD>SERVICIO</TD><TD>CODIGO</TD><TD>DESCRIPCION</TD></TR>';
					        for($m=1;$m<=20;$m++){
					        	if(@$hotel->servicios_habitacion->servicio_habitacion[$m]->codigo_servicio_hab){
						        $datos_hotel .= '<TR><TD>'.$m.'</TD><TD>'.$hotel->servicios_habitacion->servicio_habitacion[$m]->codigo_servicio_hab.'</TD><TD>'.$hotel->servicios_habitacion->servicio_habitacion[$m]->descripciones.'</TD></TR>';
						 }
					        }
					        $datos_hotel .= '</TABLE>';


					        $datos_hotel .= '<BR><BR><strong>DISTANCIAS</strong><BR><BR>';
					        $datos_hotel .= '<TABLE BORDER=1><TR><TD>LUGAR</TD><TD>KMS</TD><TD>METROS</TD><TD>PIE/HORAS</TD><TD>PIE/MINUTOS</TD><TD>COCHE HORAS</TD><TD>COCHE MINUTOS</TD></TR>';
					        for($n=1;$n<=20;$n++){
					        	if(@$hotel->distancias->distancia[$n]->lugar){
						        $datos_hotel .= "<TR><TD>".$hotel->distancias->distancia[$n]->lugar."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->kms."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->mts."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->pie_horas."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->pie_min."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->coche_horas."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->coche_min."</TD></TR>";
						 }
					        }
					        $datos_hotel .= '</TABLE>';

					        $datos_hotel .= "<BR><BR><strong>SALONES</strong><BR><BR>";
					        $datos_hotel .= "<TABLE BORDER=1><TR><TD>CODIGO</TD><TD>NOMBRE</TD><TD align='center'>METROS CUADRADOS</TD><TD align='center'>CAPACIDAD PARA TEATRO </TD><TD align='center'>CAPACIDAD PARA ESCUELA</TD><TD align='center'>CAPACIDAD PARA COCKTAIL</TD><TD align='center'>CAPACIDAD PARA BANQUETE</TD><TD align='center'>CAPACIDAD DE MESAS</TD></TR>";
					        for($o=1;$o<=20;$o++){
					        	if(@$hotel->salones->salon[$o]->codigo_salon){
						        $datos_hotel .= "<TR><TD>".$hotel->salones->salon[$o]->codigo_salon."</TD>";
						        $datos_hotel .= "<TD>".$hotel->salones->salon[$o]->nombre."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->m2."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->teadro."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->escuela."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->cocktail."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->banquete."</TD>";
						        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->mesau."</TD></TR>";

						 }
					        }
					        $datos_hotel .= '</TABLE>';*/


					//echo($datos_hotel);


					 //----------------------------------
					 //INSERTAMOS EL ALOJAMIENTO
					 //----------------------------------     
					 $nombre_revisado =   str_replace("'", "", $hotel->nombre_h);
					 $nombre_revisado =   str_replace("&", "AND", $nombre_revisado);

					 $direccion_revisado = str_replace("'", "", $hotel->direccion);
					 $direccion_revisado = str_replace("&", "AND", $direccion_revisado);

					 $descripcion_revisado = str_replace("'", "", $hotel->desc_hotel);

					$query = "INSERT INTO hit_alojamientos (NOMBRE,TIPO,CIUDAD,CATEGORIA,DIRECCION,CODIGO_POSTAL,TELEFONO, EMAIL,LOCALIDAD,VISIBLE,UBICACION,DESCRIPCION,DESCRIPCION_COMPLETA,COMO_LLEGAR,URL,LATITUD,LONGITUD) VALUES (";
					$query .= "'".$nombre_revisado."',";


					$query .= "'".$hotel->tipo_establecimiento."',";
					$query .= "'".$ciudad_provincia."',";
					$query .= "'".$hotel->categoria."',";
					$query .= "'".$direccion_revisado."',";					
					$query .= "'".$hotel->cp."',";
					$query .= "0,";
					$query .= "'".$hotel->mail."',";
					$query .= "'".str_replace("'", "", $hotel->poblacion)."',";
					$query .= "'S',";
					$query .= "'".str_replace("'", "", $hotel->desc_hotel)."',";
					$query .= "'".str_replace("'", "", $hotel->desc_hotel)."',";
					$query .= "'".str_replace("'", "", $hotel->desc_hotel)."',";
					$query .= "'".str_replace("'", "", $hotel->desc_hotel)."',";
					$query .= "'".$hotel->web."',";
					$query .= "'".$hotel->latitud."',";
					$query .= "'".$hotel->longitud."')";

					$resultadoi =$conexion->query($query);

					if ($resultadoi == FALSE){
						$respuesta = 'No se ha podido insertar el nuevo hotel. '.$conexion->error;
					}else{

						//----------------------------------------
						//INSERTAMOS LA LINEA DE INTERFAZ
						//----------------------------------------
						$datos_alojamiento =$conexion->query("SELECT  id from hit_alojamientos where substr(nombre,1,50) = substr('".$nombre_revisado."',1,50) and substr(direccion,1,90)= substr('".$direccion_revisado."',1,90) and substr(descripcion,1,200) = substr('".$descripcion_revisado."',1,200)" );

						$odatos_alojamiento = $datos_alojamiento->fetch_assoc();

						$id_hotel= $odatos_alojamiento['id'];

						$query = "INSERT INTO hit_alojamientos_interfaces (ID_ALOJAMIENTO, CODIGO_INTERFAZ, SALIDAS_DESDE, SALIDAS_HASTA,ORDEN_APLICACION,CODIGO_EXTERNO) VALUES (";
						$query .= "'".$id_hotel."',";
						$query .= "'RESTEL',";
						$query .= "'2016-01-01',";
						$query .= "'2030-12-31',";
						$query .= "1,";
						$query .= "'".$hotel->codigo_hotel."')";

						$resultadoi =$conexion->query($query);

						if ($resultadoi == FALSE){
							$respuesta = 'No se ha podido insertar la linea de interfaz en el hotel. '.$conexion->error;
						}else{


							//-------------------------------------------------------------------------
							//ACTUALIZAMOS LOS CODIGOS EXTERNOS SI HAY DUPLICADOS
							//-------------------------------------------------------------------------

							$datos_codigo_dup =$conexion->query("select codigo_duplicados from hit_interfaces_codigos_hoteles where codigo = '".$hotel->codigo_hotel."'");
							$odatos_codigo_dup = $datos_codigo_dup->fetch_assoc();
							$codigo_dup = $odatos_codigo_dup['codigo_duplicados'];

							$codigo_completo = $hotel->codigo_hotel."#";

							$query = "UPDATE hit_alojamientos_interfaces SET ";
							$query .= "CODIGO_DUPLICADOS= '".$codigo_dup."'";
							$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
							$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
							$resultado_cod=$conexion->query($query);


							if($codigo_dup != 0){
								//echo('hay duplicados');
								$resultado =$conexion->query("select codigo from hit_interfaces_codigos_hoteles where codigo_duplicados = '".$codigo_dup."' and codigo <> '".$hotel->codigo_hotel."'");

								for ($num_fila = 0; $num_fila <= $resultado->num_rows; $num_fila++) {
									$resultado->data_seek($num_fila);
									$fila = $resultado->fetch_assoc();
									//echo(' Actualizamos los codigos: '.$fila['codigo']);
									//Actualizamos los codigos duplicados del interfaz
									switch ($num_fila) {
									   case 0:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_2= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod=$conexion->query($query);
									          break;
									   case 1:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_3= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;
									   case 2:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_4= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;
									    case 3:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_5= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;
									     case 4:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_6= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;
									     case 5:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_7= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;
									     case 6:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_8= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;
									     case 7:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_9= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;
									     case 8:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_10= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;	
									      case 9:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_11= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;	
									     case 10:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_12= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;	
									     case 11:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_13= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;	
									     case 12:
										$query = "UPDATE hit_alojamientos_interfaces SET ";
										$query .= "CODIGO_EXTERNO_14= '".$fila['codigo']."'";
										$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
										$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
										$resultado_cod =$conexion->query($query);
									          break;						 
									}

									if($fila['codigo'] != null){
										$codigo_completo .= $fila['codigo']."#";
									}
								}

								$resultado->close();			
							}

							$query = "UPDATE hit_alojamientos_interfaces SET ";
							$query .= "CODIGO_EXTERNO_COMPLETO= '".$codigo_completo."'";
							$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
							$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
							$resultado_cod=$conexion->query($query);

							//-------------------------------
							//INSERTAMOS LAS IMAGENES
							//-------------------------------
							for($j=0;$j<=20;$j++){
							        	if($hotel->fotos->foto[$j] != ''){
								        $datos_hotel .= '<TR><TD>FOTO '.$j.' URL:'.$hotel->fotos->foto[$j].'</TD></TR>';


									$query = "INSERT INTO hit_alojamientos_imagenes (ALOJAMIENTO, TIPO, NUMERO,URL) VALUES (";
									$query .= "'".$id_hotel."',";
									$query .= "'P',";
									$query .= "".$j." + 1,";
									$query .= "'".$hotel->fotos->foto[$j]."')";

									$resultadoi =$conexion->query($query);

									if ($resultadoi == FALSE){
										$respuesta = 'No se han podido insertar todas las imagenes. '.$conexion->error;
									}else{
										$respuesta = 'OK';

									}
								}
							}

							//----------------------------------------------------
							//INSERTAMOS EL ACUERDO (TODAS LAS TABLAS)
							//----------------------------------------------------

							//CREAMOS EL ACUERDO 

							$iAcuerdos = new clsAcuerdos($conexion, 0, $Usuario, $id_hotel, 'Restel', 0);
							$insertaAcuerdos = $iAcuerdos->Insertar_acuerdos_interfaz($id_hotel, $divisa, $buscar_interfaz, '2016-01-01', '2030-12-31');


							if($insertaAcuerdos == 'OK'){
								$respuesta = 'OK';
							}else{
								$respuesta = $insertaAcuerdos ;
							}


						}
					}
				}
			}else{
				//echo('nueva linea de interfaz');
				$query = "INSERT INTO hit_alojamientos_interfaces (ID_ALOJAMIENTO, CODIGO_INTERFAZ, SALIDAS_DESDE, SALIDAS_HASTA,ORDEN_APLICACION,CODIGO_EXTERNO) VALUES (";
				$query .= "'".$alojamiento_hits."',";
				$query .= "'RESTEL',";
				$query .= "'2016-01-01',";
				$query .= "'2030-12-31',";
				$query .= "1,";
				$query .= "'".$codigo."')";

				$resultadoi =$conexion->query($query);

				if ($resultadoi == FALSE){
					$respuesta = 'No se ha podido insertar la linea de interfaz en el hotel. '.$conexion->error;
				}else{

					$respuesta = 'OK';

					//----------------------------------------------------
					//INSERTAMOS EL ACUERDO (TODAS LAS TABLAS)
					//----------------------------------------------------

					//CREAMOS EL ACUERDO 

					$iAcuerdos = new clsAcuerdos($conexion, 0, $Usuario, $alojamiento_hits, 'Restel', 0);
					$insertaAcuerdos = $iAcuerdos->Insertar_acuerdos_interfaz($alojamiento_hits, $divisa, $buscar_interfaz, '2016-01-01', '2030-12-31');


					if($insertaAcuerdos == 'OK'){
						$respuesta = 'OK';
					}else{
						$respuesta = $insertaAcuerdos ;
					}
					
				}
			}	
		}

		return $respuesta;											
	}


	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsInterfaz_alojamientos($conexion, $filadesde, $usuario, $buscar_interfaz, $buscar_provincia, $buscar_poblacion, $buscar_categoria, $buscar_hotel){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_interfaz = $buscar_interfaz;
		$this->Buscar_provincia = $buscar_provincia;
		$this->Buscar_poblacion = $buscar_poblacion;
		$this->Buscar_categoria = $buscar_categoria;
		$this->Buscar_hotel = $buscar_hotel;
	}
}

?>