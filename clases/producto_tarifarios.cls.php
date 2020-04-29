<?php

class clsTarifarios{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var $buscar_id_prove;
	var	$buscar_codigo;
	var	$buscar_tipo;
	var	$buscar_ciudad;
	//-----------------------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------

/*$recuperaclave,$recuperaentidad ,$recuperaanno,$recuperadestino,$recuperanoches,$recuperanombre,$recuperafolleto_base,$recuperacuadro_base,$recuperafecha_desde,$recuperafecha_hasta,$recuperadias_semana,$recuperadivisa,$recuperaredondeo,$recuperamargen_transportes,$recuperaocupacion_transportes,$recuperamargen_alojamientos,$recuperamargen_servicios*/


	function Cargar($recuperaentidad ,$recuperaanno,$recuperadestino,$recuperanoches,$recuperanombre,$recuperafolleto_base,$recuperafecha_desde,$recuperafecha_hasta,$recuperadias_semana,$recuperadivisa,$recuperaredondeo,$recuperamargen_transportes,$recuperaocupacion_transportes,$recuperamargen_alojamientos,$recuperamargen_servicios,$recuperaaplicar_descuento_antelacion,$recuperaciudad_salida){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla

		$buscar_entidad = $this ->Buscar_entidad;
		$buscar_anno = $this ->Buscar_anno;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_noches = $this ->Buscar_noches;
		$buscar_nombre = $this ->Buscar_nombre;


		if($buscar_entidad != null){
			$ann = " AND ANNO = '".$buscar_anno."'";
			$des = " AND DESTINO = '".$buscar_destino."'";
			$noc = " AND NOCHES = '".$buscar_noches."'";
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE ENTIDAD = '".$buscar_entidad."'";
			if($buscar_anno != null){
				$CADENA_BUSCAR .= $ann;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_anno != null){
			$des = " AND DESTINO = '".$buscar_destino."'";
			$noc = " AND NOCHES = '".$buscar_noches."'";
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE ANNO = '".$buscar_anno."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_destino != null){
			$noc = " AND NOCHES = '".$buscar_noches."'";
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE DESTINO = '".$buscar_destino."'";
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_noches != null){
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE NOCHES = '".$buscar_noches."'";

			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE = 0";
		}



			$resultado =$conexion->query("select clave, entidad, anno, destino, noches, nombre, folleto_base, cuadro_base, DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde, DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta, dias_semana, divisa, redondeo, margen_transportes, ocupacion_transportes, margen_alojamientos, margen_servicios, aplicar_descuento_antelacion, ciudad_salida
			  from hit_tarifarios ".$CADENA_BUSCAR." ORDER BY entidad, anno, nombre");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TARIFARIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$tarifarios = array();
		if($recuperaentidad != null){
			$tarifarios[0] = array ("entidad" => $recuperaentidad, "anno" => $recuperaanno, "destino" => $recuperadestino, "noches" => $recuperanoches, "nombre" => $recuperanombre, "folleto_base" => $recuperafolleto_base, "cuadro_base" => $recuperacuadro_base, "fecha_desde" => $recuperafecha_desde, "fecha_hasta" => $recuperafecha_hasta, "dias_semana" => $recuperadias_semana, "divisa" => $recuperadivisa, "redondeo" => $recuperaredondeo, "margen_transportes" => $recuperamargen_transportes, "ocupacion_transportes" => $recuperaocupacion_transportes, "margen_alojamientos" => $recuperamargen_alojamientos, "margen_servicios" => $recuperamargen_servicios, "aplicar_descuento_antelacion" => $recuperaaplicar_descuento_antelacion, "ciudad_salida" => $recuperaciudad_salida);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($tarifarios,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $tarifarios;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TARIFARIOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_entidad = $this ->Buscar_entidad;
		$buscar_anno = $this ->Buscar_anno;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_noches = $this ->Buscar_noches;
		$buscar_nombre = $this ->Buscar_nombre;


		if($buscar_entidad != null){
			$ann = " AND ANNO = '".$buscar_anno."'";
			$des = " AND DESTINO = '".$buscar_destino."'";
			$noc = " AND NOCHES = '".$buscar_noches."'";
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE ENTIDAD = '".$buscar_entidad."'";
			if($buscar_anno != null){
				$CADENA_BUSCAR .= $ann;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_anno != null){
			$des = " AND DESTINO = '".$buscar_destino."'";
			$noc = " AND NOCHES = '".$buscar_noches."'";
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE ANNO = '".$buscar_anno."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_destino != null){
			$noc = " AND NOCHES = '".$buscar_noches."'";
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE DESTINO = '".$buscar_destino."'";
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_noches != null){
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE NOCHES = '".$buscar_noches."'";

			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE = 0";
		}									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_tarifarios'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TARIFARIOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_entidad = $this ->Buscar_entidad;
		$buscar_anno = $this ->Buscar_anno;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_noches = $this ->Buscar_noches;
		$buscar_nombre = $this ->Buscar_nombre;


		if($buscar_entidad != null){
			$ann = " AND ANNO = '".$buscar_anno."'";
			$des = " AND DESTINO = '".$buscar_destino."'";
			$noc = " AND NOCHES = '".$buscar_noches."'";
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE ENTIDAD = '".$buscar_entidad."'";
			if($buscar_anno != null){
				$CADENA_BUSCAR .= $ann;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_anno != null){
			$des = " AND DESTINO = '".$buscar_destino."'";
			$noc = " AND NOCHES = '".$buscar_noches."'";
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE ANNO = '".$buscar_anno."'";
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $des;	
			}
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_destino != null){
			$noc = " AND NOCHES = '".$buscar_noches."'";
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE DESTINO = '".$buscar_destino."'";
			if($buscar_noches != null){
				$CADENA_BUSCAR .= $noc;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_noches != null){
			$nom = " AND NOMBRE LIKE '%".$buscar_nombre."%' ";
			$CADENA_BUSCAR = " WHERE NOCHES = '".$buscar_noches."'";

			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nom;	
			}
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%' ";
		}else{
			$CADENA_BUSCAR = " WHERE CLAVE = 0";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_tarifarios'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TARIFARIOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($clave,$entidad ,$anno,$destino,$noches,$nombre,$folleto_base,$cuadro_base,$fecha_desde,$fecha_hasta,$dias_semana,$divisa,$redondeo,$margen_transportes,$ocupacion_transportes,$margen_alojamientos,$margen_servicios,$aplicar_descuento_antelacion, $ciudad_salida){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_tarifarios SET ";
		$query .= " ENTIDAD = '".$entidad."'";
		$query .= ", ANNO = '".$anno."'";
		$query .= ", DESTINO = '".$destino."'";
		$query .= ", NOCHES = '".$noches."'";
		$query .= ", NOMBRE = '".$nombre."'";
		$query .= ", FOLLETO_BASE = '".$folleto_base."'";
		$query .= ", CUADRO_BASE = '".$cuadro_base."'";
		$query .= ", FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= ", DIAS_SEMANA = '".$dias_semana."'";
		$query .= ", DIVISA = '".$divisa."'";
		$query .= ", REDONDEO = '".$redondeo."'";
		$query .= ", MARGEN_TRANSPORTES = '".$margen_transportes."'";
		$query .= ", OCUPACION_TRANSPORTES = '".$ocupacion_transportes."'";
		$query .= ", MARGEN_ALOJAMIENTOS = '".$margen_alojamientos."'";
		$query .= ", MARGEN_SERVICIOS = '".$margen_servicios."'";
		$query .= ", APLICAR_DESCUENTO_ANTELACION = '".$aplicar_descuento_antelacion."'";
		$query .= ", CIUDAD_SALIDA = '".$ciudad_salida."'";
		$query .= " WHERE CLAVE = '".$clave."'";

		//echo($query);

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($clave){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_tarifarios WHERE CLAVE = '".$clave."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($entidad ,$anno,$destino,$noches,$nombre,$folleto_base,$cuadro_base,$fecha_desde,$fecha_hasta,$dias_semana,$divisa,$redondeo,$margen_transportes,$ocupacion_transportes,$margen_alojamientos,$margen_servicios,$aplicar_descuento_antelacion, $ciudad_salida){

		$conexion = $this ->Conexion;


		$query = "INSERT INTO hit_tarifarios(ENTIDAD,ANNO,DESTINO,NOCHES,NOMBRE,FOLLETO_BASE,CUADRO_BASE,FECHA_DESDE,FECHA_HASTA,DIAS_SEMANA,DIVISA,
		REDONDEO,MARGEN_TRANSPORTES,OCUPACION_TRANSPORTES,MARGEN_ALOJAMIENTOS,MARGEN_SERVICIOS,APLICAR_DESCUENTO_ANTELACION,CIUDAD_SALIDA) VALUES (";
		$query .= "'".$entidad."',";
		$query .= "'".$anno."',";
		$query .= "'".$destino."',";
		$query .= "'".$noches."',";
		$query .= "'".$nombre."',";
		$query .= "'".$folleto_base."',";
		$query .= "'".$cuadro_base."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta))."',";
		$query .= "'".$dias_semana."',";
		$query .= "'".$divisa."',";
		$query .= "'".$redondeo."',";
		$query .= "'".$margen_transportes."',";
		$query .= "'".$ocupacion_transportes."',";
		$query .= "'".$margen_alojamientos."',";
		$query .= "'".$margen_servicios."',";
		$query .= "'".$aplicar_descuento_antelacion."',";
		$query .= "'".$ciudad_salida."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE PRECIOS----
//--------------------------------------------------------------------


	function Cargar_precios_cabecera($clave, $fecha_desde, $fecha_hasta){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		$resultado =$conexion->query("select distinct DATE_FORMAT(tp.FECHA, '%d-%m') AS fecha
										from hit_tarifarios_precios tp
										where tp.CLAVE_TARIFARIO = '".$clave."' and tp.FECHA between '".date("Y-m-d",strtotime($fecha_desde))."' and '".date("Y-m-d",strtotime($fecha_hasta))."' order by tp.FECHA");

		$precios = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($precios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $precios;											
	}

	function Cargar_nombres_cabecera($clave, $fecha_desde, $fecha_hasta){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		$resultado =$conexion->query("select distinct DATE_FORMAT(tp.FECHA, '%d_%m') AS fecha
										from hit_tarifarios_precios tp
										where tp.CLAVE_TARIFARIO = '".$clave."' and tp.FECHA between '".date("Y-m-d",strtotime($fecha_desde))."' and '".date("Y-m-d",strtotime($fecha_hasta))."' order by tp.FECHA");

		$nombres = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($nombres,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $nombres;											
	}


	function Cargar_precios($clave, $fecha_desde, $fecha_hasta, $redondeo){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

		//Obtenemos fechas del tarifario
		$Datos_fechas =$conexion->query("select distinct tp.FECHA
											from hit_tarifarios_precios tp
										where tp.CLAVE_TARIFARIO = '".$clave."' and tp.FECHA between '".date("Y-m-d",strtotime($fecha_desde))."' and '".date("Y-m-d",strtotime($fecha_hasta))."' order by tp.FECHA");		
		$columnas_fechas = $Datos_fechas->num_rows;	

		for ($i = 0; $i <= $Datos_fechas->num_rows; $i++) {
				$Datos_fechas->data_seek($i);
				$fila = $Datos_fechas->fetch_assoc();
		}

		//Obtenemos lineas del tarifario
		$query = "select a.NOMBRE nombre, cat.NOMBRE cat, hc.NOMBRE habitacion, t.NOCHES noches, tp.REGIMEN regimen ";
			
		for ($i = 0; $i < $Datos_fechas->num_rows; $i++) {	
			$Datos_fechas->data_seek($i);
			$fila = $Datos_fechas->fetch_assoc();
			if($i == 0)		{		
				$query .= ", sum(case tp.FECHA
								when '".$fila['FECHA']."' then round(tp.PRECIO_TRANSPORTES + tp.PRECIO_ALOJAMIENTOS + tp.PRECIO_SERVICIOS,".$redondeo.")
							end) ".date("d_m",strtotime($fila['FECHA']));
			}else{
									
				$query .= ", sum(case tp.FECHA
									when '".$fila['FECHA']."' then round(tp.PRECIO_TRANSPORTES + tp.PRECIO_ALOJAMIENTOS + tp.PRECIO_SERVICIOS,".$redondeo.")
								end) ".date("d_m",strtotime($fila['FECHA']));
			}
		}					

		$query .= " from hit_tarifarios_precios tp, hit_alojamientos a, hit_habitaciones_car hc, hit_tarifarios t, hit_categorias cat
							where t.CLAVE = tp.CLAVE_TARIFARIO
									and tp.ALOJAMIENTO = a.ID
									and tp.CARACTERISTICA_HABITACION = hc.CODIGO
									and a.CATEGORIA = cat.CODIGO
									and t.CLAVE = '".$clave."'
									and tp.FECHA between '".date("Y-m-d",strtotime($fecha_desde))."' and '".date("Y-m-d",strtotime($fecha_hasta))."' 
							GROUP BY cat.NOMBRE, a.NOMBRE, hc.NOMBRE, t.NOCHES, tp.REGIMEN
							order by cat.NOMBRE, a.NOMBRE, hc.NOMBRE, t.NOCHES, tp.REGIMEN ";
		//echo($query);					

		$resultado =$conexion->query($query);
		$precios = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($precios,$fila);
		}


		//Liberar Memoria usada por la consulta
		$resultado->close();


		return $precios;											
	}



	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsTarifarios($conexion, $filadesde, $usuario, $buscar_entidad, $buscar_anno, $buscar_destino, $buscar_noches, $buscar_nombre){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_entidad = $buscar_entidad;
		$this->Buscar_anno = $buscar_anno;
		$this->Buscar_destino = $buscar_destino;
		$this->Buscar_noches = $buscar_noches;
		$this->Buscar_nombre = $buscar_nombre;
	}
}

?>