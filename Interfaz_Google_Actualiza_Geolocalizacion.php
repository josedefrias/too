<?php

	include 'funciones_php/conexiones.php';

	$conexion = conexion_hit();

	$parametrosg = $_GET;
	$actualizaziones = 0;

	if($parametrosg['tipo'] == 'CIUDAD'){
		$resultado =$conexion->query("select c.NOMBRE ciudad, p.NOMBRE provincia, pa.NOMBRE pais, c.CODIGO codigo
								from hit_ciudades c, hit_provincias p, hit_paises pa
								where
									c.PROVINCIA = p.CODIGO
									and p.PAIS = pa.CODIGO
									and c.GEOLOC_UBICACION_LATITUD is null");
	}elseif($parametrosg['tipo'] == 'PROVINCIA'){
		$resultado =$conexion->query("select p.NOMBRE provincia, pa.NOMBRE pais, p.CODIGO codigo 
								from hit_provincias p, hit_paises pa
								where
									p.PAIS = pa.CODIGO
									and p.GEOLOC_UBICACION_LATITUD is null");
	}elseif($parametrosg['tipo'] == 'PAIS'){
		$resultado =$conexion->query("select pa.NOMBRE pais, pa.CODIGO codigo 
							from hit_paises pa
							where
								pa.GEOLOC_UBICACION_LATITUD is null");	
	}elseif($parametrosg['tipo'] == 'ZONA'){
		$resultado =$conexion->query("select z.nombre zona,
							case 
								when pr.NOMBRE is not null then pr.NOMBRE 
								else d.NOMBRE 
								end destino, 
							 pa.NOMBRE pais, z.CODIGO codigo 
							from hit_zonas z, 
								  hit_destinos d left join hit_provincias pr on d.provincia = pr.CODIGO,
							     hit_paises pa
							where
								z.destino = d.CODIGO
								and d.PAIS = pa.CODIGO
								and z.GEOLOC_UBICACION_LATITUD is null");
	}elseif($parametrosg['tipo'] == 'DESTINO'){
		$resultado =$conexion->query("select case 
								when pr.NOMBRE is not null then pr.NOMBRE 
								else d.NOMBRE 
								end destino, 
							 pa.NOMBRE pais, d.CODIGO codigo 
							from hit_destinos d left join hit_provincias pr on d.provincia = pr.CODIGO,
							hit_paises pa
							where
								d.pais = pa.CODIGO
								and d.GEOLOC_UBICACION_LATITUD is null");
	}elseif($parametrosg['tipo'] == 'INTERFAZ_POBLACION'){
		$resultado =$conexion->query("select po.CODIGO codigo,  po.NOMBRE ciudad, pr.NOMBRE provincia, pa.NOMBRE pais 
							from hit_interfaces_poblaciones po, 
									hit_interfaces_provincias pr, 
									hit_interfaces_paises pa 
							where
								po.INTERFAZ = pr.INTERFAZ
								and po.PROVINCIA = pr.CODIGO
								and pr.INTERFAZ = pa.INTERFAZ	
								and pr.PAIS = pa.CODIGO
								and po.LATITUD is null
								limit 2050");
	}else{
		$resultado =$conexion->query("select pa.NOMBRE pais 
							from hit_paises pa
							where
								pa.codigo = '000'");		
	}


	for ($k = 0; $k < $resultado->num_rows; $k++) {

		$resultado->data_seek($k);
		$fila = $resultado->fetch_assoc();

	
		if($parametrosg['tipo'] == 'CIUDAD'){

			$codigo = $fila['codigo'];
			$ciudad = $fila['ciudad'].',';
			$provincia = $fila['provincia'].',';
			$pais = $fila['pais'];
			$cadena = $ciudad.$provincia.$pais;
			$cadena_peticion = str_replace(" ", "", $cadena);

		}elseif($parametrosg['tipo'] == 'PROVINCIA'){
			
			$codigo = $fila['codigo'];
			$ciudad = '';
			$provincia = $fila['provincia'].',';
			$pais = $fila['pais'];
			$cadena = $ciudad.$provincia.$pais;
			$cadena_peticion = str_replace(" ", "", $cadena);

		}elseif($parametrosg['tipo'] == 'PAIS'){
			
			$codigo = $fila['codigo'];
			$ciudad = '';
			$provincia = '';
			$pais = $fila['pais'];
			$cadena = $ciudad.$provincia.$pais;
			$cadena_peticion = str_replace(" ", "", $cadena);
	
		}elseif($parametrosg['tipo'] == 'ZONA'){
			
			$codigo = $fila['codigo'];
			$ciudad = $fila['zona'].',';
			$provincia = $fila['destino'].',';
			$pais = $fila['pais'];
			$cadena = $ciudad.$provincia.$pais;
			$cadena_peticion = str_replace(" ", "", $cadena);

		}elseif($parametrosg['tipo'] == 'DESTINO'){

			$codigo = $fila['codigo'];
			$ciudad = '';
			$provincia = $fila['destino'].',';
			$pais = $fila['pais'];
			$cadena = $ciudad.$provincia.$pais;
			$cadena_peticion = str_replace(" ", "", $cadena);

		}elseif($parametrosg['tipo'] == 'INTERFAZ_POBLACION'){
			
			$codigo = $fila['codigo'];
			$ciudad = $fila['ciudad'].',';
			$provincia = $fila['provincia'].',';
			$pais = $fila['pais'];
			$cadena = $ciudad.$provincia.$pais;
			$cadena_peticion = str_replace(" ", "", $cadena);

		}else{
			$cadena_peticion = '';
		}


		//echo($cadena_peticion.'<br>');

		try {

			$respuesta = file_get_contents("https://maps.googleapis.com/maps/api/geocode/xml?address=".$cadena_peticion."&key=AIzaSyCLBcaMUwf3MSN7HJ3icELbVdTIViLNALA");

			$xml = new SimpleXMLElement($respuesta);

			//MOSTRAR RESPUESTA PARA CERTIFICACION EN CODIGO FUENTE
			//echo('----------------RESPUESTA--------------------');
			//echo utf8_encode($xml->asXML());

			$count_respuesta = count($xml->result);
			
			//for($i=0;$i<$count_respuesta;$i++){

			if($count_respuesta > 0){
				$estado = $xml->status[0];

				//echo($estado);

				if($estado == 'OK'){

					$geoloc = $xml->result[0];


					/*print('GEOLOC_UBICACION_LATITUD: '.$geoloc->geometry->location->lat.'<br>'.
					        'GEOLOC_UBICACION_LATITUD: '.$geoloc->geometry->location->lng.'<br>'.
					        'GEOLOC_VENTANA_SUROESTE_LATITUD: '.$geoloc->geometry->viewport->southwest->lat.'<br>'.
					        'GEOLOC_VENTANA_SUROESTE_LONGITUD: '.$geoloc->geometry->viewport->southwest->lng.'<br>'.
					        'GEOLOC_VENTANA_NORDESTE_LATITUD: '.$geoloc->geometry->viewport->northeast->lat.'<br>'.
					        'GEOLOC_VENTANA_NORDESTE_LONGITUD: '.$geoloc->geometry->viewport->northeast->lng.'<br>'.
					        'GEOLOC_LIMITES_SUROESTE_LATITUD: '.$geoloc->geometry->bounds->southwest->lat.'<br>'.
					        'GEOLOC_LIMITES_SUROESTE_LONGITUD: '.$geoloc->geometry->bounds->southwest->lng.'<br>'.
					        'GEOLOC_LIMITES_NORDESTE_LATITUD: '.$geoloc->geometry->bounds->northeast->lat.'<br>'.
					        'GEOLOC_LIMITES_NORDESTE_LONGITUD: '.$geoloc->geometry->bounds->northeast->lng.'<br>'.
					        'GEOLOC_ID_LUGAR: '.$geoloc->place_id.'<br><br><br>');*/

					print('GEOLOC_UBICACION_LATITUD: '.$geoloc->geometry->location->lat.'<br>'.
					        'GEOLOC_UBICACION_LATITUD: '.$geoloc->geometry->location->lng.'<br><br>');




					if($parametrosg['tipo'] == 'CIUDAD'){

						$query = "UPDATE hit_ciudades SET ";
						$query .= " GEOLOC_UBICACION_LATITUD = '".$geoloc->geometry->location->lat."'";
						$query .= ", GEOLOC_UBICACION_LONGITUD = '".$geoloc->geometry->location->lng."'";
						$query .= ", GEOLOC_VENTANA_SUROESTE_LATITUD = '".$geoloc->geometry->viewport->southwest->lat."'";
						$query .= ", GEOLOC_VENTANA_SUROESTE_LONGITUD = '".$geoloc->geometry->viewport->southwest->lng."'";
						$query .= ", GEOLOC_VENTANA_NORDESTE_LATITUD = '".$geoloc->geometry->viewport->northeast->lat."'";
						$query .= ", GEOLOC_VENTANA_NORDESTE_LONGITUD = '".$geoloc->geometry->viewport->northeast->lng."'";
						$query .= ", GEOLOC_LIMITES_SUROESTE_LATITUD = '".$geoloc->geometry->bounds->southwest->lat."'";
						$query .= ", GEOLOC_LIMITES_SUROESTE_LONGITUD = '".$geoloc->geometry->bounds->southwest->lng."'";
						$query .= ", GEOLOC_LIMITES_NORDESTE_LATITUD = '".$geoloc->geometry->bounds->northeast->lat."'";
						$query .= ", GEOLOC_LIMITES_NORDESTE_LONGITUD = '".$geoloc->geometry->bounds->northeast->lng."'";
						$query .= ", GEOLOC_ID_LUGAR = '".$geoloc->place_id."'";
						$query .= " WHERE CODIGO = '".$codigo."'";

						$resultadom =$conexion->query($query);

						if ($resultadom == FALSE){
							$respuesta = $conexion->error;
						}else{
							$respuesta = 'OK';
							$actualizaziones++;
						};

					}elseif($parametrosg['tipo'] == 'PROVINCIA'){
						
						$query = "UPDATE hit_provincias SET ";
						$query .= " GEOLOC_UBICACION_LATITUD = '".$geoloc->geometry->location->lat."'";
						$query .= ", GEOLOC_UBICACION_LONGITUD = '".$geoloc->geometry->location->lng."'";
						$query .= ", GEOLOC_VENTANA_SUROESTE_LATITUD = '".$geoloc->geometry->viewport->southwest->lat."'";
						$query .= ", GEOLOC_VENTANA_SUROESTE_LONGITUD = '".$geoloc->geometry->viewport->southwest->lng."'";
						$query .= ", GEOLOC_VENTANA_NORDESTE_LATITUD = '".$geoloc->geometry->viewport->northeast->lat."'";
						$query .= ", GEOLOC_VENTANA_NORDESTE_LONGITUD = '".$geoloc->geometry->viewport->northeast->lng."'";
						$query .= ", GEOLOC_LIMITES_SUROESTE_LATITUD = '".$geoloc->geometry->bounds->southwest->lat."'";
						$query .= ", GEOLOC_LIMITES_SUROESTE_LONGITUD = '".$geoloc->geometry->bounds->southwest->lng."'";
						$query .= ", GEOLOC_LIMITES_NORDESTE_LATITUD = '".$geoloc->geometry->bounds->northeast->lat."'";
						$query .= ", GEOLOC_LIMITES_NORDESTE_LONGITUD = '".$geoloc->geometry->bounds->northeast->lng."'";
						$query .= ", GEOLOC_ID_LUGAR = '".$geoloc->place_id."'";
						$query .= " WHERE CODIGO = '".$codigo."'";


						$resultadom =$conexion->query($query);

						if ($resultadom == FALSE){
							$respuesta = $conexion->error;
						}else{
							$respuesta = 'OK';
							$actualizaziones++;
						}

					}elseif($parametrosg['tipo'] == 'PAIS'){
						
						$query = "UPDATE hit_paises SET ";
						$query .= " GEOLOC_UBICACION_LATITUD = '".$geoloc->geometry->location->lat."'";
						$query .= ", GEOLOC_UBICACION_LONGITUD = '".$geoloc->geometry->location->lng."'";
						$query .= ", GEOLOC_VENTANA_SUROESTE_LATITUD = '".$geoloc->geometry->viewport->southwest->lat."'";
						$query .= ", GEOLOC_VENTANA_SUROESTE_LONGITUD = '".$geoloc->geometry->viewport->southwest->lng."'";
						$query .= ", GEOLOC_VENTANA_NORDESTE_LATITUD = '".$geoloc->geometry->viewport->northeast->lat."'";
						$query .= ", GEOLOC_VENTANA_NORDESTE_LONGITUD = '".$geoloc->geometry->viewport->northeast->lng."'";
						$query .= ", GEOLOC_LIMITES_SUROESTE_LATITUD = '".$geoloc->geometry->bounds->southwest->lat."'";
						$query .= ", GEOLOC_LIMITES_SUROESTE_LONGITUD = '".$geoloc->geometry->bounds->southwest->lng."'";
						$query .= ", GEOLOC_LIMITES_NORDESTE_LATITUD = '".$geoloc->geometry->bounds->northeast->lat."'";
						$query .= ", GEOLOC_LIMITES_NORDESTE_LONGITUD = '".$geoloc->geometry->bounds->northeast->lng."'";
						$query .= ", GEOLOC_ID_LUGAR = '".$geoloc->place_id."'";
						$query .= " WHERE CODIGO = '".$codigo."'";

						$resultadom =$conexion->query($query);

						if ($resultadom == FALSE){
							$respuesta = $conexion->error;
						}else{
							$respuesta = 'OK';
							$actualizaziones++;
						}
				
					}elseif($parametrosg['tipo'] == 'ZONA'){
						
						$query = "UPDATE hit_zonas SET ";
						$query .= " GEOLOC_UBICACION_LATITUD = '".$geoloc->geometry->location->lat."'";
						$query .= ", GEOLOC_UBICACION_LONGITUD = '".$geoloc->geometry->location->lng."'";
						$query .= ", GEOLOC_VENTANA_SUROESTE_LATITUD = '".$geoloc->geometry->viewport->southwest->lat."'";
						$query .= ", GEOLOC_VENTANA_SUROESTE_LONGITUD = '".$geoloc->geometry->viewport->southwest->lng."'";
						$query .= ", GEOLOC_VENTANA_NORDESTE_LATITUD = '".$geoloc->geometry->viewport->northeast->lat."'";
						$query .= ", GEOLOC_VENTANA_NORDESTE_LONGITUD = '".$geoloc->geometry->viewport->northeast->lng."'";
						$query .= ", GEOLOC_LIMITES_SUROESTE_LATITUD = '".$geoloc->geometry->bounds->southwest->lat."'";
						$query .= ", GEOLOC_LIMITES_SUROESTE_LONGITUD = '".$geoloc->geometry->bounds->southwest->lng."'";
						$query .= ", GEOLOC_LIMITES_NORDESTE_LATITUD = '".$geoloc->geometry->bounds->northeast->lat."'";
						$query .= ", GEOLOC_LIMITES_NORDESTE_LONGITUD = '".$geoloc->geometry->bounds->northeast->lng."'";
						$query .= ", GEOLOC_ID_LUGAR = '".$geoloc->place_id."'";
						$query .= " WHERE CODIGO = '".$codigo."'";

						$resultadom =$conexion->query($query);

						if ($resultadom == FALSE){
							$respuesta = $conexion->error;
						}else{
							$respuesta = 'OK';
							$actualizaziones++;
						}

					}elseif($parametrosg['tipo'] == 'DESTINO'){

						$query = "UPDATE hit_destinos SET ";
						$query .= " GEOLOC_UBICACION_LATITUD = '".$geoloc->geometry->location->lat."'";
						$query .= ", GEOLOC_UBICACION_LONGITUD = '".$geoloc->geometry->location->lng."'";
						$query .= ", GEOLOC_VENTANA_SUROESTE_LATITUD = '".$geoloc->geometry->viewport->southwest->lat."'";
						$query .= ", GEOLOC_VENTANA_SUROESTE_LONGITUD = '".$geoloc->geometry->viewport->southwest->lng."'";
						$query .= ", GEOLOC_VENTANA_NORDESTE_LATITUD = '".$geoloc->geometry->viewport->northeast->lat."'";
						$query .= ", GEOLOC_VENTANA_NORDESTE_LONGITUD = '".$geoloc->geometry->viewport->northeast->lng."'";
						$query .= ", GEOLOC_LIMITES_SUROESTE_LATITUD = '".$geoloc->geometry->bounds->southwest->lat."'";
						$query .= ", GEOLOC_LIMITES_SUROESTE_LONGITUD = '".$geoloc->geometry->bounds->southwest->lng."'";
						$query .= ", GEOLOC_LIMITES_NORDESTE_LATITUD = '".$geoloc->geometry->bounds->northeast->lat."'";
						$query .= ", GEOLOC_LIMITES_NORDESTE_LONGITUD = '".$geoloc->geometry->bounds->northeast->lng."'";
						$query .= ", GEOLOC_ID_LUGAR = '".$geoloc->place_id."'";
						$query .= " WHERE CODIGO = '".$codigo."'";

						$resultadom =$conexion->query($query);

						if ($resultadom == FALSE){
							$respuesta = $conexion->error;
						}else{
							$respuesta = 'OK';
							$actualizaziones++;
						}


					}elseif($parametrosg['tipo'] == 'INTERFAZ_POBLACION'){
						
						$query = "UPDATE hit_interfaces_poblaciones SET ";
						$query .= " LATITUD = '".$geoloc->geometry->location->lat."'";
						$query .= ", LONGITUD = '".$geoloc->geometry->location->lng."'";
						$query .= " WHERE CODIGO = '".$codigo."'";

						$resultadom =$conexion->query($query);

						if ($resultadom == FALSE){
							$respuesta = $conexion->error;
						}else{
							$respuesta = 'OK';
							$actualizaziones++;
						}

					}else{
						$respuesta = 'NINGUNA ACTUALIZACION';
					}

				}
			}

		} catch (Exception $e) {

			if($parametrosg['tipo'] == 'CIUDAD'){

				$query = "UPDATE hit_ciudades SET ";
				$query .= " GEOLOC_UBICACION_LATITUD = '0'";
				$query .= " WHERE CODIGO = '".$codigo."'";

				$resultadom =$conexion->query($query);

				if ($resultadom == FALSE){
					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
					$actualizaziones++;
				};

			}elseif($parametrosg['tipo'] == 'PROVINCIA'){
				
				$query = "UPDATE hit_provincias SET ";
				$query .= " GEOLOC_UBICACION_LATITUD = '0'";
				$query .= " WHERE CODIGO = '".$codigo."'";


				$resultadom =$conexion->query($query);

				if ($resultadom == FALSE){
					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
					$actualizaziones++;
				}

			}elseif($parametrosg['tipo'] == 'PAIS'){
				
				$query = "UPDATE hit_paises SET ";
				$query .= " GEOLOC_UBICACION_LATITUD = '0'";
				$query .= " WHERE CODIGO = '".$codigo."'";

				$resultadom =$conexion->query($query);

				if ($resultadom == FALSE){
					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
					$actualizaziones++;
				}
		
			}elseif($parametrosg['tipo'] == 'ZONA'){
				
				$query = "UPDATE hit_zonas SET ";
				$query .= " GEOLOC_UBICACION_LATITUD = '0'";
				$query .= " WHERE CODIGO = '".$codigo."'";

				$resultadom =$conexion->query($query);

				if ($resultadom == FALSE){
					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
					$actualizaziones++;
				}

			}elseif($parametrosg['tipo'] == 'DESTINO'){

				$query = "UPDATE hit_destinos SET ";
				$query .= " GEOLOC_UBICACION_LATITUD = '0'";
				$query .= " WHERE CODIGO = '".$codigo."'";

				$resultadom =$conexion->query($query);

				if ($resultadom == FALSE){
					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
					$actualizaziones++;
				}


			}elseif($parametrosg['tipo'] == 'INTERFAZ_POBLACION'){
				
				$query = "UPDATE hit_interfaces_poblaciones SET ";
				$query .= " LATITUD = '0'";
				$query .= " WHERE CODIGO = '".$codigo."'";

				$resultadom =$conexion->query($query);

				if ($resultadom == FALSE){
					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
					$actualizaziones++;
				}

			}else{
				$respuesta = 'NINGUNA ACTUALIZACION';
			}

			echo 'Exception' . $e->getMessage();
		}
		
	}
	print("<br>Numero de actualizaciones:".$actualizaziones);
?>


