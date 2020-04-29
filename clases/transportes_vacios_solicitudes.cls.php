<?php

class clsTransportes_vacios_solicitudes{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	//var $buscar_alojamiento;
	//var	$buscar_tipo;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		$buscar_cia = $this ->Buscar_cia;
		$buscar_tipo = $this ->Buscar_tipo;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_fecha_hasta = $this ->Buscar_fecha_hasta;
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_plazas = $this ->Buscar_plazas;
		$buscar_precio = $this ->Buscar_precio;
		$buscar_estado = $this ->Buscar_estado;
		$buscar_dia_semana = $this ->Buscar_dia_semana;
		$buscar_id_vacio = $this ->Buscar_id_vacio;

		$buscar_localizador = $this ->Buscar_localizador;
		$buscar_estado_solicitud = $this ->Buscar_estado_solicitud;



		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and sol.ID = '".$buscar_localizador."'";
		}elseif($buscar_id_vacio != null){
			$CADENA_BUSCAR = " and v.id = '".$buscar_id_vacio."'";
		}else{


			/*$fech2 = " and v.fecha between curdate()  and DATE_ADD(curdate() ,INTERVAL 7 DAY)";*/

			//$fech2 = " and v.fecha >= curdate()";

			if($buscar_fecha_hasta != null){
				$fech = " and v.fecha between '".date("Y-m-d",strtotime($buscar_fecha))."'  and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
				$fech2 = " and v.fecha between curdate()  and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
			}else{
				$fech = " and v.fecha = '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$fech2 = " and v.fecha >= curdate()";
			}


			if($buscar_cia != null){
				
				$tip = " and v.tipo = '".$buscar_tipo."'";
				//$fech = " and v.fecha between '".date("Y-m-d",strtotime($buscar_fecha))."'  and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";

				$orig = " and v.origen LIKE '%".$buscar_origen."%' ";
				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";

				$CADENA_BUSCAR = " and v.id_cia = '".$buscar_cia."'";

				if($buscar_tipo != null){
					$CADENA_BUSCAR .= $tip;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}else{
					$CADENA_BUSCAR .= $fech2;
				}
				if($buscar_origen != null){
					$CADENA_BUSCAR .= $orig;	
				}
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}

			}elseif($buscar_tipo != null){
				
				//$fech = " and v.fecha between '".date("Y-m-d",strtotime($buscar_fecha))."'  and DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL 7 DAY)";

				$orig = " and v.origen LIKE '%".$buscar_origen."%' ";
				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.tipo = '".$buscar_tipo."'";

				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}else{
					$CADENA_BUSCAR .= $fech2;
				}
				if($buscar_origen != null){
					$CADENA_BUSCAR .= $orig;	
				}
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_fecha != null){

				$orig = " and v.origen LIKE '%".$buscar_origen."%' ";
				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = $fech;

				if($buscar_origen != null){
					$CADENA_BUSCAR .= $orig;	
				}
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_origen != null){

				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.origen LIKE '%".$buscar_origen."%' ";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_destino != null){

				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.destino LIKE '%".$buscar_destino."%' ";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_plazas != null){

				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.plazas >= ".$buscar_plazas;
				$CADENA_BUSCAR .= $fech2;
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_precio != null){
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.precio <= ".$buscar_precio;
				$CADENA_BUSCAR .= $fech2;
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_estado != null){
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.estado = '".$buscar_estado."'";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_dia_semana != null){
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and f.DIA = '".$buscar_dia_semana."'";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_estado_solicitud != null){
				$CADENA_BUSCAR = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR .= $fech2;
			}else{
				$CADENA_BUSCAR = $fech2;			
			}
		}


		//echo($localizador)
		$inicializa_variable =$conexion->query("SET @numero_sol = 0;");

		$resultado =$conexion->query("select 
								id, 
								id_cia,
								cod_cia,
								nombre_cia,
								cod_origen,
								origen, 
								cod_destino,
								destino, 
								fecha, 
								dia_semana,
								tipo,
								plazas,
								hora,
								precio,
								valor_tv,
								valor_equipaje,
								valor_aire,
								valor_reclinables,
								valor_minusvalidos,
								valor_wc,
								estado,
								fecha_alta, 
								fecha_orden,
								solicitudes,

								@numero_sol := case when @vacio = id then @numero_sol + 1 else @numero_sol := 1 end as numero_orden_sol,
					       			@vacio := id as vacio,	

								vacio_fecha_orden,
								vacio_origen,
								vacio_destino,
								sol_fecha_orden,
								vacio_id,	
								sol_id_solicitud, 
								sol_tipo_cliente,
								sol_cliente, 
								sol_nombre,
								sol_telefono,
								sol_email,
								sol_estado_solicitud, 
								sol_fecha_alta_solicitud,
								sol_dia_alta,
								sol_fecha_envio_cia,
								sol_fecha_envio_agencia,
								sol_fecha_cierre

					from

					(
					SELECT   

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo tipo,
								v.plazas plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								concat(m.NOMBRE, ' - ', o.DIRECCION, ' - ', o.LOCALIDAD) sol_nombre,
								o.TELEFONO sol_telefono,
								o.MAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v,
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_oficinas o,
						  hit_minoristas m
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = o.CLAVE
						and o.ID = m.ID
						and sol.TIPO_CLIENTE = 'agencia'
					".$CADENA_BUSCAR."

					union

					SELECT  

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo,
								v.plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								 
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								tra.NOMBRE sol_nombre,
								tra.TELEFONO sol_telefono,
								tra.EMAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v, 
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_transportes tra
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = tra.ID
						and sol.TIPO_CLIENTE = 'cia'
					".$CADENA_BUSCAR."

					union

					SELECT   

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo,
								v.plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								u.NOMBRE sol_nombre,
								u.TELEFONO sol_telefono,
								u.EMAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v, 
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_usuarios u
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = u.ID
						and sol.TIPO_CLIENTE = 'interno'
					".$CADENA_BUSCAR."

					order by vacio_fecha_orden, vacio_origen, vacio_destino, vacio_id, sol_fecha_orden, sol_id_solicitud
					) solicitudes");



		/*echo("select 
								id, 
								id_cia,
								cod_cia,
								nombre_cia,
								cod_origen,
								origen, 
								cod_destino,
								destino, 
								fecha, 
								dia_semana,
								tipo,
								plazas,
								hora,
								precio,
								valor_tv,
								valor_equipaje,
								valor_aire,
								valor_reclinables,
								valor_minusvalidos,
								valor_wc,
								estado,
								fecha_alta, 
								fecha_orden,
								solicitudes,

								@numero_sol := case when @vacio = id then @numero_sol + 1 else @numero_sol := 1 end as numero_orden_sol,
					       			@vacio := id as vacio,	

								vacio_fecha_orden,
								vacio_origen,
								vacio_destino,
								sol_fecha_orden,
								vacio_id,	
								sol_id_solicitud, 
								sol_tipo_cliente,
								sol_cliente, 
								sol_nombre,
								sol_telefono,
								sol_email,
								sol_estado_solicitud, 
								sol_fecha_alta_solicitud,
								sol_dia_alta,
								sol_fecha_envio_cia,
								sol_fecha_envio_agencia,
								sol_fecha_cierre

					from

					(
					SELECT   

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo tipo,
								v.plazas plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								concat(m.NOMBRE, ' - ', o.DIRECCION, ' - ', o.LOCALIDAD) sol_nombre,
								o.TELEFONO sol_telefono,
								o.MAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v,
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_oficinas o,
						  hit_minoristas m
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = o.CLAVE
						and o.ID = m.ID
						and sol.TIPO_CLIENTE = 'agencia'
					".$CADENA_BUSCAR."

					union

					SELECT  

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo,
								v.plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								 
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								tra.NOMBRE sol_nombre,
								tra.TELEFONO sol_telefono,
								tra.EMAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v, 
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_transportes tra
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = tra.ID
						and sol.TIPO_CLIENTE = 'cia'
					".$CADENA_BUSCAR."

					union

					SELECT   

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo,
								v.plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								u.NOMBRE sol_nombre,
								u.TELEFONO sol_telefono,
								u.EMAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v, 
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_usuarios u
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = u.ID
						and sol.TIPO_CLIENTE = 'interno'
					".$CADENA_BUSCAR."

					order by vacio_fecha_orden, vacio_origen, vacio_destino, vacio_id, sol_fecha_orden, sol_id_solicitud
					) solicitudes");*/





		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_VACIOS_SOLICITUDES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$transportes_vacios_solicitudes = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['id'] == ''){
				break;
			}
			array_push($transportes_vacios_solicitudes,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $transportes_vacios_solicitudes;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_VACIOS_SOLICITUDES' AND USUARIO = '".$Usuario."'");
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

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_cia = $this ->Buscar_cia;
		$buscar_tipo = $this ->Buscar_tipo;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_fecha_hasta = $this ->Buscar_fecha_hasta;
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_plazas = $this ->Buscar_plazas;
		$buscar_precio = $this ->Buscar_precio;
		$buscar_estado = $this ->Buscar_estado;
		$buscar_dia_semana = $this ->Buscar_dia_semana;
		$buscar_id_vacio = $this ->Buscar_id_vacio;

		$buscar_localizador = $this ->Buscar_localizador;
		$buscar_estado_solicitud = $this ->Buscar_estado_solicitud;
		

		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and sol.ID = '".$buscar_localizador."'";
		}elseif($buscar_id_vacio != null){
			$CADENA_BUSCAR = " and v.id = '".$buscar_id_vacio."'";
		}else{


			/*$fech2 = " and v.fecha between curdate()  and DATE_ADD(curdate() ,INTERVAL 7 DAY)";*/

			//$fech2 = " and v.fecha >= curdate()";

			if($buscar_fecha_hasta != null){
				$fech = " and v.fecha between '".date("Y-m-d",strtotime($buscar_fecha))."'  and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
				$fech2 = " and v.fecha between curdate()  and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
			}else{
				$fech = " and v.fecha = '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$fech2 = " and v.fecha >= curdate()";
			}


			if($buscar_cia != null){
				
				$tip = " and v.tipo = '".$buscar_tipo."'";
				//$fech = " and v.fecha between '".date("Y-m-d",strtotime($buscar_fecha))."'  and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";

				$orig = " and v.origen LIKE '%".$buscar_origen."%' ";
				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";

				$CADENA_BUSCAR = " and v.id_cia = '".$buscar_cia."'";

				if($buscar_tipo != null){
					$CADENA_BUSCAR .= $tip;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}else{
					$CADENA_BUSCAR .= $fech2;
				}
				if($buscar_origen != null){
					$CADENA_BUSCAR .= $orig;	
				}
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}

			}elseif($buscar_tipo != null){
				
				//$fech = " and v.fecha between '".date("Y-m-d",strtotime($buscar_fecha))."'  and DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL 7 DAY)";

				$orig = " and v.origen LIKE '%".$buscar_origen."%' ";
				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.tipo = '".$buscar_tipo."'";

				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}else{
					$CADENA_BUSCAR .= $fech2;
				}
				if($buscar_origen != null){
					$CADENA_BUSCAR .= $orig;	
				}
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_fecha != null){

				$orig = " and v.origen LIKE '%".$buscar_origen."%' ";
				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = $fech;

				if($buscar_origen != null){
					$CADENA_BUSCAR .= $orig;	
				}
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_origen != null){

				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.origen LIKE '%".$buscar_origen."%' ";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_destino != null){

				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.destino LIKE '%".$buscar_destino."%' ";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_plazas != null){

				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.plazas >= ".$buscar_plazas;
				$CADENA_BUSCAR .= $fech2;
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_precio != null){
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.precio <= ".$buscar_precio;
				$CADENA_BUSCAR .= $fech2;
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_estado != null){
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.estado = '".$buscar_estado."'";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_dia_semana != null){
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and f.DIA = '".$buscar_dia_semana."'";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_estado_solicitud != null){
				$CADENA_BUSCAR = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR .= $fech2;
			}else{
				$CADENA_BUSCAR = $fech2;			
			}
		}					

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select 
								*

					from

					(
					SELECT   

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo tipo,
								v.plazas plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								concat(m.NOMBRE, ' - ', o.DIRECCION, ' - ', o.LOCALIDAD) sol_nombre,
								o.TELEFONO sol_telefono,
								o.MAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v,
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_oficinas o,
						  hit_minoristas m
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = o.CLAVE
						and o.ID = m.ID
						and sol.TIPO_CLIENTE = 'agencia'
					".$CADENA_BUSCAR."

					union

					SELECT  

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo,
								v.plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								 
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								tra.NOMBRE sol_nombre,
								tra.TELEFONO sol_telefono,
								tra.EMAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v, 
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_transportes tra
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = tra.ID
						and sol.TIPO_CLIENTE = 'cia'
					".$CADENA_BUSCAR."

					union

					SELECT   

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo,
								v.plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								u.NOMBRE sol_nombre,
								u.TELEFONO sol_telefono,
								u.EMAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v, 
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_usuarios u
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = u.ID
						and sol.TIPO_CLIENTE = 'interno'
					".$CADENA_BUSCAR."

					order by vacio_fecha_orden, vacio_origen, vacio_destino, vacio_id, sol_fecha_orden, sol_id_solicitud
					) solicitudes");


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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_VACIOS_SOLICITUDES' AND USUARIO = '".$Usuario."'");
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
		$buscar_cia = $this ->Buscar_cia;
		$buscar_tipo = $this ->Buscar_tipo;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_fecha_hasta = $this ->Buscar_fecha_hasta;
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_plazas = $this ->Buscar_plazas;
		$buscar_precio = $this ->Buscar_precio;
		$buscar_estado = $this ->Buscar_estado;
		$buscar_dia_semana = $this ->Buscar_dia_semana;
		$buscar_id_vacio = $this ->Buscar_id_vacio;

		$buscar_localizador = $this ->Buscar_localizador;
		$buscar_estado_solicitud = $this ->Buscar_estado_solicitud;
		

		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and sol.ID = '".$buscar_localizador."'";
		}elseif($buscar_id_vacio != null){
			$CADENA_BUSCAR = " and v.id = '".$buscar_id_vacio."'";
		}else{


			/*$fech2 = " and v.fecha between curdate()  and DATE_ADD(curdate() ,INTERVAL 7 DAY)";*/

			//$fech2 = " and v.fecha >= curdate()";

			if($buscar_fecha_hasta != null){
				$fech = " and v.fecha between '".date("Y-m-d",strtotime($buscar_fecha))."'  and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
				$fech2 = " and v.fecha between curdate()  and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
			}else{
				$fech = " and v.fecha = '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$fech2 = " and v.fecha >= curdate()";
			}


			if($buscar_cia != null){
				
				$tip = " and v.tipo = '".$buscar_tipo."'";
				//$fech = " and v.fecha between '".date("Y-m-d",strtotime($buscar_fecha))."'  and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";

				$orig = " and v.origen LIKE '%".$buscar_origen."%' ";
				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";

				$CADENA_BUSCAR = " and v.id_cia = '".$buscar_cia."'";

				if($buscar_tipo != null){
					$CADENA_BUSCAR .= $tip;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}else{
					$CADENA_BUSCAR .= $fech2;
				}
				if($buscar_origen != null){
					$CADENA_BUSCAR .= $orig;	
				}
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}

			}elseif($buscar_tipo != null){
				
				//$fech = " and v.fecha between '".date("Y-m-d",strtotime($buscar_fecha))."'  and DATE_ADD('".date("Y-m-d",strtotime($buscar_fecha))."',INTERVAL 7 DAY)";

				$orig = " and v.origen LIKE '%".$buscar_origen."%' ";
				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.tipo = '".$buscar_tipo."'";

				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}else{
					$CADENA_BUSCAR .= $fech2;
				}
				if($buscar_origen != null){
					$CADENA_BUSCAR .= $orig;	
				}
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_fecha != null){

				$orig = " and v.origen LIKE '%".$buscar_origen."%' ";
				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = $fech;

				if($buscar_origen != null){
					$CADENA_BUSCAR .= $orig;	
				}
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_origen != null){

				$desti = " and v.destino LIKE '%".$buscar_destino."%' ";
				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.origen LIKE '%".$buscar_origen."%' ";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_destino != null){
					$CADENA_BUSCAR .= $desti;	
				}
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_destino != null){

				$plaz = " and v.plazas >= ".$buscar_plazas;
				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.destino LIKE '%".$buscar_destino."%' ";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_plazas != null){
					$CADENA_BUSCAR .= $plaz;	
				}
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_plazas != null){

				$prec = " and v.precio <= ".$buscar_precio;
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.plazas >= ".$buscar_plazas;
				$CADENA_BUSCAR .= $fech2;
				if($buscar_precio != null){
					$CADENA_BUSCAR .= $prec;	
				}
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_precio != null){
				$estad = " and v.estado = '".$buscar_estado."'";
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.precio <= ".$buscar_precio;
				$CADENA_BUSCAR .= $fech2;
				if($buscar_estado != null){
					$CADENA_BUSCAR .= $estad;	
				}
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_estado != null){
				$diasem = " and f.DIA = '".$buscar_dia_semana."'";
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and v.estado = '".$buscar_estado."'";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_dia_semana != null){
					$CADENA_BUSCAR .= $diasem;	
				}
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_dia_semana != null){
				$estadsol = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR = " and f.DIA = '".$buscar_dia_semana."'";
				$CADENA_BUSCAR .= $fech2;
				if($buscar_estado_solicitud != null){
					$CADENA_BUSCAR .= $estadsol;	
				}
			}elseif($buscar_estado_solicitud != null){
				$CADENA_BUSCAR = " and sol.ESTADO = '".$buscar_estado_solicitud."'";
				$CADENA_BUSCAR .= $fech2;
			}else{
				$CADENA_BUSCAR = $fech2;			
			}
		}								
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select 
								*

					from

					(
					SELECT   

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo tipo,
								v.plazas plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								concat(m.NOMBRE, ' - ', o.DIRECCION, ' - ', o.LOCALIDAD) sol_nombre,
								o.TELEFONO sol_telefono,
								o.MAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v,
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_oficinas o,
						  hit_minoristas m
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = o.CLAVE
						and o.ID = m.ID
						and sol.TIPO_CLIENTE = 'agencia'
					".$CADENA_BUSCAR."

					union

					SELECT  

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo,
								v.plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								 
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								tra.NOMBRE sol_nombre,
								tra.TELEFONO sol_telefono,
								tra.EMAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v, 
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_transportes tra
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = tra.ID
						and sol.TIPO_CLIENTE = 'cia'
					".$CADENA_BUSCAR."

					union

					SELECT   

								v.id id, 
								v.id_cia id_cia,
								v.cod_cia cod_cia,
								t.NOMBRE nombre_cia,
								v.cod_origen cod_origen,
								v.origen origen, 
								v.cod_destino cod_destino,
								v.destino destino, 
								DATE_FORMAT(v.fecha, '%d-%m-%Y') fecha, 
								f.DIA dia_semana,
								v.tipo,
								v.plazas,
								time_format(v.hora, '%H:%i') AS hora,
								v.precio precio,
								v.tv valor_tv,
								v.equipaje valor_equipaje,
								v.aire valor_aire,
								v.reclinables valor_reclinables,
								v.minusvalidos valor_minusvalidos,
								v.wc valor_wc,
								v.estado,
								DATE_FORMAT(v.fecha_alta, '%d-%m-%Y') fecha_alta, 
								v.fecha fecha_orden,
								(select count(*) from hit_transportes_trayectos_vacios_solicitudes ts where ts.id_vacio = v.id) solicitudes,
								
								v.fecha vacio_fecha_orden,
								v.ORIGEN vacio_origen,
								v.DESTINO vacio_destino,
								sol.FECHA_ALTA sol_fecha_orden,
								sol.ID_VACIO vacio_id,	
								sol.ID sol_id_solicitud, 
								sol.TIPO_CLIENTE sol_tipo_cliente,
								sol.CLIENTE sol_cliente, 
								u.NOMBRE sol_nombre,
								u.TELEFONO sol_telefono,
								u.EMAIL sol_email,
								sol.ESTADO sol_estado_solicitud, 
								DATE_FORMAT(sol.FECHA_ALTA, '%d-%m-%Y') AS sol_fecha_alta_solicitud,
								f.DIA sol_dia_alta,
								DATE_FORMAT(sol.FECHA_ENVIO_CIA, '%d-%m-%Y') AS sol_fecha_envio_cia,
								DATE_FORMAT(sol.FECHA_ENVIO_AGENCIA, '%d-%m-%Y') AS sol_fecha_envio_agencia,
								DATE_FORMAT(sol.FECHA_CIERRE, '%d-%m-%Y') AS sol_fecha_cierre
					FROM hit_transportes_trayectos_vacios v, 
						  hit_transportes t,
						  hit_fechas f, 
						  hit_transportes_trayectos_vacios_solicitudes sol,
						  hit_usuarios u
						  
					where v.ID_CIA = t.ID
						and v.ID = sol.ID_VACIO
						and sol.FECHA_ALTA = f.FECHA
						and sol.CLIENTE = u.ID
						and sol.TIPO_CLIENTE = 'interno'
					".$CADENA_BUSCAR."

					order by vacio_fecha_orden, vacio_origen, vacio_destino, vacio_id, sol_fecha_orden, sol_id_solicitud
					) solicitudes");


		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_VACIOS_SOLICITUDES' AND USUARIO = '".$Usuario."'");
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

	function Modificar($localizador, $estado){

		$conexion = $this ->Conexion;


		$query = "UPDATE hit_transportes_trayectos_vacios_solicitudes SET ";
		$query .= " ESTADO = '".$estado."'";
		$query .= " WHERE  ID = ".$localizador;

		//echo($query );

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){

			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($id){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_transportes_trayectos_vacios_solicitudes WHERE ESTADO = 'PE' AND ID = ".$id;

		//ECHO($query);

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}



	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsTransportes_vacios_solicitudes($conexion, $filadesde, $usuario, $buscar_cia, $buscar_tipo, $buscar_fecha, $buscar_fecha_hasta, $buscar_origen, $buscar_destino, $buscar_plazas, $buscar_precio, $buscar_estado, $buscar_dia_semana, $buscar_id_vacio, $buscar_localizador, $buscar_estado_solicitud){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;

		$this->Buscar_cia = $buscar_cia;
		$this->Buscar_tipo = $buscar_tipo;
		$this->Buscar_fecha = $buscar_fecha;
		$this->Buscar_fecha_hasta = $buscar_fecha_hasta;
		$this->Buscar_origen = $buscar_origen;
		$this->Buscar_destino = $buscar_destino;
		$this->Buscar_plazas = $buscar_plazas;
		$this->Buscar_precio = $buscar_precio;
		$this->Buscar_dia_semana = $buscar_dia_semana;
		$this->Buscar_estado = $buscar_estado;
		$this->Buscar_id_vacio = $buscar_id_vacio;

		$this->Buscar_localizador = $buscar_localizador;
		$this->Buscar_estado_solicitud = $buscar_estado_solicitud;




	}
}

?>