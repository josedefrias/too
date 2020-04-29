<?php

class clsReservas_abonos_cargos{

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
		$buscar_localizador = $this ->Buscar_localizador;
		$buscar_salida_desde = $this ->Buscar_salida_desde;
		$buscar_salida_hasta = $this ->Buscar_salida_hasta;
		$buscar_emision_desde = $this ->Buscar_emision_desde;
		$buscar_emision_hasta = $this ->Buscar_emision_hasta;
		$buscar_tipo = $this ->Buscar_tipo;

		
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_salida_desde != null && $buscar_salida_hasta != null){
			$fechemis = " and ab.fecha_emision between '".date("Y-m-d",strtotime($buscar_emision_desde))."' and '".date("Y-m-d",strtotime($buscar_emision_hasta))."'";
			$tip = " AND ab.tipo = '".$buscar_tipo."'";	
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_emision_desde != null && $buscar_emision_hasta != null){
				$CADENA_BUSCAR .= $fechemis;	
			}
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tip;	
			}
		}elseif($buscar_emision_desde != null && $buscar_emision_hasta != null){
			$tip = " AND ab.tipo = '".$buscar_tipo."'";	
			$CADENA_BUSCAR = " and ab.fecha_emision between '".date("Y-m-d",strtotime($buscar_emision_desde))."' and '".date("Y-m-d",strtotime($buscar_emision_hasta))."'";
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tip;	
			}
		}elseif($buscar_tipo != null){
			$CADENA_BUSCAR = " AND ab.tipo = '".$buscar_tipo."'";		
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}

		$resultado =$conexion->query("select ab.localizador, ab.numero, 
										DATE_FORMAT(r.fecha_salida, '%d-%m-%Y') AS fecha_salida,
										ab.tipo, ab.importe, ab.detalle, 
										ab.factura,  
										DATE_FORMAT(ab.fecha_emision, '%d-%m-%Y') AS fecha_emision,
										ab.usuario
										from hit_reservas r, hit_reservas_abonos_cargos ab
										where
											r.LOCALIZADOR = ab.LOCALIZADOR ".$CADENA_BUSCAR." ORDER BY ab.localizador, ab.numero");
		//echo($CADENA_BUSCAR);
		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_ABONOS_CARGOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$reservas_abonos_cargos = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['localizador'] == ''){
				break;
			}
			array_push($reservas_abonos_cargos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $reservas_abonos_cargos;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_ABONOS_CARGOS' AND USUARIO = '".$Usuario."'");
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

		$buscar_localizador = $this ->Buscar_localizador;
		$buscar_salida_desde = $this ->Buscar_salida_desde;
		$buscar_salida_hasta = $this ->Buscar_salida_hasta;
		$buscar_emision_desde = $this ->Buscar_emision_desde;
		$buscar_emision_hasta = $this ->Buscar_emision_hasta;
		$buscar_tipo = $this ->Buscar_tipo;
	
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_salida_desde != null && $buscar_salida_hasta != null){
			$fechemis = " and ab.fecha_emision between '".date("Y-m-d",strtotime($buscar_emision_desde))."' and '".date("Y-m-d",strtotime($buscar_emision_hasta))."'";
			$tip = " AND ab.tipo = '".$buscar_tipo."'";	
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_emision_desde != null && $buscar_emision_hasta != null){
				$CADENA_BUSCAR .= $fechemis;	
			}
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tip;	
			}
		}elseif($buscar_emision_desde != null && $buscar_emision_hasta != null){
			$tip = " AND ab.tipo = '".$buscar_tipo."'";		
			$CADENA_BUSCAR = " and ab.fecha_emision between '".date("Y-m-d",strtotime($buscar_emision_desde))."' and '".date("Y-m-d",strtotime($buscar_emision_hasta))."'";
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tip;	
			}
		}elseif($buscar_tipo != null){
			$CADENA_BUSCAR = " AND ab.tipo = '".$buscar_tipo."'";	
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * from hit_reservas r, hit_reservas_abonos_cargos ab
										where
											r.LOCALIZADOR = ab.LOCALIZADOR '.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_ABONOS_CARGOS' AND USUARIO = '".$Usuario."'");
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
		$resultadoc =$conexion->query('SELECT * FROM hit_reservas_abonos_cargos');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_ABONOS_CARGOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($localizador, $numero, $tipo, $importe, $detalle){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_reservas_abonos_cargos SET ";
		$query .= " TIPO = '".$tipo."'";
		$query .= ", IMPORTE = '".$importe."'";
		$query .= ", DETALLE = '".$detalle."'";
		$query .= " WHERE LOCALIZADOR = '".$localizador."' AND NUMERO = '".$numero."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($localizador, $numero){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_reservas_abonos_cargos WHERE LOCALIZADOR = '".$localizador."' AND NUMERO = '".$numero."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($localizador, $tipo, $importe, $detalle){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		$datos_factura =$conexion->query("select max(factura) + 1 factura from hit_reservas_abonos_cargos");
		$odatos_factura = $datos_factura->fetch_assoc();
		$factura = $odatos_factura['factura'];	

		$datos_numero =$conexion->query("select count(*) + 1 numero from hit_reservas_abonos_cargos where localizador = '".$localizador."'");
		$odatos_numero = $datos_numero->fetch_assoc();
		$numero = $odatos_numero['numero'];			
	
		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_reservas_abonos_cargos (LOCALIZADOR, NUMERO, TIPO,IMPORTE,DETALLE,FACTURA,FECHA_EMISION,USUARIO) VALUES (";
		$query .= "'".$localizador."',";
		$query .= "'".$numero."',";
		$query .= "'".$tipo."',";
		$query .= "'".$importe."',";
		$query .= "'".$detalle."',";
		$query .= "'".$factura."',";
		$query .= "CURDATE(),";
		$query .= "'".$Usuario."')";

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
	function clsReservas_abonos_cargos($conexion, $filadesde, $usuario, $buscar_localizador, $buscar_salida_desde, $buscar_salida_hasta, $buscar_emision_desde, $buscar_emision_hasta,$buscar_tipo){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_localizador = $buscar_localizador;
		$this->Buscar_salida_desde = $buscar_salida_desde;
		$this->Buscar_salida_hasta = $buscar_salida_hasta;
		$this->Buscar_emision_desde = $buscar_emision_desde;
		$this->Buscar_emision_hasta = $buscar_emision_hasta;
		$this->Buscar_tipo = $buscar_tipo;
	}
}

?>