<?php

class clsCuadros{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var $buscar_cuadro;


//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS GENERALES----------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------
	function Cargar($recuperacodigo,$recuperacuadro,$recuperaproducto,$recuperatipo,$recuperanombre,$recuperadestino,$recuperadestino2,$recuperasituacion,$recuperaduracion,$recuperadias_operacion,$recuperafrecuencia,$recuperaprimera_salida,$recuperaultima_salida,$recuperadivisa,$recuperaredondeo,$recuperacodigo_administrativo,$recuperamargen_transportes,$recuperaocupacion_transportes,$recuperamargen_alojamientos,$recuperamargen_alojamientos_interfaz_1,$recuperamargen_servicios,
		$recuperaventa, $recuperaminorista_margen_transportes,$recuperaminorista_ocupacion_transportes,$recuperaminorista_margen_alojamientos,$recuperaminorista_margen_alojamientos_interfaz_1,$recuperaminorista_margen_servicios){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_cuadro = $this ->Buscar_cuadro;

		if($buscar_cuadro != null){
			$CADENA_BUSCAR = " WHERE CLAVE = '".$buscar_cuadro."'";
		}/*elseif($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE FOLLETO = '".$buscar_codigo."'"; 
  		}else{
			$CADENA_BUSCAR = " WHERE FOLLETO = ''"; 
		}*/else{
			$CADENA_BUSCAR = " WHERE CLAVE = '0'"; 
		}

		$resultado =$conexion->query("SELECT clave, folleto,cuadro,producto, tipo tipo_cuadro, nombre nombre_cuadro, destino destino_cuadro, destino2 destino_cuadro2, situacion, duracion, dias_operacion, frecuencia, 
											 DATE_FORMAT(primera_salida, '%d-%m-%Y') AS primera_salida,
											 DATE_FORMAT(ultima_salida, '%d-%m-%Y') AS ultima_salida,
											 divisa, redondeo, codigo_administrativo, margen_transportes, ocupacion_transportes, margen_alojamientos, margen_alojamientos_interfaz_1, margen_servicios, venta, minorista_margen_transportes, minorista_ocupacion_transportes, minorista_margen_alojamientos, minorista_margen_alojamientos_interfaz_1, minorista_margen_servicios
										FROM hit_producto_cuadros ".$CADENA_BUSCAR." ORDER BY nombre");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			//$resultado->close();
			//$conexion->close();
			//exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$cuadros = array();
		if($recuperacodigo != null){
			$folletos[0] = array ("codigo" => $recuperacodigo, "cuadro" => $recuperacuadro, "producto" => $recuperaproducto, "tipo" => $recuperatipo, "nombre_cuadro" => $recuperanombre_cuadro, "destino" => $recuperadestino, "destino2" => $recuperadestino2, "situacion" => $recuperasituacion, "duracion" => $recuperaduracion, "dias_operacion" => $recuperadias_operacion, "frecuencia" => $recuperafrecuencia, "primera_salida" => $recuperaprimera_salida, "ultima_salida" => $recuperaultima_salida, "divisa" => $recuperadivisa, "redondeo" => $recuperaredondeo, "codigo_administrativo" => $recuperacodigo_administrativo, "margen_transportes" => $recuperamargen_transportes, "ocupacion_transportes" => $recuperaocupacion_transportes, "margen_alojamientos" => $recuperamargen_alojamientos, "margen_alojamientos_interfaz_1" => $recuperamargen_alojamientos_interfaz_1, "margen_servicios" => $recuperamargen_servicios, "venta" => $recuperaventa, "minorista_margen_transportes" => $recuperaminorista_margen_transportes, "minorista_ocupacion_transportes" => $recuperaminorista_ocupacion_transportes, "minorista_margen_alojamientos" => $recuperaminorista_margen_alojamientos, "minorista_margen_alojamientos_interfaz_1" => $recuperaminorista_margen_alojamientos_interfaz_1, "minorista_margen_servicios" => $recuperaminorista_margen_servicios);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($cuadros,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $cuadros;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS' AND USUARIO = '".$Usuario."'");
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
		$buscar_cuadro = $this ->Buscar_cuadro;

		if($buscar_cuadro != null){
			$CADENA_BUSCAR = " WHERE CLAVE = '".$buscar_cuadro."'";
		}/*elseif($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE FOLLETO = '".$buscar_codigo."'"; 
  		}else{
			$CADENA_BUSCAR = " WHERE FOLLETO = ''"; 
		}*/else{
			$CADENA_BUSCAR = " WHERE CLAVE = '0'"; 
		}

		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros ".$CADENA_BUSCAR." ORDER BY nombre");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector($filadesde, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_producto_cuadros');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($clave_cuadro, $folleto, $cuadro, $producto, $tipo, $nombre, $destino, $destino2, $situacion, $duracion, $dias_operacion, $frecuencia, $primera_salida, $ultima_salida, $divisa, $redondeo, $codigo_administrativo,$margen_transportes,$ocupacion_transportes,$margen_alojamientos,$margen_alojamientos_interfaz_1,$margen_servicios,$venta, $minorista_margen_transportes,$minorista_ocupacion_transportes,$minorista_margen_alojamientos,$minorista_margen_alojamientos_interfaz_1,$minorista_margen_servicios){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_producto_cuadros SET ";
		$query .= " FOLLETO = '".$folleto."'";
		$query .= ", CUADRO = '".$cuadro."'";
		$query .= ", PRODUCTO = '".$producto."'";
		$query .= ", TIPO = '".$tipo."'";
		$query .= ", NOMBRE = '".$nombre."'";
		$query .= ", DESTINO = '".$destino."'";
		$query .= ", DESTINO2 = '".$destino2."'";
		$query .= ", SITUACION = '".$situacion."'";
		$query .= ", DURACION = '".$duracion."'";
		$query .= ", DIAS_OPERACION = '".$dias_operacion."'";
		$query .= ", FRECUENCIA = '".$frecuencia."'";
		$query .= ", PRIMERA_SALIDA = '".date("Y-m-d",strtotime($primera_salida))."'";
		$query .= ", ULTIMA_SALIDA = '".date("Y-m-d",strtotime($ultima_salida))."'";
		$query .= ", DIVISA = '".$divisa."'";
		$query .= ", REDONDEO = '".$redondeo."'";
		$query .= ", CODIGO_ADMINISTRATIVO = '".$codigo_administrativo."'";
		$query .= ", MARGEN_TRANSPORTES = '".$margen_transportes."'";
		$query .= ", OCUPACION_TRANSPORTES = '".$ocupacion_transportes."'";
		$query .= ", MARGEN_ALOJAMIENTOS = '".$margen_alojamientos."'";
		$query .= ", MARGEN_ALOJAMIENTOS_INTERFAZ_1 = '".$margen_alojamientos_interfaz_1."'";
		$query .= ", MARGEN_SERVICIOS = '".$margen_servicios."'";
		$query .= ", VENTA = '".$venta."'";
		$query .= ", MINORISTA_MARGEN_TRANSPORTES = '".$minorista_margen_transportes."'";
		$query .= ", MINORISTA_OCUPACION_TRANSPORTES = '".$minorista_ocupacion_transportes."'";
		$query .= ", MINORISTA_MARGEN_ALOJAMIENTOS = '".$minorista_margen_alojamientos."'";
		$query .= ", MINORISTA_MARGEN_ALOJAMIENTOS_INTERFAZ_1 = '".$minorista_margen_alojamientos_interfaz_1."'";
		$query .= ", MINORISTA_MARGEN_SERVICIOS = '".$minorista_margen_servicios."'";
		$query .= " WHERE CLAVE = '".$clave_cuadro."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($clave_cuadro){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_cuadros WHERE CLAVE = '".$clave_cuadro."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($folleto, $cuadro, $producto, $tipo, $nombre, $destino, $destino2, $situacion, $duracion, $dias_operacion, $frecuencia, $primera_salida, $ultima_salida, $divisa, $redondeo, $codigo_administrativo,$margen_transportes,$ocupacion_transportes,$margen_alojamientos,$margen_alojamientos_interfaz_1,$margen_servicios, $venta, $minorista_margen_transportes,$minorista_ocupacion_transportes,$minorista_margen_alojamientos,$minorista_margen_alojamientos_interfaz_1,$minorista_margen_servicios){

		$conexion = $this ->Conexion;

		$query = "INSERT INTO hit_producto_cuadros(FOLLETO, CUADRO, PRODUCTO, TIPO, NOMBRE, DESTINO, DESTINO2, SITUACION, DURACION, DIAS_OPERACION, FRECUENCIA, PRIMERA_SALIDA, ULTIMA_SALIDA, DIVISA, REDONDEO, CODIGO_ADMINISTRATIVO, MARGEN_TRANSPORTES,
												   OCUPACION_TRANSPORTES,MARGEN_ALOJAMIENTOS,MARGEN_ALOJAMIENTOS_INTERFAZ_1,MARGEN_SERVICIOS, VENTA, MINORISTA_MARGEN_TRANSPORTES,
												   MINORISTA_OCUPACION_TRANSPORTES,MINORISTA_MARGEN_ALOJAMIENTOS,MINORISTA_MARGEN_ALOJAMIENTOS_INTERFAZ_1,MINORISTA_MARGEN_SERVICIOS) VALUES (";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$producto."',";
		$query .= "'".$tipo."',";
		$query .= "'".$nombre."',";
		$query .= "'".$destino."',";
		$query .= "'".$destino2."',";
		$query .= "'".$situacion."',";
		$query .= "'".$duracion."',";
		$query .= "'".$dias_operacion."',";
		$query .= "'".$frecuencia."',";
		$query .= "'".date("Y-m-d",strtotime($primera_salida))."',";
		$query .= "'".date("Y-m-d",strtotime($ultima_salida))."',";
		$query .= "'".$divisa."',";
		$query .= "'".$redondeo."',";
		$query .= "'".$codigo_administrativo."',";
		$query .= "'".$margen_transportes."',";
		$query .= "'".$ocupacion_transportes."',";
		$query .= "'".$margen_alojamientos."',";
		$query .= "'".$margen_alojamientos_interfaz_1."',";
		$query .= "'".$margen_servicios."',";
		$query .= "'".$venta."',";
		$query .= "'".$minorista_margen_transportes."',";
		$query .= "'".$minorista_ocupacion_transportes."',";
		$query .= "'".$minorista_margen_alojamientos."',";
		$query .= "'".$minorista_margen_alojamientos_interfaz_1."',";
		$query .= "'".$minorista_margen_servicios."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------



	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsCuadros($conexion, $filadesde, $usuario, $buscar_codigo, $buscar_cuadro){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_codigo= $buscar_codigo;
		$this->Buscar_cuadro= $buscar_cuadro;
	}
}

?>