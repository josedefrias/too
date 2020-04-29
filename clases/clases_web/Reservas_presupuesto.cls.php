
<?php

	header('Content-type: text/html; charset=utf-8');

class clsReservas_presupuesto{


	function Cargar_aereos(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantallaÑ
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
												time_format(pa.HORA_SALIDA, '%H:%i') AS v_salida,
												time_format(pa.HORA_LLEGADA, '%H:%i') AS v_llegada,
												pp.PLAZAS v_pax,
												case pa.SITUACION
												 when 'OK' then 'Confirmado'
												 else 'Pendiente'
												 end v_disponibilidad


										from
											hit_presupuestos_aereos_trayectos pa,
											hit_presupuestos_aereos pp,
											hit_presupuestos p,
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
		$resultado =$conexion->query("select distinct concat(t.NOMBRE,' ',a.NOMBRE) h_nombre
												from hit_presupuestos_alojamientos pal,
														hit_alojamientos a,
														hit_tipos_alojamiento t
											where
												pal.ALOJAMIENTO = a.ID
												and a.TIPO = t.CODIGO
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
		$resultado =$conexion->query("select distinct concat('Fecha entrada: ',DATE_FORMAT(pal.FECHA_ENTRADA, '%d-%m-%Y'),',   
										Fecha salida: ',DATE_FORMAT(DATE_ADD(pal.FECHA_ENTRADA,INTERVAL pal.NOCHES DAY), '%d-%m-%Y')) h_periodo
											from hit_presupuestos_alojamientos pal
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
											when '1' then 'Habitacion'
											else talo.NOMBRE
										end, ' ',pal.ORDEN
										,' ',
										hcar.NOMBRE
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
										 case pal.SITUACION
										when 'OK' then 'Confirmado'
										else 'Pendiente'
										end h_estado
									from
										hit_presupuestos_alojamientos pal,
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
												case r.SITUACION
												 when 'OK' then 'Confirmado'
												 else 'Pendiente'
												 end s_estado
										from hit_presupuestos_servicios r, hit_servicios s
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


	function Cargar_desglose(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		//echo($localizador)
		$resultado =$conexion->query("select detalle p_detalle, cond.PRECIO_PAX p_precio_pax, cond.PAX p_pax, cond.PRECIO_TOTAL p_total
										from
											hit_presupuestos_condiciones cond
										where
											cond.LOCALIZADOR = '".$localizador."'
										order by habitacion");


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


	function Cargar_pvp(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		//echo($localizador)
		$resultado =$conexion->query("select ps.PVP_COMISIONABLE pvp_comis, ps.PVP_NO_COMISIONABLE pvp_tasas, 
											 ps.PVP_COMISIONABLE + ps.PVP_NO_COMISIONABLE pvp_total,
											 ps.pvp_comision pvp_comision,
											 ps.pvp_importe_comision pvp_importe_comision,
											 ps.pvp_impuesto pvp_impuesto,
											 ps.pvp_impuesto_comision pvp_impuesto_comision,
											 ps.pvp_total pvp_total_factura
											from
												hit_presupuestos ps
											where
												ps.LOCALIZADOR = '".$localizador."'");


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

	function Cargar_servicios_opcionales(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		$datos =$conexion->query("select folleto, cuadro, fecha_salida, pax  from hit_presupuestos where localizador = '".$localizador."'");
		$presupuesto = $datos->fetch_assoc();
		$folleto = $presupuesto['folleto'];
		$cuadro = $presupuesto['cuadro'];
		$fecha = $presupuesto['fecha_salida'];
		$pax = $presupuesto['pax'];

		//echo($localizador)
		$resultado =$conexion->query("select sv.NOMBRE opcional_nombre, 
											DATE_FORMAT(DATE_ADD('".$fecha."',INTERVAL ps.DIA-1 DAY), '%d-%m-%Y') AS opcional_fecha, 
											psp.PRECIO opcional_pvp,
											case svp.tipo_unidad
													when 'P' then 'Personas'
													when 'R' then 'Reserva'
													when 'D' then 'Dias'
													else 'Personas'
											end opcional_tipo_unidad,
											case svp.tipo_unidad
													when 'P' then '".$pax."'
													when 'R' then '1'
													when 'D' then '1'
													else '".$pax."'
											end opcional_unidad,
											sv.id opcional_id
										from hit_producto_cuadros_servicios ps, 
											  hit_producto_cuadros_servicios_precios psp,
											  hit_servicios sv,
												hit_servicios_precios svp
										where
											ps.CLAVE = psp.CLAVE_SERVICIO
											and ps.ID_PROVEEDOR = sv.ID_PROVEEDOR
											and ps.CODIGO_SERVICIO = sv.CODIGO
											and sv.ID = svp.ID
											and '".$fecha."' between svp.FECHA_DESDE and svp.FECHA_HASTA
											and ps.FOLLETO = '".$folleto."'
											and ps.CUADRO = '".$cuadro."'
											and '".$fecha."' between psp.FECHA_DESDE and psp.FECHA_HASTA
											and ps.TIPO = 'O'
										order by ps.DIA, ps.NUMERO");

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$opcionales = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($opcionales,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $opcionales;											
	}

	function Cargar_cantidad_servicios_opcionales(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		$datos =$conexion->query("select folleto, cuadro, fecha_salida, pax  from hit_presupuestos where localizador = '".$localizador."'");
		$presupuesto = $datos->fetch_assoc();
		$folleto = $presupuesto['folleto'];
		$cuadro = $presupuesto['cuadro'];
		$fecha = $presupuesto['fecha_salida'];
		$pax = $presupuesto['pax'];

		//echo($localizador)
		$resultado =$conexion->query("select count(*) opcionales_cantidad
										from hit_producto_cuadros_servicios ps, 
											  hit_producto_cuadros_servicios_precios psp,
											  hit_servicios sv,
												hit_servicios_precios svp
										where
											ps.CLAVE = psp.CLAVE_SERVICIO
											and ps.ID_PROVEEDOR = sv.ID_PROVEEDOR
											and ps.CODIGO_SERVICIO = sv.CODIGO
											and sv.ID = svp.ID
											and '".$fecha."' between svp.FECHA_DESDE and svp.FECHA_HASTA
											and ps.FOLLETO = '".$folleto."'
											and ps.CUADRO = '".$cuadro."'
											and '".$fecha."' between psp.FECHA_DESDE and psp.FECHA_HASTA
											and ps.TIPO = 'O'");
		$cantidad = $resultado->fetch_assoc();
		$opcinales_cantidad = $cantidad['opcionales_cantidad'];

		return $opcinales_cantidad;											
	}

	function Insertar_servicios_opcinales($localizador, $fecha, $clave, $pax){

		$conexion = $this ->Conexion;


		$datos_pax =$conexion->query("SELECT ninos, bebes, novios  FROM hit_presupuestos WHERE LOCALIZADOR = '".$localizador."'");
		$odatos_pax = $datos_pax->fetch_assoc();

		$adultos = $pax - $odatos_pax['ninos'] - $odatos_pax['bebes'];
		$ninos = $odatos_pax['ninos'];
		$bebes = $odatos_pax['bebes'];
		$novios = $odatos_pax['novios'];


		$orden =$conexion->query("SELECT MAX(ORDEN) numero FROM hit_presupuestos_servicios c WHERE LOCALIZADOR = '".$localizador."' and FECHA = '".date("Y-m-d",strtotime($fecha))."'");
		$linea_orden = $orden->fetch_assoc();

		$nuevo_orden = $linea_orden['numero'] + 1;
		//$pax = $adultos + $ninos + $bebes;
		if($pax == null){
			$pax_nuevos = $adultos + $ninos + $bebes;
		}else{
			$pax_nuevos = $pax;
		}

		if($adultos >= $pax_nuevos){
			$adultos =  $pax_nuevos;
			$ninos = 0;
			$bebes = 0;
		}elseif($adultos + $ninos >= $pax_nuevos){
			$adultos =  $pax_nuevos - $ninos;
			$ninos = $ninos;
			$bebes = 0;
		}elseif($adultos + $ninos + $bebes >= $pax_nuevos){
			$adultos =  $pax_nuevos - $ninos - $bebes;
			$ninos = $ninos;
			$bebes = $bebes;
		}else{
			$adultos =  $pax_nuevos;
			$ninos = 0;
			$bebes = 0;
		}



		//ECHO($linea_cupo['FECHA']);
		$query = "INSERT INTO hit_presupuestos_servicios (LOCALIZADOR, FECHA, ORDEN, ID_SERVICIO, SITUACION, ID_PROVEEDOR, CODIGO, PAX, ADULTOS, NINOS, NOVIOS, JUBILADOS, VECES, DIVISA, CAMBIO, TIPO_PVP, TIPO) 
					SELECT '".$localizador."', '".date("Y-m-d",strtotime($fecha))."', '".$nuevo_orden."', '".$clave."',	
					CASE TIPO_CUPO
						WHEN 'F' THEN 'OK'
						WHEN 'O' THEN 'OR'
						ELSE 'OR'
					END,
					ID_PROVEEDOR,
					CODIGO,
					'".$pax_nuevos."',
					'".$adultos."',
					'".$ninos."',
					'".$novios."',
					0,
					1,
					DIVISA,
					1,
					TIPO_PVP,
					'O'
					FROM
					  hit_servicios
					where 
					  ID = '".$clave."'";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';

		}
		return $respuesta;											
	}


	function Eliminar_servicios_opcionales($localizador, $fecha, $clave){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_presupuestos_servicios WHERE LOCALIZADOR = '".$localizador."' and ID_SERVICIO = '".$clave."' AND FECHA = '".date("Y-m-d",strtotime($fecha))."'";


		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}




	function Cargar_datos_agencia($usuario){

		$conexion = $this ->Conexion;

		$es_interno =$conexion->query("SELECT count(*) EXISTE_USUARIO FROM hit_usuarios WHERE USUARIO = '".$usuario."'");
		if ($es_interno == FALSE){
			echo('Error en la consulta');
			//$datos->close();
			$conexion->close();
			exit;
		}

		$es_interno_usu	 = $es_interno->fetch_assoc();

		if($es_interno_usu['EXISTE_USUARIO'] == 0){

			//echo($localizador)
			$resultado =$conexion->query("select m.NOMBRE a_nombre, o.DIRECCION a_direccion, o.CODIGO_POSTAL a_c_postal, o.LOCALIDAD a_localidad, o.TELEFONO a_telefono, o.MAIL a_email
											from hit_oficinas o, hit_minoristas m
											where
												o.ID = m.ID
												AND o.USUARIO_WEB = '".$usuario."'");

		}else{

			$resultado =$conexion->query("select m.NOMBRE a_nombre, o.DIRECCION a_direccion, o.CODIGO_POSTAL a_c_postal, o.LOCALIDAD a_localidad, o.TELEFONO a_telefono, o.MAIL a_email
											from hit_oficinas o, hit_minoristas m
											where
												o.ID = m.ID
												AND o.USUARIO_WEB = 'Agenciabase@hitravel.es'");

		}



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
		$resultado =$conexion->query("select alo.ORDEN pax_habitacion,
												 pax.NUMERO pax_numero,
												 pax.SEXO pax_titulo,
												 pax.APELLIDO pax_apellidos,
												 pax.NOMBRE pax_nombre,
												 case pax.TIPO
													when 'A' then 'Adulto'
													when 'N' then 'Niño'
													when 'b' then 'Bebe'
												 end pax_tipo,
												 pax.EDAD pax_edad
												 
										from 
											hit_presupuestos_pasajeros pax,
											hit_presupuestos_alojamientos alo
										where
											pax.LOCALIZADOR = alo.LOCALIZADOR
											and pax.HABITACION = alo.ORDEN
											and pax.LOCALIZADOR = '".$localizador."' order by alo.ORDEN, pax.NUMERO");


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

	function Cargar_pasajeros_solo_vuelo(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		//echo($localizador)
		$resultado =$conexion->query("select 0 pax_habitacion,
												 pax.NUMERO pax_numero,
												 pax.SEXO pax_titulo,
												 pax.APELLIDO pax_apellidos,
												 pax.NOMBRE pax_nombre,
												 case pax.TIPO
													when 'A' then 'Adulto'
													when 'N' then 'Niño'
													when 'b' then 'Bebe'
												 end pax_tipo,
												 pax.EDAD pax_edad
												 
										from 
											hit_presupuestos_pasajeros pax
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


	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)

	function clsReservas_presupuesto($conexion,$localizador){
		$this->Conexion = $conexion;
		$this->localizador = $localizador;
	}
}

?>