<?php

class clsAcceso_usuarios{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_fecha_desde;
	var	$buscar_fecha_hasta;
	var	$buscar_desde;
	var	$buscar_usuario;

	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_fecha_desde = $this ->Buscar_fecha_desde;
		$buscar_fecha_hasta = $this ->Buscar_fecha_hasta;
		$buscar_desde = $this ->Buscar_desde;
		$buscar_usuario = $this ->Buscar_usuario;






		/*if($buscar_fecha_desde != null){
			if($buscar_fecha_hasta != null){
				$CADENA_BUSCAR = "and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_fecha_desde))."' and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
			}else{
				$CADENA_BUSCAR = "and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_fecha_desde))."' and '".date("Y-m-d",strtotime($buscar_fecha_desde))."'";
			}
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";

			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}
		}elseif($buscar_desde != null){
			$CADENA_BUSCAR = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}	
		}elseif($buscar_usuario != null){
			$CADENA_BUSCAR = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";;
  		}else{
			$CADENA_BUSCAR = " and uca.USUARIO = ''"; 
		}*/





		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_fecha_desde != null and $buscar_fecha_hasta != null){
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_fecha_desde))."' and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}

		}elseif($buscar_fecha_desde == null and $buscar_fecha_hasta != null){
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between curdate() and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}
		}elseif($buscar_fecha_desde != null and $buscar_fecha_hasta == null){
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_fecha_desde))."' and curdate()";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}
		}else{
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between curdate() and curdate()";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}	
		}	






		//echo($CADENA_BUSCAR);

		$resultado =$conexion->query("select DATE_FORMAT((DATE_ADD(FECHA_ACCESO, INTERVAL 2 HOUR)), '%d-%m-%Y  %H:%i:%s') AS fecha_acceso, 
											desde, 
														usuario, nombre, localidad, direccion, email, telefono
											from(
											select uca.FECHA_ACCESO, uca.DESDE, uca.USUARIO, u.NOMBRE, '' localidad, '' direccion, u.EMAIL, u.TELEFONO 
											from hit_usuarios_control_acceso uca, hit_usuarios u
											where 
											uca.USUARIO = u.USUARIO
											".$CADENA_BUSCAR."

											union

											select uca.FECHA_ACCESO, uca.DESDE, uca.USUARIO, m.NOMBRE, o.LOCALIDAD localidad, o.DIRECCION direccion, o.MAIL, o.TELEFONO
											from hit_usuarios_control_acceso uca, hit_oficinas o, hit_minoristas m
											where 
											uca.USUARIO = o.USUARIO_WEB
											and o.ID = m.ID
											".$CADENA_BUSCAR.") accesos
											order by accesos.FECHA_ACCESO");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ACCESO_USUARIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$acceso_usuarios = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['desde'] == ''){
				break;
			}
			array_push($acceso_usuarios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $acceso_usuarios;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		$buscar_fecha_desde = $this ->Buscar_fecha_desde;
		$buscar_fecha_hasta = $this ->Buscar_fecha_hasta;
		$buscar_desde = $this ->Buscar_desde;
		$buscar_usuario = $this ->Buscar_usuario;
	
		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_fecha_desde != null and $buscar_fecha_hasta != null){
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_fecha_desde))."' and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}

		}elseif($buscar_fecha_desde == null and $buscar_fecha_hasta != null){
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between curdate() and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}
		}elseif($buscar_fecha_desde != null and $buscar_fecha_hasta == null){
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_fecha_desde))."' and curdate()";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}
		}else{
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between curdate() and curdate()";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}	
		}										

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select *
											from(
											select uca.FECHA_ACCESO, uca.DESDE, uca.USUARIO, u.NOMBRE, u.EMAIL, u.TELEFONO 
											from hit_usuarios_control_acceso uca, hit_usuarios u
											where 
											uca.USUARIO = u.USUARIO
											".$CADENA_BUSCAR."

											union

											select uca.FECHA_ACCESO, uca.DESDE, uca.USUARIO, m.NOMBRE, o.MAIL, o.TELEFONO
											from hit_usuarios_control_acceso uca, hit_oficinas o, hit_minoristas m
											where 
											uca.USUARIO = o.USUARIO_WEB
											and o.ID = m.ID
											".$CADENA_BUSCAR.") accesos
											order by FECHA_ACCESO");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ACCESO_USUARIOS' AND USUARIO = '".$Usuario."'");
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
		
		$buscar_fecha_desde = $this ->Buscar_fecha_desde;
		$buscar_fecha_hasta = $this ->Buscar_fecha_hasta;
		$buscar_desde = $this ->Buscar_desde;
		$buscar_usuario = $this ->Buscar_usuario;
	
		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_fecha_desde != null and $buscar_fecha_hasta != null){
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_fecha_desde))."' and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}

		}elseif($buscar_fecha_desde == null and $buscar_fecha_hasta != null){
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between curdate() and '".date("Y-m-d",strtotime($buscar_fecha_hasta))."'";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}
		}elseif($buscar_fecha_desde != null and $buscar_fecha_hasta == null){
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_fecha_desde))."' and curdate()";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}
		}else{
			$desd = " and uca.desde = '".$buscar_desde."'";
			$usu = " and uca.USUARIO LIKE '%".$buscar_usuario."%'";
			$CADENA_BUSCAR = " and DATE_FORMAT(uca.fecha_acceso, '%Y-%m-%d') between curdate() and curdate()";
			if($buscar_desde != null){
				$CADENA_BUSCAR .= $desd;	
			}
			if($buscar_usuario != null){
				$CADENA_BUSCAR .= $usu;	
			}	
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select *
											from(
											select uca.FECHA_ACCESO, uca.DESDE, uca.USUARIO, u.NOMBRE, u.EMAIL, u.TELEFONO 
											from hit_usuarios_control_acceso uca, hit_usuarios u
											where 
											uca.USUARIO = u.USUARIO
											".$CADENA_BUSCAR."

											union

											select uca.FECHA_ACCESO, uca.DESDE, uca.USUARIO, m.NOMBRE, o.MAIL, o.TELEFONO
											from hit_usuarios_control_acceso uca, hit_oficinas o, hit_minoristas m
											where 
											uca.USUARIO = o.USUARIO_WEB
											and o.ID = m.ID
											".$CADENA_BUSCAR.") accesos
											order by FECHA_ACCESO");
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ACCESO_USUARIOS' AND USUARIO = '".$Usuario."'");
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



	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsAcceso_usuarios($conexion, $filadesde, $usuario, $buscar_fecha_desde, $buscar_fecha_hasta, $buscar_desde, $buscar_usuario){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_fecha_desde = $buscar_fecha_desde;
		$this->Buscar_fecha_hasta = $buscar_fecha_hasta;
		$this->Buscar_desde = $buscar_desde;
		$this->Buscar_usuario = $buscar_usuario;
	}
}

?>