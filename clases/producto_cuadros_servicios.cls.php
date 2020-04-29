<?php

class clsCuadros_servicios{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var $buscar_cuadro;

//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LAS SERVICIOS---
//--------------------------------------------------------------------

	function Cargar_servicios($clave, $filadesde, $buscar_tipo){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

	
		if($buscar_tipo != null){
			$CADENA_BUSCAR = " and s.CLAVE_CUADRO = '".$clave."' AND s.TIPO = '".$buscar_tipo."'";
		}else{
			$CADENA_BUSCAR = " and s.CLAVE_CUADRO = '".$clave."'"; 
  		}
		

		$resultado =$conexion->query("SELECT s.numero, s.id_proveedor, s.codigo_servicio, v.nombre, s.dia, s.orden, s.tipo
										FROM hit_producto_cuadros_servicios s, hit_servicios v
										WHERE s.id_proveedor = v.id_proveedor and s.codigo_servicio = v.codigo ".$CADENA_BUSCAR." ORDER BY dia, orden");

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SERVICIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$folletos_servicios = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['numero'] == ''){
				break;
			}
			array_push($folletos_servicios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $folletos_servicios;											
	}


	function Cargar_lineas_nuevas_servicios(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SERVICIOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_servicios($clave, $filadesde, $buscar_tipo){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_tipo != null){
			$CADENA_BUSCAR = " and s.CLAVE_CUADRO = '".$clave."' AND s.TIPO = '".$buscar_tipo."'";
		}else{
			$CADENA_BUSCAR = " and s.CLAVE_CUADRO = '".$clave."'"; 
  		}
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT *
										FROM hit_producto_cuadros_servicios s, hit_servicios v
										WHERE s.id_proveedor = v.id_proveedor and s.codigo_servicio = v.codigo ".$CADENA_BUSCAR." ORDER BY dia, orden");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SERVICIOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_servicios($filadesde, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_producto_cuadros_servicios');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SERVICIOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_servicios($clave_cuadro, $numero, $id_proveedor, $codigo_servicio, $dia, $orden, $tipo, $numero_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_producto_cuadros_servicios SET ";
		$query .= " NUMERO = '".$numero."'";
		$query .= ", ID_PROVEEDOR = '".$id_proveedor."'";
		$query .= ", CODIGO_SERVICIO = '".$codigo_servicio."'";
		$query .= ", DIA = '".$dia."'";
		$query .= ", ORDEN = '".$orden."'";
		$query .= ", TIPO = '".$tipo."'";
		$query .= " WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
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

	function Borrar_servicios($clave_cuadro, $numero){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_cuadros_servicios WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND NUMERO = '".$numero."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_servicios($clave_cuadro, $folleto, $cuadro, $id_proveedor, $codigo_servicio, $dia, $orden, $tipo){

		$conexion = $this ->Conexion;

		$datos_numero =$conexion->query("select max(numero) numero from hit_producto_cuadros_servicios where CLAVE_CUADRO = '".$clave_cuadro."'");
		$numero_siguiente = $datos_numero->fetch_assoc();
		$numero_nuevo = $numero_siguiente['numero'] + 1;

		//echo($numero_nuevo);

		$query = "INSERT INTO hit_producto_cuadros_servicios (CLAVE_CUADRO, FOLLETO, CUADRO, NUMERO, ID_PROVEEDOR, CODIGO_SERVICIO, DIA, ORDEN, TIPO) VALUES (";
		$query .= "'".$clave_cuadro."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$numero_nuevo."',";
		$query .= "'".$id_proveedor."',";
		$query .= "'".$codigo_servicio."',";
		$query .= "'".$dia."',";
		$query .= "'".$orden."',";
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
//------METODOS PARA LA PARTE INFERIOR CON LAS PRECIOS DE SERVICIOS------
//--------------------------------------------------------------------

	function Cargar_servicios_precios($clave, $filadesde, $buscar_servicio, $buscar_fecha){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

	
		if($buscar_servicio != null){
			$fech = " AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."' AND CLAVE_SERVICIO = '".$buscar_servicio."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
		}elseif($buscar_fecha != null){
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."'";    
  		}


		$resultado =$conexion->query("SELECT clave_servicio,clave_calendario, pax_desde, pax_hasta, precio, precio_ninos
										FROM hit_producto_cuadros_servicios_precios 
										WHERE ".$CADENA_BUSCAR." ORDER BY numero, fecha_desde, pax_desde");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SERVICIOS_PRECIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$folletos_servicios_precios = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['clave_servicio'] == ''){
				break;
			}
			array_push($folletos_servicios_precios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $folletos_servicios_precios;
	}


	function Cargar_lineas_nuevas_servicios_precios(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SERVICIOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_servicios_precios($clave, $filadesde, $buscar_servicio, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_servicio != null){
			$fech = " AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."' AND CLAVE_SERVICIO = '".$buscar_servicio."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
		}elseif($buscar_fecha != null){
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."'";    
  		}	

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_servicios_precios WHERE ".$CADENA_BUSCAR." ORDER BY numero, fecha_desde");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SERVICIOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_servicios_precios($filadesde, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_producto_cuadros_servicios_precios');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_SERVICIOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_servicios_precios($clave_cuadro, $clave_calendario, $clave_servicio, $precio, $precio_ninos, $clave_calendario_old, $clave_servicio_old){

		$conexion = $this ->Conexion;

		$datos_servicios =$conexion->query("select folleto, cuadro, numero from hit_producto_cuadros_servicios where clave = '".$clave_servicio."'");
		$servicios = $datos_servicios->fetch_assoc();
		$folleto = $servicios['folleto'];
		$cuadro = $servicios['cuadro'];
		$numero = $servicios['numero'];

		$datos_fechas =$conexion->query("select fecha_desde, fecha_hasta from hit_producto_cuadros_calendarios where clave = '".$clave_calendario."'");
		$fechas = $datos_fechas->fetch_assoc();
		$fecha_desde = $fechas['fecha_desde'];
		$fecha_hasta = $fechas['fecha_hasta'];

		$query = "UPDATE hit_producto_cuadros_servicios_precios SET ";
		$query .= " CLAVE_CALENDARIO = '".$clave_calendario."'";
		$query .= ", CLAVE_SERVICIO = '".$clave_servicio."'";
		$query .= ", FOLLETO = '".$folleto."'";
		$query .= ", CUADRO = '".$cuadro."'";
		$query .= ", NUMERO = '".$numero."'";
		$query .= ", FECHA_DESDE = '".$fecha_desde."'";
		$query .= ", FECHA_HASTA = '".$fecha_hasta."'";
		$query .= ", PRECIO = '".$precio."'";
		$query .= ", PRECIO_NINOS = '".$precio_ninos."'";
		$query .= " WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND CLAVE_CALENDARIO = '".$clave_calendario_old."'";
		$query .= " AND CLAVE_SERVICIO = '".$clave_servicio_old."'";

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

	function Borrar_servicios_precios($clave_cuadro, $clave_calendario, $clave_servicio){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_cuadros_servicios_precios WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND CLAVE_CALENDARIO = '".$clave_calendario."'";
		$query .= " AND CLAVE_SERVICIO = '".$clave_servicio."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_servicios_precios($clave_cuadro, $clave_calendario, $clave_servicio, $precio, $precio_ninos){

		$conexion = $this ->Conexion;

		//ECHO($clave_cuadro.'-'.$clave_calendario.'-'.$clave_servicio.'-'.$precio_1.'-'.$clase_1.'-'.$precio_2.'-'.$clase_2);

		$datos_servicios =$conexion->query("select folleto, cuadro, numero from hit_producto_cuadros_servicios where clave = '".$clave_servicio."'");
		$servicios = $datos_servicios->fetch_assoc();
		$folleto = $servicios['folleto'];
		$cuadro = $servicios['cuadro'];
		$numero = $servicios['numero'];

		$datos_fechas =$conexion->query("select fecha_desde, fecha_hasta from hit_producto_cuadros_calendarios where clave = '".$clave_calendario."'");
		$fechas = $datos_fechas->fetch_assoc();
		$fecha_desde = $fechas['fecha_desde'];
		$fecha_hasta = $fechas['fecha_hasta'];

		$query = "INSERT INTO hit_producto_cuadros_servicios_precios (CLAVE_CUADRO, CLAVE_CALENDARIO, CLAVE_SERVICIO, FOLLETO,CUADRO,NUMERO,FECHA_DESDE, FECHA_HASTA, PRECIO, PRECIO_NINOS) VALUES (";
		$query .= "'".$clave_cuadro."',";
		$query .= "'".$clave_calendario."',";
		$query .= "'".$clave_servicio."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$numero."',";
		$query .= "'".$fecha_desde."',";
		$query .= "'".$precio."',";
		$query .= "'".$precio_ninos."')";

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
	function clsCuadros_servicios($conexion, $filadesde, $usuario, $buscar_codigo, $buscar_cuadro){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_codigo= $buscar_codigo;
		$this->Buscar_cuadro= $buscar_cuadro;
	}
}

?>