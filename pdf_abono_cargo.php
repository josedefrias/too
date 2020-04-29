<?php
# Cargamos la librería dompdf.
	require_once 'dompdf_config.inc.php';
 	include 'funciones_php/conexiones.php';
	//include 'funciones_php/loggin.php';
	//require 'clases/general.cls.php';
	require 'clases/clases_web/Reservas_fin.cls.php';
	
$parametrosg = $_GET;

$localizador = $parametrosg['localizador'];
$abono_cargo = $parametrosg['factura'];

$fecha_presupuesto = date("d/m/Y"); 

$conexion = conexion_hit();

					//OBTENEMOS DATOS DEL ABONO/CARGO
					$datos_abono_cargo =$conexion->query("select tipo abono_cargo_tipo, 
															 importe abono_cargo_importe, 
															 detalle abono_cargo_detalle,
															 factura abono_cargo_factura,
															 DATE_FORMAT(fecha_emision, '%d-%m-%Y') AS abono_cargo_fecha_emision
															 from
																hit_reservas_abonos_cargos
															where
																FACTURA = '".$abono_cargo."'");

					$odatos_abono_cargo= $datos_abono_cargo->fetch_assoc();
					$abono_cargo_tipo = $odatos_abono_cargo['abono_cargo_tipo'];
					if($abono_cargo_tipo == 'A'){
						$abono_cargo_tipo = 'ABONO';
					}else{
						$abono_cargo_tipo = 'CARGO';
					}
					$abono_cargo_importe = $odatos_abono_cargo['abono_cargo_importe'];
					$abono_cargo_detalle = $odatos_abono_cargo['abono_cargo_detalle'];
					$abono_cargo_factura = $odatos_abono_cargo['abono_cargo_factura'];
					$abono_cargo_fecha_emision = $odatos_abono_cargo['abono_cargo_fecha_emision'];



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
					$factura_fecha = $odatos_factura['factura_fecha']; //******abono_cargo
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
					$factura_salida = $odatos_factura['factura_salida'];
					$factura_titular = $odatos_factura['factura_titular'];
					$factura_referencia_agencia = $odatos_factura['factura_referencia_agencia'];
					$factura_referencia_hitravel = $odatos_factura['factura_referencia_hitravel'];
					$factura_fecha_reserva = $odatos_factura['factura_fecha_reserva'];
					$factura_agente = $odatos_factura['factura_agente'];
					$factura_pax = $odatos_factura['factura_pax'];
					$factura_localizador = $odatos_factura['factura_localizador'];
					/*$factura_subtotal_comisionable = $odatos_factura['factura_subtotal_comisionable'];//*****cambiar por importe abono o cargo
					$factura_subtotal_no_comisionable = $odatos_factura['factura_subtotal_no_comisionable']; //****eliminar
					$factura_total_bruto = $odatos_factura['factura_total_bruto'];//****eliminar
					$factura_comision = $odatos_factura['factura_comision'];//****eliminar
					$factura_importe_comision = $odatos_factura['factura_importe_comision'];//****eliminar
					$factura_iva_comision = $odatos_factura['factura_iva_comision'];//****eliminar
					$factura_importe_iva_comision = $odatos_factura['factura_importe_iva_comision'];//****eliminar
					$factura_total_no_comisionable = $odatos_factura['factura_total_no_comisionable'];//*****cambiar por importe abono o cargo
					$factura_total_a_pagar = $odatos_factura['factura_total_a_pagar'];//*****cambiar por importe abono o cargo		*/


					
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
							<p style='font:16px Arial, Sans-serif;'><strong>".$abono_cargo_tipo." Nº:  ".$abono_cargo_factura."</strong></p>
							<p style='font:11px Arial, Sans-serif;'>FECHA: ".$abono_cargo_fecha_emision."</p>
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


				$mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 700px; height: 315px; padding: 10px 0px; margin: 10px 0px;'>

									<div style='clear:left;'>									
										<div  style='float:left; font:16px Arial, Sans-serif; padding: 5px 10px; width:320px;'><strong>CONCEPTOS DEL ".$abono_cargo_tipo."</strong></div>
										<div  style='float:left; text-align:center;font:16px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong></strong></div>			
										<div  style='float:left; text-align:center; font:16px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong></strong></div>
										<div  style='float:left; text-align:right; font:16px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong></strong></div>							
									</div>";

							$mensaje_html .= "<div style='clear:left;'>									
												<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:320px;'>Factura original: ".$factura."</div>
												<div style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'></div>				
												<div  style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:120px;'></div>
												<div  style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'></div>									
											</div>	";

							$mensaje_html .= "<div style='clear:left;'>									
												<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:520px;'>".$abono_cargo_detalle."</div>
												<div  style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'></div>									
											</div>	";							
						
				/*$mensaje_html .= "<div style='clear:left;'>									
											<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:320px;'>&nbsp;</div>
											<div style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'>&nbsp;</div>				
											<div  style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong>SUBTOTAL</strong></div>
											<div  style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>".$abono_cargo_importe." €</strong></div>									
										</div>	";*/

				$mensaje_html .= "								
											<div  style='position: absolute; top: 740px; left: 460px; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong>SUBTOTAL</strong></div>
											
											<div  style='position: absolute; top: 740px; left: 580px; text-align:right; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>".$abono_cargo_importe." €</strong></div>									
										";										
										
				$mensaje_html .= "</div>";
									
								
									
				$mensaje_html .= "<div style='float:right; -moz-border-radius:15px; border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 390px; height: 165px; padding: 10px 0px; margin: 10px 0px;'>
									
									<div style='clear:left;'>									
										<div style='float:left; font:16px Arial, Sans-serif; padding: 5px 10px; width:220px;'><strong>TOTALES</strong></div>
										
										<div style='float:left; text-align:right; font:16px Arial, Sans-serif; padding: 5px 250px; width:120px;'><strong>TOTAL</strong></div>			
									</div>
									
									<div style='clear:left;'>										
										<div style='float:left; text-align:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:220px;'>Total Bruto</div>
										<div style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 250px; width:120px;'>0,00 €</div>				
									</div>	

									<div style='clear:left;'>										
										<div style='float:left; text-align:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:220px;'>Comisión (0,00%)</div>
										<div style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 250px; width:120px;'>0,00 €</div>				
									</div>

									<div style='clear:left;'>										
										<div style='float:left; text-align:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:220px;'>IVA Comisión (0,00%)</div>
										<div style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 250px; width:120px;'>0,00 €</div>				
									</div>

									<div style='clear:left;'>										
										<div style='float:left; text-align:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:220px;'>Tasas/Otros no comisionables</div>
										<div style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 250px; width:120px;'>".$abono_cargo_importe." €</div>				
									</div>

									<div style='clear:left;'>										
										<div style='float:left; text-align:left; font:16px Arial, Sans-serif; padding: 5px 10px; width:220px;'><strong>TOTAL A PAGAR</strong></div>
										<div style='float:left; text-align:right; font:16px Arial, Sans-serif; padding: 5px 250px; width:120px;'><strong>".$abono_cargo_importe." €</strong></div>				
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
$nombre_pdf = "Hitravel_Reserva_".ucfirst(strtolower($abono_cargo_tipo))."_".$abono_cargo_factura.".pdf";
$mipdf ->stream($nombre_pdf);



?>


