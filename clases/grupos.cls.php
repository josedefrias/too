<?php

class clsGrupos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var	$buscar_nombre;
	var	$buscar_clave_oficina;
	var	$buscar_fecha_salida;


//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------
	function Cargar($recuperaid,$recuperaclave_oficina,$recuperapersona_contacto,$recuperaestado,$recuperatelefono,$recuperaemail,$recuperanombre,$recuperatipo_viaje,$recuperatipo_grupo,$recuperaadultos_minimo,$recuperaadultos_maximo,$recuperaninos_minimo,$recuperaninos_maximo,$recuperaninos_edades,$recuperabebes_minimo,$recuperabebes_maximo,$recuperafecha_salida,$recuperafecha_regreso,$recuperanoches_fechas,$recuperaanno,$recuperames,$recuperaperiodo,$recuperanoches_periodo,$recuperaorigen,$recuperadestino,$recuperacategoria,$recuperasituacion,$recuperaregimen,$recuperaotros_aspectos,$recuperatraslado_entrada,$recuperatraslado_salida,$recuperaseguro_opcional, $recuperaexcursiones,$recuperaentradas,$recuperaobservaciones,$recuperaplazas_pago,$recuperagratuidades,$recuperaresponsable_gestion,$recuperafecha_ultimo_envio){


		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_grupo = $this ->Buscar_grupo;
		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_clave_oficina = $this ->Buscar_clave_oficina;
		$buscar_fecha_salida = $this ->Buscar_fecha_salida;
		
		if($buscar_grupo != null){
			$CADENA_BUSCAR = " AND g.ID = '".$buscar_grupo."'";
		}elseif($buscar_id != null){
			$CADENA_BUSCAR = " AND g.ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " AND g.NOMBRE LIKE '%".$buscar_nombre."%'";				
		}elseif($buscar_clave_oficina != null){
			$fec = " AND g.FECHA_SALIDA = '".date("Y-m-d",strtotime($buscar_fecha_salida))."'";
			$CADENA_BUSCAR = " AND g.CLAVE_OFICINA = '".$buscar_clave_oficina."'";
			if($buscar_fecha_salida != null){
				$CADENA_BUSCAR .= $fec;	
			}
		}elseif($buscar_fecha_salida != null){
			$CADENA_BUSCAR = " AND g.FECHA_SALIDA = '".date("Y-m-d",strtotime($buscar_fecha_salida))."'";
		}else{
			$CADENA_BUSCAR = "AND g.CLAVE_OFICINA = 0";
		}	

		$resultado =$conexion->query("SELECT 
										g.id,
										m.nombre nombre_agencia, o.oficina oficina_agencia, o.direccion direccion_agencia, o.localidad localidad_agencia, o.telefono telefono_agencia, o.mail mail_agencia, g.clave_oficina clave_oficina, 
										g.persona_contacto persona_contacto, g.estado estado, g.telefono telefono,g.email email,
										g.nombre nombre,g.tipo_viaje tipo_viaje,
										g.tipo_grupo tipo_grupo,

										g.adultos_minimo adultos_minimo,g.adultos_maximo adultos_maximo,g.ninos_minimo ninos_minimo,g.ninos_maximo ninos_maximo,g.ninos_edades ninos_edades,
										g.bebes_minimo bebes_minimo,g.bebes_maximo bebes_maximo,
										DATE_FORMAT(g.fecha_salida, '%d-%m-%Y') AS fecha_salida,
										DATE_FORMAT(g.fecha_regreso, '%d-%m-%Y') AS fecha_regreso,
										g.noches_fechas noches_fechas, g.anno anno, g.mes mes, g.periodo periodo, g.noches_periodo noches_periodo,
										g.origen origen, g.destino destino,
										g.categoria categoria,g.situacion situacion,g.regimen regimen,g.otros_aspectos otros_aspectos,
										g.traslado_entrada traslado_entrada,g.traslado_salida traslado_salida,g.seguro_opcional seguro_opcional, 
										
										g.excursiones excursiones,
										g.entradas entradas,
										
										g.observaciones observaciones,
										DATE_FORMAT(g.fecha_solicitud, '%d-%m-%Y') AS fecha_solicitud,

										g.plazas_pago plazas_pago,
										g.gratuidades gratuidades,
										g.responsable_gestion responsable_gestion,
										DATE_FORMAT(g.fecha_ultimo_envio, '%d-%m-%Y') AS fecha_ultimo_envio

										from hit_grupos g, hit_oficinas o, hit_minoristas m
										where
											g.CLAVE_OFICINA = o.CLAVE
											and o.ID = m.ID ".$CADENA_BUSCAR." ORDER BY g.nombre");
	
		//echo($CADENA_BUSCAR);

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$grupos = array();
		if($recuperanombre != null){
			$grupos[0] = array ("id" => $recuperaid,
								"clave_oficina" => $recuperaclave_oficina,
								"estado" => $recuperaestado,
								"persona_contacto" => $recuperapersona_contacto,
								"telefono" => $recuperatelefono,
								"mail" => recuperamail,
								"nombre" => $recuperanombre,
								"tipo_viaje" => $recuperatipo_viaje,
								"tipo_grupo" => $recuperatipo_grupo,
								"adultos_minimo" => $recuperaadultos_minimo,
								"adultos_maximo" => $recuperaadultos_maximo,
								"ninos_minimo" => $recuperaninos_minimo,
								"ninos_maximo" => $recuperaninos_maximo,
								"ninos_edades" => $recuperaninos_edades,
								"bebes_minimo" => $recuperabebes_minimo,
								"bebes_maximo" => $recuperabebes_maximo,
								"fecha_salida" => $recuperafecha_salida,
								"fecha_regreso" => $recuperafecha_regreso,
								"noches_fechas" => $recuperanoches_fechas,
								"anno" => $recuperaanno,
								"mes" => $recuperames,
								"periodo" => $recuperaperiodo,
								"recuperanoches_periodo" => $recuperanoches_periodo,
								"origen" => $origen,
								"destino" => $destino,
								"categoria" => $recuperacategoria,
								"situacion" => $recuperasituacion,
								"regimen" => $recuperaregimen,
								"otros_aspectos" => $recuperaotros_aspectos,
								"traslado_entrada" => $recuperatraslado_entrada,
								"traslado_salid" => $recuperatraslado_salida,
								"seguro_opcional" => $recuperaseguro_opcional, 
								"excursiones" => $recuperaexcursiones,
								"entradas" => $recuperaentradas,
								"observaciones" => $recuperaobservaciones);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($grupos,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $grupos;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_grupo = $this ->Buscar_grupo;
		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_clave_oficina = $this ->Buscar_clave_oficina;
		$buscar_fecha_salida = $this ->Buscar_fecha_salida;
		

		if($buscar_grupo != null){
			$CADENA_BUSCAR = " AND g.ID = '".$buscar_grupo."'";
		}elseif($buscar_id != null){
			$CADENA_BUSCAR = " AND g.ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " AND g.NOMBRE LIKE '%".$buscar_nombre."%'";				
		}elseif($buscar_clave_oficina != null){
			$fec = " AND g.FECHA_SALIDA = '".date("Y-m-d",strtotime($buscar_fecha_salida))."'";
			$CADENA_BUSCAR = " AND g.CLAVE_OFICINA = '".$buscar_clave_oficina."'";
			if($buscar_fecha_salida != null){
				$CADENA_BUSCAR .= $fec;	
			}
		}elseif($buscar_fecha_salida != null){
			$CADENA_BUSCAR = " AND g.FECHA_SALIDA = '".date("Y-m-d",strtotime($buscar_fecha_salida))."'";
		}else{
			$CADENA_BUSCAR = "AND g.CLAVE_OFICINA = 0";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * from hit_grupos g, hit_oficinas o, hit_minoristas m
										where g.CLAVE_OFICINA = o.CLAVE and o.ID = m.ID '.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS' AND USUARIO = '".$Usuario."'");
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
		
		$buscar_grupo = $this ->Buscar_grupo;
		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_clave_oficina = $this ->Buscar_clave_oficina;
		$buscar_fecha_salida = $this ->Buscar_fecha_salida;
		

		if($buscar_grupo != null){
			$CADENA_BUSCAR = " AND g.ID = '".$buscar_grupo."'";
		}elseif($buscar_id != null){
			$CADENA_BUSCAR = " AND g.ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " AND g.NOMBRE LIKE '%".$buscar_nombre."%'";				
		}elseif($buscar_clave_oficina != null){
			$fec = " AND g.FECHA_SALIDA = '".date("Y-m-d",strtotime($buscar_fecha_salida))."'";
			$CADENA_BUSCAR = " AND g.CLAVE_OFICINA = '".$buscar_clave_oficina."'";
			if($buscar_fecha_salida != null){
				$CADENA_BUSCAR .= $fec;	
			}
		}elseif($buscar_fecha_salida != null){
			$CADENA_BUSCAR = " AND g.FECHA_SALIDA = '".date("Y-m-d",strtotime($buscar_fecha_salida))."'";
		}else{
			$CADENA_BUSCAR = "AND g.CLAVE_OFICINA = 0";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * from hit_grupos g, hit_oficinas o, hit_minoristas m
										where g.CLAVE_OFICINA = o.CLAVE and o.ID = m.ID '.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id,$persona_contacto,$estado,$telefono,$email,$nombre,$tipo_viaje,$tipo_grupo,$adultos_minimo,$adultos_maximo,$ninos_minimo,$ninos_maximo,$ninos_edades,$bebes_minimo,
		$bebes_maximo,$fecha_salida,$fecha_regreso,$noches_fechas,$anno,$mes,$periodo,$noches_periodo, $origen,$destino, $categoria,$situacion,$regimen,$otros_aspectos,$traslado_entrada,$traslado_salida,$seguro_opcional, $excursiones,$entradas,$observaciones,$plazas_pago,$gratuidades,$responsable_gestion){
		
		$conexion = $this ->Conexion;
		$query = "UPDATE hit_grupos SET ";
		$query .= " PERSONA_CONTACTO = '".$persona_contacto."'";
		$query .= ", ESTADO = '".$estado."'";
		$query .= ", TELEFONO = '".$telefono."'"; 
		$query .= ", EMAIL = '".$email."'"; 
		$query .= ", NOMBRE = '".$nombre."'";
		$query .= ", TIPO_VIAJE = '".$tipo_viaje."'";
		$query .= ", TIPO_GRUPO = '".$tipo_grupo."'";
		$query .= ", ADULTOS_MINIMO = '".$adultos_minimo."'";
		$query .= ", ADULTOS_MAXIMO = '".$adultos_maximo."'";
		$query .= ", NINOS_MINIMO = '".$ninos_minimo."'";
		$query .= ", NINOS_MAXIMO = '".$ninos_maximo."'";
		$query .= ", NINOS_EDADES = '".$ninos_edades."'";
		$query .= ", BEBES_MINIMO = '".$bebes_minimo."'";
		$query .= ", BEBES_MAXIMO = '".$bebes_maximo."'";
		$query .= ", FECHA_SALIDA = '".date("Y-m-d",strtotime($fecha_salida))."'";
		$query .= ", FECHA_REGRESO = '".date("Y-m-d",strtotime($fecha_regreso))."'";
		$query .= ", NOCHES_FECHAS = '".$noches_fechas."'";
		$query .= ", ANNO = '".$anno."'";
		$query .= ", MES = '".$mes."'";
		$query .= ", PERIODO = '".$periodo."'";
		$query .= ", NOCHES_PERIODO = '".$noches_periodo."'";
		$query .= ", ORIGEN = '".$origen."'";
		$query .= ", DESTINO = '".$destino."'";
		$query .= ", CATEGORIA = '".$categoria."'";
		$query .= ", SITUACION = '".$situacion."'";
		$query .= ", REGIMEN = '".$regimen."'";
		$query .= ", OTROS_ASPECTOS = '".$otros_aspectos."'";
		$query .= ", TRASLADO_ENTRADA = '".$traslado_entrada."'";
		$query .= ", TRASLADO_SALIDA = '".$traslado_salida."'";
		$query .= ", SEGURO_OPCIONAL = '".$seguro_opcional."'";
		$query .= ", EXCURSIONES = '".$excursiones."'";
		$query .= ", ENTRADAS = '".$entradas."'";
		$query .= ", OBSERVACIONES = '".$observaciones."'";
		$query .= ", PLAZAS_PAGO = '".$plazas_pago."'";
		$query .= ", GRATUIDADES = '".$gratuidades."'";
		$query .= ", RESPONSABLE_GESTION = '".$responsable_gestion."'";
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

		$query = "DELETE FROM hit_grupos WHERE ID = '".$id."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($clave_oficina,$persona_contacto,$estado,$telefono,$email,$nombre,$tipo_viaje,$tipo_grupo,$adultos_minimo,$adultos_maximo,$ninos_minimo,$ninos_maximo,$ninos_edades,$bebes_minimo,$bebes_maximo,$fecha_salida,$fecha_regreso,$noches_fechas,$anno,$mes,$periodo,$noches_periodo, $origen,$destino,$categoria,$situacion,$regimen,$otros_aspectos,$traslado_entrada,$traslado_salida,$seguro_opcional, $excursiones,$entradas,$observaciones,$plazas_pago,$gratuidades,$responsable_gestion){

		$conexion = $this ->Conexion;

		$query = "INSERT INTO hit_grupos (CLAVE_OFICINA,PERSONA_CONTACTO,ESTADO,TELEFONO,EMAIL, NOMBRE,TIPO_VIAJE,TIPO_GRUPO,ADULTOS_MINIMO,ADULTOS_MAXIMO,
					NINOS_MINIMO,NINOS_MAXIMO,NINOS_EDADES,BEBES_MINIMO,BEBES_MAXIMO,FECHA_SALIDA,FECHA_REGRESO,
					NOCHES_FECHAS,ANNO,MES,PERIODO,NOCHES_PERIODO,ORIGEN,DESTINO,CATEGORIA,SITUACION,REGIMEN,OTROS_ASPECTOS,TRASLADO_ENTRADA,
					TRASLADO_SALIDA,SEGURO_OPCIONAL,EXCURSIONES,ENTRADAS,OBSERVACIONES,PLAZAS_PAGO,GRATUIDADES,RESPONSABLE_GESTION,FECHA_SOLICITUD) VALUES (";
		$query .= "'".$clave_agencia."',";
		$query .= "'".$persona_contacto."',";
		$query .= "'".$estado."',";
		$query .= "'".$telefono."',"; 
		$query .= "'".$email."',"; 
		$query .= "'".$nombre."',";
		$query .= "'".$tipo_viaje."',";
		$query .= "'".$tipo_grupo."',";
		$query .= "'".$adultos_minimo."',";
		$query .= "'".$adultos_maximo."',";
		$query .= "'".$ninos_minimo."',";
		$query .= "'".$ninos_maximo."',";
		$query .= "'".$ninos_edades."',";
		$query .= "'".$bebes_minimo."',";
		$query .= "'".$bebes_maximo."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_salida))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_regreso))."',";
		$query .= "'".$noches_fechas."',";
		$query .= "'".$anno."',";
		$query .= "'".$mes."',";
		$query .= "'".$periodo."',";
		$query .= "'".$noches_periodo."',";
		$query .= "'".$origen."',";
		$query .= "'".$dstino."',";
		$query .= "'".$categoria."',";
		$query .= "'".$situacion."',";
		$query .= "'".$regimen."',";
		$query .= "'".$otros_aspectos."',";
		$query .= "'".$traslado_entrada."',";
		$query .= "'".$traslado_salida."',";
		$query .= "'".$seguro_opcional."',";
		$query .= "'".$excursiones."',";
		$query .= "'".$entradas."',";
		$query .= "'".$observaciones."',";
		$query .= "'".$plazas_pago."',";
		$query .= "'".$gratuidades."',";
		$query .= "'".$responsable_gestion."',";
		$query .= "CURDATE())";


		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}




//--------------------------------------------------------------------
//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE VUELOS------
//--------------------------------------------------------------------
	//----------------------------------------------------------------

	function Cargar_vuelos($id, $filadesde){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		/*if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{*/
			$CADENA_BUSCAR = " WHERE ID_GRUPO = '".$id."'";     
  		//}

		$resultado =$conexion->query("SELECT id id_vuelo, tipo tipo_vuelo, orden orden_vuelo, cia cia_vuelo, vuelo vuelo_vuelo, origen origen_vuelo, destino destino_vuelo, 
			time_format(hora_salida, '%H:%i') AS hora_salida_vuelo, time_format(hora_llegada, '%H:%i') AS hora_llegada_vuelo FROM hit_grupos_vuelos ".$CADENA_BUSCAR." ORDER BY tipo, orden");



		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_VUELOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$precios = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['id_vuelo'] == ''){
				break;
			}
			array_push($precios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $precios;											
	}


	function Cargar_lineas_nuevas_vuelos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_VUELOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_vuelos($id){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		/*if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{*/
			$CADENA_BUSCAR = " WHERE ID_GRUPO = '".$id."'";     
  		//}									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_grupos_vuelos'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------


		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_VUELOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_vuelos($filadesde, $boton, $id){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;
		
		/*if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{*/
			$CADENA_BUSCAR = " WHERE ID_GRUPO = '".$id."'";     
  		//}									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_grupos_vuelos'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------
		
		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_VUELOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_vuelos($id, $tipo, $orden, $cia, $vuelo, $origen, $destino, $hora_salida, $hora_llegada){

		//$fecha2=date("Y-m-d",strtotime($fecha_desde));
		//echo(date("Y-m-d",strtotime($fecha_desde)));

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_grupos_vuelos SET ";
		$query .= " TIPO = '".$tipo."'";
		$query .= ", ORDEN = '".$orden."'";
		$query .= ", CIA = '".$cia."'";
		$query .= ", VUELO = '".$vuelo."'";
		$query .= ", ORIGEN = '".$origen."'";
		$query .= ", DESTINO = '".$destino."'";
		$query .= ", HORA_SALIDA = '".$hora_salida."'";
		$query .= ", HORA_LLEGADA = '".$hora_llegada."'";
		$query .= " WHERE ID = '".$id."'";


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

	function Borrar_vuelos($id){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_grupos_vuelos WHERE ID = '".$id."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_vuelos($id_grupo, $tipo, $orden, $cia, $vuelo, $origen, $destino, $hora_salida, $hora_llegada){

		//ECHO('HOLA');
		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_grupos_vuelos (ID_GRUPO, TIPO, ORDEN, CIA, VUELO, ORIGEN, DESTINO, HORA_SALIDA, HORA_LLEGADA) VALUES (";
		$query .= "'".$id_grupo."',";
		$query .= "'".$tipo."',";
		$query .= "'".$orden."',";
		$query .= "'".$cia."',";
		$query .= "'".$vuelo."',";
		$query .= "'".$origen."',";
		$query .= "'".$destino."',";
		$query .= "'".$hora_salida."',";
		$query .= "'".$hora_llegada."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											

	}



//----------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE PRESUPUESTOS----
//----------------------------------------------------------------------

	function Cargar_presupuestos($id, $filadesde){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	

		/*if($buscar_temporada != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";
		}else{*/
			$CADENA_BUSCAR = " WHERE ID_GRUPO = '".$id."'";     
  		//}

		//ECHO($CADENA_BUSCAR);

		$resultado =$conexion->query("SELECT id id_presupuesto, orden orden_presupuesto, alojamiento alojamiento_presupuesto, doble doble_presupuesto, single single_presupuesto, triple triple_presupuesto, multiple multiple_presupuesto, ninos ninos_presupuesto,bebes bebes_presupuesto, bebes_maximo bebes_maximo_presupuesto, tasas tasas_presupuesto  FROM hit_grupos_presupuestos ".$CADENA_BUSCAR." ORDER BY orden");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_PRESUPUESTOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$presupuestos = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['id_presupuesto'] == ''){
				break;
			}
			array_push($presupuestos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $presupuestos;											
	}

	function Cargar_lineas_nuevas_presupuestos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_PRESUPUESTOS' AND USUARIO = '".$Usuario."'");
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

	function Cargar_combo_selector_presupuestos($id){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		/*if($buscar_temporada != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";
		}else{*/
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";     
  		//}								
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_grupos_presupuestos'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_PRESUPUESTOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_presupuestos($filadesde, $boton, $id){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		/*if($buscar_temporada != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";
		}else{*/
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";     
  		//}								
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_grupos_presupuestos'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_PRESUPUESTOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_presupuestos($id, $orden, $alojamiento, $doble, $single, $triple, $multiple, $ninos, $bebes, $bebes_maximo, $tasas){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_grupos_presupuestos SET ";
		$query .= " ORDEN = '".$orden."'";
		$query .= ", ALOJAMIENTO = '".$alojamiento."'";
		$query .= ", DOBLE = '".$doble."'";
		$query .= ", SINGLE = '".$single."'";
		$query .= ", TRIPLE = '".$triple."'";
		$query .= ", MULTIPLE = '".$multiple."'";
		$query .= ", NINOS = '".$ninos."'";
		$query .= ", BEBES = '".$bebes."'";
		$query .= ", BEBES_MAXIMO = '".$bebes_maximo."'";
		$query .= ", TASAS = '".$tasas."'";
		$query .= " WHERE ID = '".$id."'";


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

	function Borrar_presupuestos($id){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_grupos_presupuestos WHERE ID = '".$id."'";


		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_presupuestos($id_grupo, $orden, $alojamiento, $doble, $single, $triple, $multiple, $ninos, $bebes, $bebes_maximo, $tasas){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_grupos_presupuestos (ID_GRUPO, ORDEN, ALOJAMIENTO, DOBLE, SINGLE, TRIPLE, MULTIPLE, NINOS, BEBES, BEBES_MAXIMO, TASAS) VALUES (";
		$query .= "'".$id_grupo."',";
		$query .= "'".$orden."',";
		$query .= "'".$alojamiento."',";
		$query .= "'".$doble."',";
		$query .= "'".$single."',";
		$query .= "'".$triple."',";
		$query .= "'".$multiple."',";
		$query .= "'".$ninos."',";
		$query .= "'".$bebes."',";
		$query .= "'".$bebes_maximo."',";
		$query .= "'".$tasas."')";

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

	function clsGrupos($conexion, $filadesde, $usuario, $buscar_grupo, $buscar_id, $buscar_nombre,$buscar_clave_oficina, $buscar_fecha_salida){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_grupo = $buscar_grupo;
		$this->Buscar_id = $buscar_id;
		$this->Buscar_nombre = $buscar_nombre;
		$this->Buscar_clave_oficina = $buscar_clave_oficina;
		$this->Buscar_fecha_salida = $buscar_fecha_salida;

	}
}

?>		
