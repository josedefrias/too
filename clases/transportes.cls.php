<?php

class clsTransportes{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_cia;
	var	$buscar_nombre;
	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------
	function Cargar($recuperacia,$recuperanombre,$recuperavisible,$recuperacif,$recuperalocalidad,$recuperadireccion,$recuperacodigo_postal,$recuperaprovincia,$recuperapais,$recuperatelefono,$recuperafax,$recuperaemail,$recuperaidioma,$recuperaresponsable,$recuperacargo_responsable,$recuperaurl,$recuperaswift,$recuperacc_iban,$recuperanombre_banco,$recuperadireccion_banco, $recuperaemptyroute_asociado, $recuperaemptyroute_usuario, $recuperaemptyroute_pass){




		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_cia = $this ->Buscar_cia;
		$buscar_nombre = $this ->Buscar_nombre;

		if($buscar_cia != null){
			$CADENA_BUSCAR = " WHERE CIA = '".$buscar_cia."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}else{
			$CADENA_BUSCAR = "";
		}	

		$resultado =$conexion->query("SELECT cia,nombre,visible,cif,localidad,direccion,codigo_postal,provincia,pais,telefono,fax,
		email,idioma,responsable,cargo_responsable,url,swift,cc_iban,nombre_banco,direccion_banco, emptyroute_asociado, emptyroute_usuario, emptyroute_pass
		FROM hit_transportes ".$CADENA_BUSCAR." ORDER BY nombre");


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$transportes = array();
		if($recuperanombre != null){
			$transportes[0] = array ("cia" => $recuperacia,"nombre" => $recuperanombre,"visible" => $recuperavisible,"cif" => $recuperacif,"localidad" => $recuperalocalidad,
				"direccion" => $recuperadireccion,"codigo_postal" => $recuperacodigo_postal,"provincia" => $recuperaprovincia,"pais" => $recuperapais,
				"telefono" => $recuperatelefono,"fax" => $recuperafax,"email" => $recuperaemail,
				"idioma" => $recuperaidioma,"responsable" => $recuperaresponsable,	"cargo_responsable" => $recuperacargo_responsable,"url" => $recuperaurl,
				"swift" => $recuperaswift,"cc_iban" => $recuperacc_iban,"nombre_banco" => $recuperanombre_banco,"direccion_banco" => $recuperadireccion_banco,"emptyroute_asociado" => $recuperaemptyroute_asociado,"emptyroute_usuario" => $recuperaemptyroute_usuario,"emptyroute_pass" => $recuperaemptyroute_pass);


		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($transportes,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $transportes;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		$buscar_cia = $this ->Buscar_cia;
		$buscar_nombre = $this ->Buscar_nombre;

		if($buscar_cia != null){
			$CADENA_BUSCAR = " WHERE CIA = '".$buscar_cia."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}else{
			$CADENA_BUSCAR = "";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES' AND USUARIO = '".$Usuario."'");
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
		
		$buscar_cia = $this ->Buscar_cia;
		$buscar_nombre = $this ->Buscar_nombre;

		if($buscar_cia != null){
			$CADENA_BUSCAR = " WHERE CIA = '".$buscar_cia."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}else{
			$CADENA_BUSCAR = "";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES' AND USUARIO = '".$Usuario."'");
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

	function Modificar($cia,$nombre,$visible,$cif,$localidad,$direccion,$codigo_postal,$provincia,$pais,$telefono,$fax,$email,
		$idioma,$responsable,$cargo_responsable,$url,$swift,$cc_iban,$nombre_banco, $direccion_banco, $emptyrote_asociado, $emptyrote_usuario, $emptyrote_pass){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_transportes SET ";
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
		$query .= ", IDIOMA = '".$idioma."'";
		$query .= ", RESPONSABLE = '".$responsable."'";
		$query .= ", CARGO_RESPONSABLE = '".$cargo_responsable."'";
		$query .= ", URL = '".$url."'";
		$query .= ", SWIFT = '".$swift."'";
		$query .= ", CC_IBAN = '".$cc_iban."'";
		$query .= ", NOMBRE_BANCO = '".$nombre_banco."'";
		$query .= ", DIRECCION_BANCO = '".$direccion_banco."'";
		$query .= ", EMPTYROUTE_ASOCIADO = '".$emptyrote_asociado."'";
		$query .= ", EMPTYROUTE_USUARIO = '".$emptyrote_usuario."'";
		$query .= ", EMPTYROUTE_PASS = '".$emptyrote_pass."'";
		$query .= " WHERE CIA = '".$cia."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($cia){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_transportes WHERE CIA = '".$cia."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($cia,$nombre,$visible,$cif,$localidad,$direccion,$codigo_postal,$provincia,$pais,$telefono,$fax,$email,
		$idioma,$responsable,$cargo_responsable,$url,$swift,$cc_iban,$nombre_banco, $direccion_banco, $emptyrote_asociado, $emptyrote_usuario, $emptyrote_pass){

		$conexion = $this ->Conexion;

		if($cia == ''){
			$respuesta = 'No se ha podido insertar el nuevo registro. El codigo de la compañía es obligatorio';
		}else{
		
			$query = "INSERT INTO hit_transportes (CIA,NOMBRE,VISIBLE,CIF, LOCALIDAD,DIRECCION,CODIGO_POSTAL,PROVINCIA,PAIS,TELEFONO,FAX,EMAIL, IDIOMA,RESPONSABLE,CARGO_RESPONSABLE,URL,SWIFT,CC_IBAN,NOMBRE_BANCO,DIRECCION_BANCO, EMPTYROUTE_ASOCIADO, EMPTYROUTE_USUARIO, EMPTYROUTE_PASS) VALUES (";
			$query .= "'".$cia."',"; 
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
			$query .= "'".$idioma."',";
			$query .= "'".$responsable."',";
			$query .= "'".$cargo_responsable."',";
			$query .= "'".$url."',"; 
			$query .= "'".$swift."',";
			$query .= "'".$cc_iban."',";
			$query .= "'".$nombre_banco."',";
			$query .= "'".$direccion_banco."',";
			$query .= "'".$emptyrote_asociado."',";
			$query .= "'".$emptyrote_usuario."',";
			$query .= "'".$emptyrote_pass."')";


			$resultadoi =$conexion->query($query);

			if ($resultadoi == FALSE){
				$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
			}else{
				$respuesta = 'OK';
			}
		}

		return $respuesta;											
	}

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)

	function clsTransportes($conexion, $filadesde, $usuario, $buscar_cia, $buscar_nombre){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_cia = $buscar_cia;
		$this->Buscar_nombre = $buscar_nombre;
	}
}

?>