<?php

class clsAeropuertos_ciudades{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_aeropuerto;
	var	$buscar_ciudad;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_aeropuerto = $this ->Buscar_aeropuerto;
		$buscar_ciudad = $this ->Buscar_ciudad;
	
		if($buscar_aeropuerto != null){
			$CADENA_BUSCAR = " WHERE AEROPUERTO LIKE '%".$buscar_aeropuerto."%' ";
		}elseif($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE CIUDAD LIKE '%".$buscar_ciudad."%' ";
		}else{
			$CADENA_BUSCAR = "";
		}

		$resultado =$conexion->query("SELECT aeropuerto, ciudad
									  FROM hit_aeropuertos_ciudades ".$CADENA_BUSCAR." ORDER BY AEROPUERTO, CIUDAD");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'AEROPUERTOS_CIUDADES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$aeropuertos_ciudades = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['aeropuerto'] == ''){
				break;
			}
			array_push($aeropuertos_ciudades,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $aeropuertos_ciudades;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'AEROPUERTOS_CIUDADES' AND USUARIO = '".$Usuario."'");
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

		$buscar_aeropuerto = $this ->Buscar_aeropuerto;
		$buscar_ciudad = $this ->Buscar_ciudad;
	
		if($buscar_aeropuerto != null){
			$CADENA_BUSCAR = " WHERE AEROPUERTO LIKE '%".$buscar_aeropuerto."%' ";
		}elseif($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE CIUDAD LIKE '%".$buscar_ciudad."%' ";
		}else{
			$CADENA_BUSCAR = "";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_aeropuertos_ciudades'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'AEROPUERTOS_CIUDADES' AND USUARIO = '".$Usuario."'");
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
		
		$buscar_aeropuerto = $this ->Buscar_aeropuerto;
		$buscar_ciudad = $this ->Buscar_ciudad;
	
		if($buscar_aeropuerto != null){
			$CADENA_BUSCAR = " WHERE AEROPUERTO LIKE '%".$buscar_aeropuerto."%' ";
		}elseif($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE CIUDAD LIKE '%".$buscar_ciudad."%' ";
		}else{
			$CADENA_BUSCAR = "";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_aeropuertos_ciudades'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'AEROPUERTOS_CIUDADES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $num_filas->fetch_assoc();
		
		if($boton == 1){
			$selector = 1;
		}elseif($boton == 2){
			$selector = $filadesde - $Nfilas['LINEAS_MODIFICACION'];
		}elseif($boton == 3){
			$selector = $filadesde + $Nfilas['LINEAS_MODIFICACION'];		
		}else{
			$selector = 1;
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

	/*function Modificar($aeropuero, $ciudad){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_aeropuertos_ciudades SET ";
		$query .= " AEROPUERTO = '".$aeropuerto."'";
		$query .= ", CIUDAD = '".$ciudad."'";
		$query .= " WHERE AEROPUERTO = '".$aeropuerto."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}*/

	function Borrar($aeropuerto, $ciudad){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_aeropuertos_ciudades WHERE AEROPUERTO = '".$aeropuerto."' AND CIUDAD = '".$ciudad."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($aeropuerto, $ciudad){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_aeropuertos_ciudades (AEROPUERTO, CIUDAD) VALUES (";
		$query .= "'".$aeropuerto."',";
		$query .= "'".$ciudad."')";

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
	function clsAeropuertos_ciudades($conexion, $filadesde, $usuario, $buscar_aeropuerto, $buscar_ciudad){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_aeropuerto = $buscar_aeropuerto;
		$this->Buscar_ciudad = $buscar_ciudad;
	}
}

?>