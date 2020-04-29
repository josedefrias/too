<?php

class clsGrupos_comision{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var	$buscar_nombre;
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
		$buscar_nombre = $this ->Buscar_nombre;



		if($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";
		}else{
			//$CADENA_BUSCAR = " WHERE NOMBRE = '' ";
			$CADENA_BUSCAR = "";
		}

		$resultado =$conexion->query("SELECT codigo,nombre FROM hit_grupos_comision ".$CADENA_BUSCAR." ORDER BY NOMBRE");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$grupos_comision = array();
		if($recuperanombre != null){
			$grupos_comision[0] = array ("codigo" => null, "nombre" => $recuperanombre);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($grupos_comision,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $grupos_comision;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION' AND USUARIO = '".$Usuario."'");
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

		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_nombre = $this ->Buscar_nombre;

	
		if($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}else{
			//$CADENA_BUSCAR = " WHERE NOMBRE = '' ";
			$CADENA_BUSCAR = "";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_grupos_comision'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION' AND USUARIO = '".$Usuario."'");
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
		
		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_nombre = $this ->Buscar_nombre;

	
		if($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}else{
			//$CADENA_BUSCAR = " WHERE NOMBRE = '' ";
			$CADENA_BUSCAR = "";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_grupos_comision'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION' AND USUARIO = '".$Usuario."'");
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
		$query = "UPDATE hit_grupos_comision SET ";
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

		$query = "DELETE FROM hit_grupos_comision WHERE CODIGO = '".$codigo."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($codigo, $nombre){

		$conexion = $this ->Conexion;

		$query = "INSERT INTO hit_grupos_comision (CODIGO, NOMBRE) VALUES (";
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
//------METODOS PARA LA PARTE INFERIOR CON LOS DATOS DE COMISIONES----
//--------------------------------------------------------------------

	function Cargar_comisiones($id, $filadesde, $buscar_producto){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		if($buscar_producto != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND PRODUCTO LIKE '%".$buscar_producto."%' ";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";
		}

		$resultado =$conexion->query("SELECT DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde, DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta, producto, folleto, cuadro, paquete, comision_paquetes, comision_alojamientos, comision_transportes,	
									  comision_servicios FROM hit_grupos_comision_comisiones ".$CADENA_BUSCAR." ORDER BY fecha_desde, fecha_hasta, producto, folleto, cuadro, paquete");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION_COMISIONES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$grupos_comision_comisiones = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['fecha_desde'] == ''){
				break;
			}
			array_push($grupos_comision_comisiones,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $grupos_comision_comisiones;											
	}


	function Cargar_lineas_nuevas_comisiones(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION_COMISIONES' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_comisiones($id, $buscar_producto){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_producto != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND PRODUCTO LIKE '%".$buscar_producto."%' ";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_grupos_comision_comisiones'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION_COMISIONES' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_comisiones($filadesde, $boton, $id, $buscar_producto){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_producto != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND PRODUCTO LIKE '%".$buscar_producto."%' ";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_grupos_comision_comisiones'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION_COMISIONES' AND USUARIO = '".$Usuario."'");
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

	function Modificar_comisiones($id, $fecha_desde, $fecha_hasta, $producto, $folleto, $cuadro, $paquete, $comision_paquetes, $comision_alojamientos, $comision_transportes, $comision_servicios){

		//$fecha2=date("Y-m-d",strtotime($fecha_desde));
		//echo(date("Y-m-d",strtotime($fecha_desde)));


		$conexion = $this ->Conexion;
		$query = "UPDATE hit_grupos_comision_comisiones SET ";
		//$query .= " FECHA_DESDE = '".$fecha_desde."'";
		//$query .= ", FECHA_HASTA = '".$fecha_hasta."'";
		$query .= " PRODUCTO = '".$producto."'";
		$query .= ", FOLLETO = '".$folleto."'";
		$query .= ", CUADRO = '".$cuadro."'";
		$query .= ", PAQUETE = '".$paquete."'";
		$query .= ", COMISION_PAQUETES = '".$comision_paquetes."'";
		$query .= ", COMISION_ALOJAMIENTOS = '".$comision_alojamientos."'";
		$query .= ", COMISION_TRANSPORTES = '".$comision_transportes."'";
		$query .= ", COMISION_SERVICIOS = '".$comision_servicios."'";
		$query .= " WHERE ID = '".$id."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= " AND PRODUCTO = '".$producto."'";
		$query .= " AND FOLLETO = '".$folleto."'";
		$query .= " AND CUADRO = '".$cuadro."'";
		$query .= " AND PAQUETE = '".$paquete."'";

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

	function Borrar_comisiones($id, $fecha_desde,$fecha_hasta,$producto,$folleto,$cuadro,$paquete){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_grupos_comision_comisiones WHERE ID = '".$id."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= " AND PRODUCTO = '".$producto."'";
		$query .= " AND FOLLETO = '".$folleto."'";
		$query .= " AND CUADRO = '".$cuadro."'";
		$query .= " AND PAQUETE = '".$paquete."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_comisiones($id, $fecha_desde, $fecha_hasta, $producto, $folleto, $cuadro, $paquete, $comision_paquetes, $comision_alojamientos, $comision_transportes, $comision_servicios){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_grupos_comision_comisiones (ID, FECHA_DESDE, FECHA_HASTA, PRODUCTO, FOLLETO, CUADRO, PAQUETE, COMISION_PAQUETES, COMISION_ALOJAMIENTOS, COMISION_TRANSPORTES, COMISION_SERVICIOS) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta))."',";
		$query .= "'".$producto."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$paquete."',";
		$query .= "'".$comision_paquetes."',";
		$query .= "'".$comision_alojamientos."',";
		$query .= "'".$comision_transportes."',";
		$query .= "'".$comision_servicios."')";

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
	function clsGrupos_comision($conexion, $filadesde, $usuario, $buscar_codigo, $buscar_nombre){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_codigo = $buscar_codigo;
		$this->Buscar_nombre = $buscar_nombre;
	}
}

?>