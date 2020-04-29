<?php

class clsPlanning{

	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var	$buscar_nombre;
	//--------------------------------------------------

	
	
	function Cargar_calendario_cabecera(){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		$buscar_folleto = $this->Buscar_folleto;
		$buscar_destino = $this->Buscar_destino;
		
		
		
		//Averiguamos el producto de cuadro
		/*$resultado_producto =$conexion->query("SELECT producto FROM hit_producto_cuadros where clave = '".$clave."'");
		$producto_cuadro = $resultado_producto->fetch_assoc();
		$producto = $producto_cuadro['producto'];*/


			$resultado_calendario =$conexion->query("SELECT distinct s.FECHA fecha,
														concat(concat(DATE_FORMAT(s.FECHA, '%d '),`GENERAL_TRADUCE_MES`(DATE_FORMAT(s.FECHA, '%m')))) AS fecha_formato
														FROM 
															hit_reservas r,
															hit_producto_cuadros_salidas s,
															hit_producto_cuadros c
														where
															r.FOLLETO = s.FOLLETO
															and r.CUADRO = s.CUADRO
															and s.CLAVE_CUADRO = c.CLAVE
															and r.SITUACION <> 'A'
															and s.ESTADO = 'A'
															and r.FOLLETO = '".$buscar_folleto."'
															and c.DESTINO = '".$buscar_destino."'
														order by s.FECHA");


		if ($resultado_calendario == FALSE){
			echo('Error en la consulta: '.$clave);
			$resultado_calendario->close();
			$conexion->close();
			exit;
		}

		$calendario = array();
		for ($num_fila = 0; $num_fila < $resultado_calendario->num_rows; $num_fila++) {
			$resultado_calendario->data_seek($num_fila);
			$fila = $resultado_calendario->fetch_assoc();
			array_push($calendario,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado_calendario->close();


		return $calendario;											
	}	


	function Cargar_trayectos(){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		$buscar_folleto = $this->Buscar_folleto;
		$buscar_destino = $this->Buscar_destino;
		
		//Averiguamos el producto de cuadro
		/*$resultado_producto =$conexion->query("SELECT producto FROM hit_producto_cuadros where clave = '".$clave."'");
		$producto_cuadro = $resultado_producto->fetch_assoc();
		$producto = $producto_cuadro['producto'];*/

			$resultado_trayectos =$conexion->query("SELECT distinct concat(ae.CIA,' - ',atr.ORIGEN,' - ',atr.DESTINO) trayecto
														FROM 
															/*hit_reservas r LEFT OUTER JOIN hit_producto_cuadros_salidas sa on sa.FECHA = r.FECHA_SALIDA,*/
															hit_reservas r,
															hit_reservas_aereos ae,
															hit_reservas_aereos_trayectos atr,
															hit_producto_cuadros c
														where
															r.LOCALIZADOR = ae.LOCALIZADOR
															and ae.LOCALIZADOR = atr.LOCALIZADOR
															and ae.ORDEN = atr.ORDEN
															and r.FOLLETO = c.FOLLETO
															and r.CUADRO = c.CUADRO
															and r.SITUACION <> 'A'
															and r.FOLLETO = '".$buscar_folleto."'
															and c.DESTINO = '".$buscar_destino."'
														order by ae.cia, atr.ACUERDO, atr.NUMERO, atr.origen, atr.destino");


		if ($resultado_trayectos == FALSE){
			echo('Error en la consulta: '.$clave);
			$resultado_trayectos->close();
			$conexion->close();
			exit;
		}

		$trayectos = array();
		for ($num_fila = 0; $num_fila < $resultado_trayectos->num_rows; $num_fila++) {
			$resultado_trayectos->data_seek($num_fila);
			$fila = $resultado_trayectos->fetch_assoc();
			array_push($trayectos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado_trayectos->close();


		return $trayectos;											
	}	


	function Cargar_trayectos_ocupacion(){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		$buscar_folleto = $this->Buscar_folleto;
		$buscar_destino = $this->Buscar_destino;

		$resultado_trayectos_ocupacion =$conexion->query("SELECT atr.FECHA fecha, concat(ae.CIA,' - ',atr.ORIGEN,' - ',atr.DESTINO) trayecto, 
																	sum(ae.PLAZAS) PAX , round((sum(ae.PLAZAS) * 100) /cup.CUPO) OCUP
																	
																FROM 
																	/*hit_reservas r LEFT OUTER JOIN hit_producto_cuadros_salidas sa on sa.FECHA = r.FECHA_SALIDA,*/
																	hit_reservas r,
																	hit_reservas_aereos ae,
																	hit_reservas_aereos_trayectos atr,
																	hit_producto_cuadros c,
																	hit_transportes_cupos cup
																where
																	r.LOCALIZADOR = ae.LOCALIZADOR
																	and ae.LOCALIZADOR = atr.LOCALIZADOR
																	and ae.ORDEN = atr.ORDEN
																	and r.FOLLETO = c.FOLLETO
																	and r.CUADRO = c.CUADRO
																	and atr.FECHA = cup.FECHA
																	and atr.ORIGEN = cup.ORIGEN
																	and atr.DESTINO = cup.DESTINO
																	and atr.CIA = cup.CIA
																	and atr.ACUERDO = cup.ACUERDO
																	and atr.SUBACUERDO = cup.SUBACUERDO
																	and r.SITUACION <> 'A'
																	and atr.SITUACION = 'OK'
																	and r.FOLLETO = '".$buscar_folleto."'
																	and c.DESTINO = '".$buscar_destino."'
																group by fecha, trayecto
																order by atr.fecha, ae.cia, atr.ACUERDO, atr.NUMERO, atr.origen, atr.destino");


		if ($resultado_trayectos_ocupacion == FALSE){
			echo('Error en la consulta: '.$clave);
			$resultado_trayectos_ocupacion->close();
			$conexion->close();
			exit;
		}

		$trayectos_ocupacion = array();
		for ($num_fila = 0; $num_fila < $resultado_trayectos_ocupacion->num_rows; $num_fila++) {
			$resultado_trayectos_ocupacion->data_seek($num_fila);
			$fila = $resultado_trayectos_ocupacion->fetch_assoc();
			array_push($trayectos_ocupacion,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado_trayectos_ocupacion->close();


		return $trayectos_ocupacion;											
	}	


	function Cargar_trayectos_ocupacion_no_confirmados(){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		$buscar_folleto = $this->Buscar_folleto;
		$buscar_destino = $this->Buscar_destino;

		$resultado_trayectos_ocupacion =$conexion->query("SELECT atr.FECHA fecha, concat(ae.CIA,' - ',atr.ORIGEN,' - ',atr.DESTINO) trayecto, 
																	sum(ae.PLAZAS) PAX , round((sum(ae.PLAZAS) * 100) /cup.CUPO) OCUP
																	
																FROM 
																	/*hit_reservas r LEFT OUTER JOIN hit_producto_cuadros_salidas sa on sa.FECHA = r.FECHA_SALIDA,*/
																	hit_reservas r,
																	hit_reservas_aereos ae,
																	hit_reservas_aereos_trayectos atr,
																	hit_producto_cuadros c,
																	hit_transportes_cupos cup
																where
																	r.LOCALIZADOR = ae.LOCALIZADOR
																	and ae.LOCALIZADOR = atr.LOCALIZADOR
																	and ae.ORDEN = atr.ORDEN
																	and r.FOLLETO = c.FOLLETO
																	and r.CUADRO = c.CUADRO
																	and atr.FECHA = cup.FECHA
																	and atr.ORIGEN = cup.ORIGEN
																	and atr.DESTINO = cup.DESTINO
																	and atr.CIA = cup.CIA
																	and atr.ACUERDO = cup.ACUERDO
																	and atr.SUBACUERDO = cup.SUBACUERDO
																	and r.SITUACION <> 'A'
																	and atr.SITUACION <> 'OK'
																	and r.FOLLETO = '".$buscar_folleto."'
																	and c.DESTINO = '".$buscar_destino."'
																group by fecha, trayecto
																order by atr.fecha, ae.cia, atr.ACUERDO, atr.NUMERO, atr.origen, atr.destino");


		if ($resultado_trayectos_ocupacion == FALSE){
			echo('Error en la consulta: '.$clave);
			$resultado_trayectos_ocupacion->close();
			$conexion->close();
			exit;
		}

		$trayectos_ocupacion = array();
		for ($num_fila = 0; $num_fila < $resultado_trayectos_ocupacion->num_rows; $num_fila++) {
			$resultado_trayectos_ocupacion->data_seek($num_fila);
			$fila = $resultado_trayectos_ocupacion->fetch_assoc();
			array_push($trayectos_ocupacion,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado_trayectos_ocupacion->close();


		return $trayectos_ocupacion;											
	}		
	
	function Cargar_alojamientos(){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		$buscar_folleto = $this->Buscar_folleto;
		$buscar_destino = $this->Buscar_destino;

			$resultado_alojamientos =$conexion->query("SELECT distinct concat(a.NOMBRE,' - ',ra.NOCHES, ' noches') alojamiento
															FROM 
																hit_reservas r,
																hit_reservas_alojamientos ra,
																hit_alojamientos a,
																hit_producto_cuadros c
															where
																r.LOCALIZADOR = ra.LOCALIZADOR
																and ra.ALOJAMIENTO = a.ID
																and r.FOLLETO = c.FOLLETO
																and r.CUADRO = c.CUADRO
																and r.SITUACION <> 'A'
																and r.FOLLETO = '".$buscar_folleto."'
																and c.DESTINO = '".$buscar_destino."'
															order by a.nombre, ra.noches");

		if ($resultado_alojamientos == FALSE){
			echo('Error en la consulta: '.$clave);
			$resultado_alojamientos->close();
			$conexion->close();
			exit;
		}

		$alojamientos = array();
		for ($num_fila = 0; $num_fila < $resultado_alojamientos->num_rows; $num_fila++) {
			$resultado_alojamientos->data_seek($num_fila);
			$fila = $resultado_alojamientos->fetch_assoc();
			array_push($alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado_alojamientos->close();


		return $alojamientos;											
	}		
	
	function Cargar_ocupacion_alojamientos_confirmados(){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		$buscar_folleto = $this->Buscar_folleto;
		$buscar_destino = $this->Buscar_destino;

			$resultado_alojamientos =$conexion->query("SELECT ra.FECHA_ENTRADA fecha, concat(a.NOMBRE,' - ',ra.NOCHES, ' noches') alojamiento, sum(ra.CANTIDAD_HABITACIONES) hab, sum(ra.PAX) pax
														FROM 
															hit_reservas r,
															hit_reservas_alojamientos ra,
															hit_alojamientos a,
															hit_producto_cuadros c
														where
															r.LOCALIZADOR = ra.LOCALIZADOR
															and ra.ALOJAMIENTO = a.ID
															and r.FOLLETO = c.FOLLETO
															and r.CUADRO = c.CUADRO
															and r.SITUACION <> 'A'
															and ra.SITUACION = 'OK'
															and r.FOLLETO = '".$buscar_folleto."'
															and c.DESTINO = '".$buscar_destino."'
														group by fecha, a.nombre, ra.noches
														order by fecha, a.nombre, ra.noches");

		if ($resultado_alojamientos == FALSE){
			echo('Error en la consulta: '.$clave);
			$resultado_alojamientos->close();
			$conexion->close();
			exit;
		}

		$alojamientos = array();
		for ($num_fila = 0; $num_fila < $resultado_alojamientos->num_rows; $num_fila++) {
			$resultado_alojamientos->data_seek($num_fila);
			$fila = $resultado_alojamientos->fetch_assoc();
			array_push($alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado_alojamientos->close();

		return $alojamientos;											
	}		


	function Cargar_ocupacion_alojamientos_no_confirmados(){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		$buscar_folleto = $this->Buscar_folleto;
		$buscar_destino = $this->Buscar_destino;

			$resultado_alojamientos =$conexion->query("SELECT ra.FECHA_ENTRADA fecha, concat(a.NOMBRE,' - ',ra.NOCHES, ' noches') alojamiento, sum(ra.CANTIDAD_HABITACIONES) hab, sum(ra.PAX) pax
														FROM 
															hit_reservas r,
															hit_reservas_alojamientos ra,
															hit_alojamientos a,
															hit_producto_cuadros c
														where
															r.LOCALIZADOR = ra.LOCALIZADOR
															and ra.ALOJAMIENTO = a.ID
															and r.FOLLETO = c.FOLLETO
															and r.CUADRO = c.CUADRO
															and r.SITUACION <> 'A'
															and ra.SITUACION <> 'OK'
															and r.FOLLETO = '".$buscar_folleto."'
															and c.DESTINO = '".$buscar_destino."'
														group by fecha, a.nombre, ra.noches
														order by fecha, a.nombre, ra.noches");

		if ($resultado_alojamientos == FALSE){
			echo('Error en la consulta: '.$clave);
			$resultado_alojamientos->close();
			$conexion->close();
			exit;
		}

		$alojamientos = array();
		for ($num_fila = 0; $num_fila < $resultado_alojamientos->num_rows; $num_fila++) {
			$resultado_alojamientos->data_seek($num_fila);
			$fila = $resultado_alojamientos->fetch_assoc();
			array_push($alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado_alojamientos->close();

		return $alojamientos;											
	}	
	
	
	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsPlanning($conexion, $usuario, $buscar_folleto, $buscar_destino){
		$this->Conexion = $conexion;
		$this->Usuario = $usuario;
		$this->Buscar_folleto = $buscar_folleto;
		$this->Buscar_destino = $buscar_destino;
	}
}

?>