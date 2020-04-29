<?php

class clsCuadros_aereos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var $buscar_cuadro;

//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LAS AEREOS---
//--------------------------------------------------------------------

	function Cargar_aereos($clave, $filadesde, $buscar_ciudad){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

	
		if($buscar_ciudad != null){
			$CADENA_BUSCAR = " AND a.CLAVE_CUADRO = '".$clave."' AND a.CIUDAD = '".$buscar_ciudad."'";
		}else{
			$CADENA_BUSCAR = " AND a.CLAVE_CUADRO = '".$clave."'"; 
  		}

  		/*echo($CADENA_BUSCAR);*/
		$resultado =$conexion->query("SELECT a.ciudad, a.opcion, a.numero, a.cia, a.acuerdo, a.subacuerdo, a.origen, a.destino, t.vuelo, time_format(t.hora_salida, '%H:%i') AS										hora_salida, time_format(t.hora_llegada, '%H:%i') AS hora_llegada, a.dia, a.tasas, a.tipo_trayecto
										FROM hit_producto_cuadros_aereos a, hit_transportes_trayectos t 
										WHERE a.CIA = t.CIA and a.ACUERDO = t.ACUERDO and a.SUBACUERDO = t.SUBACUERDO and a.ORIGEN = t.ORIGEN and a.DESTINO = t.DESTINO ".$CADENA_BUSCAR." ORDER BY a.ciudad, a.opcion, a.numero");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$folletos_aereos = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['ciudad'] == ''){
				break;
			}
			array_push($folletos_aereos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $folletos_aereos;											
	}


	function Cargar_lineas_nuevas_aereos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_aereos($clave, $filadesde, $buscar_ciudad){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_ciudad != null){
			$CADENA_BUSCAR = " AND a.CLAVE_CUADRO = '".$clave."' AND a.CIUDAD = '".$buscar_ciudad."'";
		}else{
			$CADENA_BUSCAR = " AND a.CLAVE_CUADRO = '".$clave."'"; 
  		}				

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_aereos a, hit_transportes_trayectos t 
										WHERE a.CIA = t.CIA and a.ACUERDO = t.ACUERDO and a.SUBACUERDO = t.SUBACUERDO and a.ORIGEN = t.ORIGEN and a.DESTINO = t.DESTINO ".$CADENA_BUSCAR." ORDER BY a.ciudad, a.opcion, a.numero");
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/
		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS' AND USUARIO = '".$Usuario."'");
			$Nfilas	 = $num_filas->fetch_assoc();																	  //------
			$cada = $Nfilas['LINEAS_MODIFICACION'] + 1;
			$combo_select = array();
			for ($num_fila = 1; $num_fila <= $numero_filas; $num_fila++) {
				
				if($num_fila == $cada){
					$combo_select[$num_fila] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $cada - 1);
					$cada = $cada + $Nfilas['LINEAS_MODIFICACION'];
				}
				if($num_fila == $numero_filas){
					$combo_select[$num_fila] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $numero_filas);
				}
			}
			$num_filas->close();
		}else{
			$combo_select[1] = array ( "inicio" => 1, "fin" => 0);
			$resultadoc->close();
		}
		return $combo_select;											
	}

	function Botones_selector_aereos($filadesde, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_producto_cuadros_aereos');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_aereos($clave_cuadro, $ciudad, $opcion, $numero, $cia, $acuerdo, $subacuerdo, $origen, $destino, $dia, $tasas, $tipo_trayecto, $ciudad_old, $opcion_old, $numero_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_producto_cuadros_aereos SET ";
		$query .= " CIUDAD = '".$ciudad."'";
		$query .= ", OPCION = '".$opcion."'";
		$query .= ", NUMERO = '".$numero."'";
		$query .= ", CIA = '".$cia."'";
		$query .= ", ACUERDO = '".$acuerdo."'";
		$query .= ", SUBACUERDO = '".$subacuerdo."'";
		$query .= ", ORIGEN = '".$origen."'";
		$query .= ", DESTINO = '".$destino."'";
		$query .= ", DIA = '".$dia."'";
		$query .= ", TASAS = '".$tasas."'";
		$query .= ", TIPO_TRAYECTO = '".$tipo_trayecto."'";
		$query .= " WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND CIUDAD = '".$ciudad_old."'";
		$query .= " AND OPCION= '".$opcion_old."'";
		$query .= " AND NUMERO = '".$numero_old."'";


		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
			//echo($query);
		}else{
			$respuesta = 'OK';
			//echo($query);
		}

		return $respuesta;											
	}

	function Borrar_aereos($clave_cuadro, $ciudad, $opcion, $numero){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_cuadros_aereos WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND CIUDAD = '".$ciudad."'";
		$query .= " AND OPCION = '".$opcion."'";
		$query .= " AND NUMERO = '".$numero."'";
		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_aereos($clave_cuadro, $folleto, $cuadro, $ciudad, $opcion, $numero, $cia, $acuerdo, $subacuerdo, $origen, $destino, $dia, $tasas, $tipo_trayecto){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_producto_cuadros_aereos (CLAVE_CUADRO, FOLLETO, CUADRO, CIUDAD, OPCION, NUMERO, CIA, ACUERDO, SUBACUERDO, ORIGEN, DESTINO, DIA, TASAS, TIPO_TRAYECTO) VALUES (";
		$query .= "'".$clave_cuadro."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$ciudad."',";
		$query .= "'".$opcion."',";
		$query .= "'".$numero."',";
		$query .= "'".$cia."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$subacuerdo."',";
		$query .= "'".$origen."',";
		$query .= "'".$destino."',";
		$query .= "'".$dia."',";
		$query .= "'".$tasas."',";
		$query .= "'".$tipo_trayecto."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}

//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INFERIOR CON LAS PRECIOS DE AEREOS------
//--------------------------------------------------------------------

	function Cargar_aereos_precios($clave, $filadesde, $buscar_aereo, $buscar_fecha){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

	
		if($buscar_aereo != null){
			$fech = " AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."' AND CLAVE_AEREO = '".$buscar_aereo."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
		}elseif($buscar_fecha != null){
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."'";    
  		}


		$resultado =$conexion->query("SELECT clave_aereo,clave_calendario, precio_1, clase_1, precio_2, clase_2, suplemento_3, clase_3, 
											 suplemento_4, clase_4, suplemento_5, clase_5
										FROM hit_producto_cuadros_aereos_precios 
										WHERE ".$CADENA_BUSCAR." ORDER BY opcion, numero, fecha_desde");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS_PRECIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$folletos_aereos_precios = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['clave_aereo'] == ''){
				break;
			}
			array_push($folletos_aereos_precios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $folletos_aereos_precios;
	}


	function Cargar_lineas_nuevas_aereos_precios(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_aereos_precios($clave, $filadesde, $buscar_aereo, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_aereo != null){
			$fech = " AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."' AND CLAVE_AEREO = '".$buscar_aereo."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
		}elseif($buscar_fecha != null){
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."'";    
  		}	

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_aereos_precios WHERE ".$CADENA_BUSCAR." ORDER BY opcion, numero, fecha_desde");
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/
		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS_PRECIOS' AND USUARIO = '".$Usuario."'");
			$Nfilas	 = $num_filas->fetch_assoc();																	  //------
			$cada = $Nfilas['LINEAS_MODIFICACION'] + 1;
			$combo_select = array();
			for ($num_fila = 1; $num_fila <= $numero_filas; $num_fila++) {
				
				if($num_fila == $cada){
					$combo_select[$num_fila] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $cada - 1);
					$cada = $cada + $Nfilas['LINEAS_MODIFICACION'];
				}
				if($num_fila == $numero_filas){
					$combo_select[$num_fila] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $numero_filas);
				}
			}
			$num_filas->close();
		}else{
			$combo_select[1] = array ( "inicio" => 1, "fin" => 0);
			$resultadoc->close();
		}
		return $combo_select;											
	}

	function Botones_selector_aereos_precios($filadesde, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_producto_cuadros_aereos_precios');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_aereos_precios($clave_cuadro, $clave_calendario, $clave_aereo, $precio_1, $clase_1, $precio_2, $clase_2, $suplemento_3, $clase_3, $suplemento_4, $clase_4, $suplemento_5, $clase_5, $clave_calendario_old, $clave_aereo_old){

		$conexion = $this ->Conexion;

		$datos_aereos =$conexion->query("select folleto, cuadro, opcion, numero from hit_producto_cuadros_aereos where clave = '".$clave_aereo."'");
		$aereos = $datos_aereos->fetch_assoc();
		$folleto = $aereos['folleto'];
		$cuadro = $aereos['cuadro'];
		$opcion = $aereos['opcion'];
		$numero = $aereos['numero'];

		$datos_fechas =$conexion->query("select fecha_desde, fecha_hasta from hit_producto_cuadros_calendarios where clave = '".$clave_calendario."'");
		$fechas = $datos_fechas->fetch_assoc();
		$fecha_desde = $fechas['fecha_desde'];
		$fecha_hasta = $fechas['fecha_hasta'];

		$query = "UPDATE hit_producto_cuadros_aereos_precios SET ";
		$query .= " CLAVE_CALENDARIO = '".$clave_calendario."'";
		$query .= ", CLAVE_AEREO = '".$clave_aereo."'";
		$query .= ", FOLLETO = '".$folleto."'";
		$query .= ", CUADRO = '".$cuadro."'";
		$query .= ", OPCION = '".$opcion."'";
		$query .= ", NUMERO = '".$numero."'";
		$query .= ", FECHA_DESDE = '".$fecha_desde."'";
		$query .= ", FECHA_HASTA = '".$fecha_hasta."'";
		$query .= ", PRECIO_1 = '".$precio_1."'";
		$query .= ", CLASE_1 = '".$clase_1."'";
		$query .= ", PRECIO_2 = '".$precio_2."'";
		$query .= ", CLASE_2 = '".$clase_2."'";
		$query .= ", SUPLEMENTO_3 = '".$suplemento_3."'";
		$query .= ", CLASE_3 = '".$clase_4."'";
		$query .= ", SUPLEMENTO_4 = '".$suplemento_4."'";
		$query .= ", CLASE_4 = '".$clase_4."'";
		$query .= ", SUPLEMENTO_5 = '".$suplemento_5."'";
		$query .= ", CLASE_5 = '".$clase_5."'";
		$query .= " WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND CLAVE_CALENDARIO = '".$clave_calendario_old."'";
		$query .= " AND CLAVE_AEREO = '".$clave_aereo_old."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
			//echo($query);
		}else{
			$respuesta = 'OK';
			//echo($query);
		}

		return $respuesta;											
	}

	function Borrar_aereos_precios($clave_cuadro, $clave_calendario, $clave_aereo){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_cuadros_aereos_precios WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND CLAVE_CALENDARIO = '".$clave_calendario."'";
		$query .= " AND CLAVE_AEREO = '".$clave_aereo."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_aereos_precios($clave_cuadro, $clave_calendario, $clave_aereo, $precio_1, $clase_1, $precio_2, $clase_2, $suplemento_3, $clase_3, $suplemento_4, $clase_4, $suplemento_5, $clase_5){

		$conexion = $this ->Conexion;

		//ECHO($clave_cuadro.'-'.$clave_calendario.'-'.$clave_aereo.'-'.$precio_1.'-'.$clase_1.'-'.$precio_2.'-'.$clase_2);

		$datos_aereos =$conexion->query("select folleto, cuadro, ciudad, opcion, numero from hit_producto_cuadros_aereos where clave = '".$clave_aereo."'");
		$aereos = $datos_aereos->fetch_assoc();
		$folleto = $aereos['folleto'];
		$cuadro = $aereos['cuadro'];
		$ciudad = $aereos['ciudad'];
		$opcion = $aereos['opcion'];
		$numero = $aereos['numero'];

		$datos_fechas =$conexion->query("select fecha_desde, fecha_hasta from hit_producto_cuadros_calendarios where clave = '".$clave_calendario."'");
		$fechas = $datos_fechas->fetch_assoc();
		$fecha_desde = $fechas['fecha_desde'];
		$fecha_hasta = $fechas['fecha_hasta'];

		$query = "INSERT INTO hit_producto_cuadros_aereos_precios (CLAVE_CUADRO, CLAVE_CALENDARIO, CLAVE_AEREO, FOLLETO,CUADRO,CIUDAD,OPCION,NUMERO,FECHA_DESDE, FECHA_HASTA, PRECIO_1,CLASE_1,PRECIO_2,CLASE_2, SUPLEMENTO_3,CLASE_3,SUPLEMENTO_4,CLASE_4,SUPLEMENTO_5,CLASE_5) VALUES (";
		$query .= "'".$clave_cuadro."',";
		$query .= "'".$clave_calendario."',";
		$query .= "'".$clave_aereo."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$ciudad."',";
		$query .= "'".$opcion."',";
		$query .= "'".$numero."',";
		$query .= "'".$fecha_desde."',";
		$query .= "'".$fecha_hasta."',";
		$query .= "'".$precio_1."',";
		$query .= "'".$clase_1."',";
		$query .= "'".$precio_2."',";
		$query .= "'".$clase_2."',";
		$query .= "'".$suplemento_3."',";
		$query .= "'".$clase_3."',";
		$query .= "'".$suplemento_4."',";
		$query .= "'".$clase_4."',";
		$query .= "'".$suplemento_5."',";
		$query .= "'".$clase_5."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}



	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsCuadros_aereos($conexion, $filadesde, $usuario, $buscar_codigo, $buscar_cuadro){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_codigo= $buscar_codigo;
		$this->Buscar_cuadro= $buscar_cuadro;
	}
}

?>