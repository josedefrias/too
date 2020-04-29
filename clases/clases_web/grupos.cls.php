<?php

class clsGrupos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;




//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----Ñ
//------------------------------------------------------------------
	function Cargar($recuperaid,$recuperaclave_oficina,$recuperapersona_contacto,$recuperatelefono,$recuperaemail,$recuperanombre,$recuperatipo_viaje,$recuperatipo_grupo,$recuperaadultos_minimo,$recuperaadultos_maximo,$recuperaninos_minimo,$recuperaninos_maximo,$recuperaninos_edades,$recuperabebes_minimo,$recuperabebes_maximo,$recuperafecha_salida,$recuperafecha_regreso,$recuperanoches_fechas,$recuperaanno,$recuperames,$recuperaperiodo,$recuperanoches_periodo,$recuperaorigen,$recuperadestino,$recuperacategoria,$recuperasituacion,$recuperaotros_aspectos,$recuperatraslado_entrada,$recuperatraslado_salida,$recuperaseguro_opcional, $recuperaexcursiones,$recuperaentradas,$recuperaobservaciones){


		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_id = $this ->Buscar_id;


		if($buscar_id != null){
			$CADENA_BUSCAR = " AND g.ID = '".$buscar_id."'";
		}else{
			$CADENA_BUSCAR = "AND g.CLAVE_OFICINA = 0";
		}	

		$resultado =$conexion->query("SELECT 
										g.id id,
										m.nombre nombre_agencia, o.oficina oficina_agencia, o.direccion direccion_agencia, o.localidad localidad_agencia, o.telefono telefono_agencia, o.mail mail_agencia, g.clave_oficina clave_oficina, 
										case g.estado 
											when 'N' then 'Nuevo'
											when 'P' then 'En Proceso'
											when 'M' then 'Modificado'
											when 'A' then 'Aprobado'
											when 'C' then 'Cancelado'
											else 'Nuevo'
										end estado,
										g.persona_contacto persona_contacto,g.telefono telefono,g.email email,
										g.nombre nombre,g.tipo_viaje tipo_viaje,
										g.tipo_grupo tipo_grupo,

										g.adultos_minimo adultos_minimo,g.adultos_maximo adultos_maximo,g.ninos_minimo ninos_minimo,g.ninos_maximo ninos_maximo,g.ninos_edades ninos_edades,
										g.bebes_minimo bebes_minimo,g.bebes_maximo bebes_maximo,

										DATE_FORMAT(g.fecha_salida, '%d-%m-%Y') AS fecha_salida,

										DATE_FORMAT(g.fecha_regreso, '%d-%m-%Y') AS fecha_regreso,

										g.noches_fechas noches_fechas, g.anno anno, g.mes mes, g.periodo periodo, g.noches_periodo noches_periodo,
										g.origen origen, g.destino destino,
										g.categoria categoria,g.situacion situacion,g.otros_aspectos otros_aspectos,
										g.traslado_entrada traslado_entrada,g.traslado_salida traslado_salida,g.seguro_opcional seguro_opcional, 
										
										g.excursiones excursiones,
										g.entradas entradas,
										
										g.observaciones observaciones

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

								                        //------

		$grupos = array();
		if($recuperanombre != null){
			$grupos[0] = array ("id" => $recuperaid,
								"clave_oficina" => $recuperaclave_oficina,
								"persona_contacto" => $recuperapersona_contacto,
								"telefono" => $recuperatelefono,
								"mail" => $recuperaemail,
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
								"origen" => $recuperaorigen,
								"destino" => $recuperadestino,
								"categoria" => $recuperacategoria,
								"situacion" => $recuperasituacion,
								"otros_aspectos" => $recuperaotros_aspectos,
								"traslado_entrada" => $recuperatraslado_entrada,
								"traslado_salid" => $recuperatraslado_salida,
								"seguro_opcional" => $recuperaseguro_opcional, 
								"excursiones" => $recuperaexcursiones,
								"entradas" => $recuperaentradas,
								"observaciones" => $recuperaobservaciones);
		}else{
			for ($num_fila = 0; $num_fila < 1; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($grupos,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $grupos;											
	}


/*	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_clave_oficina = $this ->Buscar_clave_oficina;
		$buscar_fecha_salida = $this ->Buscar_fecha_salida;
		

		if($buscar_id != null){
			$CADENA_BUSCAR = " AND g.ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " AND g.NOMBRE LIKE '%".$buscar_nombre."%'";				
		}elseif($buscar_clave_oficina != null){
			$fec = " AND g.FECHA_SALIDA = '".$buscar_fecha_salida."'";
			$CADENA_BUSCAR = " AND g.CLAVE_OFICINA = '".$buscar_clave_oficina."'";
			if($buscar_fecha_salida != null){
				$CADENA_BUSCAR .= $fec;	
			}
		}elseif($buscar_fecha_salida != null){
			$CADENA_BUSCAR = " AND g.CLAVE_OFICINA = '".$buscar_fecha_salida."'";
		}else{
			$CADENA_BUSCAR = "AND g.CLAVE_OFICINA = 0";
		}											


		$resultadoc =$conexion->query('SELECT * from hit_grupos g, hit_oficinas o, hit_minoristas m
										where g.CLAVE_OFICINA = o.CLAVE and o.ID = m.ID '.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------



		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS' AND USUARIO = '".$Usuario."'");
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
									
		$resultadoc =$conexion->query('SELECT * FROM hit_grupos');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}


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

					$selector = $cada - $Nfilas['LINEAS_MODIFICACION'];
				}
			}
		}

		$resultadoc->close();
		$num_filas->close();
		return $selector;											
	}*/

	function Modificar($id,$persona_contacto,$telefono,$email,$nombre,$tipo_viaje,$tipo_grupo,$adultos_minimo,$adultos_maximo,$ninos_minimo,$ninos_maximo,$ninos_edades,$bebes_minimo,
		$bebes_maximo,$fecha_salida,$fecha_regreso,$noches_fechas,$anno,$mes,$periodo,$noches_periodo, $origen,$destino, $categoria,$situacion,$otros_aspectos,$traslado_entrada,$traslado_salida,$seguro_opcional, $excursiones,$entrada,$observaciones){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_grupos SET ";
		$query .= " PERSONA_CONTACTO = '".$persona_contacto."'";
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
		$query .= ", OTROS_ASPECTOS = '".$otros_aspectos."'";
		$query .= ", TRASLADO_ENTRADA = '".$traslado_entrada."'";
		$query .= ", TRASLADO_SALIDA = '".$traslado_salida."'";
		$query .= ", SEGURO_OPCIONAL = '".$seguro_opcional."'";
		$query .= ", EXCURSIONES = '".$excursiones."'";
		$query .= ", ENTRADAS = '".$excursiones."'";
		$query .= ", OBSERVACIONES = '".$observaciones."'";
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
		$query = "UPDATE hit_grupos SET ";
		$query .= " ESTADO = 'C'";
		$query .= " WHERE ID = '".$id."'";;

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($clave_oficina,$persona_contacto,$telefono,$email,$nombre,$tipo_viaje,$tipo_grupo,$adultos_minimo,$adultos_maximo,$ninos_minimo,$ninos_maximo,$ninos_edades,$bebes_minimo,
		$bebes_maximo,$fecha_salida,$fecha_regreso,$noches_fechas,$anno,$mes,$periodo,$noches_periodo, $origen,$destino, $categoria,$situacion,$otros_aspectos,$traslado_entrada,$traslado_salida,$seguro_opcional, $excursiones,$entradas,$observaciones){

		$conexion = $this ->Conexion;

		$query = "INSERT INTO hit_grupos (CLAVE_OFICINA, PERSONA_CONTACTO, ESTADO, TELEFONO, EMAIL, NOMBRE, TIPO_VIAJE, TIPO_GRUPO, ADULTOS_MINIMO, ADULTOS_MAXIMO,
					NINOS_MINIMO,NINOS_MAXIMO,NINOS_EDADES,BEBES_MINIMO,BEBES_MAXIMO,FECHA_SALIDA,FECHA_REGRESO,
					NOCHES_FECHAS,ANNO,MES,PERIODO,NOCHES_PERIODO,ORIGEN,DESTINO,CATEGORIA,SITUACION,OTROS_ASPECTOS,TRASLADO_ENTRADA,
					TRASLADO_SALIDA,SEGURO_OPCIONAL,EXCURSIONES,ENTRADAS,OBSERVACIONES) VALUES (";
		$query .= "'".$clave_oficina."',";
		$query .= "'".$persona_contacto."',";
		$query .= "'N',";
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
		$query .= "'".$destino."',";
		$query .= "'".$categoria."',";
		$query .= "'".$situacion."',";
		$query .= "'".$otros_aspectos."',";
		$query .= "'".$traslado_entrada."',";
		$query .= "'".$traslado_salida."',";
		$query .= "'".$seguro_opcional."',";
		$query .= "'".$excursiones."',";
		$query .= "'".$entradas."',";
		$query .= "'".$observaciones."')";

		//echo($query);
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

	function clsGrupos($conexion, $filadesde, $usuario, $buscar_id){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id = $buscar_id;



	}
}

?>		
