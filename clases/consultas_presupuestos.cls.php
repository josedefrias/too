<?php

class clsConsulta_presupuestos{

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
		$buscar_reserva_desde = $this->Buscar_reserva_desde;
		$buscar_reserva_hasta = $this->Buscar_reserva_hasta;
		$buscar_folleto = $this->Buscar_folleto;




		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_reserva_desde != null and $buscar_reserva_hasta != null){
			$foll = " AND p.folleto = '".$buscar_folleto."'";
			$CADENA_BUSCAR = " and p.fecha_reserva between '".date("Y-m-d",strtotime($buscar_reserva_desde))."' and '".date("Y-m-d",strtotime($buscar_reserva_hasta))."'";
			if($buscar_folleto != null){
				$CADENA_BUSCAR .= $foll;	
			}

		}elseif($buscar_reserva_desde == null and $buscar_reserva_hasta != null){
			$foll = " AND p.folleto = '".$buscar_folleto."'";
			$CADENA_BUSCAR = " and p.fecha_reserva between curdate() and '".date("Y-m-d",strtotime($buscar_reserva_hasta))."'";
			if($buscar_folleto != null){
				$CADENA_BUSCAR .= $foll;	
			}
		}elseif($buscar_reserva_desde != null and $buscar_reserva_hasta == null){
			$foll = " AND p.folleto = '".$buscar_folleto."'";
			$CADENA_BUSCAR = " and p.fecha_reserva between '".date("Y-m-d",strtotime($buscar_reserva_desde))."' and curdate()";
			if($buscar_folleto != null){
				$CADENA_BUSCAR .= $foll;	
			}
		}else{
			$foll = " AND p.folleto = '".$buscar_folleto."'";
			$CADENA_BUSCAR = " and p.fecha_reserva between curdate() and curdate()";
			if($buscar_folleto != null){
				$CADENA_BUSCAR .= $foll;	
			}	
		}	
		
		/*$resultado =$conexion->query("select  DATE_FORMAT(p.FECHA_RESERVA, '%d-%m-%Y') AS fecha_reserva, pr.NOMBRE provincia, p.SITUACION situacion, f.NOMBRE folleto, c.NOMBRE viaje, ci.NOMBRE ciudad, a.NOMBRE hotel, p.REGIMEN regimen, pa.NOCHES noches, p.CIUDAD_SALIDA ciudad_salida, 
			DATE_FORMAT(p.FECHA_SALIDA, '%d-%m-%Y') AS salida, p.PAX pax,
				  gg.NOMBRE grupo, m.NOMBRE agencia, o.LOCALIDAD localidad, o.DIRECCION direccion, p.PVP_COMISIONABLE + p.PVP_NO_COMISIONABLE pvp
				from 
					hit_presupuestos p left join hit_presupuestos_alojamientos pa on pa.LOCALIZADOR = p.LOCALIZADOR left join hit_alojamientos a on pa.ALOJAMIENTO = a.ID,
					hit_producto_cuadros c,
					hit_minoristas m,
					hit_oficinas o,
					hit_producto_folletos f,
					hit_provincias pr,
					hit_grupos_gestion gg,
					hit_ciudades ci
				where
					p.FOLLETO = c.FOLLETO
					and p.cuadro = c.cuadro
					and p.MINORISTA = m.ID
					and p.MINORISTA = o.ID
					and p.OFICINA = o.OFICINA
					and c.FOLLETO = f.CODIGO
					and o.PROVINCIA = pr.CODIGO
					and m.GRUPO_GESTION = gg.ID
					and a.CIUDAD = ci.CODIGO
					".$CADENA_BUSCAR."
				order by p.FECHA_RESERVA, pr.NOMBRE, f.NOMBRE, c.NOMBRE, ci.NOMBRE, a.NOMBRE");*/





		$resultado =$conexion->query("select DATE_FORMAT(p.FECHA_RESERVA, '%d-%m-%Y') AS fecha_reserva, 
									pr.NOMBRE provincia, 
									p.SITUACION situacion, 
									f.NOMBRE folleto, 
									c.NOMBRE viaje, 
									ci.NOMBRE ciudad, 
									a.NOMBRE hotel, 
									p.REGIMEN regimen, 
									pa.NOCHES noches, 
									p.CIUDAD_SALIDA ciudad_salida, 
									DATE_FORMAT(p.FECHA_SALIDA, '%d-%m-%Y') AS salida, 
									p.PAX pax, 
									gg.NOMBRE grupo, 
									m.NOMBRE agencia, 
									o.LOCALIDAD localidad, 
									o.DIRECCION direccion, 
									p.PVP_COMISIONABLE + p.PVP_NO_COMISIONABLE pvp 
							from hit_presupuestos p left join hit_presupuestos_alojamientos pa on pa.LOCALIZADOR = p.LOCALIZADOR 
																		left join hit_alojamientos a on pa.ALOJAMIENTO = a.ID, 
									hit_producto_cuadros c, 
									hit_minoristas m, 
									hit_oficinas o, 
									hit_producto_folletos f, 
									hit_provincias pr, 
									hit_grupos_gestion gg, 
									hit_ciudades ci 
							where p.FOLLETO = c.FOLLETO 
									and p.cuadro = c.cuadro 
									and p.MINORISTA = m.ID 
									and p.MINORISTA = o.ID 
									and p.OFICINA = o.OFICINA 
									and c.FOLLETO = f.CODIGO 
									and o.PROVINCIA = pr.CODIGO 
									and m.GRUPO_GESTION = gg.ID 
									and a.CIUDAD = ci.CODIGO 
									".$CADENA_BUSCAR." 
									and p.FOLLETO <> 'TODOS'
									and c.PRODUCTO not in ('SSV')
									
							union
									
							select DATE_FORMAT(p.FECHA_RESERVA, '%d-%m-%Y') AS fecha_reserva, 
									pr.NOMBRE provincia, 
									p.SITUACION situacion, 
									f.NOMBRE folleto, 
									c.NOMBRE viaje, 
									bg.POBLACION ciudad, 
									pa.INTERFAZ_NOMBRE_ALOJAMIENTO hotel, 
									p.REGIMEN regimen, 
									pa.NOCHES noches, 
									p.CIUDAD_SALIDA ciudad_salida, 
									DATE_FORMAT(p.FECHA_SALIDA, '%d-%m-%Y') AS salida, 
									p.PAX pax, 
									gg.NOMBRE grupo, 
									m.NOMBRE agencia, 
									o.LOCALIDAD localidad, 
									o.DIRECCION direccion, 
									p.PVP_COMISIONABLE + p.PVP_NO_COMISIONABLE pvp 
							from hit_presupuestos p,
									hit_presupuestos_alojamientos pa left join hit_busqueda_general bg on pa.INTERFAZ_CODIGO_ALOJAMIENTO = bg.CODIGO_ALOJAMIENTO,
									hit_producto_cuadros c, 
									hit_minoristas m, 
									hit_oficinas o, 
									hit_producto_folletos f, 
									hit_provincias pr, 
									hit_grupos_gestion gg
							where 
									pa.LOCALIZADOR = p.LOCALIZADOR
									and p.FOLLETO = c.FOLLETO 
									and p.cuadro = c.cuadro 
									and p.MINORISTA = m.ID 
									and p.MINORISTA = o.ID 
									and p.OFICINA = o.OFICINA 
									and c.FOLLETO = f.CODIGO 
									and o.PROVINCIA = pr.CODIGO 
									and m.GRUPO_GESTION = gg.ID 
									".$CADENA_BUSCAR."
									and p.FOLLETO = 'TODOS'

							union

							select DATE_FORMAT(p.FECHA_RESERVA, '%d-%m-%Y') AS fecha_reserva, 
									pr.NOMBRE provincia, 
									p.SITUACION situacion, 
									f.NOMBRE folleto, 
									c.NOMBRE viaje, 
									d.NOMBRE ciudad, 
									'' hotel, 
									p.REGIMEN regimen, 
									'' noches, 
									p.CIUDAD_SALIDA ciudad_salida, 
									DATE_FORMAT(p.FECHA_SALIDA, '%d-%m-%Y') AS salida, 
									p.PAX pax, 
									gg.NOMBRE grupo, 
									m.NOMBRE agencia, 
									o.LOCALIDAD localidad, 
									o.DIRECCION direccion, 
									p.PVP_COMISIONABLE + p.PVP_NO_COMISIONABLE pvp 
							from hit_presupuestos p, 
									hit_producto_cuadros c, 
									hit_minoristas m, 
									hit_oficinas o, 
									hit_producto_folletos f, 
									hit_provincias pr, 
									hit_grupos_gestion gg,
									hit_destinos d
							where p.FOLLETO = c.FOLLETO 
									and p.cuadro = c.cuadro 
									and p.MINORISTA = m.ID 
									and p.MINORISTA = o.ID 
									and p.OFICINA = o.OFICINA 
									and c.FOLLETO = f.CODIGO 
									and o.PROVINCIA = pr.CODIGO 
									and m.GRUPO_GESTION = gg.ID 
									and c.DESTINO = d.CODIGO
									".$CADENA_BUSCAR."
									and p.FOLLETO <> 'TODOS'
									and c.PRODUCTO in ('SSV')		

							order by fecha_reserva, provincia, folleto, viaje, ciudad, hotel");



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
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONSULTAS_PRESUPUESTOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$consulta_presupuestos = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['provincia'] == ''){
				break;
			}
			array_push($consulta_presupuestos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $consulta_presupuestos;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_reserva_desde = $this->Buscar_reserva_desde;
		$buscar_reserva_hasta = $this->Buscar_reserva_hasta;
		$buscar_folleto = $this->Buscar_folleto;


		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_reserva_desde != null and $buscar_reserva_hasta != null){
			$foll = " AND p.folleto = '".$buscar_folleto."'";
			$CADENA_BUSCAR = " and p.fecha_reserva between '".date("Y-m-d",strtotime($buscar_reserva_desde))."' and '".date("Y-m-d",strtotime($buscar_reserva_hasta))."'";
			if($buscar_folleto != null){
				$CADENA_BUSCAR .= $foll;	
			}

		}elseif($buscar_reserva_desde == null and $buscar_reserva_hasta != null){
			$foll = " AND p.folleto = '".$buscar_folleto."'";
			$CADENA_BUSCAR = " and p.fecha_reserva between curdate() and '".date("Y-m-d",strtotime($buscar_reserva_hasta))."'";
			if($buscar_folleto != null){
				$CADENA_BUSCAR .= $foll;	
			}
		}elseif($buscar_reserva_desde != null and $buscar_reserva_hasta == null){
			$foll = " AND p.folleto = '".$buscar_folleto."'";
			$CADENA_BUSCAR = " and p.fecha_reserva between '".date("Y-m-d",strtotime($buscar_reserva_desde))."' and curdate()";
			if($buscar_folleto != null){
				$CADENA_BUSCAR .= $foll;	
			}
		}else{
			$foll = " AND p.folleto = '".$buscar_folleto."'";
			$CADENA_BUSCAR = " and p.fecha_reserva between curdate() and curdate()";
			if($buscar_folleto != null){
				$CADENA_BUSCAR .= $foll;	
			}	
		}	

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select p.LOCALIZADOR
							from hit_presupuestos p left join hit_presupuestos_alojamientos pa on pa.LOCALIZADOR = p.LOCALIZADOR 
																		left join hit_alojamientos a on pa.ALOJAMIENTO = a.ID, 
									hit_producto_cuadros c, 
									hit_minoristas m, 
									hit_oficinas o, 
									hit_producto_folletos f, 
									hit_provincias pr, 
									hit_grupos_gestion gg, 
									hit_ciudades ci 
							where p.FOLLETO = c.FOLLETO 
									and p.cuadro = c.cuadro 
									and p.MINORISTA = m.ID 
									and p.MINORISTA = o.ID 
									and p.OFICINA = o.OFICINA 
									and c.FOLLETO = f.CODIGO 
									and o.PROVINCIA = pr.CODIGO 
									and m.GRUPO_GESTION = gg.ID 
									and a.CIUDAD = ci.CODIGO 
									".$CADENA_BUSCAR." 
									and p.FOLLETO <> 'TODOS'
									and c.PRODUCTO not in ('SSV')
									
							union
									
							select p.LOCALIZADOR
							from hit_presupuestos p,
									hit_presupuestos_alojamientos pa left join hit_busqueda_general bg on pa.INTERFAZ_CODIGO_ALOJAMIENTO = bg.CODIGO_ALOJAMIENTO,
									hit_producto_cuadros c, 
									hit_minoristas m, 
									hit_oficinas o, 
									hit_producto_folletos f, 
									hit_provincias pr, 
									hit_grupos_gestion gg

							where 
									pa.LOCALIZADOR = p.LOCALIZADOR
									and p.FOLLETO = c.FOLLETO 
									and p.cuadro = c.cuadro 
									and p.MINORISTA = m.ID 
									and p.MINORISTA = o.ID 
									and p.OFICINA = o.OFICINA 
									and c.FOLLETO = f.CODIGO 
									and o.PROVINCIA = pr.CODIGO 
									and m.GRUPO_GESTION = gg.ID 
									".$CADENA_BUSCAR."
									and p.FOLLETO = 'TODOS'
									
							union

							select p.LOCALIZADOR
							from hit_presupuestos p, 
									hit_producto_cuadros c, 
									hit_minoristas m, 
									hit_oficinas o, 
									hit_producto_folletos f, 
									hit_provincias pr, 
									hit_grupos_gestion gg,
									hit_destinos d
							where p.FOLLETO = c.FOLLETO 
									and p.cuadro = c.cuadro 
									and p.MINORISTA = m.ID 
									and p.MINORISTA = o.ID 
									and p.OFICINA = o.OFICINA 
									and c.FOLLETO = f.CODIGO 
									and o.PROVINCIA = pr.CODIGO 
									and m.GRUPO_GESTION = gg.ID 
									and c.DESTINO = d.CODIGO
									".$CADENA_BUSCAR."
									and p.FOLLETO <> 'TODOS'
									and c.PRODUCTO in ('SSV')");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONSULTAS_PRESUPUESTOS' AND USUARIO = '".$Usuario."'");
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
		$resultadoc =$conexion->query('SELECT * FROM hit_presupuestos');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONSULTAS_PRESUPUESTOS' AND USUARIO = '".$Usuario."'");
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
	function clsConsulta_presupuestos($conexion, $filadesde, $usuario, $buscar_reserva_desde, $buscar_reserva_hasta, $buscar_folleto){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_reserva_desde = $buscar_reserva_desde;
		$this->Buscar_reserva_hasta = $buscar_reserva_hasta;
		$this->Buscar_folleto = $buscar_folleto;
	}
}

?>