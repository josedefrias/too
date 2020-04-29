<?php

	header('Content-type: text/html; charset=utf-8');
	require 'clases/amadeus_api_web.cls.php';

class clsOfertas{

	//ESPECIFICO: Variables especificas de esta pantalla
	var	$buscar_nombre;
	var	$buscar_ciudad;
	var	$buscar_categoria;
	var	$buscar_situacion;
	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----Ñ
//------------------------------------------------------------------

	function Cargar_aereos($total_pax){
		 //REVISADO TEMA NOCHES EXTRA

		$conexion = $this ->Conexion;


		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_producto = $this ->Buscar_producto;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_noches = $this ->Buscar_noches;

		$dias = $buscar_noches + 1;

		$CADENA_BUSCAR2 = "";
		
		if($buscar_origen != null and $buscar_destino and $buscar_producto and $buscar_fecha and $buscar_noches){
			$des = " and c.DESTINO = '".$buscar_destino."'";
			$pro = " and c.PRODUCTO = '".$buscar_producto."'";
			//$fec = " and cu.FECHA = DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL (ae.DIA - 1) DAY)"

			$fec = " and ((cu.FECHA = DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL (ae.DIA - 1) DAY) and ae.DIA = 1)
							or
						  (cu.FECHA = DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL (ae.DIA + ".$dias." - ae.DIA - 1) DAY) and ae.DIA > 1))";


