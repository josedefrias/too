<?php

class clsLogos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_nombre;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		$buscar_nombre = $this ->Buscar_nombre;
	
		if($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_nombre."' ";
		}else{
			$CADENA_BUSCAR = "";
		}

		$resultado = $conexion->query("SELECT id, nombre, condicion ,fichero from hit_logos".$CADENA_BUSCAR." order by nombre");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$conexion->error);
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'LOGOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$logos = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			if($fila['id'] == ''){
				break;
			}
			array_push($logos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $logos;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'LOGOS' AND USUARIO = '".$Usuario."'");
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

		$buscar_nombre = $this ->Buscar_nombre;
	
		if($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_nombre."' ";
		}else{
			$CADENA_BUSCAR = "";
		}
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_logos'.$CADENA_BUSCAR);


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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'LOGOS' AND USUARIO = '".$Usuario."'");
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
		
		$buscar_nombre = $this ->Buscar_nombre;
	
		if($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_nombre."' ";
		}else{
			$CADENA_BUSCAR = "";
		}										

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_logos'.$CADENA_BUSCAR);


		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'LOGOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id, $nombre, $condicion, $fichero){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_logos SET ";
		$query .= " NOMBRE = '".$nombre."'";
		$query .= ", CONDICION = '".$condicion."'";
		$query .= ", FICHERO = '".$fichero."'";
		$query .= " WHERE ID = '".$id."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($id){

		$conexion = $this ->Conexion;

		//Guardamos datos del nomre de la imagen para poder borrarla despues de eliminar el registro
		$datos =$conexion->query("select fichero from hit_logos where id = '".$id."'");
		$imagenes = $datos->fetch_assoc();
		$fichero = $imagenes['fichero'];

		$query = "DELETE FROM hit_logos WHERE ID = '".$id."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';

			//Borramos el fichero de imagen del servidor
			$fichero_imagen = 'imagenes/logos/'.$fichero;
			//echo($fichero_imagen);
			if (file_exists($fichero_imagen)){
				if(unlink($fichero_imagen)){
					$respuesta = 'OK';
				}
			}

		}

		return $respuesta;											
	}

	function Insertar($nombre, $condicion, $fichero){

		$conexion = $this ->Conexion;
		echo($nombre.'-'.$fichero);

		$fichero = str_replace(' ','_',$fichero);

		$query = "INSERT INTO hit_logos (NOMBRE,CONDICION,FICHERO) VALUES (";
		$query .= "'".$nombre."',";
		$query .= "'".$condicion."',";
		$query .= "'".$fichero."')";

		//ECHO($query);

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
	function clsLogos($conexion, $filadesde, $usuario, $buscar_nombre){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_nombre = $buscar_nombre;
	}
}

?>