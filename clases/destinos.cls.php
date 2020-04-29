<?php

class clsDestinos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var	$buscar_nombre;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_pais = $this ->Buscar_pais;
		$buscar_grupo = $this ->Buscar_grupo;
	
		if($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}elseif($buscar_grupo != null){
			$pai = " AND PAIS = '".$buscar_pais."'";
			$CADENA_BUSCAR = " WHERE GRUPO = '".$buscar_grupo."'";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
		}elseif($buscar_pais != null){
			$CADENA_BUSCAR = " WHERE PAIS = '".$buscar_pais."'";
		}else{
			$CADENA_BUSCAR = "";
		}

		$resultado =$conexion->query("SELECT codigo,nombre, grupo, pais, visible, visible_grupos, paginacion_web_con_aereos, paginacion_web_resto, telefono_contacto, provincia FROM hit_destinos ".$CADENA_BUSCAR." ORDER BY NOMBRE");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'DESTINOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$destinos = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['codigo'] == '' and $num_fila != $Filadesde-1){
				break;
			}
			array_push($destinos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $destinos;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'DESTINOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_pais = $this ->Buscar_pais;
		$buscar_grupo = $this ->Buscar_grupo;
	
		if($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}elseif($buscar_grupo != null){
			$pai = " AND PAIS = '".$buscar_pais."'";
			$CADENA_BUSCAR = " WHERE GRUPO = '".$buscar_grupo."'";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
		}elseif($buscar_pais != null){
			$CADENA_BUSCAR = " WHERE PAIS = '".$buscar_pais."'";
		}else{
			$CADENA_BUSCAR = "";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_destinos'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'DESTINOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_pais = $this ->Buscar_pais;
		$buscar_grupo = $this ->Buscar_grupo;
	
		if($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}elseif($buscar_grupo != null){
			$pai = " AND PAIS = '".$buscar_pais."'";
			$CADENA_BUSCAR = " WHERE GRUPO = '".$buscar_grupo."'";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
		}elseif($buscar_pais != null){
			$CADENA_BUSCAR = " WHERE PAIS = '".$buscar_pais."'";
		}else{
			$CADENA_BUSCAR = "";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_destinos'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'DESTINOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($codigo, $nombre, $grupo, $pais, $visible, $visible_grupos, $paginacion_web_con_aereos, $paginacion_web_resto, $telefono_contacto, $provincia){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_destinos SET ";
		$query .= " NOMBRE = '".$nombre."'";
		$query .= ", GRUPO = '".$grupo."'";
		$query .= ", PAIS = '".$pais."'";
		$query .= ", VISIBLE = '".$visible."'";
		$query .= ", VISIBLE_GRUPOS = '".$visible_grupos."'";
		$query .= ", PAGINACION_WEB_CON_AEREOS = '".$paginacion_web_con_aereos."'";
		$query .= ", PAGINACION_WEB_RESTO = '".$paginacion_web_resto."'";
		$query .= ", TELEFONO_CONTACTO = '".$telefono_contacto."'";
		$query .= ", PROVINCIA = '".$provincia."'";
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

		$query = "DELETE FROM hit_destinos WHERE CODIGO = '".$codigo."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($codigo, $nombre, $grupo, $pais, $visible, $visible_grupos, $paginacion_web_con_aereos, $paginacion_web_resto, $telefono_contacto, $provincia){

		$conexion = $this ->Conexion;


		if($paginacion_web_con_aereos == null){
			$paginacion_web_con_aereos = 10;
		}
		if($paginacion_web_resto == null){
			$paginacion_web_resto = 20;
		}

		$query = "INSERT INTO hit_destinos (CODIGO, NOMBRE, GRUPO, PAIS, VISIBLE, VISIBLE_GRUPOS, PAGINACION_WEB_CON_AEREOS, PAGINACION_WEB_RESTO, TELEFONO_CONTACTO, PROVINCIA) VALUES (";
		$query .= "'".$codigo."',";
		$query .= "'".$nombre."',";
		$query .= "'".$grupo."',";
		$query .= "'".$pais."',";
		$query .= "'".$visible."',";
		$query .= "'".$visible_grupos."',";
		$query .= "'".$paginacion_web_con_aereos."',";
		$query .= "'".$paginacion_web_resto."',";
		$query .= "'".$telefono_contacto."',";
		$query .= "'".$provincia."')";

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
	function clsDestinos($conexion, $filadesde, $usuario, $buscar_codigo, $buscar_nombre, $buscar_pais, $buscar_grupo){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_codigo = $buscar_codigo;
		$this->Buscar_nombre = $buscar_nombre;
		$this->Buscar_pais = $buscar_pais;
		$this->Buscar_grupo = $buscar_grupo;
	}
}

?>