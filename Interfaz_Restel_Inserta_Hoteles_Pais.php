<?php

	include 'funciones_php/conexiones.php';
	require 'clases/alojamientos_acuerdos.cls.php';

	$conexion = conexion_hit();

	$alojamientos_cargados = 0;
	$alojamientos_interfaces_cargados = 0;
	$alojamientos_contratos_cargados = 0;

	$Usuario = 'jfrias';
	$buscar_interfaz = 'RESTEL';

	//AQUI NECESITAMSO LA QUERY PARA IR LLAMANDADO A TODO EL PROCEDIMIENTO CON CADA LINEA
	/*function Insertar($codigo,$ciudad_provincia,$divisa){*/



	$resultado =$conexion->query("select codigo_dup, ciudad_provincia, codigo
							from
							(
							select  0 codigo_dup, ic.POBLACION ciudad_provincia, ic.CODIGO codigo
							from
								hit_paises pa,
								hit_provincias pr,
								hit_interfaces_codigos_hoteles ic
							where
								pa.CODIGO = pr.PAIS
								and pr.CODIGO = ic.PROVINCIA
								AND pr.VISIBLE_WEB = 'S'
								and ic.CODIGO_DUPLICADOS = 0
								and pa.CODIGO = 'ESP'

							union


							select ic.CODIGO_DUPLICADOS codigo_dup, ic.POBLACION ciudad_provincia, ic.CODIGO codigo
							from
								hit_paises pa,
								hit_provincias pr,
								hit_interfaces_codigos_hoteles ic
							where
								pa.CODIGO = pr.PAIS
								and pr.CODIGO = ic.PROVINCIA
								AND pr.VISIBLE_WEB = 'S'
								and ic.CODIGO_DUPLICADOS <> 0
								and pa.CODIGO = 'ESP'
							GROUP BY ic.CODIGO_DUPLICADOS, ic.POBLACION
							) hoteles
							where 
							codigo not in (900643)");

	for ($k = 0; $k <= $resultado->num_rows; $k++) {
		$resultado->data_seek($k);
		$fila = $resultado->fetch_assoc();


		$codigo = $fila['codigo'];
		$ciudad_provincia = $fila['ciudad_provincia'];
		$codigo_dup = $fila['codigo_dup'];
		$divisa = 'EUR';


		$existe =$conexion->query("select count(*) existe
							from hit_alojamientos_interfaces 
							where codigo_externo = '".$codigo."' or codigo_externo_2 = '".$codigo."' or codigo_externo_3 = '".$codigo."' 
								or codigo_externo_4 = '".$codigo."' or codigo_externo_5 = '".$codigo."' or codigo_externo_6 = '".$codigo."' 
								or codigo_externo_7 = '".$codigo."' or codigo_externo_8 = '".$codigo."' or codigo_externo_9 = '".$codigo."' 
								or codigo_externo_10 = '".$codigo."' or codigo_externo_11 = '".$codigo."' or codigo_externo_12 = '".$codigo."' 
								or codigo_externo_13 = '".$codigo."' or codigo_externo_14 = '".$codigo."'");
		$oexiste = $existe->fetch_assoc();
		$existe_hotel = $oexiste['existe'];



		if($existe_hotel == 0){

			echo($codigo_dup.'-'.$ciudad_provincia.'-'.$codigo.'<br>');
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


			for($i=0;$i<$no_hotels;$i++)
			{
				$datos_hotel = '';
				$hotel = $xml->parametros->hotel[$i];


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
					$alojamientos_cargados++;
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

						$alojamientos_interfaces_cargados++;
						//-------------------------------------------------------------------------
						//ACTUALIZAMOS LOS CODIGOS EXTERNOS SI HAY DUPLICADOS
						//-------------------------------------------------------------------------

						/*$datos_codigo_dup =$conexion->query("select codigo_duplicados from hit_interfaces_codigos_hoteles where codigo = '".$hotel->codigo_hotel."'");
						$odatos_codigo_dup = $datos_codigo_dup->fetch_assoc();
						$codigo_dup = $odatos_codigo_dup['codigo_duplicados'];*/

						$codigo_completo = $hotel->codigo_hotel."#";

						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_DUPLICADOS= '".$codigo_dup."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id_hotel."'";
						$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
						$resultado_cod=$conexion->query($query);

						if($codigo_dup != 0){
							//echo('hay duplicados');
							$resultado2 =$conexion->query("select codigo from hit_interfaces_codigos_hoteles where codigo_duplicados = '".$codigo_dup."' and codigo <> '".$hotel->codigo_hotel."'");

							for ($num_fila = 0; $num_fila <= $resultado2->num_rows; $num_fila++) {
								$resultado2->data_seek($num_fila);
								$fila = $resultado2->fetch_assoc();
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

							$resultado2->close();			
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
							$alojamientos_contratos_cargados++;
						}else{
							$respuesta = $insertaAcuerdos ;
						}


					}
				
				}
			
			}
	
		}
									
	}

	print("<br>Alojamientos cargados:".$alojamientos_cargados);
	print("<br>Interfaces cargados:".$alojamientos_interfaces_cargados);
	print("<br>Contratos cargados:".$alojamientos_contratos_cargados);

?>