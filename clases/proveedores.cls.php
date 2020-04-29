<?php

class clsProveedores{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var	$buscar_nombre;
	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------
	function Cargar($recuperaid,$recuperanombre,$recuperavisible,$recuperacif,$recuperalocalidad,$recuperadireccion,$recuperacodigo_postal,$recuperaprovincia,
		$recuperapais,$recuperatelefono,$recuperafax,$recuperaemail,$recuperacaracteristicas,$recuperaidioma,$recuperaresponsable,$recuperacargo_responsable,
		$recuperaurl,$recuperaswift,$recuperacc_iban,$recuperanombre_banco,$recuperadireccion_banco){


		/*
		$recuperaid,
		$recuperanombre,
		$recuperavisible,
		$recuperacif,
		$recuperalocalidad,
		$recuperadireccion,
		$recuperacodigo_postal,
		$recuperaprovincia,
		$recuperapais,
		$recuperatelefono,
		$recuperafax,
		$recuperamail,
		$recuperacaracteristicas,
		$recuperaidioma,
		$recuperaresponsable,
		$recuperacargo_responsable,
		$recuperaurl,
		$recuperaswift,
		$recuperacc_iban,
		$recuperanombre_banco,
		$recuperadireccion_banco


		$id,
		$nombre,
		$visible,
		$cif,
		$localidad,
		$direccion,
		$codigo_postal,
		$provincia,
		$pais,
		$telefono,
		$fax,
		$mail,
		$caracteristicas,
		$idioma,
		$responsable,
		$cargo_responsable,
		$url,
		$swift,
		$cc_iban,
		$nombre_banco,
		$direccion_banco
		
		id,
		nombre,
		visible,
		cif,
		localidad,
		direccion,
		codigo_postal,
		provincia,
		pais,
		telefono,
		fax,
		mail,
		caracteristicas,
		idioma,
		responsable,
		cargo_responsable,
		url,
		swift,
		cc_iban,
		nombre_banco,
		direccion_banco
		
				"id" => $recuperaid,
				"nombre" => $recuperanombre,
				"visible" => $recuperavisible,
				"cif" => $recuperacif,
				"localidad" => $recuperalocalidad,
				"direccion" => $recuperadireccion,
				"codigo_postal" => $recuperacodigo_postal,
				"provincia" => $recuperaprovincia,
				"pais" => $recuperapais,
				"telefono" => $recuperatelefono,
				"fax" => $recuperafax,
				"email" => $recuperaemail,
				"caracteristicas" => $recuperacaracteristicas,
				"idioma" => $recuperaidioma,
				"responsable" => $recuperaresponsable,
				"cargo_responsable" => $recuperacargo_responsable,
				"url" => $recuperaurl,
				"swift" => $recuperaswift,
				"cc_iban" => $recuperacc_iban,
				"nombre_banco" => $recuperanombre_banco,
				"direccion_banco" => $recuperadireccion_banco


		*/

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;
		

		if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}else{
			$CADENA_BUSCAR = "";
		}	

