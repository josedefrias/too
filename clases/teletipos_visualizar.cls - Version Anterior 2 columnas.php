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

		
		$resultado =$conexion->query("SELECT t.ciudad ciudad, a.id id_hotel, t.orden orden, a.nombre nombre,  replace(cat.NOMBRE, '*', '☆') categoria,
			a.localidad localidad, t.regimen regimen, t.precio precio, t.precio_2 precio_2,  t.precio_3 precio_3  
			FROM hit_teletipos_hoteles t, hit_alojamientos a, hit_categorias cat 
			WHERE t.hotel = a.id 
				  and a.CATEGORIA = cat.CODIGO
				  and t.ID = '".$id."' ORDER BY t.precio");

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



	function carga_html_oferta($id, $formato, $ciudad){

		$conexion = $this ->Conexion;

		if($ciudad == null){
			$ciudad_por_defecto =$conexion->query("SELECT min(ciudad) ciudad FROM hit_teletipos_aereos WHERE ID = '".$id."'");
			$ociudad_por_defecto = $ciudad_por_defecto->fetch_assoc();
			$ciudad = $ociudad_por_defecto['ciudad'];
		}
		//echo($ciudad);

		//DATOS TELETIPO
		$datos_teletipo =$conexion->query("SELECT t.titulo, 
												  t.cabecera_precio, 
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
		$teletipo_precio_cabecera = $odatos_teletipo['cabecera_precio'];
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

		//DATOS CIUDAD SALIDA
		$datos_aereos =$conexion->query("SELECT  
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
											FROM hit_teletipos_aereos a
											WHERE a.CIUDAD = '".$ciudad."'	and ID = '".$id."'");
		$odatos_aereos = $datos_aereos->fetch_assoc();

		$opcion= $odatos_aereos['opcion'];

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



		//DATOS HOTELES
		$shoteles = $this->Cargar_hoteles($id);


		if($formato == 0){
			$respuesta = "pendiente";
		}

		if($formato == 1){
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

					<body>
					<!--Color Hitravel #b9d305    color azul de fondo cabecera original: #015ec8 color azul de fondo bloques original: #1065af
						color textos y precios original: #ffb302-->

					<p class='MsoNormal' style='text-align:center' align='center'>
						<span style='font-size:7.0pt'>

						</span>
					</p>

					<table style='width:100.0%' border='0' cellpadding='0' cellspacing='0' width='100%'>
						<tbody>
							<tr>
								<td style='background:#dbdbdb;padding:0cm 0cm 0cm 0cm'>
									<div align='center'>
										<table style='width:391.2pt' border='0' cellpadding='0' cellspacing='0' width='652'>
											<tbody>

												
												<!--LOGO HI TRAVEL-->

												<tr>
													<td style='background:white;padding:0cm 0cm 0cm 0cm; border-style:solid; border-color:#b9d305;'>
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
													<td style='background:#b9d305;padding:0cm 0cm 0cm 0cm' >
														<!--<p>-->
															<a href='http://www.hitravel.es' target='_blank'>
																<!--<span style='text-decoration:none'>-->
																	<img src='imagenes/Tenerife_1.jpg' alt='Canarias' border='0' width='660' height='274'>
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
																	<td style='width:65.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='65%'>
																		<p style='margin:0cm;margin-bottom:.0001pt'>
																			<b>
																				<span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:white'>".$teletipo_titulo."
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
																						<span style='color:#b9d305;text-decoration:none'>Desde Madrid</span>
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
																	<!--<td rowspan='2' style='width:8.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='8%'>
																		<p class='MsoNormal'>
																			<u></u>
																			<img class='CToWUd' src='prueba_oferta_files/Ezpl__iwbb2GDM11Pi-wZYjnq-XSAoJMj6scC-oRZGxuxkGAaWmBqV9-eAGK.gif' align='left'>
																			<u></u>
																			<u></u>
																			<u></u>
																		</p>
																	</td>-->
																	<td style='width:27.0%;padding:0cm 0cm 0cm 0cm' valign='top' width='27%'>
																		<p style='margin:0cm;margin-bottom:.0001pt;text-align:right' align='right'>
																			<b><span style='font-size:21.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:white'>Desde
																					<u></u>
																					<u></u>
																				</span>
																			</b>
																		</p>
																		<p style='margin:0cm;margin-bottom:.0001pt;text-align:right' align='right'>
																			<b>
																				<span style='font-size:27.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$teletipo_precio_cabecera."€
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
																							<td align='left' colspan='8' style='font-weight:bold;font-size:12.0pt;'>(Lunes, Martes, Viernes)</td>
																						</tr>
																						<tr>
																							<td align='center'><img class='CToWUd' src='imagenes/transportes/UX_logo.jpg' alt='Canadá' border='0' width='70' height='20'></td>
																								
																							<td align='center'>MAD</td>
																							<td align='center'>-</td>
																							<td align='center'>TFN</td>
																							<td align='center'>UX</td>
																							<td align='center'>9059</td>
																							<td align='center'>07:05</td>
																							<td align='center'>-</td>
																							<td align='center'>08:55</td>
																						</tr>
																						<tr>
																							<td align='center'><img class='CToWUd' src='imagenes/transportes/UX_logo.jpg' alt='Canadá' border='0' width='70' height='20'></td>
																							<td align='center'>TFN</td>
																							<td align='center'>-</td>
																							<td align='center'>MAD</td>
																							<td align='center'>UX</td>
																							<td align='center'>9117</td>
																							<td align='center'>17:50</td>
																							<td align='center'>-</td>
																							<td align='center'>21:40</td>
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

																		<td colspan='2' style='width:100%;background:#1100A7;padding:11.25pt 11.25pt 11.25pt 11.25pt' valign='top'>
																			<div align='center'>
																				<table style='width:100.0%' border='0' cellpadding='0' cellspacing='0'>
																					<tbody>
																						<tr>
																							<td colspan='1' style='padding:0cm 0cm 0cm 0cm' align='left'>

																								<p style='margin-right:0cm;margin-bottom:3.75pt;margin-left:0cm'>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#1100A7'>
																											<a href='http://www.hitravel.es/Ficha_hotel.php?id=".$shoteles[$i]['id_hotel']."' target='_blank'>
																												<span style='color:white;text-decoration:none;font-size:16.0pt;'>".$shoteles[$i]['nombre']."</span>
																											</a>
																										</span>

																										<span style='font-size:14.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#1100A7'>	
																											<a>
																												<span style='color:white;text-decoration:none;font-size:12.0pt;'>&nbsp;&nbsp;&nbsp;".$shoteles[$i]['categoria']."</span>
																											</a>											
																										</span>
																								</p>
																							</td>
																							<td colspan='1' style='padding:0cm 0cm 0cm 0cm' align='right'>
																								<p>
																									<span style='font-size:10.5pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3'>".$shoteles[$i]['localidad']."

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

																		<td style='width:50%;background:#1100A7;padding:0pt 11.25pt 11.25pt 11.25pt' valign='top'>
																			<div align='center'>
																				<table style='width:88.0%' border='0' cellpadding='0' cellspacing='0' width='88%'>
																					<tbody>
																						<tr style='height:86.4pt'>
																							<td colspan='2' style='padding:0cm 0cm 0cm 0cm;height:86.4pt'>
																								<p class='MsoNormal'>
																									<a href='http://www.hitravel.es' target='_blank'>
																										<span style='text-decoration:none'>
																											<img class='CToWUd' src='imagenes/alojamientos/".$shoteles[$i]['id_hotel']."_P_1.jpg' border='0' width='269' height='144'>
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



																		<td style='width:50%;background:#1100A7;padding:0pt 11.25pt 11.25pt 11.25pt' valign='top'>
																			<div align='center'>

																				<table border='0' cellpadding='0' cellspacing='0' align='center' width='100%' style='padding:0.5cm 0cm 0cm 0cm;'>
																					<tbody>
																						<tr style='height:30.4pt'>
																							<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'></span>
																									</b>
																								</p>
																							</td>";

					if($shoteles[$i]['precio'] != 0){																			
						$respuesta .= "														<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;margin-bottom:.0001pt'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$cabecera_1."
																										</span>
																									</b>
																								</p>
																							</td>";
					}	
					if($shoteles[$i]['precio_2'] != 0){																		
						$respuesta .= "														<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;margin-bottom:.0001pt'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$cabecera_2."
																										</span>
																									</b>
																								</p>
																							</td>";
					}	
					if($shoteles[$i]['precio_3'] != 0){																		
						$respuesta .= "														<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;margin-bottom:.0001pt'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$cabecera_3."
																										</span>
																									</b>
																								</p>
																							</td>";
					}	

					$respuesta .= "														</tr>																	
																						<tr style='height:30.4pt'>
																							<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;'>
																									<b>
																										<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['regimen']."</span>
																									</b>
																								</p>
																							</td>";


					if($shoteles[$i]['precio'] != 0){																		
						$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['precio']."€
																											</span>
																										</b>
																									</p>
																								</td>";
					}	
					if($shoteles[$i]['precio_2'] != 0){																		
						$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['precio_2']."€
																											</span>
																										</b>
																									</p>
																								</td>";
					}
					if($shoteles[$i]['precio_3'] != 0){																		
						$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['precio_3']."€
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
																						<tr style='height:47.0pt'>

																							<td colspan='3' style='padding:0cm 0cm 0cm 0cm;height:0pt' align='center' valign='bottom'>
																									<a href='http://www.hitravel.es/Buscar_viajes.php?origen=".$ciudad."&destino=".$destino_cuadro."&producto=".$producto_cuadro."&fecha=".$fecha_busqueda."&noches=".$noches_cuadro."&alojamiento=".$shoteles[$i]['id_hotel']."' target='_blank'>
																										<span style='text-decoration:none'>
																											<img width='240' height='30' src='imagenes/Logo_Informacion_y_Reservas_Hitravel.jpg' border='0'>
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







			/*for ($i = 0; $i < count($shoteles); $i++) {
				

				if($i == 0 || $i == 2 || $i == 4 || $i == 6 || $i == 8 || $i == 10 || $i == 12){

					//echo($i);

					$respuesta .= "					<!--PRIMERA LINEA DE DOBLE HOTEL-->

														<div align='center'>
															<table style='width:100.0%' border='0' cellpadding='0' cellspacing='0' width='100%'>
																<tbody>
																	<tr>

																		<!--BLOQUE IZQUIERDO-->

																		<td style='background:#1100A7;padding:11.25pt 11.25pt 11.25pt 11.25pt' valign='top'>
																			<div align='center'>
																				<table style='width:88.0%' border='0' cellpadding='0' cellspacing='0' width='88%'>
																					<tbody>
																						<tr style='height:86.4pt'>
																							<td colspan='2' style='padding:0cm 0cm 0cm 0cm;height:86.4pt'>
																								<p class='MsoNormal'>
																									<a href='http://www.hitravel.es' target='_blank'>
																										<span style='text-decoration:none'>
																											<img class='CToWUd' src='imagenes/alojamientos/".$shoteles[$i]['id_hotel']."_P_1.jpg' border='0' width='269' height='144'>
																										</span>
																									</a>
																									<u></u>
																									<u></u>
																								</p>
																							</td>
																						</tr>
																						<tr>
																							<td colspan='2' style='padding:0cm 0cm 0cm 0cm'>
																								<p style='margin-right:0cm;margin-bottom:3.75pt;margin-left:0cm'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#1100A7'>
																											<a href='http://www.hitravel.es/Ficha_hotel.php?id=".$shoteles[$i]['id_hotel']."' target='_blank'>
																												<span style='color:white;text-decoration:none;font-size:15.0pt;'>".$shoteles[$i]['nombre']."</span>
																											</a>
																											<br>
																											<a>
																												<span style='color:white;text-decoration:none;font-size:12.0pt;'>".$shoteles[$i]['categoria']."</span>
																											</a>																						
																											<u></u>
																											<u></u>
																										</span>
																									</b>
																								</p>
																							</td>
																						</tr>
																						<tr>
																							<td colspan='1' style='padding:0cm 0cm 0cm 0cm'>																	
																								<p style='margin:0cm;margin-bottom:.0001pt'>

																									<span style='font-size:10.5pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3'>".$shoteles[$i]['localidad']."
																										<u></u>
																										<u></u>
																									</span>
																							</td>																				
																							<td colspan='1' style='padding:0.1cm 0cm 0cm 0cm' align='right'>	
																								<!--<a align='right' href='' target='_blank'>-->
																								<a align='right' href='' target='_blank'>
																											<img width='50' height='18' src='imagenes/ver_pdf.gif' border='0'>
																								</a>
																							</td>
																						</tr>
																					</tbody>
																				</table>


																				<table border='0' cellpadding='0' cellspacing='0' align='center' width='95%' style='padding:0.5cm 0cm 0cm 0cm;'>
																					<tbody>
																						<tr>
																							<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'></span>
																									</b>
																								</p>
																							</td>
																							<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;margin-bottom:.0001pt'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>Julio
																										</span>
																									</b>
																								</p>
																							</td>
																							<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;margin-bottom:.0001pt'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>Agosto
																										</span>
																									</b>
																								</p>
																							</td>

																						</tr>																	
																						<tr>
																							<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;'>
																									<b>
																										<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['regimen']."</span>
																									</b>
																								</p>
																							</td>";


					if($shoteles[$i]['precio'] != 0){																		
						$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['precio']."€
																											</span>
																										</b>
																									</p>
																								</td>";
					}	
					if($shoteles[$i]['precio_2'] != 0){																		
						$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['precio_2']."€
																											</span>
																										</b>
																									</p>
																								</td>";
					}
					if($shoteles[$i]['precio_3'] != 0){																		
						$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['precio_3']."€
																											</span>
																										</b>
																									</p>
																								</td>";
					}


					$respuesta .= "														</tr>
																						<tr style='height:33.6pt'>

																							<td colspan='3' style='padding:0.5cm 0cm 0cm 0cm;height:33.6pt' align='center'>
																									<a href='http://www.hitravel.es/Buscar_viajes.php?origen=BIO&destino=TCISUR&producto=VAC&fecha=25-7-2015&noches=7&alojamiento=2449' target='_blank'>
																										<span style='text-decoration:none'>
																											<img width='100' height='25' src='imagenes/informacion_y_reservas.gif' border='0'>
																										</span>
																									</a>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</div>
																		</td>

																		<!--ESPACIO ENTRE BLOQUES DE LA MISMA LINEA-->

																		<td style='width:9.0pt;padding:0cm 0cm 0cm 0cm' width='15'>
																			<p class='MsoNormal'>&nbsp;
																				<u></u>
																				<u></u>
																			</p>
																		</td>";

				}else{

					$respuesta .= "
																		<!--BLOQUE DERECHO-->

																		<td style='background:#1100A7;padding:11.25pt 11.25pt 11.25pt 11.25pt' valign='top'>
																			<div align='center'>
																				<table style='width:88.0%' border='0' cellpadding='0' cellspacing='0' width='88%'>
																					<tbody>
																						<tr style='height:86.4pt'>
																							<td colspan='2' style='padding:0cm 0cm 0cm 0cm;height:86.4pt'>
																								<p class='MsoNormal'>
																									<a href='http://www.hitravel.es' target='_blank'>
																										<span style='text-decoration:none'>
																											<img class='CToWUd' src='imagenes/alojamientos/".$shoteles[$i]['id_hotel']."_P_1.jpg' border='0' width='269' height='144'>
																										</span>
																									</a>
																									<u></u>
																									<u></u>
																								</p>
																							</td>
																						</tr>
																						<tr>
																							<td colspan='2' style='padding:0cm 0cm 0cm 0cm'>
																								<p style='margin-right:0cm;margin-bottom:3.75pt;margin-left:0cm'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#1100A7'>
																											<a href='http://www.hitravel.es/Ficha_hotel.php?id=".$shoteles[$i]['id_hotel']."' target='_blank'>
																												<span style='color:white;text-decoration:none;font-size:15.0pt;'>".@$shoteles[$i]['nombre']."</span>
																											</a>
																											<br>
																											<a>
																												<span style='color:white;text-decoration:none;font-size:12.0pt;'>".$shoteles[$i]['categoria']."</span>
																											</a>																						
																											<u></u>
																											<u></u>
																										</span>
																									</b>
																								</p>
																							</td>
																						</tr>
																						<tr>
																							<td colspan='1' style='padding:0cm 0cm 0cm 0cm'>																	
																								<p style='margin:0cm;margin-bottom:.0001pt'>

																									<span style='font-size:10.5pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#7ac6f3'>".$shoteles[$i]['localidad']."
																										<u></u>
																										<u></u>
																									</span>
																							</td>																				
																							<td colspan='1' style='padding:0.1cm 0cm 0cm 0cm' align='right'>	
																								<a align='right' href='' target='_blank'>
																											<img width='50' height='18' src='imagenes/ver_pdf.gif' border='0'>
																								</a>
																							</td>
																						</tr>
																					</tbody>
																				</table>


																				<table border='0' cellpadding='0' cellspacing='0' align='center' width='95%' style='padding:0.5cm 0cm 0cm 0cm;'>
																					<tbody>
																						<tr>
																							<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'></span>
																									</b>
																								</p>
																							</td>
																							<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;margin-bottom:.0001pt'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>Julio
																										</span>
																									</b>
																								</p>
																							</td>
																							<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;margin-bottom:.0001pt'>
																									<b>
																										<span style='font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>Agosto
																										</span>
																									</b>
																								</p>
																							</td>

																						</tr>																	
																						<tr>
																							<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																								<p style='margin:0cm;'>
																									<b>
																										<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['regimen']."</span>
																									</b>
																								</p>
																							</td>";


					if($shoteles[$i]['precio'] != 0){																		
						$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['precio']."€
																											</span>
																										</b>
																									</p>
																								</td>";
					}	
					if($shoteles[$i]['precio_2'] != 0){																		
						$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['precio_2']."€
																											</span>
																										</b>
																									</p>
																								</td>";
					}
					if($shoteles[$i]['precio_3'] != 0){																		
						$respuesta .= "															<td style='padding:0cm 0cm 0cm 0cm;' align='center'>
																									<p style='margin:0cm;margin-bottom:.0001pt'>
																										<b>
																											<span style='font-size:18.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#b9d305'>".$shoteles[$i]['precio_3']."€
																											</span>
																										</b>
																									</p>
																								</td>";
					}


					$respuesta .= "													</tr>
																						<tr style='height:33.6pt'>

																							<td colspan='3' style='padding:0.5cm 0cm 0cm 0cm;height:33.6pt' align='center'>
																									<a href='http://www.hitravel.es/Buscar_viajes.php?origen=BIO&destino=TCISUR&producto=VAC&fecha=25-7-2015&noches=7&alojamiento=2449' target='_blank'>
																										<span style='text-decoration:none'>
																											<img width='100' height='25' src='imagenes/informacion_y_reservas.gif' border='0'>
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

														<p style='text-align:center' align='center'>
															<span style='font-size:0.1pt'>
																<u></u>
															</span>
														</p>";
				}
			}*/	

			}



			$respuesta .= "							</td>
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
		$mensaje_html = $this->carga_html_oferta($id, $teletipo_formato, $ciudad);	

		$asunto = "HITRAVEL - OFERTAS";
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