<?php

class clsTeletipos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var $buscar_nombre;


//-----------------------------------------------------------------------------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS GENERALES----------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------
	function Cargar($recuperaid,$recuperanombre,$recupera_cuadro,$recuperafecha_lanzamiento,$recuperadestino,$recuperaformato,$recuperacolores,$recuperatitulo,$recuperadias,$recuperacabecera_imagen,$recuperalogo_dcto_1,$recuperalogo_dcto_2,$recuperatexto_pie, $recuperafecha_desde_1, $recuperafecha_hasta_1,$recuperacabecera_1,$recuperafecha_desde_2,$recuperafecha_hasta_2,$recuperacabecera_2,$recuperafecha_desde_3,$recuperafecha_hasta_3,$recuperacabecera_3,$recuperatexto_libre_1,$recuperatexto_libre_2,$recuperatexto_libre_3){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_destino = $this ->Buscar_destino;
		
		if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
		}elseif($buscar_destino != null){
			$CADENA_BUSCAR = " WHERE DESTINO = '".$buscar_destino."'";
  		}else{
			$CADENA_BUSCAR = " WHERE ID = ''"; 
		}

		$resultado =$conexion->query("SELECT id, nombre, cuadro, DATE_FORMAT(fecha_lanzamiento, '%d-%m-%Y') AS fecha_lanzamiento,
											 destino, formato, colores,
											 titulo, 
											 dias,
											 cabecera_imagen, 
											 logo_dcto_1, 
											 logo_dcto_2,
											 texto_pie,
											 DATE_FORMAT(fecha_desde_1, '%d-%m-%Y') AS fecha_desde_1,
											 DATE_FORMAT(fecha_hasta_1, '%d-%m-%Y') AS fecha_hasta_1,
											 cabecera_1,
											 DATE_FORMAT(fecha_desde_2, '%d-%m-%Y') AS fecha_desde_2,
											 DATE_FORMAT(fecha_hasta_2, '%d-%m-%Y') AS fecha_hasta_2,
											 cabecera_2,
											 DATE_FORMAT(fecha_desde_3, '%d-%m-%Y') AS fecha_desde_3,
											 DATE_FORMAT(fecha_hasta_3, '%d-%m-%Y') AS fecha_hasta_3,
											 cabecera_3,
											 texto_libre_1,
											 texto_libre_2,
											 texto_libre_3
										FROM hit_teletipos ".$CADENA_BUSCAR." ORDER BY id");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			//$resultado->close();
			//$conexion->close();
			//exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$teletipos = array();
		if($recuperaid != null){
			$folletos[0] = array ("id" => $recuperaid, "nombre" => $recuperanombre, "cuadro" => $recuperacuadro, "fecha_lanzamiento" => $recuperafecha_lanzamiento,"destino" => $recuperadestino, "formato" => $recuperaformato, "colores" => $recuperacolores,"titulo" => $recuperatitulo,"dias" => $recuperadias, "cabecera_imagen" => $recuperacabecera_imagen, "logo_dcto_1" => $recuperalogo_dcto_1, "logo_dcto_2" => $recuperalogo_dcto_2, "texto_pie" => $recuperatexto_pie, "fecha_desde_1" => $recuperafecha_desde_1, "fecha_hasta_1" => $recuperafecha_hasta_1, "cabecera_1" => $recuperacabecera_1, "fecha_desde_2" => $recuperafecha_desde_2, "fecha_hasta_2" => $recuperafecha_hasta_2, "cabecera_2" => $recuperacabecera_2, "fecha_desde_3" => $recuperafecha_desde_3, "fecha_hasta_3" => $recuperafecha_hasta_3, "cabecera_3" => $recuperacabecera_3);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($teletipos,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $teletipos;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_destino = $this ->Buscar_destino;
		
		if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
		}elseif($buscar_destino != null){
			$CADENA_BUSCAR = " WHERE DESTINO = '".$buscar_destino."'";
  		}else{
			$CADENA_BUSCAR = " WHERE ID = ''"; 
		}

		$resultadoc =$conexion->query("SELECT * FROM hit_teletipos ".$CADENA_BUSCAR." ORDER BY id");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS' AND USUARIO = '".$Usuario."'");
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
		$resultadoc =$conexion->query('SELECT * FROM hit_teletipos');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id,$nombre,$cuadro,$fecha_lanzamiento,$destino,$formato,$colores,$titulo,$dias,$cabecera_imagen,$logo_dcto_1,$logo_dcto_2,$texto_pie,$fecha_desde_1,$fecha_hasta_1,$cabecera_1,$fecha_desde_2,$fecha_hasta_2,$cabecera_2,$fecha_desde_3,$fecha_hasta_3,$cabecera_3,$texto_libre_1,$texto_libre_2,$texto_libre_3){

		//ECHO('fecha_hasta_1');
		$conexion = $this ->Conexion;
		$query = "UPDATE hit_teletipos SET ";
		$query .= " ID = '".$id."'";
		$query .= ", NOMBRE = '".$nombre."'";
		$query .= ", CUADRO = '".$cuadro."'";

		if($fecha_desde_1 == ''){
			$query .= ", FECHA_LANZAMIENTO = null";
		}else{
			$query .= ", FECHA_LANZAMIENTO = '".date("Y-m-d",strtotime($fecha_lanzamiento))."'";
		}

		$query .= ", DESTINO = '".$destino."'";
		$query .= ", FORMATO = '".$formato."'";
		$query .= ", COLORES = '".$colores."'";
		$query .= ", TITULO = '".$titulo."'";
		$query .= ", DIAS = '".$dias."'";
		$query .= ", CABECERA_IMAGEN = '".$cabecera_imagen."'";
		$query .= ", LOGO_DCTO_1 = '".$logo_dcto_1."'";
		$query .= ", LOGO_DCTO_2 = '".$logo_dcto_2."'";
		$query .= ", TEXTO_LIBRE_1 = '".$texto_libre_1."'";
		$query .= ", TEXTO_LIBRE_2 = '".$texto_libre_2."'";
		$query .= ", TEXTO_LIBRE_3 = '".$texto_libre_3."'";
		$query .= ", TEXTO_PIE = '".$texto_pie."'";
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

		$query = "DELETE FROM hit_teletipos WHERE ID = '".$id."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($nombre,$fecha_lanzamiento,$cuadro,$destino,$formato,$colores,$titulo,$dias,$cabecera_imagen,$logo_dcto_1,$logo_dcto_2,$texto_pie,$fecha_desde_1,$fecha_hasta_1,$cabecera_1,$fecha_desde_2,$fecha_hasta_2,$cabecera_2,$fecha_desde_3,$fecha_hasta_3,$cabecera_3,$texto_libre_1,$texto_libre_2,$texto_libre_3){
		//ECHO('HOLAINSERTA');
		$conexion = $this ->Conexion;

		$query = "INSERT INTO hit_teletipos(NOMBRE,CUADRO,FECHA_LANZAMIENTO,DESTINO,FORMATO,COLORES,TITULO,DIAS,CABECERA_IMAGEN,LOGO_DCTO_1,LOGO_DCTO_2,TEXTO_LIBRE_1,TEXTO_LIBRE_2,TEXTO_LIBRE_3,TEXTO_PIE,FECHA_DESDE_1,FECHA_HASTA_1,CABECERA_1,FECHA_DESDE_2,FECHA_HASTA_2,CABECERA_2,FECHA_DESDE_3,FECHA_HASTA_3,CABECERA_3) VALUES (";
		//$query .= "'".$id."',";
		$query .= "'".$nombre."',";
		$query .= "'".$cuadro."',";

		if($fecha_lanzamiento == ''){
			$query .= "null,";
		}else{
			$query .= "'".date("Y-m-d",strtotime($fecha_lanzamiento))."',";
		}

		$query .= "'".$destino."',";
		$query .= "'".$formato."',";
		$query .= "'".$colores."',";
		$query .= "'".$titulo."',";
		$query .= "'".$dias."',";
		$query .= "'".$cabecera_imagen."',";
		$query .= "'".$logo_dcto_1."',";
		$query .= "'".$logo_dcto_2."',";
		$query .= "'".$texto_libre_1."',";
		$query .= "'".$texto_libre_2."',";
		$query .= "'".$texto_libre_3."',";
		$query .= "'".$texto_pie."',";

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
	function clsTeletipos($conexion, $filadesde, $usuario, $buscar_id, $buscar_nombre, $buscar_destino){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id= $buscar_id;
		$this->Buscar_nombre= $buscar_nombre;
		$this->Buscar_destino = $buscar_destino;
	}
}

?>