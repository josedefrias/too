<?php

class clsOficinas{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var	$buscar_nombre;
	var	$buscar_oficina;
	var	$buscar_telefono;
	var	$buscar_grupo_gestion;
	var	$buscar_email;
	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------
	function Cargar($recuperaid,$recuperanombre,$recuperaoficina,$recuperacif,$recuperacic,$recuperavisible,$recuperadireccion,$recuperalocalidad,$recuperacodigo_postal,$recuperaprovincia,$recuperapais,$recuperatelefono,$recuperafax,$recuperamail,$recuperadelegacion,$recuperaresponsable,$recuperacargo_responsable,$recuperausuario_web,$recuperapassword_web,$recuperasituacion,$recuperaobservaciones,$recuperanombre_fiscal,$recuperadireccion_fiscal,$recuperalocalidad_fiscal,$recuperacodigo_postal_fiscal,$recuperaprovincia_fiscal,$recuperapais_fiscal,$recuperaswift,$recuperacc_iban,$recuperanombre_banco,$recuperadireccion_banco,$recuperamail_contabilidad, $recuperafacturacion){


		/*id,oficina,cif,cic,visible,direccion,localidad,codigo_postal,provincia,pais,telefono,fax,mail,delegacion,responsable,cargo_responsable,usuario_web,password_web,situacion,	observaciones,nombre_fiscal,direccion_fiscal,localidad_fiscal,codigo_postal_fiscal,província_fiscal,pais_fiscal,swift,cc_iban,nombre_banco,direccion_banco,mail_contabilidad


		$id,
		$oficina, 
		$cif,
		$cic, 
		$visible, 
		$direccion,
		$localidad,
		$codigo_postal,
		$provincia,
		$pais,
		$telefono,
		$fax,
		$mail,
		$delegacion,
		$responsable,
		$cargo_responsable,
		$usuario_web, 
		$password_web, 
		$situacion,
		$observaciones,
		$nombre_fiscal,
		$direccion_fiscal,
		$localidad_fiscal,
		$codigo_postal_fiscal,
		$provincia_fiscal
		$pais_fiscal,
		$swift,
		$cc_iban,
		$nombre_banco,
		$direccion_banco,
		$mail_contabilidad*/






		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_oficina = $this ->Buscar_oficina;
		$buscar_telefono = $this ->Buscar_telefono;
		$buscar_email = $this ->Buscar_email;


		if($buscar_telefono != null){
			$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.TELEFONO LIKE '%".$buscar_telefono."%'";
		}elseif($buscar_codigo != null){
			if($buscar_oficina != null){
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.ID = '".$buscar_codigo."' AND OFICINA = '".$buscar_oficina."'";				
			}else{
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.ID = '".$buscar_codigo."'";
			}
		}elseif($buscar_nombre != null){
			if($buscar_oficina != null){
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND m.NOMBRE LIKE '%".$buscar_nombre."%' AND OFICINA = '".$buscar_oficina."'";	
			}else{
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND m.NOMBRE LIKE '%".$buscar_nombre."%'";
			}
		}elseif($buscar_email != null){
			$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.MAIL LIKE '%".$buscar_email."%'";
		}else{
			$CADENA_BUSCAR = " WHERE o.ID = m.ID AND m.NOMBRE = 'XX' ";
			//$CADENA_BUSCAR = "";
		}	

		$resultado =$conexion->query("SELECT o.id,m.nombre,o.oficina,o.cif,o.cic,o.visible,o.direccion,o.localidad,o.codigo_postal,o.provincia,o.pais,o.telefono,o.fax,o.mail,o.delegacion,
		o.responsable,o.cargo_responsable,o.usuario_web,o.password_web,o.situacion,o.observaciones,o.nombre_fiscal,o.direccion_fiscal,
		o.localidad_fiscal,o.codigo_postal_fiscal,o.provincia_fiscal,o.pais_fiscal,o.swift,o.cc_iban,o.nombre_banco,o.direccion_banco,o.mail_contabilidad, o.facturacion 
		FROM hit_oficinas o, hit_minoristas m ".$CADENA_BUSCAR." ORDER BY m.nombre, o.oficina");

		


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'OFICINAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$oficinas = array();
		if($recuperanombre != null){
			$minoristas[0] = array ("id" => $recuperaid,"nombre" => $recuperanombre,"oficina" => $recuperaoficina,"oficina" => $recuperacif,"cic" => $recuperacic,"visible" => $recuperavisible,"direccion" => $recuperadireccion,"localidad" => $recuperalocalidad,"codigo_postal" => $recuperacodigo_postal,"provincia" => $recuperaprovincia,"pais" => $recuperapais,"telefono" => $recuperatelefono,"fax" => $recuperafax,"mail" => $recuperamail,"delegacion" => $recuperadelegacion,"responsable" => $recuperaresponsable,"cargo_responsable" => $recuperacargo_responsable,"usuario_web" => $recuperausuario_web,"password_web" => $recuperapassword_web,"situacion" => $recuperasituacion,"observaciones" => $recuperaobservaciones,"nombre_fiscal" => $recuperanombre_fiscal,"direccion_fiscal" => $recuperadireccion_fiscal,"localidad_fiscal" => $recuperalocalidad_fiscal,"codigo_postal_fiscal" => $recuperacodigo_postal_fiscal,"provincia_fiscal" => $recuperaprvincia_fiscal,"pais_fiscal" => $recuperapais_fiscal,"swift" => $recuperaswift,"cc_iban" => $recuperacc_iban,"nombre_banco" => $recuperanombre_banco,"direccion_banco" => $recuperadireccion_banco,"mail_contabilidad" => $recuperamail_contabilidad, "facturacion"  => $recuperafacturacion);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($oficinas,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $oficinas;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_oficina = $this ->Buscar_oficina;
		$buscar_telefono = $this ->Buscar_telefono;
		$buscar_email = $this ->Buscar_email;
	
		if($buscar_telefono != null){
			$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.TELEFONO LIKE '%".$buscar_telefono."%'";
		}elseif($buscar_codigo != null){
			if($buscar_oficina != null){
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.ID = '".$buscar_codigo."' AND OFICINA = '".$buscar_oficina."'";				
			}else{
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.ID = '".$buscar_codigo."'";
			}
		}elseif($buscar_nombre != null){
			if($buscar_oficina != null){
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND m.NOMBRE LIKE '%".$buscar_nombre."%' AND OFICINA = '".$buscar_oficina."'";	
			}else{
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND m.NOMBRE LIKE '%".$buscar_nombre."%'";
			}
		}elseif($buscar_email != null){
			$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.MAIL LIKE '%".$buscar_email."%'";
		}else{
			$CADENA_BUSCAR = " WHERE o.ID = m.ID AND m.NOMBRE = 'XX' ";
			//$CADENA_BUSCAR = "";
		}										

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_oficinas o, hit_minoristas m'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'OFICINAS' AND USUARIO = '".$Usuario."'");
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
		$buscar_oficina = $this ->Buscar_oficina;
		$buscar_telefono = $this ->Buscar_telefono;
		$buscar_email = $this ->Buscar_email;
	
		if($buscar_telefono != null){
			$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.TELEFONO LIKE '%".$buscar_telefono."%'";
		}elseif($buscar_codigo != null){
			if($buscar_oficina != null){
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.ID = '".$buscar_codigo."' AND OFICINA = '".$buscar_oficina."'";				
			}else{
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.ID = '".$buscar_codigo."'";
			}
		}elseif($buscar_nombre != null){
			if($buscar_oficina != null){
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND m.NOMBRE LIKE '%".$buscar_nombre."%' AND OFICINA = '".$buscar_oficina."'";	
			}else{
				$CADENA_BUSCAR = " WHERE o.ID = m.ID AND m.NOMBRE LIKE '%".$buscar_nombre."%'";
			}
		}elseif($buscar_email != null){
			$CADENA_BUSCAR = " WHERE o.ID = m.ID AND o.MAIL LIKE '%".$buscar_email."%'";
		}else{
			$CADENA_BUSCAR = " WHERE o.ID = m.ID AND m.NOMBRE = 'XX' ";
			//$CADENA_BUSCAR = "";
		}										

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_oficinas o, hit_minoristas m'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;  		
		
		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'OFICINAS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id,$oficina,$cif,$cic, $visible, $direccion,$localidad,$codigo_postal,$provincia,$pais,$telefono,$fax,$mail,$delegacion,$responsable,$cargo_responsable,
		$usuario_web, $password_web, $situacion,$observaciones,$nombre_fiscal,$direccion_fiscal,$localidad_fiscal,$codigo_postal_fiscal,$provincia_fiscal,$pais_fiscal,$swift,	$cc_iban,$nombre_banco,	$direccion_banco,$mail_contabilidad, $facturacion){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_oficinas SET ";
		$query .= " CIF = '".$cif."'";
		$query .= ", CIC = '".$cic."'"; 
		$query .= ", VISIBLE = '".$visible."'"; 
		$query .= ", DIRECCION = '".$direccion."'";
		$query .= ", LOCALIDAD = '".$localidad."'";
		$query .= ", CODIGO_POSTAL = '".$codigo_postal."'";
		$query .= ", PROVINCIA = '".$provincia."'";
		$query .= ", PAIS = '".$pais."'";
		$query .= ", TELEFONO = '".$telefono."'";
		$query .= ", FAX = '".$fax."'";
		$query .= ", MAIL = '".$mail."'";
		$query .= ", DELEGACION = '".$delegacion."'";
		$query .= ", RESPONSABLE = '".$responsable."'";
		$query .= ", CARGO_RESPONSABLE = '".$cargo_responsable."'";
		$query .= ", USUARIO_WEB = '".$usuario_web."'";
		$query .= ", PASSWORD_WEB = '".$password_web."'";
		$query .= ", SITUACION = '".$situacion."'";
		$query .= ", OBSERVACIONES = '".$observaciones."'";
		$query .= ", NOMBRE_FISCAL = '".$nombre_fiscal."'";
		$query .= ", DIRECCION_FISCAL = '".$direccion_fiscal."'";
		$query .= ", LOCALIDAD_FISCAL = '".$localidad_fiscal."'";
		$query .= ", CODIGO_POSTAL_FISCAL = '".$codigo_postal_fiscal."'";
		$query .= ", PROVINCIA_FISCAL = '".$provincia_fiscal."'";
		$query .= ", PAIS_FISCAL = '".$pais_fiscal."'";
		$query .= ", SWIFT = '".$swift."'";
		$query .= ", CC_IBAN = '".$cc_iban."'";
		$query .= ", NOMBRE_BANCO = '".$nombre_banco."'";
		$query .= ", DIRECCION_BANCO = '".$direccion_banco."'";
		$query .= ", MAIL_CONTABILIDAD = '".$mail_contabilidad."'";
		$query .= ", FACTURACION = '".$facturacion."'";
		$query .= " WHERE ID = '".$id."' AND OFICINA = '".$oficina."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($id, $oficina){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_oficinas WHERE ID = '".$id."' AND OFICINA = '".$oficina."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($id,$oficina,$cif,$cic, $visible, $direccion,$localidad,$codigo_postal,$provincia,$pais,$telefono,$fax,$mail,$delegacion,$responsable,$cargo_responsable,
		$usuario_web, $password_web, $situacion,$observaciones,$nombre_fiscal,$direccion_fiscal,$localidad_fiscal,$codigo_postal_fiscal,$provincia_fiscal,$pais_fiscal,$swift,	$cc_iban,$nombre_banco,	$direccion_banco,$mail_contabilidad, $facturacion){

		$conexion = $this ->Conexion;


		$query = "INSERT INTO hit_oficinas (ID,OFICINA,SUBOFICINA,CIF,CIC,VISIBLE,DIRECCION,LOCALIDAD,CODIGO_POSTAL,PROVINCIA,PAIS,TELEFONO,FAX,MAIL,DELEGACION,RESPONSABLE,CARGO_RESPONSABLE,USUARIO_WEB,
		PASSWORD_WEB,SITUACION,	OBSERVACIONES,NOMBRE_FISCAL,DIRECCION_FISCAL,LOCALIDAD_FISCAL,CODIGO_POSTAL_FISCAL,PROVINCIA_FISCAL,PAIS_FISCAL,SWIFT,CC_IBAN,NOMBRE_BANCO,
		DIRECCION_BANCO,MAIL_CONTABILIDAD,FACTURACION) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$oficina."',"; 
		$query .= "0,";
		$query .= "'".$cif."',";
		$query .= "'".$cic."',";
		$query .= "'".$visible."',";
		$query .= "'".$direccion."',";
		$query .= "'".$localidad."',";
		$query .= "'".$codigo_postal."',";
		$query .= "'".$provincia."',";
		$query .= "'".$pais."',";
		$query .= "'".$telefono."',";
		$query .= "'".$fax."',";
		$query .= "'".$mail."',";
		$query .= "'".$delegacion."',";
		$query .= "'".$responsable."',";
		$query .= "'".$cargo_responsable."',";
		$query .= "'".$usuario_web."',"; 
		$query .= "'".$password_web."',";
		$query .= "'".$situacion."',";
		$query .= "'".$observaciones."',";
		$query .= "'".$nombre_fiscal."',";
		$query .= "'".$direccion_fiscal."',";
		$query .= "'".$localidad_fiscal."',";
		$query .= "'".$codigo_postal_fiscal."',";
		$query .= "'".$provincia_fiscal."',";
		$query .= "'".$pais_fiscal."',";
		$query .= "'".$swift."',";
		$query .= "'".$cc_iban."',";
		$query .= "'".$nombre_banco."',";
		$query .= "'".$direccion_banco."',";
		$query .= "'".$mail_contabilidad."',";
		$query .= "'".$facturacion."')";


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
	function clsOficinas($conexion, $filadesde, $usuario, $buscar_codigo, $buscar_nombre,$buscar_oficina,$buscar_telefono,$buscar_email){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_codigo = $buscar_codigo;
		$this->Buscar_nombre = $buscar_nombre;
		$this->Buscar_oficina = $buscar_oficina;
		$this->Buscar_telefono = $buscar_telefono;
		$this->Buscar_email = $buscar_email;
	}
}

?>