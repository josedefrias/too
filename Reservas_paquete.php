<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/Reservas.cls.php';
	//require("class.phpmailer.php"); 
	include("class.smtp.php");

	session_start();

	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];
	$conexion = conexion_hit();

	/*echo('<pre>');
	print_r($usuario);
	echo('-');
	print_r($nombre);
	echo('</pre>');*/

	/*echo('<pre>');
	print_r($_POST);
	echo('</pre>');*/

	$parametros = $_POST;
	$parametrosg = $_GET;


			//VARIABLES PARA LOS DATOS GENERALES
			$recuperafolleto = '';
			$recuperacuadro = '';
			$recuperaciudad = '';
			$recuperaopcion = '';
			$recuperafecha = '';
			$recuperapaquete = '';
			$recuperaregimen = '';
			$recuperaadultos = '';
			$recuperaninos= '';
			$recuperabebes= '';
			$recuperanovios = '';
			$recuperajubilados = '';
			$recuperahabitacion1 = '';
			$recuperacaracteristica1 = '';
			$recuperacantidad1 = '';
			$recuperahabitacion2 = '';
			$recuperacaracteristica2 = '';
			$recuperacantidad2 = '';
			$recuperahabitacion3 = '';
			$recuperacaracteristica3 = '';
			$recuperacantidad3 = '';

			$recuperacantidad_habitaciones = '';
			$recuperahabitacion1_caracteristica = '';
			$recuperahabitacion1_adultos = '';
			$recuperahabitacion1_ninos = '';
			$recuperahabitacion1_bebes = '';
			$recuperahabitacion1_novios = '';
			$recuperahabitacion1_jubilados = '';
			$recuperahabitacion2_caracteristica = '';
			$recuperahabitacion2_adultos = '';
			$recuperahabitacion2_ninos = '';
			$recuperahabitacion2_bebes = '';
			$recuperahabitacion2_novios = '';
			$recuperahabitacion2_jubilados = '';
			$recuperahabitacion3_caracteristica = '';
			$recuperahabitacion3_adultos = '';
			$recuperahabitacion3_ninos = '';
			$recuperahabitacion3_bebes = '';
			$recuperahabitacion3_novios = '';
			$recuperahabitacion3_jubilados = '';
			$recuperahabitacion4_caracteristica = '';
			$recuperahabitacion4_adultos = '';
			$recuperahabitacion4_ninos = '';
			$recuperahabitacion4_bebes = '';
			$recuperahabitacion4_novios = '';
			$recuperahabitacion4_jubilados = '';

			$recuperaclave_agencia = '';
			$recuperaagente = '';
			$recuperareferencia_agencia = '';
			$recuperaobservaciones = '';

			$mensaje1 = '';
			$mensaje2 = '';
			$Mensaje = '';
			$error = '';
			$mail_usuario = '';


	if(count($_POST) != 0){

		if($parametros['ir_a'] != 'S'){

			//---------------------------------------
			//---SECCION PRINCIPAL DEL CODIGO--------
			//---------------------------------------

			if($parametros['reservar'] == 'S'){
				//COMPROAR QUE SE HAN TECLEADO TODOS LOS DATOS PARA PODER REALIZAR LA RESERVA
				if($parametros['ciudad'] != '' 
					and  @$parametros['destino'] != '' 
					and  @$parametros['producto'] != '' 
					and  @$parametros['fecha'] != '' 
					and  @$parametros['noches'] != '' 
					and  @$parametros['clave_agencia'] != ''){

						//obtenemos folleto, cuadro y paquete segun la seleccion del usuario:
					if(@$parametros['producto'] != 'SVO' and @$parametros['producto'] != 'OSV' and @$parametros['producto'] != 'SSV'){
						$datos_cuadro =$conexion->query("select distinct al.FOLLETO folleto, al.CUADRO cuadro, al.PAQUETE paquete
															from hit_producto_cuadros c,
																  hit_producto_cuadros_aereos a,
																  hit_producto_cuadros_aereos_precios p,
																  hit_producto_cuadros_salidas s,
																  hit_destinos d,
																  hit_productos pr,
																  hit_producto_cuadros_alojamientos al
															where
																c.CLAVE = a.CLAVE_CUADRO
																and a.FOLLETO = p.FOLLETO
																and a.CUADRO = p.CUADRO
																and a.CIUDAD = p.CIUDAD
																and a.OPCION = p.OPCION
																and a.NUMERO = p.NUMERO
																and c.CLAVE = s.CLAVE_CUADRO
																and c.DESTINO = d.CODIGO
																and s.FECHA between p.FECHA_DESDE and p.FECHA_HASTA
																and s.FECHA > CURDATE()
																and c.PRODUCTO = pr.CODIGO
																and pr.VISIBLE = 'S'
																and c.CLAVE = al.CLAVE_CUADRO
																and a.CIUDAD = '".@$parametros['ciudad']."'
																and c.DESTINO = '".@$parametros['destino']."' 
																and c.PRODUCTO = '".@$parametros['producto']."'
																and s.FECHA = '".@$parametros['fecha']."' 
																and al.NOCHES = '".@$parametros['noches']."'
																and al.ALOJAMIENTO = '".@$parametros['alojamiento']."' 
																and al.CARACTERISTICA = '".@$parametros['caracteristica']."'");
					}else{
						$datos_cuadro =$conexion->query("select distinct c.FOLLETO folleto, c.CUADRO cuadro, '' paquete
															from hit_producto_cuadros c,
																  hit_producto_cuadros_aereos a,
																  hit_producto_cuadros_aereos_precios p,
																  hit_producto_cuadros_salidas s,
																  hit_destinos d,
																  hit_productos pr
															where
																c.CLAVE = a.CLAVE_CUADRO
																and a.FOLLETO = p.FOLLETO
																and a.CUADRO = p.CUADRO
																and a.CIUDAD = p.CIUDAD
																and a.OPCION = p.OPCION
																and a.NUMERO = p.NUMERO
																and c.CLAVE = s.CLAVE_CUADRO
																and c.DESTINO = d.CODIGO
																and s.FECHA between p.FECHA_DESDE and p.FECHA_HASTA
																and s.FECHA > CURDATE()
																and c.PRODUCTO = pr.CODIGO
																and pr.VISIBLE = 'S'
																and a.CIUDAD = '".@$parametros['ciudad']."'
																and c.DESTINO = '".@$parametros['destino']."' 
																and c.PRODUCTO = '".@$parametros['producto']."'
																and s.FECHA = '".@$parametros['fecha']."' 
																and '".@$parametros['noches']."' in (select pae.DIA from hit_producto_cuadros_aereos pae where pae.CLAVE_CUADRO = c.CLAVE and pae.ciudad = a.CIUDAD)");
					}

						$datos_codigos = $datos_cuadro->fetch_assoc();
						$folleto = $datos_codigos['folleto'];
						$cuadro = $datos_codigos['cuadro'];
						$paquete = $datos_codigos['paquete'];

						//echo('-'.@$parametros['ciudad'].@$parametros['destino'].@$parametros['producto'].@$parametros['fecha'].@$parametros['noches'].@$parametros['alojamiento'].@$parametros['caracteristica'].@$folleto.@$cuadro.@$paquete);
						//Hay que procurar cambiar el procedimiento de la base de datos de realizacion de reserva para adaptarlo a esto.


						//obtenemos totales de pax segun los datos de las habitaciones tecleados por el usuario:
						if($parametros['producto'] != 'SVO'){


								if($parametros['cantidad_habitaciones'] == 1){
										$adultos = $parametros['habitacion1_adultos'];
										$ninos = $parametros['habitacion1_ninos'];
										$bebes = $parametros['habitacion1_bebes'];
										$novios = 0;						
										if($parametros['habitacion1_novios'] == 'S'){						
											$novios = $novios + 2;
										}
										$jubilados = $parametros['habitacion1_jubilados'];

								}elseif($parametros['cantidad_habitaciones'] == 2){
										$adultos = $parametros['habitacion1_adultos'] + $parametros['habitacion2_adultos'];
										$ninos = $parametros['habitacion1_ninos'] + $parametros['habitacion2_ninos'];
										$bebes = $parametros['habitacion1_bebes'] +$parametros['habitacion2_bebes'];
										$novios = 0;						
										if($parametros['habitacion1_novios'] == 'S'){						
											$novios = $novios + 2;
										}
										if($parametros['habitacion2_novios'] == 'S'){						
											$novios = $novios + 2;
										}
										$jubilados = $parametros['habitacion1_jubilados'] +$parametros['habitacion2_jubilados'];

								}elseif($parametros['cantidad_habitaciones'] == 3){
										$adultos = $parametros['habitacion1_adultos'] + $parametros['habitacion2_adultos'] + $parametros['habitacion3_adultos'];
										$ninos = $parametros['habitacion1_ninos'] + $parametros['habitacion2_ninos'] + $parametros['habitacion3_ninos'];
										$bebes = $parametros['habitacion1_bebes'] +$parametros['habitacion2_bebes'] +$parametros['habitacion3_bebes'];
										$novios = 0;						
										if($parametros['habitacion1_novios'] == 'S'){						
											$novios = $novios + 2;
										}
										if($parametros['habitacion2_novios'] == 'S'){						
											$novios = $novios + 2;
										}
										if($parametros['habitacion3_novios'] == 'S'){						
											$novios = $novios + 2;
										}
										$jubilados = $parametros['habitacion1_jubilados'] +$parametros['habitacion2_jubilados'] +$parametros['habitacion3_jubilados'];

								}elseif($parametros['cantidad_habitaciones'] == 4){
										$adultos = $parametros['habitacion1_adultos'] + $parametros['habitacion2_adultos'] + $parametros['habitacion3_adultos'] + $parametros['habitacion4_adultos'];
										$ninos = $parametros['habitacion1_ninos'] + $parametros['habitacion2_ninos'] + $parametros['habitacion3_ninos']+ $parametros['habitacion4_ninos'];
										$bebes = $parametros['habitacion1_bebes'] +$parametros['habitacion2_bebes'] +$parametros['habitacion3_bebes'] +$parametros['habitacion4_bebes'];
										$novios = 0;						
										if($parametros['habitacion1_novios'] == 'S'){						
											$novios = $novios + 2;
										}
										if($parametros['habitacion2_novios'] == 'S'){						
											$novios = $novios + 2;
										}
										if($parametros['habitacion3_novios'] == 'S'){						
											$novios = $novios + 2;
										}
										if($parametros['habitacion4_novios'] == 'S'){						
											$novios = $novios + 2;
										}
										$jubilados = $parametros['habitacion1_jubilados'] +$parametros['habitacion2_jubilados'] +$parametros['habitacion3_jubilados'] +$parametros['habitacion4_jubilados'];
								}
						}else{
							$adultos = $parametros['solo_vuelo_adultos'];
							$ninos = $parametros['solo_vuelo_ninos'];
							$bebes = 0;
							$novios = 0;
							$jubilados = 0;
						}

						//echo($adultos.'-'.$ninos);
						if($parametros['producto'] != 'SVO' and ($adultos + $ninos > 9 or $adultos + $ninos == 0)){
							$error = 'Para reservas de paquete no esta permitido numero de pasajeros mayor que nueve o cero.';
						}elseif($parametros['producto'] == 'SVO' and ($adultos + $ninos == 0)){
							$error = 'Para reservas de solo vuelo o solo servicios no esta permitido numero de pasajeros cero.';							
						}else{
								//echo('-'.@$adultos.'-'.@$ninos.'-'.@$ninos2.'-'.@$bebes.'-'.@$novios.'-'.@$jublados.'-');
								//LLAMAR A FUNCION DE RESERVA
								//Nota: Ponemos una '@' delante de la instruccion para evitar que el navegador muestre los warning de mysql.
								$reserva =@$conexion->query("SELECT `RESERVAS_REALIZAR_RESERVA`('R','".$usuario."', '".$folleto."', '".$cuadro."', '".$parametros['ciudad']."', '".$parametros['opcion']."', '".$parametros['fecha']."', '".$paquete."', '".$parametros['regimen']."',
								
								
								'".$adultos."', '".$ninos."', '".$bebes."', '".$novios."', '".$jubilados."', 

								'".$parametros['cantidad_habitaciones']."',
								'".$parametros['habitacion1_caracteristica']."', '".$parametros['habitacion1_adultos']."', 
								'".$parametros['habitacion1_ninos']."', '".$parametros['habitacion1_bebes']."', '".$parametros['habitacion1_novios']."', '".$parametros['habitacion1_jubilados']."',
								
								'".$parametros['habitacion2_caracteristica']."', '".$parametros['habitacion2_adultos']."', '".$parametros['habitacion2_ninos']."', '".$parametros['habitacion2_bebes']."', '".$parametros['habitacion2_novios']."', '".$parametros['habitacion2_jubilados']."', 
								'".$parametros['habitacion3_caracteristica']."', '".$parametros['habitacion3_adultos']."', '".$parametros['habitacion3_ninos']."', '".$parametros['habitacion3_bebes']."', '".$parametros['habitacion3_novios']."', '".$parametros['habitacion3_jubilados']."', 			
								'".$parametros['habitacion4_caracteristica']."', '".$parametros['habitacion4_adultos']."', '".$parametros['habitacion4_ninos']."', '".$parametros['habitacion4_bebes']."', '".$parametros['habitacion4_novios']."', '".$parametros['habitacion4_jubilados']."',
								

								'".$parametros['pasajero1_numero']."', '".$parametros['pasajero1_habitacion']."', '".$parametros['pasajero1_sexo']."', '".$parametros['pasajero1_apellidos']."', '".$parametros['pasajero1_nombre']."', '".$parametros['pasajero1_tipo']."', 
								'".$parametros['pasajero1_edad']."',
								'".$parametros['pasajero2_numero']."', '".$parametros['pasajero2_habitacion']."', '".$parametros['pasajero2_sexo']."', '".$parametros['pasajero2_apellidos']."', '".$parametros['pasajero2_nombre']."', '".$parametros['pasajero2_tipo']."', 
								'".$parametros['pasajero2_edad']."',
								'".$parametros['pasajero3_numero']."', '".$parametros['pasajero3_habitacion']."', '".$parametros['pasajero3_sexo']."', '".$parametros['pasajero3_apellidos']."', '".$parametros['pasajero3_nombre']."', '".$parametros['pasajero3_tipo']."', 
								'".$parametros['pasajero3_edad']."',
								'".$parametros['pasajero4_numero']."', '".$parametros['pasajero4_habitacion']."', '".$parametros['pasajero4_sexo']."', '".$parametros['pasajero4_apellidos']."', '".$parametros['pasajero4_nombre']."', '".$parametros['pasajero4_tipo']."', 
								'".$parametros['pasajero4_edad']."',
								'".$parametros['pasajero5_numero']."', '".$parametros['pasajero5_habitacion']."', '".$parametros['pasajero5_sexo']."', '".$parametros['pasajero5_apellidos']."', '".$parametros['pasajero5_nombre']."', '".$parametros['pasajero5_tipo']."', 
								'".$parametros['pasajero5_edad']."',
								'".$parametros['pasajero6_numero']."', '".$parametros['pasajero6_habitacion']."', '".$parametros['pasajero6_sexo']."', '".$parametros['pasajero6_apellidos']."', '".$parametros['pasajero6_nombre']."', '".$parametros['pasajero6_tipo']."', 
								'".$parametros['pasajero6_edad']."',
								'".$parametros['pasajero7_numero']."', '".$parametros['pasajero7_habitacion']."', '".$parametros['pasajero7_sexo']."', '".$parametros['pasajero7_apellidos']."', '".$parametros['pasajero7_nombre']."', '".$parametros['pasajero7_tipo']."', 
								'".$parametros['pasajero7_edad']."',
								'".$parametros['pasajero8_numero']."', '".$parametros['pasajero8_habitacion']."', '".$parametros['pasajero8_sexo']."', '".$parametros['pasajero8_apellidos']."', '".$parametros['pasajero8_nombre']."', '".$parametros['pasajero8_tipo']."', 
								'".$parametros['pasajero8_edad']."',
								'".$parametros['pasajero9_numero']."', '".$parametros['pasajero9_habitacion']."', '".$parametros['pasajero1_sexo']."', '".$parametros['pasajero9_apellidos']."', '".$parametros['pasajero9_nombre']."', '".$parametros['pasajero9_tipo']."', 
								'".$parametros['pasajero9_edad']."',


								'".$parametros['clave_agencia']."', '".$parametros['agente']."', '".$parametros['referencia_agencia']."', '".$parametros['observaciones']."') LOC");

								if ($reserva == FALSE){
									$error = 'No se ha podido realizar la reserva. '.$conexion->error;
								}else{
									$resultado_reserva = $reserva->fetch_assoc();
									
									//---------------------------------------
									//---------------------------------------
									//ENVIAMOS MAIL DE AVISO DE NUEVA RESERVA
									$asunto = "NUEVA RESERVA";
									$mensaje_html = "
									<TABLE border='1' bgcolor='#B0FFFF'>
										<TR colspan='2'>
											<TD>
												<img src='imagenes/Logo_3.jpg' align='center' height='30' width='250'>
											</TD>
										</TR>
										<TR>
											<TD>
												<b>Localizador</b>
											</TD>
											<TD>
												<b>".$resultado_reserva['LOC']."</b>
											</TD>
										</TR>
										<TR>
											<TD>
												<br><b>Cliente</b>
											</TD>
											<TD>
												<b>".$usuario." - ".$nombre."</b>
											</TD>
										</TR>
										<TR>
											<TD>
												<b>Folleto</b>
											</TD>
											<TD>
												<b>".$folleto."</b>
											</TD>
										</TR>
										<TR>
											<TD>
												<b>Cuadro</b>
											</TD>
											<TD>
												<b>".$cuadro."</b>
											</TD>
										</TR>
										<TR>
											<TD>
												<b>Paquete</b>
											</TD>
											<TD>
												<b>".$paquete."</b>
											</TD>
										</TR>
										<TR>
											<TD>
												<b>Fecha de salida</b>
											</TD>
											<TD>
												<b>".$parametros['fecha']."</b>
											</TD>
										</TR>
										<TR>
											<TD>
												<b>Origen</b>
											</TD>
											<TD>
												<b>".$parametros['ciudad']."</b>
											</TD>
										</TR>
									</TABLE>";


									//---------------------------------------
									//---------------------------------------										
									//ENVIO DE MAIL DE CONFIRMACION----------
									
									//Buscamos primero el mail y nombre del usuario
									/*$datos_usuario =$conexion->query("select nombre, email from hit_usuarios u where u.USUARIO = '".$usuario."'");
									$datos_us = $datos_usuario->fetch_assoc();
									$mail_usuario = $datos_us['email'];

									//Enviamos mail llamando a lafuncion 'enviar_mail'
									$envio = enviar_mail($asunto, $mensaje_html, $mail_usuario, $nombre);*/

									//echo($envio);
									//---------------------------------------
									//---------------------------------------

									
									$Mensaje = 'Reserva realizada: '.$resultado_reserva['LOC']; //Esto se sustituira por un enlace a la pantalla de mantenimiento con el parametro localizador.
								}

						}


				}else{

						if($parametros['ciudad'] == ''){
							$error = 'Debe indicar ciudad de origen.';
						}elseif($parametros['destino'] == ''){
							$error = 'Debe indicar destino.';
						}elseif($parametros['producto'] == ''){
							$error = 'Debe indicar producto.';
						}elseif($parametros['fecha'] == ''){
							$error = 'Debe indicar fecha de salida.';
						}elseif($parametros['noches'] == ''){
							$error = 'Debe indicar numero de noches.';
						}elseif(@$parametros['clave_agencia'] == ''){
							$error = 'Debe indicar agencia.';
						}

						//$error = 'Hola: Se deben rellenar todos los campos de la pantalla.';
				}


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error != ''){

					
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
			}

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboCiudades = $oSino->Cargar_Combo_Ciudades_Origen();
			/*$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboHabitaciones = $oSino->Cargar_combo_Habitaciones();
			$comboRegimen = $oSino->Cargar_combo_Regimen();*/
			$combohabitaciones_car = $oSino->Cargar_combo_Habitaciones_car();
			$comboCantidad_Habitaciones = $oSino->Cargar_Combo_Cantidad_Habitaciones();
			$comboHabitaciones_Adultos = $oSino->Cargar_Combo_Habitaciones_Adultos();
			$comboHabitaciones_Ninos = $oSino->Cargar_Combo_Habitaciones_Ninos();
			$comboHabitaciones_Bebes = $oSino->Cargar_Combo_Habitaciones_Bebes();
			$comboHabitaciones_Novios = $oSino->Cargar_Combo_Habitaciones_Novios();
			$comboHabitaciones_Jubilados = $oSino->Cargar_Combo_Habitaciones_Jubilados();
			$comboSoloVuelo_Adultos = $oSino->Cargar_Combo_Solo_Vuelo_Adulos();
			$comboSoloVuelo_Ninos = $oSino->Cargar_Combo_Solo_Vuelo_Ninos();
			$comboPasajeros_Tipo = $oSino->Cargar_combo_Pasajeros_tipo();
			$comboPasajeros_Edad_Adultos = $oSino->Cargar_combo_Pasajeros_edad_adultos();
			$comboPasajeros_Edad_Ninos = $oSino->Cargar_combo_Pasajeros_edad_ninos();
			$comboSexo = $oSino->Cargar_combo_Pasajeros_sexo();


	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('formulario', 'RESERVAS DE PAQUETE');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboCiudades', $comboCiudades);
			/*$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboHabitaciones', $comboHabitaciones);
			$smarty->assign('comboRegimen', $comboRegimen);*/
			$smarty->assign('combohabitaciones_car', $combohabitaciones_car);
			$smarty->assign('comboCantidad_Habitaciones', $comboCantidad_Habitaciones);
			$smarty->assign('comboHabitaciones_Adultos', $comboHabitaciones_Adultos);
			$smarty->assign('comboHabitaciones_Ninos', $comboHabitaciones_Ninos);
			$smarty->assign('comboHabitaciones_Bebes', $comboHabitaciones_Bebes);
			$smarty->assign('comboHabitaciones_Novios', $comboHabitaciones_Novios);
			$smarty->assign('comboHabitaciones_Jubilados', $comboHabitaciones_Jubilados);
			$smarty->assign('comboSoloVuelo_Adultos', $comboSoloVuelo_Adultos);
			$smarty->assign('comboSoloVuelo_Ninos', $comboSoloVuelo_Ninos);
			$smarty->assign('comboPasajeros_Tipo', $comboPasajeros_Tipo);
			//$smarty->assign('comboPasajeros_Edad_Adultos', $comboPasajeros_Edad_Adultos);
			$smarty->assign('comboPasajeros_Edad_Ninos', $comboPasajeros_Edad_Ninos);
			$smarty->assign('comboSexo', $comboSexo);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos tecleados por el usuario para evitar que los tenga que repetir si hay un error o aviso.
			/*$smarty->assign('recuperafolleto', $parametros['folleto']);
			$smarty->assign('recuperacuadro', $parametros['cuadro']);
			$smarty->assign('recuperaciudad', $parametros['ciudad']);
			$smarty->assign('recuperaopcion', $parametros['opcion']);
			$smarty->assign('recuperafecha', $parametros['fecha']);
			$smarty->assign('recuperapaquete', $parametros['paquete']);
			$smarty->assign('recuperaregimen', $parametros['regimen']);		*/


			//campos de distribucion antiguos
			/*$smarty->assign('recuperaadultos', @$adultos);
			$smarty->assign('recuperaninos', @$ninos2);
			$smarty->assign('recuperaninos2', @$ninos);
			$smarty->assign('recuperabebes', @$bebes);
			$smarty->assign('recuperanovios', @$novios);
			$smarty->assign('recuperajubilados', @$jubilados);*/

			//campos de distribucion nuevos
			$smarty->assign('recuperacantidad_habitaciones', $parametros['cantidad_habitaciones']);
			$smarty->assign('recuperahabitacion1_caracteristica', $parametros['habitacion1_caracteristica']);
			$smarty->assign('recuperahabitacion1_adultos', $parametros['habitacion1_adultos']);
			$smarty->assign('recuperahabitacion1_ninos', $parametros['habitacion1_ninos']);
			$smarty->assign('recuperahabitacion1_bebes', $parametros['habitacion1_bebes']);
			$smarty->assign('recuperahabitacion1_novios', $parametros['habitacion1_novios']);
			$smarty->assign('recuperahabitacion1_jubilados', $parametros['habitacion1_jubilados']);
			$smarty->assign('recuperahabitacion2_caracteristica', $parametros['habitacion2_caracteristica']);
			$smarty->assign('recuperahabitacion2_adultos', $parametros['habitacion2_adultos']);
			$smarty->assign('recuperahabitacion2_ninos', $parametros['habitacion2_ninos']);
			$smarty->assign('recuperahabitacion2_bebes', $parametros['habitacion2_bebes']);
			$smarty->assign('recuperahabitacion2_novios', $parametros['habitacion2_novios']);
			$smarty->assign('recuperahabitacion2_jubilados', $parametros['habitacion2_jubilados']);
			$smarty->assign('recuperahabitacion3_caracteristica', $parametros['habitacion3_caracteristica']);
			$smarty->assign('recuperahabitacion3_adultos', $parametros['habitacion3_adultos']);
			$smarty->assign('recuperahabitacion3_ninos', $parametros['habitacion3_ninos']);
			$smarty->assign('recuperahabitacion3_bebes', $parametros['habitacion3_bebes']);
			$smarty->assign('recuperahabitacion3_novios', $parametros['habitacion3_novios']);
			$smarty->assign('recuperahabitacion3_jubilados', $parametros['habitacion3_jubilados']);
			$smarty->assign('recuperahabitacion4_caracteristica', $parametros['habitacion4_caracteristica']);
			$smarty->assign('recuperahabitacion4_adultos', $parametros['habitacion4_adultos']);
			$smarty->assign('recuperahabitacion4_ninos', $parametros['habitacion4_ninos']);
			$smarty->assign('recuperahabitacion4_bebes', $parametros['habitacion4_bebes']);
			$smarty->assign('recuperahabitacion4_novios', $parametros['habitacion4_novios']);
			$smarty->assign('recuperahabitacion4_jubilados', $parametros['habitacion4_jubilados']);

			$smarty->assign('recuperapasajero1_numero', $parametros['pasajero1_numero']);
			$smarty->assign('recuperapasajero1_habitacion', $parametros['pasajero1_habitacion']);
			$smarty->assign('recuperapasajero1_sexo', $parametros['pasajero1_sexo']);
			$smarty->assign('recuperapasajero1_apellidos', $parametros['pasajero1_apellidos']);
			$smarty->assign('recuperapasajero1_nombre', $parametros['pasajero1_nombre']);
			$smarty->assign('recuperapasajero1_tipo', $parametros['pasajero1_tipo']);
			$smarty->assign('recuperapasajero1_edad', @$parametros['pasajero1_edad']);

			$smarty->assign('recuperapasajero2_numero', $parametros['pasajero2_numero']);
			$smarty->assign('recuperapasajero2_habitacion', $parametros['pasajero2_habitacion']);
			$smarty->assign('recuperapasajero2_sexo', $parametros['pasajero2_sexo']);
			$smarty->assign('recuperapasajero2_apellidos', $parametros['pasajero2_apellidos']);
			$smarty->assign('recuperapasajero2_nombre', $parametros['pasajero2_nombre']);
			$smarty->assign('recuperapasajero2_tipo', $parametros['pasajero2_tipo']);
			$smarty->assign('recuperapasajero2_edad', @$parametros['pasajero2_edad']);

			$smarty->assign('recuperapasajero3_numero', $parametros['pasajero3_numero']);
			$smarty->assign('recuperapasajero3_habitacion', $parametros['pasajero3_habitacion']);
			$smarty->assign('recuperapasajero3_sexo', $parametros['pasajero3_sexo']);
			$smarty->assign('recuperapasajero3_apellidos', $parametros['pasajero3_apellidos']);
			$smarty->assign('recuperapasajero3_nombre', $parametros['pasajero3_nombre']);
			$smarty->assign('recuperapasajero3_tipo', $parametros['pasajero3_tipo']);
			$smarty->assign('recuperapasajero3_edad', @$parametros['pasajero3_edad']);

			$smarty->assign('recuperapasajero4_numero', $parametros['pasajero4_numero']);
			$smarty->assign('recuperapasajero4_habitacion', $parametros['pasajero4_habitacion']);
			$smarty->assign('recuperapasajero4_sexo', $parametros['pasajero4_sexo']);
			$smarty->assign('recuperapasajero4_apellidos', $parametros['pasajero4_apellidos']);
			$smarty->assign('recuperapasajero4_nombre', $parametros['pasajero4_nombre']);
			$smarty->assign('recuperapasajero4_tipo', $parametros['pasajero4_tipo']);
			$smarty->assign('recuperapasajero4_edad', @$parametros['pasajero4_edad']);

			$smarty->assign('recuperapasajero5_numero', $parametros['pasajero5_numero']);
			$smarty->assign('recuperapasajero5_habitacion', $parametros['pasajero5_habitacion']);
			$smarty->assign('recuperapasajero5_sexo', $parametros['pasajero5_sexo']);
			$smarty->assign('recuperapasajero5_apellidos', $parametros['pasajero5_apellidos']);
			$smarty->assign('recuperapasajero5_nombre', $parametros['pasajero5_nombre']);
			$smarty->assign('recuperapasajero5_tipo', $parametros['pasajero5_tipo']);
			$smarty->assign('recuperapasajero5_edad', @$parametros['pasajero5_edad']);

			$smarty->assign('recuperapasajero6_numero', $parametros['pasajero6_numero']);
			$smarty->assign('recuperapasajero6_habitacion', $parametros['pasajero6_habitacion']);
			$smarty->assign('recuperapasajero6_sexo', $parametros['pasajero6_sexo']);
			$smarty->assign('recuperapasajero6_apellidos', $parametros['pasajero6_apellidos']);
			$smarty->assign('recuperapasajero6_nombre', $parametros['pasajero6_nombre']);
			$smarty->assign('recuperapasajero6_tipo', $parametros['pasajero6_tipo']);
			$smarty->assign('recuperapasajero6_edad', @$parametros['pasajero6_edad']);

			$smarty->assign('recuperapasajero7_numero', $parametros['pasajero7_numero']);
			$smarty->assign('recuperapasajero7_habitacion', $parametros['pasajero7_habitacion']);
			$smarty->assign('recuperapasajero7_sexo', $parametros['pasajero7_sexo']);
			$smarty->assign('recuperapasajero7_apellidos', $parametros['pasajero7_apellidos']);
			$smarty->assign('recuperapasajero7_nombre', $parametros['pasajero7_nombre']);
			$smarty->assign('recuperapasajero7_tipo', $parametros['pasajero7_tipo']);
			$smarty->assign('recuperapasajero7_edad', @$parametros['pasajero7_edad']);

			$smarty->assign('recuperapasajero8_numero', $parametros['pasajero8_numero']);
			$smarty->assign('recuperapasajero8_habitacion', $parametros['pasajero8_habitacion']);
			$smarty->assign('recuperapasajero8_sexo', $parametros['pasajero8_sexo']);
			$smarty->assign('recuperapasajero8_apellidos', $parametros['pasajero8_apellidos']);
			$smarty->assign('recuperapasajero8_nombre', $parametros['pasajero8_nombre']);
			$smarty->assign('recuperapasajero8_tipo', $parametros['pasajero8_tipo']);
			$smarty->assign('recuperapasajero8_edad', @$parametros['pasajero8_edad']);

			$smarty->assign('recuperapasajero9_numero', $parametros['pasajero9_numero']);
			$smarty->assign('recuperapasajero9_habitacion', $parametros['pasajero9_habitacion']);
			$smarty->assign('recuperapasajero9_sexo', $parametros['pasajero9_sexo']);
			$smarty->assign('recuperapasajero9_apellidos', $parametros['pasajero9_apellidos']);
			$smarty->assign('recuperapasajero9_nombre', $parametros['pasajero9_nombre']);
			$smarty->assign('recuperapasajero9_tipo', $parametros['pasajero9_tipo']);
			$smarty->assign('recuperapasajero9_edad', @$parametros['pasajero9_edad']);

			$smarty->assign('recuperaclave_agencia', $parametros['clave_agencia_recuperada']);
			$smarty->assign('recuperaagente', $parametros['agente']);
			$smarty->assign('recuperareferencia_agencia', $parametros['referencia_agencia']);
			$smarty->assign('recuperaobservaciones', $parametros['observaciones']);







			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Reservas_Paquete.html');


		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS ALOJAMIENTOS

		/*$parametros['alojamiento_original'] = "";
		$parametros['acuerdo_original'] = "";
		$parametros['alojamiento_nuevo'] = "";
		$parametros['acuerdo_nuevo'] = "";*/


			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboCiudades = $oSino->Cargar_Combo_Ciudades_Origen();
			/*$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboHabitaciones = $oSino->Cargar_combo_Habitaciones();
			$comboRegimen = $oSino->Cargar_combo_Regimen();*/
			$combohabitaciones_car = $oSino->Cargar_combo_Habitaciones_car();
			$comboCantidad_Habitaciones = $oSino->Cargar_Combo_Cantidad_Habitaciones();
			$comboHabitaciones_Adultos = $oSino->Cargar_Combo_Habitaciones_Adultos();
			$comboHabitaciones_Ninos = $oSino->Cargar_Combo_Habitaciones_Ninos();
			$comboHabitaciones_Bebes = $oSino->Cargar_Combo_Habitaciones_Bebes();
			$comboHabitaciones_Novios = $oSino->Cargar_Combo_Habitaciones_Novios();
			$comboHabitaciones_Jubilados = $oSino->Cargar_Combo_Habitaciones_Jubilados();
			$comboSoloVuelo_Adultos = $oSino->Cargar_Combo_Solo_Vuelo_Adulos();
			$comboSoloVuelo_Ninos = $oSino->Cargar_Combo_Solo_Vuelo_Ninos();
			$comboPasajeros_Tipo = $oSino->Cargar_combo_Pasajeros_tipo();
			$comboPasajeros_Edad_Adultos = $oSino->Cargar_combo_Pasajeros_edad_adultos();
			$comboPasajeros_Edad_Ninos = $oSino->Cargar_combo_Pasajeros_edad_ninos();
			$comboSexo = $oSino->Cargar_combo_Pasajeros_sexo();

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------

			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS ALOJAMIENTOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('formulario', 'RESERVAS DE PAQUETE');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboCiudades', $comboCiudades);
			/*$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboHabitaciones', $comboHabitaciones);
			$smarty->assign('comboRegimen', $comboRegimen);*/
			$smarty->assign('combohabitaciones_car', $combohabitaciones_car);
			$smarty->assign('comboCantidad_Habitaciones', $comboCantidad_Habitaciones);
			$smarty->assign('comboHabitaciones_Adultos', $comboHabitaciones_Adultos);
			$smarty->assign('comboHabitaciones_Ninos', $comboHabitaciones_Ninos);
			$smarty->assign('comboHabitaciones_Bebes', $comboHabitaciones_Bebes);
			$smarty->assign('comboHabitaciones_Novios', $comboHabitaciones_Novios);
			$smarty->assign('comboHabitaciones_Jubilados', $comboHabitaciones_Jubilados);
			$smarty->assign('comboSoloVuelo_Adultos', $comboSoloVuelo_Adultos);
			$smarty->assign('comboSoloVuelo_Ninos', $comboSoloVuelo_Ninos);
			$smarty->assign('comboPasajeros_Tipo', $comboPasajeros_Tipo);
			//$smarty->assign('comboPasajeros_Edad_Adultos', $comboPasajeros_Edad_Adultos);
			$smarty->assign('comboPasajeros_Edad_Ninos', $comboPasajeros_Edad_Ninos);
			$smarty->assign('comboSexo', $comboSexo);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);


			//Cargar campos tecleados por el usuario para evitar que los tenga que repetir si hay un error o aviso.
			/*$smarty->assign('recuperafolleto', '');
			$smarty->assign('recuperacuadro', '');
			$smarty->assign('recuperaciudad', '');
			$smarty->assign('recuperaopcion', '');
			$smarty->assign('recuperafecha', '');
			$smarty->assign('recuperapaquete', '');
			$smarty->assign('recuperaregimen', '');	*/
			

			$smarty->assign('recuperaadultos', '');
			$smarty->assign('recuperaninos', '');
			$smarty->assign('recuperabebes', '');
			$smarty->assign('recuperanovios', '');
			$smarty->assign('recuperajubilados', '');

			//campos de distribucion nuevos
			$smarty->assign('recuperacantidad_habitaciones', '');
			$smarty->assign('recuperahabitacion1_caracteristica', '');
			$smarty->assign('recuperahabitacion1_adultos', '');
			$smarty->assign('recuperahabitacion1_ninos', '');
			$smarty->assign('recuperahabitacion1_bebes', '');
			$smarty->assign('recuperahabitacion1_novios', '');;
			$smarty->assign('recuperahabitacion1_jubilados', '');
			$smarty->assign('recuperahabitacion2_caracteristica', '');
			$smarty->assign('recuperahabitacion2_adultos', '');;
			$smarty->assign('recuperahabitacion2_ninos', '');
			$smarty->assign('recuperahabitacion2_bebes', '');
			$smarty->assign('recuperahabitacion2_novios', '');
			$smarty->assign('recuperahabitacion2_jubilados', '');
			$smarty->assign('recuperahabitacion3_caracteristica', '');
			$smarty->assign('recuperahabitacion3_adultos', '');;
			$smarty->assign('recuperahabitacion3_ninos', '');
			$smarty->assign('recuperahabitacion3_bebes', '');
			$smarty->assign('recuperahabitacion3_novios', '');
			$smarty->assign('recuperahabitacion3_jubilados', '');
			$smarty->assign('recuperahabitacion4_caracteristica', '');
			$smarty->assign('recuperahabitacion4_adultos', '');
			$smarty->assign('recuperahabitacion4_ninos', '');
			$smarty->assign('recuperahabitacion4_bebes', '');
			$smarty->assign('recuperahabitacion4_novios', '');
			$smarty->assign('recuperahabitacion4_jubilados', '');

			$smarty->assign('recuperapasajero1_numero', '1');
			$smarty->assign('recuperapasajero1_habitacion', '');
			$smarty->assign('recuperapasajero1_sexo', '');
			$smarty->assign('recuperapasajero1_apellidos', '');
			$smarty->assign('recuperapasajero1_nombre', '');
			$smarty->assign('recuperapasajero1_tipo', '');
			$smarty->assign('recuperapasajero1_edad', '');

			$smarty->assign('recuperapasajero2_numero', '2');
			$smarty->assign('recuperapasajero2_habitacion', '');
			$smarty->assign('recuperapasajero2_sexo', '');
			$smarty->assign('recuperapasajero2_apellidos', '');
			$smarty->assign('recuperapasajero2_nombre', '');
			$smarty->assign('recuperapasajero2_tipo', '');
			$smarty->assign('recuperapasajero2_edad', '');

			$smarty->assign('recuperapasajero3_numero', '3');
			$smarty->assign('recuperapasajero3_habitacion', '');
			$smarty->assign('recuperapasajero3_sexo', '');
			$smarty->assign('recuperapasajero3_apellidos', '');
			$smarty->assign('recuperapasajero3_nombre', '');
			$smarty->assign('recuperapasajero3_tipo', '');
			$smarty->assign('recuperapasajero3_edad', '');

			$smarty->assign('recuperapasajero4_numero', '4');
			$smarty->assign('recuperapasajero4_habitacion', '');
			$smarty->assign('recuperapasajero4_sexo', '');
			$smarty->assign('recuperapasajero4_apellidos', '');
			$smarty->assign('recuperapasajero4_nombre', '');
			$smarty->assign('recuperapasajero4_tipo', '');
			$smarty->assign('recuperapasajero4_edad', '');

			$smarty->assign('recuperapasajero5_numero', '5');
			$smarty->assign('recuperapasajero5_habitacion', '');
			$smarty->assign('recuperapasajero5_sexo', '');
			$smarty->assign('recuperapasajero5_apellidos', '');
			$smarty->assign('recuperapasajero5_nombre', '');
			$smarty->assign('recuperapasajero5_tipo', '');
			$smarty->assign('recuperapasajero5_edad', '');

			$smarty->assign('recuperapasajero6_numero', '6');
			$smarty->assign('recuperapasajero6_habitacion', '');
			$smarty->assign('recuperapasajero6_sexo', '');
			$smarty->assign('recuperapasajero6_apellidos', '');
			$smarty->assign('recuperapasajero6_nombre', '');
			$smarty->assign('recuperapasajero6_tipo', '');
			$smarty->assign('recuperapasajero6_edad', '');

			$smarty->assign('recuperapasajero7_numero', '7');
			$smarty->assign('recuperapasajero7_habitacion', '');
			$smarty->assign('recuperapasajero7_sexo', '');
			$smarty->assign('recuperapasajero7_apellidos', '');
			$smarty->assign('recuperapasajero7_nombre', '');
			$smarty->assign('recuperapasajero7_tipo', '');
			$smarty->assign('recuperapasajero7_edad', '');

			$smarty->assign('recuperapasajero8_numero', '8');
			$smarty->assign('recuperapasajero8_habitacion', '');
			$smarty->assign('recuperapasajero8_sexo', '');
			$smarty->assign('recuperapasajero8_apellidos', '');
			$smarty->assign('recuperapasajero8_nombre', '');
			$smarty->assign('recuperapasajero8_tipo', '');
			$smarty->assign('recuperapasajero8_edad', '');

			$smarty->assign('recuperapasajero9_numero', '9');
			$smarty->assign('recuperapasajero9_habitacion', '');
			$smarty->assign('recuperapasajero9_sexo', '');
			$smarty->assign('recuperapasajero9_apellidos', '');
			$smarty->assign('recuperapasajero9_nombre', '');
			$smarty->assign('recuperapasajero9_tipo', '');
			$smarty->assign('recuperapasajero9_edad', '');

			$smarty->assign('recuperaclave_agencia', '');
			$smarty->assign('recuperaagente', '');
			$smarty->assign('recuperareferencia_agencia', '');
			$smarty->assign('recuperaobservaciones', '');


			//Indicamos si hay que visualizar o no la seccion DE AÑADIR ALOJAMIENTOS
			$smarty->assign('seccion_aereos_display', 'block');
			$smarty->assign('seccion_alojamientos_display', 'block');
			$smarty->assign('seccion_pasajeros_display', 'block');

		//Visualizar plantilla
		$smarty->display('plantillas/Reservas_Paquete.html');
	}

	$conexion->close();


?>

