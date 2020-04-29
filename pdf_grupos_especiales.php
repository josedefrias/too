<?php
# Cargamos la librería dompdf.
	require_once 'dompdf_config.inc.php';
 	include 'funciones_php/conexiones.php';

$parametrosg = $_GET;

$id_grupo = $parametrosg['id'];

$fecha_emision = date("d/m/Y"); 
$fecha_impresion = date("d-m-Y"); 
$hora_impresion = date("H:i"); 

$conexion = conexion_hit();

					//OBTENEMOS DATOS DEL GRUPO
					$datos_grupo =$conexion->query("SELECT 
										g.id,
										case when m.nombre = '' then '&nbsp;' else m.nombre end nombre_agencia, 
										case when o.oficina = '' then '&nbsp;' else o.oficina  end oficina_agencia, 
										case when o.direccion = '' then '&nbsp;' else o.direccion end direccion_agencia, 
										case when o.localidad = '' then '&nbsp;' else o.localidad end localidad_agencia, 
										case when o.telefono = '' then '&nbsp;' else o.telefono end telefono_agencia, 
										case when o.mail = '' then '&nbsp;' else o.mail end mail_agencia, 
										case when g.clave_oficina = '' then '&nbsp;' else g.clave_oficina end clave_oficina, 
										case when g.persona_contacto = '' then '&nbsp;' else g.persona_contacto end persona_contacto, 
										case when g.estado = '' then '&nbsp;' else g.estado end estado, 
										case when g.telefono = '' then '&nbsp;' else g.telefono end telefono,
										case when g.email = '' then '&nbsp;' else g.email end email,
										case when g.nombre = '' then '&nbsp;' else g.nombre end nombre,
										case when g.tipo_viaje = 'PAQ' then 'Paquete Vacacional' 
											  when g.tipo_viaje = 'SVO' then 'Solo Vuelo' 
											  when g.tipo_viaje = 'SHO' then 'Solo Hotel' 
											  when g.tipo_viaje = 'ENT' then 'Entradas' 
										else g.tipo_viaje end tipo_viaje,
										case when g.tipo_grupo = 'ADU' then 'Adultos' 
											  when g.tipo_grupo = 'INC' then 'Incentivo' 
											  when g.tipo_grupo = 'TER' then 'Tercera Edad' 
											  when g.tipo_grupo = 'COL' then 'Colectivo' 
											  when g.tipo_grupo = 'DEP' then 'Deportivo' 
											  when g.tipo_grupo = 'EST' then 'Estudiantes' 
										else g.tipo_grupo end tipo_grupo,
										case when g.adultos_minimo = '' then '&nbsp;' else g.adultos_minimo end adultos_minimo,
										case when g.adultos_maximo = '' then '&nbsp;' else g.adultos_maximo end adultos_maximo,
										case when g.ninos_minimo = '' then '&nbsp;' else g.ninos_minimo end ninos_minimo,
										case when g.ninos_maximo = '' then '&nbsp;' else g.ninos_maximo end ninos_maximo,
										case when g.ninos_edades = '' then '&nbsp;' else g.ninos_edades end ninos_edades,
										case when g.bebes_minimo = '' then '&nbsp;' else g.bebes_minimo end bebes_minimo,
										case when g.bebes_maximo = '' then '&nbsp;' else g.bebes_maximo end bebes_maximo,
										DATE_FORMAT(g.fecha_salida, '%d-%m-%Y') AS fecha_salida,
										DATE_FORMAT(g.fecha_regreso, '%d-%m-%Y') AS fecha_regreso,
										case when g.noches_fechas = '' then '&nbsp;' else g.noches_fechas end noches_fechas, 
										case when g.anno = '' then '&nbsp;' else g.anno  end anno, 
										case when g.mes = '' then '&nbsp;' else g.mes end mes, 
										case when g.periodo = '' then '&nbsp;' else g.periodo end periodo, 
										case when g.noches_periodo = '' then '&nbsp;' else g.noches_periodo end noches_periodo,
										case when g.origen = '' then '&nbsp;' else g.origen end origen, 
										case when d.nombre = '' then '&nbsp;' else d.nombre end destino,
										case when g.categoria = '' then '&nbsp;' else g.categoria end categoria,
										case when g.situacion = '' then '&nbsp;' else g.situacion end situacion,
										case when g.regimen = '' then '&nbsp;' else g.regimen end regimen,
										case when g.otros_aspectos = '' then '&nbsp;' else g.otros_aspectos end otros_aspectos,

										case when g.traslado_entrada = '' then '&nbsp;' else g.traslado_entrada end traslado_entrada,

										case when g.traslado_entrada = 'C' then 'Traslados Colectivos' 
											 when g.traslado_entrada = 'P' then 'Traslados privados' 
											 when g.traslado_entrada = 'N' then 'Traslados no incluidos' 
										else g.traslado_entrada end traslado_entrada_literal,


										case when g.traslado_salida = '' then '&nbsp;' else g.traslado_salida end traslado_salida,

										case when g.traslado_salida = 'C' then 'Traslados Colectivos' 
											 when g.traslado_salida = 'P' then 'Traslados privados' 
											 when g.traslado_salida = 'N' then 'Traslados no incluidos' 
										else g.traslado_salida end traslado_salida_literal,


										case when g.seguro_opcional = '' then '&nbsp;' else g.seguro_opcional end seguro_opcional, 
										case when g.excursiones = '' then '&nbsp;' else g.excursiones end excursiones,
										case when g.entradas = '' then '&nbsp;' else g.entradas end entradas,
   										case when g.observaciones = '' then '&nbsp;' else g.observaciones end observaciones,
										DATE_FORMAT(g.fecha_solicitud, '%d-%m-%Y') AS fecha_solicitud,
										case when g.plazas_pago = '' then '&nbsp;' else g.plazas_pago end plazas_pago,
										case when g.gratuidades = '' then '&nbsp;' else g.gratuidades end gratuidades,
										case when g.responsable_gestion = '' then '&nbsp;' else g.responsable_gestion end responsable_gestion,
										DATE_FORMAT(curdate(), '%d-%m-%Y') AS fecha_ultimo_envio

										from hit_grupos g, hit_oficinas o, hit_minoristas m, hit_destinos d
										where
											g.CLAVE_OFICINA = o.CLAVE
											and o.ID = m.ID 
											and g.DESTINO = d.CODIGO
											and g.ID = '".$id_grupo."'");

					$odatos_grupo = $datos_grupo->fetch_assoc();
					$datos_persona_contacto = $odatos_grupo['persona_contacto'];
					$datos_nombre_agencia = $odatos_grupo['nombre_agencia'];
					$datos_responsable_gestion = $odatos_grupo['responsable_gestion'];
					$datos_fecha_solicitud = $odatos_grupo['fecha_solicitud'];
					$datos_fecha_ultimo_envio = $odatos_grupo['fecha_ultimo_envio'];

					$datos_nombre = $odatos_grupo['nombre'];
					$datos_tipo_viaje = $odatos_grupo['tipo_viaje'];
					$datos_tipo_grupo = $odatos_grupo['tipo_grupo'];
					$datos_plazas_pago = $odatos_grupo['plazas_pago'];
					$datos_gratuidades = $odatos_grupo['gratuidades'];
					$datos_fecha_salida = $odatos_grupo['fecha_salida'];
					$datos_fecha_regreso = $odatos_grupo['fecha_regreso'];

					$datos_traslado_entrada = $odatos_grupo['traslado_entrada'];
					$datos_traslado_salida = $odatos_grupo['traslado_salida'];
					$datos_traslado_entrada_literal = $odatos_grupo['traslado_entrada_literal'];
					$datos_traslado_salida_literal = $odatos_grupo['traslado_salida_literal'];

					$datos_destino = $odatos_grupo['destino'];
					$datos_regimen = $odatos_grupo['regimen'];
					$datos_noches = $odatos_grupo['noches_fechas'];




					//OBTENEMOS DATOS DE VUELOS
					$resultado_vuelos =$conexion->query("SELECT id id_vuelo, 
																

																case when tipo = 'I' then 'Vuelo de Ida' 
																	  when tipo = 'V' then 'Vuelo de Regreso' 
																else tipo end tipo_vuelo,

																orden orden_vuelo, cia cia_vuelo, vuelo vuelo_vuelo, origen origen_vuelo, destino destino_vuelo, 
						time_format(hora_salida, '%H:%i') AS hora_salida_vuelo, time_format(hora_llegada, '%H:%i') AS hora_llegada_vuelo FROM hit_grupos_vuelos 
						where id_grupo = ".$id_grupo." order by orden");
					$vuelos = array();
					for ($num_fila = 0; $num_fila < $resultado_vuelos->num_rows; $num_fila++) {
						$resultado_vuelos->data_seek($num_fila);
						$fila = $resultado_vuelos->fetch_assoc();
						array_push($vuelos,$fila);
					}
					//Liberar Memoria usada por la consulta
					//$resultado_vuelos->close();


					//OBTENEMOS DATOS DE PRIMER VUELO DE IDA
					$resultado_primer_vuelo_ida =$conexion->query("SELECT id id_primer_vuelo_ida 
																   FROM hit_grupos_vuelos 
															       where id_grupo = ".$id_grupo." 
																	and tipo = 'I'
																	and id = (select min(id) from hit_grupos_vuelos where id_grupo = ".$id_grupo." and tipo = 'I')");
					$oresultado_primer_vuelo_ida = $resultado_primer_vuelo_ida->fetch_assoc();
					$primer_vuelo_ida = $oresultado_primer_vuelo_ida['id_primer_vuelo_ida'];
					//Liberar Memoria usada por la consulta
					//$resultado_primer_vuelo_ida->close();

					//OBTENEMOS DATOS DE PRIMER VUELO DE VUELTA
					$resultado_primer_vuelo_vuelta =$conexion->query("SELECT id id_primer_vuelo_vuelta
																   FROM hit_grupos_vuelos 
																   where id_grupo = ".$id_grupo."
																	and tipo = 'V'
																	and id = (select min(id) from hit_grupos_vuelos where id_grupo = ".$id_grupo." and tipo = 'V')");
					$oresultado_primer_vuelo_vuelta = $resultado_primer_vuelo_vuelta->fetch_assoc();
					$primer_vuelo_vuelta = $oresultado_primer_vuelo_vuelta['id_primer_vuelo_vuelta'];
					//Liberar Memoria usada por la consulta
					//$resultado_primer_vuelo_vuelta->close();


					//OBTENEMOS DATOS DE PRESUPUESTOS
					$resultado_presupuestos =$conexion->query("SELECT p.id id_presupuesto, 
																	  p.orden orden_presupuesto, 
																	  a.nombre alojamiento_presupuesto,
																	  c.NOMBRE alojamiento_categoria,
																	  a.LOCALIDAD alojamiento_localidad,
																	  p.doble doble_presupuesto, 
																	  p.single single_presupuesto, 
																	  p.triple triple_presupuesto, 
																	  p.multiple multiple_presupuesto, 
																	  p.ninos ninos_presupuesto,
																	  p.bebes bebes_presupuesto, 
																	  p.bebes_maximo bebes_maximo_presupuesto, 
																	  p.tasas tasas_presupuesto  
															    FROM hit_grupos_presupuestos p, hit_alojamientos a, hit_categorias c
															    WHERE p.ALOJAMIENTO = a.ID and a.CATEGORIA = c.CODIGO and id_grupo = ".$id_grupo." ORDER BY p.orden");

					$presupuestos = array();
					for ($num_fila = 0; $num_fila < $resultado_presupuestos->num_rows; $num_fila++) {
						$resultado_presupuestos->data_seek($num_fila);
						$fila = $resultado_presupuestos->fetch_assoc();
						array_push($presupuestos,$fila);
					}
					//Liberar Memoria usada por la consulta
					//$resultado_presupuestos->close();
					$cantidad_presupuestos = count($presupuestos);


				
					//CABECERA
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
					</script>";	

											
			
					
						$mensaje_html .= "

						
						<div style='clear:left; height:1000px; -moz-border-radius:15px;border-radius:15px; border: 1px solid #000; margin: 5px 0px;'>


						<div style='clear:left; margin: 10px 10px; height='32'>

							
							<div style='float:left; border: 0px solid #000; width:370; font-size:40px;font-family:helvetica,serif; color:#C4C4C4; padding: 5px 5px; margin: 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>PRESUPUESTO GRUPOS</strong></div>

							<div style='float:left; width:170px; padding: 5px 5px; margin: 10px 5px;'><img src='imagenes/Logo_Mail2.jpg' align='center' height='32px' width='160'></div>

						</div>

						<div style='clear:left; padding: 10px 10px; border: 0px solid #000;'>

							
							<div style='float:left; border: 0px solid #000; background:#B9D305; width:220; font-size:17px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px; margin: 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>DATOS AGENCIA</strong></div>

							<div style='float:left; border: 0px solid #000; background:#B9D305; width:220; font-size:17px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px; margin: 5px 70px; font-weight:normal;text-align:center; valign:center;'><strong>DATOS HI TRAVEL</strong></div>

						</div>

						<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
							
							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:62; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 10px 0px 5px; margin: 10px 5px 0px 5px; font-weight:normal;text-align:left; valign:center;'>Solicitado por:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:128; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 10px 0px 5px; margin: 10px 5px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_persona_contacto."</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:20; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 10px 0px 6px; margin: 10px 0px 0px 65px; font-weight:normal;text-align:left; valign:center;'>De:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:173; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 10px 0px 5px; margin: 10px 5px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_responsable_gestion."</div>

						</div>

						<div style='clear:left; margin: 2px 10px; border: 0px solid #000;'>
							
							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:62; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 10px 0px 5px; margin: 10px 5px 0px 5px; font-weight:normal;text-align:left; valign:center;'>Agencia:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:128; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 10px 0px 5px; margin: 10px 5px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_nombre_agencia."</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:75; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 10px 0px 6px; margin: 10px 0px 0px 65px; font-weight:normal;text-align:left; valign:center;'>Fecha Solicitud:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:118; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 10px 0px 5px; margin: 10px 5px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_fecha_solicitud."</div>

						</div>

						<div style='clear:left; margin: 2px 10px; border: 0px solid #000;'>
							
							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:62; font-size:13px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 10px 0px 5px; margin: 10px 5px 0px 5px; font-weight:normal;text-align:left; valign:center;'>.</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:128; font-size:13px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 10px 0px 5px; margin: 10px 5px 0px 5px; font-weight:normal;text-align:center; valign:center;'>.</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:75; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 10px 0px 6px; margin: 10px 0px 0px 65px; font-weight:normal;text-align:left; valign:center;'>Fecha envío</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:118; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 10px 0px 5px; margin: 10px 5px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_fecha_ultimo_envio."</div>

						</div>

						<div style='clear:left; margin: 15px 10px;'>
							
							<div style='float:left; border: 0px solid #000; background:#B9D305; width:500; font-size:17px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px 2px 2px; margin: 25px 5px 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>DATOS DEL GRUPOS</strong></div>

						</div>

						<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
							
							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 5px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Nombre:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:160; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_nombre."</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 6px; margin: 10px 0px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Servicio:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:100; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_tipo_viaje."</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:25; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 6px; margin: 10px 0px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Tipo:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:80; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_tipo_grupo."</div>

						</div>


						<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
							
							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 5px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Fechas:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:160; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; 
							valign:center;'>".$datos_fecha_salida." - ".$datos_fecha_regreso."</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:70; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 6px; margin: 10px 0px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Plazas de pago:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:70; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_plazas_pago."</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 6px; margin: 10px 0px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Gratuidades:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:45; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_gratuidades."</div>



						</div>					

						

						<div style='clear:left; margin: 15px 10px;'>
							
							<div style='float:left; border: 0px solid #000; background:#B9D305; width:500; font-size:17px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px 2px 2px; margin: 30px 5px 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>SERVICIOS INCLUIDOS</strong></div>

						</div>
						

						<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
							
							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px 0px 5px; margin: 10px 5px 0px 2px; font-weight:normal;text-align:center; valign:center;'>.</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 5px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Origen</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 5px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Destino</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 5px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Nº Vuelo</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:80; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 5px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Horario</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:155; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 5px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Traslados</div>

						</div>";


						for ($i = 0; $i < count($vuelos); $i++) {
							$mensaje_html .= "<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
									<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:left; 
									valign:center;'>".$vuelos[$i]['tipo_vuelo']."</div>

									<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$vuelos[$i]['origen_vuelo']."</div>	

									<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$vuelos[$i]['destino_vuelo']."</div>

									<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$vuelos[$i]['cia_vuelo'].$vuelos[$i]['vuelo_vuelo']."</div>

									<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:80; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$vuelos[$i]['hora_salida_vuelo']." - ".$vuelos[$i]['hora_llegada_vuelo']."</div>";

									if ($primer_vuelo_ida == $vuelos[$i]['id_vuelo']){	
											$mensaje_html .= "<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:155; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_traslado_entrada_literal."</div>";
									}

									if ($primer_vuelo_vuelta == $vuelos[$i]['id_vuelo']){	
											$mensaje_html .= "<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:155; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 10px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_traslado_salida_literal."</div>";
									}

							$mensaje_html .= "</div>";
						}



						$mensaje_html .= "<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
							
							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 15px 5px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Alojamiento:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:150; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 5px; margin: 15px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_destino."</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 6px; margin: 15px 5px 0px 25px; font-weight:normal;text-align:left; valign:center;'>Régimen:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:50; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 10px; margin: 15px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_regimen."</div>

							<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 6px; margin: 15px 5px 0px 25px; font-weight:normal;text-align:left; valign:center;'>Noches:</div>

							<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:50; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 10px; margin: 15px 2px 0px 5px; font-weight:normal;text-align:center; valign:center;'>".$datos_noches."</div>

						</div>";



						for ($i = 0; $i < $cantidad_presupuestos; $i++) {

						  if($i < 2){
							$mensaje_html .= "<div style='clear:left; margin: 15px 10px;'>
							
												<div style='float:left; border: 0px solid #000; background:#B9D305; width:500; font-size:17px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px 2px 2px; margin: 30px 5px 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>PRESUPUESTO ".$presupuestos[$i]['orden_presupuesto']."</strong></div>
											</div>


											<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>

												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:35; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>HOTEL:</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:170; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['alojamiento_presupuesto']."</div>

												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:30; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>CAT:</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:50; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['alojamiento_categoria']."</div>

												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:35; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>ZONA:</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:150; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['alojamiento_localidad']."</div>

									        </div>





									        <div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:400; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'><strong>Precio NETO por PAX en distribución y régimen indicados:</strong></div>
									        </div>



									        <div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>

												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Doble:</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 20px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['doble_presupuesto']."</div>

												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Single:</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 20px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['single_presupuesto']."</div>

												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Triple:</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 20px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['triple_presupuesto']."</div>

												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Multiple:</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 20px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['multiple_presupuesto']."</div>

									        </div>


									        <div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Niños (2 a 12 años):</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 12px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['ninos_presupuesto']."</div>

												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Bebés (0 a 2 años):</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 12px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['bebes_presupuesto']."</div>

												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Máximo bebés:</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['bebes_maximo_presupuesto']."</div>

									        </div>


									        <div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>

												<div style='float:left; border: 0px solid #000; background:#FFFFFF; width:100; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Tasas de Aeropuerto:</div>

												<div style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; margin: 15px 20px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$i]['tasas_presupuesto']."</div>

									        </div>";

							 }		        
							}



						$mensaje_html .= "</div>";



						//SEGUNDA PAGINA DE PRESUPUESTOS
						if(count($presupuestos) > 2){
								$mensaje_html .= "<div style='clear:left; height:998px; -moz-border-radius:15px;border-radius:15px; border: 1px solid #000; margin: 5px 0px;'>

											<table align='center' style='padding: 5px 0px;'>						
											<tr style='clear:left; margin: 10px 10px; height='32'>

												
												<td style='float:left; border: 0px solid #000; width:370; font-size:40px;font-family:helvetica,serif; color:#C4C4C4; padding: 5px 5px; margin: 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>PRESUPUESTO GRUPOS</strong></td>

												<td style='float:left; width:170px; padding: 5px 5px; margin: 10px 5px;'><img src='imagenes/Logo_Mail2.jpg' align='center' height='32px' width='160'></td>

											</tr>
											</table>";




								for ($j = 0; $j < $cantidad_presupuestos; $j++) {



									  if($j >= 2 and $j <= 5){
											$mensaje_html .= "



											<table align='center' style='clear:left; padding: 15px 0px;'>
											
											<tr style='padding: 15px 10px;'>
							
												<td style='float:left; border: 0px solid #000; background:#B9D305; width:500; font-size:17px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 7px 2px 7px; font-weight:normal;text-align:center; valign:center;'><strong>PRESUPUESTO ".$presupuestos[$j]['orden_presupuesto']."</strong></td>
											</tr>
											</table>	

											<table style='clear:left; padding: 5px 10px;'>
											<tr style='padding: 10px 10px; border: 0px solid #000;'>
												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:35; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>HOTEL:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:170; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['alojamiento_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:30; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>CAT:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:50; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['alojamiento_categoria']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:35; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>ZONA:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:150; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['alojamiento_localidad']."</td>

									        </tr>
											</table>


										
											<table style='clear:left; padding: 5px 10px;'>
									        <tr style='padding: 10px 10px; border: 0px solid #000;'>
												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:400; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'><strong>Precio NETO por PAX en distribución y régimen indicados:</strong></td>
									        </tr>
											</table>

											<table style='clear:left; padding: 5px 10px;'>
									        <tr style='padding: 10px 10px; border: 0px solid #000;'>
												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Doble:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['doble_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Single:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['single_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Triple:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['triple_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Multiple:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['multiple_presupuesto']."</td>

									        </tr>
											</table>

											<table style='clear:left; padding: 5px 10px;'>
									        <tr style='padding: 10px 10px; border: 0px solid #000;'>
												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Niños (2 a 12 años):</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['ninos_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Bebés (0 a 2 años):</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['bebes_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Máximo bebés:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['bebes_maximo_presupuesto']."</td>

									        </tr>
											</table>

											<table style='clear:left; padding: 5px 10px;'>
									        <tr style='padding: 10px 10px; border: 0px solid #000;'>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Tasas de Aeropuerto:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['tasas_presupuesto']."</td>

									        </tr>
									        </table>";

										    }
        
								}

								$mensaje_html .= "</div>";									
						}




						//TERCERA PAGINA DE PRESUPUESTOS
						if(count($presupuestos) > 6){
								$mensaje_html .= "<div style='clear:left; height:998px; -moz-border-radius:15px;border-radius:15px; border: 1px solid #000; margin: 5px 0px;'>

											<table align='center' style='padding: 5px 0px;'>						
											<tr style='clear:left; margin: 10px 10px; height='32'>

												
												<td style='float:left; border: 0px solid #000; width:370; font-size:40px;font-family:helvetica,serif; color:#C4C4C4; padding: 5px 5px; margin: 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>PRESUPUESTO GRUPOS</strong></td>

												<td style='float:left; width:170px; padding: 5px 5px; margin: 10px 5px;'><img src='imagenes/Logo_Mail2.jpg' align='center' height='32px' width='160'></td>

											</tr>
											</table>";




								for ($j = 0; $j < $cantidad_presupuestos; $j++) {



									  if($j >= 6 and $j <= 9){
											$mensaje_html .= "



											<table align='center' style='clear:left; padding: 15px 0px;'>
											
											<tr style='padding: 15px 10px;'>
							
												<td style='float:left; border: 0px solid #000; background:#B9D305; width:500; font-size:17px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 7px 2px 7px; font-weight:normal;text-align:center; valign:center;'><strong>PRESUPUESTO ".$presupuestos[$j]['orden_presupuesto']."</strong></td>
											</tr>
											</table>	

											<table style='clear:left; padding: 5px 10px;'>
											<tr style='padding: 10px 10px; border: 0px solid #000;'>
												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:35; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>HOTEL:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:170; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['alojamiento_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:30; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>CAT:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:50; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['alojamiento_categoria']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:35; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>ZONA:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:150; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['alojamiento_localidad']."</td>

									        </tr>
											</table>


										
											<table style='clear:left; padding: 5px 10px;'>
									        <tr style='padding: 10px 10px; border: 0px solid #000;'>
												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:400; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'><strong>Precio NETO por PAX en distribución y régimen indicados:</strong></td>
									        </tr>
											</table>

											<table style='clear:left; padding: 5px 10px;'>
									        <tr style='padding: 10px 10px; border: 0px solid #000;'>
												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Doble:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['doble_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Single:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['single_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Triple:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['triple_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:40; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Multiple:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:63; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['multiple_presupuesto']."</td>

									        </tr>
											</table>

											<table style='clear:left; padding: 5px 10px;'>
									        <tr style='padding: 10px 10px; border: 0px solid #000;'>
												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Niños (2 a 12 años):</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['ninos_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Bebés (0 a 2 años):</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['bebes_presupuesto']."</td>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>Máximo bebés:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['bebes_maximo_presupuesto']."</td>

									        </tr>
											</table>

											<table style='clear:left; padding: 5px 10px;'>
									        <tr style='padding: 10px 10px; border: 0px solid #000;'>

												<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>Tasas de Aeropuerto:</td>

												<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:60; font-size:13px;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>".$presupuestos[$j]['tasas_presupuesto']."</td>

									        </tr>
									        </table>";

										    }
        
								}

								$mensaje_html .= "</div>";									
						}




						//CONDICIONES

						$mensaje_html .= "<div style='height:1000px; -moz-border-radius:15px;border-radius:15px; border: 1px solid #000; margin: 5px 0px;'>

						<div style='clear:left; margin: 10px 10px; height='32'>

							
							<div style='float:left; border: 0px solid #000; width:370; font-size:40px;font-family:helvetica,serif; color:#C4C4C4; padding: 5px 5px; margin: 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>PRESUPUESTO GRUPOS</strong></div>

							<div style='float:left; width:170px; padding: 5px 5px; margin: 10px 5px;'><img src='imagenes/Logo_Mail2.jpg' align='center' height='32px' width='160'></div>

						</div>

						<div style='clear:left; margin: 10px 10px;'>
							
							<div style='float:left; border: 0px solid #000; background:#B9D305; width:500; font-size:17px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px 2px 2px; margin: 10px 5px 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>FORMA DE PAGO</strong></div>
						</div>


						<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
							<p style='font-size:0.7em;
												font-family:helvetica,serif;
												line-height:85%;
												color:#000000;
												margin:10px 10px;
												font-weight:normal;
												text-align:justify;'>Todos los grupos seran prepagados al menos 7 días antes de la salida.<br>
												• Para la formalización del grupo habrá que realizar un depósito del 20% del importe total del grupo (sin tasas) en un plazo no superior a
													72 hs de la confirmación del mismo por parte de HI TRAVEL.<br>
												• 40 días antes un segundo depósito por el 30%.<br>
												• 21 días antes un tercer depósito por el 25%.<br>
												• 7 días antes prepago del 25% restante del importe del grupo.
												</p>
																	

							<p style='font-size:0.7em;
												font-family:helvetica,serif;
												line-height:85%;
												color:#000000;
												margin:10px 10px;
												font-weight:normal;
												font-style: italic;
												text-align:justify;'>
												NOTA IMPORTANTE: HI TRAVEL podrá solicitar una forma de pago distinta si en lo servicios contratados el proveedor requiere fianzas, depósitos o pagos especiales.
											</p>	
						</div>
											

						<div style='clear:left; margin: 10px 10px;'>
							
							<div style='float:left; border: 0px solid #000; background:#B9D305; width:500; font-size:17px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px 2px 2px; margin: 5px 5px 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>CONDICIONES DE CANCELACION</strong></div>
						</div>





						<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
							<p style='font-size:0.7em;
												font-family:helvetica,serif;
												line-height:85%;
												color:#000000;
												margin:10px 10px;
												font-weight:normal;
												text-align:justify;'>En todo momento el usuario o consumidor puede desistir de los servicios solicitados o contratados, teniendo derecho a la devolución de las
													cantidades que hubiera abonado, tanto si se trata del precio total como del anticipo depositado, pero deberá indemnizar a la Agencia por los
													conceptos que a continuación se indican:
							</p>
							<p style='font-size:0.7em;
												font-family:helvetica,serif;
												line-height:85%;
												color:#000000;
												margin:10px 10px;
												font-weight:normal;
												text-align:justify;'>1) Gastos por Cancelación Parcial y/o Total:
							</p>
						</div>



						<table style='clear:left; width:500; padding: 5px 10px;'>

						    <tr style='padding: 10px 10px; border: 0px solid #000;'>

								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:20; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>

								</td>

								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										
								</td>

								<td colspan='2' style='float:left; border: 0px solid #000; background:#A4A2A2; width:90; font-size:14px; font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										CANCELACION PARCIAL
								</td>


								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
	
								</td>

								<td colspan='2' style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#A4A2A2; width:90; font-size:14px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										CANCELACION TOTAL
								</td>

					        </tr>



						    <tr style='padding: 10px 10px; border: 0px solid #000;'>

								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:30; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>

								</td>

								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										
								</td>

								<td style='float:left; border: 0px solid #000; background:#D7D7D7; width:45; font-size:0.8emfont-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										Grupos menos de 60 pax
								</td>

								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:45; font-size:0.8em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										Grupos más de 60 pax
								</td>

								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
	
								</td>

								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#D7D7D7; width:45; font-size:0.8em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										Grupos menos de 60 pax
								</td>

								<td style='float:left; border: 0px solid #000; background:#D7D7D7; width:45; font-size:0.8em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										Grupos más de 60 pax
								</td>

					        </tr>


						    <tr style='padding: 10px 10px; border: 0px solid #000;'>

								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:20; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>

								</td>

								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										
								</td>

								<td style='float:left; border: 0px solid #000; background:#F0F0F0; width:45; font-size:0.7emfont-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										- 20% Pax
								</td>

								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#F0F0F0; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										+ 20% Pax
								</td>

								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
	
								</td>

								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										5%
								</td>

								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										10%
								</td>

					        </tr>




						    <tr style='padding: 10px 10px; border: 0px solid #000;'>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:18; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>
										de 60 a 46 días antes de la salida *:
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7emfont-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										0%
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										0%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										10%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										20%
								</td>
					        </tr>

						    <tr style='padding: 10px 10px; border: 0px solid #000;'>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:18; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>
										de 45 a 31 días antes de la salida :
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7emfont-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										0%
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										10%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										30%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										40%
								</td>
					        </tr>

						    <tr style='padding: 10px 10px; border: 0px solid #000;'>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:18; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>
										de 30 a 22 días antes de la salida :
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7emfont-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										10%
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										20%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										50%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										60%
								</td>
					        </tr>

						    <tr style='padding: 10px 10px; border: 0px solid #000;'>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:18; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>
										de 21 a 15 días antes de la salida :
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7emfont-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										25%
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										50%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										50%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										80%
								</td>
					        </tr>

						    <tr style='padding: 10px 10px; border: 0px solid #000;'>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:18; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>
										de 14 a 7 días antes de la salida :
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7emfont-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										50%
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										80%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										75%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										90%
								</td>
					        </tr>

						    <tr style='padding: 10px 10px; border: 0px solid #000;'>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:18; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>
										de 6 a 3 días de la salida :
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7emfont-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										75%
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										90%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										100%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										100%
								</td>
					        </tr>

						    <tr style='padding: 10px 10px; border: 0px solid #000;'>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:18; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>
										en los 2 días antes de la salida:
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7emfont-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										100%
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										100%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										100%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										100%
								</td>
					        </tr>

						    <tr style='padding: 10px 10px; border: 0px solid #000;'>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:90; height:18; font-size:0.7em; font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:left; valign:center;'>
										No show
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7emfont-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										100%
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										100%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:10; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
								</td>
								<td style='float:left; -moz-border-radius:5px;border-radius:5px; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										100%
								</td>
								<td style='float:left; border: 0px solid #000; background:#FFFFFF; width:45; font-size:0.7em;font-family:helvetica,serif; color:#000000; padding: 2px 2px 0px 2px; font-weight:normal;text-align:center; valign:center;'>
										100%
								</td>
					        </tr>

						</table>



						<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
							<p style='font-size:0.7em;
												font-family:helvetica,serif;
												line-height:85%;
												color:#000000;
												margin:10px 10px;
												font-weight:normal;
												text-align:justify;'>* NOTA IMPORTANTE POR CANCELACION TOTAL: Todo grupo que supere los 60 días reservados y sea cancelado, conllevará un 5
													% de gastos, en concepto de penalización por bloqueo de espacio, aunque sea con más de 60 días de antelación a la salida del grupo.
							</p>
							<p style='font-size:0.7em;
												font-family:helvetica,serif;
												line-height:85%;
												color:#000000;
												margin:10px 10px;
												font-weight:normal;
												text-align:justify;'>2) En el caso de que alguno de los servicios contratados y anulados estuviera sujeto a condiciones económicas especiales de contratación,
													tales como el flete de aviones, buques, tarifas especiales, etc., los gastos de anulación por desistimiento se establecerán de acuerdo con
													las condiciones informadas y/o acordadas entre las partes.
							</p>
							<p style='font-size:0.7em;
												font-family:helvetica,serif;
												line-height:85%;
												color:#000000;
												margin:10px 10px;
												font-weight:normal;
												text-align:justify;'>3) Los billetes de avión después de emitidos, conllevan 100% de gastos.
							</p>
						</div>


						<div style='clear:left; margin: 10px 10px;'>
							
							<div style='float:left; border: 0px solid #000; background:#B9D305; width:500; font-size:17px;font-family:helvetica,serif; color:#FFFFFF; padding: 2px 2px 2px 2px; margin: 5px 5px 5px 5px; font-weight:normal;text-align:center; valign:center;'><strong>ROOMING LIST</strong></div>
						</div>





						<div style='clear:left; margin: 10px 10px; border: 0px solid #000;'>
							<p style='font-size:0.7em;
												font-family:helvetica,serif;
												line-height:85%;
												color:#000000;
												margin:10px 10px;
												font-weight:normal;
												text-align:justify;'>21 días antes de la salida, envío de la ROOMING LIST DEFINITIVA del grupo.
							</p>

						</div>


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
$nombre_pdf = "Hitravel_Grupo_".$id_grupo.".pdf";
$mipdf ->stream($nombre_pdf);

?>


