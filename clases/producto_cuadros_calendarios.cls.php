<?php

class clsCuadros_calendarios{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var $buscar_cuadro;

//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS CALENDARIOS---
//--------------------------------------------------------------------

	function Cargar_calendarios($clave, $filadesde_calendarios, $buscar_fecha){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

	
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."'"; 
  		}


		$resultado =$conexion->query("SELECT  
		DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde,
		DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta,
		nombre, color, tipo FROM hit_producto_cuadros_calendarios ".$CADENA_BUSCAR." ORDER BY DATE_FORMAT(fecha_desde, '%Y-%m-%d')");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		
		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_CALENDARIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$calendarios = array();
		for ($num_fila = $filadesde_calendarios-1; $num_fila <= $filadesde_calendarios + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['fecha_desde'] == ''){
				break;
			}
			array_push($calendarios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $calendarios;											
	}


	function Cargar_lineas_nuevas_calendarios(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_CALENDARIOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_calendarios($clave, $filadesde_calendarios, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."' AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."'"; 
  		}					

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_calendarios ".$CADENA_BUSCAR." ORDER BY fecha_desde, fecha_hasta");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_CALENDARIOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_calendarios($filadesde_calendarios, $boton, $clave, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."' AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."'"; 
  		}					

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_calendarios ".$CADENA_BUSCAR." ORDER BY fecha_desde, fecha_hasta");
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_CALENDARIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $num_filas->fetch_assoc();
		
		if($boton == 1){
			$selector = 1;
		}elseif($boton == 2){
			$selector = $filadesde_calendarios - $Nfilas['LINEAS_MODIFICACION'];
		}elseif($boton == 3){
			$selector = $filadesde_calendarios + $Nfilas['LINEAS_MODIFICACION'];		
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

	function Modificar_calendarios($clave_cuadro, $fecha_desde, $fecha_hasta, $nombre, $color, $tipo, $fecha_desde_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_producto_cuadros_calendarios SET ";
		$query .= "FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= ", NOMBRE = '".$nombre."'";
		$query .= ", COLOR = '".$color."'";
		$query .= ", TIPO = '".$tipo."'";
		$query .= " WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND FECHA_DESDE= '".date("Y-m-d",strtotime($fecha_desde_old))."'";


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

	function Borrar_calendarios($clave_cuadro, $fecha_desde){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_cuadros_calendarios WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND FECHA_DESDE= '".date("Y-m-d",strtotime($fecha_desde))."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_calendarios($clave_cuadro, $folleto, $cuadro, $fecha_desde, $fecha_hasta, $nombre, $color, $tipo){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_producto_cuadros_calendarios (CLAVE_CUADRO, FOLLETO, CUADRO, FECHA_DESDE, FECHA_HASTA, NOMBRE, COLOR, TIPO) VALUES (";
		$query .= "'".$clave_cuadro."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta))."',";
		$query .= "'".$nombre."',";
		$query .= "'".$color."',";
		$query .= "'".$tipo."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}


//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LAS SALIDAS--------------
//--------------------------------------------------------------------

	function Cargar_salidas($clave, $filadesde_salidas, $buscar_fecha){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

		//echo($filadesde_salidas);	
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' = FECHA";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."'"; 
  		}


		$resultado =$conexion->query("SELECT  
		DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha,
		estado FROM hit_producto_cuadros_salidas ".$CADENA_BUSCAR." ORDER BY DATE_FORMAT(fecha, '%Y-%m-%d')");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		
		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SALIDAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$salidas = array();
		for ($num_fila = $filadesde_salidas-1; $num_fila <= $filadesde_salidas + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['fecha'] == ''){
				break;
			}
			array_push($salidas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $salidas;											
	}


	function Cargar_lineas_nuevas_salidas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SALIDAS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_salidas($clave, $filadesde_salidas, $buscar_fecha){
		//ECHO($clave."-".$filadesde_salidas."-".$buscar_fecha);
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' = FECHA";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."'"; 
  		}					

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_salidas ".$CADENA_BUSCAR." ORDER BY fecha");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SALIDAS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_salidas($filadesde_salidas, $boton, $clave, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' = FECHA";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."'"; 
  		}					

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_salidas ".$CADENA_BUSCAR." ORDER BY fecha");
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SALIDAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $num_filas->fetch_assoc();
		
		if($boton == 1){
			$selector = 1;
		}elseif($boton == 2){
			$selector = $filadesde_salidas - $Nfilas['LINEAS_MODIFICACION'];
		}elseif($boton == 3){
			$selector = $filadesde_salidas + $Nfilas['LINEAS_MODIFICACION'];		
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

	function Modificar_salidas($clave_cuadro, $fecha, $estado){
		
		$conexion = $this ->Conexion;
		$query = "UPDATE hit_producto_cuadros_salidas SET ";
		$query .= " ESTADO = '".$estado."'";
		$query .= " WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND FECHA = '".date("Y-m-d",strtotime($fecha))."'";

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

	function Borrar_salidas($clave_cuadro, $fecha){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_cuadros_salidas WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND FECHA= '".date("Y-m-d",strtotime($fecha))."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_salidas($clave_cuadro, $folleto, $cuadro, $fecha, $estado){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_producto_cuadros_salidas (CLAVE_CUADRO, FOLLETO, CUADRO, FECHA, ESTADO) VALUES (";
		$query .= "'".$clave_cuadro."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".date("Y-m-d",strtotime($fecha))."',";
		$query .= "'".$estado."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}



//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA DE CALENDARIO CIUDADES--------
//--------------------------------------------------------------------

	function Cargar_calendarios_ciudades($clave, $filadesde_calendarios_ciudades, $buscar_ciudad){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

		//echo($filadesde_salidas);	
		if($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."' AND '".$buscar_ciudad."' = CIUDAD";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."'"; 
  		}


		$resultado =$conexion->query("SELECT  
		ciudad,
		DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde_calendarios_ciudades,
		DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta_calendarios_ciudades,
		dias_semana,
		duraciones 
		FROM hit_producto_cuadros_calendarios_ciudades ".$CADENA_BUSCAR." ORDER BY ciudad, DATE_FORMAT(fecha_desde, '%Y-%m-%d')");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		
		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_CALENDARIOS_CIUDADES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$calendarios_ciudades = array();
		for ($num_fila = $filadesde_calendarios_ciudades-1; $num_fila <= $filadesde_calendarios_ciudades + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['fecha_desde_calendarios_ciudades'] == ''){
				break;
			}
			array_push($calendarios_ciudades,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $calendarios_ciudades;											
	}


	function Cargar_lineas_nuevas_calendarios_ciudades(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_CALENDARIOS_CIUDADES' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_calendarios_ciudades($clave, $filadesde_calendarios_ciudades, $buscar_ciudad){
		//ECHO($clave."-".$filadesde_salidas."-".$buscar_fecha);
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."' AND '".$buscar_ciudad."' = CIUDAD";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."'"; 
  		}					

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_calendarios_ciudades ".$CADENA_BUSCAR." ORDER BY ciudad, DATE_FORMAT(fecha_desde, '%Y-%m-%d')");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_CALENDARIOS_CIUDADES' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_calendario_ciudades($filadesde_salidas, $boton, $clave, $buscar_ciudad){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."' AND '".$buscar_ciudad."' = CIUDAD";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE_CUADRO = '".$clave."'"; 
  		}				

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_calendarios_ciudades ".$CADENA_BUSCAR." ORDER BY ciudad, DATE_FORMAT(fecha_desde, '%Y-%m-%d')");
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_CALENDARIOS_CIUDADES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $num_filas->fetch_assoc();
		
		if($boton == 1){
			$selector = 1;
		}elseif($boton == 2){
			$selector = $filadesde_salidas - $Nfilas['LINEAS_MODIFICACION'];
		}elseif($boton == 3){
			$selector = $filadesde_salidas + $Nfilas['LINEAS_MODIFICACION'];		
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

	function Modificar_calendarios_ciudades($clave_cuadro, $ciudad, $fecha_desde, $fecha_hasta, $dias_semana, $duraciones, $ciudad_old, $fecha_desde_old){
		
		$conexion = $this ->Conexion;
		$query = "UPDATE hit_producto_cuadros_calendarios_ciudades SET ";
		$query .= " CIUDAD = '".$ciudad."'";
		$query .= ", FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= ", DIAS_SEMANA = '".$dias_semana."'";
		$query .= ", DURACIONES = '".$duraciones."'";
		$query .= " WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND CIUDAD = '".$ciudad_old."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_old))."'";

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

	function Borrar_calendarios_ciudades($clave_cuadro, $ciudad, $fecha_desde){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_cuadros_calendarios_ciudades WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND CIUDAD= '".$ciudad."'";
		$query .= " AND FECHA_DESDE= '".date("Y-m-d",strtotime($fecha_desde))."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_calendarios_ciudades($clave_cuadro, $folleto, $cuadro, $ciudad, $fecha_desde, $fecha_hasta, $dias_semana, $duraciones){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_producto_cuadros_calendarios_ciudades (CLAVE_CUADRO, FOLLETO, CUADRO, CIUDAD, FECHA_DESDE, FECHA_HASTA, DIAS_SEMANA, DURACIONES) VALUES (";
		$query .= "'".$clave_cuadro."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$ciudad."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta))."',";
		$query .= "'".$dias_semana."',";
		$query .= "'".$duraciones."')";

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
	function clsCuadros_calendarios($conexion, $filadesde_calendarios, $usuario, $buscar_codigo, $buscar_cuadro){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde_calendarios;
		$this->Usuario = $usuario;
		$this->Buscar_codigo= $buscar_codigo;
		$this->Buscar_cuadro= $buscar_cuadro;
	}
}

?>