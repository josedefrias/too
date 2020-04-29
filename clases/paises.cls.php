<?php

class clsPaises{

	var $Orden1;
	var $Orden2;
	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var	$buscar_nombre;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$ordenadopor1 = $this ->Orden1;
		$ordenadopor2 = $this ->Orden2;
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_nombre = $this ->Buscar_nombre;
	
		if($buscar_codigo != null){
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}else{
			$CADENA_BUSCAR = "";
		}

		$resultado =$conexion->query("SELECT codigo,codigo_corto,codigo_numerico,nombre,continente,area,idioma,divisa,union_europea, prefijo, visado, visible_hits, visible_web, visible_web_minorista
									  FROM hit_paises ".$CADENA_BUSCAR." ORDER BY $ordenadopor1, $ordenadopor2");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PAISES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$paises = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['codigo'] == ''){
				break;
			}
			array_push($paises,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $paises;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PAISES' AND USUARIO = '".$Usuario."'");
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
			$CADENA_BUSCAR = "";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_paises'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PAISES' AND USUARIO = '".$Usuario."'");
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
			$CADENA_BUSCAR = "";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_paises'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PAISES' AND USUARIO = '".$Usuario."'");
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

	function Modificar($codigo_corto, $codigo_numerico, $codigo, $nombre, $continente, $area, $idioma, $divisa, $union_europea, $prefijo, $visado, $visible_hits, $visible_web, $visible_web_minorista){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_paises SET ";
		$query .= " CODIGO_CORTO = '".$codigo_corto."'";
		$query .= ", CODIGO_NUMERICO = '".$codigo_numerico."'";
		$query .= ", NOMBRE = '".$nombre."'";
		$query .= ", CONTINENTE = '".$continente."'";
		$query .= ", AREA = '".$area."'";
		$query .= ", IDIOMA = '".$idioma."'";
		$query .= ", DIVISA = '".$divisa."'";
		$query .= ", UNION_EUROPEA = '".$union_europea."'";
		$query .= ", PREFIJO = ".$prefijo;
		$query .= ", VISADO = '".$visado."'";
		$query .= ", VISIBLE_HITS = '".$visible_hits."'";
		$query .= ", VISIBLE_WEB = '".$visible_web."'";
		$query .= ", VISIBLE_WEB_MINORISTA = '".$visible_web_minorista."'";
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

		$query = "DELETE FROM hit_paises WHERE CODIGO = '".$codigo."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($codigo_corto,$codigo_numerico, $codigo, $nombre, $continente, $area, $idioma, $divisa, $union_europea, $prefijo, $visado, $visible_hits, $visible_web, $visible_web_minorista){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_paises (CODIGO_CORTO, CODIGO_NUMERICO, CODIGO, NOMBRE, CONTINENTE, AREA, IDIOMA, DIVISA,UNION_EUROPEA, PREFIJO, VISADO, VISIBLE_HITS, VISIBLE_WEB, VISIBLE_WEB_MINORISTA) VALUES (";
		$query .= "'".$codigo_corto."',";
		$query .= "'".$codigo_numerico."',";
		$query .= "'".$codigo."',";
		$query .= "'".$nombre."',";
		$query .= "'".$continente."',";
		$query .= "'".$area."',";
		$query .= "'".$idioma."',";
		$query .= "'".$divisa."',";
		$query .= "'".$union_europea."',";
		$query .= "'".$prefijo."',";
		$query .= "'".$visado."',";
		$query .= "'".$visible_hits."',";
		$query .= "'".$visible_web."',";
		$query .= "'".$visible_web_minorista."')";

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
	function clsPaises($conexion, $pOrden1, $pOrden2, $filadesde, $usuario, $buscar_codigo, $buscar_nombre){
		$this->Conexion = $conexion;
		$this->Orden1 = $pOrden1;
		$this->Orden2 = $pOrden2;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_codigo = $buscar_codigo;
		$this->Buscar_nombre = $buscar_nombre;
	}
}

?>