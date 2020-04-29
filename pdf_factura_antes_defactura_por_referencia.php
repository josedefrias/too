<?php
# Cargamos la librería dompdf.
	require_once 'dompdf_config.inc.php';
 	include 'funciones_php/conexiones.php';
	//include 'funciones_php/loggin.php';
	//require 'clases/general.cls.php';
	require 'clases/clases_web/Reservas_fin.cls.php';
	
$parametrosg = $_GET;

$localizador = $parametrosg['loc'];


$fecha_presupuesto = date("d/m/Y"); 

$conexion = conexion_hit();



					//OBTENEMOS DATOS FACTURA DE LA hit_reservas
					$datos_factura =$conexion->query("select case r.FACTURA 
																when 0 then 'PROFORMA'
																else r.FACTURA
																end factura,  
																 DATE_FORMAT(r.FACTURA_FECHA_EMISION, '%d-%m-%Y') AS factura_fecha,
																 m.CIF factura_cif,
																 m.ID factura_codigo_cliente,
																 o.OFICINA factura_oficina,
																 case
																 	when m.NOMBRE_FISCAL = '' then m.NOMBRE
																 	else m.NOMBRE_FISCAL
																 	end factura_nombre_minorista,
																
																 o.facturacion,
																	
																 o.DIRECCION_FISCAL factura_direccion,
																 o.CODIGO_POSTAL_FISCAL factura_codigo_postal,
																 o.LOCALIDAD_FISCAL factura_localidad,
																 p.NOMBRE factura_provincia,
																 
																 m.DIRECCION_FISCAL factura_direccion_central,
																 m.CODIGO_POSTAL_FISCAL factura_codigo_postal_central,
																 m.LOCALIDAD_FISCAL factura_localidad_central,
																 p2.NOMBRE factura_provincia_central,
																 
																 pc.NOMBRE factura_viaje,
																 DATE_FORMAT(r.FECHA_SALIDA, '%d-%m-%Y') AS factura_salida,
																 concat(rp.NOMBRE,' ',rp.APELLIDO) factura_titular,
																 r.REFERENCIA_AGENCIA factura_referencia_agencia,
																 r.REFERENCIA factura_referencia_hitravel,
																 DATE_FORMAT(r.FECHA_RESERVA, '%d-%m-%Y') AS factura_fecha_reserva,
																 r.AGENTE factura_agente,
																 r.PAX factura_pax,
																 r.LOCALIZADOR factura_localizador,
																 r.PVP_COMISIONABLE factura_subtotal_comisionable,
																 r.PVP_NO_COMISIONABLE factura_subtotal_no_comisionable,
																 r.PVP_COMISIONABLE + r.PVP_NO_COMISIONABLE factura_total_bruto,
																 r.PVP_COMISION factura_comision,
																 r.PVP_IMPORTE_COMISION factura_importe_comision,
																 r.PVP_IMPUESTO factura_iva_comision,
																 r.PVP_IMPUESTO_COMISION factura_importe_iva_comision,
																 r.PVP_NO_COMISIONABLE factura_total_no_comisionable,
																 r.PVP_TOTAL factura_total_a_pagar
															 from
																hit_reservas r,
																hit_oficinas o,
																hit_minoristas m,
																hit_provincias p,
																hit_provincias p2,
																hit_producto_cuadros pc,
																hit_reservas_pasajeros rp
															where
																r.MINORISTA = o.ID
																and r.OFICINA = o.OFICINA
																and o.ID = m.ID
																and o.PROVINCIA_FISCAL = p.CODIGO
																and m.PROVINCIA_FISCAL = p2.CODIGO
																and r.FOLLETO = pc.FOLLETO
																and r.CUADRO = pc.CUADRO
																and r.LOCALIZADOR = rp.LOCALIZADOR
																and rp.NUMERO = (select min(rp2.numero) from hit_reservas_pasajeros rp2 where rp2.LOCALIZADOR = r.LOCALIZADOR)
																and r.LOCALIZADOR = '".$localizador."'");
					$odatos_factura = $datos_factura->fetch_assoc();
					$factura = $odatos_factura['factura'];
					$factura_fecha = $odatos_factura['factura_fecha'];
					$factura_cif = $odatos_factura['factura_cif'];
					$factura_codigo_cliente = $odatos_factura['factura_codigo_cliente'];
					$factura_oficina = $odatos_factura['factura_oficina'];
					$factura_nombre_minorista = $odatos_factura['factura_nombre_minorista'];
					
					if($odatos_factura['factura_direccion'] == 'O'){
						$factura_direccion = $odatos_factura['factura_direccion'];
						$factura_codigo_postal = $odatos_factura['factura_codigo_postal'];
						$factura_localidad = $odatos_factura['factura_localidad'];
						$factura_provincia = $odatos_factura['factura_provincia'];
					}else{
						$factura_direccion = $odatos_factura['factura_direccion_central'];
						$factura_codigo_postal = $odatos_factura['factura_codigo_postal_central'];
						$factura_localidad = $odatos_factura['factura_localidad_central'];
						$factura_provincia = $odatos_factura['factura_provincia_central'];
					}
					
					$factura_viaje = $odatos_factura['factura_viaje'];
					if($localizador == 100674){
						$factura_viaje = 'Solo vuelo Fuerteventura';
					}
					$factura_salida = $odatos_factura['factura_salida'];
					$factura_titular = $odatos_factura['factura_titular'];
					$factura_referencia_agencia = $odatos_factura['factura_referencia_agencia'];
					$factura_referencia_hitravel = $odatos_factura['factura_referencia_hitravel'];
					$factura_fecha_reserva = $odatos_factura['factura_fecha_reserva'];
					$factura_agente = $odatos_factura['factura_agente'];
					$factura_pax = $odatos_factura['factura_pax'];
					$factura_localizador = $odatos_factura['factura_localizador'];
					$factura_subtotal_comisionable = $odatos_factura['factura_subtotal_comisionable'];
					$factura_subtotal_no_comisionable = $odatos_factura['factura_subtotal_no_comisionable'];
					$factura_total_bruto = $odatos_factura['factura_total_bruto'];
					$factura_comision = $odatos_factura['factura_comision'];
					$factura_importe_comision = $odatos_factura['factura_importe_comision'];
					$factura_iva_comision = $odatos_factura['factura_iva_comision'];
					$factura_importe_iva_comision = $odatos_factura['factura_importe_iva_comision'];
					$factura_total_no_comisionable = $odatos_factura['factura_total_no_comisionable'];
					$factura_total_a_pagar = $odatos_factura['factura_total_a_pagar'];					

					$oReservas_fin = new clsReservas_fin($conexion, $localizador);
					$sdesglose_factura = $oReservas_fin->Cargar_desglose();
					$sdesglose_factura_no_comisionables = $oReservas_fin->Cargar_desglose_no_comisionables();
			
					$datos_lineas_desglose_comisionables =$conexion->query("select count(*) lineas_comisionables
															 from
																hit_reservas_condiciones c
															where
																c.localizador = '".$localizador."'");
					$odatos_lineas_desglose_comisionables = $datos_lineas_desglose_comisionables->fetch_assoc();
					$factura_lineas_comisionables = $odatos_lineas_desglose_comisionables['lineas_comisionables'];
					$alto_comisionables = ($factura_lineas_comisionables * 25) + 56;

					
					$datos_lineas_desglose_no_comisionables =$conexion->query("select count(*) lineas
						from
						(
						SELECT sv.nombre p_detalle, s.pax p_pax, s.pvp_pax p_precio_pax, s.pvp_total_servicio p_total
																from hit_reservas_servicios s, hit_servicios sv
																where
																	s.ID_SERVICIO = sv.ID
																	and s.localizador = '".$localizador."'
																	and s.tipo_pvp = 'N'
																	
																union all

																select 'Tasas de Aeropuerto' p_detalle, a.PLAZAS p_pax, sum(t.TASAS_PAX) p_precio_pax, sum(t.TASAS_PVP_TOTAL_TRAYECTO) p_total
																from hit_reservas_aereos a, hit_reservas_aereos_trayectos t
																where
																	a.LOCALIZADOR = t.LOCALIZADOR
																	and a.ORDEN = t.ORDEN
																	and a.LOCALIZADOR = '".$localizador."'
																group by  a.LOCALIZADOR, a.ORDEN
						) lineas");
																				
					$odatos_lineas_desglose_no_comisionables = $datos_lineas_desglose_no_comisionables->fetch_assoc();
					$factura_lineas_no_comisionables = $odatos_lineas_desglose_no_comisionables['lineas'];
					
					$datos_lineas_desglose_no_comisionables_manual =$conexion->query("select count(*) lineas
																													from 
						(
						SELECT sv.nombre p_detalle, s.pax p_pax, s.pvp_pax p_precio_pax, s.pvp_total_servicio p_total
																from hit_reservas_servicios s, hit_servicios sv
																where
																	s.ID_SERVICIO = sv.ID
																	and s.localizador = '".$localizador."'
																	and s.tipo_pvp = 'N'
																	
						union all

						select 'Tasas de Aeropuerto' p_detalle, a.PLAZAS p_pax, sum(t.TASAS_PAX) p_precio_pax, sum(t.TASAS_PVP_TOTAL_TRAYECTO) p_total
																from hit_reservas_aereos a, hit_reservas_aereos_trayectos t
																where
																	a.LOCALIZADOR = t.LOCALIZADOR
																	and a.ORDEN = t.ORDEN
																	and a.LOCALIZADOR = '".$localizador."'
																group by  a.LOCALIZADOR, a.ORDEN		
						) todos_no_comis, hit_reservas r
						where
							r.MODIFICAR = 'S'
							AND r.MODIFICAR_NO_COMISIONABLE is not null and r.MODIFICAR_NO_COMISIONABLE <> 0
							and r.LOCALIZADOR	= '".$localizador."'");
															
																				
					$odatos_lineas_desglose_no_comisionables_manual = $datos_lineas_desglose_no_comisionables_manual->fetch_assoc();
					$factura_lineas_no_comisionables_manual = $odatos_lineas_desglose_no_comisionables_manual['lineas'];						
					
					
					
					$alto_no_comisionables = (($factura_lineas_no_comisionables + $factura_lineas_no_comisionables_manual) * 25) + 56;

					
				$mensaje_html = "<html lang='es'>
					<head>	
						<meta charset='utf-8'/>
					</head>

					<body>
				<table style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 100%;'>
					<tr>
						<td width='300px'>
							<img src='imagenes/comun/hi-travel.png' align='center' height='60' width='300'>
						</td>
						<td  width='150px' align = 'left' style='font:10px Arial, Sans-serif;'>
							VIAJES Y OCIO HITS, S.L.<BR>
							C/ PILARICA, 18 BAJO<BR>
							28026 MADRID<BR>
							CIF B-86915287
							
						</td>							
						<td align = 'left'>
							<p style='font:16px Arial, Sans-serif;'><strong>FACTURA Nº:  ".$factura."</strong></p>
							<p style='font:11px Arial, Sans-serif;'>FECHA: ".$factura_fecha."</p>
							<p style='font:11px Arial, Sans-serif;'>Régimen Especial de las Agencias de Viajes</p>
						</td>					
						
					<tr/>
				</table>";

				
				$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 700px; height: 110px; padding: 10px 0px; margin: 10px 0px;'>
									
									<div style='font:17px Arial, Sans-serif; padding: 5px 10px; width:100px;'><strong>AGENCIA</strong></div>
														
									<div style='clear:left;'>									
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'>CIF:</div>
										<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:100px;'>".$factura_cif."</div>				
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:70px;'>Nombre:</div>
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:360px;'>".$factura_nombre_minorista."</div>							
									</div>
									
									<div style='clear:left;'>
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'>Cod.Cliente:</div>
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:100px'>".$factura_codigo_cliente."</div>
										
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:70px;'>Dirección:</div>
										<div  style='float:left; font:12px Arial, Sans-serif; padding: 5px 10px; width:360px;'>".$factura_direccion." - ".$factura_codigo_postal." - ".$factura_localidad."</div>
									</div>
									
									<div style='clear:left;'>									
										<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'>Oficina:</div>
										<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:100px;'>".$factura_oficina."</div>				
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:70px;'>Provincia:</div>
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:360px;'>".$factura_provincia."</div>									
									</div>	
									
								</div>";				

				$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 700px; height: 140px; padding: 10px 0px; margin: 10px 0px;'>
									
									<div style='font:17px Arial, Sans-serif; padding: 5px 10px; width:100px;'><strong>SERVICIO</strong></div>
														
									<div style='clear:left;'>									
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:50px;'>Viaje:</div>
										<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:330px;'>".$factura_viaje."</div>				
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'>Localizador:</div>
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:170px;'>".$factura_localizador."</div>							
									</div>
									
									<div style='clear:left;'>
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:50px;'>Salida:</div>
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:330px'>".$factura_salida."</div>
										
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'>F.Reserva:</div>
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:170px;'>".$factura_fecha_reserva."</div>
									</div>
									
									<div style='clear:left;'>									
										<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:50px;'>Titular:</div>
										<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:330px;'>".$factura_titular."</div>				
										<div  style='float:left; font:5 Arial, Sans-serif; padding: 5px 10px; width:80px;'>Agente:</div>
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:170px;'>".$factura_agente."</div>									
									</div>	

									<div style='clear:left;'>									
										<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:100px;'>Ref. agencia:</div>
										<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:280px;'>".$factura_referencia_agencia."</div>				
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:120px;'>Nº de Personas:</div>
										<div  style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:130px;'>".$factura_pax."</div>									
									</div>	
									
								</div>";								


				$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 700px; height: ".$alto_comisionables."px; padding: 10px 0px; margin: 10px 0px;'>

									<div style='clear:left;'>									
										<div  style='float:left; font:16px Arial, Sans-serif; padding: 5px 10px; width:320px;'><strong>CONCEPTOS COMISIONABLES</strong></div>
										<div  style='float:left; text-align:center;font:16px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>Nº PAX</strong></div>			
										<div  style='float:left; text-align:center; font:16px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong>PRECIO PAX</strong></div>
										<div  style='float:left; text-align:right; font:16px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>TOTAL<</strong></div>							
									</div>";
									
						for ($i = 0; $i < count($sdesglose_factura); $i++) {

							$mensaje_html .= "<div style='clear:left;'>									
												<div style='float:left; font:13px Arial, Sans-serif; padding: 5px 10px; width:320px;'>".$sdesglose_factura[$i]['p_detalle']."</div>
												<div style='float:left; text-align:center; font:13px Arial, Sans-serif; padding: 5px 10px; width:80px;'>".$sdesglose_factura[$i]['p_pax']."</div>				
												<div  style='float:left; text-align:center; font:13px Arial, Sans-serif; padding: 5px 10px; width:120px;'>".$sdesglose_factura[$i]['p_precio_pax']."</div>
												<div  style='float:left; text-align:right; font:13px Arial, Sans-serif; padding: 5px 10px; width:80px;'>".$sdesglose_factura[$i]['p_total']."</div>									
											</div>	";
						}									

						
				$mensaje_html .= "<div style='clear:left;'>									
											<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:320px;'>&nbsp;</div>
											<div style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'>&nbsp;</div>				
											<div  style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong>SUBTOTAL</strong></div>
											<div  style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>".$factura_subtotal_comisionable." €</strong></div>									
										</div>	";
									
				$mensaje_html .= "</div>";
									
									
				$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 700px; height: ".$alto_no_comisionables."px; padding: 10px 0px; margin: 10px 0px;'>

									<div style='clear:left;'>									
										<div  style='float:left; font:16px Arial, Sans-serif; padding: 5px 10px; width:320px;'><strong>CONCEPTOS NO COMISIONABLES</strong></div>
										<div  style='float:left; text-align:center;font:16px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>Nº PAX</strong></div>			
										<div  style='float:left; text-align:center; font:16px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong>PRECIO PAX</strong></div>
										<div  style='float:left; text-align:right; font:16px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>TOTAL<</strong></div>							
									</div>";
									
						for ($i = 0; $i < count($sdesglose_factura_no_comisionables); $i++) {

							$mensaje_html .= "<div style='clear:left;'>									
												<div style='float:left; font:13px Arial, Sans-serif; padding: 5px 10px; width:320px;'>".$sdesglose_factura_no_comisionables[$i]['p_detalle']."</div>
												<div style='float:left; text-align:center; font:13px Arial, Sans-serif; padding: 5px 10px; width:80px;'>".$sdesglose_factura_no_comisionables[$i]['p_pax']."</div>				
												<div  style='float:left; text-align:center; font:13px Arial, Sans-serif; padding: 5px 10px; width:120px;'>".$sdesglose_factura_no_comisionables[$i]['p_precio_pax']."</div>
												<div  style='float:left; text-align:right; font:13px Arial, Sans-serif; padding: 5px 10px; width:80px;'>".$sdesglose_factura_no_comisionables[$i]['p_total']."</div>									
											</div>	";
						}									

						$mensaje_html .= "<div style='clear:left;'>									
											<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:320px;'>&nbsp;</div>
											<div style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'>&nbsp;</div>				
											<div  style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong>SUBTOTAL</strong></div>
											<div  style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>".$factura_subtotal_no_comisionable." €</strong></div>									
										</div>	";
									
				$mensaje_html .= "</div>";									

				/*$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 100px; height: 110px; padding: 10px 0px; margin: 10px 0px;'>
									

								</div>";*/

								
									
				$mensaje_html .= "<div style='float:right; -moz-border-radius:15px; border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 390px; height: 165px; padding: 10px 0px; margin: 10px 0px;'>
									
									<div style='clear:left;'>									
										<div style='float:left; font:16px Arial, Sans-serif; padding: 5px 10px; width:220px;'><strong>TOTALES</strong></div>
										
										<div style='float:left; text-align:right; font:16px Arial, Sans-serif; padding: 5px 250px; width:120px;'><strong>TOTAL</strong></div>			
									</div>
									
									<div style='clear:left;'>										
										<div style='float:left; text-align:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:220px;'>Total Bruto</div>
										<div style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 250px; width:120px;'>".$factura_total_bruto." €</div>				
									</div>	

									<div style='clear:left;'>										
										<div style='float:left; text-align:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:220px;'>Comisión (".$factura_comision."%)</div>
										<div style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 250px; width:120px;'>".$factura_importe_comision." €</div>				
									</div>

									<div style='clear:left;'>										
										<div style='float:left; text-align:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:220px;'>IVA Comisión (".$factura_iva_comision."%)</div>
										<div style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 250px; width:120px;'>".$factura_importe_iva_comision."  €</div>				
									</div>

									<div style='clear:left;'>										
										<div style='float:left; text-align:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:220px;'>Tasas/Otros no comisionables</div>
										<div style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 250px; width:120px;'>".$factura_total_no_comisionable." €</div>				
									</div>

									<div style='clear:left;'>										
										<div style='float:left; text-align:left; font:16px Arial, Sans-serif; padding: 5px 10px; width:220px;'><strong>TOTAL A PAGAR</strong></div>
										<div style='float:left; text-align:right; font:16px Arial, Sans-serif; padding: 5px 250px; width:120px;'><strong>".$factura_total_a_pagar." €</strong></div>				
									</div>
									
								</div>";	
								
				$mensaje_html .= "<div style='clear:left; width: 700px; height: 14px; margin: 0px 0px;'>
									
									<div style='font:12px Arial, Sans-serif; padding: 0px 0px; width:700px;'><strong> DATOS BANCARIOS: LA CAIXA - IBAN: ES14 2100 1633 5502 0023 6985</strong></div>

								</div>";

				$mensaje_html .= "<div style='clear:left; width: 700px; height: 14px; margin: 0px 0px;'>
									
									<div style='font:9px Arial, Sans-serif; padding: 0px 0px; width:700px;'><strong>VIAJES Y OCIO HITS, S.L. CIF B-86915287. Inscrita en el Registro Mercantil de Madrid, al tomo 31.854, libro 0, folio 194, sección 8, hoja M-573251, inscripción 1ª.</strong></div>

								</div>";	

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


