<?php

class clsAcuerdos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var	$buscar_acuerdo;
	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------
	function Cargar($recuperaid,$recuperaacuerdo,$recuperatipo,$recuperasituacion,$recuperafecha_desde,$recuperafecha_hasta,$recuperadias_entrada,$recuperaregimen_sa, $recuperaregimen_ad,$recuperaregimen_mp,$recuperaregimen_pc,$recuperaregimen_ti,$recuperagrat_tipo,$recuperagrat_uso,$recuperagrat_cada,$recuperagrat_max,$recuperadivisa,$recuperaforma_pago,$recuperacorresponsal,$recuperadescripcion,$recuperaenvio_1,$recuperaenvio_2,$recuperaenvio_3,$recuperaenvio_rooming,$recuperacaracteristica_base){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_id = $this ->Buscar_id;
		$buscar_acuerdo = $this ->Buscar_acuerdo;

		if($buscar_id != null){
			if($buscar_acuerdo != null){
				$CADENA_BUSCAR = " AND ac.id = '".$buscar_id."' AND ac.ACUERDO = '".$buscar_acuerdo."'";
			}else{
				$CADENA_BUSCAR = " AND ac.id = '".$buscar_id."'";
			}
		}elseif($buscar_acuerdo != null){
			$CADENA_BUSCAR = " AND ac.acuerdo = '".$buscar_acuerdo."'";
		}else{
			$CADENA_BUSCAR = " AND ac.id = 0 ";
			//$CADENA_BUSCAR = "";
		}

		$resultado =$conexion->query("SELECT a.nombre nombre, a.nombre nombre_hotel,  ac.id,ac.acuerdo, ac.interfaz, ac.tipo,ac.situacion, DATE_FORMAT(ac.fecha_desde, '%d-%m-%Y') AS fecha_desde, DATE_FORMAT(ac.fecha_hasta, '%d-%m-%Y') AS fecha_hasta,ac.dias_entrada,ac.regimen_sa,ac.regimen_ad,ac.regimen_mp,ac.regimen_pc,ac.regimen_ti,ac.grat_tipo,ac.grat_uso, ac.grat_cada,ac.grat_max,ac.divisa,ac.pago_tipo,ac.pago_plazo,ac.forma_pago,ac.corresponsal,ac.descripcion,ac.envio_1,ac.envio_2,ac.envio_3,ac.envio_rooming, ac.caracteristica_base FROM hit_alojamientos_acuerdos ac, hit_alojamientos a WHERE a.id = ac.id ".$CADENA_BUSCAR." ORDER BY a.nombre, ac.acuerdo");


		/*echo("SELECT a.nombre, ac.id,ac.acuerdo, ac.interfaz, ac.tipo,ac.situacion, DATE_FORMAT(ac.fecha_desde, '%d-%m-%Y') AS fecha_desde, DATE_FORMAT(ac.fecha_hasta, '%d-%m-%Y') AS fecha_hasta,ac.dias_entrada,ac.regimen_sa,ac.regimen_ad,ac.regimen_mp,ac.regimen_pc,ac.regimen_ti,ac.grat_tipo,ac.grat_uso, ac.grat_cada,ac.grat_max,ac.divisa,ac.pago_tipo,ac.pago_plazo,ac.forma_pago,ac.corresponsal,ac.descripcion,ac.envio_1,ac.envio_2,ac.envio_3,ac.envio_rooming, ac.caracteristica_base FROM hit_alojamientos_acuerdos ac, hit_alojamientos a WHERE a.id = ac.id ".$CADENA_BUSCAR." ORDER BY a.nombre, ac.acuerdo");*/

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_ACUERDOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$acuerdos = array();
		if($recuperaid != null){
			$acuerdos[0] = array ("id" => null, "acuerdo" => $recuperaacuerdo, "tipo" => $recuperatipo, "situacion" => $recuperasituacion, "fecha_desde" => $recuperafecha_desde, "fecha_hasta" => $recuperafecha_hasta, "dias_entrada" => $recuperadias_entrada, "regimen_sa" => $recuperaregimen_sa, "regimen_ad" => $recuperaregimen_ad, "regimen_mp" => $recuperaregimen_mp, "regimen_pc" => $recuperaregimen_pc, "regimen_ti" => $recuperaregimen_ti, "grat_tipo" => $recuperagrat_tipo, "grat_uso" => $recuperagrat_uso, "grat_cada" => $recuperagrat_cada, "grat_max" => $recuperagrat_max, "divisa" => $recuperadivisa, "forma_pago" => $recuperaforma_pago, "corresponsal" => $recuperacorresponsal, "descripcion" => $recuperadescripcion, "envio_1" => $recuperaenvio_1, "envio_2" => $recuperaenvio_2, "envio_3" => $recuperaenvio_3, "envio_rooming" => $recuperaenvio_rooming, "caracteristica_base" => $recuperacaracteristica_base);
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
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_ACUERDOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_acuerdo = $this ->Buscar_acuerdo;



		if($buscar_id != null){
			if($buscar_acuerdo != null){
				$CADENA_BUSCAR = " ac.id = '".$buscar_id."' AND ac.ACUERDO = '".$buscar_acuerdo."'";
			}else{
				$CADENA_BUSCAR = " ac.id = '".$buscar_id."'";
			}
		}elseif($buscar_acuerdo != null){
			$CADENA_BUSCAR = " ac.acuerdo = '".$buscar_acuerdo."'";
		}else{
			$CADENA_BUSCAR = " ac.id = 0 ";
			//$CADENA_BUSCAR = "";
		}										

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_acuerdos ac where '.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_ACUERDOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_id = $this ->Buscar_id;
		$buscar_acuerdo = $this ->Buscar_acuerdo;



		if($buscar_id != null){
			if($buscar_acuerdo != null){
				$CADENA_BUSCAR = " ac.id = '".$buscar_id."' AND ac.ACUERDO = '".$buscar_acuerdo."'";
			}else{
				$CADENA_BUSCAR = " ac.id = '".$buscar_id."'";
			}
		}elseif($buscar_acuerdo != null){
			$CADENA_BUSCAR = " ac.acuerdo = '".$buscar_acuerdo."'";
		}else{
			$CADENA_BUSCAR = " ac.id = 0 ";
			//$CADENA_BUSCAR = "";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_acuerdos ac where '.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_ACUERDOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id,$acuerdo,$tipo,$situacion,$fecha_desde,$fecha_hasta,$dias_entrada,$regimen_sa,$regimen_ad,$regimen_mp,$regimen_pc,$regimen_ti,	$grat_tipo,$grat_uso,$grat_cada,$grat_max,$divisa,$pago_tipo,$pago_plazo,$forma_pago,$corresponsal,$descripcion,$envio_1,$envio_2,$envio_3,$envio_rooming,$caracteristica_base){


		$conexion = $this ->Conexion;
		$query = "UPDATE hit_alojamientos_acuerdos SET ";
		$query .= " TIPO = '".$tipo."'";
		$query .= ", SITUACION = '".$situacion."'";
		$query .= ", FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		//$query .= ", FECHA_DESDE = '".$fecha_desde."'";
		//$query .= ", FECHA_HASTA = '".$fecha_hasta."'";
		$query .= ", DIAS_ENTRADA = '".$dias_entrada."'";
		$query .= ", REGIMEN_SA = '".$regimen_sa."'";
		$query .= ", REGIMEN_AD = '".$regimen_ad."'";
		$query .= ", REGIMEN_MP = '".$regimen_mp."'";
		$query .= ", REGIMEN_PC = '".$regimen_pc."'";
		$query .= ", REGIMEN_TI = '".$regimen_ti."'";
		$query .= ", GRAT_TIPO = '".$grat_tipo."'";
		$query .= ", GRAT_USO = '".$grat_uso."'";
		$query .= ", GRAT_CADA = '".$grat_cada."'";
		$query .= ", GRAT_MAX = '".$grat_max."'";
		$query .= ", DIVISA = '".$divisa."'";
		$query .= ", PAGO_TIPO = '".$pago_tipo."'";
		$query .= ", PAGO_PLAZO = '".$pago_plazo."'";
		$query .= ", FORMA_PAGO = '".$forma_pago."'";
		$query .= ", CORRESPONSAL = '".$corresponsal."'";
		$query .= ", DESCRIPCION = '".$descripcion."'";
		$query .= ", ENVIO_1 = '".$envio_1."'";
		$query .= ", ENVIO_2 = '".$envio_2."'";
		$query .= ", ENVIO_3 = '".$envio_3."'";
		$query .= ", ENVIO_ROOMING = '".$envio_rooming."'";
		$query .= ", CARACTERISTICA_BASE = '".$caracteristica_base."'";
		$query .= " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo;


		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($id, $acuerdo){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_alojamientos_acuerdos WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo;

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($id,$acuerdo,$tipo,$situacion,$fecha_desde,$fecha_hasta,$dias_entrada,$regimen_sa,$regimen_ad,$regimen_mp,$regimen_pc,$regimen_ti,	$grat_tipo,$grat_uso,$grat_cada,$grat_max,$divisa,$pago_tipo,$pago_plazo,$forma_pago,$corresponsal,$descripcion,$envio_1,$envio_2,$envio_3,$envio_rooming,$caracteristica_base){

		$conexion = $this ->Conexion;

		$query = "INSERT INTO hit_alojamientos_acuerdos(ID,ACUERDO,TIPO,SITUACION,FECHA_DESDE,FECHA_HASTA,DIAS_ENTRADA,REGIMEN_SA,REGIMEN_AD,REGIMEN_MP,REGIMEN_PC,REGIMEN_TI,GRAT_TIPO,GRAT_USO,GRAT_CADA,GRAT_MAX,DIVISA,PAGO_TIPO,PAGO_PLAZO,FORMA_PAGO,CORRESPONSAL,DESCRIPCION,ENVIO_1,ENVIO_2,ENVIO_3,ENVIO_ROOMING,CARACTERISTICA_BASE) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$tipo."',";
		$query .= "'".$situacion."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta))."',";
		$query .= "'".$dias_entrada."',";
		$query .= "'".$regimen_sa."',";
		$query .= "'".$regimen_ad."',";
		$query .= "'".$regimen_mp."',";
		$query .= "'".$regimen_pc."',";
		$query .= "'".$regimen_ti."',";
		$query .= "'".$grat_tipo."',";
		$query .= "'".$grat_uso."',";
		$query .= "'".$grat_cada."',";
		$query .= "'".$grat_max."',";
		$query .= "'".$divisa."',";
		$query .= "'".$pago_tipo."',";
		$query .= "'".$pago_plazo."',";
		$query .= "'".$forma_pago."',";
		$query .= "'".$corresponsal."',";
		$query .= "'".$descripcion."',";
		$query .= "'".$envio_1."',";
		$query .= "'".$envio_2."',";
		$query .= "'".$envio_3."',";
		$query .= "'".$envio_rooming."',";
		$query .= "'".$caracteristica_base."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Expandir_cupos($id, $acuerdo){

		$conexion = $this ->Conexion;

		//obtenemos las lineas que hay que expndir segun lors periodos y las habitaciones del contrato
		$resultado =$conexion->query("select distinct a.ID, a.ACUERDO, pr.HABITACION, pr.CARACTERISTICA, p.FECHA_DESDE, p.FECHA_HASTA, p.DIAS_RELEASE,  a.DIAS_ENTRADA	from hit_alojamientos_acuerdos a, hit_alojamientos_periodos p , hit_alojamientos_precios pr where a.ID = p.ID and a.ACUERDO = p.ACUERDO	and p.ID = pr.ID and p.ACUERDO = pr.ACUERDO	and p.TEMPORADA = pr.TEMPORADA	and a.ID = ".$id."	and a.ACUERDO = ".$acuerdo." order by a.ID, a.ACUERDO, p.FECHA_DESDE, p.FECHA_HASTA");

		if ($resultado == FALSE){
			$respuesta = 'No se han podido expandir los cupos. '.$conexion->error;
		}else{
			while($fila=mysqli_fetch_row($resultado)){
				//echo($fila[0]."-".$fila[1]."-".$fila[2]."-".$fila[3]."-".$fila[4]."-".$fila[5]."-".$fila[6]."-".$fila[7]."-");
				$expandir = "CALL `PROVEEDORES_EXPANDE_CUPOS_ALOJAMIENTOS`('".$fila[0]."', '".$fila[1]."', '".$fila[2]."', '".$fila[3]."', '".$fila[4]."', '".$fila[5]."', '".$fila[6]."', '".$fila[7]."')";
				$resultadoexpandir =$conexion->query($expandir);
					//echo($expandir);
				if ($resultadoexpandir == FALSE){
					$respuesta = 'No se han podido expandir los cupos. '.$conexion->error;
				}else{
					$respuesta = 'OK';
					$expandido =$conexion->query("UPDATE hit_alojamientos_acuerdos SET SITUACION = 'E' WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo);
				}
			}
		}
		return $respuesta;											
	}

	function Borrar_cupos($id, $acuerdo, $desde, $hasta){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_alojamientos_cupos WHERE OCUPADAS = 0 AND ID = '".$id."' AND ACUERDO = '".$acuerdo."' AND FECHA BETWEEN '".date("Y-m-d",strtotime($desde))."' AND '".date("Y-m-d",strtotime($hasta))."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE PERIODOS----
//--------------------------------------------------------------------

	function Cargar_periodos($id, $acuerdo, $filadesde, $buscar_fecha){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}

		$resultado =$conexion->query("SELECT temporada,DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde_periodo, DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta_periodo, dias_release FROM hit_alojamientos_periodos ".$CADENA_BUSCAR." ORDER BY fecha_desde, fecha_hasta");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PERIODOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$periodos = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['temporada'] == ''){
				break;
			}
			array_push($periodos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $periodos;											
	}


	function Cargar_lineas_nuevas_periodos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PERIODOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_periodos($id, $acuerdo, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
			//$CADENA_BUSCAR = " WHERE ID = '0' AND ACUERDO = '0'";     
		}										

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_periodos'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PERIODOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_periodos($filadesde, $boton, $id, $acuerdo, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
			//$CADENA_BUSCAR = " WHERE ID = '0' AND ACUERDO = '0'";     
		}										

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_periodos'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PERIODOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_periodos($id, $acuerdo, $temporada, $fecha_desde, $fecha_hasta, $dias_release, $temporada_old, $fecha_desde_old, $fecha_hasta_old){

		//$fecha2=date("Y-m-d",strtotime($fecha_desde));
		//echo(date("Y-m-d",strtotime($fecha_desde)));

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_alojamientos_periodos SET ";
		$query .= " TEMPORADA = '".$temporada."'";
		$query .= ", FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= ", DIAS_RELEASE = '".$dias_release."'";
		$query .= " WHERE ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND TEMPORADA = '".$temporada_old."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_old))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta_old))."'";


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

	function Borrar_periodos($id,$acuerdo,$temporada, $fecha_desde,$fecha_hasta){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_alojamientos_periodos WHERE ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND TEMPORADA = '".$temporada."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_periodos($id,$acuerdo,$temporada, $fecha_desde,$fecha_hasta, $dias_release){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_alojamientos_periodos (ID, ACUERDO, TEMPORADA, FECHA_DESDE, FECHA_HASTA, DIAS_RELEASE) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$temporada."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta))."',";
		$query .= "'".$dias_release."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}


//----------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE TEMPORADAS----
//----------------------------------------------------------------------

	function Cargar_temporadas($id, $acuerdo, $filadesde, $buscar_temporada){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		if($buscar_temporada != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND TEMPORADA = '".$buscar_temporada."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}

		$resultado =$conexion->query("SELECT temporada temporada_temp, spto_ad, spto_mp, spto_pc, spto_ti, permite_sa, permite_ad,
		permite_mp, permite_pc, permite_ti FROM hit_alojamientos_temporadas ".$CADENA_BUSCAR." ORDER BY temporada");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_TEMPORADAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$temporadas = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['temporada_temp'] == ''){
				break;
			}
			array_push($temporadas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $temporadas;											
	}

	function Cargar_lineas_nuevas_temporadas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_TEMPORADAS' AND USUARIO = '".$Usuario."'");
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

	function Cargar_combo_selector_temporadas($id, $acuerdo, $buscar_temporada){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_temporada != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND TEMPORADA = '".$buscar_temporada."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}									
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_temporadas'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_TEMPORADAS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_temporadas($filadesde, $boton, $id, $acuerdo, $buscar_temporada){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_temporada != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND TEMPORADA = '".$buscar_temporada."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}									
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_temporadas'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_TEMPORADAS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_temporadas($id, $acuerdo, $temporada, $spto_ad, $spto_mp, $spto_pc, $spto_ti, $permite_sa, $permite_ad,
		$permite_mp, $permite_pc, $permite_ti, $temporada_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_alojamientos_temporadas SET ";
		$query .= " TEMPORADA = '".$temporada."'";
		$query .= ", SPTO_AD = '".$spto_ad."'";
		$query .= ", SPTO_MP = '".$spto_mp."'";
		$query .= ", SPTO_PC = '".$spto_pc."'";
		$query .= ", SPTO_TI = '".$spto_ti."'";
		$query .= ", PERMITE_SA = '".$permite_sa."'";
		$query .= ", PERMITE_AD = '".$permite_ad."'";
		$query .= ", PERMITE_MP = '".$permite_mp."'";
		$query .= ", PERMITE_PC = '".$permite_pc."'";
		$query .= ", PERMITE_TI = '".$permite_ti."'";
		$query .= " WHERE ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND TEMPORADA = '".$temporada_old."'";

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

	function Borrar_temporadas($id,$acuerdo,$temporada){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_alojamientos_temporadas WHERE ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND TEMPORADA = '".$temporada."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_temporadas($id,$acuerdo,$temporada, $spto_ad, $spto_mp, $spto_pc, $spto_ti, $permite_sa, $permite_ad,
		$permite_mp, $permite_pc, $permite_ti){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_alojamientos_temporadas (ID, ACUERDO, TEMPORADA, SPTO_AD, SPTO_MP, SPTO_PC, SPTO_TI, PERMITE_SA, PERMITE_AD, PERMITE_MP, PERMITE_PC, PERMITE_TI) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$temporada."',";
		$query .= "'".$spto_ad."',";
		$query .= "'".$spto_mp."',";
		$query .= "'".$spto_pc."',";
		$query .= "'".$spto_ti."',";
		$query .= "'".$permite_sa."',";
		$query .= "'".$permite_ad."',";
		$query .= "'".$permite_mp."',";
		$query .= "'".$permite_pc."',";
		$query .= "'".$permite_ti."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}

//---------------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE LAS CONDICIONES----
//---------------------------------------------------------------------------

	function Cargar_condiciones($id, $acuerdo, $filadesde, $buscar_tipo){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		if($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND TIPO = '".$buscar_tipo."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}

		$resultado =$conexion->query("SELECT DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde_condiciones, DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta_condiciones, DATE_FORMAT(reserva_desde, '%d-%m-%Y') AS reserva_desde_condiciones, DATE_FORMAT(reserva_hasta, '%d-%m-%Y') AS reserva_hasta_condiciones, uso uso_condiciones, tipo tipo_condiciones, edad_desde edad_desde_condiciones, edad_hasta edad_hasta_condiciones, regimen regimen_condiciones, calculo calculo_condiciones, valor valor_condiciones, valor_pvp valor_pvp_condiciones FROM hit_alojamientos_condiciones ".$CADENA_BUSCAR." ORDER BY fecha_desde, uso, tipo");

		//echo($CADENA_BUSCAR);
		
		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CONDICIONES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$condiciones = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['uso_condiciones'] == ''){
				break;
			}
			array_push($condiciones,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $condiciones;		
		
	}

	function Cargar_lineas_nuevas_condiciones(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CONDICIONES' AND USUARIO = '".$Usuario."'");
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

	function Cargar_combo_selector_condiciones($id, $acuerdo, $filadesde, $buscar_tipo){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND TIPO = '".$buscar_tipo."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}									
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_condiciones'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------


		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CONDICIONES' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_condiciones($filadesde, $boton, $id, $acuerdo, $buscar_tipo){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_tipo != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND TIPO = '".$buscar_tipo."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}									
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_condiciones'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CONDICIONES' AND USUARIO = '".$Usuario."'");
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

	function Modificar_condiciones($id, $acuerdo, $fecha_desde, $fecha_hasta, $reserva_desde, $reserva_hasta, $uso, $tipo, $edad_desde, $edad_hasta, $regimen, $calculo, $valor, $valor_pvp, $fecha_desde_old, $fecha_hasta_old, $reserva_desde_old, $reserva_hasta_old, $uso_old, $tipo_old, $edad_desde_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_alojamientos_condiciones SET ";
		$query .= " FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= ", RESERVA_DESDE = '".date("Y-m-d",strtotime($reserva_desde))."'";
		$query .= ", RESERVA_HASTA = '".date("Y-m-d",strtotime($reserva_hasta))."'";
		$query .= ", USO = '".$uso."'";
		$query .= ", TIPO = '".$tipo."'";
		$query .= ", EDAD_DESDE = '".$edad_desde."'";
		$query .= ", EDAD_HASTA = '".$edad_hasta."'";
		$query .= ", REGIMEN = '".$regimen."'";
		$query .= ", CALCULO = '".$calculo."'";
		$query .= ", VALOR = '".$valor."'";
		$query .= ", VALOR_PVP = '".$valor_pvp."'";
		$query .= " WHERE ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_old))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta_old))."'";
		$query .= " AND RESERVA_DESDE = '".date("Y-m-d",strtotime($reserva_desde_old))."'";
		$query .= " AND RESERVA_HASTA = '".date("Y-m-d",strtotime($reserva_hasta_old))."'";
		$query .= " AND USO = '".$uso_old."'";
		$query .= " AND TIPO = '".$tipo_old."'";
		$query .= " AND EDAD_DESDE = '".$edad_desde_old."'";

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

	function Borrar_condiciones($id, $acuerdo, $fecha_desde, $fecha_hasta, $reserva_desde, $reserva_hasta, $uso, $tipo, $edad_desde){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_alojamientos_condiciones WHERE ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= " AND RESERVA_DESDE = '".date("Y-m-d",strtotime($reserva_desde))."'";
		$query .= " AND RESERVA_HASTA = '".date("Y-m-d",strtotime($reserva_hasta))."'";
		$query .= " AND USO = '".$uso."'";
		$query .= " AND TIPO = '".$tipo."'";
		$query .= " AND EDAD_DESDE = '".$edad_desde."'";

		
		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_condiciones($id, $acuerdo, $fecha_desde, $fecha_hasta, $reserva_desde, $reserva_hasta, $uso, $tipo, $edad_desde, $edad_hasta, $regimen, $calculo, $valor, $valor_pvp, $fecha_desde_acuerdo, $fecha_hasta_acuerdo){

		$conexion = $this ->Conexion;
		
		if($fecha_desde == null || $fecha_hasta == null){
			$fecha_desde = $fecha_desde_acuerdo;
			$fecha_hasta = $fecha_hasta_acuerdo;
		}		
		
		$query = "INSERT INTO hit_alojamientos_condiciones (ID, ACUERDO, FECHA_DESDE, FECHA_HASTA, RESERVA_DESDE, RESERVA_HASTA, USO,TIPO,EDAD_DESDE,EDAD_HASTA,REGIMEN,CALCULO,VALOR,VALOR_PVP) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta))."',";
		$query .= "'".date("Y-m-d",strtotime($reserva_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($reserva_hasta))."',";		
		$query .= "'".$uso."',";
		$query .= "'".$tipo."',";
		$query .= "'".$edad_desde."',";
		$query .= "'".$edad_hasta."',";
		$query .= "'".$regimen."',";
		$query .= "'".$calculo."',";
		$query .= "'".$valor."',";
		$query .= "'".$valor_pvp."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}



//------------------------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE LOS USOS MINIMO Y MAXIMO----
//------------------------------------------------------------------------------------

	function Cargar_usos($id, $acuerdo, $filadesde, $buscar_caracteristica_usos){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		if($buscar_caracteristica_usos != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND CARACTERISTICA = '".$buscar_caracteristica_usos."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}

		$resultado =$conexion->query("SELECT caracteristica caracteristica_usos, 
											 minimo_detalle minimo_detalle_usos,  
											 uso_minimo uso_minimo_usos,
											 uso_minimo_adultos uso_minimo_adultos_usos,
											 uso_minimo_ninos uso_minimo_ninos_usos,
											 uso_minimo_bebes uso_minimo_bebes_usos,
											 maximo_detalle maximo_detalle_usos,
											 uso_maximo_pax uso_maximo_pax_usos,
											 uso_maximo_adultos uso_maximo_adultos_usos,
											 uso_maximo_ninos uso_maximo_ninos_usos,
											 uso_maximo_bebes uso_maximo_bebes_usos
											 FROM hit_alojamientos_usos ".$CADENA_BUSCAR." ORDER BY caracteristica");

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_USOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$usos = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['caracteristica_usos'] == ''){
				break;
			}
			array_push($usos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $usos;		

	}

	function Cargar_lineas_nuevas_usos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_USOS' AND USUARIO = '".$Usuario."'");
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

	function Cargar_combo_selector_usos($id, $acuerdo, $buscar_caracteristica_usos){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_caracteristica_usos != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND CARACTERISTICA = '".$buscar_caracteristica_usos."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}			
		//echo($CADENA_BUSCAR);		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_usos'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_USOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_usos($filadesde, $boton, $id, $acuerdo, $buscar_caracteristica_usos){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_caracteristica_usos != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND CARACTERISTICA = '".$buscar_caracteristica_usos."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}								
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_usos'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;          //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_USOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_usos($id, $acuerdo, $caracteristica_usos, $minimo_detalle, $uso_minimo, $uso_minimo_adultos, $uso_minimo_ninos, $uso_minimo_bebes,$maximo_detalle, $uso_maximo_pax, $uso_maximo_adultos, $uso_maximo_ninos, $uso_maximo_bebes, $caracteristica_usos_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_alojamientos_usos SET ";
		$query .= " CARACTERISTICA = '".$caracteristica_usos."'";
		$query .= ", MINIMO_DETALLE = '".$minimo_detalle."'";
		$query .= ", USO_MINIMO = '".$uso_minimo."'";
		$query .= ", USO_MINIMO_ADULTOS = '".$uso_minimo_adultos."'";
		$query .= ", USO_MINIMO_NINOS = '".$uso_minimo_ninos."'";
		$query .= ", USO_MINIMO_BEBES = '".$uso_minimo_bebes."'";
		$query .= ", MAXIMO_DETALLE = '".$maximo_detalle."'";
		$query .= ", USO_MAXIMO_PAX = '".$uso_maximo_pax."'";
		$query .= ", USO_MAXIMO_ADULTOS = '".$uso_maximo_adultos."'";
		$query .= ", USO_MAXIMO_NINOS = '".$uso_maximo_ninos."'";
		$query .= ", USO_MAXIMO_BEBES = '".$uso_maximo_bebes."'";
		$query .= " WHERE ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND CARACTERISTICA = '".$caracteristica_usos_old."'";

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

	function Borrar_usos($id,$acuerdo, $caracteristica_usos){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_alojamientos_usos WHERE ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND CARACTERISTICA = '".$caracteristica_usos."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_usos($id, $acuerdo, $caracteristica_usos, $minimo_detalle, $uso_minimo, $uso_minimo_adultos, $uso_minimo_ninos, $uso_minimo_bebes, $maximo_detalle, $uso_maximo_pax, $uso_maximo_adultos, $uso_maximo_ninos, $uso_maximo_bebes){
		//echo('hola');
		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_alojamientos_usos (ID, ACUERDO, CARACTERISTICA,MINIMO_DETALLE,USO_MINIMO,USO_MINIMO_ADULTOS,USO_MINIMO_NINOS,USO_MINIMO_BEBES,MAXIMO_DETALLE,USO_MAXIMO_PAX,USO_MAXIMO_ADULTOS,USO_MAXIMO_NINOS,USO_MAXIMO_BEBES) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$caracteristica_usos."',";
		$query .= "'".$minimo_detalle."',";
		$query .= "'".$uso_minimo."',";
		$query .= "'".$uso_minimo_adultos."',";
		$query .= "'".$uso_minimo_ninos."',";
		$query .= "'".$uso_minimo_bebes."',";
		$query .= "'".$maximo_detalle."',";
		$query .= "'".$uso_maximo_pax."',";
		$query .= "'".$uso_maximo_adultos."',";
		$query .= "'".$uso_maximo_ninos."',";
		$query .= "'".$uso_maximo_bebes."')";
		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}



//----------------------------------------------------------------------
//------METODOS PARA LA PARTE INFERIOR CON LOS DATOS DE PRECIOS---------
//----------------------------------------------------------------------

	function Cargar_precios($id, $acuerdo, $filadesde, $buscar_temporada, $buscar_habitacion, $buscar_caracteristica){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
			
		if($buscar_temporada != null){
			$habit = " AND HABITACION = '".$buscar_habitacion."'";
			$carac = " AND CARACTERISTICA = '".$buscar_caracteristica."'";
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND TEMPORADA = '".$buscar_temporada."'";
			if($buscar_habitacion != null){
				$CADENA_BUSCAR .= $habit;	
			}
			if($buscar_caracteristica != null){
				$CADENA_BUSCAR .= $carac;	
			}
		}elseif($buscar_habitacion){
			$carac = " AND CARACTERISTICA = '".$buscar_caracteristica."'";
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND HABITACION = '".$buscar_habitacion."'";
			if($buscar_caracteristica != null){
					$CADENA_BUSCAR .= $carac;	
			}
		}elseif($buscar_caracteristica){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND CARACTERISTICA = '".$buscar_caracteristica."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}

		$resultado =$conexion->query("SELECT temporada temporada_prec, habitacion, caracteristica, uso, coste_pax, coste_habitacion, calculo, porcentaje_neto, neto_pax, neto_habitacion, porcentaje_com, pvp_pax, pvp_habitacion FROM hit_alojamientos_precios ".$CADENA_BUSCAR." ORDER BY temporada, uso");

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$precios = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['temporada_prec'] == ''){
				break;
			}
			array_push($precios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $precios;											
	}

	function Cargar_lineas_nuevas_precios(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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

	function Cargar_combo_selector_precios($id, $acuerdo, $filadesde, $buscar_temporada, $buscar_habitacion, $buscar_caracteristica){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_temporada != null){
			$habit = " AND HABITACION = '".$buscar_habitacion."'";
			$carac = " AND CARACTERISTICA = '".$buscar_caracteristica."'";
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND TEMPORADA = '".$buscar_temporada."'";
			if($buscar_habitacion != null){
				$CADENA_BUSCAR .= $habit;	
			}
			if($buscar_caracteristica != null){
				$CADENA_BUSCAR .= $carac;	
			}
		}elseif($buscar_habitacion){
			$carac = " AND CARACTERISTICA = '".$buscar_caracteristica."'";
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND HABITACION = '".$buscar_habitacion."'";
			if($buscar_caracteristica != null){
					$CADENA_BUSCAR .= $carac;	
			}
		}elseif($buscar_caracteristica){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND CARACTERISTICA = '".$buscar_caracteristica."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}									
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_precios'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_precios($filadesde, $boton, $id, $acuerdo, $buscar_temporada, $buscar_habitacion, $buscar_caracteristica){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_temporada != null){
			$habit = " AND HABITACION = '".$buscar_habitacion."'";
			$carac = " AND CARACTERISTICA = '".$buscar_caracteristica."'";
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND TEMPORADA = '".$buscar_temporada."'";
			if($buscar_habitacion != null){
				$CADENA_BUSCAR .= $habit;	
			}
			if($buscar_caracteristica != null){
				$CADENA_BUSCAR .= $carac;	
			}
		}elseif($buscar_habitacion){
			$carac = " AND CARACTERISTICA = '".$buscar_caracteristica."'";
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND HABITACION = '".$buscar_habitacion."'";
			if($buscar_caracteristica != null){
					$CADENA_BUSCAR .= $carac;	
			}
		}elseif($buscar_caracteristica){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = ".$acuerdo." AND CARACTERISTICA = '".$buscar_caracteristica."'";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND ACUERDO = '".$acuerdo."'";     
  		}									
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_precios'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_precios($id, $acuerdo, $temporada, $habitacion, $caracteristica, $uso, $coste_pax, $coste_habitacion, $calculo, $porcentaje_neto, $neto_pax, $neto_habitacion, $porcentaje_com, $pvp_pax, $pvp_habitacion, $temporada_old, $habitacion_old, $caracteristica_old, $uso_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_alojamientos_precios SET ";
		$query .= " TEMPORADA = '".$temporada."'";
		$query .= ", HABITACION = '".$habitacion."'";
		$query .= ", CARACTERISTICA = '".$caracteristica."'";
		$query .= ", USO = '".$uso."'";
		$query .= ", COSTE_PAX = '".$coste_pax."'";
		$query .= ", COSTE_HABITACION = '".$coste_habitacion."'";
		$query .= ", CALCULO = '".$calculo."'";
		$query .= ", PORCENTAJE_NETO = '".$porcentaje_neto."'";
		$query .= ", NETO_PAX = '".$neto_pax."'";
		$query .= ", NETO_HABITACION = '".$neto_habitacion."'";
		$query .= ", PORCENTAJE_COM = '".$porcentaje_com."'";
		$query .= ", PVP_PAX = '".$pvp_pax."'";
		$query .= ", PVP_HABITACION = '".$pvp_habitacion."'";
		$query .= " WHERE ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND TEMPORADA = '".$temporada_old."'";
		$query .= " AND HABITACION = '".$habitacion_old."'";
		$query .= " AND CARACTERISTICA = '".$caracteristica_old."'";
		$query .= " AND USO = '".$uso_old."'";

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

	function Borrar_precios($id,$acuerdo,$temporada, $habitacion, $caracteristica, $uso){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_alojamientos_precios WHERE ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND TEMPORADA = '".$temporada."'";
		$query .= " AND HABITACION = '".$habitacion."'";
		$query .= " AND CARACTERISTICA = '".$caracteristica."'";
		$query .= " AND USO = '".$uso."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_precios($id, $acuerdo, $temporada, $habitacion, $caracteristica, $uso, $coste_pax, $coste_habitacion, $calculo, $porcentaje_neto, $neto_pax, $neto_habitacion, $porcentaje_com, $pvp_pax, $pvp_habitacion){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_alojamientos_precios (ID, ACUERDO, TEMPORADA, HABITACION, CARACTERISTICA, USO, COSTE_PAX, COSTE_HABITACION, CALCULO, PORCENTAJE_NETO, NETO_PAX,	NETO_HABITACION, PORCENTAJE_COM, PVP_PAX, PVP_HABITACION) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$temporada."',";
		$query .= "'".$habitacion."',";
		$query .= "'".$caracteristica."',";
		$query .= "'".$uso."',";
		$query .= "'".$coste_pax."',";
		$query .= "'".$coste_habitacion."',";
		$query .= "'".$calculo."',";
		$query .= "'".$porcentaje_neto."',";
		$query .= "'".$neto_pax."',";
		$query .= "'".$neto_habitacion."',";
		$query .= "'".$porcentaje_com."',";
		$query .= "'".$pvp_pax."',";
		$query .= "'".$pvp_habitacion."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}


//----------------------------------------------------------------------
//------METODOS PARA ACUERDOS AUTOMATICOS------------------
//----------------------------------------------------------------------

	function Insertar_acuerdos_interfaz($id, $divisa, $interfaz, $fecha_desde, $fecha_hasta){

		$conexion = $this ->Conexion;



		//----------------------------------------------------
		//INSERTAMOS EL ACUERDO (TODAS LAS TABLAS)
		//----------------------------------------------------

		//HIT_ACUERDOS
		if($divisa == null){
			$divisa = 'EUR';
		}else{
			$datos_divisa =$conexion->query("SELECT codigo from hit_divisas where codigo_corto= '".$divisa."'");
			$odatos_divisa = $datos_divisa->fetch_assoc();
			$divisa= $odatos_divisa['codigo'];
			if($divisa == null){
				$divisa = 'EUR';
			}
		}

		$datos_hay_acuerdo =$conexion->query("select count(*) cantidad from hit_alojamientos_acuerdos where id = ".$id." and acuerdo between 6000 and 6999");
		$odatos_hay_acuerdo = $datos_hay_acuerdo->fetch_assoc();
		$hay_acuerdo= $odatos_hay_acuerdo['cantidad'];

		if($hay_acuerdo == 0){
			$numero_acuerdo = 6000;
		}else{
			$datos_numero_acuerdo =$conexion->query("select acuerdo+1 numero from hit_alojamientos_acuerdos where id = ".$id." and acuerdo between 6000 and 6999");
			$odatos_numero_acuerdo = $datos_numero_acuerdo->fetch_assoc();
			$numero_acuerdo= $odatos_numero_acuerdo['numero'];
		}


		$query = "INSERT INTO hit_alojamientos_acuerdos (ID, ACUERDO, INTERFAZ, TIPO, SITUACION, FECHA_DESDE, FECHA_HASTA, DIAS_ENTRADA, DESCRIPCION, CORRESPONSAL, CARACTERISTICA_BASE, DIVISA) VALUES (";
		$query .= "'".$id."',";
		$query .= $numero_acuerdo.",";
		$query .= "'".$interfaz."',";
		$query .= "'I',";
		$query .= "'A',";
		$query .= "'".$fecha_desde."',";
		$query .= "'".$fecha_hasta."',";
		$query .= "'LMXJVSD',";
		$query .= "'CONTRATO AUTOMATICO RESTEL',";
		$query .= "220,";
		$query .= "'DST',";
		$query .= "'".$divisa."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el acuerdo '.$conexion->error;
		}else{

			$respuesta = 'OK';

			//HIT_PERIODOS
			$query = "INSERT INTO hit_alojamientos_periodos (ID, ACUERDO, TEMPORADA, FECHA_DESDE, FECHA_HASTA, DIAS_RELEASE) VALUES (";
			$query .= "'".$id."',";
			$query .= $numero_acuerdo.",";
			$query .= "'1',";
			$query .= "'".$fecha_desde."',";
			$query .= "'".$fecha_hasta."',";
			$query .= "0)";

			$resultadoi =$conexion->query($query);

			if ($resultadoi == FALSE){
				$respuesta = 'No se ha podido insertar el periodo '.$conexion->error;
			}else{

				$respuesta = 'OK';

				//HIT_TEMPORADAS								
				$query = "INSERT INTO hit_alojamientos_temporadas (ID, ACUERDO, TEMPORADA, SPTO_AD, SPTO_MP, SPTO_PC, SPTO_TI) VALUES (";
				$query .= "'".$id."',";
				$query .= $numero_acuerdo.",";
				$query .= "'1',";
				$query .= "0,";
				$query .= "0,";
				$query .= "0,";
				$query .= "0)";

				$resultadoi =$conexion->query($query);

				if ($resultadoi == FALSE){
					$respuesta = 'No se ha podido insertar la temporada '.$conexion->error;
				}else{

					$respuesta = 'OK';

					//HIT_USOS								
					$query = "INSERT INTO hit_alojamientos_usos (ID, ACUERDO, CARACTERISTICA, MINIMO_DETALLE, USO_MINIMO, USO_MINIMO_ADULTOS, USO_MINIMO_NINOS, USO_MINIMO_BEBES, MAXIMO_DETALLE, USO_MAXIMO_PAX, USO_MAXIMO_ADULTOS, USO_MAXIMO_NINOS, USO_MAXIMO_BEBES) VALUES (";
					$query .= "'".$id."',";
					$query .= $numero_acuerdo.",";
					$query .= "'XXX',";
					$query .= "1,";
					$query .= "1,";
					$query .= "1,";
					$query .= "0,";
					$query .= "0,";
					$query .= "4,";
					$query .= "9,";
					$query .= "9,";
					$query .= "2,";
					$query .= "2)";

					$resultadoi =$conexion->query($query);

					if ($resultadoi == FALSE){
						$respuesta = 'No se ha podido insertar el uso '.$conexion->error;
					}else{

						$respuesta = 'OK';

						//HIT_PRECIOS						
						$query = "INSERT INTO hit_alojamientos_precios (ID, ACUERDO, TEMPORADA, HABITACION, CARACTERISTICA, USO) VALUES (";
						$query .= "'".$id."',";
						$query .= $numero_acuerdo.",";
						$query .= "1,";
						$query .= "'DBL',";
						$query .= "'XXX',";
						$query .= "2)";

						$resultadoi =$conexion->query($query);

						if ($resultadoi == FALSE){
							$respuesta = 'No se ha podido insertar el precio '.$conexion->error;
						}else{

							$respuesta = 'OK';
						}
					}
				}
			}
		}

		return $respuesta;


	}




	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsAcuerdos($conexion, $filadesde, $usuario, $buscar_id, $buscar_nombre, $buscar_acuerdo){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id = $buscar_id;
		$this->Buscar_nombre = $buscar_nombre;
		$this->Buscar_acuerdo = $buscar_acuerdo;
	}
}

?>