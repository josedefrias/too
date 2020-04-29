<?php

class clsTeletipos_hoteles{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var $buscar_nombre;

//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS HOTELES--------------
//--------------------------------------------------------------------

	function Cargar_hoteles($id, $filadesde_hoteles, $buscar_hotel, $buscar_ciudad){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

		if($buscar_hotel != 0){
			$ciud = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND HOTEL = '".$buscar_hotel."'";
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciud;	
			}
		}elseif($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND CIUDAD = '".$buscar_ciudad."'";
  		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'"; 
		}

		
		$resultado =$conexion->query("SELECT orden, ciudad, hotel, regimen, precio, precio_2, precio_3, logo_1, logo_2, logo_3 FROM hit_teletipos_hoteles ".$CADENA_BUSCAR." ORDER BY ciudad, orden, precio");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//echo($id."-".$filadesde_hoteles."-".$buscar_hotel);
		
		//echo($CADENA_BUSCAR);
		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_HOTELES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$hoteles = array();
		for ($num_fila = $filadesde_hoteles-1; $num_fila <= $filadesde_hoteles + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			if($fila['hotel'] == ''){
				break;
			}
			array_push($hoteles,$fila);
		}

		
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $hoteles;											
	}


	function Cargar_lineas_nuevas_hoteles(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_HOTELES' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_hoteles($id, $filadesde_hoteles, $buscar_hotel, $buscar_ciudad){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
	
		if($buscar_hotel != 0){
			$ciud = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND HOTEL = '".$buscar_hotel."'";
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciud;	
			}
		}elseif($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND CIUDAD = '".$buscar_ciudad."'";
  		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'"; 
		}

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_teletipos_hoteles ".$CADENA_BUSCAR." ORDER BY precio");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_HOTELES' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_hoteles($filadesde_hoteles, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_teletipos_hoteles');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_HOTELES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $num_filas->fetch_assoc();
		
		if($boton == 1){
			$selector = 1;
		}elseif($boton == 2){
			$selector = $filadesde_hoteles - $Nfilas['LINEAS_MODIFICACION'];
		}elseif($boton == 3){
			$selector = $filadesde_hoteles + $Nfilas['LINEAS_MODIFICACION'];		
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

	function Modificar_hoteles($id, $ciudad, $orden, $hotel, $regimen, $precio, $precio_2, $precio_3, $logo_1, $logo_2, $logo_3, $ciudad_old, $hotel_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_teletipos_hoteles SET ";
		$query .= " CIUDAD = '".$ciudad."'";
		$query .= ", ORDEN = '".$orden."'";
		$query .= ", HOTEL = '".$hotel."'";
		$query .= ", REGIMEN = '".$regimen."'";
		$query .= ", PRECIO = '".$precio."'";
		$query .= ", PRECIO_2 = '".$precio_2."'";
		$query .= ", PRECIO_3 = '".$precio_3."'";
		$query .= ", LOGO_1 = '".$logo_1."'";
		$query .= ", LOGO_2 = '".$logo_2."'";
		$query .= ", LOGO_3 = '".$logo_3."'";
		$query .= " WHERE ID = '".$id."'";
		$query .= " AND CIUDAD = '".$ciudad_old."'";
		$query .= " AND HOTEL = '".$hotel_old."'";

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

	function Borrar_hoteles($id, $hotel, $ciudad){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_teletipos_hoteles WHERE ID = '".$id."'";
		$query .= " AND HOTEL = '".$hotel."'";
		$query .= " AND CIUDAD = '".$ciudad."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_hoteles($id, $ciudad, $orden, $hotel, $regimen, $precio, $precio_2, $precio_3, $logo_1, $logo_2, $logo_3){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_teletipos_hoteles (ID, CIUDAD, ORDEN, HOTEL, REGIMEN, PRECIO, PRECIO_2, PRECIO_3, LOGO_1, LOGO_2, LOGO_3) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$ciudad."',";		
		$query .= "'".$orden."',";
		$query .= "'".$hotel."',";
		$query .= "'".$regimen."',";
		$query .= "'".$precio."',";
		$query .= "'".$precio_2."',";
		$query .= "'".$precio_3."',";
		$query .= "'".$logo_1."',";
		$query .= "'".$logo_2."',";
		$query .= "'".$logo_3."')";

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
	function clsTeletipos_hoteles($conexion, $filadesde, $usuario, $buscar_id, $buscar_nombre, $buscar_destino){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id= $buscar_id;
		$this->Buscar_nombre= $buscar_nombre;
		$this->Buscar_destino = $buscar_destino;
	}		
	
}

?>