<?php

class clsTeletipos_aereos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var $buscar_nombre;

//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS AEREOS--------------
//--------------------------------------------------------------------

	function Cargar_aereos($id, $filadesde_aereos){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		$resultado =$conexion->query("SELECT ciudad, opcion, 
			DATE_FORMAT(fecha_desde_1, '%d-%m-%Y') AS aereos_fecha_desde_1, 
			DATE_FORMAT(fecha_hasta_1, '%d-%m-%Y') AS aereos_fecha_hasta_1, 
			cabecera_1 aereos_cabecera_1, 
			DATE_FORMAT(fecha_desde_2, '%d-%m-%Y') AS aereos_fecha_desde_2, 
			DATE_FORMAT(fecha_hasta_2, '%d-%m-%Y') AS aereos_fecha_hasta_2, 
			cabecera_2 aereos_cabecera_2, 
			DATE_FORMAT(fecha_desde_3, '%d-%m-%Y') AS aereos_fecha_desde_3, 
			DATE_FORMAT(fecha_hasta_3, '%d-%m-%Y') AS aereos_fecha_hasta_3, 
			cabecera_3 aereos_cabecera_3
			FROM hit_teletipos_aereos WHERE ID = '".$id."' ORDER BY ciudad");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//echo($id."-".$filadesde_aereos."-".$buscar_hotel);
		
		//echo($CADENA_BUSCAR);
		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_AEREOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$aereos = array();
		for ($num_fila = $filadesde_aereos-1; $num_fila <= $filadesde_aereos + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			if($fila['ciudad'] == ''){
				break;
			}
			array_push($aereos,$fila);
		}

		
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $aereos;											
	}


	function Cargar_lineas_nuevas_aereos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_AEREOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_aereos($id, $filadesde_aereos){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_teletipos_aereos WHERE ID = '".$id."' ORDER BY ciudad");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_AEREOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_aereos($filadesde_aereos, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_teletipos_aereos');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_AEREOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $num_filas->fetch_assoc();
		
		if($boton == 1){
			$selector = 1;
		}elseif($boton == 2){
			$selector = $filadesde_aereos - $Nfilas['LINEAS_MODIFICACION'];
		}elseif($boton == 3){
			$selector = $filadesde_aereos + $Nfilas['LINEAS_MODIFICACION'];		
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

	function Modificar_aereos($id, $ciudad, $opcion, $fecha_desde_1, $fecha_hasta_1, $cabecera_1, $fecha_desde_2, $fecha_hasta_2, $cabecera_2, $fecha_desde_3, $fecha_hasta_3, $cabecera_3, $ciudad_old){


		$conexion = $this ->Conexion;
		$query = "UPDATE hit_teletipos_aereos SET ";
		$query .= " CIUDAD = '".$ciudad."'";
		$query .= ", OPCION = '".$opcion."'";

		if($fecha_desde_1 == ''){
			$query .= ", FECHA_DESDE_1 = null";
		}else{
			$query .= ", FECHA_DESDE_1 = '".date("Y-m-d",strtotime($fecha_desde_1))."'";
		}
		if($fecha_hasta_1 == ''){
			$query .= ", FECHA_HASTA_1 = null";
		}else{
			$query .= ", FECHA_HASTA_1 = '".date("Y-m-d",strtotime($fecha_hasta_1))."'";
		}
		

		$query .= ", CABECERA_1 = '".$cabecera_1."'";

		if($fecha_desde_2 == ''){
			$query .= ", FECHA_DESDE_2 = null";
		}else{
			$query .= ", FECHA_DESDE_2 = '".date("Y-m-d",strtotime($fecha_desde_2))."'";
		}
		if($fecha_hasta_2 == ''){
			$query .= ", FECHA_HASTA_2 = null";
		}else{
			$query .= ", FECHA_HASTA_2 = '".date("Y-m-d",strtotime($fecha_hasta_2))."'";
		}

		$query .= ", CABECERA_2 = '".$cabecera_2."'";		

		if($fecha_desde_3 == ''){
			$query .= ", FECHA_DESDE_3 = null";
		}else{
			$query .= ", FECHA_DESDE_3 = '".date("Y-m-d",strtotime($fecha_desde_3))."'";
		}
		if($fecha_hasta_3 == ''){
			$query .= ", FECHA_HASTA_3 = null";
		}else{
			$query .= ", FECHA_HASTA_3 = '".date("Y-m-d",strtotime($fecha_hasta_3))."'";
		}

		$query .= ", CABECERA_3 = '".$cabecera_3."'";
		$query .= " WHERE ID = '".$id."'";
		$query .= " AND CIUDAD = '".$ciudad_old."'";


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

	function Borrar_aereos($id, $ciudad){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_teletipos_aereos WHERE ID = '".$id."'";
		$query .= " AND CIUDAD = '".$ciudad."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_aereos($id, $ciudad, $opcion, $fecha_desde_1, $fecha_hasta_1, $cabecera_1, $fecha_desde_2, $fecha_hasta_2, $cabecera_2, $fecha_desde_3, $fecha_hasta_3, $cabecera_3){

		$conexion = $this ->Conexion;


		if($fecha_desde_2 == ''){
			$fecha_desde_2 = null;
		}

		$query = "INSERT INTO hit_teletipos_aereos (ID, CIUDAD, OPCION, FECHA_DESDE_1, FECHA_HASTA_1, CABECERA_1, FECHA_DESDE_2, FECHA_HASTA_2, CABECERA_2, FECHA_DESDE_3, FECHA_HASTA_3, CABECERA_3) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$ciudad."',";		
		$query .= "'".$opcion."',";

		if($fecha_desde_1 == ''){
			$query .= "null,";
		}else{
			$query .= "'".date("Y-m-d",strtotime($fecha_desde_1))."',";
		}
		if($fecha_hasta_1 == ''){
			$query .= "null,";
		}else{
			$query .= "'".date("Y-m-d",strtotime($fecha_hasta_1))."',";
		}

		$query .= "'".$cabecera_1."',";

		if($fecha_desde_2 == ''){
			$query .= "null,";
		}else{
			$query .= "'".date("Y-m-d",strtotime($fecha_desde_2))."',";
		}
		if($fecha_hasta_2 == ''){
			$query .= "null,";
		}else{
			$query .= "'".date("Y-m-d",strtotime($fecha_hasta_2))."',";
		}

		$query .= "'".$cabecera_2."',";

		if($fecha_desde_3 == ''){
			$query .= "null,";
		}else{
			$query .= "'".date("Y-m-d",strtotime($fecha_desde_3))."',";
		}
		if($fecha_hasta_3 == ''){
			$query .= "null,";
		}else{
			$query .= "'".date("Y-m-d",strtotime($fecha_hasta_3))."',";
		}
		
		$query .= "'".$cabecera_3."')";


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
	function clsTeletipos_aereos($conexion, $filadesde, $usuario, $buscar_id, $buscar_nombre, $buscar_destino){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id= $buscar_id;
		$this->Buscar_nombre= $buscar_nombre;
		$this->Buscar_destino = $buscar_destino;
	}		
	
}

?>