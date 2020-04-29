<?php

class clsCuadros_precios{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var $buscar_cuadro;

//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LAS PRECIOS---
//--------------------------------------------------------------------

	function Cargar_calendario_cabecera($clave){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

		//Averiguamos el producto de cuadro
		$resultado_producto =$conexion->query("SELECT producto FROM hit_producto_cuadros where clave = '".$clave."'");
		$producto_cuadro = $resultado_producto->fetch_assoc();
		$producto = $producto_cuadro['producto'];

		if($producto == 'VAC' || $producto == 'OVA' || $producto == 'SHO'){
			$resultado_calendario =$conexion->query("SELECT concat(concat(DATE_FORMAT(fecha_desde, '%d '),`GENERAL_TRADUCE_MES`(DATE_FORMAT(fecha_desde, '%m'))),' - ', 
													 concat(DATE_FORMAT(fecha_hasta, '%d '),`GENERAL_TRADUCE_MES`(DATE_FORMAT(fecha_hasta, '%m')))) AS desde
											FROM hit_producto_cuadros_calendarios
											WHERE clave_cuadro = '".$clave."' ORDER BY fecha_desde");
		}else{
			$resultado_calendario =$conexion->query("SELECT concat(concat(DATE_FORMAT(s.FECHA, '%d '),`GENERAL_TRADUCE_MES`(DATE_FORMAT(s.FECHA, '%m')))) AS desde
											FROM hit_producto_cuadros_calendarios c, hit_producto_cuadros_salidas s
											WHERE 
											c.CLAVE_CUADRO = s.CLAVE_CUADRO
											and s.FECHA between c.FECHA_DESDE and c.FECHA_HASTA
											and c.clave_cuadro = '".$clave."' ORDER BY c.fecha_desde");
		}

		if ($resultado_calendario == FALSE){
			echo('Error en la consulta: '.$clave);
			$resultado_calendario->close();
			$conexion->close();
			exit;
		}

		$calendario = array();
		for ($num_fila = 0; $num_fila < $resultado_calendario->num_rows; $num_fila++) {
			$resultado_calendario->data_seek($num_fila);
			$fila = $resultado_calendario->fetch_assoc();
			array_push($calendario,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado_calendario->close();


		return $calendario;											
	}

	function Cargar_precios($clave, $buscar_ciudad, $buscar_paquete){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

		//Averiguamos el producto de cuadro
		$resultado_producto =$conexion->query("SELECT producto FROM hit_producto_cuadros where clave = '".$clave."'");
		$producto_cuadro = $resultado_producto->fetch_assoc();
		$producto = $producto_cuadro['producto'];


		if($buscar_ciudad != 'SIN' and $buscar_paquete != null){
			$cadena_buscar = " and p.paquete = '".$buscar_paquete."'";
		}else{
			$cadena_buscar = " and p.paquete = ''";
		}

		if($producto == 'VAC' || $producto == 'OVA' || $producto == 'SHO'){
				

				//OBTENEMOS LA LISTA DE PAQUETES
				$resultado_paquetes =$conexion->query("SELECT concat(a.nombre,'-',p.habitacion,'-',p.caracteristica,'-',p.regimen) nombre_paquete,
												p.paquete,p.numero, p.clave
												FROM hit_producto_cuadros_alojamientos p, hit_alojamientos a
												WHERE p.alojamiento = a.id and p.clave_cuadro = '".$clave."'".$cadena_buscar." ORDER BY p.paquete, p.numero");

				if ($resultado_paquetes == FALSE){
					echo('Error en la consulta: '.$clave);
					$resultado_paquetes->close();
					$conexion->close();
					exit;
				}


				$paquetes = array();
				for ($num_fila = 0; $num_fila < $resultado_paquetes->num_rows; $num_fila++) {
					$resultado_paquetes->data_seek($num_fila);
					$fila_paquetes = $resultado_paquetes->fetch_assoc();
					//echo($fila_paquetes['nombre_paquete']);
					$fila_paquetes['precios'] = '';
					//echo($fila_paquetes['precios']);

					//Aqui añadimos una cadena con todas las columnas de precios de cada linea
					//Buscamos para cada linea del calendario general del cuadro haya o no precios para este paquete
					
						$resultado_calendario =$conexion->query("SELECT clave clave_calendario
															FROM hit_producto_cuadros_calendarios
															WHERE clave_cuadro = '".$clave."' ORDER BY fecha_desde");
						$calendario = array();
						for ($num_fila_cal = 0; $num_fila_cal < $resultado_calendario->num_rows; $num_fila_cal++) {
							$resultado_calendario->data_seek($num_fila_cal);
							$fila_calendario = $resultado_calendario->fetch_assoc();
							

							//Para cada linea buscamos los precios de los alojamientos
							$datos_precios =$conexion->query("SELECT p.PRECIO, p.SPTO_INDIVIDUAL, p.SPTO_NOCHE_EXTRA
																FROM
																	hit_producto_cuadros_alojamientos a, 
																	hit_producto_cuadros_alojamientos_precios p
																	where
																		a.CLAVE_CUADRO = p.CLAVE_CUADRO and a.PAQUETE = p.PAQUETE and a.NUMERO = p.NUMERO
																		and a.CLAVE_CUADRO = '".$clave."' and a.CLAVE = '".$fila_paquetes['clave']."' and p.CLAVE_CALENDARIO = '".$fila_calendario['clave_calendario']."'");
																



							$precios_paquete = $datos_precios->fetch_assoc();
							$pvp_alojamientos = $precios_paquete['PRECIO'];
							$sind = $precios_paquete['SPTO_INDIVIDUAL'];
							$next = $precios_paquete['SPTO_NOCHE_EXTRA'];


							//Tambien buscamos los precios de los servicios
							$datos_precios_servicios =$conexion->query("SELECT sum(p.PRECIO) precio_servicios
																		FROM
																			hit_producto_cuadros_servicios_precios p, hit_producto_cuadros_servicios s
																			where
																				p.CLAVE_CUADRO = s.CLAVE_CUADRO
																				and p.CLAVE_SERVICIO = s.CLAVE
																				and s.TIPO = 'I'
																				and p.CLAVE_CUADRO = '".$clave."' and p.CLAVE_CALENDARIO = '".$fila_calendario['clave_calendario']."'");	

							$precios_servicios = $datos_precios_servicios->fetch_assoc();
							$pvp_servicios = $precios_servicios['precio_servicios'];


							//Tambien buscamos los precios de los aereos
							$datos_precios_aereos =$conexion->query("SELECT sum(p.PRECIO_1) precio_aereos
																		FROM
																			hit_producto_cuadros_aereos_precios p, hit_producto_cuadros_aereos a
																			where
																				p.CLAVE_CUADRO = a.CLAVE_CUADRO
																				and p.CLAVE_AEREO = a.CLAVE
																				and a.CIUDAD = '".$buscar_ciudad."'
																				and p.CLAVE_CUADRO = '".$clave."' and p.CLAVE_CALENDARIO = '".$fila_calendario['clave_calendario']."' and a.opcion = 1");	

							$precios_aereos = $datos_precios_aereos->fetch_assoc();
							$pvp_aereos = $precios_aereos['precio_aereos'];

							//si se ha elegido ciudad de salida pero no se encuentran precios se envia precio nulo para todo el paquete
							if($buscar_ciudad != 'SIN' and $pvp_aereos == null){
								$pvp_alojamientos = null;
								$sind = null;
								$next = null;
							}

							if($pvp_alojamientos != null){
									$pvp_total = $pvp_alojamientos + $pvp_servicios + $pvp_aereos;

							}else{
								$pvp_total = '';
							}

							//echo($pvp.'-'.$sind.'-'.$next.' / ');
							$fila_paquetes['precios'] .= "<td align = 'center' class='celda_precios'>".$pvp_total."</td><td align = 'center' class='celda_precios_suplementos'>".$sind."</td><td align = 'center' class='celda_precios_suplementos'>".$next."</td>";

						}

					array_push($paquetes,$fila_paquetes);

				}

				//Liberar Memoria usada por la consulta
				$resultado_paquetes->close();

				return $paquetes;			
		}elseif($producto == 'SVO' || $producto == 'OSV'){
				
				//OBTENEMOS LA LISTA DE OPCIONES
				/*$resultado_paquetes =$conexion->query("SELECT concat(a.nombre,'-',p.habitacion,'-',p.caracteristica,'-',p.regimen) nombre_paquete,
												p.paquete,p.numero, p.clave
												FROM hit_producto_cuadros_alojamientos p, hit_alojamientos a
												WHERE p.alojamiento = a.id and p.clave_cuadro = '".$clave."' ORDER BY p.paquete, p.numero");*/

				//OBTENEMOS LA LISTA DE OPCIONES
				$resultado_opciones =$conexion->query("SELECT distinct concat('Salida:',a.CIUDAD,' Opcion:',a.OPCION,' - ',t.NOMBRE) nombre_paquete, 
															  a.CIUDAD ciudad, a.OPCION opcion
													   FROM hit_producto_cuadros_aereos a, hit_transportes t
													   WHERE a.CIA = t.CIA 
														     and a.clave_cuadro = '".$clave."' ORDER BY a.CIUDAD, a.OPCION");


				if ($resultado_opciones == FALSE){
					echo('Error en la consulta: '.$clave);
					$resultado_opciones->close();
					$conexion->close();
					exit;
				}


				$paquetes = array();
				for ($num_fila = 0; $num_fila < $resultado_opciones->num_rows; $num_fila++) {
					$resultado_opciones->data_seek($num_fila);
					$fila_paquetes = $resultado_opciones->fetch_assoc();
					//echo($fila_paquetes['nombre_paquete']);
					$fila_paquetes['precios'] = '';
					//echo($fila_paquetes['precios']);

					//Aqui añadimos una cadena con todas las columnas de precios de cada linea
					//Buscamos para cada linea del calendario general del cuadro haya o no precios para esta opcion
					
						$resultado_calendario =$conexion->query("SELECT clave clave_calendario
															FROM hit_producto_cuadros_calendarios
															WHERE clave_cuadro = '".$clave."' ORDER BY fecha_desde");
						$calendario = array();
						for ($num_fila_cal = 0; $num_fila_cal < $resultado_calendario->num_rows; $num_fila_cal++) {
							$resultado_calendario->data_seek($num_fila_cal);
							$fila_calendario = $resultado_calendario->fetch_assoc();
							

							//Tambien buscamos los precios de los servicios
							/*$datos_precios_servicios =$conexion->query("SELECT sum(p.PRECIO) precio_servicios
																		FROM
																			hit_producto_cuadros_servicios_precios p, hit_producto_cuadros_servicios s
																			where
																				p.CLAVE_CUADRO = s.CLAVE_CUADRO
																				and p.CLAVE_SERVICIO = s.CLAVE
																				and s.TIPO = 'I'
																				and p.CLAVE_CUADRO = '".$clave."' and p.CLAVE_CALENDARIO = '".$fila_calendario['clave_calendario']."'");

							$precios_servicios = $datos_precios_servicios->fetch_assoc();
							$pvp_servicios = $precios_servicios['precio_servicios'];*/


							//Tambien buscamos los precios de los aereos
							$datos_precios_aereos =$conexion->query("SELECT sum(p.PRECIO_1) precio_aereos
																		FROM
																			hit_producto_cuadros_aereos_precios p, hit_producto_cuadros_aereos a
																			where
																				p.CLAVE_CUADRO = a.CLAVE_CUADRO
																				and p.CLAVE_AEREO = a.CLAVE
																				and a.CIUDAD = '".$fila_paquetes['ciudad']."'
																				and p.CLAVE_CUADRO = '".$clave."' and p.CLAVE_CALENDARIO = '".$fila_calendario['clave_calendario']."' and a.opcion = '".$fila_paquetes['opcion']."'");		

							$precios_aereos = $datos_precios_aereos->fetch_assoc();
							$pvp_aereos = $precios_aereos['precio_aereos'];

							//si se ha elegido ciudad de salida pero no se encuentran precios se envia precio nulo para todo el paquete
							if($buscar_ciudad != 'SIN' and $pvp_aereos == null){
								$pvp_aereos = null;

							}

							if($pvp_aereos != null){
									$pvp_total = $pvp_aereos;
							}else{
								$pvp_total = '';
							}

							//echo($pvp.'-'.$sind.'-'.$next.' / ');
							$fila_paquetes['precios'] .= "<td align = 'center' class='celda_precios'>".$pvp_total."</td>";

						}

					array_push($paquetes,$fila_paquetes);

				}

				//Liberar Memoria usada por la consulta
				$resultado_opciones->close();

				return $paquetes;		

		}elseif($producto == 'SSV'){
				
				//OBTENEMOS LA LISTA DE OPCIONES
				/*$resultado_paquetes =$conexion->query("SELECT concat(a.nombre,'-',p.habitacion,'-',p.caracteristica,'-',p.regimen) nombre_paquete,
												p.paquete,p.numero, p.clave
												FROM hit_producto_cuadros_alojamientos p, hit_alojamientos a
												WHERE p.alojamiento = a.id and p.clave_cuadro = '".$clave."' ORDER BY p.paquete, p.numero");*/

				//OBTENEMOS LA LISTA DE OPCIONES
				$resultado_opciones =$conexion->query("SELECT distinct concat('Salida:',a.CIUDAD,' Opcion:',a.OPCION,' - ',t.NOMBRE) nombre_paquete, 
															  a.CIUDAD ciudad, a.OPCION opcion
													   FROM hit_producto_cuadros_aereos a, hit_transportes t
													   WHERE a.CIA = t.CIA 
														     and a.clave_cuadro = '".$clave."' ORDER BY a.CIUDAD, a.OPCION");


				if ($resultado_opciones == FALSE){
					echo('Error en la consulta: '.$clave);
					$resultado_opciones->close();
					$conexion->close();
					exit;
				}


				$paquetes = array();
				for ($num_fila = 0; $num_fila < $resultado_opciones->num_rows; $num_fila++) {
					$resultado_opciones->data_seek($num_fila);
					$fila_paquetes = $resultado_opciones->fetch_assoc();
					//echo($fila_paquetes['nombre_paquete']);
					$fila_paquetes['precios'] = '';
					//echo($fila_paquetes['precios']);

					//Aqui añadimos una cadena con todas las columnas de precios de cada linea
					//Buscamos para cada linea del calendario general del cuadro haya o no precios para esta opcion
					
						$resultado_calendario =$conexion->query("SELECT clave clave_calendario
															FROM hit_producto_cuadros_calendarios
															WHERE clave_cuadro = '".$clave."' ORDER BY fecha_desde");
						$calendario = array();
						for ($num_fila_cal = 0; $num_fila_cal < $resultado_calendario->num_rows; $num_fila_cal++) {
							$resultado_calendario->data_seek($num_fila_cal);
							$fila_calendario = $resultado_calendario->fetch_assoc();
							

							//Tambien buscamos los precios de los servicios
							$datos_precios_servicios =$conexion->query("SELECT sum(p.PRECIO) precio_servicios
																		FROM
																			hit_producto_cuadros_servicios_precios p, hit_producto_cuadros_servicios s
																			where
																				p.CLAVE_CUADRO = s.CLAVE_CUADRO
																				and p.CLAVE_SERVICIO = s.CLAVE
																				and s.TIPO = 'I'
																				and p.CLAVE_CUADRO = '".$clave."' and p.CLAVE_CALENDARIO = '".$fila_calendario['clave_calendario']."'");

							$precios_servicios = $datos_precios_servicios->fetch_assoc();
							$pvp_servicios = $precios_servicios['precio_servicios'];


							//Tambien buscamos los precios de los aereos
							/*$datos_precios_aereos =$conexion->query("SELECT sum(p.PRECIO_1) precio_aereos
																		FROM
																			hit_producto_cuadros_aereos_precios p, hit_producto_cuadros_aereos a
																			where
																				p.CLAVE_CUADRO = a.CLAVE_CUADRO
																				and p.CLAVE_AEREO = a.CLAVE
																				and a.CIUDAD = '".$fila_paquetes['ciudad']."'
																				and p.CLAVE_CUADRO = '".$clave."' and p.CLAVE_CALENDARIO = '".$fila_calendario['clave_calendario']."' and a.opcion = '".$fila_paquetes['opcion']."'");		

							$precios_aereos = $datos_precios_aereos->fetch_assoc();
							$pvp_aereos = $precios_aereos['precio_aereos'];*/

							//si se ha elegido ciudad de salida pero no se encuentran precios se envia precio nulo para todo el paquete
							if($buscar_ciudad != 'SIN' and $pvp_aereos == null){
								$pvp_aereos = null;

							}

							if($pvp_aereos != null){
									$pvp_total = $pvp_aereos;
							}else{
								$pvp_total = '';
							}

							//echo($pvp.'-'.$sind.'-'.$next.' / ');
							$fila_paquetes['precios'] .= "<td align = 'center' class='celda_precios'>".$pvp_total."</td>";

						}

					array_push($paquetes,$fila_paquetes);

				}

				//Liberar Memoria usada por la consulta
				$resultado_opciones->close();

				return $paquetes;		

		}


	}

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsCuadros_precios($conexion, $filadesde, $usuario, $buscar_codigo, $buscar_cuadro){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_codigo= $buscar_codigo;
		$this->Buscar_cuadro= $buscar_cuadro;
	}
}

?>