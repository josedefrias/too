<?php

class clsTeletipos_visualizar{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var $buscar_nombre;

//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS HOTELES--------------
//--------------------------------------------------------------------

	function Cargar_hoteles($id){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		$resultado =$conexion->query("SELECT t.ciudad ciudad, a.id id_hotel, t.orden orden, a.nombre nombre,  
											replace(cat.NOMBRE, '*', '☆') categoria,
											replace(substr(cat.NOMBRE,1,3), '*', '☆') categoria_2,
											substr(cat.NOMBRE,1,3) categoria_3,
											a.localidad localidad, t.regimen regimen, t.precio precio, t.precio_2 precio_2,  t.precio_3 precio_3,
											log1.FICHERO logo1, log2.FICHERO logo2, log3.FICHERO logo3
											FROM hit_teletipos_hoteles t left join hit_logos log1 on t.LOGO_1 = log1.ID 
																		 left join hit_logos log2 on t.LOGO_2 = log2.ID
																		 left join hit_logos log3 on t.LOGO_3 = log3.ID, 
												  hit_alojamientos a, 
												  hit_categorias cat,
												  hit_teletipos tel,
												  hit_producto_cuadros cua
											WHERE t.hotel = a.id 
												  and a.CATEGORIA = cat.CODIGO
												  and t.ID = tel.ID
												  and tel.CUADRO = cua.CLAVE
												  and cua.PRODUCTO not in ('SVO','OSV')
												  and t.ID = '".$id."'
								union				  
												  
								SELECT t.ciudad ciudad, 0, t.orden orden, '',  
											'' categoria,
											'' categoria_2,
											'' categoria_3,
											'' localidad, '' regimen, t.precio precio, t.precio_2 precio_2,  t.precio_3 precio_3,
											log1.FICHERO logo1, log2.FICHERO logo2, log3.FICHERO logo3
											FROM hit_teletipos_hoteles t left join hit_logos log1 on t.LOGO_1 = log1.ID 
																		 left join hit_logos log2 on t.LOGO_2 = log2.ID
																		 left join hit_logos log3 on t.LOGO_3 = log3.ID, 

												  hit_teletipos tel,
												  hit_producto_cuadros cua
											WHERE t.ID = tel.ID
												  and tel.CUADRO = cua.CLAVE
												  and cua.PRODUCTO in ('SVO','OSV')
												  and t.ID = '".$id."' 
								ORDER BY precio");


		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//echo($id."-".$filadesde_hoteles."-".$buscar_hotel);
							                        //------

		$hoteles = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows;  $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($hoteles,$fila);
		}
		
		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $hoteles;											
	}

	function Cargar_vuelos($id, $ciudad, $fecha_busqueda){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		//echo($fecha_busqueda.'-');

		if($id != ''){
			$CADENA = "and t.ID = ".$id." ";
		}else{
			$CADENA = "";
		}

		/*$resultado =$conexion->query("select tt.cia cia, tt.origen origen, tt.destino destino, tt.vuelo vuelo,  
										time_format(tt.hora_salida, '%H:%i') AS hora_salida, 
										time_format(tt.hora_llegada, '%H:%i') AS hora_llegada
										from hit_teletipos t,
														  hit_teletipos_aereos ta, 
														  hit_producto_cuadros_aereos pa,
														  hit_transportes_trayectos tt
										where
											t.ID = ta.ID
											and t.CUADRO = pa.CLAVE_CUADRO
											and ta.CIUDAD = pa.CIUDAD
											and ta.OPCION = pa.OPCION
											and pa.CIA = tt.CIA
											and pa.ACUERDO = tt.ACUERDO
											and pa.SUBACUERDO = tt.SUBACUERDO
											and pa.ORIGEN = tt.ORIGEN
											and pa.DESTINO = tt.DESTINO
											".$CADENA."
											and ta.CIUDAD = '".$ciudad."'
											order by tt.TRAYECTO, tt.HORA_SALIDA");*/

		$resultado =$conexion->query("select distinct cu.cia cia, cu.origen origen, cu.destino destino, cu.vuelo vuelo,
										time_format(cu.HORA_SALIDA, '%H:%i') AS hora_salida, 
										time_format(cu.hora_llegada, '%H:%i') AS hora_llegada,
										papr.CLASE_1 clase_1
										from hit_teletipos t,
											  hit_teletipos_aereos ta, 
											  hit_producto_cuadros_aereos pa,
											  hit_transportes_cupos cu,
											  hit_producto_cuadros_aereos_precios papr
										where
											t.ID = ta.ID
											and t.CUADRO = pa.CLAVE_CUADRO
											and ta.CIUDAD = pa.CIUDAD
											and ta.OPCION = pa.OPCION
											and pa.CIA = cu.CIA
											and pa.ACUERDO = cu.ACUERDO
											and pa.SUBACUERDO = cu.SUBACUERDO
											and pa.ORIGEN = cu.ORIGEN
											and pa.DESTINO = cu.DESTINO
											and cu.FECHA = DATE_ADD('".date("Y-m-d",strtotime($fecha_busqueda))."',INTERVAL t.DIAS - 1 DAY)
											and pa.CLAVE = papr.CLAVE_AEREO
											and DATE_ADD('".date("Y-m-d",strtotime($fecha_busqueda))."',INTERVAL t.DIAS - 1 DAY) between papr.FECHA_DESDE and papr.FECHA_HASTA
											".$CADENA."
											and ta.CIUDAD = '".$ciudad."'
											order by cu.fecha");





		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//echo($id."-".$filadesde_hoteles."-".$buscar_hotel);
							                        //------

		$vuelos = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows;  $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($vuelos,$fila);
		}

		
		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $vuelos;											
	}



