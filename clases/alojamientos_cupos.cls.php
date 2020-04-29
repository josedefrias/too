<?php

class clsAlojamientos_cupos{

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
		$buscar_acuerdo = $this ->Buscar_acuerdo;
		$buscar_habitacion = $this ->Buscar_habitacion;
		$buscar_caracteristica = $this ->Buscar_caracteristica;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_release = $this ->Buscar_release;
	
		if($buscar_id != null){
			if($buscar_id != null){
				$acuer = " AND ac.ACUERDO = '".$buscar_acuerdo."'";
				$habit = " AND ac.HABITACION = '".$buscar_habitacion."'";
				$carac = " AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
				$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.ID = '".$buscar_id."'";
				if($buscar_acuerdo != null){
					$CADENA_BUSCAR .= $acuer;	
				}
				if($buscar_habitacion != null){
					$CADENA_BUSCAR .= $habit;	
				}
				if($buscar_caracteristica != null){
					$CADENA_BUSCAR .= $carac;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}elseif($buscar_acuerdo != null){
				$habit = " AND ac.HABITACION = '".$buscar_habitacion."'";
				$carac = " AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
				$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.ACUERDO = '".$buscar_acuerdo."'";
				if($buscar_habitacion != null){
					$CADENA_BUSCAR .= $habit;	
				}
				if($buscar_caracteristica != null){
					$CADENA_BUSCAR .= $carac;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}elseif($buscar_habitacion != null){
				$carac = " AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
				$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.HABITACION = '".$buscar_habitacion."'";
				if($buscar_caracteristica != null){
					$CADENA_BUSCAR .= $carac;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}elseif($buscar_caracteristica != null){
				$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}elseif($buscar_fecha != null){
				$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}elseif($buscar_release != null){
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.RELEASE = '".date("Y-m-d",strtotime($buscar_release))."'";
			}else{
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.ID = '0' AND ac.ACUERDO = '0'";     
  			}

		}else{
			$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.ID = '0' AND ac.ACUERDO = '0'";     
  		}

		$resultado =$conexion->query("SELECT ac.id, a.nombre, ac.acuerdo, ac.habitacion, ac.caracteristica, DATE_FORMAT(ac.fecha, '%d-%m-%Y') AS fecha, ac.dia,									  ac.cupo, ac.ocupadas, ac.en_espera,  DATE_FORMAT(ac.release_cupo, '%d-%m-%Y') AS release_cupo
									  FROM hit_alojamientos_cupos ac, hit_alojamientos a ".$CADENA_BUSCAR." ORDER BY a.NOMBRE, ac.ACUERDO, ac.FECHA, ac.HABITACION, ac.CARACTERISTICA ");


		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CUPOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$alojamientos_cupos = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['id'] == ''){
				break;
			}
			array_push($alojamientos_cupos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $alojamientos_cupos;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CUPOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_habitacion = $this ->Buscar_habitacion;
		$buscar_caracteristica = $this ->Buscar_caracteristica;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_release = $this ->Buscar_release;
	
		if($buscar_id != null){
			if($buscar_id != null){
				$acuer = " AND ac.ACUERDO = '".$buscar_acuerdo."'";
				$habit = " AND ac.HABITACION = '".$buscar_habitacion."'";
				$carac = " AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
				$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.ID = '".$buscar_id."'";
				if($buscar_acuerdo != null){
					$CADENA_BUSCAR .= $acuer;	
				}
				if($buscar_habitacion != null){
					$CADENA_BUSCAR .= $habit;	
				}
				if($buscar_caracteristica != null){
					$CADENA_BUSCAR .= $carac;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}elseif($buscar_acuerdo != null){
				$habit = " AND ac.HABITACION = '".$buscar_habitacion."'";
				$carac = " AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
				$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.ACUERDO = '".$buscar_acuerdo."'";
				if($buscar_habitacion != null){
					$CADENA_BUSCAR .= $habit;	
				}
				if($buscar_caracteristica != null){
					$CADENA_BUSCAR .= $carac;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}elseif($buscar_habitacion != null){
				$carac = " AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
				$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.HABITACION = '".$buscar_habitacion."'";
				if($buscar_caracteristica != null){
					$CADENA_BUSCAR .= $carac;	
				}
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}elseif($buscar_caracteristica != null){
				$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
				if($buscar_fecha != null){
					$CADENA_BUSCAR .= $fech;	
				}
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}elseif($buscar_fecha != null){
				$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
				if($buscar_release != null){
					$CADENA_BUSCAR .= $releas;	
				}
			}elseif($buscar_release != null){
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.RELEASE = '".date("Y-m-d",strtotime($buscar_release))."'";
			}else{
				$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.ID = '0' AND ac.ACUERDO = '0'";     
  			}

		}else{
			$CADENA_BUSCAR = " WHERE ac.id = a.id  AND ac.ID = '0' AND ac.ACUERDO = '0'";     
  		}

		$resultadoc =$conexion->query("SELECT * FROM hit_alojamientos_cupos ac, hit_alojamientos a ".$CADENA_BUSCAR." ORDER BY a.NOMBRE, ac.ACUERDO, ac.FECHA, ac.HABITACION, ac.CARACTERISTICA ");											

												//ESPCIFICO: nombre tabla
		//$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_cupos ac'.$CADENA_BUSCAR);


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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CUPOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_habitacion = $this ->Buscar_habitacion;
		$buscar_caracteristica = $this ->Buscar_caracteristica;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_release = $this ->Buscar_release;
	
		if($buscar_id != null){
			$acuer = " AND ac.ACUERDO = '".$buscar_acuerdo."'";
			$habit = " AND ac.HABITACION = '".$buscar_habitacion."'";
			$carac = " AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
			$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
			$CADENA_BUSCAR = " WHERE ac.ID = '".$buscar_id."'";
			if($buscar_acuerdo != null){
				$CADENA_BUSCAR .= $acuer;	
			}
			if($buscar_habitacion != null){
				$CADENA_BUSCAR .= $habit;	
			}
			if($buscar_caracteristica != null){
				$CADENA_BUSCAR .= $carac;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_release != null){
				$CADENA_BUSCAR .= $releas;	
			}
		}elseif($buscar_acuerdo){
			$habit = " AND ac.HABITACION = '".$buscar_habitacion."'";
			$carac = " AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
			$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
			$CADENA_BUSCAR = " WHERE AND ac.ACUERDO = '".$buscar_acuerdo."'";
			if($buscar_habitacion != null){
				$CADENA_BUSCAR .= $habit;	
			}
			if($buscar_caracteristica != null){
				$CADENA_BUSCAR .= $carac;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_release != null){
				$CADENA_BUSCAR .= $releas;	
			}
		}elseif($buscar_habitacion){
			$carac = " AND ac.CARACTERISTICA = '".$buscar_caracteristica."'";
			$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
			$CADENA_BUSCAR = " WHERE ac.HABITACION = '".$buscar_habitacion."'";
			if($buscar_caracteristica != null){
				$CADENA_BUSCAR .= $carac;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_release != null){
				$CADENA_BUSCAR .= $releas;	
			}
		}elseif($buscar_caracteristica){
			$fech = " AND ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
			$CADENA_BUSCAR = " WHERE ac.CARACTERISTICA = '".$buscar_caracteristica."'";
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_release != null){
				$CADENA_BUSCAR .= $releas;	
			}
		}elseif($buscar_fecha){
			$releas = " AND ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";
			$CADENA_BUSCAR = " WHERE ac.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			if($buscar_release != null){
				$CADENA_BUSCAR .= $releas;	
			}
		}elseif($buscar_release){
			$CADENA_BUSCAR = " WHERE ac.RELEASE_CUPO = '".date("Y-m-d",strtotime($buscar_release))."'";

		}else{
			$CADENA_BUSCAR = " WHERE ac.ID = '".$buscar_id."' AND ac.ACUERDO = '".$buscar_acuerdo."'";     
  		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos_cupos ac'.$CADENA_BUSCAR);


		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CUPOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id,$acuerdo,$habitacion,$caracteristica,$fecha,$cupo,$release){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_alojamientos_cupos SET ";
		$query .= " CUPO = '".$cupo."'";
		$query .= ", RELEASE_CUPO = '".date("Y-m-d",strtotime($release))."'";
		$query .= " WHERE OCUPADAS <= ".$cupo." AND ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND HABITACION = '".$habitacion."'";
		$query .= " AND CARACTERISTICA = '".$caracteristica."'";
		$query .= " AND FECHA = '".date("Y-m-d",strtotime($fecha))."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){

			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}
		
		$query2 = "UPDATE hit_alojamientos_cupos SET ";
		$query2 .= " CUPO = OCUPADAS";
		$query2 .= ", RELEASE_CUPO = '".date("Y-m-d",strtotime($release))."'";
		$query2 .= " WHERE OCUPADAS > ".$cupo." AND ID = '".$id."'";
		$query2 .= " AND ACUERDO = '".$acuerdo."'";
		$query2 .= " AND HABITACION = '".$habitacion."'";
		$query2 .= " AND CARACTERISTICA = '".$caracteristica."'";
		$query2 .= " AND FECHA = '".date("Y-m-d",strtotime($fecha))."'";

		$resultadom2 =$conexion->query($query2);

		if ($resultadom2 == FALSE){

			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}		

		
		return $respuesta;											
	}

	function Borrar($id,$acuerdo,$habitacion,$caracteristica,$fecha){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_alojamientos_cupos WHERE OCUPADAS = 0 AND ID = '".$id."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND HABITACION = '".$habitacion."'";
		$query .= " AND CARACTERISTICA = '".$caracteristica."'";
		$query .= " AND FECHA = '".date("Y-m-d",strtotime($fecha))."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($id,$acuerdo,$habitacion,$caracteristica,$fecha,$cupo,$release){

		$conexion = $this ->Conexion;

		$ResultadoNombreDia =$conexion->query("SELECT DAYNAME('".date("Y-m-d",strtotime($fecha))."')");
		$Nombre_dia_semana = mysqli_fetch_row($ResultadoNombreDia);	

		$resultadodia =$conexion->query("SELECT `GENERAL_TRADUCE_DIA`('".$Nombre_dia_semana[0]."')");
		$dia_semana = mysqli_fetch_row($resultadodia);	

		
		//------

		$query = "INSERT INTO hit_alojamientos_cupos (ID,ACUERDO,HABITACION,CARACTERISTICA,FECHA,DIA,CUPO,OCUPADAS,EN_ESPERA,RELEASE_CUPO) VALUES (";
		$query .= $id.",";
		$query .= "'".$acuerdo."',";
		$query .= "'".$habitacion."',";
		$query .= "'".$caracteristica."',";
		$query .= "'".date("Y-m-d",strtotime($fecha))."',";
		$query .= "'".$dia_semana[0]."',";
		//$query .= "'".$resultadodia."',";
		$query .= "'".$cupo."',";
		$query .= "0,";
		$query .= "0,";
		$query .= "'".date("Y-m-d",strtotime($release))."')";

		//ECHO($query);

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Actualizar($id,$acuerdo,$habitacion,$caracteristica,$fecha_desde, $fecha_hasta,$cupo,$release){
		
		/*for ($i=0;$i<count($caracteristica);$i++)    
		{     
		echo "Caracteristica " . $i . ": " . $caracteristica[$i]."<br> ";    
		} */


		//echo($id."-".$acuerdo."-".$habitacion."-".$caracteristica."-".$fecha_desde."-".$fecha_hasta."-".$cupo."-".$release);
		if($id != '' and $acuerdo != '' and $habitacion != '' and $caracteristica != '' and $fecha_desde != '' and $fecha_hasta){

			for ($i=0;$i<count($caracteristica);$i++)    
			{
				//actualizamos los cupos que quedan por encima de las plazas ocupadas
				if($cupo != '' and $release != ''){
					$conexion = $this ->Conexion;
					$query = "UPDATE hit_alojamientos_cupos SET ";
					$query .= " CUPO = '".$cupo."'";
					$query .= ", RELEASE_CUPO = DATE_SUB(FECHA,INTERVAL ".$release." DAY)";
					$query .= " WHERE OCUPADAS <= ".$cupo." AND ID = '".$id."'";
					$query .= " AND ACUERDO = '".$acuerdo."'";
					$query .= " AND HABITACION = '".$habitacion."'";
					$query .= " AND CARACTERISTICA = '".$caracteristica[$i]."'";
					$query .= " AND FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				}elseif($cupo != '' and $release == ''){
					$conexion = $this ->Conexion;
					$query = "UPDATE hit_alojamientos_cupos SET ";
					$query .= " CUPO = '".$cupo."'";
					$query .= " WHERE OCUPADAS <= ".$cupo." AND ID = '".$id."'";
					$query .= " AND ACUERDO = '".$acuerdo."'";
					$query .= " AND HABITACION = '".$habitacion."'";
					$query .= " AND CARACTERISTICA = '".$caracteristica[$i]."'";
					$query .= " AND FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				}elseif($cupo == '' and $release != ''){
					$conexion = $this ->Conexion;
					$query = "UPDATE hit_alojamientos_cupos SET ";
					$query .= " RELEASE_CUPO = DATE_SUB(FECHA,INTERVAL ".$release." DAY)";
					$query .= " WHERE ID = '".$id."'";
					$query .= " AND ACUERDO = '".$acuerdo."'";
					$query .= " AND HABITACION = '".$habitacion."'";
					$query .= " AND CARACTERISTICA = '".$caracteristica[$i]."'";
					$query .= " AND FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
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
					$query2 = "UPDATE hit_alojamientos_cupos SET ";
					$query2 .= " CUPO = OCUPADAS";
					$query2 .= ", RELEASE_CUPO = DATE_SUB(FECHA,INTERVAL ".$release." DAY)";
					$query2 .= " WHERE OCUPADAS > ".$cupo." AND ID = '".$id."'";
					$query2 .= " AND ACUERDO = '".$acuerdo."'";
					$query2 .= " AND HABITACION = '".$habitacion."'";
					$query2 .= " AND CARACTERISTICA = '".$caracteristica[$i]."'";
					$query2 .= " AND FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				}elseif($cupo != '' and $release == ''){
					$conexion = $this ->Conexion;
					$query2 = "UPDATE hit_alojamientos_cupos SET ";
					$query2 .= " CUPO = OCUPADAS";
					$query2 .= " WHERE OCUPADAS > ".$cupo." AND ID = '".$id."'";
					$query2 .= " AND ACUERDO = '".$acuerdo."'";
					$query2 .= " AND HABITACION = '".$habitacion."'";
					$query2 .= " AND CARACTERISTICA = '".$caracteristica[$i]."'";
					$query2 .= " AND FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				}

				$resultadom2 =$conexion->query($query2);
				if ($resultadom2 == FALSE){

					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
				}
			}

		}else{
			$respuesta = 'Debe indicar alojamiento, acuerdo, periodo de fechas, tipo y caracteristica';
		}

		return $respuesta;										
	}

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsAlojamientos_cupos($conexion, $filadesde, $usuario, $buscar_id, $buscar_nombre, $buscar_acuerdo, $buscar_habitacion, $buscar_caracteristica, $buscar_fecha, $buscar_release){

		if($buscar_fecha == null){
			//echo(getdate('Y-m-d'));
			//echo(date('d-m-Y'));
			$buscar_fecha = date('d-m-Y');
		}

		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id = $buscar_id;
		$this->Buscar_nombre = $buscar_nombre;
		$this->Buscar_acuerdo = $buscar_acuerdo;
		$this->Buscar_habitacion = $buscar_habitacion;
		$this->Buscar_caracteristica = $buscar_caracteristica;
		$this->Buscar_fecha = $buscar_fecha;
		$this->Buscar_release = $buscar_release;
	}
}

?>