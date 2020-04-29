<?php
# Cargamos la librería dompdf.
	require_once 'dompdf_config.inc.php';
 	include 'funciones_php/conexiones.php';
	//include 'funciones_php/loggin.php';
	//require 'clases/general.cls.php';
	require 'clases/teletipos_visualizar.cls.php';

$parametrosg = $_GET;

$id = $parametrosg['id'];
$ciudad = $parametrosg['ciudad'];

$conexion = conexion_hit();




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
												  t.texto_pie,
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
		$teletipo_colores = $odatos_teletipo['colores'];
		$teletipo_cabecera_imagen_codigo = $odatos_teletipo['cabecera_imagen'];
		$teletipo_logo_dcto_1_codigo = $odatos_teletipo['logo_dcto_1'];
		$teletipo_logo_dcto_2_codigo = $odatos_teletipo['logo_dcto_2'];
		$destino = $odatos_teletipo['destino'];
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


		$ClaseVisualizar = new clsTeletipos_visualizar($conexion, '1', '', $id, ' ', ' ');


		//DATOS VUELOS
		$svuelos = $ClaseVisualizar->Cargar_vuelos($id,$ciudad,$fecha_busqueda);

		$clase = $svuelos[0]['clase_1'];

		if($texto_pie == ''){
			$texto_pie = "Oferta válida a partir del ".$fecha_lanzamiento.". Precio final por persona correspondiente a determinadas salidas de ".$salidas_texto_pie.". Los precios incluyen: Avión ida y vuelta (clase ".$clase.") + Estancias en el hotel en régimen elegido y/o indicado en habitación doble + Tasas de aeropuerto +  Seguro básico. Consultar  condiciones de niños, suplementos aéreos y condiciones generales en  www.hitravel.es Plazas Limitadas. CICMA 2988 COD: CAN15";
		}

		

		//DATOS HOTELES
		$sHoteles = $ClaseVisualizar->Cargar_hoteles($id);	

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


		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		///////////////////////////////            TABLA RESUMEN                  /////////////////////////////////////////////

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		

			$respuesta = "<HTML>
						  <link rel='StyleSheet' href='css/menu.css'>

					<HEAD>
					  <meta charset='utf-8'>
					  <TITLE> Menú </TITLE>
					  <META NAME='Author' CONTENT='Jose de Frias'>
					  <META NAME='Keywords' CONTENT=''>
					  <META NAME='Description' CONTENT=''>
					  <link rel='StyleSheet' href='css/menu.css'>
					  <link rel='StyleSheet' href='css/calendario/calendar-blue2.css'>
					  <style type='text/css'>
						@page { margin: 0px; }
						body { margin: 0px; }
					  </style>

					</HEAD>

					<body style='height:31cm'>

					<table style='padding:0cm 0cm 0cm 0cm; margin:0cm 0cm 0cm 0cm;' border='0' cellpadding='0' cellspacing='0'>
						<tbody>
							<tr>
								<td style='background:#dbdbdb;padding:0cm 0cm 0cm 0cm'>
									<div align='center'>
										<table style='width:21.5cm' border='0' cellpadding='0' cellspacing='0'>
											<tbody>
												
												<!--LOGO HI TRAVEL-->

												<tr>
													<td style='background:".$color_fondo_logo.";padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_borde_logo.";' align='center'>
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
													<table width='100%' border='0' cellpadding='0' cellspacing='0' style='background:#0E0090 border='0'>
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

															<td width='15%' align='center' style='background:".$color_cabecera_fondo_logo_derecha.";padding:0pt 20.0pt 0pt 0pt'>";

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
															<a href='http://www.hitravel.es' target='_blank'>";



					if($cabecera_imagen == ""){

						for ($i = 0; $i < count($sHoteles); $i++) {		
							//if($shoteles[$i]['ciudad'] == $ciudad and $shoteles[$i]['orden'] == 1){	
							if($sHoteles[$i]['ciudad'] == $ciudad and $sHoteles[$i]['orden'] == 1){
								$respuesta .= "<img class='CToWUd' src='imagenes/alojamientos/".$sHoteles[$i]['id_hotel']."_P_1.jpg' border='0' width='820' height='300'>";
							}
						}
					}else{		
													
						$respuesta .= "<img src='imagenes/logos/".$cabecera_imagen."' alt='Canarias' border='0' width='820' height='300'>";
					}






					$respuesta .= "							</a>

													</td>
												</tr>

												<!--TEXTOS Y ENLACES DESTINO PRIMERA OFERTA-->

												<tr style='height:123.0pt'>
													<td style='background:".$color_tabla_fondo.";padding:15.0pt 10pt 15.0pt 22.5pt; height:123.0pt'>
														<table style='width:95.0%;padding:0pt 0pt 0.5pt 0pt;' border='0' cellpadding='0' cellspacing='0' width='95%'>
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
																					<!--<a href='http://www.panavision-tours.es/circuitos/combinados-canada-usa/canadavision-2015/' target='_blank'>-->
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
				$tamano_fuente_hoteles_precio = 100;


			$respuesta .= "														<TABLE border='0' width='100%' align='center' 																			style='font-weight:bold;font-size:12.0pt;font-																		family:&quot;Arial&quot;,&quot;sans-																				serif&quot;;color:#7ac6f3'>
																					<tbody>
																						<tr height='30'>																					
																							<td style='padding:0.0cm 0cm 0.0cm 0cm;' 																					align='center'>
																									<p style='margin:0cm;margin-bottom:.0000pt'>
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
			for ($i = 0; $i < count($sHoteles); $i++) {		

				if($sHoteles[$i]['ciudad'] == $ciudad and $sHoteles[$i]['orden'] == 1){	

				$respuesta .= " 												<TABLE border='0' width='100%' align='center' 																								style='font-weight:bold;font-size:12.0pt;font-																						family:&quot;Arial&quot;,&quot;sans-																								serif&quot;;color:#7ac6f3'>
																					<tbody>		
																						<tr height='30'>

																							<td colspan='5' align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles_nombre.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_nombre_hotel."'>".$sHoteles[$i]['nombre']."</td>	

																						</tr>
																					</tbody>
																				</TABLE>
																				<TABLE border='0' width='100%' align='center' 																			style='font-weight:bold;font-size:12.0pt;font-																		family:&quot;Arial&quot;,&quot;sans-																				serif&quot;;color:#7ac6f3'>
																					<tbody>
																						<tr height='30'>

																							<td>
																								<span align='center' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_categoria_hotel."'>Categoría: </span> 
																								<span align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_categoria_hotel."'>".$sHoteles[$i]['categoria_3']."</span>
																							</td>

																							<td 
																								<span align='left' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_categoria_hotel."'>Localidad: </span>
																						    	<span align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_categoria_hotel."'>".$sHoteles[$i]['localidad']."</span>
																						    </td>


																							<td <span align='center' colspan='1' style='font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_cabecera_precios_regimen_hotel."'>Régimen: </span>
																								<span align='center' style='font-weight:bold;font-size:".$tamano_fuente_hoteles.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_regimen_hotel_tabla."'>".$sHoteles[$i]['regimen']."</span>
																							</td>
																						</tr>
																					</tbody>
																				</TABLE>";



						if($sHoteles[$i]['precio'] != 0 && $cabecera_1 != ''){																		
							$respuesta .= "										<TABLE border='0' width='100%' align='center' 																			style='font-weight:bold;font-size:12.0pt;font-																		family:&quot;Arial&quot;,&quot;sans-																				serif&quot;;color:#7ac6f3'>
																					<tbody>											
																						<tr height='30'>
																							
																								<td align='center' style='font-size:".$tamano_fuente_hoteles.".0pt;font-																					family:&quot;Arial&quot;,&quot;sans-																					serif&quot;;color:#b9d305'>
																										<p style='margin:0cm;margin-bottom:.0001pt'>
																											<b>
																												<span style='font-size:".$tamano_fuente_hoteles_precio.".0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:".$color_precios_precio1_hotel."'>".$sHoteles[$i]['precio']."€
																												</span>
																											</b>
																										</p>
																									</td>";
						}	




							$respuesta .= "												</tr>";
					$control_lineas_hotel++;
				}
			}

			$respuesta .= "															</tbody>
																				</TABLE>

																			</b>
																		</p>
																	</td>
																</tr>
															</tbody>
														</table>
														<br>
														<table style='width:95.0%; background:".$color_pie_fondo."; padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_pie_borde.";' border='0' cellpadding='0' cellspacing='0'>
																<tr>
																	<td style='background:".$color_pie_fondo.";padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:".$color_pie_borde.";align:center;'>
																		<p style='width:95%;font-weight:normal;text-align:justify;color:".$color_pie_texto.";font-size:0.8em;margin:6pt 11.25pt 6pt 11.25pt;'>
																			".$texto_pie."
																			<u></u>
																			<u></u>
																		</p>
																	</td>
																</tr>
														</table>	
													</td>
												</tr>";


			$respuesta .= "					</tbody>
									
									</div>
								</td>
							</tr>
						</tbody>
					</table>

					</BODY>
					</HTML>";			



 
# Instanciamos un objeto de la clase DOMPDF.
$mipdf = new DOMPDF();
 
# Definimos el tamaño y orientación del papel que queremos.
# O por defecto cogerá el que está en el fichero de configuración.
$mipdf ->set_paper("A4", "portrait");
  
# Cargamos el contenido HTML.
$mipdf ->load_html($respuesta,'UTF-8'); 
 
# Renderizamos el documento PDF.
$mipdf ->render();
 
# Enviamos el fichero PDF al navegador.
$nombre_pdf = "Hitravel_Oferta_Resumen.pdf";
$mipdf ->stream($nombre_pdf);



?>