		$resultado =$conexion->query("SELECT id,nombre,visible,cif,localidad,direccion,codigo_postal,provincia,pais,telefono,fax,
		email,caracteristicas,idioma,responsable,cargo_responsable,url,swift,cc_iban,nombre_banco,direccion_banco 
		FROM hit_proveedores ".$CADENA_BUSCAR." ORDER BY nombre");
	


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PROVEEDORES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$proveedores = array();
		if($recuperanombre != null){
			$proveedores[0] = array ("id" => $recuperaid,"nombre" => $recuperanombre,"visible" => $recuperavisible,"cif" => $recuperacif,"localidad" => $recuperalocalidad,
				"direccion" => $recuperadireccion,"codigo_postal" => $recuperacodigo_postal,"provincia" => $recuperaprovincia,"pais" => $recuperapais,
				"telefono" => $recuperatelefono,"fax" => $recuperafax,"email" => $recuperaemail,"caracteristicas" => $recuperacaracteristicas,
				"idioma" => $recuperaidioma,"responsable" => $recuperaresponsable,	"cargo_responsable" => $recuperacargo_responsable,"url" => $recuperaurl,
				"swift" => $recuperaswift,"cc_iban" => $recuperacc_iban,"nombre_banco" => $recuperanombre_banco,"direccion_banco" => $recuperadireccion_banco);


		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($proveedores,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $proveedores;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;

		if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}else{
			$CADENA_BUSCAR = "";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_proveedores'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PROVEEDORES' AND USUARIO = '".$Usuario."'");
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
		
		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;

		if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}else{
			$CADENA_BUSCAR = "";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_proveedores'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PROVEEDORES' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id,$nombre,$visible,$cif,$localidad,$direccion,$codigo_postal,$provincia,$pais,$telefono,$fax,$email,$caracteristicas,
		$idioma,$responsable,$cargo_responsable,$url,$swift,$cc_iban,$nombre_banco,	$direccion_banco){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_proveedores SET ";
		$query .= " NOMBRE = '".$nombre."'";
		$query .= ", VISIBLE = '".$visible."'"; 
		$query .= ", CIF = '".$cif."'"; 
		$query .= ", LOCALIDAD = '".$localidad."'";
		$query .= ", DIRECCION = '".$direccion."'";
		$query .= ", CODIGO_POSTAL = '".$codigo_postal."'";
		$query .= ", PROVINCIA = '".$provincia."'";
		$query .= ", PAIS = '".$pais."'";
		$query .= ", TELEFONO = '".$telefono."'";
		$query .= ", FAX = '".$fax."'";
		$query .= ", EMAIL = '".$email."'";
		$query .= ", CARACTERISTICAS = '".$caracteristicas."'";
		$query .= ", IDIOMA = '".$idioma."'";
		$query .= ", RESPONSABLE = '".$responsable."'";
		$query .= ", CARGO_RESPONSABLE = '".$cargo_responsable."'";
		$query .= ", URL = '".$url."'";
		$query .= ", SWIFT = '".$swift."'";
		$query .= ", CC_IBAN = '".$cc_iban."'";
		$query .= ", NOMBRE_BANCO = '".$nombre_banco."'";
		$query .= ", DIRECCION_BANCO = '".$direccion_banco."'";
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

		$query = "DELETE FROM hit_proveedores WHERE ID = '".$id."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($nombre,$visible,$cif,$localidad,$direccion,$codigo_postal,$provincia,$pais,$telefono,$fax,$email,$caracteristicas,
		$idioma,$responsable,$cargo_responsable,$url,$swift,$cc_iban,$nombre_banco,	$direccion_banco){

		$conexion = $this ->Conexion;


		$query = "INSERT INTO hit_proveedores (NOMBRE,VISIBLE,CIF, LOCALIDAD,DIRECCION,CODIGO_POSTAL,PROVINCIA,PAIS,TELEFONO,FAX,EMAIL, CARACTERISTICAS,IDIOMA,RESPONSABLE,CARGO_RESPONSABLE,URL,SWIFT,CC_IBAN,NOMBRE_BANCO,DIRECCION_BANCO) VALUES (";
		$query .= "'".$nombre."',"; 
		$query .= "'".$visible."',";
		$query .= "'".$cif."',";
		$query .= "'".$localidad."',";
		$query .= "'".$direccion."',";
		$query .= "'".$codigo_postal."',";
		$query .= "'".$provincia."',";
		$query .= "'".$pais."',";
		$query .= "'".$telefono."',";
		$query .= "'".$fax."',";
		$query .= "'".$email."',";
		$query .= "'".$caracteristicas."',";
		$query .= "'".$idioma."',";
		$query .= "'".$responsable."',";
		$query .= "'".$cargo_responsable."',";
		$query .= "'".$url."',"; 
		$query .= "'".$swift."',";
		$query .= "'".$cc_iban."',";
		$query .= "'".$nombre_banco."',";
		$query .= "'".$direccion_banco."')";


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

	function clsProveedores($conexion, $filadesde, $usuario, $buscar_id, $buscar_nombre){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id = $buscar_id;
		$this->Buscar_nombre = $buscar_nombre;
	}
}

?>