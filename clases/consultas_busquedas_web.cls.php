<?php

class clsConsulta_busquedas_web{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var $buscar_nombre;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_desde = $this->Buscar_desde;
		$buscar_hasta = $this->Buscar_hasta;
		$buscar_pais = $this->Buscar_pais;
		$buscar_producto = $this->Buscar_producto;



		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_desde != null and $buscar_hasta != null){
			$pai = " AND bw.pais = '".$buscar_pais."'";
			$pro = " AND bw.producto = '".$buscar_producto."'";
			$CADENA_BUSCAR = " and DATE_FORMAT(bw.BUSQUEDA_INICIO, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_desde))."' and '".date("Y-m-d",strtotime($buscar_hasta))."'";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}

		}elseif($buscar_desde == null and $buscar_hasta != null){
			$pai = " AND bw.pais = '".$buscar_pais."'";
			$pro = " AND bw.producto = '".$buscar_producto."'";
			$CADENA_BUSCAR = " and DATE_FORMAT(bw.BUSQUEDA_INICIO, '%Y-%m-%d') between curdate() and '".date("Y-m-d",strtotime($buscar_hasta))."'";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
		}elseif($buscar_desde != null and $buscar_hasta == null){
			$pai = " AND bw.pais = '".$buscar_pais."'";
			$pro = " AND bw.producto = '".$buscar_producto."'";
			$CADENA_BUSCAR = " and DATE_FORMAT(bw.BUSQUEDA_INICIO, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_desde))."' and curdate()";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
		}else{
			$pai = " AND bw.pais = '".$buscar_pais."'";
			$pro = " AND bw.producto = '".$buscar_producto."'";
			$CADENA_BUSCAR = " and DATE_FORMAT(bw.BUSQUEDA_INICIO, '%Y-%m-%d') between curdate() and curdate()";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}	
		}	
		
		/*$resultado =$conexion->query("select p.NOMBRE pais, pr.NOMBRE producto, o.NOMBRE origen, d.NOMBRE destino, 
			DATE_FORMAT(bw.FECHA, '%d-%m-%Y') salida, 
			bw.NOCHES noches, bw.CATEGORIA categoria, 
			z.NOMBRE zona, a.NOMBRE hotel,
			DATE_FORMAT(bw.BUSQUEDA_INICIO, '%d-%m-%Y') fecha_busqueda, 
			DATE_FORMAT(bw.BUSQUEDA_INICIO, '%H:%i:%s') busqueda_inicio, 
			DATE_FORMAT(bw.BUSQUEDA_FIN, '%H:%i:%s') busqueda_fin,

			TIME_TO_SEC(TIMEDIFF(bw.busqueda_fin,bw.busqueda_inicio)) segundos, 
			bw.USUARIO usuario,
			bw.WEB web
			from hit_busquedas_web bw left join hit_zonas z on bw.ZONA = z.CODIGO
				  							   left join hit_alojamientos a on bw.ALOJAMIENTO = a.ID, 
				hit_paises p, hit_productos pr, hit_origenes o, hit_destinos d
			where
			bw.PAIS = p.CODIGO
			and bw.PRODUCTO = pr.CODIGO
			and bw.ORIGEN = o.CODIGO
			and bw.DESTINO = d.CODIGO 
			".$CADENA_BUSCAR."
			 order by bw.busqueda_inicio");*/



		$resultado =$conexion->query("select p.NOMBRE pais, pr.NOMBRE producto, 
							o.NOMBRE origen, 
							d.NOMBRE destino,
							DATE_FORMAT(bw.FECHA, '%d-%m-%Y') salida, bw.NOCHES noches, 
							bw.CATEGORIA categoria, z.NOMBRE zona, a.NOMBRE hotel, 
							DATE_FORMAT(bw.BUSQUEDA_INICIO, '%d-%m-%Y') fecha_busqueda, 
							DATE_FORMAT(bw.BUSQUEDA_INICIO, '%H:%i:%s') busqueda_inicio, 
							DATE_FORMAT(bw.BUSQUEDA_FIN, '%H:%i:%s') busqueda_fin, 
							TIME_TO_SEC(TIMEDIFF(bw.busqueda_fin,bw.busqueda_inicio)) segundos, 
							bw.USUARIO usuario, 
							bw.WEB web 
							from hit_busquedas_web bw left join hit_zonas z on bw.ZONA = z.CODIGO 
													left join hit_alojamientos a on bw.ALOJAMIENTO = a.ID, 
								  hit_paises p, 
								  hit_productos pr, 
								  hit_origenes o, 
								  hit_destinos d
							where (bw.PAIS = p.CODIGO or bw.PAIS = p.CODIGO_CORTO)
									and bw.PRODUCTO = pr.CODIGO 
									and bw.ORIGEN = o.CODIGO 
									and bw.DESTINO = d.CODIGO
									".$CADENA_BUSCAR."
									and web = 'IPSOTRAVEL'

							union

							select p.NOMBRE pais, pr.NOMBRE producto, 
							o.NOMBRE origen, 
							bw.DESTINO,
							DATE_FORMAT(bw.FECHA, '%d-%m-%Y') salida, bw.NOCHES noches, 
							bw.CATEGORIA categoria, z.NOMBRE zona, a.NOMBRE hotel, 
							DATE_FORMAT(bw.BUSQUEDA_INICIO, '%d-%m-%Y') fecha_busqueda, 
							DATE_FORMAT(bw.BUSQUEDA_INICIO, '%H:%i:%s') busqueda_inicio, 
							DATE_FORMAT(bw.BUSQUEDA_FIN, '%H:%i:%s') busqueda_fin, 
							TIME_TO_SEC(TIMEDIFF(bw.busqueda_fin,bw.busqueda_inicio)) segundos, 
							bw.USUARIO usuario, 
							bw.WEB web 
							from hit_busquedas_web bw left join hit_zonas z on bw.ZONA = z.CODIGO 
													left join hit_alojamientos a on bw.ALOJAMIENTO = a.ID, 
								  hit_paises p, 
								  hit_productos pr, 
								  hit_origenes o
							where (bw.PAIS = p.CODIGO or bw.PAIS = p.CODIGO_CORTO)
									and bw.PRODUCTO = pr.CODIGO 
									and bw.ORIGEN = o.CODIGO 
									".$CADENA_BUSCAR."
									and web <> 'IPSOTRAVEL'
							order by fecha_busqueda, busqueda_inicio");





		/*echo("select p.NOMBRE pais, pr.NOMBRE producto, o.NOMBRE origen, d.NOMBRE destino, 
			DATE_FORMAT(bw.FECHA, '%d-%m-%Y') salida, 
			bw.NOCHES noches, bw.CATEGORIA categoria, 
			z.NOMBRE zona, a.NOMBRE hotel,
			DATE_FORMAT(bw.BUSQUEDA_INICIO, '%d-%m-%Y') fecha_busqueda, 
			DATE_FORMAT(bw.BUSQUEDA_INICIO, '%H:%i:%s') busqueda_inicio, 
			DATE_FORMAT(bw.BUSQUEDA_FIN, '%H:%i:%s') busqueda_fin,

			TIME_TO_SEC(TIMEDIFF(bw.busqueda_fin,bw.busqueda_inicio)) segundos, 
			bw.USUARIO usuario,
			bw.WEB web
			from hit_busquedas_web bw left join hit_zonas z on bw.ZONA = z.CODIGO
				  							   left join hit_alojamientos a on bw.ALOJAMIENTO = a.ID, 
				hit_paises p, hit_productos pr, hit_origenes o, hit_destinos d
			where
			bw.PAIS = p.CODIGO
			and bw.PRODUCTO = pr.CODIGO
			and bw.ORIGEN = o.CODIGO
			and bw.DESTINO = d.CODIGO 
			".$CADENA_BUSCAR."
			 order by bw.busqueda_inicio");*/


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
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONSULTAS_BUSQUEDAS_WEB' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$consulta_presupuestos = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['pais'] == ''){
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
		$buscar_desde = $this->Buscar_desde;
		$buscar_hasta = $this->Buscar_hasta;
		$buscar_pais = $this->Buscar_pais;
		$buscar_producto = $this->Buscar_producto;



		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_desde != null and $buscar_hasta != null){
			$pai = " AND bw.pais = '".$buscar_pais."'";
			$pro = " AND bw.producto = '".$buscar_producto."'";
			$CADENA_BUSCAR = " and DATE_FORMAT(bw.BUSQUEDA_INICIO, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_desde))."' and '".date("Y-m-d",strtotime($buscar_hasta))."'";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}

		}elseif($buscar_desde == null and $buscar_hasta != null){
			$pai = " AND bw.pais = '".$buscar_pais."'";
			$pro = " AND bw.producto = '".$buscar_producto."'";
			$CADENA_BUSCAR = " and DATE_FORMAT(bw.BUSQUEDA_INICIO, '%Y-%m-%d') between curdate() and '".date("Y-m-d",strtotime($buscar_hasta))."'";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
		}elseif($buscar_desde != null and $buscar_hasta == null){
			$pai = " AND bw.pais = '".$buscar_pais."'";
			$pro = " AND bw.producto = '".$buscar_producto."'";
			$CADENA_BUSCAR = " and DATE_FORMAT(bw.BUSQUEDA_INICIO, '%Y-%m-%d') between '".date("Y-m-d",strtotime($buscar_desde))."' and curdate()";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}
		}else{
			$pai = " AND bw.pais = '".$buscar_pais."'";
			$pro = " AND bw.producto = '".$buscar_producto."'";
			$CADENA_BUSCAR = " and DATE_FORMAT(bw.BUSQUEDA_INICIO, '%Y-%m-%d') between curdate() and curdate()";
			if($buscar_pais != null){
				$CADENA_BUSCAR .= $pai;	
			}
			if($buscar_producto != null){
				$CADENA_BUSCAR .= $pro;	
			}	
		}	

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select p.NOMBRE pais, pr.NOMBRE producto, 
							o.NOMBRE origen, 
							d.NOMBRE destino,
							DATE_FORMAT(bw.FECHA, '%d-%m-%Y') salida, bw.NOCHES noches, 
							bw.CATEGORIA categoria, z.NOMBRE zona, a.NOMBRE hotel, 
							DATE_FORMAT(bw.BUSQUEDA_INICIO, '%d-%m-%Y') fecha_busqueda, 
							DATE_FORMAT(bw.BUSQUEDA_INICIO, '%H:%i:%s') busqueda_inicio, 
							DATE_FORMAT(bw.BUSQUEDA_FIN, '%H:%i:%s') busqueda_fin, 
							TIME_TO_SEC(TIMEDIFF(bw.busqueda_fin,bw.busqueda_inicio)) segundos, 
							bw.USUARIO usuario, 
							bw.WEB web 
							from hit_busquedas_web bw left join hit_zonas z on bw.ZONA = z.CODIGO 
													left join hit_alojamientos a on bw.ALOJAMIENTO = a.ID, 
								  hit_paises p, 
								  hit_productos pr, 
								  hit_origenes o, 
								  hit_destinos d
							where (bw.PAIS = p.CODIGO or bw.PAIS = p.CODIGO_CORTO)
									and bw.PRODUCTO = pr.CODIGO 
									and bw.ORIGEN = o.CODIGO 
									and bw.DESTINO = d.CODIGO
									".$CADENA_BUSCAR."
									and web = 'IPSOTRAVEL'

							union

							select p.NOMBRE pais, pr.NOMBRE producto, 
							o.NOMBRE origen, 
							bw.DESTINO,
							DATE_FORMAT(bw.FECHA, '%d-%m-%Y') salida, bw.NOCHES noches, 
							bw.CATEGORIA categoria, z.NOMBRE zona, a.NOMBRE hotel, 
							DATE_FORMAT(bw.BUSQUEDA_INICIO, '%d-%m-%Y') fecha_busqueda, 
							DATE_FORMAT(bw.BUSQUEDA_INICIO, '%H:%i:%s') busqueda_inicio, 
							DATE_FORMAT(bw.BUSQUEDA_FIN, '%H:%i:%s') busqueda_fin, 
							TIME_TO_SEC(TIMEDIFF(bw.busqueda_fin,bw.busqueda_inicio)) segundos, 
							bw.USUARIO usuario, 
							bw.WEB web 
							from hit_busquedas_web bw left join hit_zonas z on bw.ZONA = z.CODIGO 
													left join hit_alojamientos a on bw.ALOJAMIENTO = a.ID, 
								  hit_paises p, 
								  hit_productos pr, 
								  hit_origenes o
							where (bw.PAIS = p.CODIGO or bw.PAIS = p.CODIGO_CORTO)
									and bw.PRODUCTO = pr.CODIGO 
									and bw.ORIGEN = o.CODIGO 
									".$CADENA_BUSCAR."
									and web <> 'IPSOTRAVEL'
							order by fecha_busqueda, busqueda_inicio");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONSULTAS_BUSQUEDAS_WEB' AND USUARIO = '".$Usuario."'");
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
		$resultadoc =$conexion->query('SELECT * FROM hit_busquedas_web');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONSULTAS_BUSQUEDAS_WEB' AND USUARIO = '".$Usuario."'");
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
	function clsConsulta_busquedas_web($conexion, $filadesde, $usuario, $buscar_desde, $buscar_hasta, $buscar_pais, $buscar_producto){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_desde = $buscar_desde;
		$this->Buscar_hasta = $buscar_hasta;
		$this->Buscar_pais = $buscar_pais;
		$this->Buscar_producto = $buscar_producto;
	}
}

?>