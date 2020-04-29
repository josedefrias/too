<?php

require 'clases_web/Reservas_fin.cls.php';

class clsPrecobros{

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
		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_estado = $this->Buscar_estado;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_agencia = $this->Buscar_agencia;
		$buscar_grupo = $this->Buscar_grupo;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$estad = " AND r.precobro = '".$buscar_estado."'";
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $estad;	
			}
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	
		
		$resultado =$conexion->query("select DATE_FORMAT(r.FECHA_SALIDA, '%d-%m-%Y') AS fecha_salida,
												r.localizador localizador, 
												 m.NOMBRE agencia, o.OFICINA oficina, 
												 o.LOCALIDAD localidad,
												 concat(r.CUADRO,'/',r.REGIMEN,'/',r.CIUDAD_SALIDA) viaje,
												 r.pax pax, 
												 r.PVP_COMISIONABLE + r.PVP_NO_COMISIONABLE pvp_bruto,
												 r.PVP_COMISIONABLE comisionable,
												 r.PVP_NO_COMISIONABLE no_comisionable,
												 r.PVP_COMISION comision,
												 r.PVP_IMPORTE_COMISION importe_comision,
												 r.PVP_IMPUESTO impuesto,
												 r.PVP_IMPUESTO_COMISION importe_impuesto,

												 r.PVP_TOTAL importe_facturado, 
												 r.PVP_TOTAL - r.PVP_NO_COMISIONABLE importe_sintasas,
												 r.DIVISA_ACTUAL moneda, 
												 r.PRECOBRO estado
										from hit_reservas r,
											  hit_minoristas m,
											  hit_oficinas o
										where 
												r.MINORISTA = m.ID
												and r.MINORISTA = o.ID
												and r.OFICINA = o.OFICINA
												and r.pvp_total > 0
												and r.precobro <> 'C' ".$CADENA_BUSCAR." order by r.FECHA_SALIDA,m.NOMBRE,r.localizador");
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
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONSULTAS_INFORMES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$precobros = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['localizador'] == ''){
				break;
			}
			array_push($precobros,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $precobros;											
	}


	function Cargar_totales(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_estado = $this->Buscar_estado;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_agencia = $this->Buscar_agencia;
		$buscar_grupo = $this->Buscar_grupo;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$estad = " AND r.precobro = '".$buscar_estado."'";
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $estad;	
			}
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	
		
		$resultado =$conexion->query("select 
												 sum(r.pax) total_pax, 
												 sum(r.PVP_COMISIONABLE + r.PVP_NO_COMISIONABLE) total_pvp_bruto,
												 sum(r.PVP_COMISIONABLE) total_comisionable,
												 sum(r.PVP_NO_COMISIONABLE) total_no_comisionable,
												 round(avg(r.PVP_COMISION),2) total_comision,
												 sum(r.PVP_IMPORTE_COMISION) total_importe_comision,
												 round(avg(r.PVP_IMPUESTO),2) total_impuesto,
												 sum(r.PVP_IMPUESTO_COMISION) total_importe_impuesto,
												 sum(r.PVP_TOTAL) total_importe_facturado, 
												 sum(r.PVP_TOTAL) - r.PVP_NO_COMISIONABLE total_importe_sintasas
										from hit_reservas r,
											  hit_minoristas m,
											  hit_oficinas o
										where 
												r.MINORISTA = m.ID
												and r.MINORISTA = o.ID
												and r.OFICINA = o.OFICINA
												and r.pvp_total > 0
												and r.precobro <> 'C' ".$CADENA_BUSCAR." order by r.FECHA_SALIDA,m.NOMBRE,r.localizador");
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
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONSULTAS_INFORMES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$totales = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['total_pvp_bruto'] == ''){
				break;
			}
			array_push($totales,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $totales;											
	}

	
	function Cargar_Importe_Total(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_estado = $this->Buscar_estado;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_agencia = $this->Buscar_agencia;
		$buscar_grupo = $this->Buscar_grupo;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$estad = " AND r.precobro = '".$buscar_estado."'";
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $estad;	
			}
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	
		
		$resultado =$conexion->query("select ifnull(sum(r.PVP_TOTAL),0) IMPORTE_TOTAL
										from hit_reservas r,
											  hit_minoristas m,
											  hit_oficinas o
										where 
												r.MINORISTA = m.ID
												and r.MINORISTA = o.ID
												and r.OFICINA = o.OFICINA
												and r.pvp_total > 0
												and r.precobro <> 'C' ".$CADENA_BUSCAR." order by r.FECHA_SALIDA,m.NOMBRE,r.localizador");
		//echo($CADENA_BUSCAR);
		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		$fila = $resultado->fetch_assoc();
		$importe_total = $fila['IMPORTE_TOTAL'];
		
		//Liberar Memoria usada por la consulta
		$resultado->close();


		return $importe_total;											
	}

	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_estado = $this->Buscar_estado;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_agencia = $this->Buscar_agencia;
		$buscar_grupo = $this->Buscar_grupo;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$estad = " AND r.precobro = '".$buscar_estado."'";
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$grup = " AND m.grupo_gestion = '".$buscar_grupo."'";
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $estad;	
			}
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select *
										from hit_reservas r,
											  hit_minoristas m,
											  hit_oficinas o
										where 
												r.MINORISTA = m.ID
												and r.MINORISTA = o.ID
												and r.OFICINA = o.OFICINA
												and r.pvp_total > 0
												and r.precobro <> 'C' ".$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONSULTAS_INFORMES' AND USUARIO = '".$Usuario."'");
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
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_reservas');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONSULTAS_INFORMES' AND USUARIO = '".$Usuario."'");
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

	function Modificar($localizador, $estado){

		$usuario = $this->Usuario;
	
		$conexion = $this ->Conexion;
		$query = "UPDATE hit_reservas SET ";
		$query .= " PRECOBRO = '".$estado."'";
		$query .= ", FECHA_PAGO = CURRENT_TIMESTAMP()";
		$query .= ", OPERADOR_PREPAGO = '".$usuario."'";
		$query .= " WHERE LOCALIZADOR = '".$localizador."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsPrecobros($conexion, $filadesde, $usuario, $buscar_salida_desde, $buscar_salida_hasta, $buscar_estado, $buscar_localizador, $buscar_agencia, $buscar_grupo){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_salida_desde = $buscar_salida_desde;
		$this->Buscar_salida_hasta = $buscar_salida_hasta;
		$this->Buscar_estado = $buscar_estado;
		$this->Buscar_localizador = $buscar_localizador;
		$this->Buscar_agencia = $buscar_agencia;
		$this->Buscar_grupo = $buscar_grupo;
	}
}

?>