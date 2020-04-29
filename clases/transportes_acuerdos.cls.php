<?php

class clsAcuerdos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_cia;
	var	$buscar_acuerdo;
	var	$buscar_subacuerdo;
	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------
	function Cargar($recuperacia,$recuperaacuerdo,$recuperasubacuerdo,$recuperatipo,$recuperasituacion,$recuperafecha_desde,$recuperafecha_hasta,$recuperadivisa,$recuperapago_tipo,$recuperapago_plazo,$recuperapago_forma,$recuperaemision,$recuperacorresponsal,$recuperadescripcion,$recuperaobservaciones,$recuperagds, $recuperagds_rf, $recuperagds_os, $depuracion_1, $depuracion_2, $depuracion_final){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_cia = $this ->Buscar_cia;
		$buscar_acuerdo = $this ->Buscar_acuerdo;
		$buscar_subacuerdo = $this ->Buscar_subacuerdo;

		if($buscar_cia != null){
			if($buscar_acuerdo != null){
				if($buscar_subacuerdo != null){
					$CADENA_BUSCAR = " cia = '".$buscar_cia."' AND ACUERDO = '".$buscar_acuerdo."' AND SUBACUERDO = '".$buscar_subacuerdo."'";
				}else{
					$CADENA_BUSCAR = " cia = '".$buscar_cia."' AND ACUERDO = '".$buscar_acuerdo."'";
				}		
			}else{
				$CADENA_BUSCAR = " cia = '".$buscar_cia."'";
			}
		}elseif($buscar_acuerdo != null){
			if($buscar_subacuerdo != null){
				$CADENA_BUSCAR = " acuerdo '%".$buscar_acuerdo."' AND SUBACUERDO = '".$buscar_subacuerdo."'";
			}else{
				$CADENA_BUSCAR = " acuerdo '%".$buscar_acuerdo."%'";
			}	
		}else{
			$CADENA_BUSCAR = " cia = 'xx' ";
			//$CADENA_BUSCAR = "";
		}

		$resultado =$conexion->query("SELECT cia,acuerdo,subacuerdo,tipo,situacion,DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde, DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta, divisa, pago_tipo, pago_plazo, pago_forma, emision, corresponsal, descripcion, observaciones, gds, gds_rf, gds_os, depuracion_1, depuracion_2, depuracion_final FROM hit_transportes_acuerdos WHERE ".$CADENA_BUSCAR." ORDER BY cia, acuerdo, subacuerdo");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			//$resultado->close();
			//$conexion->close();
			//exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_ACUERDOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$acuerdos = array();
		if($recuperacia != null){
			$acuerdos[0] = array ("cia" => null, "acuerdo" => $recuperaacuerdo, "subacuerdo" => $recuperasubacuerdo,"tipo" => $recuperatipo, "situacion" => $recuperasituacion, "fecha_desde" => $recuperafecha_desde, "fecha_hasta" => $recuperafecha_hasta, "divisa" => $recuperadivisa, "pago_tipo" => $recuperapago_tipo, "pago_plazo" => $recuperapago_plazo, "pago_forma" => $recuperapago_forma, "emision" => $recuperaemision, "corresponsal" => $recuperacorresponsal, "descripcion" => $recuperadescripcion, "observaciones" => $recuperaobservaciones, "gds" => $recuperagds, "gds_rf" => $recuperagds_rf, "gds_os" => $recuperagds_os, "depuracion_1" => $recuperagds_os, "depuracion_2" => $recuperagds_os, "depuracion_final" => $recuperagds_os);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($acuerdos,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $acuerdos;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_ACUERDOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_cia = $this ->Buscar_cia;
		$buscar_acuerdo = $this ->Buscar_acuerdo;
		$buscar_subacuerdo = $this ->Buscar_subacuerdo;

		if($buscar_cia != null){
			if($buscar_acuerdo != null){
				if($buscar_subacuerdo != null){
					$CADENA_BUSCAR = " cia = '".$buscar_cia."' AND ACUERDO = '".$buscar_acuerdo."' AND SUBACUERDO = '".$buscar_subacuerdo."'";
				}else{
					$CADENA_BUSCAR = " cia = '".$buscar_cia."' AND ACUERDO = '".$buscar_acuerdo."'";
				}		
			}else{
				$CADENA_BUSCAR = " cia = '".$buscar_cia."'";
			}
		}elseif($buscar_acuerdo != null){
			if($buscar_subacuerdo != null){
				$CADENA_BUSCAR = " acuerdo '%".$buscar_acuerdo."' AND SUBACUERDO = '".$buscar_subacuerdo."'";
			}else{
				$CADENA_BUSCAR = " acuerdo '%".$buscar_acuerdo."%'";
			}	
		}else{
			$CADENA_BUSCAR = " cia = 'xx' ";
			//$CADENA_BUSCAR = "";
		}									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes_acuerdos WHERE'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_ACUERDOS' AND USUARIO = '".$Usuario."'");
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
		
		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_cia = $this ->Buscar_cia;
		$buscar_acuerdo = $this ->Buscar_acuerdo;
		$buscar_subacuerdo = $this ->Buscar_subacuerdo;

		if($buscar_cia != null){
			if($buscar_acuerdo != null){
				if($buscar_subacuerdo != null){
					$CADENA_BUSCAR = " cia = '".$buscar_cia."' AND ACUERDO = '".$buscar_acuerdo."' AND SUBACUERDO = '".$buscar_subacuerdo."'";
				}else{
					$CADENA_BUSCAR = " cia = '".$buscar_cia."' AND ACUERDO = '".$buscar_acuerdo."'";
				}		
			}else{
				$CADENA_BUSCAR = " cia = '".$buscar_cia."'";
			}
		}elseif($buscar_acuerdo != null){
			if($buscar_subacuerdo != null){
				$CADENA_BUSCAR = " acuerdo '%".$buscar_acuerdo."' AND SUBACUERDO = '".$buscar_subacuerdo."'";
			}else{
				$CADENA_BUSCAR = " acuerdo '%".$buscar_acuerdo."%'";
			}	
		}else{
			$CADENA_BUSCAR = " cia = 'xx' ";
			//$CADENA_BUSCAR = "";
		}									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes_acuerdos WHERE'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/
		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_ACUERDOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($cia,$acuerdo,$subacuerdo,$tipo,$situacion,$fecha_desde,$fecha_hasta,$divisa,$pago_tipo,$pago_plazo,$pago_forma,$emision,$corresponsal,$descripcion,$observaciones,$gds,$gds_rf,$gds_os,$depuracion_1,$depuracion_2,$depuracion_final){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_transportes_acuerdos SET ";
		$query .= " TIPO = '".$tipo."'";
		$query .= ", SITUACION = '".$situacion."'";
		$query .= ", FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= ", DIVISA = '".$divisa."'";
		$query .= ", PAGO_TIPO = '".$pago_tipo."'";
		$query .= ", PAGO_PLAZO = '".$pago_plazo."'";
		$query .= ", PAGO_FORMA = '".$pago_forma."'";
		$query .= ", EMISION = '".$emision."'";
		$query .= ", CORRESPONSAL = '".$corresponsal."'";
		$query .= ", DESCRIPCION = '".$descripcion."'";
		$query .= ", OBSERVACIONES = '".$observaciones."'";
		$query .= ", GDS = '".$gds."'";
		$query .= ", GDS_RF = '".$gds_rf."'";
		$query .= ", GDS_OS = '".$gds_os."'";
		$query .= ", DEPURACION_1 = '".$depuracion_1."'";
		$query .= ", DEPURACION_2 = '".$depuracion_2."'";
		$query .= ", DEPURACION_FINAL = '".$depuracion_final."'";
		$query .= " WHERE CIA = '".$cia."' AND ACUERDO = ".$acuerdo. " AND SUBACUERDO = ".$subacuerdo;


		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($cia, $acuerdo, $subacuerdo){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_transportes_acuerdos WHERE CIA = '".$cia."' AND ACUERDO = ".$acuerdo." AND SUBACUERDO = ".$subacuerdo;

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($cia,$acuerdo,$subacuerdo,$tipo,$situacion,$fecha_desde,$fecha_hasta,$divisa,$pago_tipo,$pago_plazo,$pago_forma,$emision,$corresponsal,$descripcion,$observaciones,$gds,$gds_rf,$gds_os,$depuracion_1,$depuracion_2,$depuracion_final){

		$conexion = $this ->Conexion;

		$query = "INSERT INTO hit_transportes_acuerdos(CIA,ACUERDO,SUBACUERDO,TIPO,SITUACION,FECHA_DESDE,FECHA_HASTA,DIVISA,PAGO_TIPO,PAGO_PLAZO,PAGO_FORMA,EMISION,CORRESPONSAL,DESCRIPCION,OBSERVACIONES,GDS,GDS_RF,GDS_OS, DEPURACION_1, DEPURACION_2, DEPURACION_FINAL) VALUES (";
		$query .= "'".$cia."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$subacuerdo."',";
		$query .= "'".$tipo."',";
		$query .= "'".$situacion."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta))."',";
		$query .= "'".$divisa."',";
		$query .= "'".$pago_tipo."',";
		$query .= "'".$pago_plazo."',";
		$query .= "'".$pago_forma."',";
		$query .= "'".$emision."',";
		$query .= "'".$corresponsal."',";
		$query .= "'".$descripcion."',";
		$query .= "'".$observaciones."',";
		$query .= "'".$gds."',";
		$query .= "'".$gds_rf."',";
		$query .= "'".$gds_os."',";
		$query .= "'".$depuracion_1."',";
		$query .= "'".$depuracion_2."',";
		$query .= "'".$depuracion_final."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Expandir_cupos($cia, $acuerdo, $subacuerdo){

		$conexion = $this ->Conexion;

		//obtenemos las lineas que hay que expndir segun los trayectos
		$resultado =$conexion->query("select distinct a.CIA, a.ACUERDO, a.SUBACUERDO, t.ORIGEN, t.DESTINO, a.FECHA_DESDE, a.FECHA_HASTA, t.DIAS, t.VUELO, t.HORA_SALIDA, t.HORA_LLEGADA, t.CUPO_INICIAL, a.DEPURACION_1, a.DEPURACION_2, a.DEPURACION_FINAL, t.clase_inicial, t.clase_2, t.clase_3, t.clase_4, t.clase_5  from hit_transportes_acuerdos a, hit_transportes_trayectos t where a.CIA = t.CIA and a.ACUERDO = t.ACUERDO and a.SUBACUERDO = t.SUBACUERDO	and a.CIA = '".$cia."'and a.ACUERDO = ".$acuerdo." and a.SUBACUERDO = ".$subacuerdo." order by a.CIA, a.ACUERDO, t.origen");

		if ($resultado == FALSE){
			$respuesta = 'No se han podido expandir los cupos. '.$conexion->error;
		}else{
			while($fila=mysqli_fetch_row($resultado)){
				//echo($fila[0]."-".$fila[1]."-".$fila[2]."-".$fila[3]."-".$fila[4]."-".$fila[5]."-".$fila[6]."-".$fila[7]."-");
				$expandir = "CALL `PROVEEDORES_EXPANDE_CUPOS_TRANSPORTES`('".$fila[0]."', '".$fila[1]."', '".$fila[2]."', '".$fila[3]."', '".$fila[4]."', '".$fila[5]."', '".$fila[6]."', '".$fila[7]."', '".$fila[8]."', '".$fila[9]."', '".$fila[10]."', '".$fila[11]."', '".$fila[12]."', '".$fila[13]."', '".$fila[14]."', '".$fila[15]."', '".$fila[16]."', '".$fila[17]."', '".$fila[18]."', '".$fila[19]."')";
				$resultadoexpandir =$conexion->query($expandir);
					//echo($expandir);
				if ($resultadoexpandir == FALSE){
					$respuesta = 'No se han podido expandir los cupos. '.$conexion->error;
				}else{
					$respuesta = 'OK';
					$expandido =$conexion->query("UPDATE hit_transportes_acuerdos SET SITUACION = 'E' WHERE CIA = '".$cia."' AND ACUERDO = ".$acuerdo. " AND SUBACUERDO = ".$subacuerdo);
				}
			}
		}
		return $respuesta;											
	}

	function Borrar_cupos($cia, $acuerdo, $subacuerdo, $origen, $destino){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_transportes_cupos WHERE PLAZAS_OK = 0 AND CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND SUBACUERDO = '".$subacuerdo."' AND ORIGEN = '".$origen."' AND DESTINO = '".$destino."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE TRAYECTOS---
//--------------------------------------------------------------------

	function Cargar_trayectos($cia, $acuerdo, $subacuerdo, $filadesde, $buscar_origen, $buscar_destino){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

	
		if($buscar_origen != null or $buscar_destino != null ){
			$org = " AND ORIGEN = '".$buscar_origen."'";
			$des = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND subacuerdo = '".$subacuerdo."'";
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $org;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND subacuerdo = '".$subacuerdo."'"; 
  		}


		$resultado =$conexion->query("SELECT origen,destino,vuelo,time_format(hora_salida, '%H:%i') AS hora_salida,time_format(hora_llegada, '%H:%i') AS hora_llegada,desplazamiento_llegada,dias,clave_coste,cupo_inicial,clase_inicial, clase_2 clase_tray_2, clase_3 clase_tray_3, clase_4 clase_tray_4, clase_5 clase_tray_5 FROM hit_transportes_trayectos ".$CADENA_BUSCAR." ORDER BY trayecto");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_TRAYECTOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$trayectos = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['origen'] == ''){
				break;
			}
			array_push($trayectos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $trayectos;											
	}


	function Cargar_lineas_nuevas_trayectos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_TRAYECTOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_trayectos($cia, $acuerdo, $subacuerdo, $filadesde, $buscar_origen, $buscar_destino){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_origen != null or $buscar_destino != null ){
			$org = " AND ORIGEN = '".$buscar_origen."'";
			$des = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND subacuerdo = '".$subacuerdo."'";
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $org;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND subacuerdo = '".$subacuerdo."'"; 
			//$CADENA_BUSCAR = " WHERE CIA = 'IB' AND ACUERDO = 3000 AND SUBACUERDO = 1";   
  		}
									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes_trayectos'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_TRAYECTOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_trayectos($filadesde, $boton, $cia, $acuerdo, $subacuerdo, $buscar_origen, $buscar_destino){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_origen != null or $buscar_destino != null ){
			$org = " AND ORIGEN = '".$buscar_origen."'";
			$des = " AND DESTINO = '".$buscar_destino."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND subacuerdo = '".$subacuerdo."'";
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $org;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND subacuerdo = '".$subacuerdo."'"; 
			//$CADENA_BUSCAR = " WHERE CIA = 'IB' AND ACUERDO = 3000 AND SUBACUERDO = 1";   
  		}
									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes_trayectos'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_TRAYECTOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_trayectos($cia, $acuerdo, $subacuerdo, $origen, $destino, $vuelo, $hora_salida, $hora_llegada, $desplazamiento_llegada, $dias, $clave_coste, $cupo_inicial, $clase_inicial, $clase_tray_2, $clase_tray_3, $clase_tray_4, $clase_tray_5, $origen_old, $destino_old){

		//$fecha2=date("Y-m-d",strtotime($fecha_desde));
		//echo(date("Y-m-d",strtotime($fecha_desde)));

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_transportes_trayectos SET ";
		$query .= " ORIGEN = '".$origen."'";
		$query .= ", DESTINO = '".$destino."'";
		$query .= ", VUELO = '".$vuelo."'";
		$query .= ", HORA_SALIDA = '".$hora_salida."'";
		$query .= ", HORA_LLEGADA = '".$hora_llegada."'";
		$query .= ", DESPLAZAMIENTO_LLEGADA = '".$desplazamiento_llegada."'";
		$query .= ", DIAS = '".$dias."'";
		$query .= ", CLAVE_COSTE = '".$clave_coste."'";
		$query .= ", CUPO_INICIAL = '".$cupo_inicial."'";
		$query .= ", CLASE_INICIAL = '".$clase_inicial."'";
		$query .= ", CLASE_2 = '".$clase_tray_2."'";
		$query .= ", CLASE_3 = '".$clase_tray_3."'";
		$query .= ", CLASE_4 = '".$clase_tray_4."'";
		$query .= ", CLASE_5 = '".$clase_tray_5."'";
		$query .= " WHERE CIA = '".$cia."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND SUBACUERDO = '".$subacuerdo."'";
		$query .= " AND ORIGEN = '".$origen_old."'";
		$query .= " AND DESTINO = '".$destino_old."'";

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

	function Borrar_trayectos($cia, $acuerdo, $subacuerdo, $origen, $destino){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_transportes_trayectos WHERE CIA = '".$cia."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND SUBACUERDO= '".$subacuerdo."'";
		$query .= " AND ORIGEN = '".$origen."'";
		$query .= " AND DESTINO = '".$destino."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_trayectos($cia, $acuerdo, $subacuerdo, $origen, $destino, $vuelo, $hora_salida, $hora_llegada, $desplazamiento_llegada, $dias, $clave_coste, $cupo_inicial, $clase_inicial, $clase_tray_2, $clase_tray_3, $clase_tray_4, $clase_tray_5){

		//ECHO($clase_inicial.'-'.$clase_2.'-'.$clase_3.'-'.$clase_4.'-'.$clase_5);

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_transportes_trayectos (CIA,ACUERDO,SUBACUERDO,ORIGEN,DESTINO,VUELO,HORA_SALIDA,HORA_LLEGADA,DESPLAZAMIENTO_LLEGADA,DIAS,CLAVE_COSTE,CUPO_INICIAL,CLASE_INICIAL,CLASE_2,CLASE_3,CLASE_4,CLASE_5) VALUES (";
		$query .= "'".$cia."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$subacuerdo."',";
		$query .= "'".$origen."',";
		$query .= "'".$destino."',";
		$query .= "'".$vuelo."',";
		$query .= "'".$hora_salida."',";
		$query .= "'".$hora_llegada."',";
		$query .= "'".$desplazamiento_llegada."',";
		$query .= "'".$dias."',";
		$query .= "'".$clave_coste."',";
		$query .= "'".$cupo_inicial."',";
		$query .= "'".$clase_inicial."',";
		$query .= "'".$clase_tray_2."',";
		$query .= "'".$clase_tray_3."',";
		$query .= "'".$clase_tray_4."',";
		$query .= "'".$clase_tray_5."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}

//----------------------------------------------------------------------------------
//------METODOS PARA LA PARTE INFERIOR CON LOS DATOS DE PRECIOS DE LINEA REGULAR----
//----------------------------------------------------------------------------------

	function Cargar_precios_regular($cia, $acuerdo, $filadesde, $buscar_clave_coste, $buscar_fecha, $buscar_tipo){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
			
		//ECHO($filadesde);

		if($buscar_clave_coste != null){
			$fecha = " AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$tipo = " AND TIPO = '".$buscar_tipo."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND CLAVE_COSTE = '".$buscar_clave_coste."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fecha;	
			}
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tipo;	
			}
		}elseif($buscar_fecha != null){
			$tipo = " AND TIPO = '".$buscar_tipo."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			if($buscar_tipo != null){
					$CADENA_BUSCAR .= $tipo;	
			}
		}elseif($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND TIPO = '".$buscar_tipo."'";
		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."'"; 
  		}

		$resultado =$conexion->query("SELECT clave_coste clave_coste_regular, DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde_precios_regular, DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta_precios_regular, tipo tipo_regular, tasas, clase_1, coste_1, calculo, porcentaje_com, pvp_1, clase_2, suplemento_coste_2, suplemento_pvp_2, clase_3, suplemento_coste_3, suplemento_pvp_3, clase_4, suplemento_coste_4, suplemento_pvp_4, clase_5, suplemento_coste_5, suplemento_pvp_5 FROM hit_transportes_costes_regular ".$CADENA_BUSCAR." ORDER BY clave_coste, fecha_desde, tipo");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_REGULAR' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$precios_regular = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['fecha_desde_precios_regular'] == ''){
				break;
			}
			array_push($precios_regular,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $precios_regular;											
	}

	function Cargar_lineas_nuevas_precios_regular(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_REGULAR' AND USUARIO = '".$Usuario."'");
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

	function Cargar_combo_selector_precios_regular($cia, $acuerdo, $filadesde, $buscar_clave_coste, $buscar_fecha, $buscar_tipo){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_clave_coste != null){
			$fecha = " AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$tipo = " AND TIPO = '".$buscar_tipo."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND CLAVE_COSTE = '".$buscar_clave_coste."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fecha;	
			}
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tipo;	
			}
		}elseif($buscar_fecha != null){
			$tipo = " AND TIPO = '".$buscar_tipo."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			if($buscar_tipo != null){
					$CADENA_BUSCAR .= $tipo;	
			}
		}elseif($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND TIPO = '".$buscar_tipo."'";
		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."'"; 
  		}						
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes_costes_regular'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_REGULAR' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_precios_regular($filadesde, $boton, $cia, $acuerdo, $buscar_clave_coste, $buscar_fecha, $buscar_tipo){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
		
		if($buscar_clave_coste != null){
			$fecha = " AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$tipo = " AND TIPO = '".$buscar_tipo."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND CLAVE_COSTE = '".$buscar_clave_coste."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fecha;	
			}
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tipo;	
			}
		}elseif($buscar_fecha != null){
			$tipo = " AND TIPO = '".$buscar_tipo."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			if($buscar_tipo != null){
					$CADENA_BUSCAR .= $tipo;	
			}
		}elseif($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND TIPO = '".$buscar_tipo."'";
		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."'"; 
  		}						
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes_costes_regular'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_REGULAR' AND USUARIO = '".$Usuario."'");
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

	function Modificar_precios_regular($cia, $acuerdo, $clave_coste, $fecha_desde_precios_regular, $fecha_hasta_precios_regular, $tipo_regular, $tasas, $clase_1, $coste_1, $calculo, $porcentaje_com, $pvp_1, $clase_2, $suplemento_coste_2, $suplemento_pvp_2, $clase_3, $suplemento_coste_3, $suplemento_pvp_3, $clase_4, $suplemento_coste_4, $suplemento_pvp_4, $clase_5, $suplemento_coste_5, $suplemento_pvp_5,$clave_coste_old, $fecha_desde_precios_regular_old, $fecha_hasta_precios_regular_old, $tipo_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_transportes_costes_regular SET ";
		$query .= " CLAVE_COSTE = '".$clave_coste."'";
		$query .= ", FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_precios_regular))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta_precios_regular))."'";
		$query .= ", TIPO = '".$tipo_regular."'";
		$query .= ", TASAS = '".$tasas."'";
		$query .= ", CLASE_1 = '".$clase_1."'";
		$query .= ", COSTE_1 = '".$coste_1."'";
		$query .= ", CALCULO = '".$calculo."'";
		$query .= ", PORCENTAJE_COM = '".$porcentaje_com."'";
		$query .= ", PVP_1 = '".$pvp_1."'";
		$query .= ", CLASE_2 = '".$clase_2."'";
		$query .= ", SUPLEMENTO_COSTE_2 = '".$suplemento_coste_2."'";
		$query .= ", SUPLEMENTO_PVP_2 = '".$suplemento_pvp_2."'";
		$query .= ", CLASE_3 = '".$clase_3."'";
		$query .= ", SUPLEMENTO_COSTE_3 = '".$suplemento_coste_3."'";
		$query .= ", SUPLEMENTO_PVP_3 = '".$suplemento_pvp_3."'";
		$query .= ", CLASE_4 = '".$clase_4."'";
		$query .= ", SUPLEMENTO_COSTE_4 = '".$suplemento_coste_4."'";
		$query .= ", SUPLEMENTO_PVP_4 = '".$suplemento_pvp_4."'";
		$query .= ", CLASE_5 = '".$clase_5."'";
		$query .= ", SUPLEMENTO_COSTE_5 = '".$suplemento_coste_5."'";
		$query .= ", SUPLEMENTO_PVP_5 = '".$suplemento_pvp_5."'";
		$query .= " WHERE CIA = '".$cia."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND CLAVE_COSTE = '".$clave_coste_old."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_precios_regular_old))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta_precios_regular_old))."'";
		$query .= " AND TIPO = '".$tipo_old."'";

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

	function Borrar_precios_regular($cia,$acuerdo,$clave_coste, $fecha_desde_precios_regular, $fecha_hasta_precios_regular, $tipo_regular){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_transportes_costes_regular WHERE CIA = '".$cia."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND CLAVE_COSTE = '".$clave_coste."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_precios_regular))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta_precios_regular))."'";
		$query .= " AND TIPO = '".$tipo_regular."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_precios_regular($cia, $acuerdo, $clave_coste, $fecha_desde_precios_regular, $fecha_hasta_precios_regular, $tipo_regular, $tasas, $clase_1, $coste_1, $calculo, $porcentaje_com, $pvp_1, $clase_2, $suplemento_coste_2, $suplemento_pvp_2, $clase_3, $suplemento_coste_3, $suplemento_pvp_3, $clase_4, $suplemento_coste_4, $suplemento_pvp_4, $clase_5, $suplemento_coste_5, $suplemento_pvp_5){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_transportes_costes_regular (CIA, ACUERDO, CLAVE_COSTE, FECHA_DESDE, FECHA_HASTA, TIPO, TASAS, CLASE_1, COSTE_1, CALCULO, PORCENTAJE_COM, PVP_1, CLASE_2, SUPLEMENTO_COSTE_2, SUPLEMENTO_PVP_2, CLASE_3, SUPLEMENTO_COSTE_3, SUPLEMENTO_PVP_3, CLASE_4, SUPLEMENTO_COSTE_4, SUPLEMENTO_PVP_4, CLASE_5, SUPLEMENTO_COSTE_5, SUPLEMENTO_PVP_5) VALUES (";
		$query .= "'".$cia."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$clave_coste."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde_precios_regular))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta_precios_regular))."',";
		$query .= "'".$tipo_regular."',";
		$query .= "'".$tasas."',";
		$query .= "'".$clase_1."',";
		$query .= "'".$coste_1."',";
		$query .= "'".$calculo."',";
		$query .= "'".$porcentaje_com."',";
		$query .= "'".$pvp_1."',";
		$query .= "'".$clase_2."',";
		$query .= "'".$suplemento_coste_2."',";
		$query .= "'".$suplemento_pvp_2."',";
		$query .= "'".$clase_3."',";
		$query .= "'".$suplemento_coste_3."',";
		$query .= "'".$suplemento_pvp_3."',";
		$query .= "'".$clase_4."',";
		$query .= "'".$suplemento_coste_4."',";
		$query .= "'".$suplemento_pvp_4."',";
		$query .= "'".$clase_5."',";
		$query .= "'".$suplemento_coste_5."',";
		$query .= "'".$suplemento_pvp_5."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}


