<?php


<html lang='es'>
	<head>	
			<meta charset='utf-8'/>
	</head>
	
	<body>

		<div style='height:1000px; clear:left;'>
				
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
			</table>

							
			<div style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 700px; height: 110px; padding: 10px 0px; margin: 10px 0px;'>
												
				<div style='font:17px Arial, Sans-serif; padding: 5px 10px; width:100px;'><strong>AGENCIA</strong>
				</div>
																	
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
												
			</div>				


				
				
			<div style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 700px; height: 140px; padding: 10px 0px; margin: 10px 0px;'>
												
				<div style='font:17px Arial, Sans-serif; padding: 5px 10px; width:100px;'><strong>SERVICIO</strong>
				</div>
																	
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
												
			</div>								


			<div style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 700px; height: ".$alto_comisionables."px; padding: 10px 0px; margin: 10px 0px;'>

				<div style='clear:left;'>									
					<div  style='float:left; font:16px Arial, Sans-serif; padding: 5px 10px; width:320px;'><strong>CONCEPTOS COMISIONABLES</strong></div>
					<div  style='float:left; text-align:center;font:16px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>Nº PAX</strong></div>			
					<div  style='float:left; text-align:center; font:16px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong>PRECIO PAX</strong></div>
					<div  style='float:left; text-align:right; font:16px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>TOTAL<</strong></div>
				</div>
												
				<div style='clear:left;'>									
					<div style='float:left; font:13px Arial, Sans-serif; padding: 5px 10px; width:320px;'>".$sdesglose_factura[$i]['p_detalle']."</div>
					<div style='float:left; text-align:center; font:13px Arial, Sans-serif; padding: 5px 10px; width:80px;'>".$sdesglose_factura[$i]['p_pax']."</div>				
					<div  style='float:left; text-align:center; font:13px Arial, Sans-serif; padding: 5px 10px; width:120px;'>".$sdesglose_factura[$i]['p_precio_pax']."</div>
					<div  style='float:left; text-align:right; font:13px Arial, Sans-serif; padding: 5px 10px; width:80px;'>".$sdesglose_factura[$i]['p_total']."</div>									
				</div>
										
				<div style='clear:left;'>									
					<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:320px;'>&nbsp;</div>
					<div style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'>&nbsp;</div>				
					<div  style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong>SUBTOTAL</strong></div>
					<div  style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>".$factura_subtotal_comisionable." €</strong></div>									
				</div>
												
			</div>
												
												
			<div style='-moz-border-radius:15px;border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 700px; height: ".$alto_no_comisionables."px; padding: 10px 0px; margin: 10px 0px;'>

				<div style='clear:left;'>									
					<div  style='float:left; font:16px Arial, Sans-serif; padding: 5px 10px; width:320px;'><strong>CONCEPTOS NO COMISIONABLES</strong></div>
					<div  style='float:left; text-align:center;font:16px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>Nº PAX</strong></div>			
					<div  style='float:left; text-align:center; font:16px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong>PRECIO PAX</strong></div>
					<div  style='float:left; text-align:right; font:16px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>TOTAL<</strong></div>							
				</div>
												
				<div style='clear:left;'>									
					<div style='float:left; font:13px Arial, Sans-serif; padding: 5px 10px; width:320px;'>".$sdesglose_factura_no_comisionables[$i]['p_detalle']."</div>
					<div style='float:left; text-align:center; font:13px Arial, Sans-serif; padding: 5px 10px; width:80px;'>".$sdesglose_factura_no_comisionables[$i]['p_pax']."</div>				
					<div  style='float:left; text-align:center; font:13px Arial, Sans-serif; padding: 5px 10px; width:120px;'>".$sdesglose_factura_no_comisionables[$i]['p_precio_pax']."</div>
					<div  style='float:left; text-align:right; font:13px Arial, Sans-serif; padding: 5px 10px; width:80px;'>".$sdesglose_factura_no_comisionables[$i]['p_total']."</div>									
				</div>
				<div style='clear:left;'>									
					<div style='float:left; font:15px Arial, Sans-serif; padding: 5px 10px; width:320px;'>&nbsp;</div>
					<div style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'>&nbsp;</div>				
					<div  style='float:left; text-align:center; font:15px Arial, Sans-serif; padding: 5px 10px; width:120px;'><strong>SUBTOTAL</strong></div>
					<div  style='float:left; text-align:right; font:15px Arial, Sans-serif; padding: 5px 10px; width:80px;'><strong>".$factura_subtotal_no_comisionable." €</strong></div>									
				</div>
			</div>									

								
			<div style='float:right; -moz-border-radius:15px; border-radius:15px; font:12px Arial, Sans-serif; border: 1px solid #000; width: 390px; height: 165px; padding: 10px 0px; margin: 10px 0px;'>
												
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
												
			</div>	
											
			<div style='clear:left; width: 700px; height: 14px; margin: 0px 0px;'>
												
				<div style='font:12px Arial, Sans-serif; padding: 0px 0px; width:700px;'><strong> DATOS BANCARIOS: LA CAIXA - IBAN: ES14 2100 1633 5502 0023 6985</strong></div>
												
				<div style='font:9px Arial, Sans-serif; padding: 0px 0px; width:700px;'><strong>VIAJES Y OCIO HITS, S.L. CIF B-86915287. Inscrita en el Registro Mercantil de Madrid, al tomo 31.854, libro 0, folio 194, sección 8, hoja M-573251, inscripción 1ª.</strong></div>
			</div>
		</div>								
	</body>
</html>



?>


