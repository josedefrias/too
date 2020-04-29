<?php

require 'clases/teletipos_visualizar.cls.php';

class clsTeletipos_destinatarios{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var $buscar_nombre;

//--------------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DESTINATARIOS--------------
//--------------------------------------------------------------------------

	function Cargar_destinatarios($id, $filadesde_destinatarios, $buscar_provincia_arr, $buscar_grupo_gestion, $buscar_minorista, $buscar_estado){

		//Conertimos array provincias en cadena
		$buscar_provincia = '(';
		for ($i=0;$i<count($buscar_provincia_arr);$i++)    
		{     
			if($i==0){
				$buscar_provincia .= "'".@$buscar_provincia_arr[$i]."'";
			}else{
				$buscar_provincia .= ",'".@$buscar_provincia_arr[$i]."'";
			}
			   
		} 
		$buscar_provincia .= ')';
		if($buscar_provincia == "()"){
			$buscar_provincia = "('')";
		}

		//echo($buscar_provincia);

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

		if($buscar_provincia != null and $buscar_provincia != "('')"){
			$ges= " AND g.ID = '".$buscar_grupo_gestion."'";
			$min = " AND m.ID = '".$buscar_minorista."'";
			$est = " AND e.ESTADO = '".$buscar_estado."'";
			$CADENA_BUSCAR = " and e.ID_CONTENIDO = '".$id."' and p.CODIGO in ".$buscar_provincia;
			if($buscar_grupo_gestion != null){
				$CADENA_BUSCAR .= $ges;	
			}
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $min;	
			}
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $est;	
			}
		}elseif($buscar_grupo_gestion != null){
			$min = " AND m.ID = '".$buscar_minorista."'";
			$est = " AND e.ESTADO = '".$buscar_estado."'";
			$CADENA_BUSCAR = " and e.ID_CONTENIDO = '".$id."' AND g.ID = '".$buscar_grupo_gestion."'";
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $min;	
			}
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $est;	
			}
		}elseif($buscar_minorista != null){
			$est = " AND e.ESTADO = '".$buscar_estado."'";
			$CADENA_BUSCAR = " and e.ID_CONTENIDO = '".$id."' AND m.ID = '".$buscar_minorista."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $est;	
			}
  		}else{
  			$est = " AND e.ESTADO = '".$buscar_estado."'";
			$CADENA_BUSCAR = " and e.ID_CONTENIDO = '".$id."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $est;	
			}
		}		

		
		$resultado =$conexion->query("SELECT e.ID id_envio, p.NOMBRE provincia, g.NOMBRE grupo, m.NOMBRE agencia, o.LOCALIDAD localidad, o.DIRECCION direccion, 
																 e.REMITENTE_EMAIL email, 
																 e.ESTADO estado, 
																 DATE_FORMAT(e.FECHA_GENERACION, '%d-%m-%Y  %H:%i:%s') AS fecha_generacion,
																 contenido,
																 DATE_FORMAT(e.FECHA_ENVIO, '%d-%m-%Y  %H:%i:%s') AS fecha_envio
														from hit_envios e, hit_oficinas o, hit_minoristas m, hit_grupos_gestion g, hit_provincias p
														where 
															e.TIPO = 'TEL'
															and e.REMITENTE_TIPO = 'AGE'
															and e.REMITENTE_ID1 = o.ID
															and e.REMITENTE_ID2 = o.OFICINA
															and o.ID = m.ID
															and m.GRUPO_GESTION = g.ID
															and o.PROVINCIA = p.CODIGO
															".$CADENA_BUSCAR."
														order by p.NOMBRE, g.NOMBRE, m.NOMBRE, o.LOCALIDAD, o.DIRECCION");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//echo($id."-".$filadesde_destinatarios."-".$buscar_hotel);
		
		//echo($CADENA_BUSCAR);
		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_DESTINATARIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$destinatarios = array();
		for ($num_fila = $filadesde_destinatarios-1; $num_fila <= $filadesde_destinatarios + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			if($fila['id_envio'] == ''){
				break;
			}
			array_push($destinatarios,$fila);
		}

		
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $destinatarios;											
	}


	/*function Cargar_lineas_nuevas_destinatarios(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_DESTINATARIOS' AND USUARIO = '".$Usuario."'");
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
	}*/


	function Cargar_combo_selector_destinatarios($id, $filadesde_destinatarios, $buscar_provincia_arr, $buscar_grupo_gestion, $buscar_minorista, $buscar_estado){

		//Conertimos array provincias en cadena
		$buscar_provincia = '(';
		for ($i=0;$i<count($buscar_provincia_arr);$i++)    
		{     
			if($i==0){
				$buscar_provincia .= "'".@$buscar_provincia_arr[$i]."'";
			}else{
				$buscar_provincia .= ",'".@$buscar_provincia_arr[$i]."'";
			}
			   
		} 
		$buscar_provincia .= ')';
		if($buscar_provincia == "()"){
			$buscar_provincia = "('')";
		}

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		if($buscar_provincia != null and $buscar_provincia != "('')"){
			$ges= " AND g.ID = '".$buscar_grupo_gestion."'";
			$min = " AND m.ID = '".$buscar_minorista."'";
			$est = " AND e.ESTADO = '".$buscar_estado."'";
			$CADENA_BUSCAR = " and e.ID_CONTENIDO = '".$id."' and p.CODIGO in ".$buscar_provincia;
			if($buscar_grupo_gestion != null){
				$CADENA_BUSCAR .= $ges;	
			}
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $min;	
			}
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $est;	
			}
		}elseif($buscar_grupo_gestion != null){
			$min = " AND m.ID = '".$buscar_minorista."'";
			$est = " AND e.ESTADO = '".$buscar_estado."'";
			$CADENA_BUSCAR = " and e.ID_CONTENIDO = '".$id."' AND g.ID = '".$buscar_grupo_gestion."'";
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $min;	
			}
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $est;	
			}
		}elseif($buscar_minorista != null){
			$est = " AND e.ESTADO = '".$buscar_estado."'";
			$CADENA_BUSCAR = " and e.ID_CONTENIDO = '".$id."' AND m.ID = '".$buscar_minorista."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $est;	
			}
  		}else{
  			$est = " AND e.ESTADO = '".$buscar_estado."'";
			$CADENA_BUSCAR = " and e.ID_CONTENIDO = '".$id."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $est;	
			}
		}	


		$resultadoc =$conexion->query("SELECT *
														from hit_envios e, hit_oficinas o, hit_minoristas m, hit_grupos_gestion g, hit_provincias p
														where 
															e.TIPO = 'TEL'
															and e.REMITENTE_TIPO = 'AGE'
															and e.REMITENTE_ID1 = o.ID
															and e.REMITENTE_ID2 = o.OFICINA
															and o.ID = m.ID
															and m.GRUPO_GESTION = g.ID
															and o.PROVINCIA = p.CODIGO
															".$CADENA_BUSCAR);
	
												//ESPCIFICO: nombre tabla
		//echo($CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_DESTINATARIOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_destinatarios($filadesde_destinatarios, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_envios');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_DESTINATARIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $num_filas->fetch_assoc();

		if($boton == 1){
			$selector = 1;
		}elseif($boton == 2){
			$selector = $filadesde_destinatarios - $Nfilas['LINEAS_MODIFICACION'];
		}elseif($boton == 3){
			$selector = $filadesde_destinatarios + $Nfilas['LINEAS_MODIFICACION'];		
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

	function Modificar_destinatarios($id_envio, $email, $estado){
		
		$conexion = $this ->Conexion;
		$query = "UPDATE hit_envios SET ";
		$query .= " REMITENTE_EMAIL = '".$email."'";
		$query .= ", ESTADO = '".$estado."'";
		$query .= " WHERE ID = '".$id_envio."'";

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

	function Borrar_destinatarios($id_envio){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_envios WHERE ESTADO <> 'OK' AND  ID = '".$id_envio."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	/*function Insertar_destinatarios($id, $buscar_provincia, $buscar_grupo_gestion, $buscar_minorista){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_teletipos_destinatarios (ID, HOTEL, REGIMEN, PRECIO) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$hotel."',";
		$query .= "'".$regimen."',";
		$query .= "'".$precio."')";

		
		
		
		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}*/

	function Anadir_Grupo_destinatarios($id, $buscar_provincia, $buscar_grupo_gestion, $buscar_minorista, $ciudad_salida){

		$conexion = $this ->Conexion;


			$datos_teletipo =$conexion->query("SELECT formato FROM hit_teletipos WHERE ID = '".$id."'");
			$odatos_teletipo = $datos_teletipo->fetch_assoc();
			$formato = $odatos_teletipo['formato'];

			//Cargamos el contenido html en la tabla de contenidos y asignamos ese codigo a cada linea de envio
			$ClaseVisualizar = new clsTeletipos_visualizar($conexion, 1, '', $id, '', '');
			$contenido = $ClaseVisualizar->carga_html_oferta($id, $formato, $ciudad_salida,'');	
			$contenido = trim($contenido);
			$contenido = preg_replace("/\r\n+|\r+|\n+|\t+/i", " ", $contenido);
			$contenido_html = str_replace("'","\"",$contenido);

 

			$query = "INSERT INTO hit_envios_contenido (CONTENIDO) VALUES (";
			$query .= "'".$contenido_html."')";

			$resultadoi =$conexion->query($query);
			if ($resultadoi == FALSE){
				$respuesta = 'No se ha podido insertar el contenido en la tabla de contenidos. '.$conexion->error;
			}else{
				$respuesta = 'OK';
				$datos_contenido =$conexion->query("SELECT max(id) max_id FROM hit_envios_contenido");
				$odatos_contenido = $datos_contenido->fetch_assoc();
				$contenido_id = $odatos_contenido['max_id'];
			}

			//echo($contenido_html );

		for ($i=0;$i<count($buscar_provincia);$i++)    
		{			
			//echo($buscar_provincia[$i]);
			$expandir = "CALL `TELETIPOS_INSERTA_REMITENTES`('".$id."', '".$buscar_provincia[$i]."', '".$buscar_grupo_gestion."', '".$buscar_minorista."', '".$contenido_id."')";
			$resultadoinsertar =$conexion->query($expandir);
				//echo($expandir);
			if ($resultadoinsertar == FALSE){
				$respuesta = 'No se han podido añadir los remitentes seleccionados. '.$conexion->error;
			}else{
				$respuesta = 'OK';
			}	
		}	
		return $respuesta;											
	}

	function Borrar_Grupo_destinatarios($id, $buscar_provincia_arr, $buscar_grupo_gestion, $buscar_minorista){

		$conexion = $this ->Conexion;

		//Conertimos array provincias en cadena
		$buscar_provincia = '(';
		for ($i=0;$i<count($buscar_provincia_arr);$i++)    
		{     
			if($i==0){
				$buscar_provincia .= "'".@$buscar_provincia_arr[$i]."'";
			}else{
				$buscar_provincia .= ",'".@$buscar_provincia_arr[$i]."'";
			}

		} 
		$buscar_provincia .= ')';
		if($buscar_provincia == "()"){
			$buscar_provincia = "('')";
		}

		if($buscar_provincia != null and $buscar_provincia != "('')"){
			$ges= " AND grupo_gestion = '".$buscar_grupo_gestion."'";
			$min = " AND remitente_id1 = '".$buscar_minorista."'";
			$CADENA_BUSCAR = " where ESTADO <> 'OK' AND ID_CONTENIDO = '".$id."' and provincia in ".$buscar_provincia;
			if($buscar_grupo_gestion != null){
				$CADENA_BUSCAR .= $ges;	
			}
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $min;	
			}
		}elseif($buscar_grupo_gestion != null){
			$min = " AND remitente_id1 = '".$buscar_minorista."'";
			$CADENA_BUSCAR = " where ESTADO <> 'OK' AND ID_CONTENIDO = '".$id."' AND grupo_gestion = '".$buscar_grupo_gestion."'";
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $min;	
			}
		}elseif($buscar_minorista != null){
			$CADENA_BUSCAR = " where ESTADO <> 'OK' AND ID_CONTENIDO = '".$id."' AND remitente_id1 = '".$buscar_minorista."'";
  		}else{
			$CADENA_BUSCAR = " where ESTADO <> 'OK' AND ID_CONTENIDO = '".$id."'";
		}
		
		$query = "DELETE FROM hit_envios ".$CADENA_BUSCAR;

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}
	
	function Activar_Grupo_destinatarios($id, $buscar_provincia_arr, $buscar_grupo_gestion, $buscar_minorista){

		$conexion = $this ->Conexion;

		//Conertimos array provincias en cadena
		$buscar_provincia = '(';
		for ($i=0;$i<count($buscar_provincia_arr);$i++)    
		{     
			if($i==0){
				$buscar_provincia .= "'".@$buscar_provincia_arr[$i]."'";
			}else{
				$buscar_provincia .= ",'".@$buscar_provincia_arr[$i]."'";
			}
		} 
		$buscar_provincia .= ')';
		if($buscar_provincia == "()"){
			$buscar_provincia = "('')";
		}

		if($buscar_provincia != null and $buscar_provincia != "('')"){
			$ges= " AND grupo_gestion = '".$buscar_grupo_gestion."'";
			$min = " AND remitente_id1 = '".$buscar_minorista."'";
			$CADENA_BUSCAR = " where ESTADO not in ('OK','EA','ER') AND ID_CONTENIDO = '".$id."' and provincia in ".$buscar_provincia;
			if($buscar_grupo_gestion != null){
				$CADENA_BUSCAR .= $ges;	
			}
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $min;	
			}
		}elseif($buscar_grupo_gestion != null){
			$min = " AND remitente_id1 = '".$buscar_minorista."'";
			$CADENA_BUSCAR = " where ESTADO not in ('OK','EA','ER') AND ID_CONTENIDO = '".$id."' AND grupo_gestion = '".$buscar_grupo_gestion."'";
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $min;	
			}
		}elseif($buscar_minorista != null){
			$CADENA_BUSCAR = " where ESTADO not in ('OK','EA','ER') AND ID_CONTENIDO = '".$id."' AND remitente_id1 = '".$buscar_minorista."'";
  		}else{
			$CADENA_BUSCAR = " where ESTADO not in ('OK','EA','ER') AND ID_CONTENIDO = '".$id."'";
		}
		
		$query = "UPDATE hit_envios set ESTADO = 'EA' ".$CADENA_BUSCAR;

		//echo($query);

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Desctivar_Grupo_destinatarios($id, $buscar_provincia_arr, $buscar_grupo_gestion, $buscar_minorista){

		$conexion = $this ->Conexion;

		//Conertimos array provincias en cadena
		$buscar_provincia = '(';
		for ($i=0;$i<count($buscar_provincia_arr);$i++)    
		{     
			if($i==0){
				$buscar_provincia .= "'".@$buscar_provincia_arr[$i]."'";
			}else{
				$buscar_provincia .= ",'".@$buscar_provincia_arr[$i]."'";
			}
		} 
		$buscar_provincia .= ')';
		if($buscar_provincia == "()"){
			$buscar_provincia = "('')";
		}

		if($buscar_provincia != null and $buscar_provincia != "('')"){
			$ges= " AND grupo_gestion = '".$buscar_grupo_gestion."'";
			$min = " AND remitente_id1 = '".$buscar_minorista."'";
			$CADENA_BUSCAR = " where ESTADO in ('EA') AND ID_CONTENIDO = '".$id."' and provincia in ".$buscar_provincia;
			if($buscar_grupo_gestion != null){
				$CADENA_BUSCAR .= $ges;	
			}
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $min;	
			}
		}elseif($buscar_grupo_gestion != null){
			$min = " AND remitente_id1 = '".$buscar_minorista."'";
			$CADENA_BUSCAR = " where ESTADO in ('EA') AND ID_CONTENIDO = '".$id."' AND grupo_gestion = '".$buscar_grupo_gestion."'";
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $min;	
			}
		}elseif($buscar_minorista != null){
			$CADENA_BUSCAR = " where ESTADO in ('EA') AND ID_CONTENIDO = '".$id."' AND remitente_id1 = '".$buscar_minorista."'";
  		}else{
			$CADENA_BUSCAR = " where ESTADO in ('EA') AND ID_CONTENIDO = '".$id."'";
		}
		
		$query = "UPDATE hit_envios set ESTADO = 'ED' ".$CADENA_BUSCAR;

		//echo($query);

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsTeletipos_destinatarios($conexion, $filadesde, $usuario, $buscar_id, $buscar_nombre, $buscar_destino){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id= $buscar_id;
		$this->Buscar_nombre= $buscar_nombre;
		$this->Buscar_destino = $buscar_destino;
	}		
	
}

?>