			$CADENA_BUSCAR = " and ae.CIUDAD = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR2 = $CADENA_BUSCAR;
				$CADENA_BUSCAR .= $fec;	
			}

		}else{
			$CADENA_BUSCAR = " and ae.CIUDAD = 'XXX'";
		}	
		
		//echo($CADENA_BUSCAR);
		//echo($CADENA_BUSCAR2);

		//Actualizamos la disponibilidad llamando al Api de Amadeus.
		$Actualiza_dispo =$conexion->query("select DISTINCT cu.FECHA fecha,DATE_FORMAT(cu.FECHA, '%d%m%y') fecha_api, cu.CIA cia, cu.VUELO vuelo, cu.ORIGEN origen, cu.DESTINO destino, ae.OPCION opcion, ae.ACUERDO acuerdo, ae.SUBACUERDO subacuerdo
											from hit_producto_cuadros c,hit_producto_cuadros_aereos ae,hit_transportes_cupos cu,hit_transportes tr,
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
											where c.CLAVE = ae.CLAVE_CUADRO
													and ae.CIA = cu.CIA
													and ae.ACUERDO = cu.ACUERDO
													and ae.SUBACUERDO = cu.SUBACUERDO
													and ae.ORIGEN = cu.ORIGEN
													and ae.DESTINO = cu.DESTINO
													and cc.CLAVE_CUADRO = c.CLAVE
													and cc.CIUDAD = ae.CIUDAD
													and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
													and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
													and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))
													and c.SITUACION = 'W'
													and ae.ACUERDO = 5100
													and FIND_IN_SET('".$dias."', cc.DURACIONES)
													and '".date("Y-m-d",strtotime($buscar_fecha))."' between c.PRIMERA_SALIDA and c.ULTIMA_SALIDA
													and ae.CIA = tr.CIA ".$CADENA_BUSCAR." order by ae.CIUDAD, ae.OPCION, ae.NUMERO");

		if ($Actualiza_dispo == FALSE){
			echo('Error en la consulta: '.$Actualiza_dispo);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}else{
			//Obtenemos la primera opcion para la fecha y origen seleccionados.
			$Primera_opcion =$conexion->query("select min(ae.OPCION) primera_opcion 
												from hit_producto_cuadros c,hit_producto_cuadros_aereos ae,hit_transportes_cupos cu,hit_transportes tr,
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
												where c.CLAVE = ae.CLAVE_CUADRO
														and ae.CIA = cu.CIA
														and ae.ACUERDO = cu.ACUERDO
														and ae.SUBACUERDO = cu.SUBACUERDO
														and ae.ORIGEN = cu.ORIGEN
														and ae.DESTINO = cu.DESTINO
														and cc.CLAVE_CUADRO = c.CLAVE
														and cc.CIUDAD = ae.CIUDAD
														and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
														and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
														and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))
														and c.SITUACION = 'W'
														and ae.ACUERDO = 5100
														and FIND_IN_SET('".$dias."', cc.DURACIONES)
														and '".date("Y-m-d",strtotime($buscar_fecha))."' between c.PRIMERA_SALIDA and c.ULTIMA_SALIDA
														and ae.CIA = tr.CIA ".$CADENA_BUSCAR." order by ae.CIUDAD, ae.OPCION, ae.NUMERO");

			$oPrimera_opcion = $Primera_opcion->fetch_assoc();
			$Opcion_primera = $oPrimera_opcion['primera_opcion'].'<br>';	
			//echo($Opcion_primera);

			$opcion_anterior = 1;
			$opcion_dispo = $Opcion_primera;
			$oAmadeus_Api = new clsAmadeus_api_web($conexion);



			for ($i = 0; $i < $Actualiza_dispo->num_rows; $i++) {
				$Actualiza_dispo->data_seek($i);
				$fila_dispo = $Actualiza_dispo->fetch_assoc();

				//echo($fila_dispo['fecha'].'-'.$fila_dispo['cia'].'-'.$fila_dispo['vuelo'].'-'.$fila_dispo['origen'].'-'.$fila_dispo['destino'].'-'.'<br>');
				//if($i == 0){echo($opcion_anterior.'-'.$fila_dispo['opcion'].'<br>');}
				if($opcion_anterior != $fila_dispo['opcion']){
					if($opcion_dispo == 1){
						//echo("no sigo<br>");
						//Si encuentra disponibilidad en todos los trayectos de una opcion no sigue buscando
						break;
					}else{
						//echo("sigo<br>");
						$opcion_dispo = 1;
					}
				}
				//echo($fila_dispo['opcion'].'-'.$fila_dispo['fecha'].'-'.$fila_dispo['cia'].'-'.$fila_dispo['vuelo'].'-'.$fila_dispo['origen'].'-'.$fila_dispo['destino'].'-'.'<br>');
				$sDispo = $oAmadeus_Api->Cargar_Disponibilidad($fila_dispo['fecha'],$fila_dispo['fecha_api'],$fila_dispo['cia'],$fila_dispo['vuelo'],$fila_dispo['origen'],$fila_dispo['destino']);

				$comprueba_dispopcion_trayecto = $conexion->query("select count(*) hay_dispo
												from
													hit_transportes_cupos trc
												where
													trc.CIA = '".$fila_dispo['cia']."'
													AND trc.ACUERDO = ".$fila_dispo['acuerdo']."
													and trc.SUBACUERDO = ".$fila_dispo['subacuerdo']."
													and trc.ORIGEN = '".$fila_dispo['origen']."'
													and trc.DESTINO = '".$fila_dispo['destino']."'
													and trc.FECHA = '".$fila_dispo['fecha']."'
													and (trc.CUPO_1 >= ".$total_pax." or trc.CUPO_2 >= ".$total_pax." or trc.CUPO_3 >= ".$total_pax." or trc.CUPO_4 >= ".$total_pax." or trc.CUPO_5 >= ".$total_pax.")");
				$fila_dispopcion_trayecto = $comprueba_dispopcion_trayecto->fetch_assoc();
				$v_hay_dispo = $fila_dispopcion_trayecto['hay_dispo'];
				//echo($v_hay_dispo.'-'.'<br>');
				//echo($fila_dispo['fecha'].'-'.$fila_dispo['cia'].'-'.$fila_dispo['vuelo'].'-'.$fila_dispo['origen'].'-'.$fila_dispo['destino'].'-'.$v_hay_dispo.'<br>');

				//echo($fila_dispo['fecha'].'-'.$fila_dispo['cia'].'-'.$fila_dispo['vuelo'].'-'.$fila_dispo['origen'].'-'.$fila_dispo['destino'].'-'.$fila_dispo['opcion'].'-'.$fila_dispo['acuerdo'].'-'.$fila_dispo['subacuerdo'].'<br>');

				$opcion_anterior = $fila_dispo['opcion'];
				if($v_hay_dispo == 0){
					$opcion_dispo = 0;
				}
			}	
		}


		
		$resultado =$conexion->query("select 	distinct
												c.FOLLETO v_folleto , c.CUADRO v_cuadro, ae.OPCION v_opcion, 
												DATE_FORMAT(cu.FECHA, '%d-%m-%Y') AS v_fecha, 
												case 
													WHEN cu.FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."' then 'Ida'
													WHEN cu.FECHA <> '".date("Y-m-d",strtotime($buscar_fecha))."' then 'Vuelta'
												end v_ida_vuelta,
												cu.ORIGEN v_origen, cu.DESTINO v_destino, 
												tr.NOMBRE v_cia, tr.CIA v_codigo_cia,
												cu.VUELO v_vuelo, 
												time_format(cu.HORA_SALIDA, '%H:%i') AS v_salida,
												time_format(cu.HORA_LLEGADA, '%H:%i') AS v_llegada,
												".$total_pax." v_pax,

												case
													 when ta.TIPO <> 'RAC'
													 	then
														 	CASE 
																WHEN cu.CUPO - cu.PLAZAS_OK >= ".$total_pax." THEN 'Confirmado'
																WHEN cu.CUPO - cu.PLAZAS_OK < ".$total_pax." THEN 'Pendiente'
																ELSE 'Pendiente'
															END 
													 else
														 	CASE 
																WHEN cu.CIA <> 'VY' AND cu.CUPO_1 >= ".$total_pax." THEN 'Confirmado'
																WHEN cu.CIA = 'VY' AND cu.CUPO_1 >= ".$total_pax." THEN 'Confirmado'
															ELSE 
																CASE
																	WHEN cu.CIA <> 'VY' AND cu.CUPO_2 >= ".$total_pax." THEN 'Confirmado'
																	WHEN cu.CIA = 'VY' AND cu.CUPO_2 >= ".$total_pax." THEN 'Confirmado'
																ELSE
																	CASE
																		WHEN cu.CIA <> 'VY' AND cu.CUPO_3 >= ".$total_pax." THEN 'Confirmado'
																		WHEN cu.CIA = 'VY' AND cu.CUPO_3 >= ".$total_pax." THEN 'Confirmado'
																	ELSE
																		CASE
																			WHEN cu.CIA <> 'VY' AND cu.CUPO_4 >= ".$total_pax." THEN 'Confirmado'
																			WHEN cu.CIA = 'VY' AND cu.CUPO_4 >= ".$total_pax." THEN 'Confirmado'
																		ELSE
																			CASE
																				WHEN cu.CIA <> 'VY' AND cu.CUPO_5 >= ".$total_pax." THEN 'Confirmado'
																				WHEN cu.CIA = 'VY' AND cu.CUPO_5 >= ".$total_pax." THEN 'Confirmado'
																			ELSE
																				'Pendiente'
																			END
																		END
																	END
																END
															END 												
												end v_disponibilidad,
												
												case
													 when ta.TIPO <> 'RAC'
													 	then
														 	CASE 
																WHEN cu.CUPO - cu.PLAZAS_OK >= ".$total_pax." THEN cu.CLASE
																ELSE cu.CLASE
															END 
													 else
														 	CASE 
																WHEN cu.CIA <> 'VY' AND cu.CUPO_1 >= ".$total_pax." THEN cu.CLASE_1
																WHEN cu.CIA = 'VY' AND cu.CUPO_1 >= ".$total_pax." THEN cu.CLASE_1
															ELSE 
																CASE
																	WHEN cu.CIA <> 'VY' AND cu.CUPO_2 >= ".$total_pax." THEN cu.CLASE_2
																	WHEN cu.CIA = 'VY' AND cu.CUPO_2 >= ".$total_pax." THEN cu.CLASE_2
																ELSE
																	CASE
																		WHEN cu.CIA <> 'VY' AND cu.CUPO_3 >= ".$total_pax." THEN cu.CLASE_3
																		WHEN cu.CIA = 'VY' AND cu.CUPO_3 >= ".$total_pax." THEN cu.CLASE_3
																	ELSE
																		CASE
																			WHEN cu.CIA <> 'VY' AND cu.CUPO_4 >= ".$total_pax." THEN cu.CLASE_4
																			WHEN cu.CIA = 'VY' AND cu.CUPO_4 >= ".$total_pax." THEN cu.CLASE_4
																		ELSE
																			CASE
																				WHEN cu.CIA <> 'VY' AND cu.CUPO_5 >= ".$total_pax." THEN cu.CLASE_5
																				WHEN cu.CIA = 'VY' AND cu.CUPO_5 >= ".$total_pax." THEN cu.CLASE_5
																			ELSE
																				case
																					when cu.CLASE_5 <> '' then cu.CLASE_5
																				else
																					case
																						when cu.CLASE_4 <> '' then cu.CLASE_4
																					else
																						case 
																							when cu.CLASE_3 <> '' then cu.CLASE_3
																						else
																							case 
																								when cu.CLASE_2 <> '' then cu.CLASE_2
																							else
																								cu.CLASE_1
																							end
																						end
																					end
																				end	
																			END
																		END
																	END
																END
															END 												
												end v_clase,


												cu.MAXIMO_BEBES -
												IFNULL((select sum(re.BEBES) from hit_reservas re, hit_reservas_pasajeros pa, 
																			hit_reservas_aereos_pasajeros rap, hit_reservas_aereos_trayectos rat
																	 where
																	 		re.LOCALIZADOR = pa.LOCALIZADOR
																	 		and pa.LOCALIZADOR = rap.LOCALIZADOR
																	 		and pa.NUMERO = rap.NUMERO
																	 		and rap.LOCALIZADOR = rat.LOCALIZADOR
																	 		and rap.ORDEN = rat.ORDEN
																	 		and re.SITUACION <> 'A'
																	 		and pa.TIPO = 'B'
																	 		and rat.FECHA = cu.FECHA
																	 		and rat.ORIGEN = cu.ORIGEN
																	 		and rat.DESTINO = cu.DESTINO
																	 		and rat.CIA = cu.CIA
																	 		and rat.ACUERDO = cu.ACUERDO
																	 		and rat.SUBACUERDO = cu.SUBACUERDO),0) bebes_disponibles

										from
											hit_producto_cuadros c,
											hit_producto_cuadros_aereos ae,
											hit_transportes_cupos cu,
											hit_transportes tr,
											hit_producto_cuadros_salidas sal,
											hit_transportes_acuerdos ta,
												  
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe

										where
											c.CLAVE = ae.CLAVE_CUADRO
											and ae.CIA = cu.CIA
											and ae.ACUERDO = cu.ACUERDO
											and ae.SUBACUERDO = cu.SUBACUERDO
											and ae.ORIGEN = cu.ORIGEN
											and ae.DESTINO = cu.DESTINO
											and c.CLAVE = sal.CLAVE_CUADRO
											and ae.CIA = ta.CIA
											and ae.ACUERDO = ta.ACUERDO
											and ae.SUBACUERDO = ta.SUBACUERDO
											and sal.FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'


													and cc.CLAVE_CUADRO = c.CLAVE
													and cc.CIUDAD = ae.CIUDAD
													and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
													and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
													and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1),
																		substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))	


											and c.SITUACION = 'W'
													and FIND_IN_SET('".$dias."', cc.DURACIONES)
											and '".date("Y-m-d",strtotime($buscar_fecha))."' between c.PRIMERA_SALIDA and c.ULTIMA_SALIDA
											and ae.CIA = tr.CIA ".$CADENA_BUSCAR." ORDER BY ae.opcion, cu.fecha, hora_salida");
		//echo($CADENA_BUSCAR);

		//obtenemos la cantidad de trayectos real por opcion. (Basta buscar por la estancia base?)
		$opcion_trayectos =$conexion->query("select  distinct ae.OPCION opcion, count(*) trayectos
										from
											hit_producto_cuadros c,
											hit_producto_cuadros_aereos ae,
											hit_transportes_cupos cu,
											hit_transportes tr,
												  
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
										where
											c.CLAVE = ae.CLAVE_CUADRO
											and ae.CIA = cu.CIA
											and ae.ACUERDO = cu.ACUERDO
											and ae.SUBACUERDO = cu.SUBACUERDO
											and ae.ORIGEN = cu.ORIGEN
											and ae.DESTINO = cu.DESTINO

													and cc.CLAVE_CUADRO = c.CLAVE
													and cc.CIUDAD = ae.CIUDAD
													and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
													and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
													and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1),
																		substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))	

											and c.SITUACION = 'W'
													and FIND_IN_SET('".$dias."', cc.DURACIONES)
											and '".date("Y-m-d",strtotime($buscar_fecha))."' between c.PRIMERA_SALIDA and c.ULTIMA_SALIDA
											and ae.CIA = tr.CIA ".$CADENA_BUSCAR." group by ae.OPCION");
											
		//obtenemos la cantidad de trayectos de construccion de producto por opcion. (Basta buscar por la estancia base?)
		$opcion_trayectos_producto =$conexion->query("select ae.OPCION opcion, count(*) trayectos
										from
											hit_producto_cuadros c,
											hit_producto_cuadros_aereos ae,
											hit_transportes tr,
												  
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
										where
											c.CLAVE = ae.CLAVE_CUADRO

													and cc.CLAVE_CUADRO = c.CLAVE
													and cc.CIUDAD = ae.CIUDAD
													and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
													and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
													and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1),
																		substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))	

											and c.SITUACION = 'W'
													and FIND_IN_SET('".$dias."', cc.DURACIONES)
											and '".date("Y-m-d",strtotime($buscar_fecha))."' between c.PRIMERA_SALIDA and c.ULTIMA_SALIDA
											and ae.CIA = tr.CIA ".$CADENA_BUSCAR2." group by ae.OPCION");											
		//echo($CADENA_BUSCAR2);									
		
				/*for ($k = 0; $k <= $opcion_trayectos_producto->num_rows; $k++) {
					$opcion_trayectos_producto->data_seek($k);
					$fila_trayectos = $opcion_trayectos_producto->fetch_assoc();
					print($fila_trayectos['opcion'].'-'.$fila_trayectos['trayectos'].'<br>');
				}*/

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//NUEVO CODIGO PARA OBTENER LA OPCION VALIDA
		$opcion=0;
		$disponible1 = 'N';
		$traydisponibles1 = 0;
		$cantidadtrayectos1 = 0;
		$cantidadtrayectos_producto1 = 0; 
		$disponible2 = 'N';
		$traydisponibles2 = 0;
		$cantidadtrayectos2 = 0;
		$cantidadtrayectos_producto2 = 0;
		$disponible3 = 'N';
		$traydisponibles3 = 0;
		$cantidadtrayectos3 = 0;
		$cantidadtrayectos_producto3 = 0;
		$disponible4 = 'N';
		$traydisponibles4 = 0;
		$cantidadtrayectos4 = 0;
		$cantidadtrayectos_producto4 = 0;
		$disponible5 = 'N';
		$traydisponibles5 = 0;
		$cantidadtrayectos5 = 0;
		$cantidadtrayectos_producto5 = 0;
		$disponible6 = 'N';
		$traydisponibles6 = 0;
		$cantidadtrayectos6 = 0;
		$cantidadtrayectos_producto6 = 0;
		$disponible7 = 'N';
		$traydisponibles7 = 0;
		$cantidadtrayectos7 = 0;
		$cantidadtrayectos_producto7 = 0;
		$disponible8 = 'N';
		$traydisponibles8 = 0;
		$cantidadtrayectos8 = 0;
		$cantidadtrayectos_producto8 = 0;
		$disponible9 = 'N';
		$traydisponibles9 = 0;
		$cantidadtrayectos9 = 0;
		$cantidadtrayectos_producto9 = 0;
		$disponible10 = 'N';
		$traydisponibles10 = 0;
		$cantidadtrayectos10 = 0;
		$cantidadtrayectos_producto10 = 0;

		//Cargamos las variables de todas las opciones con los valores correspondientes segun la query
		for ($i = 0; $i <= $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();

			//Cargamos una variable con la primera opción
			if($i == 0){
			$primera_opcion = $fila['v_opcion'];
			}

			if($fila['v_disponibilidad'] == 'Confirmado'){
				${'disponible'.$fila['v_opcion']} = 'S';
				${'traydisponibles'.$fila['v_opcion']}++;
				for ($k = 0; $k <= $opcion_trayectos->num_rows; $k++) {
					$opcion_trayectos->data_seek($k);
					$fila_trayectos = $opcion_trayectos->fetch_assoc();
					if($fila_trayectos['opcion'] == $fila['v_opcion']){
						${'cantidadtrayectos'.$fila['v_opcion']} = $fila_trayectos['trayectos']; 
					}
				}
				for ($j = 0; $j <= $opcion_trayectos_producto->num_rows; $j++) {
					$opcion_trayectos_producto->data_seek($j);
					$fila_trayectos_producto = $opcion_trayectos_producto->fetch_assoc();
					if($fila_trayectos_producto['opcion'] == $fila['v_opcion']){
						${'cantidadtrayectos_producto'.$fila['v_opcion']} = $fila_trayectos_producto['trayectos']; 
					}
				}			
			}
		}	
		
		/*Visualizar en pagina para ver que opciones encuentra*/
		/*echo('opcion 1 - Situacion: '.$disponible1.' - Trayectos Confirmados: '.$traydisponibles1.' - Cantidad de trayectos: '.$cantidadtrayectos1.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto1.'<br>'.
			 'opcion 2 - Situacion: '.$disponible2.' - Trayectos Confirmados: '.$traydisponibles2.' - Cantidad de trayectos: '.$cantidadtrayectos2.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto2.'<br>'.
			 'opcion 3 - Situacion: '.$disponible3.' - Trayectos Confirmados: '.$traydisponibles3.' - Cantidad de trayectos: '.$cantidadtrayectos3.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto3.'<br>'.
			 'opcion 4 - Situacion: '.$disponible4.' - Trayectos Confirmados: '.$traydisponibles4.' - Cantidad de trayectos: '.$cantidadtrayectos4.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto4.'<br>'.
			 'opcion 5 - Situacion: '.$disponible5.' - Trayectos Confirmados: '.$traydisponibles5.' - Cantidad de trayectos: '.$cantidadtrayectos5.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto5.'<br>'.
			 'opcion 6 - Situacion: '.$disponible6.' - Trayectos Confirmados: '.$traydisponibles6.' - Cantidad de trayectos: '.$cantidadtrayectos6.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto6.'<br>'.
			 'opcion 7 - Situacion: '.$disponible7.' - Trayectos Confirmados: '.$traydisponibles7.' - Cantidad de trayectos: '.$cantidadtrayectos7.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto7.'<br>'.
			 'opcion 8 - Situacion: '.$disponible8.' - Trayectos Confirmados: '.$traydisponibles8.' - Cantidad de trayectos: '.$cantidadtrayectos8.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto8.'<br>'.
			 'opcion 9 - Situacion: '.$disponible9.' - Trayectos Confirmados: '.$traydisponibles9.' - Cantidad de trayectos: '.$cantidadtrayectos9.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto9.'<br>'.
			 'opcion 10 - Situacion: '.$disponible10.' - Trayectos Confirmados: '.$traydisponibles10.' - Cantidad de trayectos: '.$cantidadtrayectos10.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto10.'<br>');	*/
		
		//obtenemos la mejor opcion segun disponibilidad de trayectos
		//Primero buscamos que todos los trayectos esten ok
		for ($i = 1; $i <= 10; $i++) {	
			if(${'cantidadtrayectos'.$i} == ${'cantidadtrayectos_producto'.$i} and ${'disponible'.$i} == 'S' and ${'traydisponibles'.$i} == ${'cantidadtrayectos'.$i}){
				//echo($i.'-'.${'disponible'.$i}.'-'.${'traydisponibles'.$i}.'-'.${'cantidadtrayectos'.$i}.'<br>');
				$opcion = $i;
				break;
			}
		}
		//Sino buscamos que todos los trayectos menos uno esten ok
		if($opcion == 0){
			for ($i = 1; $i <= 10; $i++) {	
				if(${'cantidadtrayectos'.$i} == ${'cantidadtrayectos_producto'.$i} and ${'disponible'.$i} == 'S' and ${'traydisponibles'.$i} == ${'cantidadtrayectos'.$i} - 1){
					//echo($i.'-'.${'disponible'.$i}.'-'.${'traydisponibles'.$i}.'-'.${'cantidadtrayectos'.$i}.'<br>');
					$opcion = $i;
					break;
				}
			}
		}
		//Sino buscamos que todos los trayectos menos dos esten ok
		if($opcion == 0){
			for ($i = 1; $i <= 10; $i++) {	
				if(${'cantidadtrayectos'.$i} == ${'cantidadtrayectos_producto'.$i} and ${'disponible'.$i} == 'S' and ${'traydisponibles'.$i} == ${'cantidadtrayectos'.$i} - 2){
					//echo($i.'-'.${'disponible'.$i}.'-'.${'traydisponibles'.$i}.'-'.${'cantidadtrayectos'.$i}.'<br>');
					$opcion = $i;
					break;
				}
			}
		}
		//Sino buscamos que todos los trayectos menos tres esten ok
		if($opcion == 0){
			for ($i = 1; $i <= 10; $i++) {	
				if(${'cantidadtrayectos'.$i} == ${'cantidadtrayectos_producto'.$i} and ${'disponible'.$i} == 'S' and ${'traydisponibles'.$i} == ${'cantidadtrayectos'.$i} - 3){
					//echo($i.'-'.${'disponible'.$i}.'-'.${'traydisponibles'.$i}.'-'.${'cantidadtrayectos'.$i}.'<br>');
					$opcion = $i;
					break;
				}
			}
		}		
		//Si no hay ninguna disponibilidad asignamos la primera opcion que tenga todos los trayectos de la construccion de producto
		if($opcion == 0){
			$opcion = $primera_opcion;
			/*for ($i = 1; $i <= 10; $i++) {	
				if(${'cantidadtrayectos'.$i} == ${'cantidadtrayectos_producto'.$i}){
					$opcion = $i;
					break;
				}
			}	*/		
		}

		//echo($opcion);
		
		//Cargamos array con los trayectos de la opción disponible
		$aereos = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			if($fila['v_opcion'] == $opcion){
				array_push($aereos,$fila);
			}
		}		

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $aereos;											
	}

	function Cargar_aereos_solo_vuelo($total_pax,$fecha_regreso){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_producto = $this ->Buscar_producto;
		$buscar_fecha = $this ->Buscar_fecha;
		$cadena_buscar_zona_svo = $this ->Cadena_buscar_zona_svo;
		//$buscar_noches = $this ->Buscar_noches;


		$CADENA_BUSCAR2 = "";
		
		if($fecha_regreso == ''){
			$fecha_regreso = $buscar_fecha;
		}

		$dias	= (strtotime($fecha_regreso)-strtotime($buscar_fecha))/86400;
		$dias 	= abs($dias); 
		$dias = floor($dias) + 1;		
		//	print($dias);

		if($buscar_origen != null and $buscar_destino and $buscar_producto and $buscar_fecha and $fecha_regreso){
			$des = " and c.DESTINO = '".$buscar_destino."'";
			$pro = " and c.PRODUCTO = '".$buscar_producto."'";
			//$fec = " and cu.FECHA = DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL (ae.DIA - 1) DAY)";

			$fec = " and ((cu.FECHA = DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL (ae.DIA - 1) DAY) and ae.DIA = 1)
							or
						  (cu.FECHA = DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL (ae.DIA + ".$dias." - ae.DIA - 1) DAY) and ae.DIA > 1))";


			$CADENA_BUSCAR = " and ae.CIUDAD = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR2 = $CADENA_BUSCAR;	
				$CADENA_BUSCAR .= $fec;	
			}

		}else{
			$CADENA_BUSCAR = " and ae.CIUDAD = 'XXX'";
		}	
		
		//echo($CADENA_BUSCAR.$cadena_buscar_zona_svo);


		//Actualizamos la disponibilidad llamando al Api de Amadeus.
		$Actualiza_dispo =$conexion->query("select distinct cu.FECHA fecha,DATE_FORMAT(cu.FECHA, '%d%m%y') fecha_api, cu.CIA cia, cu.VUELO vuelo, cu.ORIGEN origen, cu.DESTINO destino, ae.OPCION opcion, ae.ACUERDO acuerdo, ae.SUBACUERDO subacuerdo
										from
											hit_producto_cuadros c,
											hit_producto_cuadros_aereos ae,
											hit_transportes_cupos cu,
											hit_transportes tr,
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
										where
											c.CLAVE = ae.CLAVE_CUADRO
											and ae.CIA = cu.CIA
											and ae.ACUERDO = cu.ACUERDO
											and ae.SUBACUERDO = cu.SUBACUERDO
											and ae.ORIGEN = cu.ORIGEN
											and ae.DESTINO = cu.DESTINO

														and cc.CLAVE_CUADRO = c.CLAVE
														and cc.CIUDAD = ae.CIUDAD
														and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
														and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
														and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))
														and c.SITUACION = 'W'
														and ae.ACUERDO = 5100
														and FIND_IN_SET('".$dias."', cc.DURACIONES)

											and '".date("Y-m-d",strtotime($buscar_fecha))."' between c.PRIMERA_SALIDA and c.ULTIMA_SALIDA
											and ae.CIA = tr.CIA".$CADENA_BUSCAR.$cadena_buscar_zona_svo);	

		if ($Actualiza_dispo == FALSE){
			echo('Error en la consulta: '.$Actualiza_dispo);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}else{

			$opcion_anterior = 1;
			$opcion_dispo = 1;
			$oAmadeus_Api = new clsAmadeus_api_web($conexion);

			for ($i = 0; $i < $Actualiza_dispo->num_rows; $i++) {

				$Actualiza_dispo->data_seek($i);
				$fila_dispo = $Actualiza_dispo->fetch_assoc();
				//echo($fila_dispo['fecha'].'-'.$fila_dispo['cia'].'-'.$fila_dispo['vuelo'].'-'.$fila_dispo['origen'].'-'.$fila_dispo['destino'].'<br>');
				
				if($opcion_anterior != $fila_dispo['opcion']){
					if($opcion_dispo == 1){
						//echo("no sigo<br>");
						break;
					}else{
						//echo("sigo<br>");
						$opcion_dispo = 1;
					}
				}

				$sDispo = $oAmadeus_Api->Cargar_Disponibilidad($fila_dispo['fecha'],$fila_dispo['fecha_api'],$fila_dispo['cia'],$fila_dispo['vuelo'],$fila_dispo['origen'],$fila_dispo['destino']);

				$comprueba_dispopcion_trayecto = $conexion->query("select count(*) hay_dispo
												from
													hit_transportes_cupos trc
												where
													trc.CIA = '".$fila_dispo['cia']."'
													AND trc.ACUERDO = ".$fila_dispo['acuerdo']."
													and trc.SUBACUERDO = ".$fila_dispo['subacuerdo']."
													and trc.ORIGEN = '".$fila_dispo['origen']."'
													and trc.DESTINO = '".$fila_dispo['destino']."'
													and trc.FECHA = '".$fila_dispo['fecha']."'
													and (trc.CUPO_1 >= ".$total_pax." or trc.CUPO_2 >= ".$total_pax." or trc.CUPO_3 >= ".$total_pax." or trc.CUPO_4 >= ".$total_pax." or trc.CUPO_5 >= ".$total_pax.")");
				$fila_dispopcion_trayecto = $comprueba_dispopcion_trayecto->fetch_assoc();
				$v_hay_dispo = $fila_dispopcion_trayecto['hay_dispo'];

				//echo($fila_dispo['fecha'].'-'.$fila_dispo['cia'].'-'.$fila_dispo['vuelo'].'-'.$fila_dispo['origen'].'-'.$fila_dispo['destino'].$v_hay_dispo.'<br>');

				//echo($fila_dispo['fecha'].'-'.$fila_dispo['cia'].'-'.$fila_dispo['vuelo'].'-'.$fila_dispo['origen'].'-'.$fila_dispo['destino'].'-'.$fila_dispo['opcion'].'-'.$fila_dispo['acuerdo'].'-'.$fila_dispo['subacuerdo'].'<br>');

				$opcion_anterior = $fila_dispo['opcion'];
				if($v_hay_dispo == 0){
					$opcion_dispo = 0;
				}




			}	
		}


		
		$resultado =$conexion->query("select 
												c.FOLLETO v_folleto , c.CUADRO v_cuadro, ae.OPCION v_opcion, 
												DATE_FORMAT(cu.FECHA, '%d-%m-%Y') AS v_fecha, 
												cu.ORIGEN v_origen, cu.DESTINO v_destino, 
												tr.NOMBRE v_cia, tr.CIA v_codigo_cia,
												cu.VUELO v_vuelo, 
												time_format(cu.HORA_SALIDA, '%H:%i') AS v_salida,
												time_format(cu.HORA_LLEGADA, '%H:%i') AS v_llegada,
												
												case
													 when ta.TIPO <> 'RAC'
													 	then
														 	CASE 
																WHEN cu.CUPO - cu.PLAZAS_OK >= ".$total_pax." THEN 'Confirmado'
																WHEN cu.CUPO - cu.PLAZAS_OK < ".$total_pax." THEN 'Pendiente'
																ELSE 'Pendiente'
															END 
													 else
														 	CASE 
																WHEN cu.CIA <> 'VY' AND cu.CUPO_1 >= ".$total_pax." THEN 'Confirmado'
																WHEN cu.CIA = 'VY' AND cu.CUPO_1 >= ".$total_pax." THEN 'Confirmado'
															ELSE 
																CASE
																	WHEN cu.CIA <> 'VY' AND cu.CUPO_2 >= ".$total_pax." THEN 'Confirmado'
																	WHEN cu.CIA = 'VY' AND cu.CUPO_2 >= ".$total_pax." THEN 'Confirmado'
																ELSE
																	CASE
																		WHEN cu.CIA <> 'VY' AND cu.CUPO_3 >= ".$total_pax." THEN 'Confirmado'
																		WHEN cu.CIA = 'VY' AND cu.CUPO_3 >= ".$total_pax." THEN 'Confirmado'
																	ELSE
																		CASE
																			WHEN cu.CIA <> 'VY' AND cu.CUPO_4 >= ".$total_pax." THEN 'Confirmado'
																			WHEN cu.CIA = 'VY' AND cu.CUPO_4 >= ".$total_pax." THEN 'Confirmado'
																		ELSE
																			CASE
																				WHEN cu.CIA <> 'VY' AND cu.CUPO_5 >= ".$total_pax." THEN 'Confirmado'
																				WHEN cu.CIA = 'VY' AND cu.CUPO_5 >= ".$total_pax." THEN 'Confirmado'
																			ELSE
																				'Pendiente'
																			END
																		END
																	END
																END
															END 												
												end v_disponibilidad,
												
												case
													 when ta.TIPO <> 'RAC'
													 	then
														 	CASE 
																WHEN cu.CUPO - cu.PLAZAS_OK >= ".$total_pax." THEN cu.CLASE
																ELSE cu.CLASE
															END 
													 else
														 	CASE 
																WHEN cu.CIA <> 'VY' AND cu.CUPO_1 >= ".$total_pax." THEN cu.CLASE_1
																WHEN cu.CIA = 'VY' AND cu.CUPO_1 >= ".$total_pax." THEN cu.CLASE_1
															ELSE 
																CASE
																	WHEN cu.CIA <> 'VY' AND cu.CUPO_2 >= ".$total_pax." THEN cu.CLASE_2
																	WHEN cu.CIA = 'VY' AND cu.CUPO_2 >= ".$total_pax." THEN cu.CLASE_2
																ELSE
																	CASE
																		WHEN cu.CIA <> 'VY' AND cu.CUPO_3 >= ".$total_pax." THEN cu.CLASE_3
																		WHEN cu.CIA = 'VY' AND cu.CUPO_3 >= ".$total_pax." THEN cu.CLASE_3
																	ELSE
																		CASE
																			WHEN cu.CIA <> 'VY' AND cu.CUPO_4 >= ".$total_pax." THEN cu.CLASE_4
																			WHEN cu.CIA = 'VY' AND cu.CUPO_4 >= ".$total_pax." THEN cu.CLASE_4
																		ELSE
																			CASE
																				WHEN cu.CIA <> 'VY' AND cu.CUPO_5 >= ".$total_pax." THEN cu.CLASE_5
																				WHEN cu.CIA = 'VY' AND cu.CUPO_5 >= ".$total_pax." THEN cu.CLASE_5
																			ELSE
																				case
																					when cu.CLASE_5 <> '' then cu.CLASE_5
																				else
																					case
																						when cu.CLASE_4 <> '' then cu.CLASE_4
																					else
																						case 
																							when cu.CLASE_3 <> '' then cu.CLASE_3
																						else
																							case 
																								when cu.CLASE_2 <> '' then cu.CLASE_2
																							else
																								cu.CLASE_1
																							end
																						end
																					end
																				end	
																			END
																		END
																	END
																END
															END 												
												end v_clase,

												cu.MAXIMO_BEBES -
												IFNULL((select sum(re.BEBES) from hit_reservas re, hit_reservas_pasajeros pa, 
																			hit_reservas_aereos_pasajeros rap, hit_reservas_aereos_trayectos rat
																	 where
																	 		re.LOCALIZADOR = pa.LOCALIZADOR
																	 		and pa.LOCALIZADOR = rap.LOCALIZADOR
																	 		and pa.NUMERO = rap.NUMERO
																	 		and rap.LOCALIZADOR = rat.LOCALIZADOR
																	 		and rap.ORDEN = rat.ORDEN
																	 		and re.SITUACION <> 'A'
																	 		and pa.TIPO = 'B'
																	 		and rat.FECHA = cu.FECHA
																	 		and rat.ORIGEN = cu.ORIGEN
																	 		and rat.DESTINO = cu.DESTINO
																	 		and rat.CIA = cu.CIA
																	 		and rat.ACUERDO = cu.ACUERDO
																	 		and rat.SUBACUERDO = cu.SUBACUERDO),0) bebes_disponibles


										from
											hit_producto_cuadros c,
											hit_producto_cuadros_aereos ae,
											hit_transportes_cupos cu,
											hit_transportes tr,
											hit_producto_cuadros_salidas sal,
											hit_transportes_acuerdos ta,
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
										where
											c.CLAVE = ae.CLAVE_CUADRO
											and ae.CIA = cu.CIA
											and ae.ACUERDO = cu.ACUERDO
											and ae.SUBACUERDO = cu.SUBACUERDO
											and ae.ORIGEN = cu.ORIGEN
											and ae.DESTINO = cu.DESTINO
											and c.CLAVE = sal.CLAVE_CUADRO
											and ae.CIA = ta.CIA
											and ae.ACUERDO = ta.ACUERDO
											and ae.SUBACUERDO = ta.SUBACUERDO
											and c.SITUACION = 'W'


														and cc.CLAVE_CUADRO = c.CLAVE
														and cc.CIUDAD = ae.CIUDAD
														and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
														and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
														and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))
														and FIND_IN_SET('".$dias."', cc.DURACIONES)


											and '".date("Y-m-d",strtotime($buscar_fecha))."' between c.PRIMERA_SALIDA and c.ULTIMA_SALIDA
											and sal.FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'
											and ae.CIA = tr.CIA ".$CADENA_BUSCAR.$cadena_buscar_zona_svo." ORDER BY ae.opcion, cu.fecha, hora_salida");

		//echo($CADENA_BUSCAR);
		//echo($fecha_regreso);
		
		//obtenemos la cantidad de trayectos por opcion. 
		$opcion_trayectos =$conexion->query("select distinct ae.OPCION opcion, count(*) trayectos
										from
											hit_producto_cuadros c,
											hit_producto_cuadros_aereos ae,
											hit_transportes_cupos cu,
											hit_transportes tr,
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
										where
											c.CLAVE = ae.CLAVE_CUADRO
											and ae.CIA = cu.CIA
											and ae.ACUERDO = cu.ACUERDO
											and ae.SUBACUERDO = cu.SUBACUERDO
											and ae.ORIGEN = cu.ORIGEN
											and ae.DESTINO = cu.DESTINO
											and c.SITUACION = 'W'

														and cc.CLAVE_CUADRO = c.CLAVE
														and cc.CIUDAD = ae.CIUDAD
														and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
														and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
														and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))
														and FIND_IN_SET('".$dias."', cc.DURACIONES)

											and '".date("Y-m-d",strtotime($buscar_fecha))."' between c.PRIMERA_SALIDA and c.ULTIMA_SALIDA

											and ae.CIA = tr.CIA".$CADENA_BUSCAR.$cadena_buscar_zona_svo." group by ae.OPCION");	

		//obtenemos la cantidad de trayectos de construccion  por opcion. 
		$opcion_trayectos_producto =$conexion->query("select ae.OPCION opcion, count(*) trayectos
										from
											hit_producto_cuadros c,
											hit_producto_cuadros_aereos ae,
											hit_transportes tr,
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
										where
											c.CLAVE = ae.CLAVE_CUADRO
											and c.SITUACION = 'W'

														and cc.CLAVE_CUADRO = c.CLAVE
														and cc.CIUDAD = ae.CIUDAD
														and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
														and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
														and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))
														and FIND_IN_SET('".$dias."', cc.DURACIONES)

											and '".date("Y-m-d",strtotime($buscar_fecha))."' between c.PRIMERA_SALIDA and c.ULTIMA_SALIDA

											and ae.CIA = tr.CIA".$CADENA_BUSCAR2.$cadena_buscar_zona_svo." group by ae.OPCION");
											
		
		//echo('---'.$total_pax.'-'.$buscar_fecha.'-'.$fecha_regreso.'-');
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//NUEVO CODIGO PARA OBTENER LA OPCION VALIDA
		$opcion=0;
		$disponible1 = 'N';
		$traydisponibles1 = 0;
		$cantidadtrayectos1 = 0;
		$cantidadtrayectos_producto1 = 0; 
		$disponible2 = 'N';
		$traydisponibles2 = 0;
		$cantidadtrayectos2 = 0;
		$cantidadtrayectos_producto2 = 0;
		$disponible3 = 'N';
		$traydisponibles3 = 0;
		$cantidadtrayectos3 = 0;
		$cantidadtrayectos_producto3 = 0;
		$disponible4 = 'N';
		$traydisponibles4 = 0;
		$cantidadtrayectos4 = 0;
		$cantidadtrayectos_producto4 = 0;
		$disponible5 = 'N';
		$traydisponibles5 = 0;
		$cantidadtrayectos5 = 0;
		$cantidadtrayectos_producto5 = 0;
		$disponible6 = 'N';
		$traydisponibles6 = 0;
		$cantidadtrayectos6 = 0;
		$cantidadtrayectos_producto6 = 0;
		$disponible7 = 'N';
		$traydisponibles7 = 0;
		$cantidadtrayectos7 = 0;
		$cantidadtrayectos_producto7 = 0;
		$disponible8 = 'N';
		$traydisponibles8 = 0;
		$cantidadtrayectos8 = 0;
		$cantidadtrayectos_producto8 = 0;
		$disponible9 = 'N';
		$traydisponibles9 = 0;
		$cantidadtrayectos9 = 0;
		$cantidadtrayectos_producto9 = 0;
		$disponible10 = 'N';
		$traydisponibles10 = 0;
		$cantidadtrayectos10 = 0;
		$cantidadtrayectos_producto10 = 0;

		//Cargamos las variables de todas las opciones con los valores correspondientes segun la query
		for ($i = 0; $i <= $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();

			//Cargamos una variable con la primera opción
			if($i == 0){
			$primera_opcion = $fila['v_opcion'];
			}

			if($fila['v_disponibilidad'] == 'Confirmado'){
				${'disponible'.$fila['v_opcion']} = 'S';
				${'traydisponibles'.$fila['v_opcion']}++;
				for ($k = 0; $k <= $opcion_trayectos->num_rows; $k++) {
					$opcion_trayectos->data_seek($k);
					$fila_trayectos = $opcion_trayectos->fetch_assoc();

					//print($fila_trayectos['opcion'].'-'.$fila_trayectos['trayectos'].'<br>');

					if($fila_trayectos['opcion'] == $fila['v_opcion']){
						${'cantidadtrayectos'.$fila['v_opcion']} = $fila_trayectos['trayectos']; 
					}
				}
				for ($j = 0; $j <= $opcion_trayectos_producto->num_rows; $j++) {
					$opcion_trayectos_producto->data_seek($j);
					$fila_trayectos_producto = $opcion_trayectos_producto->fetch_assoc();
					if($fila_trayectos_producto['opcion'] == $fila['v_opcion']){
						${'cantidadtrayectos_producto'.$fila['v_opcion']} = $fila_trayectos_producto['trayectos']; 
					}
				}
			}
		}	
		
		/*Visualizar en pagina para ver que opciones encuentra*/
		/*echo('opcion 1 - Situacion: '.$disponible1.' - Trayectos Confirmados: '.$traydisponibles1.' - Cantidad de trayectos: '.$cantidadtrayectos1.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto1.'<br>'.
			 'opcion 2 - Situacion: '.$disponible2.' - Trayectos Confirmados: '.$traydisponibles2.' - Cantidad de trayectos: '.$cantidadtrayectos2.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto2.'<br>'.
			 'opcion 3 - Situacion: '.$disponible3.' - Trayectos Confirmados: '.$traydisponibles3.' - Cantidad de trayectos: '.$cantidadtrayectos3.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto3.'<br>'.
			 'opcion 4 - Situacion: '.$disponible4.' - Trayectos Confirmados: '.$traydisponibles4.' - Cantidad de trayectos: '.$cantidadtrayectos4.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto4.'<br>'.
			 'opcion 5 - Situacion: '.$disponible5.' - Trayectos Confirmados: '.$traydisponibles5.' - Cantidad de trayectos: '.$cantidadtrayectos5.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto5.'<br>'.
			 'opcion 6 - Situacion: '.$disponible6.' - Trayectos Confirmados: '.$traydisponibles6.' - Cantidad de trayectos: '.$cantidadtrayectos6.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto6.'<br>'.
			 'opcion 7 - Situacion: '.$disponible7.' - Trayectos Confirmados: '.$traydisponibles7.' - Cantidad de trayectos: '.$cantidadtrayectos7.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto7.'<br>'.
			 'opcion 8 - Situacion: '.$disponible8.' - Trayectos Confirmados: '.$traydisponibles8.' - Cantidad de trayectos: '.$cantidadtrayectos8.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto8.'<br>'.
			 'opcion 9 - Situacion: '.$disponible9.' - Trayectos Confirmados: '.$traydisponibles9.' - Cantidad de trayectos: '.$cantidadtrayectos9.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto9.'<br>'.
			 'opcion 10 - Situacion: '.$disponible10.' - Trayectos Confirmados: '.$traydisponibles10.' - Cantidad de trayectos: '.$cantidadtrayectos10.' - Cantidad de trayectos producto: '.$cantidadtrayectos_producto10.'<br>');	*/ 
		
		//obtenemos la mejor opcion segun disponibilidad de trayectos
		//Primero buscamos que todos los trayectos esten ok
		for ($i = 1; $i <= 10; $i++) {	
			//echo(${'disponible'.$i}.'-'.${'traydisponibles'.$i}.'-'.${'cantidadtrayectos'.$i}.'<br>');
			if(${'cantidadtrayectos'.$i} == ${'cantidadtrayectos_producto'.$i} and ${'disponible'.$i} == 'S' and ${'traydisponibles'.$i} == ${'cantidadtrayectos'.$i}){
				$opcion = $i;
				break;
			}
		}
		//Sino buscamos que todos los trayectos menos uno esten ok
		if($opcion == 0){
			for ($i = 1; $i <= 10; $i++) {	
				//echo(${'disponible'.$i}.'-'.${'traydisponibles'.$i}.'-'.${'cantidadtrayectos'.$i}.'<br>');
				if(${'cantidadtrayectos'.$i} == ${'cantidadtrayectos_producto'.$i} and ${'disponible'.$i} == 'S' and ${'traydisponibles'.$i} == ${'cantidadtrayectos'.$i} - 1){
					$opcion = $i;
					break;
				}
			}
		}
		//Sino buscamos que todos los trayectos menos dos esten ok
		if($opcion == 0){
			for ($i = 1; $i <= 10; $i++) {	
				//echo(${'disponible'.$i}.'-'.${'traydisponibles'.$i}.'-'.${'cantidadtrayectos'.$i}.'<br>');
				if(${'cantidadtrayectos'.$i} == ${'cantidadtrayectos_producto'.$i} and ${'disponible'.$i} == 'S' and ${'traydisponibles'.$i} == ${'cantidadtrayectos'.$i} - 2){
					$opcion = $i;
					break;
				}
			}
		}
		//Sino buscamos que todos los trayectos menos tres esten ok
		if($opcion == 0){
			for ($i = 1; $i <= 10; $i++) {	
				//echo(${'disponible'.$i}.'-'.${'traydisponibles'.$i}.'-'.${'cantidadtrayectos'.$i}.'<br>');
				if(${'cantidadtrayectos'.$i} == ${'cantidadtrayectos_producto'.$i} and ${'disponible'.$i} == 'S' and ${'traydisponibles'.$i} == ${'cantidadtrayectos'.$i} - 3){
					$opcion = $i;
					break;
				}
			}
		}		
		//Si no hay ninguna disponibilidad asignamos la primera opcion que tenga todos los trayectos de la construccion de producto
		if($opcion == 0){
			$opcion = $primera_opcion;
			/*for ($i = 1; $i <= 10; $i++) {	
				if(${'cantidadtrayectos'.$i} == ${'cantidadtrayectos_producto'.$i}){
					$opcion = $i;
					break;
				}
			}	*/		
		}

		//echo($opcion);
	
		//Cargamos array con los trayectos de la opción disponible
		$aereos = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			
			if($fila['v_opcion'] == $opcion){
				array_push($aereos,$fila);
			}
		}		

		//Liberar Memoria usada por la consulta
		$resultado->close();		
		
		
		
		return $aereos;											
	}


	function Cargar_precios_solo_vuelo($total_pax,$fecha_regreso,$opcion,$clases){


		$conexion = $this ->Conexion;


		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_producto = $this ->Buscar_producto;
		$buscar_fecha = $this ->Buscar_fecha;
		$cadena_buscar_zona_svo = $this ->Cadena_buscar_zona_svo;

		if($fecha_regreso == ''){
			$fecha_regreso = $buscar_fecha;
		}

		$dias	= (strtotime($fecha_regreso)-strtotime($buscar_fecha))/86400;
		$dias 	= abs($dias); 
		$dias = floor($dias) + 1;		
		//	print($dias);

		if($buscar_origen != null and $buscar_destino and $buscar_producto and $buscar_fecha and $fecha_regreso){
			$des = " and c.DESTINO = '".$buscar_destino."'";
			$pro = " and c.PRODUCTO = '".$buscar_producto."'";
			//$fec = " and cu.FECHA = DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL (ae.DIA - 1) DAY)";

			$fec = " and ((cu.FECHA = DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL (ae.DIA - 1) DAY) and ae.DIA = 1)
							or
						  (cu.FECHA = DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL (ae.DIA + ".$dias." - ae.DIA - 1) DAY) and ae.DIA > 1))";

			$CADENA_BUSCAR = " and ae.CIUDAD = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fec;	
			}

		}else{
			$CADENA_BUSCAR = " and ae.CIUDAD = 'XXX'";
		}	
		//PENDIENTE INCLUIR EL CALCULO SEGN LA NUEVA ADISPONIBILIDAD DE CLASES
		$resultado =$conexion->query("select 
												sum(p.PRECIO_1 + ae.TASAS +
													case
															when substr('".$clases."', ae.NUMERO, 1) = p.CLASE_2 then p.PRECIO_2
															when substr('".$clases."', ae.NUMERO, 1) = p.CLASE_3 then p.SUPLEMENTO_3
															when substr('".$clases."', ae.NUMERO, 1) = p.CLASE_4 then p.SUPLEMENTO_4
															when substr('".$clases."', ae.NUMERO, 1) = p.CLASE_5 then p.SUPLEMENTO_5
														else 0
														end
												) pvp_solovuelo

										from
											hit_producto_cuadros c,
											hit_producto_cuadros_aereos ae,
											hit_transportes_cupos cu,
											hit_transportes tr,
											hit_producto_cuadros_aereos_precios p,
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
										where
											c.CLAVE = ae.CLAVE_CUADRO
											and ae.CIA = cu.CIA
											and ae.ACUERDO = cu.ACUERDO
											and ae.SUBACUERDO = cu.SUBACUERDO
											and ae.ORIGEN = cu.ORIGEN
											and ae.DESTINO = cu.DESTINO
											and p.CLAVE_AEREO = ae.CLAVE
											and cu.FECHA between p.FECHA_DESDE and p.FECHA_HASTA

														and cc.CLAVE_CUADRO = c.CLAVE
														and cc.CIUDAD = ae.CIUDAD
														and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
														and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
														and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))
														and FIND_IN_SET('".$dias."', cc.DURACIONES)

											and c.SITUACION = 'W'
											and '".date("Y-m-d",strtotime($buscar_fecha))."' between c.PRIMERA_SALIDA and c.ULTIMA_SALIDA
											and ae.OPCION = '".$opcion."'

											and ae.CIA = tr.CIA ".$CADENA_BUSCAR.$cadena_buscar_zona_svo."  ORDER BY cu.fecha, cu.hora_salida");

		//echo($CADENA_BUSCAR.$cadena_buscar_zona_svo);
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}

		$aereos = $resultado->fetch_assoc();
		$pvp = $aereos['pvp_solovuelo'];


		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $pvp;											
	}


	function Cargar_alojamientos($filadesde,$minimo_adultos,$maximo_adultos,$minimo_ninos,$maximo_ninos,$minimo_bebes,$maximo_bebes,$tipo){
		//REVISADO TEMA NOCHES EXTRA
		$conexion = $this ->Conexion;


		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_producto = $this ->Buscar_producto;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_noches = $this ->Buscar_noches;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_alojamiento = $this ->Buscar_alojamiento;
		$cadena_buscar_zona_alojamiento = $this ->Cadena_buscar_zona_alojamiento;

		$minimo_pax = $minimo_adultos + $minimo_ninos + $minimo_bebes; 
		$maximo_pax = $maximo_adultos + $maximo_ninos + $maximo_bebes;

		$dias = $buscar_noches + 1;

		if($buscar_origen != null and $buscar_destino and $buscar_producto and $buscar_fecha and $buscar_noches){
			$des = " and c.DESTINO = '".$buscar_destino."'";
			$pro = " and c.PRODUCTO = '".$buscar_producto."'";
			$fec = " and sal.FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'";
			//$noc = " and p.NOCHES = '".$buscar_noches."'";
			$noc = " and FIND_IN_SET('".$dias."', cc.DURACIONES)";


			$cat = " and a.CATEGORIA = '".$buscar_categoria."'";
			$reg = " and p.ALOJAMIENTO = '".$buscar_alojamiento."'";

			$CADENA_BUSCAR = " and ae.CIUDAD = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fec;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_categoria != null){
				$CADENA_BUSCAR .= $cat;	
			}
			if($buscar_alojamiento != null){
				$CADENA_BUSCAR .= $reg;	
			}
		}else{
			$CADENA_BUSCAR = " and ae.CIUDAD = 'XXX'";
		}	

		$resultado =$conexion->query("SELECT distinct
											ta.NOMBRE tipo_alojamiento,
											a.NOMBRE nombre,
											cat.NOMBRE categoria,
											a.LOCALIDAD localidad,
											substr(a.DESCRIPCION,1,800) descripcion,
											p.ALOJAMIENTO id_alojamiento,
											c.folleto folleto,
											c.cuadro cuadro,
											p.paquete paquete
											from
												hit_producto_cuadros c,
												hit_productos pr,
												hit_categorias cat,
												hit_producto_cuadros_salidas sal,
												hit_producto_cuadros_alojamientos p,
												hit_producto_cuadros_aereos ae,
												hit_alojamientos a,
												hit_tipos_alojamiento ta,
												hit_alojamientos_usos au,
												
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
											where
												c.PRODUCTO = pr.CODIGO
												and c.CLAVE = sal.CLAVE_CUADRO
												and c.CLAVE = p.CLAVE_CUADRO
												and c.CLAVE = ae.CLAVE_CUADRO
												and p.ALOJAMIENTO = a.ID
												and a.CATEGORIA = cat.CODIGO
												and a.TIPO = ta.CODIGO

												and cc.CLAVE_CUADRO = c.CLAVE
												and cc.CIUDAD = ae.CIUDAD
												and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
												and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
												and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), 
												substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))

												and p.ALOJAMIENTO = au.ID
												and p.ACUERDO = au.ACUERDO
												and p.CARACTERISTICA = au.CARACTERISTICA
												and au.USO_MINIMO_ADULTOS <= ".$minimo_adultos."
												and au.USO_MAXIMO_ADULTOS >= ".$maximo_adultos."
												and au.USO_MINIMO_NINOS <= ".$minimo_ninos."
												and au.USO_MAXIMO_NINOS >= ".$maximo_ninos."
												and au.USO_MINIMO_BEBES <= ".$minimo_bebes."
												and au.USO_MAXIMO_BEBES >= ".$maximo_bebes."
												and au.USO_MINIMO <= ".$minimo_pax."
												and au.USO_MAXIMO_PAX >= ".$maximo_pax."
												and ae.NUMERO = 1
												and c.SITUACION = 'W'
												and p.SITUACION = 'W'
												and sal.ESTADO = 'A'".$CADENA_BUSCAR.$cadena_buscar_zona_alojamiento." ORDER BY a.orden, a.categoria, a.nombre");

		//ECHO($minimo_adultos."-".$maximo_adultos."-".$minimo_ninos."-".$maximo_ninos."-".$minimo_bebes."-".$maximo_bebes."-".$minimo_pax."-".$maximo_pax."<br>");

		//ECHO($CADENA_BUSCAR." ***** ".$cadena_buscar_zona_alojamiento);
		
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------
		
		//Guardamos el resultado en una matriz 	
		$Filadesde = $filadesde;
		$alojamientos = array();
		//for ($i = 0; $i < $resultado->num_rows; $i++) {
		//for ($i = 0; $i < 10; $i++) {
		$cadena_codigos = "";
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + 10-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			//$resultado->data_seek(5);
			$fila = $resultado->fetch_assoc();
			$cadena_codigos .= "'".$fila['folleto'].$fila['cuadro'].$fila['paquete']."',";
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['nombre'] == ''){
				break;
			}
			array_push($alojamientos,$fila);
		}
			
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$cadena_codigos = substr($cadena_codigos, 0, -1);
		//echo($cadena_codigos);
		
		if($tipo == 1){
			return $alojamientos;
		}else{
			return $cadena_codigos;
		}
	}


	function Cargar_combo_selector_alojamientos($minimo_adultos,$maximo_adultos,$minimo_ninos,$maximo_ninos,$minimo_bebes,$maximo_bebes){
		//REVISADO TEMA NOCHES EXTRA
		$conexion = $this ->Conexion;		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_producto = $this ->Buscar_producto;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_noches = $this ->Buscar_noches;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_alojamiento = $this ->Buscar_alojamiento;
		$cadena_buscar_zona_alojamiento = $this ->Cadena_buscar_zona_alojamiento;

		$minimo_pax = $minimo_adultos + $minimo_ninos + $minimo_bebes; 
		$maximo_pax = $maximo_adultos + $maximo_ninos + $maximo_bebes;
		
		$dias = $buscar_noches + 1;

		if($buscar_origen != null and $buscar_destino and $buscar_producto and $buscar_fecha and $buscar_noches){
			$des = " and c.DESTINO = '".$buscar_destino."'";
			$pro = " and c.PRODUCTO = '".$buscar_producto."'";
			$fec = " and sal.FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'";
			//$noc = " and p.NOCHES = '".$buscar_noches."'";
			$noc = " and FIND_IN_SET('".$dias."', cc.DURACIONES)";
			$cat = " and a.CATEGORIA = '".$buscar_categoria."'";
			$reg = " and p.ALOJAMIENTO = '".$buscar_alojamiento."'";

			$CADENA_BUSCAR = " and ae.CIUDAD = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fec;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_categoria != null){
				$CADENA_BUSCAR .= $cat;	
			}
			if($buscar_alojamiento != null){
				$CADENA_BUSCAR .= $reg;	
			}
		}else{
			$CADENA_BUSCAR = " and ae.CIUDAD = 'XXX'";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT distinct
											ta.NOMBRE tipo_alojamiento,
											a.NOMBRE nombre,
											cat.NOMBRE categoria,
											a.LOCALIDAD localidad,
											substr(a.DESCRIPCION,1,800) descripcion,
											p.ALOJAMIENTO id_alojamiento,
											c.folleto folleto,
											c.cuadro cuadro,
											p.paquete paquete
											from
												hit_producto_cuadros c,
												hit_productos pr,
												hit_categorias cat,
												hit_producto_cuadros_salidas sal,
												hit_producto_cuadros_alojamientos p,
												hit_producto_cuadros_aereos ae,
												hit_alojamientos a,
												hit_tipos_alojamiento ta,
												hit_alojamientos_usos au,
												
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
											where
												c.PRODUCTO = pr.CODIGO
												and c.CLAVE = sal.CLAVE_CUADRO
												and c.CLAVE = p.CLAVE_CUADRO
												and c.CLAVE = ae.CLAVE_CUADRO
												and p.ALOJAMIENTO = a.ID
												and a.CATEGORIA = cat.CODIGO
												and a.TIPO = ta.CODIGO

												and cc.CLAVE_CUADRO = c.CLAVE
												and cc.CIUDAD = ae.CIUDAD
												and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
												and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
												and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), 
												substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))												
												and p.ALOJAMIENTO = au.ID
												and p.ACUERDO = au.ACUERDO
												and p.CARACTERISTICA = au.CARACTERISTICA
												and au.USO_MINIMO_ADULTOS <= ".$minimo_adultos."
												and au.USO_MAXIMO_ADULTOS >= ".$maximo_adultos."
												and au.USO_MINIMO_NINOS <= ".$minimo_ninos."
												and au.USO_MAXIMO_NINOS >= ".$maximo_ninos."
												and au.USO_MINIMO_BEBES <= ".$minimo_bebes."
												and au.USO_MAXIMO_BEBES >= ".$maximo_bebes."
												and au.USO_MINIMO <= ".$minimo_pax."
												and au.USO_MAXIMO_PAX >= ".$maximo_pax."
												and ae.NUMERO = 1
												and c.SITUACION = 'W'
												and p.SITUACION = 'W'
												and sal.ESTADO = 'A'".$CADENA_BUSCAR.$cadena_buscar_zona_alojamiento." ORDER BY a.orden, a.categoria, a.nombre");
		$numero_filas = $resultadoc->num_rows;         //----------
		//echo($numero_filas);
		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/
		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PAISES' AND USUARIO = '".$Usuario."'");*/
			$Nfilas	 = 10;																	  //------
			$cada = $Nfilas + 1;
			$combo_select = array();
			for ($num_fila = 1; $num_fila <= $numero_filas; $num_fila++) {
				
				if($num_fila == $cada){
					$combo_select[$cada - $Nfilas] = array ( "inicio" => $cada - $Nfilas, "fin" => $cada - 1);
					//echo(($cada - $Nfilas).'-'.($cada - 1).'<br>');
					$cada = $cada + $Nfilas;
				}
				if($num_fila == $numero_filas){
					$combo_select[$cada - $Nfilas] = array ( "inicio" => $cada - $Nfilas, "fin" => $numero_filas);
					//echo(($cada - $Nfilas).'-'.($numero_filas).'<br>');
				}
			}
			/*$num_filas->close();*/
		}else{
			$combo_select[1] = array ( "inicio" => 1, "fin" => 0);
			$resultadoc->close();
		}
		return $combo_select;											
	}	



	function Cargar_alojamientos_SoloHotel($filadesde,$minimo_adultos,$maximo_adultos,$minimo_ninos,$maximo_ninos,$minimo_bebes,$maximo_bebes,$tipo){
		//REVISADO TEMA NOCHES EXTRA

		$conexion = $this ->Conexion;


		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_producto = $this ->Buscar_producto;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_noches = $this ->Buscar_noches;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_alojamiento = $this ->Buscar_alojamiento;
		$cadena_buscar_zona_alojamiento = $this ->Cadena_buscar_zona_alojamiento;

		$minimo_pax = $minimo_adultos + $minimo_ninos + $minimo_bebes; 
		$maximo_pax = $maximo_adultos + $maximo_ninos + $maximo_bebes;
		
		$dias = $buscar_noches + 1;

		$CADENA_BUSCAR = "";

		if($buscar_origen != null and $buscar_destino and $buscar_producto and $buscar_fecha and $buscar_noches){
			$des = " and c.DESTINO = '".$buscar_destino."'";
			$pro = " and c.PRODUCTO = '".$buscar_producto."'";
			$fec = " and sal.FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'";
			//$noc = " and p.NOCHES = '".$buscar_noches."'";
			$noc = " and FIND_IN_SET('".$dias."', cc.DURACIONES)";
			$cat = " and a.CATEGORIA = '".$buscar_categoria."'";
			$reg = " and p.ALOJAMIENTO = '".$buscar_alojamiento."'";

			//$CADENA_BUSCAR = " and ae.CIUDAD = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fec;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_categoria != null){
				$CADENA_BUSCAR .= $cat;	
			}
			if($buscar_alojamiento != null){
				$CADENA_BUSCAR .= $reg;	
			}
		}else{
			$CADENA_BUSCAR = " and c.DESTINO = 'XXX'";
		}	

		$resultado =$conexion->query("SELECT distinct
											ta.NOMBRE tipo_alojamiento,
											a.NOMBRE nombre,
											cat.NOMBRE categoria,
											a.LOCALIDAD localidad,
											substr(a.DESCRIPCION,1,800) descripcion,
											p.ALOJAMIENTO id_alojamiento,
											c.folleto folleto,
											c.cuadro cuadro,
											p.paquete paquete
											from
												hit_producto_cuadros c,
												hit_productos pr,
												hit_categorias cat,
												hit_producto_cuadros_salidas sal,
												hit_producto_cuadros_alojamientos p,
												hit_alojamientos a,
												hit_tipos_alojamiento ta,
												hit_alojamientos_usos au,
												
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe

											where
												c.PRODUCTO = pr.CODIGO
												and c.CLAVE = sal.CLAVE_CUADRO
												and c.CLAVE = p.CLAVE_CUADRO
												and p.ALOJAMIENTO = a.ID
												and a.CATEGORIA = cat.CODIGO
												and a.TIPO = ta.CODIGO

												and cc.CLAVE_CUADRO = c.CLAVE
												and cc.CIUDAD = 'BLN'
												and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
												and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
												and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), 
												substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))

												and p.ALOJAMIENTO = au.ID
												and p.ACUERDO = au.ACUERDO
												and p.CARACTERISTICA = au.CARACTERISTICA
												and au.USO_MINIMO_ADULTOS <= ".$minimo_adultos."
												and au.USO_MAXIMO_ADULTOS >= ".$maximo_adultos."
												and au.USO_MINIMO_NINOS <= ".$minimo_ninos."
												and au.USO_MAXIMO_NINOS >= ".$maximo_ninos."
												and au.USO_MINIMO_BEBES <= ".$minimo_bebes."
												and au.USO_MAXIMO_BEBES >= ".$maximo_bebes."
												and au.USO_MINIMO <= ".$minimo_pax."
												and au.USO_MAXIMO_PAX >= ".$maximo_pax."
												and c.SITUACION = 'W'
												and p.SITUACION = 'W'
												and sal.ESTADO = 'A'".$CADENA_BUSCAR.$cadena_buscar_zona_alojamiento." ORDER BY a.orden, a.categoria, a.nombre");

		/*if($tipo==1){
		echo($CADENA_BUSCAR."-----------------------");
		}	*/
		
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	
		$Filadesde = $filadesde;
		$alojamientos = array();
		//for ($i = 0; $i < $resultado->num_rows; $i++) {
		//for ($i = 0; $i < 10; $i++) {
		$cadena_codigos = "";
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + 10-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			//$resultado->data_seek(5);
			$fila = $resultado->fetch_assoc();
			$cadena_codigos .= "'".$fila['folleto'].$fila['cuadro'].$fila['paquete']."',";
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['nombre'] == ''){
				break;
			}
			array_push($alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$cadena_codigos = substr($cadena_codigos, 0, -1);
		
		if($tipo == 1){		
			return $alojamientos;	
		}else{
			return $cadena_codigos;
		}
		
	}	


	function Cargar_combo_selector_alojamientos_SoloHotel($minimo_adultos,$maximo_adultos,$minimo_ninos,$maximo_ninos,$minimo_bebes,$maximo_bebes){
		//REVISADO TEMA NOCHES EXTRA
		$conexion = $this ->Conexion;		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_producto = $this ->Buscar_producto;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_noches = $this ->Buscar_noches;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_alojamiento = $this ->Buscar_alojamiento;
		$cadena_buscar_zona_alojamiento = $this ->Cadena_buscar_zona_alojamiento;
		
		$minimo_pax = $minimo_adultos + $minimo_ninos + $minimo_bebes; 
		$maximo_pax = $maximo_adultos + $maximo_ninos + $maximo_bebes;	
		
		$dias = $buscar_noches + 1;

		$CADENA_BUSCAR = "";

		if($buscar_origen != null and $buscar_destino and $buscar_producto and $buscar_fecha and $buscar_noches){
			$des = " and c.DESTINO = '".$buscar_destino."'";
			$pro = " and c.PRODUCTO = '".$buscar_producto."'";
			$fec = " and sal.FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'";
			//$noc = " and p.NOCHES = '".$buscar_noches."'";
			$noc = " and FIND_IN_SET('".$dias."', cc.DURACIONES)";
			$cat = " and a.CATEGORIA = '".$buscar_categoria."'";
			$reg = " and p.ALOJAMIENTO = '".$buscar_alojamiento."'";

			//$CADENA_BUSCAR = " and ae.CIUDAD = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fec;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_categoria != null){
				$CADENA_BUSCAR .= $cat;	
			}
			if($buscar_alojamiento != null){
				$CADENA_BUSCAR .= $reg;	
			}
		}else{
			$CADENA_BUSCAR = " and c.DESTINO = 'XXX'";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT distinct
											ta.NOMBRE tipo_alojamiento,
											a.NOMBRE nombre,
											cat.NOMBRE categoria,
											a.LOCALIDAD localidad,
											substr(a.DESCRIPCION,1,800) descripcion,
											p.ALOJAMIENTO id_alojamiento,
											c.folleto folleto,
											c.cuadro cuadro,
											p.paquete paquete
											from
												hit_producto_cuadros c,
												hit_productos pr,
												hit_categorias cat,
												hit_producto_cuadros_salidas sal,
												hit_producto_cuadros_alojamientos p,
												hit_alojamientos a,
												hit_tipos_alojamiento ta,
												hit_alojamientos_usos au,
												
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe

											where
												c.PRODUCTO = pr.CODIGO
												and c.CLAVE = sal.CLAVE_CUADRO
												and c.CLAVE = p.CLAVE_CUADRO
												and p.ALOJAMIENTO = a.ID
												and a.CATEGORIA = cat.CODIGO
												and a.TIPO = ta.CODIGO

												and cc.CLAVE_CUADRO = c.CLAVE
												and cc.CIUDAD = 'BLN'
												and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
												and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
												and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), 
												substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))

												and p.ALOJAMIENTO = au.ID
												and p.ACUERDO = au.ACUERDO
												and p.CARACTERISTICA = au.CARACTERISTICA
												and au.USO_MINIMO_ADULTOS <= ".$minimo_adultos."
												and au.USO_MAXIMO_ADULTOS >= ".$maximo_adultos."
												and au.USO_MINIMO_NINOS <= ".$minimo_ninos."
												and au.USO_MAXIMO_NINOS >= ".$maximo_ninos."
												and au.USO_MINIMO_BEBES <= ".$minimo_bebes."
												and au.USO_MAXIMO_BEBES >= ".$maximo_bebes."
												and au.USO_MINIMO <= ".$minimo_pax."
												and au.USO_MAXIMO_PAX >= ".$maximo_pax."
												and c.SITUACION = 'W'
												and p.SITUACION = 'W'
												and sal.ESTADO = 'A'".$CADENA_BUSCAR.$cadena_buscar_zona_alojamiento." ORDER BY a.orden, a.categoria, a.nombre");
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
			/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PAISES' AND USUARIO = '".$Usuario."'");*/
			$Nfilas	 = 10;																	  //------
			$cada = $Nfilas + 1;
			$combo_select = array();
			for ($num_fila = 1; $num_fila <= $numero_filas; $num_fila++) {
				//echo($num_fila.'<br');
				if($num_fila == $cada){
					$combo_select[$cada - $Nfilas] = array ( "inicio" => $cada - $Nfilas, "fin" => $cada - 1);
					//echo(($cada - $Nfilas).'-'.($cada - 1).'<br>');
					$cada = $cada + $Nfilas;
					
				}
				if($num_fila == $numero_filas){
					$combo_select[$cada - $Nfilas] = array ( "inicio" => $cada - $Nfilas, "fin" => $numero_filas);
					//echo(($cada - $Nfilas).'-'.($numero_filas).'<br>');
				}

			}
			/*$num_filas->close();*/
		}else{
			$combo_select[1] = array ( "inicio" => 1, "fin" => 0);
			$resultadoc->close();
		}
		return $combo_select;											
	}	



	function Cargar_paquetes($filadesde, $total_uso_2_3, $opcion,$clases,$minimo_adultos,$maximo_adultos,$minimo_ninos,$maximo_ninos,$minimo_bebes,$maximo_bebes){
		//REVISADO TEMA NOCHES EXTRA
		//echo($total_uso_2_3);

		$conexion = $this ->Conexion;

		//echo($clases."-");

		$cadena_codigos = $this->Cargar_alojamientos($filadesde,$minimo_adultos,$maximo_adultos,$minimo_ninos,$maximo_ninos,$minimo_bebes,$maximo_bebes,2);
		//echo($cadena_codigos);

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_producto = $this ->Buscar_producto;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_noches = $this ->Buscar_noches;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_alojamiento = $this ->Buscar_alojamiento;
		$cadena_buscar_zona_alojamiento = $this ->Cadena_buscar_zona_alojamiento;
	
		$minimo_pax = $minimo_adultos + $minimo_ninos + $minimo_bebes; 
		$maximo_pax = $maximo_adultos + $maximo_ninos + $maximo_bebes;
		
		$dias = $buscar_noches + 1;

		if($opcion == ''){
			$opcion = 1;
		}
	

		if($buscar_origen != null and $buscar_destino and $buscar_producto and $buscar_fecha and $buscar_noches){
			$des = " and c.DESTINO = '".$buscar_destino."'";
			$pro = " and c.PRODUCTO = '".$buscar_producto."'";
			$fec = " and sal.FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'";
			//$noc = " and p.NOCHES = '".$buscar_noches."'";
			$noc = " and FIND_IN_SET('".$dias."', cc.DURACIONES)";
			$cat = " and a.CATEGORIA = '".$buscar_categoria."'";
			$reg = " and p.ALOJAMIENTO = '".$buscar_alojamiento."'";


			$CADENA_BUSCAR = " and ae.CIUDAD = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fec;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_categoria != null){
				$CADENA_BUSCAR .= $cat;	
			}
			if($buscar_alojamiento != null){
				$CADENA_BUSCAR .= $reg;	
			}
		}else{
			$CADENA_BUSCAR = " and ae.CIUDAD = 'XXX'";
		}	


		$resultado =$conexion->query("SELECT 	distinct							
												p.ALOJAMIENTO id_alojamiento,
												c.FOLLETO r_folleto, c.CUADRO r_cuadro, ae.CIUDAD r_ciudad, ae.OPCION r_opcion, '".date("Y-m-d",strtotime($buscar_fecha))."' r_fecha, 
												p.PAQUETE r_paquete, p.NUMERO r_numero, 
												car.nombre caracteristica, p.CARACTERISTICA r_cod_caracteristica, 

												(SELECT `PRODUCTO_DISPONIBILIDAD_DOBLE_2_3`(p.ALOJAMIENTO, p.ACUERDO, p.HABITACION, p.CARACTERISTICA, '".date("Y-m-d",strtotime($buscar_fecha))."', ".$buscar_noches.", '".$total_uso_2_3."')) estado,


												IFNULL(
												concat(round(SUM(
												CASE p.REGIMEN
													WHEN 'SA'
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, ae.CIUDAD, 'SA', '".date("Y-m-d",strtotime($buscar_fecha))."', '".$opcion."', '".$clases."', '".$buscar_noches."')),0)
												END) 
												+ 
												IFNULL((SELECT `RESERVAS_OBTIENE_SUPLEMENTOS_FECHA_SALIDA`(c.FOLLETO, c.CUADRO, p.PAQUETE, '".date("Y-m-d",strtotime($buscar_fecha))."', CURDATE(), 
												SUM(
												CASE p.REGIMEN
													WHEN 'SA'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, ae.CIUDAD, 'SA', '".date("Y-m-d",strtotime($buscar_fecha))."', '".$opcion."', '".$clases."', '".$buscar_noches."')),0)
												END)
												)),0),0),' €')
												,'-')
												precio_pax_SA,
												
												IFNULL(
												concat(round(SUM(
												CASE p.REGIMEN
													WHEN 'AD'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, ae.CIUDAD, 'AD', '".date("Y-m-d",strtotime($buscar_fecha))."', '".$opcion."', '".$clases."', '".$buscar_noches."')),0)
												END) 
												+ 
												IFNULL((SELECT `RESERVAS_OBTIENE_SUPLEMENTOS_FECHA_SALIDA`(c.FOLLETO, c.CUADRO, p.PAQUETE, '".date("Y-m-d",strtotime($buscar_fecha))."', CURDATE(), 
												SUM(
												CASE p.REGIMEN
													WHEN 'AD'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, ae.CIUDAD, 'AD', '".date("Y-m-d",strtotime($buscar_fecha))."', '".$opcion."', '".$clases."', '".$buscar_noches."')),0)
												END)
												)),0),0),' €')
												,'-')
												precio_pax_AD,
												
												IFNULL(
												concat(round(SUM(
												CASE p.REGIMEN
													WHEN 'MP'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, ae.CIUDAD, 'MP', '".date("Y-m-d",strtotime($buscar_fecha))."', '".$opcion."', '".$clases."', '".$buscar_noches."')),0)
												END)  
												+ 
												IFNULL((SELECT `RESERVAS_OBTIENE_SUPLEMENTOS_FECHA_SALIDA`(c.FOLLETO, c.CUADRO, p.PAQUETE, '".date("Y-m-d",strtotime($buscar_fecha))."', CURDATE(), 
												SUM(
												CASE p.REGIMEN
													WHEN 'MP'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, ae.CIUDAD, 'MP', '".date("Y-m-d",strtotime($buscar_fecha))."', '".$opcion."', '".$clases."', '".$buscar_noches."')),0)
												END)
												)),0),0),' €')
												,'-')
												precio_pax_MP,												
												
												IFNULL(
												concat(round(SUM(
												CASE p.REGIMEN
													WHEN 'PC'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, ae.CIUDAD, 'PC', '".date("Y-m-d",strtotime($buscar_fecha))."', '".$opcion."', '".$clases."', '".$buscar_noches."')),0)
												END)  
												+ 
												IFNULL((SELECT `RESERVAS_OBTIENE_SUPLEMENTOS_FECHA_SALIDA`(c.FOLLETO, c.CUADRO, p.PAQUETE, '".date("Y-m-d",strtotime($buscar_fecha))."', CURDATE(), 
												SUM(
												CASE p.REGIMEN
													WHEN 'PC'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, ae.CIUDAD, 'PC', '".date("Y-m-d",strtotime($buscar_fecha))."', '".$opcion."', '".$clases."', '".$buscar_noches."')),0)
												END)
												)),0),0),' €')
												,'-')
												precio_pax_PC,												
												
												IFNULL(
												concat(round(SUM(
												CASE p.REGIMEN
													WHEN 'TI'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, ae.CIUDAD, 'TI', '".date("Y-m-d",strtotime($buscar_fecha))."', '".$opcion."', '".$clases."', '".$buscar_noches."')),0)
												END)  
												+ 
												IFNULL((SELECT `RESERVAS_OBTIENE_SUPLEMENTOS_FECHA_SALIDA`(c.FOLLETO, c.CUADRO, p.PAQUETE, '".date("Y-m-d",strtotime($buscar_fecha))."', CURDATE(), 
												SUM(
												CASE p.REGIMEN
													WHEN 'TI'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, ae.CIUDAD, 'TI', '".date("Y-m-d",strtotime($buscar_fecha))."', '".$opcion."', '".$clases."', '".$buscar_noches."')),0)
												END)
												)),0),0),' €')
												,'-')
												precio_pax_TI,
												concat(au.MINIMO_DETALLE,' / ',au.MAXIMO_DETALLE) min_max,
												au.USO_MINIMO paquetes_uso_minimo,
												au.USO_MINIMO_ADULTOS paquetes_uso_minimo_adultos,
												au.USO_MINIMO_NINOS paquetes_uso_minimo_ninos,
												au.USO_MINIMO_BEBES paquetes_uso_minimo_bebes,
												au.USO_MAXIMO_PAX paquetes_uso_maximo,
												au.USO_MAXIMO_ADULTOS paquetes_uso_maximo_adultos,
												au.USO_MAXIMO_NINOS paquetes_uso_maximo_ninos,
												au.USO_MAXIMO_BEBES paquetes_uso_maximo_bebes

										from
											hit_producto_cuadros c,
											hit_productos pr,
											hit_categorias cat,
											hit_habitaciones_car car,
											hit_producto_cuadros_salidas sal,
											hit_producto_cuadros_alojamientos p,
											hit_producto_cuadros_aereos ae,
											hit_alojamientos a,
											hit_alojamientos_usos au,
												
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
										where
											c.PRODUCTO = pr.CODIGO
											and c.CLAVE = sal.CLAVE_CUADRO
											and c.CLAVE = p.CLAVE_CUADRO
											and c.CLAVE = ae.CLAVE_CUADRO
											and p.ALOJAMIENTO = a.ID
											and a.CATEGORIA = cat.CODIGO

												and cc.CLAVE_CUADRO = c.CLAVE
												and cc.CIUDAD = ae.CIUDAD
												and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
												and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
												and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), 
												substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))	

											and p.CARACTERISTICA = car.CODIGO
											and p.ALOJAMIENTO = au.ID
											and p.ACUERDO = au.ACUERDO
											and p.CARACTERISTICA = au.CARACTERISTICA
											and ae.NUMERO = 1
											and c.SITUACION = 'W'
											and p.SITUACION = 'W'
											and ae.OPCION = ".$opcion." 
											and au.USO_MINIMO_ADULTOS <= ".$minimo_adultos."
											and au.USO_MAXIMO_ADULTOS >= ".$maximo_adultos."
											and au.USO_MINIMO_NINOS <= ".$minimo_ninos."
											and au.USO_MAXIMO_NINOS >= ".$maximo_ninos."
											and au.USO_MINIMO_BEBES <= ".$minimo_bebes."
											and au.USO_MAXIMO_BEBES >= ".$maximo_bebes."
											and au.USO_MINIMO <= ".$minimo_pax."
											and au.USO_MAXIMO_PAX >= ".$maximo_pax."
											and sal.ESTADO = 'A' ".$CADENA_BUSCAR.$cadena_buscar_zona_alojamiento." 
											and CONCAT(c.FOLLETO,c.CUADRO,p.PAQUETE) in (".$cadena_codigos.")
											GROUP BY  p.ALOJAMIENTO,c.FOLLETO, c.CUADRO, ae.CIUDAD, ae.OPCION, '".date("Y-m-d",strtotime($buscar_fecha))."', p.PAQUETE,car.nombre, p.CARACTERISTICA, '---', cc.CLAVE
											ORDER BY r_paquete,precio_pax_SA,precio_pax_AD,precio_pax_MP,precio_pax_PC,precio_pax_TI");


		//echo($buscar_fecha."<br>".$total_uso_2_3."<br>".$opcion."<br>".$CADENA_BUSCAR."<br>".$cadena_buscar_zona_alojamiento);
		//echo($cadena_codigos);
		//echo($minimo_adultos.$maximo_adultos.$minimo_ninos.$maximo_ninos);
		//$prueba = "'hola'";
		//echo($prueba);

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$alojamientos = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $alojamientos;											
	}

	
	
	function Cargar_paquetes_SoloHotel($filadesde, $total_uso_2_3,$minimo_adultos,$maximo_adultos,$minimo_ninos,$maximo_ninos,$minimo_bebes,$maximo_bebes){
		//REVISADO TEMA NOCHES EXTRA
		//echo($total_uso_2_3);

		$conexion = $this ->Conexion;

		$cadena_codigos = $this->Cargar_alojamientos_SoloHotel($filadesde,$minimo_adultos,$maximo_adultos,$minimo_ninos,$maximo_ninos,$minimo_bebes,$maximo_bebes,2);
		//echo($cadena_codigos);		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_producto = $this ->Buscar_producto;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_noches = $this ->Buscar_noches;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_alojamiento = $this ->Buscar_alojamiento;
		$cadena_buscar_zona_alojamiento = $this ->Cadena_buscar_zona_alojamiento;

		$CADENA_BUSCAR = "";
		
		$minimo_pax = $minimo_adultos + $minimo_ninos + $minimo_bebes; 
		$maximo_pax = $maximo_adultos + $maximo_ninos + $maximo_bebes;	

		$dias = $buscar_noches + 1;

		if($buscar_origen != null and $buscar_destino and $buscar_producto and $buscar_fecha and $buscar_noches){
			$des = " and c.DESTINO = '".$buscar_destino."'";
			$pro = " and c.PRODUCTO = '".$buscar_producto."'";
			$fec = " and sal.FECHA = '".date("Y-m-d",strtotime($buscar_fecha))."'";
			//$noc = " and p.NOCHES = '".$buscar_noches."'";
			$noc = " and FIND_IN_SET('".$dias."', cc.DURACIONES)";
			$cat = " and a.CATEGORIA = '".$buscar_categoria."'";
			$reg = " and p.ALOJAMIENTO = '".$buscar_alojamiento."'";


			//$CADENA_BUSCAR = " and ae.CIUDAD = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fec;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_categoria != null){
				$CADENA_BUSCAR .= $cat;	
			}
			if($buscar_alojamiento != null){
				$CADENA_BUSCAR .= $reg;	
			}
		}else{
			$CADENA_BUSCAR = " and c.DESTINO = 'XXX'";
		}	


		$resultado =$conexion->query("SELECT 	distinct							
												p.ALOJAMIENTO id_alojamiento,
												c.FOLLETO r_folleto, c.CUADRO r_cuadro, 'BLN' r_ciudad, 0 r_opcion, '".date("Y-m-d",strtotime($buscar_fecha))."' r_fecha, 
												p.PAQUETE r_paquete, p.NUMERO r_numero, 
												car.nombre caracteristica, p.CARACTERISTICA r_cod_caracteristica, 

												(SELECT `PRODUCTO_DISPONIBILIDAD_DOBLE_2_3`(p.ALOJAMIENTO, p.ACUERDO, p.HABITACION, p.CARACTERISTICA, '".date("Y-m-d",strtotime($buscar_fecha))."', ".$buscar_noches.", '".$total_uso_2_3."')) estado,


												IFNULL(
												concat(round(SUM(
												CASE p.REGIMEN
													WHEN 'SA'
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, 'BLN', 'SA', '".date("Y-m-d",strtotime($buscar_fecha))."', '0', '0', '".$buscar_noches."')),0)
												END) 
												+ 
												IFNULL((SELECT `RESERVAS_OBTIENE_SUPLEMENTOS_FECHA_SALIDA`(c.FOLLETO, c.CUADRO, p.PAQUETE, '".date("Y-m-d",strtotime($buscar_fecha))."', CURDATE(), 
												SUM(
												CASE p.REGIMEN
													WHEN 'SA'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, 'BLN', 'SA', '".date("Y-m-d",strtotime($buscar_fecha))."', '0', '0', '".$buscar_noches."')),0)
												END)
												)),0),0),' €')
												,'-')
												precio_pax_SA,
												
												IFNULL(
												concat(round(SUM(
												CASE p.REGIMEN
													WHEN 'AD'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, 'BLN', 'AD', '".date("Y-m-d",strtotime($buscar_fecha))."', '0', '0', '".$buscar_noches."')),0)
												END) 
												+ 
												IFNULL((SELECT `RESERVAS_OBTIENE_SUPLEMENTOS_FECHA_SALIDA`(c.FOLLETO, c.CUADRO, p.PAQUETE, '".date("Y-m-d",strtotime($buscar_fecha))."', CURDATE(), 
												SUM(
												CASE p.REGIMEN
													WHEN 'AD'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, 'BLN', 'AD', '".date("Y-m-d",strtotime($buscar_fecha))."', '0', '0', '".$buscar_noches."')),0)
												END)
												)),0),0),' €')
												,'-')
												precio_pax_AD,
												
												IFNULL(
												concat(round(SUM(
												CASE p.REGIMEN
													WHEN 'MP'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, 'BLN', 'MP', '".date("Y-m-d",strtotime($buscar_fecha))."', '0', '0', '".$buscar_noches."')),0)
												END)  
												+ 
												IFNULL((SELECT `RESERVAS_OBTIENE_SUPLEMENTOS_FECHA_SALIDA`(c.FOLLETO, c.CUADRO, p.PAQUETE, '".date("Y-m-d",strtotime($buscar_fecha))."', CURDATE(), 
												SUM(
												CASE p.REGIMEN
													WHEN 'MP'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, 'BLN', 'MP', '".date("Y-m-d",strtotime($buscar_fecha))."', '0', '0', '".$buscar_noches."')),0)
												END)
												)),0),0),' €')
												,'-')
												precio_pax_MP,												
												
												IFNULL(
												concat(round(SUM(
												CASE p.REGIMEN
													WHEN 'PC'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, 'BLN', 'PC', '".date("Y-m-d",strtotime($buscar_fecha))."', '0', '0', '".$buscar_noches."')),0)
												END)  
												+ 
												IFNULL((SELECT `RESERVAS_OBTIENE_SUPLEMENTOS_FECHA_SALIDA`(c.FOLLETO, c.CUADRO, p.PAQUETE, '".date("Y-m-d",strtotime($buscar_fecha))."', CURDATE(), 
												SUM(
												CASE p.REGIMEN
													WHEN 'PC'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, 'BLN', 'PC', '".date("Y-m-d",strtotime($buscar_fecha))."', '0', '0', '".$buscar_noches."')),0)
												END)
												)),0),0),' €')
												,'-')
												precio_pax_PC,												
												
												IFNULL(
												concat(round(SUM(
												CASE p.REGIMEN
													WHEN 'TI'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, 'BLN', 'TI', '".date("Y-m-d",strtotime($buscar_fecha))."', '0', '0', '".$buscar_noches."')),0)
												END)  
												+ 
												IFNULL((SELECT `RESERVAS_OBTIENE_SUPLEMENTOS_FECHA_SALIDA`(c.FOLLETO, c.CUADRO, p.PAQUETE, '".date("Y-m-d",strtotime($buscar_fecha))."', CURDATE(), 
												SUM(
												CASE p.REGIMEN
													WHEN 'TI'																								
														THEN round((SELECT `PRODUCTO_PRECIO_PAQUETE_CON_TASAS`(c.FOLLETO, c.CUADRO, p.PAQUETE, p.NUMERO, 'BLN', 'TI', '".date("Y-m-d",strtotime($buscar_fecha))."', '0', '0', '".$buscar_noches."')),0)
												END)
												)),0),0),' €')
												,'-')
												precio_pax_TI,
												concat(au.MINIMO_DETALLE,' / ',au.MAXIMO_DETALLE) min_max,
												au.USO_MINIMO paquetes_uso_minimo,
												au.USO_MINIMO_ADULTOS paquetes_uso_minimo_adultos,
												au.USO_MINIMO_NINOS paquetes_uso_minimo_ninos,
												au.USO_MINIMO_BEBES paquetes_uso_minimo_bebes,
												au.USO_MAXIMO_PAX paquetes_uso_maximo,
												au.USO_MAXIMO_ADULTOS paquetes_uso_maximo_adultos,
												au.USO_MAXIMO_NINOS paquetes_uso_maximo_ninos,
												au.USO_MAXIMO_BEBES paquetes_uso_maximo_bebes

										from
											hit_producto_cuadros c,
											hit_productos pr,
											hit_categorias cat,
											hit_habitaciones_car car,
											hit_producto_cuadros_salidas sal,
											hit_producto_cuadros_alojamientos p,
											hit_alojamientos a,
											hit_alojamientos_usos au,
												
												  hit_producto_cuadros_calendarios_ciudades cc,
										 		  hit_fechas fe
										where
											c.PRODUCTO = pr.CODIGO
											and c.CLAVE = sal.CLAVE_CUADRO
											and c.CLAVE = p.CLAVE_CUADRO
											and p.ALOJAMIENTO = a.ID
											and a.CATEGORIA = cat.CODIGO

												and cc.CLAVE_CUADRO = c.CLAVE
												and cc.CIUDAD = 'BLN'
												and '".date("Y-m-d",strtotime($buscar_fecha))."' between cc.FECHA_DESDE and cc.FECHA_HASTA
												and '".date("Y-m-d",strtotime($buscar_fecha))."' = fe.FECHA
												and fe.DIA in (substr(cc.DIAS_SEMANA,1,1), substr(cc.DIAS_SEMANA,2,1), substr(cc.DIAS_SEMANA,3,1), substr(cc.DIAS_SEMANA,4,1), 
												substr(cc.DIAS_SEMANA,5,1), substr(cc.DIAS_SEMANA,6,1), substr(cc.DIAS_SEMANA,7,1))	

											and p.CARACTERISTICA = car.CODIGO
											and p.ALOJAMIENTO = au.ID
											and p.ACUERDO = au.ACUERDO
											and p.CARACTERISTICA = au.CARACTERISTICA
											and c.SITUACION = 'W'
											and au.USO_MINIMO_ADULTOS <= ".$minimo_adultos."
											and au.USO_MAXIMO_ADULTOS >= ".$maximo_adultos."
											and au.USO_MINIMO_NINOS <= ".$minimo_ninos."
											and au.USO_MAXIMO_NINOS >= ".$maximo_ninos."
											and au.USO_MINIMO_BEBES <= ".$minimo_bebes."
											and au.USO_MAXIMO_BEBES >= ".$maximo_bebes."
											and au.USO_MINIMO <= ".$minimo_pax."
											and au.USO_MAXIMO_PAX >= ".$maximo_pax."
											and sal.ESTADO = 'A' ".$CADENA_BUSCAR.$cadena_buscar_zona_alojamiento." 
											and CONCAT(c.FOLLETO,c.CUADRO,p.PAQUETE) in (".$cadena_codigos.")
											GROUP BY  p.ALOJAMIENTO,c.FOLLETO, c.CUADRO, r_ciudad, r_opcion, '".date("Y-m-d",strtotime($buscar_fecha))."', p.PAQUETE,car.nombre, p.CARACTERISTICA, '---', cc.CLAVE
											ORDER BY r_paquete,precio_pax_SA,precio_pax_AD,precio_pax_MP,precio_pax_PC,precio_pax_TI");


		//echo($CADENA_BUSCAR);

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$alojamientos = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $alojamientos;											
	}	


	function Cargar_iconos(){
		//echo($total_uso_2_3);

		$conexion = $this ->Conexion;

		/*$resultado =$conexion->query("select id icono_id, case	when s.aire_acondicionado_central = 'S' then 'aire-acondicionado.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.NINOS_GRATIS = 'S' then 'ninos-gratis.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.CELIACOS = 'S' then 'celiaco.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.DISCAPACITADOS = 'S' then 'discapacitados.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.GOLF = 'S' then 'golf.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.GIMNASIO = 'S' then 'gym.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.SOLO_ADULTOS = 'S' then 'solo-adultos.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.SPA = 'S' then 'spa.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.TODO_INCLUIDO = 'S' then 'todo-incluido.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.VISTA_MAR = 'S' then 'vista-mar.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.WIFI = 'S' then 'wifi.png' end icono_imagen from hit_alojamientos_servicios s
										order by icono_id, icono_imagen");*/

		$resultado =$conexion->query("select id_alojamiento icono_id, case when s.aire_acondicionado_central = 'S' then 'aire-acondicionado.png' when s.aire_acondicionado_central = 'N' then 'aire-acondicionado_no.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.NINOS_GRATIS = 'S' then 'ninos-gratis.png' when s.NINOS_GRATIS = 'N' then 'ninos-gratis_no.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.CELIACOS = 'S' then 'celiaco.png' when s.CELIACOS = 'N' then 'celiaco_no.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.DISCAPACITADOS = 'S' then 'discapacitados.png' when s.DISCAPACITADOS = 'N' then 'discapacitados_no.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.GOLF = 'S' then 'golf.png' when s.GOLF = 'N' then 'golf_no.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.GIMNASIO = 'S' then 'gym.png' when s.GIMNASIO = 'N' then 'gym_no.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.SOLO_ADULTOS = 'S' then 'solo-adultos.png' when s.SOLO_ADULTOS = 'N' then 'solo-adultos_no.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.SPA = 'S' then 'spa.png' when s.SPA = 'N' then 'spa_no.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.TODO_INCLUIDO = 'S' then 'todo-incluido.png' when s.TODO_INCLUIDO = 'N' then 'todo-incluido_no.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.VISTA_MAR = 'S' then 'vista-mar.png' when s.VISTA_MAR = 'N' then 'vista-mar_no.png' end icono_imagen from hit_alojamientos_servicios s
										UNION
										select id_alojamiento icono_id, case when s.WIFI = 'S' then 'wifi.png' when s.WIFI = 'N' then 'wifi_no.png' end icono_imagen from hit_alojamientos_servicios s
										order by icono_id, icono_imagen");


		//echo($CADENA_BUSCAR);
		
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$iconos = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($iconos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $iconos;											
	}


	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)

	function clsOfertas($conexion,$buscar_origen,$buscar_destino,$buscar_producto,$buscar_fecha,$buscar_noches,$buscar_categoria,$buscar_alojamiento){
	
	
	
		$this->Conexion = $conexion;
		$this->Buscar_origen = $buscar_origen;
		//$this->Buscar_destino = $buscar_destino;
		$this->Buscar_producto = $buscar_producto;
		$this->Buscar_fecha = $buscar_fecha;
		$this->Buscar_noches = $buscar_noches;
		$this->Buscar_categoria = $buscar_categoria;
		$this->Buscar_alojamiento = $buscar_alojamiento;
		
		if($buscar_destino == 'TNORTE'){
			$this->Buscar_zona = 'TCINORTE';
			$this->Buscar_zona_svo = 'TFN';
			$this->Buscar_destino = 'TCISUR';
			$this->Cadena_buscar_zona_svo = " and ae.OPCION in (select ae2.opcion from hit_producto_cuadros_aereos ae2 
			where ae2.CLAVE_CUADRO = c.CLAVE and (ae2.ORIGEN = 'TFN' or ae2.DESTINO = 'TFN')) ";
			$this->Cadena_buscar_zona_alojamiento = " and a.ZONA_PRODUCTO = 'TCINORTE' ";
		}elseif($buscar_destino == 'TSUR'){
			$this->Buscar_zona = 'TCISUR';
			$this->Buscar_zona_svo = 'TFS';
			$this->Buscar_destino = 'TCISUR';
			$this->Cadena_buscar_zona_svo = " and ae.OPCION in (select ae2.opcion from hit_producto_cuadros_aereos ae2 
			where ae2.CLAVE_CUADRO = c.CLAVE and (ae2.ORIGEN = 'TFS' or ae2.DESTINO = 'TFS')) ";
			$this->Cadena_buscar_zona_alojamiento = " and a.ZONA_PRODUCTO = 'TCISUR' ";
		}else{
			$this->Buscar_zona = 'SINZONA';
			$this->Buscar_destino = $buscar_destino;
			$this->Cadena_buscar_zona_svo = "";
			$this->Cadena_buscar_zona_alojamiento = "";
		}
		
		
	}
}

?>