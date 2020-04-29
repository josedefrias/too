<?php

require 'clases_web/Reservas_fin.cls.php';

class clsReservas_consultas{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var	$buscar_nombre;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_reservas_desde = $this->Buscar_reservas_desde;
		$buscar_reservas_hasta = $this->Buscar_reservas_hasta;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_agencia = $this->Buscar_agencia;
		$orden = $this->Orden;
		$buscar_origen = $this->Buscar_origen;
		$buscar_grupo = $this->Buscar_grupo;


		//Montamos los parametros de busqueda
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_reservas_desde != null and $buscar_reservas_hasta != null){
			$periodo_salid = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$orig = " AND rt.origen = '".$buscar_origen."'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_reserva between '".date("Y-m-d",strtotime($buscar_reservas_desde))."' and '".date("Y-m-d",strtotime($buscar_reservas_hasta))."'";
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}			
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_salida_desde != null and $buscar_salida_hasta != null){
				$CADENA_BUSCAR .= $periodo_salid;	
			}	
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$orig = " AND rt.origen = '".$buscar_origen."'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}	
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	

		if($orden == 'LOC'){
			$CADENA_ORDEN = " ORDER BY r.localizador";
		}elseif($orden == 'SAL_LOC'){
			$CADENA_ORDEN = " ORDER BY r.fecha_salida, r.localizador";			
		}elseif($orden == 'RES_LOC'){
			$CADENA_ORDEN = " ORDER BY r.fecha_reserva, r.localizador";			
		}else{
			$CADENA_ORDEN = " ORDER BY r.localizador";			
		}
		//echo($CADENA_BUSCAR.'-'.$CADENA_ORDEN);
		$resultado =$conexion->query("select r.LOCALIZADOR localizador, r.SITUACION situacion, 
												 DATE_FORMAT(r.FECHA_RESERVA, '%d-%m-%Y') AS fecha_reserva,
												 DATE_FORMAT(r.FECHA_SALIDA, '%d-%m-%Y') AS fecha_salida,
												 rt.ORIGEN origen,
												 rt.DESTINO destino,
												 concat(rt.CIA,'/',rt.VUELO,' ',time_format(rt.HORA_SALIDA, '%H:%i'),'-',time_format(rt.HORA_LLEGADA, '%H:%i')) vuelo,
												 concat(r.PAX,' (',r.ADULTOS,'+',r.NINOS,'+',r.BEBES,')') pax_reserva,
												 p.APELLIDO apellido, p.NOMBRE nombre, 
												 a.NOMBRE hotel, 
												 DATE_FORMAT(ra.FECHA_ENTRADA, '%d-%m-%Y') AS fecha_entrada,
												 ra.NOCHES noches,  ac.NOMBRE habitacion, count(ra.HABITACION) cantidad, ra.SITUACION situacion_hotel,
												 concat(sum(ra.PAX),' (',sum(ra.ADULTOS),'+',sum(ra.NINOS1),'+',sum(ra.BEBES),')') pax_hotel,
												 g.nombre grupo_gestion,
												 m.NOMBRE agencia, o.LOCALIDAD localidad, o.DIRECCION direccion, o.TELEFONO telefono, o.MAIL mail, 
												 o.USUARIO_WEB usuario_web, o.PASSWORD_WEB password_web
										from
											hit_reservas r left join hit_reservas_pasajeros p on r.LOCALIZADOR = p.LOCALIZADOR and p.NUMERO = 1
																left join hit_reservas_alojamientos ra on r.LOCALIZADOR = ra.LOCALIZADOR
																			 left join hit_alojamientos a on ra.ALOJAMIENTO = a.ID
																			 left join hit_habitaciones_car ac on ra.CARACTERISTICA = ac.CODIGO
																left join hit_reservas_aereos_trayectos rt on r.LOCALIZADOR = rt.LOCALIZADOR and r.FECHA_SALIDA = rt.FECHA,
											hit_oficinas o, 
											hit_minoristas m,
											hit_grupos_gestion g
										where
											r.MINORISTA = o.ID
											and r.OFICINA = o.OFICINA
											and o.ID = m.ID
											and m.grupo_gestion = g.id
											and r.SITUACION <> 'A' ".$CADENA_BUSCAR." group by r.LOCALIZADOR, r.SITUACION, r.FECHA_RESERVA, r.FECHA_SALIDA, a.NOMBRE, ra.FECHA_ENTRADA, ra.NOCHES, ac.NOMBRE, ra.SITUACION, ac.NOMBRE,
												 p.APELLIDO, p.NOMBRE
										".$CADENA_ORDEN);
		//echo($CADENA_BUSCAR.$CADENA_ORDEN);
		
		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_CONSULTAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$reservas_consultas = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['localizador'] == ''){
				break;
			}
			array_push($reservas_consultas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $reservas_consultas;											
	}

	
	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_reservas_desde = $this->Buscar_reservas_desde;
		$buscar_reservas_hasta = $this->Buscar_reservas_hasta;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_agencia = $this->Buscar_agencia;
		$orden = $this->Orden;
		$buscar_origen = $this->Buscar_origen;
		$buscar_grupo = $this->Buscar_grupo;

		//ESPECIFICO: Montamos los parametros de busqueda
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_reservas_desde != null and $buscar_reservas_hasta != null){
			$periodo_salid = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$orig = " AND rt.origen = '".$buscar_origen."'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_reserva between '".date("Y-m-d",strtotime($buscar_reservas_desde))."' and '".date("Y-m-d",strtotime($buscar_reservas_hasta))."'";
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_salida_desde != null and $buscar_salida_hasta != null){
				$CADENA_BUSCAR .= $periodo_salid;	
			}	
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$orig = " AND rt.origen = '".$buscar_origen."'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	

		if($orden == 'LOC'){
			$CADENA_ORDEN = " ORDER BY r.localizador";
		}elseif($orden == 'SAL_LOC'){
			$CADENA_ORDEN = " ORDER BY r.fecha_salida, r.localizador";			
		}elseif($orden == 'RES_LOC'){
			$CADENA_ORDEN = " ORDER BY r.fecha_reserva, r.localizador";			
		}else{
			$CADENA_ORDEN = " ORDER BY r.localizador";			
		}

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select *
										from
											hit_reservas r left join hit_reservas_pasajeros p on r.LOCALIZADOR = p.LOCALIZADOR and p.NUMERO = 1
																left join hit_reservas_alojamientos ra on r.LOCALIZADOR = ra.LOCALIZADOR
																			 left join hit_alojamientos a on ra.ALOJAMIENTO = a.ID
																			 left join hit_habitaciones_car ac on ra.CARACTERISTICA = ac.CODIGO
																left join hit_reservas_aereos_trayectos rt on r.LOCALIZADOR = rt.LOCALIZADOR and r.FECHA_SALIDA = rt.FECHA,
											hit_oficinas o, 
											hit_minoristas m
										where
											r.MINORISTA = o.ID
											and r.OFICINA = o.OFICINA
											and o.ID = m.ID
											and r.SITUACION <> 'A' ".$CADENA_BUSCAR." group by r.LOCALIZADOR, r.SITUACION, r.FECHA_RESERVA, r.FECHA_SALIDA, a.NOMBRE, ra.FECHA_ENTRADA, ra.NOCHES, ac.NOMBRE, ra.SITUACION, ac.NOMBRE, p.APELLIDO, p.NOMBRE ");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_CONSULTAS' AND USUARIO = '".$Usuario."'");
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
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_reservas');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_CONSULTAS' AND USUARIO = '".$Usuario."'");
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

	
	function Cargar_totales(){

		$conexion = $this ->Conexion;

		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_reservas_desde = $this->Buscar_reservas_desde;
		$buscar_reservas_hasta = $this->Buscar_reservas_hasta;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_agencia = $this->Buscar_agencia;
		$orden = $this->Orden;
		$buscar_origen = $this->Buscar_origen;
		$buscar_grupo = $this->Buscar_grupo;

		//ESPECIFICO: Montamos los parametros de busqueda
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_reservas_desde != null and $buscar_reservas_hasta != null){
			$periodo_salid = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$orig = " AND rt.origen = '".$buscar_origen."'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_reserva between '".date("Y-m-d",strtotime($buscar_reservas_desde))."' and '".date("Y-m-d",strtotime($buscar_reservas_hasta))."'";
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_salida_desde != null and $buscar_salida_hasta != null){
				$CADENA_BUSCAR .= $periodo_salid;	
			}	
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$orig = " AND rt.origen = '".$buscar_origen."'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	

		if($orden == 'LOC'){
			$CADENA_ORDEN = " ORDER BY r.localizador";
		}elseif($orden == 'SAL_LOC'){
			$CADENA_ORDEN = " ORDER BY r.fecha_salida, r.localizador";			
		}elseif($orden == 'RES_LOC'){
			$CADENA_ORDEN = " ORDER BY r.fecha_reserva, r.localizador";			
		}else{
			$CADENA_ORDEN = " ORDER BY r.localizador";			
		}
		
		/*$resultado =$conexion->query("select   
										 ifnull(concat(sum(r.PAX),' (',sum(r.ADULTOS),'+',sum(r.NINOS),'+',sum(r.BEBES),')'),0) total_pax,
										 ifnull(sum(case 
											when c.PRODUCTO in ('VAC','OVA') then r.PAX
											end),0) total_paquetes,
										ifnull(sum(case 
											when c.PRODUCTO in ('SVO','OSV') then r.PAX
											end),0) total_solo_vuelo,
									   ifnull(sum(case 
											when c.PRODUCTO not in ('VAC','OVA','SVO','OSV') then r.PAX
											end),0) total_otros

																		from
											hit_reservas r left join hit_reservas_pasajeros p on r.LOCALIZADOR = p.LOCALIZADOR and p.NUMERO = 1
																left join hit_reservas_alojamientos ra on r.LOCALIZADOR = ra.LOCALIZADOR
																			 left join hit_alojamientos a on ra.ALOJAMIENTO = a.ID
																			 left join hit_habitaciones_car ac on ra.CARACTERISTICA = ac.CODIGO
																left join hit_reservas_aereos_trayectos rt on r.LOCALIZADOR = rt.LOCALIZADOR and r.FECHA_SALIDA = rt.FECHA,
											hit_oficinas o, 
											hit_minoristas m,
											hit_producto_cuadros c
										where
											r.MINORISTA = o.ID
											and r.OFICINA = o.OFICINA
											and o.ID = m.ID
											and r.FOLLETO = c.FOLLETO
											and r.CUADRO = c.CUADRO
											and r.SITUACION <> 'A' ".$CADENA_BUSCAR);*/
											
											
		$resultado =$conexion->query("select
										ifnull(concat(sum(reservas.pax),' (',sum(reservas.adultos),'+',sum(reservas.ninos),'+',sum(reservas.bebes),')'),0) total_pax,
																				 ifnull(sum(case 
																					when reservas.producto in ('VAC','OVA') then reservas.PAX
																					end),0) total_paquetes,
																				ifnull(sum(case 
																					when reservas.producto in ('SVO','OSV') then reservas.PAX
																					end),0) total_solo_vuelo,
																			   ifnull(sum(case 
																					when reservas.producto not in ('VAC','OVA','SVO','OSV') then reservas.PAX
																					end),0) total_otros


										from(
										select r.LOCALIZADOR localizador, r.SITUACION situacion, 
																						 DATE_FORMAT(r.FECHA_RESERVA, '%d-%m-%Y') AS fecha_reserva,
																						 DATE_FORMAT(r.FECHA_SALIDA, '%d-%m-%Y') AS fecha_salida,
																						 rt.ORIGEN origen,
																						 rt.DESTINO destino,
																						 concat(rt.CIA,'/',rt.VUELO,' ',time_format(rt.HORA_SALIDA, '%H:%i'),'-',time_format(rt.HORA_LLEGADA, '%H:%i')) vuelo,
																						 
																						 r.pax pax, r.ADULTOS adultos,r.NINOS ninos, r.BEBES bebes,
																						 
																						 
																						 p.APELLIDO apellido, p.NOMBRE nombre, 
																						 a.NOMBRE hotel, 
																						 DATE_FORMAT(ra.FECHA_ENTRADA, '%d-%m-%Y') AS fecha_entrada,
																						 ra.NOCHES noches,  ac.NOMBRE habitacion, count(ra.HABITACION) cantidad, ra.SITUACION situacion_hotel,
																						 concat(sum(ra.PAX),' (',sum(ra.ADULTOS),'+',sum(ra.NINOS1),'+',sum(ra.BEBES),')') pax_hotel,
																						 m.NOMBRE agencia, o.LOCALIDAD localidad, o.DIRECCION direccion, o.TELEFONO telefono, o.MAIL mail, 
																						 o.USUARIO_WEB usuario_web, o.PASSWORD_WEB password_web, c.PRODUCTO producto
																				from
																					hit_reservas r left join hit_reservas_pasajeros p on r.LOCALIZADOR = p.LOCALIZADOR and p.NUMERO = 1
																										left join hit_reservas_alojamientos ra on r.LOCALIZADOR = ra.LOCALIZADOR
																													 left join hit_alojamientos a on ra.ALOJAMIENTO = a.ID
																													 left join hit_habitaciones_car ac on ra.CARACTERISTICA = ac.CODIGO
																										left join hit_reservas_aereos_trayectos rt on r.LOCALIZADOR = rt.LOCALIZADOR and r.FECHA_SALIDA = rt.FECHA,
																					hit_oficinas o, 
																					hit_minoristas m,
																					hit_producto_cuadros c
																				where
																					r.MINORISTA = o.ID
																					and r.OFICINA = o.OFICINA
																					and o.ID = m.ID
																					and r.FOLLETO = c.FOLLETO
																					and r.CUADRO = c.CUADRO
																					and r.SITUACION <> 'A' ".$CADENA_BUSCAR." group by r.LOCALIZADOR, r.SITUACION, r.FECHA_RESERVA, r.FECHA_SALIDA, a.NOMBRE, ra.FECHA_ENTRADA, ra.NOCHES, 
																						 p.APELLIDO, p.NOMBRE
																				ORDER BY r.fecha_reserva, r.localizador) reservas");											
											
								
		//echo($CADENA_BUSCAR.$CADENA_ORDEN);
		
		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_CONSULTAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$reservas_consultas = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['total_pax'] == ''){
				break;
			}
			array_push($reservas_consultas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $reservas_consultas;											
	}	

	function Cargar_Canceladas(){

		$conexion = $this ->Conexion;

		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_reservas_desde = $this->Buscar_reservas_desde;
		$buscar_reservas_hasta = $this->Buscar_reservas_hasta;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_agencia = $this->Buscar_agencia;
		$orden = $this->Orden;
		$buscar_origen = $this->Buscar_origen;
		$buscar_grupo = $this->Buscar_grupo;

		//ESPECIFICO: Montamos los parametros de busqueda
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_reservas_desde != null and $buscar_reservas_hasta != null){
			$periodo_salid = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$orig = " AND r.ciudad_salida= '".$buscar_origen."'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.anulacion_fecha between '".date("Y-m-d",strtotime($buscar_reservas_desde))."' and '".date("Y-m-d",strtotime($buscar_reservas_hasta))."'";
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_salida_desde != null and $buscar_salida_hasta != null){
				$CADENA_BUSCAR .= $periodo_salid;	
			}	
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$orig = " AND r.ciudad_salida = '".$buscar_origen."'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	

		$resultado =$conexion->query("select   
										 ifnull(concat(sum(r.PAX),' (',sum(r.ADULTOS),'+',sum(r.NINOS),'+',sum(r.BEBES),')'),0) pax_cancelados
																		from
																			hit_reservas r,
																			hit_oficinas o, 
																			hit_minoristas m,
																			hit_producto_cuadros c
																		where
																			r.MINORISTA = o.ID
																			and r.OFICINA = o.OFICINA
																			and o.ID = m.ID
																			and r.FOLLETO = c.FOLLETO
																			and r.CUADRO = c.CUADRO
																			and r.SITUACION = 'A' ".$CADENA_BUSCAR);
		//echo($CADENA_BUSCAR.$CADENA_ORDEN);

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_CONSULTAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$reservas_consultas = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['pax_cancelados'] == ''){
				break;
			}
			array_push($reservas_consultas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $reservas_consultas;											
	}


	function Modificar($localizador, $estado){

		$usuario = $this->Usuario;
	
		$conexion = $this ->Conexion;
		$query = "UPDATE hit_reservas SET ";
		$query .= " PRECOBRO = '".$estado."'";
		$query .= ", FECHA_PAGO = CURRENT_TIMESTAMP()";
		$query .= ", OPERADOR_PREPAGO = '".$usuario."'";
		$query .= " WHERE LOCALIZADOR = '".$localizador."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


	function Enviar_mail_reserva($localizador){

		$usuario = $this->Usuario;
		$conexion = $this ->Conexion;

		$respuesta = '';
		
		//OBTENEMOS INFORMACION DE LA SITUACION DE COBRO PARA NO MANDAR MAIL DE RESERVAS YA COBRADAS
		$datos_precobro =$conexion->query("select precobro precobro, pvp_total pvp_total from hit_reservas where localizador = '".$localizador."'");
		$odatos_precobro = $datos_precobro->fetch_assoc();
		$precobro = $odatos_precobro['precobro'];
		$pvp_total = $odatos_precobro['pvp_total'];

		
		if($precobro == 'N'){
				
				////////////////////////////////////////////////////////////////////////////////////////////////				
				//ENVIAR MAIL INFORMANDO A LA AGENCIA DEl PAGO
				////////////////////////////////////////////////////////////////////////////////////////////////

				//OBTENEMOS NOMBRE Y PRODUCTO DEL VIAJE
				$datos_nombre_viaje =$conexion->query("select nombre 
														from hit_producto_cuadros c, hit_reservas r 
														where c.FOLLETO = r.FOLLETO and c.CUADRO = r.CUADRO and r.LOCALIZADOR = '".$localizador."'");
				$onombre_viaje = $datos_nombre_viaje->fetch_assoc();
				$nombre_viaje = $onombre_viaje['nombre'];

				//OBTENEMOS SALIDA, REGRESO, ESTADO Y DATOS AGENCIA
				$datos_salida_viaje =$conexion->query("select DATE_FORMAT(fecha_salida, '%d-%m-%Y') AS fecha_salida, DATE_FORMAT(fecha_regreso, '%d-%m-%Y') AS fecha_regreso, minorista, oficina
				from hit_reservas where localizador = '".$localizador."'");
				$odatos_salida_viaje = $datos_salida_viaje->fetch_assoc();
				$datos_salida = $odatos_salida_viaje['fecha_salida'];
				$datos_regreso = $odatos_salida_viaje['fecha_regreso'];
				$reserva_minorista = $odatos_salida_viaje['minorista'];
				$reserva_oficina = $odatos_salida_viaje['oficina'];
				

				$oReservas_fin = new clsReservas_fin($conexion, $localizador);
				$sdatos_pasajeros = $oReservas_fin->Cargar_pasajeros();
				
				///AQUI MONTAMOS EL HTML PARA EL CONTENIDO DEL CORREO ELECTRONICO

					$mensaje_html = "
					<html lang='es'>
					<head>	
						<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
					</head>

					<body>

					<img src='imagenes/Logo_Mail.jpg' align='center' height='60' width='300'>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:25px 15px 15px;font-weight:normal;text-align:justify;clear:both;display:block;'>Les informamos que deben realizar el pago de <strong>".$pvp_total."€</strong>. correspondiente a la reserva con localizador: <strong>".$localizador."</strong> en el día de hoy y enviarnos comprobante de pago a través de nuestra web. También pueden realizar el pago con tarjeta.</h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:25px 15px 15px;font-weight:normal;text-align:justify;clear:both;display:block;'>Para enviarnos el comprobante de pago a través de nuestra web, deben acceder con su clave y contraseña de usuario, ir a la opcion de menú <strong>Mis Reservas</strong>, seleccionar el localizador en el combo <strong>Seleccione Reserva</strong> y la acción <strong>Enviar Justificante de Transferencia</strong>. Pulsar el boton Actuar y adjuntar el pdf del justificante.</h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:25px 15px 15px;font-weight:normal;text-align:justify;clear:both;display:block;'>Para realizar el pago con tarjeta pueden hacerlo desde la misma página seleccionando la acción <strong>Pago con Tarjeta</strong>. Pulsar el boton Actuar y Elegir entre pago con tarjeta o pago a través de Iupal. Si selecciona el pago habitual con tarjeta, durante la operación, le será enviado un código secreto al móvil que tenga su entidad bancaria asignado a la tarjeta. Dicho código le será solicitado para finalizar el pago. De este modo se garantiza la seguridad en sus operaciones de pago.</h1>					
					
					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Viaje: <strong>".$nombre_viaje."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Salida: <strong>".$datos_salida."</strong>&nbsp;&nbsp;&nbsp;Regreso: <strong>".$datos_regreso."</strong></h1>";

					$mensaje_html .= "<h1 style='font-size:17px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Pasajeros</h1>

					<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	
						for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

							$mensaje_html .= "<p  style='font-size:13px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
							".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

							if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
								$mensaje_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
							}
							$mensaje_html .= "</p>";
							
						}
					$mensaje_html .= "</DIV>";					
					
					$mensaje_html .= "<h1 style='font-size:1.5em;line-height:120%;color:#6f7073;margin:40px 20px 10px;font-weight:normal;text-align:left;clear:both;	display:block;'><strong>Gracias por confiar en Hi Travel.</strong></h1>";					
					
					$mensaje_html .= "</body></html>";
			
				
				////FIN MENSAJE CONTENIDO///////////////////////////////////////////////////////////////////////
				////////////////////////////////////////////////////////////////////////////////////////////////				
				
				
				//aqui buscamos el mail de la agencia obteniendolo de la reserva. comprobamos si se factura a la central o a la oficina e intentamos pasarlo al mail de contabilidad. Sino al normal.
				/*$datos_agencia =$conexion->query("select o.MAIL_CONTABILIDAD mail_oficina_contabilidad, 
														o.MAIL mail_oficina,
														 m.MAIL_CONTABILIDAD mail_central_contabilidad, 
														 m.MAIL mail_central, 
														 facturacion 
												from hit_oficinas o, hit_minoristas m where o.ID = m.ID and o.ID = '".$reserva_minorista."' and o.OFICINA = '".$reserva_oficina."'");
				$datos_agenc = $datos_agencia->fetch_assoc();
				$facturacion_modo = $datos_agenc['facturacion'];
				if($facturacion_modo == 'C'){
					if($datos_agenc['mail_central_contabilidad'] != ''){
						$direccion_correo = $datos_agenc['mail_central_contabilidad'];
					}else{
						$direccion_correo = $datos_agenc['mail_central'];						
					}
				}elseif($facturacion_modo == 'O'){
					if($datos_agenc['mail_oficina_contabilidad'] != ''){
						$direccion_correo = $datos_agenc['mail_oficina_contabilidad'];
					}else{
						$direccion_correo = $datos_agenc['mail_oficina'];	
					}
				}*/				
				
				// Para pruebas usamos un mail interno
				$direccion_correo = "jfrias@hitravel.es";
				
				
				$nombre_destinatario = "Agencia de Viajes";
				$asunto = "HI TRAVEL - INFORMACION DE PAGO DE RESERVA: ".$localizador;
				$envio = enviar_mail_aviso_cobros($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario);
				//echo($envio);
				//---------------------------------------
				//---------------------------------------
				$respuesta = 'OK';
		}

		return $respuesta;											
	}	


	function Enviar_mail_grupo(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_estado = $this->Buscar_estado;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_agencia = $this->Buscar_agencia;
		$buscar_grupo = $this->Buscar_grupo;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$estad = " AND r.precobro = '".$buscar_estado."'";
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $estad;	
			}
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	
		
		$resultado =$conexion->query("select r.localizador localizador, precobro precobro
										from hit_reservas r,
											  hit_minoristas m,
											  hit_oficinas o
										where 
												r.MINORISTA = m.ID
												and r.MINORISTA = o.ID
												and r.OFICINA = o.OFICINA
												and r.pvp_total > 0
												and r.precobro <> 'C' ".$CADENA_BUSCAR." order by r.FECHA_SALIDA,m.NOMBRE,r.localizador");
		//echo($CADENA_BUSCAR);
		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Recorremos el resultado facturando cada localizador
				                        //------

		$envios = 0;
		
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			if($fila['precobro'] == 'N'){
				$this->Enviar_mail_reserva($fila['localizador']);
				$envios++;
			}
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			/*if($fila['localizador'] == ''){
				break;
			}*/
			//array_push($facturacion,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		
		return $envios;
											
	}


	
	
	

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsReservas_consultas($conexion, $filadesde, $usuario, $buscar_salida_desde, $buscar_salida_hasta, $buscar_reservas_desde, $buscar_reservas_hasta, $buscar_localizador, $buscar_agencia, $orden, $buscar_origen, $buscar_grupo){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_salida_desde = $buscar_salida_desde;
		$this->Buscar_salida_hasta = $buscar_salida_hasta;
		$this->Buscar_reservas_desde = $buscar_reservas_desde;
		$this->Buscar_reservas_hasta = $buscar_reservas_hasta;
		$this->Buscar_localizador = $buscar_localizador;
		$this->Buscar_agencia = $buscar_agencia;
		$this->Orden = $orden;
		$this->Buscar_origen = $buscar_origen;
		$this->Buscar_grupo = $buscar_grupo;
	}
}

?>