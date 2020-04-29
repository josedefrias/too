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
												  t.cabecera_imagen, 
												  d.NOMBRE destino,
												  t.cuadro,
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
												  t.cabecera_3
											FROM hit_teletipos t, hit_destinos d
											WHERE t.DESTINO = d.CODIGO	and ID = '".$id."'");
		$odatos_teletipo = $datos_teletipo->fetch_assoc();
		$teletipo_titulo = $odatos_teletipo['titulo'];
		$teletipo_cabecera_imagen_codigo = $odatos_teletipo['cabecera_imagen'];
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
		//echo($odatos_teletipo['cuadro']);

		$datos_cabecera_imagen =$conexion->query("SELECT fichero FROM hit_logos WHERE ID = '".$teletipo_cabecera_imagen_codigo."'");
		$odatos_cabecera_imagen = $datos_cabecera_imagen->fetch_assoc();
		$cabecera_imagen = $odatos_cabecera_imagen['fichero'];


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
		$svuelos = $ClaseVisualizar->Cargar_vuelos($id,$ciudad);

		//DATOS HOTELES
		$sHoteles = $ClaseVisualizar->Cargar_hoteles($id);	

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

					</HEAD>

					<body style='margin-top: 0.0em; margin-left: 0.0em;'>

					<table style='padding:0cm 0cm 0cm 0cm; margin:0cm 0cm 0cm 0cm;' border='1' cellpadding='0' cellspacing='0'>
						<tbody>
							<tr>
								<td style='background:#dbdbdb;padding:0cm 0cm 0cm 0cm'>
									<div align='center'>
										<table style='width:18cm' border='0' cellpadding='0' cellspacing='0'>
											<tbody>

												
												<!--LOGO HI TRAVEL-->

												<tr>
													<td style='background:white;padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:#b9d305;' align='center'>
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
													<td style='background:#0E0090;padding:10.0pt 22.5pt 15.0pt 22.5pt'>
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
																<!--<span style='font-family:Arial,sans-serif;color:#7ac6f3'>Tenerife<u></u><u></u></span>
																<span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>Tenerife</span>-->
																<span style='font-size:40.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$destino."</span>
															</b>
														</p>
													</td>
												</tr>

												<!--FOTO DESTINO DE LA OFERTA-->
												<tr>
													<td style='background:#b9d305;padding:0cm 0cm 0cm 0cm' align='center'>
														<!--<p>-->
															<a href='http://www.hitravel.es' target='_blank'>
																<!--<span style='text-decoration:none'>-->
																	<img src='imagenes/logos/".$cabecera_imagen."' alt='Canarias' border='0' width='700' height='274'>
																<!--</span>-->
															</a>

														<!--</p>-->
													</td>
												</tr>

												<!--TEXTOS Y ENLACES DESTINO PRIMERA OFERTA-->

												<tr style='height:123.0pt'>
													<td style='background:#1100A7;padding:15.0pt 10pt 15.0pt 22.5pt; height:123.0pt'>
														<table style='width:95.0%' border='0' cellpadding='0' cellspacing='0' width='95%'>
															<tbody>
																<tr>
																	<td style='width:60.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='60%'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:white'>".$teletipo_titulo."
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
																						<span style='color:#b9d305;text-decoration:none'>Desde ".@$nombre_ciudad."</span>
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
																				<TABLE width='80%' align='center' style='font-weight:bold;font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3'>
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

			//////////////////////////////////////////////////////////////////////
			//////////////////////////   HOTELES   ///////////////////////////////
			//////////////////////////////////////////////////////////////////////

			$respuesta .= "															</tbody>
																				</TABLE>

																				<TABLE border='0' width='100%' align='center' style='font-weight:bold;font-size:12.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3'>
																					<tbody>
																						<tr>
																							<td align='left' colspan='1' style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>Hotel</td>
																							<td align='left' colspan='1' style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>Cat.</td>";





						if($cabecera_1 != ''){																			
							$respuesta .= "														<td style='padding:0cm 0cm 0cm 0cm;' align='right'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$cabecera_1."
																											</span>
																										</b>
																									</p>
																								</td>";
						}	
						if($cabecera_2 != ''){																		
							$respuesta .= "														<td style='padding:0cm 0cm 0cm 0cm;' align='right'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$cabecera_2."
																											</span>
																										</b>
																									</p>
																								</td>";
						}	
						if($cabecera_3 != ''){																		
							$respuesta .= "														<td style='padding:0cm 0cm 0cm 0cm;' align='right'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$cabecera_3."
																											</span>
																										</b>
																									</p>
																								</td>";
						}	

		$respuesta .= "																	</tr>";



			for ($i = 0; $i < count($sHoteles); $i++) {		


				if($sHoteles[$i]['ciudad'] == $ciudad){	

				$respuesta .= " 														<tr>
																							
																							<td align='left' style='font-weight:bold;font-size:12.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:white'>".$sHoteles[$i]['nombre']."</td>	
																							<td align='left'>".$sHoteles[$i]['categoria_3']."</td>";




						if($sHoteles[$i]['precio'] != 0 && $cabecera_1 != ''){																		
							$respuesta .= "															<td align='right' style='font-size:12.0pt;font-																					family:&quot;Arial&quot;,&quot;sans-																					serif&quot;;color:#b9d305'>
																										<p style='margin:0cm;margin-bottom:.0001pt'>
																											<b>
																												<span style='font-size:12.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$sHoteles[$i]['precio']."€
																												</span>
																											</b>
																										</p>
																									</td>";
						}	
						if($sHoteles[$i]['precio_2'] != 0 && $cabecera_2 != ''){																		
							$respuesta .= "															<td  align='right' style='padding:0cm 0cm 0cm 0cm;'>
																										<p style='margin:0cm;margin-bottom:.0001pt'>
																											<b>
																												<span style='font-size:12.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$sHoteles[$i]['precio_2']."€
																												</span>
																											</b>
																										</p>
																									</td>";
						}
						if($sHoteles[$i]['precio_3'] != 0 && $cabecera_3 != ''){																		
							$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='right'>
																										<p style='margin:0cm;margin-bottom:.0001pt'>
																											<b>
																												<span style='font-size:12.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$sHoteles[$i]['precio_3']."€
																												</span>
																											</b>
																										</p>
																									</td>";
						}



							$respuesta .= "														</tr>";
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
													</td>
												</tr>";


			$respuesta .= "					</tbody>
										</table>
									</div>
								</td>
							</tr>
						</tbody>
					</table>


										<table style='width:18.5cm; background:white; padding:0.3cm 0cm 0cm 0cm; border-style:solid; border-color:#b9d305;' border='0' cellpadding='0' cellspacing='0'>
												<tr>
													<td>
														<p style='font-weight:normal;text-align:justify;color:#6f7073;font-size:0.8em;margin:pt 2.0pt pt 11.25pt;'>
															".$texto_pie."
															<u></u>
															<u></u>
														</p>
													</td>
												</tr>
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


