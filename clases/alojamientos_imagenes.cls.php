<?php

class clsAlojamientos_imagenes{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_alojamiento;
	var	$buscar_tipo;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_alojamiento = $this ->Buscar_alojamiento;
		$buscar_tipo = $this ->Buscar_tipo;
	
		if($buscar_alojamiento != null){
			if($buscar_tipo != null){
				$CADENA_BUSCAR = " WHERE ALOJAMIENTO = '".$buscar_alojamiento."' and TIPO = '".$buscar_tipo."'";
			}else{
				$CADENA_BUSCAR = " WHERE ALOJAMIENTO = '".$buscar_alojamiento."' ";
			}
		/*}elseif($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE TIPO = '".$buscar_tipo."' ";*/
		}else{
			$CADENA_BUSCAR = " WHERE ALOJAMIENTO = '' ";
		}

		$resultado =$conexion->query("SELECT id, alojamiento, tipo, numero, url
									  FROM hit_alojamientos_imagenes ".$CADENA_BUSCAR." ORDER BY ALOJAMIENTO, TIPO, NUMERO");


		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_IMAGENES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$alojamientos_imagenes = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($alojamientos_imagenes,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $alojamientos_imagenes;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_IMAGENES' AND USUARIO = '".$Usuario."'");
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

		$buscar_alojamiento = $this ->Buscar_alojamiento;
		$buscar_tipo = $this ->Buscar_tipo;
	
		if($buscar_alojamiento != null){
			if($buscar_tipo != null){
				$CADENA_BUSCAR = " WHERE ALOJAMIENTO = '".$buscar_alojamiento."' and TIPO = '".$buscar_tipo."'";
			}else{
				$CADENA_BUSCAR = " WHERE ALOJAMIENTO = '".$buscar_alojamiento."' ";
			}
		/*}elseif($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE TIPO = '".$buscar_tipo."' ";*/
		}else{
			$CADENA_BUSCAR = " WHERE ALOJAMIENTO = '".$buscar_alojamiento."' ";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_imagenes'.$CADENA_BUSCAR);


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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_IMAGENES' AND USUARIO = '".$Usuario."'");
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
		
		$buscar_alojamiento = $this ->Buscar_alojamiento;
		$buscar_tipo = $this ->Buscar_tipo;
	
		if($buscar_alojamiento != null){
			if($buscar_tipo != null){
				$CADENA_BUSCAR = " WHERE ALOJAMIENTO = '".$buscar_alojamiento."' and TIPO = '".$buscar_tipo."'";
			}else{
				$CADENA_BUSCAR = " WHERE ALOJAMIENTO = '".$buscar_alojamiento."' ";
			}
		/*}elseif($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE TIPO = '".$buscar_tipo."' ";*/
		}else{
			$CADENA_BUSCAR = " WHERE ALOJAMIENTO = '".$buscar_alojamiento."' ";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_imagenes'.$CADENA_BUSCAR);


		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_IMAGENES' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id,$alojamiento, $tipo, $numero, $url){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_alojamientos_imagenes SET ";
		$query .= " ALOJAMIENTO = '".$alojamiento."'";
		$query .= ", TIPO = '".$tipo."'";
		$query .= ", NUMERO = '".$numero."'";
		$query .= ", URL = '".$url."'";
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
		$datos =$conexion->query("select alojamiento, tipo, numero from hit_alojamientos_imagenes where id = '".$id."'");
		$imagenes = $datos->fetch_assoc();
		$alojamiento = $imagenes['alojamiento'];
		$tipo = $imagenes['tipo'];
		$numero = $imagenes['numero'];

		$query = "DELETE FROM hit_alojamientos_imagenes WHERE ID = '".$id."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';

			//Borramos el fichero de imagen del servidor
			$fichero_imagen = 'imagenes/alojamientos/'.$alojamiento.'_'.$tipo.'_'.$numero.'.jpg';
			//echo($fichero_imagen);
			if (file_exists($fichero_imagen)){
				if(unlink($fichero_imagen)){
					$respuesta = 'OK';
				}
			}

		}

		return $respuesta;											
	}

	function Insertar($alojamiento, $tipo, $numero){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_alojamientos_imagenes (ALOJAMIENTO, TIPO, NUMERO) VALUES (";
		$query .= $alojamiento.",";
		$query .= "'".$tipo."',";
		$query .= $numero.")";

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
	function clsAlojamientos_imagenes($conexion, $filadesde, $usuario, $buscar_alojamiento, $buscar_tipo){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_alojamiento = $buscar_alojamiento;
		$this->Buscar_tipo = $buscar_tipo;
	}
}

?>