//----------------------------------------------------------------------------------
//------METODOS PARA LA PARTE INFERIOR CON LOS DATOS DE PRECIOS DE PLAZAS FIJAS-----
//----------------------------------------------------------------------------------

	function Cargar_precios_fijos($cia, $acuerdo, $subacuerdo, $filadesde, $buscar_origen, $buscar_destino, $buscar_fecha){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
			
		if($buscar_origen != null){
			$destino = " AND DESTINO = '".$buscar_destino."'";
			$fecha = " AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND SUBACUERDO = '".$subacuerdo."' AND ORIGEN = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $destino;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fecha;	
			}
		}elseif($buscar_destino != null){
			$fecha = " AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND SUBACUERDO = '".$subacuerdo."' AND DESINO = '".$buscar_destino."'";
			if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fecha;	
			}
		}elseif($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND SUBACUERDO = '".$subacuerdo."'"; 
  		}

		$resultado =$conexion->query("SELECT origen origen_fijos, destino destino_fijos, DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde_precios_fijos, DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta_precios_fijos, tasas, tipo_coste, clase_1, coste_1, calculo, porcentaje_com, pvp_1, clase_2, suplemento_coste_2, suplemento_pvp_2  FROM hit_transportes_costes_fijos ".$CADENA_BUSCAR." ORDER BY origen, destino, fecha_desde, fecha_hasta");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_FIJOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$precios_fijos = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['origen_fijos'] == ''){
				break;
			}
			array_push($precios_fijos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $precios_fijos;											
	}

	function Cargar_lineas_nuevas_precios_fijos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_FIJOS' AND USUARIO = '".$Usuario."'");
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

	function Cargar_combo_selector_precios_fijos($cia, $acuerdo, $subacuerdo, $filadesde, $buscar_origen, $buscar_destino, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_origen != null){
			$destino = " AND DESTINO = '".$buscar_destino."'";
			$fecha = " AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND SUBACUERDO = '".$subacuerdo."' AND ORIGEN = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $destino;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fecha;	
			}
		}elseif($buscar_destino != null){
			$fecha = " AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND SUBACUERDO = '".$subacuerdo."' AND DESINO = '".$buscar_destino."'";
			if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fecha;	
			}
		}elseif($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND SUBACUERDO = '".$subacuerdo."'"; 
  		}					
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes_costes_fijos'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_FIJOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_precios_fijos($filadesde, $boton, $cia, $acuerdo, $subacuerdo, $buscar_origen, $buscar_destino, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_origen != null){
			$destino = " AND DESTINO = '".$buscar_destino."'";
			$fecha = " AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND SUBACUERDO = '".$subacuerdo."' AND ORIGEN = '".$buscar_origen."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $destino;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fecha;	
			}
		}elseif($buscar_destino != null){
			$fecha = " AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND SUBACUERDO = '".$subacuerdo."' AND DESINO = '".$buscar_destino."'";
			if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fecha;	
			}
		}elseif($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND '".$buscar_fecha."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$cia."' AND ACUERDO = '".$acuerdo."' AND SUBACUERDO = '".$subacuerdo."'"; 
  		}					
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes_costes_fijos'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_FIJOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_precios_fijos($cia, $acuerdo, $subacuerdo, $origen, $destino, $fecha_desde_precios_fijos, $fecha_hasta_precios_fijos, $tasas, $tipo_coste, $clase_1, $coste_1, $calculo, $porcentaje_com, $pvp_1, $clase_2, $suplemento_coste_2, $suplemento_pvp_2,$origen_old, $destino_old, $fecha_desde_precios_fijos_old, $fecha_hasta_precios_fijos_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_transportes_costes_fijos SET ";
		$query .= " ORIGEN = '".$origen."'";
		$query .= ", DESTINO = '".$destino."'";
		$query .= ", FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_precios_fijos))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta_precios_fijos))."'";
		$query .= ", TASAS = '".$tasas."'";
		$query .= ", TIPO_COSTE = '".$tipo_coste."'";
		$query .= ", CLASE_1 = '".$clase_1."'";
		$query .= ", COSTE_1 = '".$coste_1."'";
		$query .= ", CALCULO = '".$calculo."'";
		$query .= ", PORCENTAJE_COM = '".$porcentaje_com."'";
		$query .= ", PVP_1 = '".$pvp_1."'";
		$query .= ", CLASE_2 = '".$clase_2."'";
		$query .= ", SUPLEMENTO_COSTE_2 = '".$suplemento_coste_2."'";
		$query .= ", SUPLEMENTO_PVP_2 = '".$suplemento_pvp_2."'";
		$query .= " WHERE CIA = '".$cia."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND SUBACUERDO = '".$subacuerdo."'";
		$query .= " AND ORIGEN = '".$origen_old."'";
		$query .= " AND DESTINO = '".$destino_old."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_precios_fijos_old))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta_precios_fijos_old))."'";

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

	function Borrar_precios_fijos($cia,$acuerdo,$subacuerdo,$origen,$destino, $fecha_desde_precios_fijos, $fecha_hasta_precios_fijos){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_transportes_costes_fijos WHERE CIA = '".$cia."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND SUBACUERDO = '".$subacuerdo."'";
		$query .= " AND ORIGEN = '".$origen."'";
		$query .= " AND DESTINO = '".$destino."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_precios_fijos))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta_precios_fijos))."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_precios_fijos($cia, $acuerdo, $subacuerdo, $origen, $destino, $fecha_desde_precios_fijos, $fecha_hasta_precios_fijos, $tasas, $tipo_coste, $clase_1, $coste_1, $calculo, $porcentaje_com, $pvp_1, $clase_2, $suplemento_coste_2, $suplemento_pvp_2){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_transportes_costes_fijos (CIA, ACUERDO, SUBACUERDO, ORIGEN, DESTINO, FECHA_DESDE, FECHA_HASTA, TASAS, TIPO_COSTE, CLASE_1, COSTE_1, CALCULO, PORCENTAJE_COM, PVP_1, CLASE_2, SUPLEMENTO_COSTE_2, SUPLEMENTO_PVP_2) VALUES (";
		$query .= "'".$cia."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$subacuerdo."',";
		$query .= "'".$origen."',";
		$query .= "'".$destino."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde_precios_fijos))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta_precios_fijos))."',";
		$query .= "'".$tasas."',";
		$query .= "'".$tipo_coste."',";
		$query .= "'".$clase_1."',";
		$query .= "'".$coste_1."',";
		$query .= "'".$calculo."',";
		$query .= "'".$porcentaje_com."',";
		$query .= "'".$pvp_1."',";
		$query .= "'".$clase_2."',";
		$query .= "'".$suplemento_coste_2."',";
		$query .= "'".$suplemento_pvp_2."')";

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
	function clsAcuerdos($conexion, $filadesde, $usuario, $buscar_cia, $buscar_acuerdo, $buscar_subacuerdo){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_cia= $buscar_cia;
		$this->Buscar_acuerdo = $buscar_acuerdo;
		$this->Buscar_subacuerdo = $buscar_subacuerdo;
	}
}

?>