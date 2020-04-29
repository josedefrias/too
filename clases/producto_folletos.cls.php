<?php

class clsFolletos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;

	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------
	function Cargar($recuperacodigo,$recuperanombre){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_codigo = $this ->Buscar_codigo;

		$resultado =$conexion->query("SELECT codigo, nombre FROM hit_producto_folletos WHERE CODIGO = '".$buscar_codigo."' ORDER BY nombre");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			//$resultado->close();
			//$conexion->close();
			//exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$folletos = array();
		if($recuperacodigo != null){
			$folletos[0] = array ("codigo" => $recuperacodigo, "nombre" => $recuperanombre);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($folletos,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $folletos;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_codigo = $this ->Buscar_codigo;

		$resultadoc =$conexion->query("SELECT * from hit_producto_folletos WHERE CODIGO = '".$buscar_codigo."' ORDER BY nombre");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS' AND USUARIO = '".$Usuario."'");
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
		$resultadoc =$conexion->query('SELECT * FROM hit_producto_folletos');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($codigo,$nombre){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_producto_folletos SET ";
		$query .= " NOMBRE = '".$nombre."'";
		$query .= " WHERE CODIGO = '".$codigo."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($codigo){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_folletos WHERE CODIGO = '".$codigo."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($codigo,$nombre){

		$conexion = $this ->Conexion;

		$query = "INSERT INTO hit_producto_folletos(CODIGO, NOMBRE) VALUES (";
		$query .= "'".$codigo."',";
		$query .= "'".$nombre."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}



//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LAS CONDICIONES---
//--------------------------------------------------------------------

	function Cargar_condiciones($codigo, $filadesde, $buscar_tipo){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

	
		if($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE FOLLETO = '".$codigo."' AND TIPO = '".$buscar_tipo."'";
		}else{
			$CADENA_BUSCAR = " WHERE FOLLETO = '".$codigo."'"; 
  		}


		$resultado =$conexion->query("SELECT tipo, 
		DATE_FORMAT(salida_desde, '%d-%m-%Y') AS salida_desde,
		DATE_FORMAT(salida_hasta, '%d-%m-%Y') AS salida_hasta,
		DATE_FORMAT(reserva_desde, '%d-%m-%Y') AS reserva_desde,
		DATE_FORMAT(reserva_hasta, '%d-%m-%Y') AS reserva_hasta,
		margen_1, margen_2, maximo, forma_calculo, valor_1, valor_2, acumulable, prioritario, aplicacion FROM hit_producto_folletos_condiciones ".$CADENA_BUSCAR." ORDER BY tipo, salida_desde, reserva_desde, margen_1");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS_CONDICIONES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$folletos_condiciones = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['tipo'] == ''){
				break;
			}
			array_push($folletos_condiciones,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $folletos_condiciones;											
	}


	function Cargar_lineas_nuevas_condiciones(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS_CONDICIONES' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_condiciones($codigo, $filadesde, $buscar_tipo){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE FOLLETO = '".$codigo."' AND TIPO = '".$buscar_tipo."'";
		}else{
			$CADENA_BUSCAR = " WHERE FOLLETO = '".$codigo."'"; 
  		}						

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_producto_folletos_condiciones'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS_CONDICIONES' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_condiciones($filadesde, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_producto_folletos_condiciones');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS_CONDICIONES' AND USUARIO = '".$Usuario."'");
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

	function Modificar_condiciones($codigo, $tipo, $salida_desde, $salida_hasta, $reserva_desde, $reserva_hasta, $margen_1, $margen_2, $maximo, $forma_calculo, $valor_1,
		$valor_2, $acumulable, $prioritario, $aplicacion, $tipo_old, $salida_desde_old, $salida_hasta_old, $reserva_desde_old, $reserva_hasta_old, $margen_1_old, $margen_2_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_producto_folletos_condiciones SET ";
		$query .= " TIPO = '".$tipo."'";
		$query .= ", SALIDA_DESDE = '".date("Y-m-d",strtotime($salida_desde))."'";
		$query .= ", SALIDA_HASTA = '".date("Y-m-d",strtotime($salida_hasta))."'";
		$query .= ", RESERVA_DESDE = '".date("Y-m-d",strtotime($reserva_desde))."'";
		$query .= ", RESERVA_HASTA = '".date("Y-m-d",strtotime($reserva_hasta))."'";
		$query .= ", MARGEN_1 = '".$margen_1."'";
		$query .= ", MARGEN_2 = '".$margen_2."'";
		$query .= ", MAXIMO = '".$maximo."'";
		$query .= ", FORMA_CALCULO = '".$forma_calculo."'";
		$query .= ", VALOR_1 = '".$valor_1."'";
		$query .= ", VALOR_2 = '".$valor_2."'";
		$query .= ", ACUMULABLE = '".$acumulable."'";
		$query .= ", PRIORITARIO = '".$prioritario."'";
		$query .= ", APLICACION = '".$aplicacion."'";
		$query .= " WHERE FOLLETO = '".$codigo."'";
		$query .= " AND TIPO = '".$tipo_old."'";
		$query .= " AND SALIDA_DESDE = '".date("Y-m-d",strtotime($salida_desde_old))."'";
		$query .= " AND SALIDA_HASTA = '".date("Y-m-d",strtotime($salida_hasta_old))."'";
		$query .= " AND RESERVA_DESDE = '".date("Y-m-d",strtotime($reserva_desde_old))."'";
		$query .= " AND RESERVA_HASTA = '".date("Y-m-d",strtotime($reserva_hasta_old))."'";
		$query .= " AND MARGEN_1 = '".$margen_1_old."'";
		$query .= " AND MARGEN_2 = '".$margen_2_old."'";

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

	function Borrar_condiciones($codigo, $tipo, $salida_desde, $salida_hasta, $reserva_desde, $reserva_hasta, $margen_1, $margen_2){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_folletos_condiciones WHERE FOLLETO = '".$codigo."'";
		$query .= " AND TIPO = '".$tipo."'";
		$query .= " AND SALIDA_DESDE= '".date("Y-m-d",strtotime($salida_desde))."'";
		$query .= " AND SALIDA_HASTA = '".date("Y-m-d",strtotime($salida_hasta))."'";
		$query .= " AND RESERVA_DESDE = '".date("Y-m-d",strtotime($reserva_desde))."'";
		$query .= " AND RESERVA_HASTA = '".date("Y-m-d",strtotime($reserva_hasta))."'";
		$query .= " AND MARGEN_1 = '".$margen_1."'";
		$query .= " AND MARGEN_2 = '".$margen_2."'";
		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_condiciones($codigo, $tipo, $salida_desde, $salida_hasta, $reserva_desde, $reserva_hasta, $margen_1, $margen_2, $maximo, $forma_calculo, $valor_1,
		$valor_2, $acumulable, $prioritario, $aplicacion){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_producto_folletos_condiciones (FOLLETO, TIPO, SALIDA_DESDE, SALIDA_HASTA, RESERVA_DESDE, RESERVA_HASTA, MARGEN_1, MARGEN_2, MAXIMO, FORMA_CALCULO, VALOR_1, VALOR_2, ACUMULABLE, PRIORITARIO, APLICACION) VALUES (";
		$query .= "'".$codigo."',";
		$query .= "'".$tipo."',";
		$query .= "'".date("Y-m-d",strtotime($salida_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($salida_hasta))."',";
		$query .= "'".date("Y-m-d",strtotime($reserva_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($reserva_hasta))."',";
		$query .= "'".$margen_1."',";
		$query .= "'".$margen_2."',";
		$query .= "'".$maximo."',";
		$query .= "'".$forma_calculo."',";
		$query .= "'".$valor_1."',";
		$query .= "'".$valor_2."',";
		$query .= "'".$acumulable."',";
		$query .= "'".$prioritario."',";
		$query .= "'".$aplicacion."')";

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
	function clsFolletos($conexion, $filadesde, $usuario, $buscar_codigo){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_codigo= $buscar_codigo;
	}
}

?>