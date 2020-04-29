<?php

class clsCuadros_alojamientos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var $buscar_cuadro;

//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LAS ALOJAMIENTOS---
//--------------------------------------------------------------------

	function Cargar_alojamientos($clave, $filadesde, $buscar_alojamiento){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

	
		if($buscar_alojamiento != null){
			$CADENA_BUSCAR = "CLAVE_CUADRO = '".$clave."' AND ALOJAMIENTO = '".$buscar_alojamiento."'";
		}else{
			$CADENA_BUSCAR = "CLAVE_CUADRO = '".$clave."'"; 
  		}
		

		$resultado =$conexion->query("SELECT pa.CLAVE_CUADRO, pa.paquete, pa.numero, pa.alojamiento, a.NOMBRE nombre_alojamiento, pa.regimen, pa.acuerdo, pa.habitacion, pa.caracteristica, ac.NOMBRE caracteristica_nombre, pa.uso, pa.dia, pa.noches, pa.situacion
								FROM hit_producto_cuadros_alojamientos pa, hit_alojamientos a, hit_habitaciones_car ac
								where 
								pa.ALOJAMIENTO = a.ID
								and pa.CARACTERISTICA = ac.CODIGO
								and ".$CADENA_BUSCAR." ORDER BY paquete, numero");

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$folletos_alojamientos = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['paquete'] == ''){
				break;
			}
			array_push($folletos_alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $folletos_alojamientos;											
	}


	function Cargar_lineas_nuevas_alojamientos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_alojamientos($clave, $filadesde, $buscar_alojamiento){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_alojamiento != null){
			$CADENA_BUSCAR = "CLAVE_CUADRO = '".$clave."' AND ALOJAMIENTO = '".$buscar_alojamiento."'";
		}else{
			$CADENA_BUSCAR = "CLAVE_CUADRO = '".$clave."'"; 
  		}
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_alojamientos
										WHERE ".$CADENA_BUSCAR." ORDER BY paquete, numero");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_alojamientos($filadesde, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_producto_cuadros_alojamientos');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_alojamientos($clave_cuadro, $paquete, $numero, $alojamiento, $regimen, $acuerdo, $habitacion, $caracteristica, $uso, $dia, $noches, $situacion,
		$paquete_old, $numero_old, $alojamiento_old, $regimen_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_producto_cuadros_alojamientos SET ";
		$query .= " PAQUETE = '".$paquete."'";
		$query .= ", NUMERO = '".$numero."'";
		$query .= ", ALOJAMIENTO = '".$alojamiento."'";
		$query .= ", REGIMEN = '".$regimen."'";
		$query .= ", ACUERDO = '".$acuerdo."'";
		$query .= ", HABITACION = '".$habitacion."'";
		$query .= ", CARACTERISTICA = '".$caracteristica."'";
		$query .= ", USO = '".$uso."'";
		$query .= ", DIA = '".$dia."'";
		$query .= ", NOCHES = '".$noches."'";
		$query .= ", SITUACION = '".$situacion."'";
		$query .= " WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND PAQUETE = '".$paquete_old."'";
		$query .= " AND NUMERO = '".$numero_old."'";
		$query .= " AND ALOJAMIENTO = '".$alojamiento_old."'";
		$query .= " AND REGIMEN = '".$regimen_old."'";



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

	function Borrar_alojamientos($clave_cuadro, $paquete, $numero, $alojamiento, $regimen){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_cuadros_alojamientos WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND PAQUETE = '".$paquete."'";
		$query .= " AND NUMERO = '".$numero."'";
		$query .= " AND ALOJAMIENTO = '".$alojamiento."'";
		$query .= " AND REGIMEN = '".$regimen."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_alojamientos($clave_cuadro, $folleto, $cuadro, $paquete, $numero, $alojamiento, $regimen, $acuerdo, $habitacion, $caracteristica, $uso, $dia, $noches, $situacion){

		$conexion = $this ->Conexion;

		/*$datos_numero =$conexion->query("select max(numero) numero from hit_producto_cuadros_alojamientos where CLAVE_CUADRO = '".$clave_cuadro."'");
		$numero_siguiente = $datos_numero->fetch_assoc();
		$numero_nuevo = $numero_siguiente['numero'] + 1;*/

		//echo($numero_nuevo);

		$query = "INSERT INTO hit_producto_cuadros_alojamientos (CLAVE_CUADRO, FOLLETO, CUADRO, PAQUETE, NUMERO, ALOJAMIENTO, REGIMEN, ACUERDO, HABITACION, CARACTERISTICA, USO, DIA, NOCHES, SITUACION) VALUES (";
		$query .= "'".$clave_cuadro."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$paquete."',";
		$query .= "'".$numero."',";
		$query .= "'".$alojamiento."',";
		$query .= "'".$regimen."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$habitacion."',";
		$query .= "'".$caracteristica."',";
		$query .= "'".$uso."',";
		$query .= "'".$dia."',";
		$query .= "'".$noches."',";
		$query .= "'".$situacion."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}

//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INFERIOR CON LAS PRECIOS DE ALOJAMIENTOS------
//--------------------------------------------------------------------

	function Cargar_alojamientos_precios($clave, $filadesde, $buscar_paquete, $buscar_fecha, $buscar_paquete_detalle){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;



		if($buscar_paquete != null){
			$fech = " AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN ap.FECHA_DESDE AND ap.FECHA_HASTA";
			$paq = " AND ap.CLAVE_PAQUETE = '".$buscar_paquete_detalle."'";
			$CADENA_BUSCAR = " ap.CLAVE_CUADRO = '".$clave."' AND ap.PAQUETE = '".$buscar_paquete."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_paquete_detalle != null){
				$CADENA_BUSCAR .= $paq;	
			}
		}elseif($buscar_paquete_detalle != null){
			$fech = " AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN ap.FECHA_DESDE AND ap.FECHA_HASTA";
			$CADENA_BUSCAR = " ap.CLAVE_PAQUETE = '".$buscar_paquete_detalle."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
		}elseif($buscar_fecha != null){
			$CADENA_BUSCAR = " ap.CLAVE_CUADRO = '".$clave."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN ap.FECHA_DESDE AND ap.FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " ap.CLAVE_CUADRO = '".$clave."'";    
  		}


		$resultado =$conexion->query("SELECT ap.clave_paquete,
							concat(c.paquete, ' - ', c.numero, ' - ', a.nombre, ' - ', c.caracteristica, ' - ', c.regimen) nombre_paquete,
							ap.clave_calendario, 
							concat(DATE_FORMAT(cc.fecha_desde, '%d-%m-%Y'),'  /  ',DATE_FORMAT(cc.fecha_hasta, '%d-%m-%Y')) nombre_calendario,
							ap.precio, ap.spto_individual, ap.spto_noche_extra
														FROM hit_producto_cuadros_alojamientos_precios  ap, hit_producto_cuadros_alojamientos c , hit_alojamientos a,
														hit_producto_cuadros_calendarios cc
														WHERE 
														ap.CLAVE_CUADRO = c.CLAVE_CUADRO 
														and ap.CLAVE_PAQUETE = c.CLAVE
														and ap.CLAVE_CALENDARIO = cc.CLAVE
														and c.ALOJAMIENTO = a.ID
														and ".$CADENA_BUSCAR." ORDER BY ap.paquete, ap.numero, ap.fecha_desde");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$folletos_alojamientos_precios = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['clave_paquete'] == ''){
				break;
			}
			array_push($folletos_alojamientos_precios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $folletos_alojamientos_precios;
	}


	function Cargar_lineas_nuevas_alojamientos_precios(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_alojamientos_precios($clave, $filadesde, $buscar_paquete, $buscar_fecha, $buscar_paquete_detalle){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		if($buscar_paquete != null){
			$fech = " AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$paq = " AND CLAVE_PAQUETE = '".$buscar_paquete_detalle."'";
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."' AND PAQUETE = '".$buscar_paquete."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_paquete_detalle != null){
				$CADENA_BUSCAR .= $paq;	
			}
		}elseif($buscar_paquete_detalle != null){
			$fech = " AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
			$CADENA_BUSCAR = " CLAVE_PAQUETE = '".$buscar_paquete_detalle."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
		}elseif($buscar_fecha != null){
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " CLAVE_CUADRO = '".$clave."'";    
  		}

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_producto_cuadros_alojamientos_precios 
										WHERE ".$CADENA_BUSCAR." ORDER BY paquete, numero, fecha_desde");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_alojamientos_precios($filadesde, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_producto_cuadros_alojamientos_precios');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_alojamientos_precios($clave_cuadro, $clave_calendario, $clave_paquete, $precio, $spto_individual, $spto_noche_extra, $clave_calendario_old, $clave_paquete_old){

		$conexion = $this ->Conexion;

		$datos_alojamientos =$conexion->query("select folleto, cuadro, paquete, numero from hit_producto_cuadros_alojamientos where clave = '".$clave_paquete."'");
		$alojamientos = $datos_alojamientos->fetch_assoc();
		$folleto = $alojamientos['folleto'];
		$cuadro = $alojamientos['cuadro'];
		$paquete = $alojamientos['paquete'];
		$numero = $alojamientos['numero'];

		$datos_fechas =$conexion->query("select fecha_desde, fecha_hasta from hit_producto_cuadros_calendarios where clave = '".$clave_calendario."'");
		$fechas = $datos_fechas->fetch_assoc();
		$fecha_desde = $fechas['fecha_desde'];
		$fecha_hasta = $fechas['fecha_hasta'];

		$query = "UPDATE hit_producto_cuadros_alojamientos_precios SET ";
		$query .= " CLAVE_CALENDARIO = '".$clave_calendario."'";
		$query .= ", CLAVE_PAQUETE = '".$clave_paquete."'";
		$query .= ", FOLLETO = '".$folleto."'";
		$query .= ", CUADRO = '".$cuadro."'";
		$query .= ", PAQUETE = '".$paquete."'";
		$query .= ", NUMERO = '".$numero."'";
		$query .= ", FECHA_DESDE = '".$fecha_desde."'";
		$query .= ", FECHA_HASTA = '".$fecha_hasta."'";
		$query .= ", PRECIO = '".$precio."'";
		$query .= ", SPTO_INDIVIDUAL = '".$spto_individual."'";
		$query .= ", SPTO_NOCHE_EXTRA = '".$spto_noche_extra."'";
		$query .= " WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND CLAVE_CALENDARIO = '".$clave_calendario_old."'";
		$query .= " AND CLAVE_PAQUETE = '".$clave_paquete_old."'";

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

	function Borrar_alojamientos_precios($clave_cuadro, $clave_calendario, $clave_paquete){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_producto_cuadros_alojamientos_precios WHERE CLAVE_CUADRO = '".$clave_cuadro."'";
		$query .= " AND CLAVE_CALENDARIO = '".$clave_calendario."'";
		$query .= " AND CLAVE_PAQUETE = '".$clave_paquete."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_alojamientos_precios($clave_cuadro, $clave_calendario, $clave_paquete, $precio, $spto_individual, $spto_noche_extra){

		$conexion = $this ->Conexion;

		//ECHO($clave_cuadro.'-'.$clave_calendario.'-'.$clave_servicio.'-'.$precio_1.'-'.$clase_1.'-'.$precio_2.'-'.$clase_2);

		$datos_alojamientos =$conexion->query("select folleto, cuadro, paquete, numero from hit_producto_cuadros_alojamientos where clave = '".$clave_paquete."'");
		$alojamientos = $datos_alojamientos->fetch_assoc();
		$folleto = $alojamientos['folleto'];
		$cuadro = $alojamientos['cuadro'];
		$paquete = $alojamientos['paquete'];
		$numero = $alojamientos['numero'];

		$datos_fechas =$conexion->query("select fecha_desde, fecha_hasta from hit_producto_cuadros_calendarios where clave = '".$clave_calendario."'");
		$fechas = $datos_fechas->fetch_assoc();
		$fecha_desde = $fechas['fecha_desde'];
		$fecha_hasta = $fechas['fecha_hasta'];

		$query = "INSERT INTO hit_producto_cuadros_alojamientos_precios (CLAVE_CUADRO, CLAVE_CALENDARIO, CLAVE_PAQUETE, FOLLETO,CUADRO,PAQUETE,NUMERO,FECHA_DESDE, FECHA_HASTA, PRECIO, SPTO_INDIVIDUAL, SPTO_NOCHE_EXTRA) VALUES (";
		$query .= "'".$clave_cuadro."',";
		$query .= "'".$clave_calendario."',";
		$query .= "'".$clave_paquete."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$paquete."',";
		$query .= "'".$numero."',";
		$query .= "'".$fecha_desde."',";
		$query .= "'".$fecha_hasta."',";
		$query .= "'".$precio."',";
		$query .= "'".$spto_individual."',";
		$query .= "'".$spto_noche_extra."')";

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
	function clsCuadros_alojamientos($conexion, $filadesde, $usuario, $buscar_codigo, $buscar_cuadro){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_codigo= $buscar_codigo;
		$this->Buscar_cuadro= $buscar_cuadro;
	}
}

?>