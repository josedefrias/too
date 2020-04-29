<?php

class clsServicios_cupos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_alojamiento;
	var	$buscar_tipo;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_id = $this ->Buscar_id;
		$buscar_servicio = $this ->Buscar_servicio;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_release = $this ->Buscar_release;


		if($buscar_id != null and $buscar_servicio !=null){
			if($buscar_id != null){
				$servi = " and s.CODIGO = '".$buscar_servicio."'";
				$fech = " AND c.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND c.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " and s.ID_PROVEEDOR = '".$buscar_id."'";
				if($buscar_servicio != null){
					$CADENA_BUSCAR .= $servi;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}

		}else{
			$CADENA_BUSCAR = " and s.ID_PROVEEDOR = ''";     
  		}

		$resultado =$conexion->query("select c.CLAVE clave, p.ID id, p.NOMBRE proveedor, s.CODIGO codigo, 
							s.NOMBRE servicio, 
							DATE_FORMAT(c.FECHA, '%d-%m-%Y') AS fecha,
							c.DIA dia, 
							c.CUPO cupo, 
							c.OCUPADAS ocupadas, 
							c.EN_ESPERA en_espera, 
							DATE_FORMAT(c.RELEASE_CUPO, '%d-%m-%Y') AS release_cupo
				from
				hit_servicios s, hit_servicios_cupos c, hit_proveedores p
				where
				s.ID = c.ID
				and s.ID_PROVEEDOR = p.ID 
				".$CADENA_BUSCAR." 
				order by c.FECHA, p.NOMBRE, s.NOMBRE");


		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_CUPOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$servicios_cupos = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['clave'] == ''){
				break;
			}
			array_push($servicios_cupos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $servicios_cupos;											
	}

	/*function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_CUPOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_id = $this ->Buscar_id;
		$buscar_servicio = $this ->Buscar_servicio;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_release = $this ->Buscar_release;
	
		if($buscar_id != null){
			if($buscar_id != null){
				$servi = " and s.CODIGO = '".$buscar_servicio."'";
				$fech = " AND c.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND c.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " and s.ID_PROVEEDOR = '".$buscar_id."'";
				if($buscar_servicio != null){
					$CADENA_BUSCAR .= $servi;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}

		}else{
			$CADENA_BUSCAR = " and s.ID_PROVEEDOR = ''";     
  		}

		$resultadoc =$conexion->query("select *
				from
				hit_servicios s, hit_servicios_cupos c, hit_proveedores p
				where
				s.ID = c.ID
				and s.ID_PROVEEDOR = p.ID 
				".$CADENA_BUSCAR." 
				order by c.FECHA, p.NOMBRE, s.NOMBRE");											

												//ESPCIFICO: nombre tabla
		//$resultadoc =$conexion->query('SELECT * FROM hit_servicios_cupos ac'.$CADENA_BUSCAR);


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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_CUPOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_servicio = $this ->Buscar_servicio;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_release = $this ->Buscar_release;
	
		if($buscar_id != null){
			if($buscar_id != null){
				$servi = " and s.CODIGO = '".$buscar_servicio."'";
				$fech = " AND c.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND c.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " and s.ID_PROVEEDOR = '".$buscar_id."'";
				if($buscar_servicio != null){
					$CADENA_BUSCAR .= $servi;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}

		}else{
			$CADENA_BUSCAR = " and s.ID_PROVEEDOR = ''";     
  		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select *
				from
				hit_servicios s, hit_servicios_cupos c, hit_proveedores p
				where
				s.ID = c.ID
				and s.ID_PROVEEDOR = p.ID 
				".$CADENA_BUSCAR." 
				order by c.FECHA, p.NOMBRE, s.NOMBRE");


		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_CUPOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($clave,$fecha,$cupo,$release){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_servicios_cupos SET ";
		$query .= " CUPO = '".$cupo."'";
		$query .= ", RELEASE_CUPO = '".date("Y-m-d",strtotime($release))."'";
		$query .= " WHERE OCUPADAS <= ".$cupo." AND CLAVE = '".$clave."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){

			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}
		
		$query2 = "UPDATE hit_servicios_cupos SET ";
		$query2 .= " CUPO = OCUPADAS";
		$query2 .= ", RELEASE_CUPO = '".date("Y-m-d",strtotime($release))."'";
		$query2 .= " WHERE OCUPADAS > ".$cupo." AND CLAVE = '".$clave."'";

		$resultadom2 =$conexion->query($query2);

		if ($resultadom2 == FALSE){

			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}		

		
		return $respuesta;											
	}

	function Borrar($clave){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_servicios_cupos WHERE OCUPADAS = 0 AND CLAVE = '".$clave."'";


		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


	function Actualizar($id, $servicio, $fecha_desde, $fecha_hasta, $cupo, $dias_semana, $release){
		
		$conexion = $this ->Conexion;

		//echo($id."-".$servicio."-".$fecha_desde."-".$fecha_hasta."-".$cupo."-".$release);



		if($id != '' and $servicio != '' and $fecha_desde != '' and $fecha_hasta != ''){

			$datos_servicio =$conexion->query("select id id_servicio 
									from hit_servicios 
								     where ID_PROVEEDOR = '".$id."' and  CODIGO = '".$servicio."'");
				$odatos_servicio = $datos_servicio->fetch_assoc();
				$id_servicio = $odatos_servicio['id_servicio'];


			//actualizamos los cupos que quedan por encima de las plazas ocupadas
			if($cupo != '' and $release != ''){
				$conexion = $this ->Conexion;
				$query = "UPDATE hit_servicios_cupos SET ";
				$query .= " CUPO = '".$cupo."'";
				$query .= ", RELEASE_CUPO = DATE_SUB(FECHA,INTERVAL ".$release." DAY)";
				$query .= " WHERE OCUPADAS <= ".$cupo." AND ID = '".$id_servicio."'";
				$query .= " AND FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				$query .= " AND (DIA = substr('".$dias_semana."',1,1) or DIA = substr('".$dias_semana."',2,1) or DIA = substr('".$dias_semana."',3,1) or DIA = substr('".$dias_semana."',4,1) or DIA = substr('".$dias_semana."',5,1) or DIA = substr('".$dias_semana."',6,1) or DIA = substr('".$dias_semana."',7,1))";

			}elseif($cupo != '' and $release == ''){
				$conexion = $this ->Conexion;
				$query = "UPDATE hit_servicios_cupos SET ";
				$query .= " CUPO = '".$cupo."'";
				$query .= " WHERE OCUPADAS <= ".$cupo." AND ID = '".$id_servicio."'";
				$query .= " AND FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				$query .= " AND (DIA = substr('".$dias_semana."',1,1) or DIA = substr('".$dias_semana."',2,1) or DIA = substr('".$dias_semana."',3,1) or DIA = substr('".$dias_semana."',4,1) or DIA = substr('".$dias_semana."',5,1) or DIA = substr('".$dias_semana."',6,1) or DIA = substr('".$dias_semana."',7,1))";
			}elseif($cupo == '' and $release != ''){
				$conexion = $this ->Conexion;
				$query = "UPDATE hit_servicios_cupos SET ";
				$query .= " RELEASE_CUPO = DATE_SUB(FECHA,INTERVAL ".$release." DAY)";
				$query .= " WHERE ID = '".$id_servicio."'";
				$query .= " AND FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				$query .= " AND (DIA = substr('".$dias_semana."',1,1) or DIA = substr('".$dias_semana."',2,1) or DIA = substr('".$dias_semana."',3,1) or DIA = substr('".$dias_semana."',4,1) or DIA = substr('".$dias_semana."',5,1) or DIA = substr('".$dias_semana."',6,1) or DIA = substr('".$dias_semana."',7,1))";
			}
			//echo($query);
			$resultadom =$conexion->query($query);
			if ($resultadom == FALSE){

				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}
			
			//actualizamos los cupos que quedan por debajo de las plazas ocupadas
			if($cupo != '' and $release != ''){
				$conexion = $this ->Conexion;
				$query2 = "UPDATE hit_servicios_cupos SET ";
				$query2 .= " CUPO = OCUPADAS";
				$query2 .= ", RELEASE_CUPO = DATE_SUB(FECHA,INTERVAL ".$release." DAY)";
				$query2 .= " WHERE OCUPADAS > ".$cupo." AND ID = '".$id_servicio."'";
				$query2 .= " AND FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				$query2 .= " AND (DIA = substr('".$dias_semana."',1,1) or DIA = substr('".$dias_semana."',2,1) or DIA = substr('".$dias_semana."',3,1) or DIA = substr('".$dias_semana."',4,1) or DIA = substr('".$dias_semana."',5,1) or DIA = substr('".$dias_semana."',6,1) or DIA = substr('".$dias_semana."',7,1))";
			}elseif($cupo != '' and $release == ''){
				$conexion = $this ->Conexion;
				$query2 = "UPDATE hit_servicios_cupos SET ";
				$query2 .= " CUPO = OCUPADAS";
				$query2 .= " WHERE OCUPADAS > ".$cupo." AND ID = '".$id_servicio."'";
				$query2 .= " AND FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				$query2 .= " AND (DIA = substr('".$dias_semana."',1,1) or DIA = substr('".$dias_semana."',2,1) or DIA = substr('".$dias_semana."',3,1) or DIA = substr('".$dias_semana."',4,1) or DIA = substr('".$dias_semana."',5,1) or DIA = substr('".$dias_semana."',6,1) or DIA = substr('".$dias_semana."',7,1))";
			}

			$resultadom2 =$conexion->query($query2);
			if ($resultadom2 == FALSE){

				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}

		}else{
			$respuesta = 'Debe indicar proveedor, servicio y periodo de fechas';
		}

		return $respuesta;										
	}

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsServicios_cupos($conexion, $filadesde, $usuario, $buscar_id, $buscar_servicio, $buscar_fecha, $buscar_release){

		if($buscar_fecha == null){
			//echo(getdate('Y-m-d'));
			//echo(date('d-m-Y'));
			$buscar_fecha = date('d-m-Y');
		}

		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id = $buscar_id;
		$this->Buscar_servicio = $buscar_servicio;
		$this->Buscar_fecha = $buscar_fecha;
		$this->Buscar_release = $buscar_release;
	}
}

?>