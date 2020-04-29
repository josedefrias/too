<?php
# Cargamos la librería dompdf.
	require_once 'dompdf_config.inc.php';
 	include 'funciones_php/conexiones.php';
	//include 'funciones_php/loggin.php';
	//require 'clases/general.cls.php';
	require 'clases/clases_web/Reservas_fin.cls.php';

$parametrosg = $_GET;

$localizador = $parametrosg['loc'];


$fecha_emision = date("d/m/Y"); 
$fecha_impresion = date("d-m-Y"); 
$hora_impresion = date("H:i"); 

$politica_cancelacion = "";
$observaciones = "";

$conexion = conexion_hit();



					//OBTENEMOS NOMBRE DEL VIAJE Á
					$datos_nombre_viaje =$conexion->query("select p.nombre nombre, p.producto producto
															from hit_producto_cuadros p, hit_reservas r 
															where 
															p.FOLLETO = r.FOLLETO
															and p.CUADRO = r.CUADRO
															and r.LOCALIZADOR = '".$localizador."'");
					$onombre_viaje = $datos_nombre_viaje->fetch_assoc();
					$nombre_viaje = $onombre_viaje['nombre'];
					if($localizador == 100674){
						$nombre_viaje = 'Solo vuelo Fuerteventura';
					}
					
					$producto = $onombre_viaje['producto'];

					//OBTENEMOS SALIDA, REGRESO, ESTADO
					$datos_salida_viaje =$conexion->query("select DATE_FORMAT(fecha_salida, '%d-%m-%Y') AS fecha_salida, DATE_FORMAT(fecha_regreso, '%d-%m-%Y') AS fecha_regreso, 
					DATE_FORMAT(fecha_reserva, '%d-%m-%Y') AS fecha_reserva,
					case situacion 
						when 'P' then 'Servicios pendientes'
						when 'F' then 'Servicios confirmados'
					end situacion,
					(pax + bebes) total_pax,
					DATEDIFF(fecha_regreso,fecha_salida) + 1 duracion_viaje,
					observaciones_clientes
					from hit_reservas where localizador = '".$localizador."'");
					$odatos_salida_viaje = $datos_salida_viaje->fetch_assoc();
					$datos_salida = $odatos_salida_viaje['fecha_salida'];
					$datos_regreso = $odatos_salida_viaje['fecha_regreso'];
					$datos_situacion = $odatos_salida_viaje['situacion'];
					$fecha_reserva = $odatos_salida_viaje['fecha_reserva'];
					$total_pax = $odatos_salida_viaje['total_pax'];
					$duracion_viaje = $odatos_salida_viaje['duracion_viaje'];
					$observaciones_clientes = $odatos_salida_viaje['observaciones_clientes'];

					//OBTENEMOS TIPO DE SEGURO (OPCIONAL)
					$datos_seguro_opcional =$conexion->query("select count(*) hay_seguro_opcional from hit_reservas_servicios s, hit_servicios sv
															where
																s.ID_PROVEEDOR = sv.ID_PROVEEDOR
																and s.CODIGO = sv.CODIGO
																and sv.TIPO = 'SEG'
																and s.tipo = 'O'
																and s.localizador = '".$localizador."'");					
					$odatos_seguroa_opcional = $datos_seguro_opcional->fetch_assoc();
					$hay_seguro_opcional = $odatos_seguroa_opcional['hay_seguro_opcional'];
					
						//Si hay opcional aveiguamos si es de solo vuelo de paquete
					if($hay_seguro_opcional > 0){
						$datos_seguro_opcional_tipo =$conexion->query("select s.CODIGO seguro_opcional_tipo from hit_reservas_servicios s, hit_servicios sv
																where
																	s.ID_PROVEEDOR = sv.ID_PROVEEDOR
																	and s.CODIGO = sv.CODIGO
																	and sv.TIPO = 'SEG'
																	and s.tipo = 'O'
																	and s.localizador = '".$localizador."'");					
						$odatos_seguroa_opcional_tipo = $datos_seguro_opcional_tipo->fetch_assoc();
						$seguro_opcional_tipo = $odatos_seguroa_opcional_tipo['seguro_opcional_tipo'];					
					
					}
					
					//OBTENEMOS TIPO DE SEGURO (BASICO)
					$datos_seguro_incluido =$conexion->query("select count(*) hay_seguro_incluido from hit_reservas_servicios s, hit_servicios sv
															where
																s.ID_PROVEEDOR = sv.ID_PROVEEDOR
																and s.CODIGO = sv.CODIGO
																and sv.TIPO = 'SEG'
																and s.tipo = 'I'
																and s.localizador = '".$localizador."'");					
					$odatos_seguroa_incluido = $datos_seguro_incluido->fetch_assoc();
					$hay_seguro_incluido = $odatos_seguroa_incluido['hay_seguro_incluido'];					

					$oReservas_fin = new clsReservas_fin($conexion, $localizador);
					$sdatos_agencia = $oReservas_fin->Cargar_datos_agencia($localizador);
					$sdatos_pasajeros = $oReservas_fin->Cargar_pasajeros();
					$sServicios = $oReservas_fin->Cargar_servicios();
					$sAereos = $oReservas_fin->Cargar_aereos();
					$hay_aereos = count($sAereos);
					$sNombre_hotel = $oReservas_fin->Cargar_nombre_hotel();
					$sPeriodo_hotel = $oReservas_fin->Cargar_periodo_hotel();
					$shoteles = $oReservas_fin->Cargar_hoteles();
					$sdesglose = $oReservas_fin->Cargar_desglose('L');
					$spvp = $oReservas_fin->Cargar_pvp('L');
					$sdatos_hoteles = $oReservas_fin->Cargar_datos_hoteles();

				
					//CABECERA, AGENCIA, PASAJEROS Y SERVICIOS
					$mensaje_html = "
					<html lang='es'>
					<head>	
						<meta charset='utf-8'/>
			
					</head>

					<body>
			
					<script type=\"text/php\"> 
						if ( isset(\$pdf) ) { 
							 \$font = Font_Metrics::get_font(\"helvetica\", \"bold\"); 
							\$pdf->page_text(520, 820, \"Pagina: {PAGE_NUM} de {PAGE_COUNT}\", \$font, 6, array(0,0,0)); 
							\$pdf->page_text(480, 18, \"Fecha: ".$fecha_impresion."  Hora: ".$hora_impresion."\", \$font, 6, array(0,0,0)); 
						} 
					</script> 	
					
					<div style='height:1000px;'>

					<img src='imagenes/Logo_Mail2.jpg' align='center' height='45' width='250'>

					<h1 style='font-size:25px;font-family:arial,serif;line-height:120%;color:#000000;margin:25px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'><strong>Documentacion de la reserva: ".$localizador."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Fecha de emisión: <strong>".$fecha_emision."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Viaje: <strong>".$nombre_viaje."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Salida: <strong>".$datos_salida."</strong>&nbsp;&nbsp;&nbsp;Regreso: <strong>".$datos_regreso."</strong>&nbsp;&nbsp;&nbsp;<strong>".$datos_situacion."</strong></h1>

					
					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-agencia-docu.gif' height='20' width='20'> Agencia</h1>

					<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>	

							<p style='font-size:15px;font-family:arial,serif;'><strong>".$sdatos_agencia[0]['a_nombre']."</strong></p>
							
							<p style='font-size:15px;font-family:arial,serif;'> Dirección: ".$sdatos_agencia[0]['a_direccion']." - (".$sdatos_agencia[0]['a_c_postal'].") ".$sdatos_agencia[0]['a_localidad']."</p>
							
							<p style='font-size:15px;font-family:arial,serif;'> Telefono: ".$sdatos_agencia[0]['a_telefono']." - Mail: ".$sdatos_agencia[0]['a_email']."</p>
							<p style='font-size:15px;font-family:arial,serif;'> Agente: ".$sdatos_agencia[0]['a_agente']." - Referencia agencia: ".$sdatos_agencia[0]['a_referencia_agencia']."</p>

							<p style='font-size:15px;font-family:arial,serif;'>Observaciones: ".$observaciones_clientes."</p>

					</DIV>



					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-pasajeros-docu.gif' height='20' width='20'> Pasajeros</h1>

					<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	
						for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

							$mensaje_html .= "<p  style='font-size:13px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
							".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

							//if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
								$mensaje_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
							//}
							$mensaje_html .= "</p>";
							
						}
					$mensaje_html .= "</DIV>";

					//SERVICIOS
					$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Resumen de los servicios</h1>";

					//AEREOS
					if($hay_aereos > 0){
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-aereos-docu.gif' height='20' width='20'> Aereos</h1>";

						$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	

							for ($i = 0; $i < count($sAereos); $i++) {
									$mensaje_html .= "<h2 style='font-size:1px;font-family:arial,serif;line-height:80%;color:#FFFFFF;font-weight:normal;text-align:left;'>-</h2>

									
								  <span style='font-size:13px;font-family:arial,serif;'><img src='imagenes/transportes/".$sAereos[$i]['v_codigo_cia']."_logo.jpg' alt='logo compañía aérea' height='20' width='60' />  &nbsp;&nbsp;Numero: <strong>".$sAereos[$i]['v_vuelo']."</strong>, <strong>".$sAereos[$i]['v_origen']." - ".$sAereos[$i]['v_destino']."</strong></span>

									<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sAereos[$i]['v_fecha']."</strong>,<strong>  ".$sAereos[$i]['v_salida']." hs -  ".$sAereos[$i]['v_llegada']." hs</strong></span>

									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;Pasajeros: <strong>".$sAereos[$i]['v_pax']."</strong></span>

									<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sAereos[$i]['v_disponibilidad']."</strong></span>";

							}
						$mensaje_html .= "</DIV>";
					}
					
					//ECHO($parametros['buscar_producto']);
					if ($producto != 'SVO' and $producto != 'OSV'){

						//ALOJAMIENTOS
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-alojamientos-docu.gif' height='20' width='20'> Alojamientos</h1>";

						$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	

							$mensaje_html .= "<h2 style='font-size:18px;font-family:arial,serif;line-height:120%;color:#000000;font-weight:normal;text-align:left;'><strong>".$sNombre_hotel[0]['h_nombre']."</strong></h2>";
							$mensaje_html .= "<p style='font-size:15px;font-family:arial,serif;'><strong>".$sPeriodo_hotel[0]['h_periodo']."</strong></p>";

							for ($i = 0; $i < count($shoteles); $i++) {

									$mensaje_html .= "<span style='font-size:13px;font-family:arial,serif;'><strong>".$shoteles[$i]['h_caracteristica']."</strong>
									- ".$shoteles[$i]['h_pax']." ".$shoteles[$i]['h_desglose_pax'].".
									".$shoteles[$i]['h_texto_regimen']." <strong>".$shoteles[$i]['h_regimen']."</strong></span>

									<span><strong>".$shoteles[$i]['h_estado']."</strong></span><br><br>";

									$politica_cancelacion .= $shoteles[$i]['h_politica_cancelacion'];
									$observaciones .= $shoteles[$i]['h_observaciones'];

							}
							if($sNombre_hotel[0]['h_comunidad'] == 'BAL' and $observaciones == ""){
								$observaciones = 'TASA ECOLOGICA: Todos los clientes con estancia en este establecimiento a partir del 01.07.16 (incluidos aquellos que hayan iniciado su estancia con anterioridad) se ven afectados por la obligatoriedad del pago del impuesto (ECOTASA) aprobado por el Gobierno Balear. Dicho pago deberá ser efectuado directamente en el establecimiento y en función de la categoría oficial del mismo. Información detallada en: www.caib.es/eboibfront/es/2016/10470/578257/ley-2-2016-de-30-de-marzo-del-impuesto-sobre-estan';
							}

							
						$mensaje_html .= "</DIV>";

					}

					//SERVICIOS
					if(count($sServicios) > 0){
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-servicios-docu.gif' height='20' width='20'> Servicios</h1>";

						$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px; margin:10px 15px 15px; background:#e6e6e6; padding:10px 5px 5px;clear:both;line-height:10px'>";	


							for ($i = 0; $i < count($sServicios); $i++) {

									/*$mensaje_html .= "<span style='font-size:13px;font-family:arial,serif;'>Servicio <strong>".$sServicios[$i]['s_nombre']."</strong></span>
									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;Fecha <strong>".$sServicios[$i]['s_fecha']."</strong></span>
									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;".$sServicios[$i]['s_pax']." Personas</span>
									<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sServicios[$i]['s_estado']."</strong></span><br><br>";*/


									$mensaje_html .= "<span style='font-size:13px;font-family:arial,serif;'>Servicio <strong>".$sServicios[$i]['s_nombre']."</strong></span>
									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;Fecha <strong>".$sServicios[$i]['s_fecha']."</strong></span>
									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;".$sServicios[$i]['s_pax']." Personas ";
									if($sServicios[$i]['s_veces'] > 1){
										$mensaje_html .= $sServicios[$i]['s_veces']." Días";
									}
									$mensaje_html .= "</span>
									<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sServicios[$i]['s_estado']."</strong></span><br><br>";

							}
						$mensaje_html .= "</DIV>";					
					}				
					
					$mensaje_html .= "</DIV>";


					//CANCELACIONES Y NOTAS IMPORTANTES
					$mensaje_html .= "<div style='page-break-after: always; height:980px; -moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;line-height:10px'>
											<img src='imagenes/Logo_Mail.jpg' align='center' height='30' width='150'>";
						
						if ($producto == 'OVA' || $producto == 'OSV'){						
						$mensaje_html .= "<h1 style='font-size:0.8em;
												font-family:arial,serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:left;
												clear:both;'>INFORMACION AÉREO - VUELOS CON RYANAIR</h1>
											<p style='font-size:0.8em;
												font-family:serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Es imprescindible que realices la facturación on-line e imprimas las tarjetas de embarque siguiendo el enlace indicado en el correo de confirmación que te enviamos de la compañía aérea o bien entrando directamente en la página de facturación de RyanAir seleccionando la opción 1, 2 o 3 y sigue con el resto de pasos. Si no facturas on-line, RyanAir te cobrará un gasto adicional en el aeropuerto antes de embarcar. Facturación en línea: Asignación general de asientos - Los clientes que no deseen pagar por un asiento asignado Premium o Regular pueden facturar online entre 7 días y 2 horas antes de cada vuelo y se le asignará un asiento de forma gratuita. Facturación online por adelantado - (desde 30 días hasta 8 días antes de cada vuelo) sólo está disponible para los clientes que eligen comprar un asiento asignado.</p>
																	
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Recuerda que los gastos de equipaje y/o tarjeta de crédito del vuelo, no incluidos en estos importes de cancelación, no son en ningún caso reembolsables.</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											La reserva puede ser cancelada o modificada enviando un e-mail a info@hitravel.es se deberá recibir en la Agencia en horario laboral de Lunes a Viernes de 9 a 19 hrs y Sábados de 9:30 a 13.30 hrs, para que se considere como efectiva. En caso contrario, la fecha de anulación o modificación se entenderá como efectiva a partir del siguiente día laborable. <br>
											</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											- Este presupuesto está sujeto a disponibilidad de plazas y tarifa en el momento de efectuar la reserva<br>
											- <strong>Este presupuesto no contempla los suplementos que la compañía aérea puede cargar en función del equipaje.</strong><br>
											- Es responsabilidad del viajero disponer de la documentación en regla necesaria para poder viajar al destino solicitado
											</p>";				
						}



						
						$mensaje_html .= "<h1 style='font-size:0.8em;
												font-family:arial,serif;
												line-height:120%;
												color:#6f7073;
												margin:15px 40px 5px;
												font-weight:normal;
												text-align:left;
												clear:both;'>POLITICA DE CANCELACION/MODIFICACION</h1>
											<p style='font-size:0.8em;
												font-family:serif;
												line-height:120%;
												color:#6f7073;
												margin:15px 40px 5px;
												font-weight:normal;
												text-align:justify;'>- Vuelos que sean del grupo Iberia y Air Europa : emisión 10 días antes de la salida, excepto con Air Nostrum que la emisión será 10 días tras la confirmación de reserva, 100% gastos aéreo en el momento de la emisión.<br>
												- Vuelos que no sean del grupo Iberia emisión 24 horas después de la confirmación de la reserva 100% de gastos.<br
												- Vuelos low cost: emisión inmediata 100% gastos en el momento de confirmación de la reserva. La agencia Minorista deberá realizar el pago del aéreo el mismo día de la confirmación de reserva, enviado comprobante de pago a info@hitravel.es</p>";
						if($politica_cancelacion != ""){
							$mensaje_html .= "<p style='font-size:0.8em;
												font-family:serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>- Alojamientos: ".$politica_cancelacion."</p>";
						}


							$mensaje_html .= "<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:15px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Los gastos de gestión más los gastos de anulación, si los hubiere <br>
												- Los gastos de gestión por reserva, modificación total* y cancelación de los servicios solicitados se aplicarán en función del tiempo que medie desde la creación de la reserva, según el siguiente escalado:<br>
												- Hasta las 72 h posteriores desde la creación de la reserva: Sin gastos<br>
												- A partir de 72 h y hasta 7 días naturales desde la creación de la reserva: 25€<br>
												- Más de 7 días naturales desde la creación de la reserva: 55 €</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:15px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											- Una penalización, consistente en el 5% del total del viaje si el desistimiento se produce con más de diez días naturales y menos de quince días de antelación a la fecha del comienzo del viaje<br>
											- Una penalización consistente en el 15% entre los días 3 y 10, <br>
											- Una penalización consistente en el 25% dentro de las cuarenta y ocho horas anteriores a la salida. .  
											</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:15px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											- De no presentarse a la hora prevista para la salida, no tendrá derecho a devolución alguna de la cantidad abonada, salvo acuerdo entre las partes en otro sentido.<br>
											*Modificaciones totales: Cambio de todos los nombres de la reserva, cambio de destino, cambio de las dos fechas de viaje y cambio de tipo de venta.<br>
											Las reservas confirmadas entre 7 días y 2 días antes de la fecha de inicio del viaje, dispondrán de 24 hrs. para cancelar sin gastos excepto vuelos low cost que será el 100% de la emisión de los vuelos. Transcurrido dicho plazo se aplicarán los gastos de gestión arriba indicados, más los gastos de cancelación. Dentro de las 48 hrs. anteriores a la fecha de inicio del viaje se aplicarán los gastos generales.
											</p>

											<h1 style='font-size:0.8em;
														line-height:120%;
														color:#000000;
														margin:15px 40px 5px;
														font-weight:normal;
														text-align:left;
														clear:both;'>POLITICA CANCELACION GRUPOS</h1>
											
											<table style='margin:0px 20px 20px 40px; border: 1px solid #000;
												border-collapse: collapse;font-size:11px;font-family:arial,serif;color:#000000;font-weight:normal;text-align:left;'>
												<tr>
													<td></td>
													<td colspan='2' style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>Cancelación Parcial</td>
													<td rowspan='2' style='border: 1px solid #000;padding:0px 5px 0px 5px;text-align:center;'>Cancelación Total</td>
												</tr>
												<tr>
													<td></td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>-20 %Pax</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>+20 %Pax</td>
												</tr>												
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 60 a 45 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>5%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>10%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 44 a 31 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>10%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>20%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 30 a 21 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>25%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>30%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 20 a 11 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>30%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>50%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>40%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>Menos de 10 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
												</tr>
											</table>

											<h1 style='font-size:0.8em;
														line-height:120%;
														color:#000000;
														margin:15px 40px 5px;
														font-weight:normal;
														text-align:left;
														clear:both;'>NOTAS IMPORTANTES</h1>";

							if($observaciones != ""){
								$mensaje_html .= "<p style='font-size:0.8em;
													font-family:serif;
													line-height:120%;
													color:#6f7073;
													margin:20px 40px 5px;
													font-weight:normal;
													text-align:justify;'>- Alojamientos: ".$observaciones."</p>";
							}




							$mensaje_html .= "<p style='font-size:0.8em;
														line-height:120%;
														color:#6f7073;
														margin:15px 40px 5px;
														font-weight:normal;
														text-align:justify;'>Precio y carburante el precio del viaje combinado ha sido calculado segun los tipos de cambio, tarifas de transporte, coste del carburante y tasas e impuestos aplicables en la fecha de inicio de la reserva. Cualquier variacion del precio de los citados elementos podra dar lugar a la revision del precio final del viaje comunicandolo con 21 dias de antelacion a la salida.</p>
											<p style='font-size:0.8em;
														line-height:120%;
														color:#6f7073;
														margin:15px 40px 5px;
														font-weight:normal;
														text-align:justify;'>Los servicios reflejados en este presupuesto están pendientes de disponibilidad a la hora de efectuar la reserva. Se reconfirmarán los precios en el momento de confirmar la reserva. Los precios están cotizados en base a las tarifas y cambios de moneda vigentes a día de hoy, estando sujetos a cambios por posibles incrementos en el precio del combustible y clases aéreas disponibles. Todos los precios reflejados en este presupuesto son precios de venta al público.
														</p>
											<p style='font-size:0.8em;
														line-height:120%;
														color:#6f7073;
														margin:15px 40px 5px;
														font-weight:normal;
														text-align:justify;'>La realización de la reserva en firme implica la aceptación de la política de gastos de hi travel. Véase condiciones de gastos en nuestra web www.hitravel.es</p>
											</div>";

					//BONO DEL ALOJAMIENTO		
					if ($producto != 'SVO' and $producto != 'OSV' and $producto != 'SSV'){		

						$mensaje_html .= "
						
						<div style='page-break-after: always; height:1000px; -moz-border-radius:15px;border-radius:15px; border: 1px solid #000;'>
						

						
						<div style='clear:left; margin: 10px 10px;'>
							<div style='float:left; width:170; padding: 2px 5px; margin: 2px 5px;'><img src='imagenes/Logo_Mail2.jpg' align='center' height='40' width='225'></div>
							
							<div style='float:left; border: 1px solid #000; width:290; font-size:14px;font-family:arial,serif;color:#000000; padding: 5px 5px; margin: 10px 5px; font-weight:normal;text-align:center; valign:center;'><strong>BONO PARA ENTREGAR A SU LLEGADA AL HOTEL</strong></div>
						</div>

						<div style='margin:20px 15px; clear:both;line-height:10px font-size:14px;font-family:arial,serif;color:#FFFFFF;'> - </div>					
						
						<div style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:20px 15px; padding:10px 10px; font-size:20px;font-family:arial,serif; background:#B9D305; clear:both;line-height:20px'>Reserva Hi Travel: <strong>".$localizador."</strong></div>
						
						<div style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:20px 15px; padding:10px 10px; font-size:20px;font-family:arial,serif; background:#B9D305; clear:both;line-height:20px'>Alojamiento: <strong>".$sNombre_hotel[0]['h_nombre']."</strong></div>

						<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-pasajeros-docu.gif' height='25' width='25'><strong>  Pasajeros</strong></h1>

						<DIV style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:20px 15px;background:#e6e6e6;padding:10px 15px;clear:both;line-height:10px'>";	
						
						for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

							$mensaje_html .= "<p  style='font-size:13px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
							".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

							if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
								$mensaje_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
							}
							$mensaje_html .= "</p>";
							
						}
						$mensaje_html .= "</DIV>";


						//ALOJAMIENTOS
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-alojamientos-docu.gif' height='25' width='25'><strong>  Detalle Alojamiento</strong></h1>";

						$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:5px 15px;background:#e6e6e6; padding:5px 15px; clear:both;line-height:10px'>";	

							$mensaje_html .= "<h2 style='font-size:18px;font-family:arial,serif;line-height:120%;color:#000000;font-weight:normal;text-align:left;'><strong>".$sNombre_hotel[0]['h_nombre']."</strong></h2>";
							$mensaje_html .= "<p style='font-size:15px;font-family:arial,serif;'><strong>".$sPeriodo_hotel[0]['h_periodo']."</strong></p>";

							$reservado_pagado = "RESERVADO Y PAGADO POR HI TRAVEL - VIAJES Y OCIO HITS S.L.";

							$pongase_contacto = "PARA CUALQUIER DUDA O CONSULTA PONGASE EN CONTACTO CON NUESTRA OFICINA EN CANARIAS. TFNO: 634 802 903. O EN WWW.HITRAVEL.ES";

							for ($i = 0; $i < count($shoteles); $i++) {

								$mensaje_html .= "<span style='font-size:13px;font-family:arial,serif;'><strong>".$shoteles[$i]['h_caracteristica']."</strong>
								- ".$shoteles[$i]['h_pax']." ".$shoteles[$i]['h_desglose_pax'].".
								".$shoteles[$i]['h_texto_regimen']." <strong>".$shoteles[$i]['h_regimen']."</strong></span>

								<span><strong>".$shoteles[$i]['h_estado']."</strong></span><br><br>";

								if($shoteles[$i]['h_interfaz_codigo'] != ""){
									$reservado_pagado = "RESERVADO Y PAGADO POR ".$shoteles[$i]['h_interfaz_codigo'].".<br>Localizador de la reserva del alojamiento: ".$shoteles[$i]['h_interfaz_localizador']."<br>".$shoteles[$i]['h_observaciones'];
								}
							}
						$mensaje_html .= "</DIV>";

						//DATOS ALOJAMIENTO
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><strong>Datos Alojamiento</strong></h1>";

						$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:5px 15px;background:#e6e6e6; padding:5px 15px; clear:both;line-height:10px'>";	

							$telefono_contacto = '';
							for ($i = 0; $i < count($sdatos_hoteles); $i++) {

									$mensaje_html .= "<div style='font-size:13px;font-family:arial,serif; margin:15px 15px;'>Direccion: <strong>".$sdatos_hoteles[$i]['dh_direccion']."</strong></div>

									<div style='font-size:13px;font-family:arial,serif; margin:15px 15px 3px;'>Telefono: <strong>".$sdatos_hoteles[$i]['dh_telefono']."</strong> - Web: <strong>".$sdatos_hoteles[$i]['dh_url']."</strong></div><br><br>";

									$telefono_contacto = $sdatos_hoteles[$i]['dh_telefono_contacto'];
							}
						$mensaje_html .= "</DIV>";	


						$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #B9D305; margin:20px 15px; padding:10px 10px; font-size:12px;font-family:arial,serif; background:#FFFFFF; clear:both;line-height:20px'><strong>".$reservado_pagado."</strong></div>";

						$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #B9D305; margin:20px 15px; padding:10px 10px; font-size:12px;font-family:arial,serif; background:#FFFFFF; clear:both;line-height:20px'>PARA CUALQUIER CONSULTA PONGASE EN CONTACTO CON NUESTRA OFICINA EN EL TFNO: ".$telefono_contacto.". O EN WWW.HITRAVEL.ES</div>";	

						$mensaje_html .= "<div style='float:right; -moz-border-radius:15px; border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 200px; height: 70px; margin: 10px 15px;'>
												<div style='float:left; font:10px Arial, Sans-serif; margin:5px 10px; width:220px;'>Sello de la agencia</div>
										</div>";

					}
					$mensaje_html .= "</DIV>";											
											


					//BONO DE AEREOS	
					if($hay_aereos > 0){
						if ($producto != 'SHO' and $producto != 'SSV'){		
						
						$mensaje_html .= "
						
						<div style='height:980px; -moz-border-radius:15px;border-radius:15px; border: 1px solid #000; padding: 10px 0px; margin: 5px 0px;'>
						

						
						<div style='clear:left; margin: 10px 10px;'>
							<div style='float:left; width:170; padding: 2px 5px; margin: 2px 5px;'><img src='imagenes/Logo_Mail2.jpg' align='center' height='40' width='225'></div>
							
							<div style='float:left; border: 1px solid #000; width:290; font-size:14px;font-family:arial,serif;color:#000000; padding: 5px 5px; margin: 10px 5px; font-weight:normal;text-align:center; valign:center;'><strong>BONO DE SERVICIOS AEREOS</strong></div>
						</div>

						<div style='margin:20px 15px; clear:both;line-height:10px font-size:14px;font-family:arial,serif;color:#FFFFFF;'> - </div>					
						
						<div style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:20px 15px; padding:10px 10px; font-size:20px;font-family:arial,serif; background:#B9D305; clear:both;line-height:20px'>Reserva Hi Travel: <strong>".$localizador."</strong></div>
						

						<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-pasajeros-docu.gif' height='25' width='25'><strong>  Pasajeros</strong></h1>

						<DIV style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:20px 15px;background:#e6e6e6;padding:10px 15px;clear:both;line-height:10px'>";	
							for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

								$mensaje_html .= "<p  style='font-size:13px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
								".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

								if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
									$mensaje_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
								}
								$mensaje_html .= "</p>";
								
							}
						$mensaje_html .= "</DIV>";



							//AEREOS
							$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-aereos-docu.gif' height='25' width='25'><strong> Aereos<strong></h1>";

							$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	

								for ($i = 0; $i < count($sAereos); $i++) {
										$mensaje_html .= "<h2 style='font-size:1px;font-family:arial,serif;line-height:80%;color:#FFFFFF;font-weight:normal;text-align:left;'>-</h2>

										
									  <span style='font-size:13px;font-family:arial,serif;'><img src='imagenes/transportes/".$sAereos[$i]['v_codigo_cia']."_logo.jpg' alt='logo compañía aérea' height='20' width='60' />  &nbsp;&nbsp;Numero: <strong>".$sAereos[$i]['v_vuelo']."</strong>, <strong>".$sAereos[$i]['v_origen']." - ".$sAereos[$i]['v_destino']."</strong></span>

										<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sAereos[$i]['v_fecha']."</strong>,<strong>  ".$sAereos[$i]['v_salida']." hs -  ".$sAereos[$i]['v_llegada']." hs</strong></span>

										<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;Pasajeros: <strong>".$sAereos[$i]['v_pax']."</strong></span>

										<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sAereos[$i]['v_disponibilidad']."</strong></span>";

								}
							$mensaje_html .= "</DIV>";	

							$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #B9D305; margin:20px 15px; padding:10px 10px; font-size:12px;font-family:arial,serif; background:#FFFFFF; clear:both;line-height:20px'><strong>RESERVADO Y PAGADO POR HI TRAVEL - VIAJES Y OCIO HITS S.L.</strong></div>";

							$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #B9D305; margin:20px 15px; padding:10px 10px; font-size:12px;font-family:arial,serif; background:#FFFFFF; clear:both;line-height:20px'>PARA CUALQUIER DUDA O CONSULTA PONGASE EN CONTACTO CON NUESTRA OFICINA EN CANARIAS. TFNO: 634 802 903. O EN WWW.HITRAVEL.ES</div>";	

							$mensaje_html .= "<div style='float:right; -moz-border-radius:15px; border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 200px; height: 70px; margin: 10px 15px;'>
													<div style='float:left; font:10px Arial, Sans-serif; margin:5px 10px; width:220px;'>Sello de la agencia</div>
											</div>";
							
						}
						$mensaje_html .= "</DIV>";	
					}


					//BONO DE SERVICIOS TERRESTRES		
					/*if ($producto != 'SHO' and $producto != 'SSV'){		
					
					$mensaje_html .= "
					
					<div style='height:980px; -moz-border-radius:15px;border-radius:15px; border: 1px solid #000; padding: 10px 0px; margin: 5px 0px;'>
					

					
					<div style='clear:left; margin: 10px 10px;'>
						<div style='float:left; width:170; padding: 2px 5px; margin: 2px 5px;'><img src='imagenes/Logo_Mail2.jpg' align='center' height='40' width='225'></div>
						
						<div style='float:left; border: 1px solid #000; width:290; font-size:14px;font-family:arial,serif;color:#000000; padding: 5px 5px; margin: 10px 5px; font-weight:normal;text-align:center; valign:center;'><strong>BONO DE SERVICIOS TERRESTRES</strong></div>
					</div>

					<div style='margin:20px 15px; clear:both;line-height:10px font-size:14px;font-family:arial,serif;color:#FFFFFF;'> - </div>					
					
					<div style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:20px 15px; padding:10px 10px; font-size:20px;font-family:arial,serif; background:#B9D305; clear:both;line-height:20px'>Reserva Hi Travel: <strong>".$localizador."</strong></div>
					

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-pasajeros-docu.gif' height='25' width='25'><strong>  Pasajeros</strong></h1>

					<DIV style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:20px 15px;background:#e6e6e6;padding:10px 15px;clear:both;line-height:10px'>";	
						for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

							$mensaje_html .= "<p  style='font-size:13px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
							".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

							if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
								$mensaje_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
							}
							$mensaje_html .= "</p>";
							
						}
					$mensaje_html .= "</DIV>";



						//SERVICIOS TERRESTRES
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'><img src='imagenes/comun/icon-servicios-docu.gif' height='25' width='25'><strong> Servicios<strong></h1>";

						$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	

							for ($i = 0; $i < count($sServicios); $i++) {
									$mensaje_html .= "<h2 style='font-size:1px;font-family:arial,serif;line-height:80%;color:#FFFFFF;font-weight:normal;text-align:left;'>-</h2>";

									
									$mensaje_html .= "<span style='font-size:13px;font-family:arial,serif;'>Servicio <strong>".$sServicios[$i]['s_nombre']."</strong></span>
									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;Fecha <strong>".$sServicios[$i]['s_fecha']."</strong></span>
									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;".$sServicios[$i]['s_pax']." Personas</span>
									<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sServicios[$i]['s_estado']."</strong></span><br><br>";

							}
						$mensaje_html .= "</DIV>";	

						$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #B9D305; margin:20px 15px; padding:10px 10px; font-size:12px;font-family:arial,serif; background:#FFFFFF; clear:both;line-height:20px'><strong>RESERVADO Y PAGADO POR HI TRAVEL - VIAJES Y OCIO HITS S.L.</strong></div>";

						$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #B9D305; margin:20px 15px; padding:10px 10px; font-size:12px;font-family:arial,serif; background:#FFFFFF; clear:both;line-height:20px'>PARA CUALQUIER DUDA O CONSULTA PONGASE EN CONTACTO CON NUESTRA OFICINA EN CANARIAS. TFNO: 634 802 903. O EN WWW.HITRAVEL.ES</div>";	

						$mensaje_html .= "<div style='float:right; -moz-border-radius:15px; border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 200px; height: 70px; margin: 10px 15px;'>
												<div style='float:left; font:10px Arial, Sans-serif; margin:5px 10px; width:220px;'>Sello de la agencia</div>
										</div>";
					$mensaje_html .= "</DIV>";						
					}*/
											

					//SEGUROS
					
					//OPCIONAL PAQUETES
					if($hay_seguro_opcional > 0 and $seguro_opcional_tipo == 'SEG002'){
						$mensaje_html .= "<div style='height:980px; background:#FFFFFF;'>
						
						
						
												<img src='imagenes/seguros/seguros_opcional_paquetes_1-1.jpg' align='center' height='120' width='700'>
												<img src='imagenes/seguros/seguros_opcional_paquetes_1-2.jpg' align='center' height='30' width='520'>
												<img src='imagenes/seguros/seguros_opcional_paquetes_1-3.jpg' align='center' height='25' width='700'>
												<img src='imagenes/seguros/seguros_linea.jpg' align='center' height='5' width='700'>
												<div style='float:left; border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:430px; height:55px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 17px;'>
													<strong>Fecha de emisión:&nbsp;&nbsp;&nbsp;</strong>".$fecha_reserva."
													<br>
													<strong>Fecha inicio viaje:&nbsp;&nbsp;&nbsp;</strong>".$datos_salida."<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha fin viaje:&nbsp;&nbsp;&nbsp;</strong>".$datos_regreso."
													<br>
													<strong>Numero de Asegurados:&nbsp;&nbsp;&nbsp;</strong>".$total_pax."
													</p>
												</div>
												
												<img src='imagenes/seguros/seguros_opcional_paquetes_1-4.jpg' align='center' height='60' width='230'>
												<img src='imagenes/seguros/seguros_opcional_paquetes_1-5.jpg' align='center' height='50' width='700'>
												
												<div style='border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:700px; height:25px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 10px;'>
													<strong>Nombre del Primer Asegurado:&nbsp;&nbsp;&nbsp;</strong>".$sdatos_pasajeros[0]['pax_nombre']." ".$sdatos_pasajeros[0]['pax_apellidos']."
													</p>
												</div>	
												
												<img src='imagenes/seguros/seguros_linea.jpg' align='center' height='5' width='700'>

												<div style='border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:700px; height:25px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 10px;'>
													<strong>Datos del viaje:&nbsp;&nbsp;&nbsp;</strong>".$nombre_viaje."
													</p>
												</div>											

												<img src='imagenes/seguros/seguros_linea.jpg' align='center' height='5' width='700'>
												
												<div style='border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:430px; height:65px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 17px;'>
													<strong>Motivo:&nbsp;&nbsp;&nbsp;</strong>Vacaciones
													<br>
													<strong>Origen:&nbsp;&nbsp;&nbsp;</strong>España<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destino:&nbsp;&nbsp;&nbsp;</strong>España
													<br>
													<strong>Duración del viaje:&nbsp;&nbsp;&nbsp;</strong>".$duracion_viaje."&nbsp;días.
													</p>
												</div>
												
												<img src='imagenes/seguros/seguros_opcional_paquetes_1-6.jpg' align='center' height='125' width='700'>
												<img src='imagenes/seguros/seguros_opcional_paquetes_1-7.jpg' align='center' height='490' width='700'>
												<img src='imagenes/seguros/seguros_opcional_paquetes_2-1.jpg' align='center' height='500' width='700'>
												<img src='imagenes/seguros/seguros_opcional_paquetes_2-2.jpg' align='center' height='500' width='700'>
												<img src='imagenes/seguros/seguros_opcional_paquetes_3-1.jpg' align='center' height='500' width='700'>

												
												<DIV style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:20px 15px;background:#e6e6e6;padding:10px 15px;clear:both;line-height:10px'>";	
													for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

														$mensaje_html .= "<p  style='font-size:12px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
														".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

														if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
															$mensaje_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
														}
														$mensaje_html .= "</p>";
														
													}
												$mensaje_html .= "</DIV>";												
												
												$mensaje_html .= "<img src='imagenes/seguros/seguros_opcional_paquetes_3-2.jpg' align='center' height='50' width='700'>

												</div>";
					//OPCIONAL SOLO VUELO
					}elseif($hay_seguro_opcional > 0 and $seguro_opcional_tipo == 'SEG001'){
						$mensaje_html .= "<div style='height:980px; background:#FFFFFF;'>
						
						
						
												<img src='imagenes/seguros/seguros_opciona_solovuelo_1-1.jpg' align='center' height='120' width='700'>
												<img src='imagenes/seguros/seguros_opciona_solovuelo_1-2.jpg' align='center' height='30' width='520'>
												<img src='imagenes/seguros/seguros_opciona_solovuelo_1-3.jpg' align='center' height='25' width='700'>
												<img src='imagenes/seguros/seguros_linea.jpg' align='center' height='5' width='700'>
												<div style='float:left; border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:430px; height:55px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 17px;'>
													<strong>Fecha de emisión:&nbsp;&nbsp;&nbsp;</strong>".$fecha_reserva."
													<br>
													<strong>Fecha inicio viaje:&nbsp;&nbsp;&nbsp;</strong>".$datos_salida."<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha fin viaje:&nbsp;&nbsp;&nbsp;</strong>".$datos_regreso."
													<br>
													<strong>Numero de Asegurados:&nbsp;&nbsp;&nbsp;</strong>".$total_pax."
													</p>
												</div>
												
												<img src='imagenes/seguros/seguros_opciona_solovuelo_1-4.jpg' align='center' height='60' width='230'>
												<img src='imagenes/seguros/seguros_opciona_solovuelo_1-5.jpg' align='center' height='50' width='700'>
												
												<div style='border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:700px; height:25px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 10px;'>
													<strong>Nombre del Primer Asegurado:&nbsp;&nbsp;&nbsp;</strong>".$sdatos_pasajeros[0]['pax_nombre']." ".$sdatos_pasajeros[0]['pax_apellidos']."
													</p>
												</div>	
												
												<img src='imagenes/seguros/seguros_linea.jpg' align='center' height='5' width='700'>

												<div style='border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:700px; height:25px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 10px;'>
													<strong>Datos del viaje:&nbsp;&nbsp;&nbsp;</strong>".$nombre_viaje."
													</p>
												</div>											

												<img src='imagenes/seguros/seguros_linea.jpg' align='center' height='5' width='700'>
												
												<div style='border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:430px; height:65px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 17px;'>
													<strong>Motivo:&nbsp;&nbsp;&nbsp;</strong>Vacaciones
													<br>
													<strong>Origen:&nbsp;&nbsp;&nbsp;</strong>España<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destino:&nbsp;&nbsp;&nbsp;</strong>España
													<br>
													<strong>Duración del viaje:&nbsp;&nbsp;&nbsp;</strong>".$duracion_viaje."&nbsp;días.
													</p>
												</div>
												
												<img src='imagenes/seguros/seguros_opciona_solovuelo_1-6.jpg' align='center' height='125' width='700'>
												<img src='imagenes/seguros/seguros_opciona_solovuelo_1-7.jpg' align='center' height='490' width='700'>
												<img src='imagenes/seguros/seguros_opciona_solovuelo_2-1.jpg' align='center' height='500' width='700'>
												<img src='imagenes/seguros/seguros_opciona_solovuelo_2-2.jpg' align='center' height='500' width='700'>
												<img src='imagenes/seguros/seguros_opciona_solovuelo_3-1.jpg' align='center' height='500' width='700'>
												<img src='imagenes/seguros/seguros_opciona_solovuelo_3-2.jpg' align='center' height='500' width='700'>
												<img src='imagenes/seguros/seguros_opciona_solovuelo_4-1.jpg' align='center' height='220' width='700'>
												
												<DIV style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:20px 15px;background:#e6e6e6;padding:10px 15px;clear:both;line-height:10px'>";	
													for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

														$mensaje_html .= "<p  style='font-size:12px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
														".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

														if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
															$mensaje_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
														}
														$mensaje_html .= "</p>";
														
													}
												$mensaje_html .= "</DIV>";												
												
												$mensaje_html .= "<img src='imagenes/seguros/seguros_opciona_solovuelo_4-2.jpg' align='center' height='50' width='700'>

												</div>";
					//INLCUIDO							
					}elseif($hay_seguro_incluido > 0){
						$mensaje_html .= "<div style='height:980px; background:#FFFFFF;'>
						
						
						
												<img src='imagenes/seguros/seguros_incluido_1-1.jpg' align='center' height='120' width='700'>
												<img src='imagenes/seguros/seguros_incluido_1-2.jpg' align='center' height='30' width='520'>
												<img src='imagenes/seguros/seguros_incluido_1-3.jpg' align='center' height='25' width='700'>
												<img src='imagenes/seguros/seguros_linea.jpg' align='center' height='5' width='700'>
												<div style='float:left; border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:430px; height:55px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 17px;'>
													<strong>Fecha de emisión:&nbsp;&nbsp;&nbsp;</strong>".$fecha_emision."
													<br>
													<strong>Fecha inicio viaje:&nbsp;&nbsp;&nbsp;</strong>".$datos_salida."<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha fin viaje:&nbsp;&nbsp;&nbsp;</strong>".$datos_regreso."
													<br>
													<strong>Numero de Asegurados:&nbsp;&nbsp;&nbsp;</strong>".$total_pax."
													</p>
												</div>
												
												<img src='imagenes/seguros/seguros_incluido_1-4.jpg' align='center' height='60' width='230'>
												<img src='imagenes/seguros/seguros_incluido_1-5.jpg' align='center' height='50' width='700'>
												
												<div style='border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:700px; height:25px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 10px;'>
													<strong>Nombre del Primer Asegurado:&nbsp;&nbsp;&nbsp;</strong>".$sdatos_pasajeros[0]['pax_nombre']." ".$sdatos_pasajeros[0]['pax_apellidos']."
													</p>
												</div>	
												
												<img src='imagenes/seguros/seguros_linea.jpg' align='center' height='5' width='700'>

												<div style='border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:700px; height:25px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 10px;'>
													<strong>Datos del viaje:&nbsp;&nbsp;&nbsp;</strong>".$nombre_viaje."
													</p>
												</div>											

												<img src='imagenes/seguros/seguros_linea.jpg' align='center' height='5' width='700'>
												
												<div style='border: 0px solid #000; font:10px Arial, Sans-serif; margin:0px 27px; width:430px; height:65px;'> 
													<p style='font-size:12px;font-family:arial,serif; line-height: 17px;'>
													<strong>Motivo:&nbsp;&nbsp;&nbsp;</strong>Vacaciones
													<br>
													<strong>Origen:&nbsp;&nbsp;&nbsp;</strong>España<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destino:&nbsp;&nbsp;&nbsp;</strong>España
													<br>
													<strong>Duración del viaje:&nbsp;&nbsp;&nbsp;</strong>".$duracion_viaje."&nbsp;días.
													</p>
												</div>
												
												<img src='imagenes/seguros/seguros_incluido_1-6.jpg' align='center' height='80' width='700'>
												<img src='imagenes/seguros/seguros_incluido_1-7.jpg' align='center' height='125' width='700'>
												<img src='imagenes/seguros/seguros_incluido_2-1.jpg' align='center' height='500' width='700'>
												<img src='imagenes/seguros/seguros_incluido_2-2.jpg' align='center' height='500' width='700'>
												<img src='imagenes/seguros/seguros_incluido_3-1.jpg' align='center' height='500' width='700'>

												
												<DIV style='-moz-border-radius:15px;border-radius:15px; border: 1px solid #000000; margin:20px 15px;background:#e6e6e6;padding:10px 15px;clear:both;line-height:10px'>";	
													for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

														$mensaje_html .= "<p  style='font-size:12px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
														".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

														if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
															$mensaje_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
														}
														$mensaje_html .= "</p>";
														
													}
												$mensaje_html .= "</DIV>";												
												
												$mensaje_html .= "<img src='imagenes/seguros/seguros_incluido_3-2.jpg' align='center' height='50' width='700'>

												</div>";
					}								
												
					$mensaje_html .= "</body></html>";


 
# Instanciamos un objeto de la clase DOMPDF.
$mipdf = new DOMPDF();
 
# Definimos el tamaño y orientación del papel que queremos.
# O por defecto cogerá el que está en el fichero de configuración.
$mipdf ->set_paper("A4", "portrait");
  
# Cargamos el contenido HTML.
$mipdf ->load_html($mensaje_html,'UTF-8'); 
 
# Renderizamos el documento PDF.
$mipdf ->render();
 
# Enviamos el fichero PDF al navegador.
$nombre_pdf = "Hitravel_Reserva_".$localizador.".pdf";
$mipdf ->stream($nombre_pdf);



?>


