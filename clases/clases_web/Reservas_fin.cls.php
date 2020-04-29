<?php

	header('Content-type: text/html; charset=utf-8');

class clsReservas_fin{


	function Cargar_aereos(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla Ñ
		$localizador = $this ->localizador;

		//echo($localizador)
		$resultado =$conexion->query("select 
												DATE_FORMAT(pa.FECHA, '%d-%m-%Y') AS v_fecha, 
												case 
													WHEN pa.FECHA = p.FECHA_SALIDA then 'Ida'
													WHEN pa.FECHA <> p.FECHA_SALIDA then 'Vuelta'
												end v_ida_vuelta,
												pa.ORIGEN v_origen, pa.DESTINO v_destino, 
												tr.NOMBRE v_cia, pa.CIA v_codigo_cia,
												pa.VUELO v_vuelo, 
												pa.CLASE v_clase, 
												time_format(pa.HORA_SALIDA, '%H:%i') AS v_salida,
												time_format(pa.HORA_LLEGADA, '%H:%i') AS v_llegada,
												pp.PLAZAS v_pax,
												case pa.SITUACION
												 when 'OK' then 'Confirmado'
												 when 'ND' then 'Pendiente'
												 when 'OR' then 'On Request'
												 else 'Pendiente'
												 end v_disponibilidad


										from
											hit_reservas_aereos_trayectos pa,
											hit_reservas_aereos pp,
											hit_reservas p,
											hit_transportes tr
										where
											pa.LOCALIZADOR = pp.LOCALIZADOR
											and pa.ORDEN = pp.ORDEN
											and pa.LOCALIZADOR = p.LOCALIZADOR
											and pa.CIA = tr.CIA
											and pa.LOCALIZADOR = '".$localizador."' ORDER BY pa.FECHA, pa.HORA_SALIDA");


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$aereos = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($aereos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $aereos;											
	}


	function Cargar_nombre_hotel(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		//echo($localizador)
		$resultado =$conexion->query("select distinct concat(case when pal.INTERFAZ_NOMBRE_ALOJAMIENTO is null then t.NOMBRE
											else '' 
											end,' ',case 
											when pal.INTERFAZ_NOMBRE_ALOJAMIENTO is null then a.NOMBRE
											else pal.INTERFAZ_NOMBRE_ALOJAMIENTO 
											end) h_nombre, pr.comunidad h_comunidad
												from hit_reservas_alojamientos pal,
														hit_alojamientos a,
														hit_tipos_alojamiento t,
														hit_provincias pr
											where
												pal.ALOJAMIENTO = a.ID
												and a.TIPO = t.CODIGO
												and a.PROVINCIA = pr.CODIGO
												and pal.LOCALIZADOR = '".$localizador."'");


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$nombre_hotel = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($nombre_hotel,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $nombre_hotel;											
	}

	function Cargar_periodo_hotel(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		//echo($localizador)
		$resultado =$conexion->query("select distinct concat('Entrada: ',DATE_FORMAT(pal.FECHA_ENTRADA, '%d-%m-%Y'),',   
										Salida: ',DATE_FORMAT(DATE_ADD(pal.FECHA_ENTRADA,INTERVAL pal.NOCHES DAY), '%d-%m-%Y')) h_periodo
											from hit_reservas_alojamientos pal
											where pal.LOCALIZADOR = '".$localizador."'");


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$periodo_hotel = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($periodo_hotel,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $periodo_hotel;											
	}

	function Cargar_hoteles(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		//echo($localizador)
		$resultado =$conexion->query("select
										concat(
										case hcar.TIPO_ALOJAMIENTO
											when '1' then 'habitacion'
											else talo.NOMBRE
										end, ' ',pal.ORDEN
										,' ',
										case 
											when pal.INTERFAZ_CODIGO is null then hcar.NOMBRE
											else pal.INTERFAZ_CARACTERISTICA
											end
										) h_caracteristica,
										 concat(pal.USO,' pasajeros') h_pax,
										 concat(
										 '(',
										 concat(pal.ADULTOS,' adultos'),
										 case
										 when pal.NINOS1 > 0 then concat(' + ',pal.NINOS1, ' niños')
										 else ''
										 end,
										 case
										 when pal.BEBES > 0 then concat(' + ',pal.BEBES, ' bebes')
										 else ''
										 end,
										 ')') h_desglose_pax,	 
										 'en regimen de ' h_texto_regimen,
										 case pal.REGIMEN
										 when 'SA' then 'Solo alojamiento'
										 when 'AD' then 'Alojamiento y desayuno'
										 when 'MP' then 'Media Pension'
										 when 'PC' then 'Pension completa'
										 when 'TI' then 'Todo incluido'	 
										 end h_regimen,
										 pal.REGIMEN h_regimen_siglas,
										 case pal.SITUACION
										when 'OK' then 'Confirmado'
										else 'Pendiente'
										end h_estado,
										interfaz_politica_cancelacion_texto h_politica_cancelacion,
										interfaz_observaciones_texto h_observaciones,
										interfaz_localizador h_interfaz_localizador,
										interfaz_codigo h_interfaz_codigo
									from
										hit_reservas_alojamientos pal,
										hit_habitaciones_car hcar,
										hit_tipos_alojamiento talo
									where
										pal.CARACTERISTICA = hcar.CODIGO
										and hcar.TIPO_ALOJAMIENTO = talo.CODIGO
										and pal.LOCALIZADOR = '".$localizador."' order by h_caracteristica");


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$hoteles = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($hoteles,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $hoteles;											
	}

	function Cargar_servicios(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		//echo($localizador)
		$resultado =$conexion->query("select s.nombre s_nombre, 
									 DATE_FORMAT(r.FECHA, '%d-%m-%Y') AS s_fecha,
									r.PAX s_pax,
									r.veces s_veces,
									case r.SITUACION
									 when 'OK' then 'Confirmado'
									 else 'Pendiente'
									 end s_estado
							from hit_reservas_servicios r, hit_servicios s
							where
								r.ID_PROVEEDOR = s.ID_PROVEEDOR
								and r.CODIGO = s.CODIGO
								and r.LOCALIZADOR = '".$localizador."'
							ORDER BY r.FECHA");


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$servicios = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($servicios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $servicios;											
	}


	function Cargar_desglose($tipo){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		$localizadores = $this->localizadores;
		$referencia = $this->referencia;

		if($tipo == 'R'){
			$cadena_tipo = "and r.REFERENCIA = '".$referencia."'";
		}else{
			$cadena_tipo = "and r.LOCALIZADOR = '".$localizador."'";
		}

		//echo($localizador)
		if($localizadores > 1){
			$resultado =$conexion->query("select concat(cond.LOCALIZADOR, ' - ',cond.detalle) p_detalle, cond.PRECIO_PAX p_precio_pax, cond.PAX p_pax, cond.PRECIO_TOTAL p_total
										from
											hit_reservas_condiciones cond,
											hit_reservas r
										where
											cond.LOCALIZADOR = r.LOCALIZADOR
											".$cadena_tipo."
										order by cond.LOCALIZADOR, cond.habitacion");
		}else{
			$resultado =$conexion->query("select detalle p_detalle, cond.PRECIO_PAX p_precio_pax, cond.PAX p_pax, cond.PRECIO_TOTAL p_total
										from
											hit_reservas_condiciones cond
										where
											cond.LOCALIZADOR = '".$localizador."'
										order by habitacion");
		}

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$desglose = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($desglose,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $desglose;											
	}

	
	function Cargar_desglose_no_comisionables($tipo){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		$localizadores = $this->localizadores;
		$referencia = $this->referencia;

		if($tipo == 'R'){
			$cadena_tipo = "and r.REFERENCIA = '".$referencia."'";
		}else{
			$cadena_tipo = "and r.LOCALIZADOR = '".$localizador."'";
		}

		//echo($localizador)

		if($localizadores > 1){
				$resultado =$conexion->query("SELECT concat(s.LOCALIZADOR, ' - ', sv.nombre) p_detalle, s.pax p_pax, s.pvp_pax p_precio_pax, s.pvp_total_servicio p_total
										from hit_reservas_servicios s, hit_servicios sv, hit_reservas r
										where
											s.ID_SERVICIO = sv.ID
											and s.LOCALIZADOR = r.LOCALIZADOR
											".$cadena_tipo."
											and s.tipo_pvp = 'N'
											
										union all

										select concat(a.LOCALIZADOR, ' - ', 'Tasas de Aeropuerto') p_detalle, a.PLAZAS p_pax, sum(t.TASAS_PAX) p_precio_pax, sum(t.TASAS_PVP_TOTAL_TRAYECTO) p_total
										from hit_reservas_aereos a, hit_reservas_aereos_trayectos t, hit_reservas r
										where
											a.LOCALIZADOR = t.LOCALIZADOR
											and a.LOCALIZADOR = r.LOCALIZADOR
											and a.ORDEN = t.ORDEN
											".$cadena_tipo."
										group by  a.LOCALIZADOR, a.ORDEN");
		}else{
				$resultado =$conexion->query("SELECT sv.nombre p_detalle, s.pax p_pax, s.pvp_pax p_precio_pax, s.pvp_total_servicio p_total
										from hit_reservas_servicios s, hit_servicios sv
										where
											s.ID_SERVICIO = sv.ID
											and s.localizador = '".$localizador."'
											and s.tipo_pvp = 'N'
											
										union all

										select 'Tasas de Aeropuerto' p_detalle, a.PLAZAS p_pax, sum(t.TASAS_PAX) p_precio_pax, sum(t.TASAS_PVP_TOTAL_TRAYECTO) p_total
										from hit_reservas_aereos a, hit_reservas_aereos_trayectos t
										where
											a.LOCALIZADOR = t.LOCALIZADOR
											and a.ORDEN = t.ORDEN
											and a.LOCALIZADOR = '".$localizador."'
										group by  a.LOCALIZADOR, a.ORDEN");
		}		



		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}

		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$desglose = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($desglose,$fila);
		}

		//Si hay modificadcion manual de precios no comisionables lo añadimos
		
		if($localizadores > 1){
			$resultado_nocom =$conexion->query("select concat(r.LOCALIZADOR, ' - ', r.MODIFICAR_DETALLE_NO_COMISIONABLE) p_detalle,  r.PAX p_pax, 
											round((r.MODIFICAR_NO_COMISIONABLE - sum(p_total))/r.PAX,2) p_precio_pax,  
											round(r.MODIFICAR_NO_COMISIONABLE - sum(p_total),2) p_total
											from 
											(
											SELECT concat(s.LOCALIZADOR, ' - ', sv.nombre) p_detalle, s.pax p_pax, s.pvp_pax p_precio_pax, s.pvp_total_servicio p_total
																					from hit_reservas_servicios s, hit_servicios sv, hit_reservas r
																					where
																						s.ID_SERVICIO = sv.ID
																						and  s.localizador = r.localizador
																						".$cadena_tipo."
																						and s.tipo_pvp = 'N'
																						
											union all

											select concat(a.LOCALIZADOR, ' - ', 'Tasas de Aeropuerto') p_detalle, a.PLAZAS p_pax, sum(t.TASAS_PAX) p_precio_pax, sum(t.TASAS_PVP_TOTAL_TRAYECTO) p_total
																					from hit_reservas_aereos a, hit_reservas_aereos_trayectos t, hit_reservas r
																					where
																						a.LOCALIZADOR = t.LOCALIZADOR
																						and a.ORDEN = t.ORDEN
																						and a.localizador = r.localizador
																						".$cadena_tipo."
																					group by  a.LOCALIZADOR, a.ORDEN		
											) todos_no_comis, hit_reservas r
											where
												r.MODIFICAR = 'S'
												AND r.MODIFICAR_NO_COMISIONABLE is not null and r.MODIFICAR_NO_COMISIONABLE <> 0
												".$cadena_tipo);
		}else{
			$resultado_nocom =$conexion->query("select r.MODIFICAR_DETALLE_NO_COMISIONABLE p_detalle,  r.PAX p_pax, 
											round((r.MODIFICAR_NO_COMISIONABLE - sum(p_total))/r.PAX,2) p_precio_pax,  
											round(r.MODIFICAR_NO_COMISIONABLE - sum(p_total),2) p_total
											from 
											(
											SELECT sv.nombre p_detalle, s.pax p_pax, s.pvp_pax p_precio_pax, s.pvp_total_servicio p_total
																					from hit_reservas_servicios s, hit_servicios sv
																					where
																						s.ID_SERVICIO = sv.ID
																						and s.localizador = '".$localizador."'
																						and s.tipo_pvp = 'N'
																						
											union all

											select 'Tasas de Aeropuerto' p_detalle, a.PLAZAS p_pax, sum(t.TASAS_PAX) p_precio_pax, sum(t.TASAS_PVP_TOTAL_TRAYECTO) p_total
																					from hit_reservas_aereos a, hit_reservas_aereos_trayectos t
																					where
																						a.LOCALIZADOR = t.LOCALIZADOR
																						and a.ORDEN = t.ORDEN
																						and a.LOCALIZADOR = '".$localizador."'
																					group by  a.LOCALIZADOR, a.ORDEN		
											) todos_no_comis, hit_reservas r
											where
												r.MODIFICAR = 'S'
												AND r.MODIFICAR_NO_COMISIONABLE is not null and r.MODIFICAR_NO_COMISIONABLE <> 0
												and r.LOCALIZADOR	= '".$localizador."'");	
		}	
		
		for ($i = 0; $i < $resultado_nocom->num_rows; $i++) {
			$resultado_nocom->data_seek($i);
			$fila = $resultado_nocom->fetch_assoc();
			array_push($desglose,$fila);
		}		
		
		
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$resultado_nocom->close();

		return $desglose;											
	}	
	

	function Cargar_pvp($tipo){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		$localizadores = $this->localizadores;
		$referencia = $this->referencia;

		if($tipo == 'R'){
			$cadena_tipo = "ps.REFERENCIA = '".$referencia."'";
		}else{
			$cadena_tipo = "ps.LOCALIZADOR = '".$localizador."'";
		}

		//echo($localizador)
		$resultado =$conexion->query("select sum(ps.PVP_COMISIONABLE) pvp_comis, sum(ps.PVP_NO_COMISIONABLE) pvp_tasas, 
											 sum(ps.PVP_COMISIONABLE + ps.PVP_NO_COMISIONABLE) pvp_total
											from
												hit_reservas ps
											where
												".$cadena_tipo);


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$pvp = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($pvp,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $pvp;											
	}


	function Cargar_datos_agencia($localizador){

		$conexion = $this ->Conexion;


		//echo($localizador)
		$resultado =$conexion->query("select m.nombre a_nombre, o.direccion a_direccion, o.codigo_postal a_c_postal, 
												 o.localidad a_localidad, o.telefono a_telefono, o.MAIL a_email,
												 r.agente a_agente, r.referencia_agencia a_referencia_agencia, r.observaciones a_observaciones
										from hit_reservas r, hit_oficinas o, hit_minoristas m
										where
											r.MINORISTA = o.ID
											and r.OFICINA = o.OFICINA
											and o.ID = m.ID
											and r.LOCALIZADOR = '".$localizador."'");





		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$datos_agencia = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($datos_agencia,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $datos_agencia;											
	}


	function Cargar_pasajeros(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		//echo($localizador)
		$resultado =$conexion->query("select 	pax.NUMERO pax_numero,
												 case pax.SEXO 
													when 'H' then 'Mr.'
													when 'M' then 'Ms.'
												 end  pax_titulo,
												 pax.APELLIDO pax_apellidos,
												 pax.NOMBRE pax_nombre,
												 case pax.TIPO
													when 'A' then 'Adulto'
													when 'N' then 'Niño'
													when 'b' then 'Bebe'
												 end pax_tipo,
												 pax.EDAD pax_edad,
												 pax.TIPO pax_tipo_cod
												 
										from 
											hit_reservas_pasajeros pax
										where
											pax.LOCALIZADOR = '".$localizador."' order by pax.NUMERO");


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$pasajeros = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($pasajeros,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $pasajeros;											
	}

	function Cargar_datos_hoteles(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		//echo($localizador)
		$control_es_interfaz =$conexion->query("SELECT count(*) es_interfaz
								FROM hit_reservas_alojamientos  
								WHERE LOCALIZADOR = '".$localizador."' and INTERFAZ_CODIGO is not null");
		$ocontrol_es_interfaz = $control_es_interfaz->fetch_assoc();
		$es_interfaz = $ocontrol_es_interfaz['es_interfaz'];

		//echo($localizador)
		if($es_interfaz == 0){
			$resultado =$conexion->query("select distinct
								concat(alo.DIRECCION,'  ',alo.LOCALIDAD,'-',alo.CODIGO_POSTAL,' ',ciu.NOMBRE) dh_direccion,
								alo.TELEFONO dh_telefono,
								alo.URL dh_url,
								de.TELEFONO_CONTACTO dh_telefono_contacto
							from
								hit_reservas_alojamientos pal,
								hit_alojamientos alo,
								hit_ciudades ciu,
								hit_zonas zo,
								hit_destinos de
							where
								pal.ALOJAMIENTO= alo.ID
								and alo.CIUDAD = ciu.CODIGO
								and ciu.ZONA = zo.CODIGO
								and zo.DESTINO = de.CODIGO
											and pal.LOCALIZADOR = '".$localizador."' order by pal.FECHA_ENTRADA");
		}else{
			$resultado =$conexion->query("select distinct
									concat(inter.DIRECCION,'  ',inter.POBLACION,'-',inter.CODIGO_POSTAL,' ',inter.PROVINCIA_NOMBRE,' ',inter.PAIS_NOMBRE) dh_direccion,
									'' dh_telefono,
									inter.WEB dh_url,
									'' dh_telefono_contacto
								from
									hit_reservas_alojamientos pal,
									hit_interfaces_codigos_hoteles inter
								where
									pal.INTERFAZ_CODIGO_ALOJAMIENTO= inter.CODIGO
									and pal.LOCALIZADOR = '".$localizador."' order by pal.FECHA_ENTRA");



		}


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$hoteles = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($hoteles,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $hoteles;											
	}
	

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)

	function clsReservas_fin($conexion,$localizador){
		$this->Conexion = $conexion;
		$this->localizador = $localizador;

		//OBTENEMOS LA REFERENCIA DEL LOCALIZADOR
		$datos_referencia =$conexion->query("select referencia from hit_reservas where localizador = '".$localizador."'");
		$odatos_referencia = $datos_referencia->fetch_assoc();
		$referencia = $odatos_referencia['referencia'];
		$this->referencia = $referencia;

		//OBTENEMOS CANTIDAD DE LOCALIZADORES PARA LA REFERENCIA
		$datos_referencia2 =$conexion->query("select count(*) localizadores from hit_reservas where referencia = '".$referencia."'");
		$odatos_referencia2 = $datos_referencia2->fetch_assoc();
		$localizadores = $odatos_referencia2['localizadores'];
		$this->localizadores = $localizadores;

	}
}

?>