	function carga_html_oferta($id, $formato, $ciudad, $colores){

		$conexion = $this ->Conexion;

		if($ciudad == null){
			$ciudad_por_defecto =$conexion->query("SELECT min(ciudad) ciudad FROM hit_teletipos_aereos WHERE ID = '".$id."'");
			$ociudad_por_defecto = $ciudad_por_defecto->fetch_assoc();
			$ciudad = $ociudad_por_defecto['ciudad'];
		}
		//echo($ciudad);

		//DATOS TELETIPO
		$datos_teletipo =$conexion->query("SELECT t.titulo, 
												  t.colores,
												  t.cabecera_imagen, 
												  t.logo_dcto_1, 
												  t.logo_dcto_2, 
												  d.NOMBRE destino,
												  t.cuadro,
												  concat(
												  DATE_FORMAT(t.fecha_lanzamiento, '%d'),
												  ' de ',
												  case DATE_FORMAT(t.fecha_lanzamiento, '%M')
												  	when 'January' then 'Enero'
												  	when 'February' then 'Febrero'
												  	when 'March' then 'Marzo'
												  	when 'April' then 'Abril'
												  	when 'May' then 'Mayo'
												  	when 'June' then 'Junio'
												  	when 'July' then 'Julio'
												  	when 'August' then 'Agosto'
												  	when 'September' then 'Septiembre'
												  	when 'October' then 'Octubre'
												  	when 'November' then 'Noviembre'
												  	when 'December' then 'Diciembre'
												  	else DATE_FORMAT(t.fecha_lanzamiento, '%M')
												  	end,
												  ' de ',
												  DATE_FORMAT(t.fecha_lanzamiento, '%Y')
												  )
												  AS fecha_lanzamiento,
												  t.texto_libre_1,
												  t.texto_libre_2,
												  t.texto_libre_3,
												  t.texto_pie,
												  DATE_FORMAT(t.fecha_desde_1, '%d-%m-%Y') AS fecha_desde_1,
											 	  DATE_FORMAT(t.fecha_hasta_1, '%d-%m-%Y') AS fecha_hasta_1,
												  t.cabecera_1,
												  DATE_FORMAT(t.fecha_desde_2, '%d-%m-%Y') AS fecha_desde_2,
												  DATE_FORMAT(t.fecha_hasta_2, '%d-%m-%Y') AS fecha_hasta_2,
												  t.cabecera_2,
												  DATE_FORMAT(t.fecha_desde_3, '%d-%m-%Y') AS fecha_desde_3,
												  DATE_FORMAT(t.fecha_hasta_3, '%d-%m-%Y') AS fecha_hasta_3,
												  t.cabecera_3,
												  concat(
												  case when t.cabecera_1 <> '' then t.cabecera_1 else '' end,
												  case when t.cabecera_2 <> '' and t.cabecera_3 <> '' then ', '
												  	   when t.cabecera_2 <> '' and t.cabecera_3 = '' then ' y '
												  	   else ''
												  	   end,
												  case when t.cabecera_2 <> '' then concat(t.cabecera_2) else '' end,
												  case when t.cabecera_3 <> '' then ' y '
												  	   else ''
												  	   end,
												  case when t.cabecera_3 <> '' then concat(t.cabecera_3) else '' end	
												  ) salidas_texto_pie
											FROM hit_teletipos t, hit_destinos d
											WHERE t.DESTINO = d.CODIGO	and ID = '".$id."'");
		$odatos_teletipo = $datos_teletipo->fetch_assoc();
		$teletipo_titulo = $odatos_teletipo['titulo'];

		//Si se ha pasado el parametro para la combinacion de colores lo tomamos, sino usamos el del registro del teletipo
		if($colores == ''){
			$teletipo_colores = $odatos_teletipo['colores'];
		}else{
			$teletipo_colores = $colores;
		}

		$teletipo_cabecera_imagen_codigo = $odatos_teletipo['cabecera_imagen'];
		$teletipo_logo_dcto_1_codigo = $odatos_teletipo['logo_dcto_1'];
		$teletipo_logo_dcto_2_codigo = $odatos_teletipo['logo_dcto_2'];
		$destino = $odatos_teletipo['destino'];
		$texto_libre_1 = $odatos_teletipo['texto_libre_1'];
		$texto_libre_2 = $odatos_teletipo['texto_libre_2'];
		$texto_libre_3 = $odatos_teletipo['texto_libre_3'];
		$texto_pie = $odatos_teletipo['texto_pie'];
		$fecha_desde_1 = $odatos_teletipo['fecha_desde_1'];
		$fecha_hasta_1 = $odatos_teletipo['fecha_hasta_1'];
		$cabecera_1 = $odatos_teletipo['cabecera_1'];
		$fecha_desde_2 = $odatos_teletipo['fecha_desde_2'];
		$fecha_hasta_2 = $odatos_teletipo['fecha_hasta_2'];
		$cabecera_2 = $odatos_teletipo['cabecera_2'];
		$fecha_desde_3 = $odatos_teletipo['fecha_desde_3'];
		$fecha_hasta_3 = $odatos_teletipo['fecha_hasta_3'];
		$cabecera_3 = $odatos_teletipo['cabecera_3'];
		$cuadro = $odatos_teletipo['cuadro'];
		$fecha_lanzamiento = $odatos_teletipo['fecha_lanzamiento'];
		$salidas_texto_pie = $odatos_teletipo['salidas_texto_pie'];
		//echo($odatos_teletipo['cuadro']);

		$datos_cabecera_imagen =$conexion->query("SELECT fichero FROM hit_logos WHERE ID = '".$teletipo_cabecera_imagen_codigo."'");
		$odatos_cabecera_imagen = $datos_cabecera_imagen->fetch_assoc();
		$cabecera_imagen = $odatos_cabecera_imagen['fichero'];

		$datos_logo_dcto_1 =$conexion->query("SELECT fichero FROM hit_logos WHERE ID = '".$teletipo_logo_dcto_1_codigo."'");
		$odatos_logo_dcto_1 = $datos_logo_dcto_1->fetch_assoc();
		$logo_dcto_1 = $odatos_logo_dcto_1['fichero'];

		$datos_logo_dcto_2 =$conexion->query("SELECT fichero FROM hit_logos WHERE ID = '".$teletipo_logo_dcto_2_codigo."'");
		$odatos_logo_dcto_2 = $datos_logo_dcto_2->fetch_assoc();
		$logo_dcto_2 = $odatos_logo_dcto_2['fichero'];

		//PRECIO DESDE
		$datos_precio_desde =$conexion->query("select  min(precio_desde) precio_desde
												from
												(
												SELECT  ifnull(min(precio),0) precio_desde
															FROM hit_teletipos_hoteles a
															WHERE a.PRECIO <> 0
																	and a.CIUDAD = '".$ciudad."'	and ID = '".$id."'
												UNION
												SELECT  ifnull(min(precio_2),0) precio_desde
															FROM hit_teletipos_hoteles a
															WHERE a.PRECIO_2 <> 0
																	and a.CIUDAD = '".$ciudad."'	and ID = '".$id."'
												UNION
												SELECT  ifnull(min(precio_3),0) precio_desde
															FROM hit_teletipos_hoteles a
															WHERE a.PRECIO_3 <> 0
																	and a.CIUDAD = '".$ciudad."'	and ID = '".$id."'
												) precios_min 
												where precio_desde > 0");
		$odatos_precio_desde = $datos_precio_desde->fetch_assoc();
		$precio_desde = $odatos_precio_desde['precio_desde'];



		//DATOS CIUDAD SALIDA
		$datos_aereos =$conexion->query("SELECT  
												  c.nombre nombre_ciudad,
												  a.opcion, 
												  DATE_FORMAT(a.fecha_desde_1, '%d-%m-%Y') AS fecha_desde_1,
											 	  DATE_FORMAT(a.fecha_hasta_1, '%d-%m-%Y') AS fecha_hasta_1,
												  a.cabecera_1,
												  DATE_FORMAT(a.fecha_desde_2, '%d-%m-%Y') AS fecha_desde_2,
												  DATE_FORMAT(a.fecha_hasta_2, '%d-%m-%Y') AS fecha_hasta_2,
												  a.cabecera_2,
												  DATE_FORMAT(a.fecha_desde_3, '%d-%m-%Y') AS fecha_desde_3,
												  DATE_FORMAT(a.fecha_hasta_3, '%d-%m-%Y') AS fecha_hasta_3,
												  a.cabecera_3
											FROM hit_teletipos_aereos a, hit_ciudades c
											WHERE a.CIUDAD = c.CODIGO
													and a.CIUDAD = '".$ciudad."'	and ID = '".$id."'");
		$odatos_aereos = $datos_aereos->fetch_assoc();

		$opcion= $odatos_aereos['opcion'];
		$nombre_ciudad = $odatos_aereos['nombre_ciudad'];

		//Si hay datos especificos de cabecera para esta ciudad de salida los tomamos
		if($odatos_aereos['cabecera_1'] != ''){
			$fecha_desde_1 = $odatos_aereos['fecha_desde_1'];
			$fecha_hasta_1 = $odatos_aereos['fecha_hasta_1'];
			$cabecera_1 = $odatos_aereos['cabecera_1'];
			$fecha_desde_2 = $odatos_aereos['fecha_desde_2'];
			$fecha_hasta_2 = $odatos_aereos['fecha_hasta_2'];
			$cabecera_2 = $odatos_aereos['cabecera_2'];
			$fecha_desde_3 = $odatos_aereos['fecha_desde_3'];
			$fecha_hasta_3 = $odatos_aereos['fecha_hasta_3'];
			$cabecera_3 = $odatos_aereos['cabecera_3'];
		}

		//DATOS CUADRO
		$datos_cuadro =$conexion->query("select producto, destino, duracion-1 noches from hit_producto_cuadros where clave = '".$cuadro."'");
		$odatos_cuadro = $datos_cuadro->fetch_assoc();

		$producto_cuadro = $odatos_cuadro['producto'];
		$destino_cuadro = $odatos_cuadro['destino'];
		$noches_cuadro = $odatos_cuadro['noches'];


		//DATOS FECHA BUSQUEDA ENLACE
		$datos_fecha_busqueda =$conexion->query("select DATE_FORMAT(min(sal.FECHA), '%d-%m-%Y') AS  fecha_busqueda
				from hit_producto_cuadros c, 
					  hit_producto_cuadros_salidas sal,
					  hit_fechas fec,
					  hit_producto_cuadros_calendarios_ciudades calciu
				where 
					c.CLAVE = sal.CLAVE_CUADRO
					and sal.FECHA = fec.FECHA
					and sal.CLAVE_CUADRO = calciu.CLAVE_CUADRO
					and sal.FECHA between calciu.FECHA_DESDE and calciu.FECHA_HASTA
					and calciu.CIUDAD = '".$ciudad."'
					AND fec.DIA in (substr(calciu.DIAS_SEMANA,1,1), substr(calciu.DIAS_SEMANA,2,1), substr(calciu.DIAS_SEMANA,3,1), substr(calciu.DIAS_SEMANA,4,1),
										 substr(calciu.DIAS_SEMANA,5,1), substr(calciu.DIAS_SEMANA,6,1), substr(calciu.DIAS_SEMANA,7,1))
					and (sal.FECHA between '".date("Y-m-d",strtotime($fecha_desde_1))."' and '".date("Y-m-d",strtotime($fecha_hasta_1))."' 
						  or sal.FECHA between '".date("Y-m-d",strtotime($fecha_desde_2))."' and '".date("Y-m-d",strtotime($fecha_hasta_2))."' 
						  or sal.FECHA between '".date("Y-m-d",strtotime($fecha_desde_3))."' and '".date("Y-m-d",strtotime($fecha_hasta_3))."')
					and sal.FECHA > DATE_ADD(curdate(),INTERVAL 2 DAY)
				   and c.clave = '".$cuadro."'");

		$odatos_fecha_busqueda = $datos_fecha_busqueda->fetch_assoc();
		$fecha_busqueda = $odatos_fecha_busqueda['fecha_busqueda'];

		//echo($ciudad.'-'.$cuadro.'-'.$fecha_busqueda.'-'.$fecha_desde_1.'-'.$fecha_hasta_1.'-'.$fecha_desde_2.'-'.$fecha_hasta_2.'-'.$fecha_desde_3.'-'.$fecha_hasta_3);

		//DATOS SALIDAS DIAS DE LA SEMANA
		$datos_dias_semana =$conexion->query("select max(dias.lunes) lunes, max(dias.martes) martes,
		 max(dias.miercoles) miercoles, max(dias.jueves) jueves, 
		 max(dias.viernes) viernes, max(dias.sabado) sabado, 
		 max(dias.domingo) domingo
						from
						(
						select  distinct 
									case  when substr(dias_semana,1,1) = 'L' then 'Lunes'
										  	when substr(dias_semana,2,1) = 'L' then 'Lunes'
											when substr(dias_semana,3,1) = 'L' then 'Lunes'
											when substr(dias_semana,4,1) = 'L' then 'Lunes'
											when substr(dias_semana,5,1) = 'L' then 'Lunes'
											when substr(dias_semana,6,1) = 'L' then 'Lunes'
											when substr(dias_semana,7,1) = 'L' then 'Lunes'
										end lunes,
									case  
											when substr(dias_semana,1,1) = 'M' then 'Martes'
										  	when substr(dias_semana,2,1) = 'M' then 'Martes'
											when substr(dias_semana,3,1) = 'M' then 'Martes'
											when substr(dias_semana,4,1) = 'M' then 'Martes'
											when substr(dias_semana,5,1) = 'M' then 'Martes'
											when substr(dias_semana,6,1) = 'M' then 'Martes'
											when substr(dias_semana,7,1) = 'M' then 'Martes'
										end martes,
									case  
											when substr(dias_semana,1,1) = 'X' then 'Miércoles'
										  	when substr(dias_semana,2,1) = 'X' then 'Miércoles'
											when substr(dias_semana,3,1) = 'X' then 'Miércoles'
											when substr(dias_semana,4,1) = 'X' then 'Miércoles'
											when substr(dias_semana,5,1) = 'X' then 'Miércoles'
											when substr(dias_semana,6,1) = 'X' then 'Miércoles'
											when substr(dias_semana,7,1) = 'X' then 'Miércoles'
										end miercoles,
									case  
											when substr(dias_semana,1,1) = 'J' then 'Jueves'
										  	when substr(dias_semana,2,1) = 'J' then 'Jueves'
											when substr(dias_semana,3,1) = 'J' then 'Jueves'
											when substr(dias_semana,4,1) = 'J' then 'Jueves'
											when substr(dias_semana,5,1) = 'J' then 'Jueves'
											when substr(dias_semana,6,1) = 'J' then 'Jueves'
											when substr(dias_semana,7,1) = 'J' then 'Jueves'
										end jueves,
									case  
											when substr(dias_semana,1,1) = 'V' then 'Viernes'
										  	when substr(dias_semana,2,1) = 'V' then 'Viernes'
											when substr(dias_semana,3,1) = 'V' then 'Viernes'
											when substr(dias_semana,4,1) = 'V' then 'Viernes'
											when substr(dias_semana,5,1) = 'V' then 'Viernes'
											when substr(dias_semana,6,1) = 'V' then 'Viernes'
											when substr(dias_semana,7,1) = 'V' then 'Viernes'
										end viernes,
									case  
											when substr(dias_semana,1,1) = 'S' then 'Sábados'
										  	when substr(dias_semana,2,1) = 'S' then 'Sábados'
											when substr(dias_semana,3,1) = 'S' then 'Sábados'
											when substr(dias_semana,4,1) = 'S' then 'Sábados'
											when substr(dias_semana,5,1) = 'S' then 'Sábados'
											when substr(dias_semana,6,1) = 'S' then 'Sábados'
											when substr(dias_semana,7,1) = 'S' then 'Sábados'
										end sabado,
									case  
											when substr(dias_semana,1,1) = 'D' then 'Domingos'
										  	when substr(dias_semana,2,1) = 'D' then 'Domingos'
											when substr(dias_semana,3,1) = 'D' then 'Domingos'
											when substr(dias_semana,4,1) = 'D' then 'Domingos'
											when substr(dias_semana,5,1) = 'D' then 'Domingos'
											when substr(dias_semana,6,1) = 'D' then 'Domingos'
											when substr(dias_semana,7,1) = 'D' then 'Domingos'
										end domingo
										from hit_producto_cuadros c, 
											  hit_producto_cuadros_salidas sal,
											  hit_fechas fec,
											  hit_producto_cuadros_calendarios_ciudades calciu
										where 
											c.CLAVE = sal.CLAVE_CUADRO
											and sal.FECHA = fec.FECHA
											and sal.CLAVE_CUADRO = calciu.CLAVE_CUADRO
											and sal.FECHA between calciu.FECHA_DESDE and calciu.FECHA_HASTA
											and calciu.CIUDAD = '".$ciudad."'
											AND fec.DIA in (substr(calciu.DIAS_SEMANA,1,1), substr(calciu.DIAS_SEMANA,2,1), substr(calciu.DIAS_SEMANA,3,1), substr(calciu.DIAS_SEMANA,4,1),
																 substr(calciu.DIAS_SEMANA,5,1), substr(calciu.DIAS_SEMANA,6,1), substr(calciu.DIAS_SEMANA,7,1))
											and (sal.FECHA between '".date("Y-m-d",strtotime($fecha_desde_1))."' and '".date("Y-m-d",strtotime($fecha_hasta_1))."' 
												  or sal.FECHA between '".date("Y-m-d",strtotime($fecha_desde_2))."' and '".date("Y-m-d",strtotime($fecha_hasta_2))."' 
												  or sal.FECHA between '".date("Y-m-d",strtotime($fecha_desde_3))."' and '".date("Y-m-d",strtotime($fecha_hasta_3))."')
											and sal.FECHA > DATE_ADD(curdate(),INTERVAL 2 DAY)
										   and c.clave = '".$cuadro."') dias");

		$odatos_dias_semana = $datos_dias_semana->fetch_assoc();
		$cuenta_dias = 0;
		$d1 = $odatos_dias_semana['lunes'];
		if($d1 != ''){$cuenta_dias++;}
		$d2 = $odatos_dias_semana['martes'];
		if($d2 != ''){$cuenta_dias++;}
		$d3 = $odatos_dias_semana['miercoles'];
		if($d3 != ''){$cuenta_dias++;}
		$d4 = $odatos_dias_semana['jueves'];
		if($d4 != ''){$cuenta_dias++;}
		$d5 = $odatos_dias_semana['viernes'];
		if($d5 != ''){$cuenta_dias++;}
		$d6 = $odatos_dias_semana['sabado'];
		if($d6 != ''){$cuenta_dias++;}
		$d7 = $odatos_dias_semana['domingo'];
		if($d7 != ''){$cuenta_dias++;}

		$texto_salidas = '';
		$coma = ', ';
		$i2 = 0;
		if($cuenta_dias == 7){
			$texto_salidas = 'Diarios';
		}elseif($cuenta_dias > 4){
			$texto_salidas = 'Diarios excepto ';
			for ($i = 1; $i <= 7; $i++) {	
				if(${"d".$i} == ''){
					$i2++;
					if($i == 1){$dia_excepcion = 'Lunes';}
					if($i == 2){$dia_excepcion = 'Martes';}
					if($i == 3){$dia_excepcion = 'Miércoles';}
					if($i == 4){$dia_excepcion = 'Jueves';}
					if($i == 5){$dia_excepcion = 'Viernes';}
					if($i == 6){$dia_excepcion = 'Sábados';}
					if($i == 7){$dia_excepcion = 'Domingos';}
					if($cuenta_dias == 1){
						$coma = '';
					}elseif(7-$cuenta_dias - 1 == $i2){
						$coma = ' y ';
					}elseif(7-$cuenta_dias == $i2){
						$coma = '';
					}else{
						$coma = ', ';
					}

					$texto_salidas .= $dia_excepcion.$coma;
				}
			}
		}else{
			for ($i = 1; $i <= 7; $i++) {	
				if(${"d".$i} != ''){
					$i2++;
					if($cuenta_dias == 1){
						$coma = '';
					}elseif($cuenta_dias - 1 == $i2){
						$coma = ' y ';
					}elseif($cuenta_dias == $i2){
						$coma = '';
					}else{
						$coma = ', ';
					}

					$texto_salidas .= ${"d".$i}.$coma;
				}
			}
		}

		//echo($d1."-".$d2."-".$d3."-".$d4."-".$d5."-".$d6."-".$d7."-".$cuenta_dias."<br>".$texto_salidas);
		//echo($texto_salidas);

		//DATOS VUELOS
		$svuelos = $this->Cargar_vuelos($id,$ciudad,$fecha_busqueda);

		//echo($svuelos[0]['clase_1']);
		$clase = @$svuelos[0]['clase_1'];

		if($texto_pie == '' && $formato != 3){
			$texto_pie = "Oferta válida a partir del ".$fecha_lanzamiento.". Precio final por persona correspondiente a determinadas salidas de ".$salidas_texto_pie.". Los precios incluyen: Avión ida y vuelta (clase ".$clase.") + Estancias en el hotel en régimen elegido y/o indicado en habitación doble + Tasas de aeropuerto +  Seguro básico. Consultar  condiciones de niños, suplementos aéreos y condiciones generales en  www.hitravel.es Plazas Limitadas. CICMA 2988 COD: CAN15";
		}elseif($texto_pie == '' && $formato == 3){
			$texto_pie = "Oferta válida a partir del ".$fecha_lanzamiento.". Precio final por persona correspondiente a determinadas salidas de ".$salidas_texto_pie.". Los precios incluyen: Avión ida y vuelta (clase ".$clase.") + Tasas de aeropuerto. Consultar  condiciones de niños, suplementos aéreos y condiciones generales en  www.hitravel.es Plazas Limitadas. CICMA 2988 COD: CAN15";
		}

		//echo($texto_pie);

		//DATOS HOTELES
		$shoteles = $this->Cargar_hoteles($id);


		if($formato == 0){
			$respuesta = "pendiente";
		}


		//ECHO($formato);

		//COLORES

		$datos_teletipo_colores =$conexion->query("SELECT 
											c.COLOR_BORDE_LOGO,
											c.COLOR_FONDO_LOGO,
											c.COLOR_CABECERA_FONDO_LOGO_IZQUIERDA,
											c.COLOR_CABECERA_FONDO_DESTINO,
											c.COLOR_CABECERA_FONDO_LOGO_DERECHA,
											c.COLOR_CABECERA_FUENTE_DESTINO,
											c.COLOR_CABECERA_IMAGEN_FONDO,
											c.COLOR_TABLA_FONDO,
											c.COLOR_CABECERA_CONTENIDO_FONDO,
											c.COLOR_TITULO,
											c.COLOR_CIUDAD_SALIDA,
											c.COLOR_TEXTO_VUELOS,
											c.COLOR_BLOQUE_FONDO_CABECERA,
											c.COLOR_BLOQUE_FONDO_IMAGEN,
											c.COLOR_BLOQUE_FONDO_PRECIOS,
											c.COLOR_CABECERA_PRECIOS_NOMBRE_HOTEL,
											c.COLOR_CABECERA_PRECIOS_CATEGORIA_HOTEL,
											c.COLOR_CABECERA_PRECIOS_REGIMEN_HOTEL,
											c.COLOR_CABECERA_PRECIOS_PRECIO1_HOTEL,
											c.COLOR_CABECERA_PRECIOS_PRECIO2_HOTEL,
											c.COLOR_CABECERA_PRECIOS_PRECIO3_HOTEL,
											c.COLOR_PRECIOS_NOMBRE_HOTEL,
											c.COLOR_PRECIOS_CATEGORIA_HOTEL,
											c.COLOR_PRECIOS_LOCALIDAD_HOTEL,
											c.COLOR_PRECIOS_REGIMEN_HOTEL_TABLA,
											c.COLOR_PRECIOS_REGIMEN_HOTEL_BLOQUES,
											c.COLOR_PRECIOS_PRECIO1_HOTEL,
											c.COLOR_PRECIOS_PRECIO2_HOTEL,
											c.COLOR_PRECIOS_PRECIO3_HOTEL,
											c.COLOR_PIE_FONDO,
											c.COLOR_PIE_BORDE,
											c.COLOR_PIE_TEXTO
											FROM hit_teletipos_colores c
											WHERE c.ID = '".$teletipo_colores."'");
		$odatos_teletipo_colores = $datos_teletipo_colores->fetch_assoc();

		//CABECERA
		$color_borde_logo = $odatos_teletipo_colores['COLOR_BORDE_LOGO'];
		$color_fondo_logo = $odatos_teletipo_colores['COLOR_FONDO_LOGO'];
		$color_cabecera_fondo_logo_izquierda = $odatos_teletipo_colores['COLOR_CABECERA_FONDO_LOGO_IZQUIERDA'];
		$color_cabecera_fondo_destino = $odatos_teletipo_colores['COLOR_CABECERA_FONDO_DESTINO'];
		$color_cabecera_fondo_logo_derecha = $odatos_teletipo_colores['COLOR_CABECERA_FONDO_LOGO_DERECHA'];
		$color_cabecera_fuente_destino = $odatos_teletipo_colores['COLOR_CABECERA_FUENTE_DESTINO'];

		//IMAGEN PRINCIPAL
		$color_cabecera_imagen_fondo = $odatos_teletipo_colores['COLOR_CABECERA_IMAGEN_FONDO'];

		//FONDO DE CONTENIDO TRAS IMAGEN PRINCIPAL Y CONTENIDO FORMATO TIPO TABLA
		$color_tabla_fondo = $odatos_teletipo_colores['COLOR_TABLA_FONDO']; //formato tabla
		$color_cabecera_contenido_fondo = $odatos_teletipo_colores['COLOR_CABECERA_CONTENIDO_FONDO']; //formato bloques

		//TITULO Y CIUDAD DE SALIDA
		$color_titulo = $odatos_teletipo_colores['COLOR_TITULO'];
		$color_ciudad_salida = $odatos_teletipo_colores['COLOR_CIUDAD_SALIDA'];

		//VUELOS
		$color_texto_vuelos = $odatos_teletipo_colores['COLOR_TEXTO_VUELOS'];

		//FONDO BLOQUES
		$color_bloque_fondo_cabecera = $odatos_teletipo_colores['COLOR_BLOQUE_FONDO_CABECERA']; //formato bloques
		$color_bloque_fondo_imagen = $odatos_teletipo_colores['COLOR_BLOQUE_FONDO_IMAGEN']; //formato bloques
		$color_bloque_fondo_precios = $odatos_teletipo_colores['COLOR_BLOQUE_FONDO_PRECIOS']; //formato bloques

		//HOTELES CABECERAS Y PRECIOS
		$color_cabecera_precios_nombre_hotel = $odatos_teletipo_colores['COLOR_CABECERA_PRECIOS_NOMBRE_HOTEL'];
		$color_cabecera_precios_categoria_hotel = $odatos_teletipo_colores['COLOR_CABECERA_PRECIOS_CATEGORIA_HOTEL'];
		$color_cabecera_precios_regimen_hotel = $odatos_teletipo_colores['COLOR_CABECERA_PRECIOS_REGIMEN_HOTEL'];
		$color_cabecera_precios_precio1_hotel = $odatos_teletipo_colores['COLOR_CABECERA_PRECIOS_PRECIO1_HOTEL'];
		$color_cabecera_precios_precio2_hotel = $odatos_teletipo_colores['COLOR_CABECERA_PRECIOS_PRECIO2_HOTEL'];
		$color_cabecera_precios_precio3_hotel = $odatos_teletipo_colores['COLOR_CABECERA_PRECIOS_PRECIO3_HOTEL'];

		$color_precios_nombre_hotel = $odatos_teletipo_colores['COLOR_PRECIOS_NOMBRE_HOTEL'];
		$color_precios_categoria_hotel = $odatos_teletipo_colores['COLOR_PRECIOS_CATEGORIA_HOTEL'];
		$color_precios_localidad_hotel = $odatos_teletipo_colores['COLOR_PRECIOS_LOCALIDAD_HOTEL'];
		$color_precios_regimen_hotel_tabla = $odatos_teletipo_colores['COLOR_PRECIOS_REGIMEN_HOTEL_TABLA'];
		$color_precios_regimen_hotel_bloques = $odatos_teletipo_colores['COLOR_PRECIOS_REGIMEN_HOTEL_BLOQUES'];
		$color_precios_precio1_hotel = $odatos_teletipo_colores['COLOR_PRECIOS_PRECIO1_HOTEL'];
		$color_precios_precio2_hotel = $odatos_teletipo_colores['COLOR_PRECIOS_PRECIO2_HOTEL'];
		$color_precios_precio3_hotel = $odatos_teletipo_colores['COLOR_PRECIOS_PRECIO3_HOTEL'];

		//PIE DE OFERTA
		$color_pie_fondo = $odatos_teletipo_colores['COLOR_PIE_FONDO'];
		$color_pie_borde = $odatos_teletipo_colores['COLOR_PIE_BORDE'];
		$color_pie_texto = $odatos_teletipo_colores['COLOR_PIE_TEXTO'];



		
		/*
		//CABECERA
		$color_borde_logo = $odatos_teletipo_colores['COLOR_BORDE_LOGO'];
		$color_fondo_logo = '#b9d305';
		$color_cabecera_fondo_logo_izquierda = '#0E0090';
		$color_cabecera_fondo_destino = '#0E0090';
		$color_cabecera_fondo_logo_deracha = '#0E0090';
		$color_cabecera_fuente_destino = '#b9d305';

		//IMAGEN PRINCIPAL
		$color_cabecera_imagen_fondo = '#b9d305';

		//FONDO DE CONTENIDO TRAS IMAGEN PRINCIPAL Y CONTENIDO FORMATO TIPO TABLA
		$color_tabla_fondo = '#1100A7'; //formato tabla
		$color_cabecera_contenido_fondo = '#1100A7'; //formato bloques

		//TITULO Y CIUDAD DE SALIDA
		$color_titulo = 'white';
		$color_ciudad_salida = '#b9d305';

		//VUELOS
		$color_texto_vuelos = '#7ac6f3';

		//FONDO BLOQUES
		$color_bloque_fondo_cabecera = '#1100A7'; //formato bloques
		$color_bloque_fondo_imagen = '#1100A7'; //formato bloques
		$color_bloque_fondo_precios = '#1100A7'; //formato bloques

		//HOTELES CABECERAS Y PRECIOS
		$color_cabecera_precios_nombre_hotel = '#b9d305';
		$color_cabecera_precios_categoria_hotel = '#b9d305';
		$color_cabecera_precios_regimen_hotel = '#b9d305';
		$color_cabecera_precios_precio1_hotel = '#b9d305';
		$color_cabecera_precios_precio2_hotel = '#b9d305';
		$color_cabecera_precios_precio3_hotel = '#b9d305';

		$color_precios_nombre_hotel = 'white';
		$color_precios_categoria_hotel = 'white';
		$color_precios_localidad_hotel = '#7ac6f3';
		$color_precios_regimen_hotel_tabla = 'white';
		$color_precios_regimen_hotel_bloques = '#b9d305';
		$color_precios_precio1_hotel = '#b9d305';
		$color_precios_precio2_hotel = '#b9d305';
		$color_precios_precio3_hotel = '#b9d305';

		//PIE DE OFERTA
		$color_pie_fondo = 'white';
		$color_pie_borde = '#b9d305';
		$color_pie_texto = '#6f7073'; 
		*/



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////            TABLA RESUMEN                  /////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



if($formato == 0){
	$respuesta = "<HTML>
	  <link rel='StyleSheet' href='css/comun.css'>



<body>
<!--Color Hitravel #b9d305    color azul de fondo cabecera original: #015ec8 color azul de fondo bloques original: #1065af
	color textos y precios original: #ffb302-->

<p class='MsoNormal' style='text-align:center' align='center'>
	<span style='font-size:7.0pt'>

	</span>
</p>

<table style='width:95%' border='0' cellpadding='0' cellspacing='0' width='100%'>
<tbody>
	<tr>
		<td style='padding:0cm 0cm 0cm 0cm'>
			<div align='center'>
				<table style='width:18.5cm' border='0' cellpadding='0' cellspacing='0'>
					<tbody>

						
						<!--LOGO HI TRAVEL-->

						<tr>
							<td style='background:white;padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_borde_logo.";' align='center'>
								<p class='MsoNormal'>
									<a href='http://www.hitravel.es/' title='HI TRAVEL' target='_blank'>
										<span style='text-decoration:none'  align='center'>
											<img src='imagenes/Logo_2.jpg' alt='HI TRAVEL' border='0' width='652' height='90'>
										</span>
									</a>
									<u></u>
									<u></u>
								</p>
							</td>
						</tr>

						<!--TITULOS PRIMERA OFERTA-->

						<tr>
							<td>
							<table width='100%' border='0' cellpadding='0' cellspacing='0' style='background:#0E0090;' border='0'>
								<tr>
									<td width='15%'align='center' style='background:".$color_cabecera_fondo_logo_izquierda.";padding:0.0pt 0pt 0.0pt 0pt'>";

					if($logo_dcto_2 != null){										
						$respuesta .= "<img border='0' src='imagenes/logos/".$logo_dcto_2."' width='95' height='105'>";
					}											

					$respuesta .= "</td>

							<td width='70%'style='background:".$color_cabecera_fondo_destino.";padding:10.0pt 0pt 15.0pt 0pt'>
								<p style='margin-right:0cm;margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center' align='center'>
									<b>
										<span style='font-size:40.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_fuente_destino."'>".$destino."</span>
									</b>
								</p>
							</td>

							<td width='15%' align='center' style='background:".$color_cabecera_fondo_logo_derecha.";padding:0pt 0pt 0pt 0pt'>";

					if($logo_dcto_1 != null){											
						$respuesta .= "<img border='0' src='imagenes/logos/".$logo_dcto_1."' width='95' height='105'>";
					}											

					$respuesta .= "</td>
							</tr>
						</table>
						</td>
					</tr>

					<!--FOTO DESTINO DE LA OFERTA-->
					<tr>
						<td style='background:".$color_cabecera_imagen_fondo.";padding:0cm 0cm 0cm 0cm' align='center'>
							<!--<p>-->
								<a href='http://www.hitravel.es' target='_blank'>
									<!--<span style='text-decoration:none'>-->
										<img src='imagenes/logos/".$cabecera_imagen."' alt='Canarias' border='0' width='875' height='363'>
									<!--</span>-->
								</a>

							<!--</p>-->
						</td>
					</tr>

					<!--TEXTOS Y ENLACES DESTINO PRIMERA OFERTA-->

					<tr style='height:123.0pt'>
						<td style='background:".$color_tabla_fondo.";padding:15.0pt 10pt 1.0pt 22.5pt; height:123.0pt'>
							<table style='width:95.0%' border='0' cellpadding='0' cellspacing='0' width='95%'>
								<tbody>
									<tr>
										<td style='width:60.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='60%'>
											<p style='margin:0cm;margin-bottom:.0001pt'>
												<b>
													<span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_titulo."'>".$teletipo_titulo."
														<u></u>
														<u></u>
													</span>
												</b>
											</p>
										</td>

										<td style='width:40.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='40%'>
											<p style='margin:0cm;margin-bottom:.0001pt;text-align:right' align='right'>
												<b>
													<span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>
														<a>
															<span style='color:".$color_ciudad_salida.";text-decoration:none'>Desde ".@$nombre_ciudad."</span>
														</a>
														<u></u>
														<u></u>
													</span>
												</b>
											</p>
										</td>
									</tr>
									<tr>
										<td style='padding:0cm 0cm 0cm 0cm' valign='top' colspan='2'>
					
											<p style='margin:0cm;margin-bottom:.0001pt'>
												<b>

													<TABLE width='80%' align='center' style='font-weight:bold;font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_texto_vuelos."'>
														<tbody>
															<tr>
																<td align='center' colspan='1'>Vuelos:</td>
																<td align='left' colspan='8' style='font-weight:bold;font-size:12.0pt;'>(".$texto_salidas.")</td>
															</tr>";


			for ($i = 0; $i < count($svuelos); $i++) {	

				$respuesta .= " 									<tr>
																<td align='center'><img class='CToWUd' src='imagenes/transportes/".$svuelos[$i]['cia']."_logo.jpg' alt='Canadá' border='0' width='70' height='20'></td>
																	
																<td align='center'>".$svuelos[$i]['origen']."</td>
																<td align='center'>-</td>
																<td align='center'>".$svuelos[$i]['destino']."</td>
																<td align='center'>".$svuelos[$i]['cia']."</td>
																<td align='center'>".$svuelos[$i]['vuelo']."</td>
																<td align='center'>".$svuelos[$i]['hora_salida']."</td>
																<td align='center'>-</td>
																<td align='center'>".$svuelos[$i]['hora_llegada']."</td>
															</tr>";

			}

			//////////////////////////////////////////////////////////////////////
			//////////////////////////   HOTELES   ///////////////////////////////
			//////////////////////////////////////////////////////////////////////

			//Ajustamos el tamaño de la fuente segun el numero de columnas
			if($cabecera_3 != ''){
				$tamano_fuente_hoteles = 16;
			}elseif($cabecera_2 != ''){
				$tamano_fuente_hoteles = 17;
			}else{
				$tamano_fuente_hoteles = 17;
			}

			$respuesta .= "																	
														</tbody>
													</TABLE>

													<TABLE border='0' width='100%' align='center' style='font-weight:bold;font-size:12.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3'>
														<tbody>
															<tr height='30'>
																<td align='left' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_nombre_hotel."'>Hotel</td>
																<td align='center' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_categoria_hotel."'>Cat.</td>
																<td align='center' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_regimen_hotel."'>Reg.</td>";





			if($cabecera_1 != ''){																			
			$respuesta .= "											<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_precio1_hotel."'>".$cabecera_1."
																				</span>
																			</b>
																		</p>
																	</td>";
						}	
						if($cabecera_2 != ''){																		
							$respuesta .= "								<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_precio2_hotel."'>".$cabecera_2."
																				</span>
																			</b>
																		</p>
																	</td>";
						}	
						if($cabecera_3 != ''){																		
							$respuesta .= "								<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_precio3_hotel."'>".$cabecera_3."
																				</span>
																			</b>
																		</p>
																	</td>";
						}	

		$respuesta .= "												</tr>";



			$control_lineas_hotel = 0;
			for ($i = 0; $i < count($shoteles); $i++) {		


				if($shoteles[$i]['ciudad'] == $ciudad and $control_lineas_hotel < 8){	

		$respuesta .= " 												<tr height='30'>
																			
																	<td align='left' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_nombre_hotel."'>".$shoteles[$i]['nombre']."</td>	
																	<td align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_categoria_hotel."'>".$shoteles[$i]['categoria_2']."</td>
																	<td align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_regimen_hotel_tabla."'>".$shoteles[$i]['regimen']."</td>";




	if($shoteles[$i]['precio'] != 0 && $cabecera_1 != ''){																		
														$respuesta .= "	<td align='center' style='font-size:".$tamano_fuente_hoteles.".0pt;font-																					family:&quot;Arial&quot;,&quot;sans-																					serif&quot;;color:#b9d305'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_precio1_hotel."'>".$shoteles[$i]['precio']."€
																				</span>
																			</b>
																		</p>
																	</td>";
						}	
						if($shoteles[$i]['precio_2'] != 0 && $cabecera_2 != ''){																		
							$respuesta .= "								<td  align='center' style='padding:0cm 0cm 0cm 0cm;'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_precio2_hotel."'>".$shoteles[$i]['precio_2']."€
																				</span>
																			</b>
																		</p>
																	</td>";
						}
						if($shoteles[$i]['precio_3'] != 0 && $cabecera_3 != ''){																		
							$respuesta .= "								<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_precio3_hotel."'>".$shoteles[$i]['precio_3']."€
																				</span>
																			</b>
																		</p>
																	</td>";
						}



							$respuesta .= "							</tr>";
					$control_lineas_hotel++;
				}
			}

			$respuesta .= "											<tr>

																	<td colspan='5' style='padding:0.5cm 0cm 0cm 0cm; height:0pt' align='center' valign='center'>
																			<a href='http://54.229.179.250/pdf_teletipo_resumen_formato_1.php?id=".$id."&ciudad=".$ciudad."'>
																				<span style='text-decoration:none'>
																					<img width='300' height='30' src='imagenes/Logo_Descargar_Pdf_Resumen_Teletipo_Formato_1_Hitravel.jpg' border='0'>
																				</span>
																			</a>

																	</td>
																</tr>

															</tbody>
														</TABLE>

													</b>
												</p>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>";



			$respuesta .= "						

							<!--LOGO HI TRAVEL-->

							<tr>
								<td style='background:".$color_pie_fondo.";padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_pie_borde.";align:center;'>
									<p style='width:95%;font-weight:normal;text-align:justify;color:".$color_pie_texto.";font-size:0.8em;margin:11.25pt 11.25pt 11.25pt 11.25pt;'>
										".$texto_pie."
										<u></u>
										<u></u>
									</p>
								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</td>
		</tr>
	</tbody>
</table>
</BODY>
</HTML>";			
}






		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		///////////////////////////////            OFERTA POR BLOQUES             /////////////////////////////////////////////

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




		if($formato == 1){
			$respuesta = "<HTML>
					<HEAD>
					  <meta charset='utf-8'>
					  <TITLE> Menú </TITLE>
					  <META NAME='Author' CONTENT='Jose de Frias'>
					  <META NAME='Keywords' CONTENT=''>
					  <META NAME='Description' CONTENT=''>
					  <link rel='StyleSheet' href='css/menu.css'>
					  <link rel='StyleSheet' href='css/calendario/calendar-blue2.css'>
					</HEAD>

					<body>
					<!--Color Hitravel #b9d305    color azul de fondo cabecera original: #015ec8 color azul de fondo bloques original: #1065af
						color textos y precios original: #ffb302-->

					<p class='MsoNormal' style='text-align:center' align='center'>
						<span style='font-size:7.0pt'>

						</span>
					</p>

					<table style='width:95%' border='0' cellpadding='0' cellspacing='0' width='100%'>
						<tbody>
							<tr>
								<td style='padding:0cm 0cm 0cm 0cm'>
									<div align='center'>
										<table style='width:391.2pt' border='0' cellpadding='0' cellspacing='0' width='652'>
											<tbody>

												
												<!--LOGO HI TRAVEL-->

												<tr>
													<td style='background:white;padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_borde_logo.";'>
														<p class='MsoNormal'>
															<a href='http://www.hitravel.es/' title='HI TRAVEL' target='_blank'>
																<span style='text-decoration:none'>
																	<img src='imagenes/Logo_2.jpg' alt='HI TRAVEL' border='0' width='652' height='90'>
																</span>
															</a>
															<u></u>
															<u></u>
														</p>
													</td>
												</tr>

												<!--TITULOS PRIMERA OFERTA-->

												<tr>
													<td style='background:".$color_cabecera_fondo_destino.";padding:10.0pt 22.5pt 15.0pt 22.5pt'>
														<!--<p style='margin:0cm;margin-bottom:.0001pt;text-align:center' align='center'>
															<b>
																<span style='font-size:24.0pt;font-family:Arial,sans-serif;color:white'>Ofertas
																	<u></u>
																	<u></u>
																</span>
															</b>
														</p>-->
														<p style='margin-right:0cm;margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center' align='center'>
												
															<b>
																<span style='font-size:40.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_fuente_destino."'>".$destino."</span>
															</b>
														</p>
													</td>
												</tr>

												<!--FOTO DESTINO DE LA OFERTA-->
												<tr>
													<td style='background:".$color_cabecera_imagen_fondo.";padding:0cm 0cm 0cm 0cm' >
														<!--<p>-->
															<a href='http://www.hitravel.es' target='_blank'>
																<!--<span style='text-decoration:none'>-->
																	<img src='imagenes/logos/".$cabecera_imagen."' alt='Canarias' border='0' width='660' height='274'>
																<!--</span>-->
															</a>

														<!--</p>-->
													</td>
												</tr>

												<!--TEXTOS Y ENLACES DESTINO PRIMERA OFERTA-->

												<tr style='height:123.0pt'>
													<td style='background:".$color_cabecera_contenido_fondo.";padding:15.0pt 10pt 0.0pt 22.5pt; height:123.0pt'>
														<table style='width:95.0%' border='0' cellpadding='0' cellspacing='0' width='95%'>
															<tbody>
																<tr>
																	<td style='width:78.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='78%'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_titulo."'>".$teletipo_titulo."
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>
																					<!--<a href='http://www.panavision-tours.es/circuitos/combinados-canada-usa/canadavision-2015/' target='_blank'>-->
																					<a>
																						<span style='color:".$color_ciudad_salida.";text-decoration:none'>Desde ".@$nombre_ciudad."</span>
																					</a>
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:9.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>&nbsp;
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>
																	</td>

																	<td style='width:20.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='20%'>
																		<p style='margin:0cm;margin-bottom:.0001pt;text-align:right' align='right'>
																			<b><span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_titulo."'>Desde
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>
																		<p style='margin:0cm;margin-bottom:.0001pt;text-align:right' align='right'>
																			<b>
																				<span style='font-size:27.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_ciudad_salida."'>".$precio_desde."€
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>
																	</td>
																</tr>
																<tr>
																	<td style='padding:0cm 0cm 0cm 0cm' valign='top' colspan='2'>
																		<!--<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3'>&nbsp;
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>-->												
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<!--<br>Confirmación inmediata-->
																				<TABLE width='80%' align='center' style='font-weight:bold;font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_texto_vuelos."'>
																					<tbody>
																						<tr>
																							<td align='center' colspan='1'>Vuelos:</td>
																							<td align='left' colspan='8' style='font-weight:bold;font-size:12.0pt;'>(".$texto_salidas.")</td>
																						</tr>";


			for ($i = 0; $i < count($svuelos); $i++) {																			
				$respuesta .= " 														<tr>
																							<td align='center'><img class='CToWUd' src='imagenes/transportes/".$svuelos[$i]['cia']."_logo.jpg' alt='Canadá' border='0' width='70' height='20'></td>
																								
																							<td align='center'>".$svuelos[$i]['origen']."</td>
																							<td align='center'>-</td>
																							<td align='center'>".$svuelos[$i]['destino']."</td>
																							<td align='center'>".$svuelos[$i]['cia']."</td>
																							<td align='center'>".$svuelos[$i]['vuelo']."</td>
																							<td align='center'>".$svuelos[$i]['hora_salida']."</td>
																							<td align='center'>-</td>
																							<td align='center'>".$svuelos[$i]['hora_llegada']."</td>
																						</tr>";
			}

			$respuesta .= "																<tr>
																							<td colspan='10' style='padding:0.7cm 0cm 0cm 0cm; height:0pt' align='center' valign='center'>
																								<a href='http://54.229.179.250/pdf_teletipo_resumen_formato_1.php?id=".$id."&ciudad=".$ciudad."'>
																									<span style='text-decoration:none'>
																											<img width='300' height='30' src='imagenes/Logo_Descargar_Pdf_Resumen_Teletipo_Formato_1_Hitravel.jpg' border='0'>
																									</span>
																								</a>
																							</td>
																						</tr>";


			$respuesta .= "															</tbody>
																				</TABLE>
																			</b>
																		</p>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>

												<tr>
													<td style='background:white;padding:11.25pt 11.25pt 0pt 11.25pt'>";






			for ($i = 0; $i < count($shoteles); $i++) {

				if($shoteles[$i]['ciudad'] == $ciudad){

						$respuesta .= "					<!--LINEA DE HOTEL-->

															<div align='center'>
																<table style='width:100.0%' border='0' cellpadding='0' cellspacing='0' width='100%'>
																	<tbody>

																		<tr>

																			<td colspan='2' style='width:100%;background:".$color_bloque_fondo_cabecera.";padding:5.25pt 11.25pt 5.25pt 11.25pt' valign='top'>
																				<div align='center'>
																					<table style='width:100.0%' border='0' cellpadding='0' cellspacing='0'>
																						<tbody>
																							<tr>
																								<td colspan='1' style='padding:0cm 0cm 0cm 0cm' align='left'>

																									<p style='margin-right:0cm;margin-bottom:3.75pt;margin-left:0cm'>
																											<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#1100A7'>
																												<a href='http://www.hitravel.es/Ficha_hotel.php?id=".$shoteles[$i]['id_hotel']."' target='_blank'>
																													<span style='color:".$color_precios_nombre_hotel.";text-decoration:none;font-size:16.0pt;'>".$shoteles[$i]['nombre']."</span>
																												</a>
																											</span>

																											<span style='font-size:14.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#1100A7'>	
																												<a>
																													<span style='color:".$color_precios_categoria_hotel.";text-decoration:none;font-size:12.0pt;'>&nbsp;&nbsp;&nbsp;".$shoteles[$i]['categoria']."</span>
																												</a>											
																											</span>
																									</p>
																								</td>
																								<td colspan='1' style='padding:0cm 0cm 0cm 0cm' align='right'>
																									<p>
																										<span style='font-size:10.5pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_localidad_hotel."'>".$shoteles[$i]['localidad']."

																										</span>
																									</p>
																								</td>															



																							</tr>
																						</tbody>
																					</table>

																				</div>
																			</td>

																		</tr>


																		<tr>

																			<td style='width:50%;background:".$color_bloque_fondo_imagen.";padding:0pt 11.25pt 2.25pt 11.25pt' valign='top'>
																				<div align='center'>
																					<table style='width:88.0%' border='0' cellpadding='0' cellspacing='0' width='88%'>
																						<tbody>
																							<tr style='height:86.4pt'>
																								<td colspan='2' style='padding:0cm 0cm 0cm 0cm;height:70.4pt'>
																									<p class='MsoNormal'>
																										<a href='http://www.hitravel.es/Ficha_hotel.php?id=".$shoteles[$i]['id_hotel']."' target='_blank'>
																											<span style='text-decoration:none'>
																												<img class='CToWUd' src='imagenes/alojamientos/".$shoteles[$i]['id_hotel']."_P_1.jpg' border='0' width='269' height='165'>
																											</span>
																										</a>
																										<u></u>
																										<u></u>
																									</p>
																								</td>
																							</tr>

																						</tbody>
																					</table>

																				</div>
																			</td>



																			<td style='width:50%;background:".$color_bloque_fondo_precios.";padding:0pt 11.25pt 11.25pt 11.25pt' valign='top'>
																				<div align='center'>

																					<table border='0' cellpadding='0' cellspacing='0' align='center' width='100%' style='padding:0cm 0cm 0cm 0cm;'>
																						<tbody>

																							<tr style='height:10.4pt'>";

							if($shoteles[$i]['logo1'] != ''){
								$respuesta .= "													<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;'>
																										<!--<a href='http://www.hitravel.es' target='_blank'>-->
																											<span style='text-decoration:none'>

																												<img class='CToWUd' src='imagenes/logos/".$shoteles[$i]['logo1']."' border='0' width='60' height='70'>
																											</span>
																										<!--</a>-->
																									</p>
																								</td>";

							}
							if($shoteles[$i]['logo2'] != ''){																	
								$respuesta .= "													<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;'>
																											<span style='text-decoration:none'>
																												<img class='CToWUd' src='imagenes/logos/".$shoteles[$i]['logo2']."' border='0' width='60' height='70'>
																											</span>
																									</p>
																								</td>";
							}																	
							if($shoteles[$i]['logo3'] != ''){
								$respuesta .= "													<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;'>
																											<span style='text-decoration:none'>
																												<img class='CToWUd' src='imagenes/logos/".$shoteles[$i]['logo3']."' border='0' width='60' height='70'>
																											</span>
																									</p>
																								</td>";
							}																	

							$respuesta .= "													</tr>
																						</tbody>
																					</table>


																					<table border='0' cellpadding='0' cellspacing='0' align='center' width='100%' style='padding:0.1cm 0cm 0cm 0cm;'>
																						<tbody>

																							<tr style='height:25.4pt'>
																								<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;'>
																										<b>
																											<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'></span>
																										</b>
																									</p>
																								</td>";

						if($shoteles[$i]['precio'] != 0 && $cabecera_1 != ''){																			
							$respuesta .= "														<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_precio1_hotel."'>".$cabecera_1."
																											</span>
																										</b>
																									</p>
																								</td>";
						}	
						if($shoteles[$i]['precio_2'] != 0 && $cabecera_2 != ''){																		
							$respuesta .= "														<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_precio2_hotel."'>".$cabecera_2."
																											</span>
																										</b>
																									</p>
																								</td>";
						}	
						if($shoteles[$i]['precio_3'] != 0 && $cabecera_3 != ''){																		
							$respuesta .= "														<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_precio3_hotel."'>".$cabecera_3."
																											</span>
																										</b>
																									</p>
																								</td>";
						}	

						$respuesta .= "														</tr>																	
																							<tr style='height:25.4pt'>
																								<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;'>
																										<b>
																											<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_regimen_hotel_bloques."'>".$shoteles[$i]['regimen']."</span>
																										</b>
																									</p>
																								</td>";


						if($shoteles[$i]['precio'] != 0 && $cabecera_1 != ''){																		
							$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																										<p style='margin:0cm;margin-bottom:.0001pt'>
																											<b>
																												<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_precio1_hotel."'>".$shoteles[$i]['precio']."€
																												</span>
																											</b>
																										</p>
																									</td>";
						}	
						if($shoteles[$i]['precio_2'] != 0 && $cabecera_2 != ''){																		
							$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																										<p style='margin:0cm;margin-bottom:.0001pt'>
																											<b>
																												<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_precio2_hotel."'>".$shoteles[$i]['precio_2']."€
																												</span>
																											</b>
																										</p>
																									</td>";
						}
						if($shoteles[$i]['precio_3'] != 0 && $cabecera_3 != ''){																		
							$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																										<p style='margin:0cm;margin-bottom:.0001pt'>
																											<b>
																												<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_precio3_hotel."'>".$shoteles[$i]['precio_3']."€
																												</span>
																											</b>
																										</p>
																									</td>";
						}


						$respuesta .= "														</tr>
																						</tbody>
																					</table>

																					<table border='0' cellpadding='0' cellspacing='0' align='center' width='100%' style='padding:0cm 0cm 0cm 0cm;' >
																						<tbody>																				
																							<tr style='height:30.0pt'>

																								<td colspan='3' style='padding:0cm 0cm 0cm 0cm;height:0pt' align='center' valign='bottom'>
																										<a href='http://www.hitravel.es/Buscar_viajes.php?origen=".$ciudad."&destino=".$destino_cuadro."&producto=".$producto_cuadro."&fecha=".$fecha_busqueda."&noches=".$noches_cuadro."&alojamiento=".$shoteles[$i]['id_hotel']."' target='_blank'>
																											<span style='text-decoration:none'>
																												<img width='200' height='25' src='imagenes/Logo_Informacion_y_Reservas_Hitravel.jpg' border='0'>
																											</span>
																										</a>
																								</td>
																							</tr>
																						</tbody>
																					</table>
																				</div>
																			</td>

																		</tr>
																	</tbody>
																</table>
															</div>

															<!--ESPACIO ENTRE LINEAS DE BLOQUES-->

															<p style='text-align:center;margin:0pt 11.25pt 11.25pt 11.25pt;'>
																<span style='font-size:0.05'>
																	<u></u>&nbsp;<u></u>
																</span>
															</p>";
				}

			}


			$respuesta .= "							</td>
												</tr>

												<!--LOGO HI TRAVEL-->

												<tr>
													<td style='background:".$color_pie_fondo.";padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_pie_borde.";align:center;'>
														<p style='width:95%;font-weight:normal;text-align:justify;color:".$color_pie_texto.";font-size:0.8em;margin:11.25pt 11.25pt 11.25pt 11.25pt;'>
															".$texto_pie."
															<u></u>
															<u></u>
														</p>
													</td>
												</tr>

											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					</BODY>
					</HTML>";			
		}






		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		///////////////////////////////            OFERTA SOLO UN HOTEL           /////////////////////////////////////////////

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




		if($formato == 2){

			$respuesta = "<HTML>
						  <link rel='StyleSheet' href='css/menu.css'>



					<body>
					<!--Color Hitravel #b9d305    color azul de fondo cabecera original: #015ec8 color azul de fondo bloques original: #1065af
						color textos y precios original: #ffb302-->

					<p class='MsoNormal' style='text-align:center' align='center'>
						<span style='font-size:7.0pt'>

						</span>
					</p>

					<table style='width:95.0%' border='0' cellpadding='0' cellspacing='0' width='100%'>
						<tbody>
							<tr>
								<td style=';padding:0cm 0cm 0cm 0cm'>
									<div align='center'>
										<table style='width:18.5cm' border='0' cellpadding='0' cellspacing='0'>
											<tbody>

												
												<!--LOGO HI TRAVEL-->

												<tr>
													<td style='background:white;padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_borde_logo.";' align='center'>
														<p class='MsoNormal'>
															<a href='http://www.hitravel.es/' title='HI TRAVEL' target='_blank'>
																<span style='text-decoration:none'  align='center'>
																	<img src='imagenes/Logo_2.jpg' alt='HI TRAVEL' border='0' width='652' height='90'>
																</span>
															</a>
															<u></u>
															<u></u>
														</p>
													</td>
												</tr>

												<!--TITULOS PRIMERA OFERTA-->

												<tr>
													<td>
													<table width='100%' border='0' cellpadding='0' cellspacing='0' style='background:#0E0090;' border='0'>
														<tr>
															<td width='15%'align='center' style='background:".$color_cabecera_fondo_logo_izquierda.";padding:0.0pt 0pt 0.0pt 0pt'>";

					if($logo_dcto_2 != null){										
						$respuesta .= "							<img border='0' src='imagenes/logos/".$logo_dcto_2."' width='95' height='105'>";
					}											

					$respuesta .= "							</td>

															<td width='70%'style='background:".$color_cabecera_fondo_destino.";padding:10.0pt 0pt 15.0pt 0pt'>
																<p style='margin-right:0cm;margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center' align='center'>
																	<b>
																		<span style='font-size:40.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_fuente_destino."'>".$destino."</span>
																	</b>
																</p>
															</td>

															<td width='15%' align='center' style='background:".$color_cabecera_fondo_logo_derecha.";padding:0pt 0pt 0pt 0pt'>";

					if($logo_dcto_1 != null){											
						$respuesta .= "							<img border='0' src='imagenes/logos/".$logo_dcto_1."' width='95' height='105'>";
					}											

					$respuesta .= "							</td>
														</tr>
													</table>
													</td>
												</tr>

												<!--FOTO DESTINO DE LA OFERTA-->
												<tr>
													<td style='background:".$color_cabecera_imagen_fondo.";padding:0cm 0cm 0cm 0cm' align='center'>
														<!--<p>-->
															<a href='http://www.hitravel.es' target='_blank'>";
	

					if($cabecera_imagen == ''){
						for ($i = 0; $i < count($shoteles); $i++) {		
							if($shoteles[$i]['ciudad'] == $ciudad and $shoteles[$i]['orden'] == 1){	
								$respuesta .= "									<img class='CToWUd' src='imagenes/alojamientos/".$shoteles[$i]['id_hotel']."_P_1.jpg' border='0' width='875' height='363'>";
							}
						}
					}else{									
						$respuesta .= "									<img src='imagenes/logos/".$cabecera_imagen."' alt='Canarias' border='0' width='875' height='363'>";
					}


					$respuesta .= "							</a>

														<!--</p>-->
													</td>
												</tr>

												<!--TEXTOS Y ENLACES DESTINO PRIMERA OFERTA-->

												<tr style='height:123.0pt'>
													<td style='background:".$color_tabla_fondo.";padding:15.0pt 10pt 1.0pt 22.5pt; height:123.0pt'>
														<table style='width:95.0%' border='0' cellpadding='0' cellspacing='0' width='95%'>
															<tbody>
																<tr>
																	<td style='width:60.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='60%'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_titulo."'>".$teletipo_titulo."
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>
																	</td>

																	<td style='width:40.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='40%'>
																		<p style='margin:0cm;margin-bottom:.0001pt;text-align:right' align='right'>
																			<b>
																				<span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>
																					<a>
																						<span style='color:".$color_ciudad_salida.";text-decoration:none'>Desde ".@$nombre_ciudad."</span>
																					</a>
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>
																	</td>
																</tr>
																<tr>
																	<td style='padding:0cm 0cm 0cm 0cm' valign='top' colspan='2'>
												
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>

																				<TABLE width='80%' align='center' style='font-weight:bold;font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_texto_vuelos."'>
																					<tbody>
																						<tr>
																							<td align='center' colspan='1'>Vuelos:</td>
																							<td align='left' colspan='8' style='font-weight:bold;font-size:12.0pt;'>(".$texto_salidas.")</td>
																						</tr>";


			for ($i = 0; $i < count($svuelos); $i++) {	

				$respuesta .= " 														<tr>
																							<td align='center'><img class='CToWUd' src='imagenes/transportes/".$svuelos[$i]['cia']."_logo.jpg' alt='Canadá' border='0' width='70' height='20'></td>
																								
																							<td align='center'>".$svuelos[$i]['origen']."</td>
																							<td align='center'>-</td>
																							<td align='center'>".$svuelos[$i]['destino']."</td>
																							<td align='center'>".$svuelos[$i]['cia']."</td>
																							<td align='center'>".$svuelos[$i]['vuelo']."</td>
																							<td align='center'>".$svuelos[$i]['hora_salida']."</td>
																							<td align='center'>-</td>
																							<td align='center'>".$svuelos[$i]['hora_llegada']."</td>
																						</tr>";

			}

			$respuesta .= "																	
																					</tbody>
																				</TABLE>";

			//////////////////////////////////////////////////////////////////////
			//////////////////////////   HOTELES   ///////////////////////////////
			//////////////////////////////////////////////////////////////////////

			//Ajustamos el tamaño de la fuente segun el numero de columnas

				$tamano_fuente_hoteles_nombre = 30;
				$tamano_fuente_hoteles = 16;
				$tamano_fuente_hoteles_cabecera = 30;
				$tamano_fuente_hoteles_precio = 120;



			$respuesta .= "														<TABLE border='0' width='100%' align='center' 																			style='font-weight:bold;font-size:12.0pt;font-																		family:&quot;Arial&quot;,&quot;sans-																				serif&quot;;color:#7ac6f3'>
																					<tbody>
																						<tr height='30'>																					
																							<td style='padding:0.5cm 0cm 0.5cm 0cm;' 																					align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:".$tamano_fuente_hoteles_cabecera.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_precio1_hotel."'>".$cabecera_1."
																											</span>
																										</b>
																									</p>
																								</td>

																						</tr>
																					</tbody>
																				</TABLE>";









			$control_lineas_hotel = 0; //Con esta variable controlamos el numero de hotles que queremos mostrar en la oferta
			for ($i = 0; $i < count($shoteles); $i++) {		

				if($shoteles[$i]['ciudad'] == $ciudad and $shoteles[$i]['orden'] == 1){	

				$respuesta .= " 												<TABLE border='0' width='100%' align='center' 																								style='font-weight:bold;font-size:12.0pt;font-																						family:&quot;Arial&quot;,&quot;sans-																								serif&quot;;color:#7ac6f3'>
																					<tbody>		
																						<tr height='30'>
																							
																							<td colspan='5' align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles_nombre.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_nombre_hotel."'>".$shoteles[$i]['nombre']."</td>	

																						</tr>
																					</tbody>
																				</TABLE>
																				<TABLE border='0' width='85%' align='center' 																			style='font-weight:bold;font-size:12.0pt;font-																		family:&quot;Arial&quot;,&quot;sans-																				serif&quot;;color:#7ac6f3'>
																					<tbody>
																						<tr height='30'>

																							<td>
																								<span align='center' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_categoria_hotel."'>Categoría: </span> 
																								<span align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_categoria_hotel."'>".$shoteles[$i]['categoria_2']."</span>
																							</td>

																							<td 
																								<span align='left' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_categoria_hotel."'>Localidad: </span>
																						    	<span align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_categoria_hotel."'>".$shoteles[$i]['localidad']."</span>
																						    </td>


																							<td <span align='center' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_regimen_hotel."'>Régimen: </span>
																								<span align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_regimen_hotel_tabla."'>".$shoteles[$i]['regimen']."</span>
																							</td>
																						</tr>
																					</tbody>
																				</TABLE>";



						if($shoteles[$i]['precio'] != 0 && $cabecera_1 != ''){																		
							$respuesta .= "										<TABLE border='0' width='100%' align='center' 																			style='font-weight:bold;font-size:12.0pt;font-																		family:&quot;Arial&quot;,&quot;sans-																				serif&quot;;color:#7ac6f3'>
																					<tbody>											
																						<tr height='30'>
																							
																								<td align='center' style='font-size:".$tamano_fuente_hoteles.".0pt;font-																					family:&quot;Arial&quot;,&quot;sans-																					serif&quot;;color:#b9d305'>
																										<p style='margin:0cm;margin-bottom:.0001pt'>
																											<b>
																												<span style='font-size:".$tamano_fuente_hoteles_precio.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_precio1_hotel."'>".$shoteles[$i]['precio']."€
																												</span>
																											</b>
																										</p>
																									</td>";
						}	




							$respuesta .= "												</tr>";
					$control_lineas_hotel++;
				}
			}

			$respuesta .= "																<tr>

																									<td colspan='5' style='padding:0.5cm 0cm 0cm 0cm; height:0pt' align='center' valign='center'>
																											<a href='http://54.229.179.250/pdf_teletipo_resumen_formato_3.php?id=".$id."&ciudad=".$ciudad."'>
																												<span style='text-decoration:none'>
																													<img width='300' height='30' src='imagenes/Logo_Descargar_Pdf_Resumen_Teletipo_Formato_1_Hitravel.jpg' border='0'>
																												</span>
																											</a>

																									</td>
																								</tr>
	
																					</tbody>
																				</TABLE>

																			</b>
																		</p>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>";



			$respuesta .= "						

												<!--LOGO HI TRAVEL-->

												<tr>
													<td style='background:".$color_pie_fondo.";padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_pie_borde.";align:center;'>
														<p style='width:95%;font-weight:normal;text-align:justify;color:".$color_pie_texto.";font-size:0.8em;margin:11.25pt 11.25pt 11.25pt 11.25pt;'>
															".$texto_pie."
															<u></u>
															<u></u>
														</p>
													</td>
												</tr>

											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					</BODY>
					</HTML>";

		}



		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		///////////////////////////////            OFERTA SOLO VUELO           /////////////////////////////////////////////

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




		if($formato == 3){

			$respuesta = "<HTML>
						  <link rel='StyleSheet' href='css/menu.css'>



					<body>
					<!--Color Hitravel #b9d305    color azul de fondo cabecera original: #015ec8 color azul de fondo bloques original: #1065af
						color textos y precios original: #ffb302-->

					<p class='MsoNormal' style='text-align:center' align='center'>
						<span style='font-size:7.0pt'>

						</span>
					</p>

					<table style='width:95.0%' border='0' cellpadding='0' cellspacing='0' width='100%'>
						<tbody>
							<tr>
								<td style='padding:0cm 0cm 0cm 0cm'>
									<div align='center'>
										<table style='width:18.5cm' border='0' cellpadding='0' cellspacing='0'>
											<tbody>

												
												<!--LOGO HI TRAVEL-->

												<tr>
													<td style='background:white;padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_borde_logo.";' align='center'>
														<p class='MsoNormal'>
															<a href='http://www.hitravel.es/' title='HI TRAVEL' target='_blank'>
																<span style='text-decoration:none'  align='center'>
																	<img src='imagenes/Logo_2.jpg' alt='HI TRAVEL' border='0' width='652' height='90'>
																</span>
															</a>
															<u></u>
															<u></u>
														</p>
													</td>
												</tr>

												<!--TITULOS PRIMERA OFERTA-->

												<tr>
													<td>
													<table width='100%' border='0' cellpadding='0' cellspacing='0' style='background:#0E0090;' border='0'>
														<tr>
															<td width='15%'align='center' style='background:".$color_cabecera_fondo_logo_izquierda.";padding:0.0pt 0pt 0.0pt 0pt'>";

					if($logo_dcto_2 != null){										
						$respuesta .= "							<img border='0' src='imagenes/logos/".$logo_dcto_2."' width='95' height='105'>";
					}											

					$respuesta .= "							</td>

															<td width='70%'style='background:".$color_cabecera_fondo_destino.";padding:10.0pt 0pt 15.0pt 0pt'>
																<p style='margin-right:0cm;margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center' align='center'>
																	<b>
																		<span style='font-size:40.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_fuente_destino."'>".$destino."</span>
																	</b>
																</p>
															</td>

															<td width='15%' align='center' style='background:".$color_cabecera_fondo_logo_derecha.";padding:0pt 0pt 0pt 0pt'>";

					if($logo_dcto_1 != null){											
						$respuesta .= "							<img border='0' src='imagenes/logos/".$logo_dcto_1."' width='95' height='105'>";
					}											

					$respuesta .= "							</td>
														</tr>
													</table>
													</td>
												</tr>

												<!--FOTO DESTINO DE LA OFERTA-->
												<tr>
													<td style='background:".$color_cabecera_imagen_fondo.";padding:0cm 0cm 0cm 0cm' align='center'>
														<!--<p>-->
															<a href='http://www.hitravel.es' target='_blank'>";
	

					if($cabecera_imagen == ''){
						for ($i = 0; $i < count($shoteles); $i++) {		
							if($shoteles[$i]['ciudad'] == $ciudad and $shoteles[$i]['orden'] == 1){	
								$respuesta .= "									<img class='CToWUd' src='imagenes/alojamientos/".$shoteles[$i]['id_hotel']."_P_1.jpg' border='0' width='875' height='363'>";
							}
						}
					}else{									
						$respuesta .= "									<img src='imagenes/logos/".$cabecera_imagen."' alt='Canarias' border='0' width='875' height='363'>";
					}


					$respuesta .= "							</a>

														<!--</p>-->
													</td>
												</tr>

												<!--TEXTOS Y ENLACES DESTINO PRIMERA OFERTA-->

												<tr style='height:123.0pt'>
													<td style='background:".$color_tabla_fondo.";padding:15.0pt 10pt 1.0pt 22.5pt; height:123.0pt'>
														<table style='width:95.0%' border='0' cellpadding='0' cellspacing='0' width='95%'>
															<tbody>
																<tr>
																	<td style='width:60.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='60%'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:25.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_titulo."'>".$teletipo_titulo."
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>
																	</td>

																	<td style='width:40.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='40%'>
																		<p style='margin:0cm;margin-bottom:.0001pt;text-align:right' align='right'>
																			<b>
																				<span style='font-size:25.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>
																					<a>
																						<span style='color:".$color_ciudad_salida.";text-decoration:none'>Desde ".@$nombre_ciudad."</span>
																					</a>
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>
																	</td>
																</tr>
																<tr>
																	<td style='padding:0.5cm 0cm 0cm 0cm' valign='top' colspan='2'>
												
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>

																				<TABLE width='90%' align='center' style='font-weight:bold;font-size:20.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_texto_vuelos."'>
																					<tbody>
																						<tr>
																							<td align='center' colspan='1'>Vuelos:</td>
																							<td align='left' colspan='8' style='font-weight:bold;font-size:12.0pt;'>(".$texto_salidas.")</td>
																						</tr>";


			for ($i = 0; $i < count($svuelos); $i++) {	

				$respuesta .= " 														<tr>
																							<td align='center'><img class='CToWUd' src='imagenes/transportes/".$svuelos[$i]['cia']."_logo.jpg' alt='Canadá' border='0' width='70' height='20'></td>
																								
																							<td align='center'>".$svuelos[$i]['origen']."</td>
																							<td align='center'>-</td>
																							<td align='center'>".$svuelos[$i]['destino']."</td>
																							<td align='center'>".$svuelos[$i]['cia']."</td>
																							<td align='center'>".$svuelos[$i]['vuelo']."</td>
																							<td align='center'>".$svuelos[$i]['hora_salida']."</td>
																							<td align='center'>-</td>
																							<td align='center'>".$svuelos[$i]['hora_llegada']."</td>
																						</tr>";

			}

			$respuesta .= "																	
																					</tbody>
																				</TABLE>";

			//////////////////////////////////////////////////////////////////////
			//////////////////////////   HOTELES   ///////////////////////////////
			//////////////////////////////////////////////////////////////////////

			//Ajustamos el tamaño de la fuente segun el numero de columnas

				$tamano_fuente_hoteles_nombre = 30;
				$tamano_fuente_hoteles = 16;
				$tamano_fuente_hoteles_cabecera = 35;
				$tamano_fuente_hoteles_precio = 120;



			$respuesta .= "														<TABLE border='0' width='100%' align='center' 																			style='font-weight:bold;font-size:12.0pt;font-																		family:&quot;Arial&quot;,&quot;sans-																				serif&quot;;color:#7ac6f3'>
																					<tbody>
																						<tr height='30'>																					
																							<td style='padding:0.8cm 0cm 0.5cm 0cm;' 																					align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:".$tamano_fuente_hoteles_cabecera.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_precio1_hotel."'>".$cabecera_1."
																											</span>
																										</b>
																									</p>
																								</td>

																						</tr>
																					</tbody>
																				</TABLE>";



			$control_lineas_hotel = 0; //Con esta variable controlamos el numero de hotles que queremos mostrar en la oferta
			for ($i = 0; $i < count($shoteles); $i++) {		

				if($shoteles[$i]['ciudad'] == $ciudad and $shoteles[$i]['orden'] == 1){	

				/*$respuesta .= " 												<TABLE border='0' width='100%' align='center' 																								style='font-weight:bold;font-size:12.0pt;font-																						family:&quot;Arial&quot;,&quot;sans-																								serif&quot;;color:#7ac6f3'>
																					<tbody>		
																						<tr height='30'>
																							
																							<td colspan='5' align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles_nombre.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_nombre_hotel."'>".$shoteles[$i]['nombre']."</td>	

																						</tr>
																					</tbody>
																				</TABLE>
																				<TABLE border='0' width='85%' align='center' 																			style='font-weight:bold;font-size:12.0pt;font-																		family:&quot;Arial&quot;,&quot;sans-																				serif&quot;;color:#7ac6f3'>
																					<tbody>
																						<tr height='30'>

																							<td>
																								<span align='center' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_categoria_hotel."'>Categoría: </span> 
																								<span align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_categoria_hotel."'>".$shoteles[$i]['categoria_2']."</span>
																							</td>

																							<td 
																								<span align='left' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_categoria_hotel."'>Localidad: </span>
																						    	<span align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_categoria_hotel."'>".$shoteles[$i]['localidad']."</span>
																						    </td>


																							<td <span align='center' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_regimen_hotel."'>Régimen: </span>
																								<span align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_regimen_hotel_tabla."'>".$shoteles[$i]['regimen']."</span>
																							</td>
																						</tr>
																					</tbody>
																				</TABLE>";*/



						if($shoteles[$i]['precio'] != 0 && $cabecera_1 != ''){																		
							$respuesta .= "										<TABLE border='0' width='100%' align='center' 																			style='font-weight:bold;font-size:12.0pt;font-																		family:&quot;Arial&quot;,&quot;sans-																				serif&quot;;color:#7ac6f3'>
																					<tbody>											
																						<tr height='30'>
																							
																								<td align='center' style='font-size:".$tamano_fuente_hoteles.".0pt;font-																					family:&quot;Arial&quot;,&quot;sans-																					serif&quot;;color:#b9d305'>
																										<p style='margin:0cm;margin-bottom:.0001pt'>
																											<b>
																												<span style='font-size:".$tamano_fuente_hoteles_precio.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_precio1_hotel."'>".$shoteles[$i]['precio']."€
																												</span>
																											</b>
																										</p>
																									</td>";
						}	




							$respuesta .= "												</tr>";
					$control_lineas_hotel++;
				}
			}

			$respuesta .= "																<tr>

																									<td colspan='5' style='padding:0.5cm 0cm 0cm 0cm; height:0pt' align='center' valign='center'>
																											<a href='http://54.229.179.250/pdf_teletipo_resumen_formato_4.php?id=".$id."&ciudad=".$ciudad."'>
																												<span style='text-decoration:none'>
																													<img width='300' height='30' src='imagenes/Logo_Descargar_Pdf_Resumen_Teletipo_Formato_1_Hitravel.jpg' border='0'>
																												</span>
																											</a>

																									</td>
																								</tr>
	
																					</tbody>
																				</TABLE>

																			</b>
																		</p>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>";



			$respuesta .= "						

												<!--LOGO HI TRAVEL-->

												<tr>
													<td style='background:".$color_pie_fondo.";padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_pie_borde.";align:center;'>
														<p style='width:95%;font-weight:normal;text-align:justify;color:".$color_pie_texto.";font-size:0.8em;margin:11.25pt 11.25pt 11.25pt 11.25pt;'>
															".$texto_pie."
															<u></u>
															<u></u>
														</p>
													</td>
												</tr>

											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					</BODY>
					</HTML>";

		}







///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////            TEXTO LIBRE                  /////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



if($formato == 4){
	$respuesta = "<HTML>
	  <link rel='StyleSheet' href='css/menu.css'>



<body>
<!--Color Hitravel #b9d305    color azul de fondo cabecera original: #015ec8 color azul de fondo bloques original: #1065af
	color textos y precios original: #ffb302-->

<p class='MsoNormal' style='text-align:center' align='center'>
	<span style='font-size:7.0pt'>

	</span>
</p>

<table style='width:95.0%' border='0' cellpadding='0' cellspacing='0' width='100%'>
<tbody>
	<tr>
		<td style='padding:0cm 0cm 0cm 0cm'>
			<div align='center'>
				<table style='width:18.5cm' border='0' cellpadding='0' cellspacing='0'>
					<tbody>

						
						<!--LOGO HI TRAVEL-->

						<tr>
							<td style='background:white;padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_borde_logo.";' align='center'>
								<p class='MsoNormal'>
									<a href='http://www.hitravel.es/' title='HI TRAVEL' target='_blank'>
										<span style='text-decoration:none'  align='center'>
											<img src='imagenes/Logo_2.jpg' alt='HI TRAVEL' border='0' width='652' height='90'>
										</span>
									</a>
									<u></u>
									<u></u>
								</p>
							</td>
						</tr>

						<!--TITULOS PRIMERA OFERTA-->

						<tr>
							<td>
							<table width='100%' border='0' cellpadding='0' cellspacing='0' style='background:#0E0090;' border='0'>
								<tr>
									<td width='15%'align='center' style='background:".$color_cabecera_fondo_logo_izquierda.";padding:0.0pt 0pt 0.0pt 0pt'>";

					if($logo_dcto_2 != null){										
						$respuesta .= "<img border='0' src='imagenes/logos/".$logo_dcto_2."' width='95' height='105'>";
					}											

					//TITULO
					$respuesta .= "</td>

							<td width='70%'style='background:".$color_cabecera_fondo_destino.";padding:10.0pt 0pt 15.0pt 0pt'>
								<p style='margin-right:0cm;margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center' align='center'>
									<b>
										<span style='font-size:36.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_fuente_destino."'>".$teletipo_titulo."</span>
									</b>
								</p>
							</td>

							<td width='15%' align='center' style='background:".$color_cabecera_fondo_logo_derecha.";padding:0pt 0pt 0pt 0pt'>";

					if($logo_dcto_1 != null){											
						$respuesta .= "<img border='0' src='imagenes/logos/".$logo_dcto_1."' width='95' height='105'>";
					}											

					$respuesta .= "</td>
							</tr>
						</table>
						</td>
					</tr>

					<!--FOTO DESTINO DE LA OFERTA-->
					<tr>
						<td style='background:".$color_cabecera_imagen_fondo.";padding:0cm 0cm 0cm 0cm' align='center'>
							<!--<p>-->
								<a href='http://www.hitravel.es' target='_blank'>
									<!--<span style='text-decoration:none'>-->
										<img src='imagenes/logos/".$cabecera_imagen."' alt='Canarias' border='0' width='875' height='363'>
									<!--</span>-->
								</a>

							<!--</p>-->
						</td>
					</tr>

					<!--TEXTOS-->

					<tr style='height:123.0pt'>
						<td style='background:".$color_tabla_fondo.";padding:15.0pt 10pt 15.0pt 22.5pt; height:123.0pt'>
							<table style='text-align:center;' border='0' cellpadding='0' cellspacing='0' width='95%' align='center'>
								<tbody>
									<tr>
										<td style='width:95%; padding:0cm 0cm 0.5cm 0cm'; valign='top'; text-align:center;'>
											<p style='margin:0cm;margin-bottom:.0001pt'>
												<b>
													<span style='text-align:justify; font-size:30.0pt; font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_titulo."'>".$texto_libre_1."
														<u></u>
														<u></u>
													</span>
												</b>
											</p>
										</td>


									</tr>
									<tr>
										<td style='padding:0cm 0cm 0cm 0cm' valign='top' colspan='1'>
					
											<p style='margin:0cm;margin-bottom:.0001pt'>
												<b>

													<TABLE width='95%' align='center' style='font-weight:bold;font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_texto_vuelos."'>
														<tbody>
															<tr>
																<td align='left' colspan='1' style='padding:0.5cm 0cm 0cm 0cm; text-align:justify; font-weight:bold;font-size:20.0pt;'>".$texto_libre_2."</td>
															</tr>
														</tbody>
													</TABLE>";



			//////////////////////////////////////////////////////////////////////
			//////////////////////////   HOTELES   ///////////////////////////////
			//////////////////////////////////////////////////////////////////////

			//Ajustamos el tamaño de la fuente segun el numero de columnas

			$tamano_fuente_hoteles = 20;

			//TERCER PARRAFO
			$respuesta .= "																	
													<TABLE border='0' width='95%' align='center' style='font-weight:bold;font-size:12.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3'>
														<tbody>
															<tr height='30'>
																<td align='left' colspan='1' style='padding:0.5cm 0cm 0cm 0cm; text-align:justify; font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_nombre_hotel."'>".$texto_libre_3."</td>
															</tr>";


$respuesta .= "																																			<tr height='30'>
													<td align='left' colspan='1' style='padding:0.5cm 0cm 0cm 0cm; text-align:justify; font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3;'>
															<a style='padding:0.5cm 0cm 0cm 0cm; text-align:justify; font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3;' href='http://www.hitravel.es/buscar_viajes.php' target='blank'>
																
																	Pulsa aquí para acceder directamente a nuestro buscador y selecciona la opción '".$cabecera_1."' en Tipo de Viaje.
															</a>
													</td>
												</tr>
														</tbody>
													</TABLE>

												</b>
											</p>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>";


			$respuesta .= "						

							<!--LOGO HI TRAVEL-->

							<tr>
								<td style='background:".$color_pie_fondo.";padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_pie_borde.";align:center;'>
									<p style='width:95%;font-weight:normal;text-align:center;color:".$color_pie_texto.";font-size:1.1em;margin:11.25pt 11.25pt 11.25pt 11.25pt;'>
										".$texto_pie."
										<u></u>
										<u></u>
									</p>
								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</td>
		</tr>
	</tbody>
</table>
</BODY>
</HTML>";			
}






///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////            OFERTA TEXTO LIBRE                  ///////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



if($formato == 5){
	$respuesta = "<HTML>
	  <link rel='StyleSheet' href='css/menu.css'>



<body>
<!--Color Hitravel #b9d305    color azul de fondo cabecera original: #015ec8 color azul de fondo bloques original: #1065af
	color textos y precios original: #ffb302-->

<p class='MsoNormal' style='text-align:center' align='center'>
	<span style='font-size:7.0pt'>

	</span>
</p>

<table style='width:95.0%' border='0' cellpadding='0' cellspacing='0' width='100%'>
<tbody>
	<tr>
		<td style='padding:0cm 0cm 0cm 0cm'>
			<div align='center'>
				<table style='width:18.5cm' border='0' cellpadding='0' cellspacing='0'>
					<tbody>

						
						<!--LOGO HI TRAVEL-->

						<tr>
							<td style='background:white;padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_borde_logo.";' align='center'>
								<p class='MsoNormal'>
									<a href='http://www.hitravel.es/' title='HI TRAVEL' target='_blank'>
										<span style='text-decoration:none'  align='center'>
											<img src='imagenes/Logo_2.jpg' alt='HI TRAVEL' border='0' width='652' height='90'>
										</span>
									</a>
									<u></u>
									<u></u>
								</p>
							</td>
						</tr>

						<!--TITULOS PRIMERA OFERTA-->

						<tr>
							<td>
							<table width='100%' border='0' cellpadding='0' cellspacing='0' style='background:#0E0090;' border='0'>
								<tr>
									<td width='15%'align='center' style='background:".$color_cabecera_fondo_logo_izquierda.";padding:0.0pt 0pt 0.0pt 0pt'>";

					if($logo_dcto_2 != null){										
						$respuesta .= "<img border='0' src='imagenes/logos/".$logo_dcto_2."' width='95' height='105'>";
					}											

					//TITULO
					$respuesta .= "</td>

							<td width='70%'style='background:".$color_cabecera_fondo_destino.";padding:10.0pt 0pt 15.0pt 0pt'>
								<p style='margin-right:0cm;margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center' align='center'>
									<b>
										<span style='font-size:50.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_fuente_destino."'>".$teletipo_titulo."</span>
									</b>
								</p>
							</td>

							<td width='15%' align='center' style='background:".$color_cabecera_fondo_logo_derecha.";padding:0pt 0pt 0pt 0pt'>";

					if($logo_dcto_1 != null){											
						$respuesta .= "<img border='0' src='imagenes/logos/".$logo_dcto_1."' width='95' height='105'>";
					}											

					$respuesta .= "</td>
							</tr>
						</table>
						</td>
					</tr>

					<!--FOTO DESTINO DE LA OFERTA-->
					<tr>
						<td style='background:".$color_cabecera_imagen_fondo.";padding:0cm 0cm 0cm 0cm' align='center'>
							<!--<p>-->
								<a href='http://www.hitravel.es' target='_blank'>
									<!--<span style='text-decoration:none'>-->
										<img src='imagenes/logos/".$cabecera_imagen."' alt='Canarias' border='0' width='875' height='363'>
									<!--</span>-->
								</a>

							<!--</p>-->
						</td>
					</tr>

					<!--TEXTOS-->

					<tr style='height:123.0pt'>
						<td style='background:".$color_tabla_fondo.";padding:15.0pt 10pt 15.0pt 22.5pt; height:123.0pt'>
							<table style='text-align:center;' border='0' cellpadding='0' cellspacing='0' width='95%' align='center'>
								<tbody>
									<tr>
										<td style='width:95%; padding:0cm 0cm 0.5cm 0cm'; valign='top'; text-align:center;'>
											<p style='margin:0cm;margin-bottom:.0001pt'>
												<b>
													<span style='text-align:justify; font-size:60.0pt; font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_titulo."'>".$texto_libre_1."
														<u></u>
														<u></u>
													</span>
												</b>
											</p>
										</td>


									</tr>
									<tr>
										<td style='padding:0cm 0cm 0cm 0cm' valign='top' colspan='1'>
					
											<p style='margin:0cm;margin-bottom:.0001pt'>
												<b>

													<TABLE width='95%' align='center' style='font-weight:bold;font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_texto_vuelos."'>
														<tbody>
															<tr>
																<td align='left' colspan='1' style='padding:0.5cm 0cm 0cm 0cm; text-align:justify; font-weight:bold;font-size:30.0pt;'>".$texto_libre_2."</td>
															</tr>
														</tbody>
													</TABLE>";



			//////////////////////////////////////////////////////////////////////
			//////////////////////////   HOTELES   ///////////////////////////////
			//////////////////////////////////////////////////////////////////////

			//Ajustamos el tamaño de la fuente segun el numero de columnas

			$tamano_fuente_hoteles = 20;

			//TERCER PARRAFO
			$respuesta .= "																	
													<TABLE border='0' width='95%' align='center' style='font-weight:bold;font-size:12.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3'>
														<tbody>
															<tr height='30'>
																<td align='left' colspan='1' style='padding:0.5cm 0cm 0cm 0cm; text-align:justify; font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_nombre_hotel."'>".$texto_libre_3."</td>
															</tr>";


$respuesta .= "																																			<tr height='30'>
													<td align='left' colspan='1' style='padding:0.5cm 0cm 0cm 0cm; text-align:justify; font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3;'>
															<a style='padding:0.5cm 0cm 0cm 0cm; text-align:justify; font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3;' href='http://www.hitravel.es/buscar_viajes.php' target='blank'>
																
																	Pulsa aquí para acceder directamente a nuestro buscador y selecciona la opción '".$cabecera_1."'  en tipo de viaje".$cabecera_2." ".$cabecera_3.". (http://www.hitravel.es/Buscar_viajes.php)
															</a>
													</td>
												</tr>
														</tbody>
													</TABLE>

												</b>
											</p>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>";


			$respuesta .= "						

							<!--LOGO HI TRAVEL-->

							<tr>
								<td style='background:".$color_pie_fondo.";padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_pie_borde.";align:center;'>
									<p style='width:95%;font-weight:normal;text-align:center;color:".$color_pie_texto.";font-size:1.1em;margin:11.25pt 11.25pt 11.25pt 11.25pt;'>
										".$texto_pie."
										<u></u>
										<u></u>
									</p>
								</td>
							</tr>

						</tbody>
					</table>
				</div>
			</td>
		</tr>
	</tbody>
</table>
</BODY>
</HTML>";			
}




	if($formato == 6){
		//ECHO($cabecera_1.'-');
		$respuesta = "<html>
				<body>
					<p class='MsoNormal'>
						<a href='http://www.hitravel.es/' title='HI TRAVEL' target='_blank'>
							<span style='text-decoration:none'  align='center'>
								<img src='imagenes/teletipos/".$texto_libre_1."' style='width: 210mm; height: 297mm;' alt='HI TRAVEL' border='0'>
							</span>
						</a>


					</p>

					<p align='center' style='width: 210mm;'>
						<a href='http://hitravel.com.es/imagenes/teletipos/".$texto_libre_2."' title='Haz click para ver la oferta en pdf' onClick='window.open(this.href); return false;'>
							<img src='imagenes/Logo_Descargar_Pdf_Resumen_Teletipo_Formato_1_Hitravel.jpg' align='center' style='width:105mm; height:10mm;' alt='HI TRAVEL' border='0'>
						</a>

					</p>


				</body>
				</html>";	
		//echo	($respuesta);	
	}

	if($formato == 7){
		//ECHO($cabecera_1.'-');
		$respuesta = "<html>
				<body>
					<p class='MsoNormal'>
						<a href='http://www.hitravel.es/' title='HI TRAVEL' target='_blank'>
							<span style='text-decoration:none'  align='center'>
								<img src='imagenes/teletipos/".$texto_libre_1."' style='width: 210mm; height: 140mm;' alt='HI TRAVEL' border='0'>
							</span>
						</a>


					</p>

					<p align='center' style='width: 210mm;'>
						<a href='http://hitravel.com.es/imagenes/teletipos/".$texto_libre_2."' title='Haz click para ver la oferta en pdf' onClick='window.open(this.href); return false;'>
							<img src='imagenes/Logo_Descargar_Pdf_Resumen_Teletipo_Formato_1_Hitravel.jpg' align='center' style='width:105mm; height:10mm;' alt='HI TRAVEL' border='0'>
						</a>

					</p>


				</body>
				</html>";	
		//echo	($respuesta);	
	}


		return $respuesta;											
	}

	function Enviar_Oferta_Mail_Especifico($id, $direccion_mail, $ciudad){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		$respuesta = "";
		
		//EJEMPLO LECTURA UNA LINEA
		$datos_teletipo =$conexion->query("SELECT formato
		FROM hit_teletipos WHERE ID = '".$id."'");
		$odatos_teletipo = $datos_teletipo->fetch_assoc();
		$teletipo_formato = $odatos_teletipo['formato'];

		//obtenemos el html
		$mensaje_html = $this->carga_html_oferta($id, $teletipo_formato, $ciudad, '');	

		$asunto = "HITRAVEL - NEWSLETTER";
		//Enviamos mail
		$envio = enviar_mail_oferta($asunto, $mensaje_html, $direccion_mail, '');
			
		return $respuesta;											
	}	

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsTeletipos_visualizar($conexion, $filadesde, $usuario, $buscar_id, $buscar_nombre, $buscar_destino){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id= $buscar_id;
		$this->Buscar_nombre= $buscar_nombre;
		$this->Buscar_destino = $buscar_destino;
	}		

}

?>