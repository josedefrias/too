<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/reservas.cls.php';
	//include("class.phpmailer.php"); 
	//include("class.smtp.php");

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
			$recuperareferencia = '';
			$recuperafolleto = '';
			$recuperacuadro = '';
			$recuperaciudad_salida = '';
			$recuperapaquete = '';
			$recuperaregimen = '';
			$recuperafecha_salida = '';
			$recuperanombre_titular = '';
			$recuperaadultos = '';
			$recuperaninos = '';
			$recuperabebes = '';
			$recuperanovios = '';
			$recuperajubilados = '';
			$recuperaobservaciones = '';
			$recuperaagente = '';
			$recuperareferencia_agencia = '';
			$recuperaenvio = '';
			$recuperadivisa_actual = '';
			$recuperamodificacion_motivo = '';
			$recuperamodificacion_responsable = '';
			$recuperafree = '';
			$recuperamodificar = '';
			$recuperamodificar_comisionable = '';
			$recuperamodificar_detalle_comisionable = '';
			$recuperamodificar_no_comisionable = '';
			$recuperamodificar_detalle_no_comisionable = '';
			$recuperamodificar_comision = '';
			$recuperamodificar_usuario = '';
			$recuperavisa = '';
			$recuperaobservaciones_internas = '';
			$recuperaobservaciones_hotel = '';
			$recuperaobservaciones_clientes = '';
			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';

			//VARIABLES PARA LOS PASAJEROS
			$mensaje1_pasajeros = '';
			$mensaje2_pasajeros = '';
			$error_pasajeros = '';

			//VARIABLES PARA LOS AEREOS
			$recuperaaereos_orden = '';
			$recuperaaereos_reserva = '';
			$recuperaaereos_tipo_cuerdo = '';
			$recuperaaereos_cia = '';
			$recuperaaereos_plazas = '';
			$recuperaaereos_fecha_reserva = '';
			$recuperaaereos_emitido = '';
			$recuperaaereos_fecha_emision = '';
			$mensaje1_aereos = '';
			$mensaje2_aereos = '';
			$error_aereos = '';	
			$hay_reservas_aereos_nuevas = 'N';

			$recuperaaereos_clave = '';	
			$recuperaaereos_pasajero_numero = '';	
			$mensaje1_aereos_pasajeros = '';
			$mensaje2_aereos_pasajeros = '';
			$error_aereos_pasajeros = '';	

			$recuperaaereos_trayectos_fecha = '';	
			$recuperaaereos_trayectos_origen = '';	
			$recuperaaereos_trayectos_destino = '';	
			$recuperaaereos_trayectos_cia = '';	
			$recuperaaereos_trayectos_vuelo = '';	
			$recuperaaereos_trayectos_hora_salida = '';	
			$recuperaaereos_trayectos_hora_llegada = '';	
			$recuperaaereos_trayectos_desplazamiento_llegada = '';
			$recuperaaereos_trayectos_clase = '';	
			$recuperaaereos_trayectos_situacion = '';	
			$recuperaaereos_trayectos_acuerdo = '';
			$recuperaaereos_trayectos_subacuerdo = '';
			$recuperaaereos_trayectos_pvp_total_trayecto = '';
			$recuperaaereos_trayectos_tasas_pvp_total_trayecto = '';
			$recuperaaereos_trayectos_tipo_precio = '';
			$recuperaaereos_trayectos_clave_aereo = '';
			$mensaje1_aereos_trayectos = '';
			$mensaje2_aereos_trayectos = '';
			$error_aereos_trayectos = '';	

			//VARIABLES PARA LOS ALOJAMIENTOS

			$mensaje1_alojamientos = '';
			$mensaje2_alojamientos = '';
			$error_alojamientos = '';

			//VARIABLES PARA LOS ALOJAMIENTOS

			$mensaje1_servicios = '';
			$mensaje2_servicios = '';
			$error_servicios = '';

//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//CONTROLAMOS LAS VARIABLES NECESARIAS PARA LLAMAR A LA CLASE RESERVAS DEPENDIENDO DE SI ACABAMOS DE ENTRAR EN LA PANTALLA
	//O YA HEMOS REALIZADO ALGUNA PETICION Y/O VOLVEMOS DE OTRA PANTALLA
//--------------------------------------------------------------------------------------------------------------------------------
	if(count($_POST) != 0){
		$filadesde = $parametros['filadesde'];

		//esto es por si venimos de otra pantalla
		if($parametros['buscar_localizador'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_localizador'] = $parametrosg['localizador'];
			}
		//esto es por si hemos seleccionado una reserva del combo de reservas
		}
		if($parametros['busca_reservas'] != null){
				$parametros['buscar_localizador'] = $parametros['busca_reservas'];
		}
	}else{
		//SI ACABAMOS DE ENTRAR EN LA PANTALLA
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS RESERVAS
		$filadesde = 1;
		if(count($_GET) != 0 and $parametrosg['localizador']){
			$parametros['buscar_localizador'] = $parametrosg['localizador'];
		}else{
			$parametros['buscar_localizador'] = 0;
			$parametros['buscar_referencia'] = ""; 
			$parametros['buscar_referencia_agencia'] = "";
			$parametros['buscar_folleto'] = "";
			$parametros['buscar_cuadro'] = ""; 
			$parametros['buscar_fecha_salida'] = "";
			$parametros['buscar_fecha_reserva'] = ""; 
			$parametros['buscar_minorista'] = ""; 
			$parametros['buscar_oficina'] = ""; 
			$parametros['buscar_telefono_oficina'] = ""; 
			$parametros['buscar_nombre'] = "";
		}
	}

//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//LLAMADA A LA CLASE GENERAL PARA TODAS LAS ACCIONES
//--------------------------------------------------------------------------------------------------------------------------------
	$ClaseReservas = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'],																	$parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], 
									$parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], 
									$parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);

   //TAMBIEN LLAMAMOS A LA CLASE GENERAL PARA CARGAR LOS COMBOS DE LA PANTALLA
	$combos = new clsGeneral($conexion);

//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//SI HAY PARAMETROS REALIZAMOS TODAS LAS ACCIONES SOLICITADAS POR EL USUARIO
//--------------------------------------------------------------------------------------------------------------------------------

	if(count($_POST) != 0){
		
		//Si no se ha pulsado el boton Salir ni el de Borrador
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'F'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LA RESERVA--------------
			//--------------------------------------------------------------------------

			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){

				$botonselec = $ClaseReservas->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			
			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'MANTENIMIENTO' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							//echo($parametros['jubilados'.$num_fila].'-');
							//echo($parametros['observaciones'.$num_fila].'-');
							$modificaReservas = $ClaseReservas->Modificar($parametros['localizador'.$num_fila],$parametros['referencia'.$num_fila],
								$parametros['codigo_situacion'.$num_fila],
								$parametros['bloqueada'.$num_fila],$parametros['bloqueada_usuario'.$num_fila],$parametros['folleto'.$num_fila],$parametros['cuadro'.$num_fila],$parametros['ciudad_salida'.$num_fila],$parametros['paquete'.$num_fila],$parametros['regimen'.$num_fila],$parametros['fecha_salida'.$num_fila],$parametros['fecha_regreso'.$num_fila],$parametros['fecha_modificacion'.$num_fila],$parametros['usuario_modificacion'.$num_fila],
								$parametros['nombre_titular'.$num_fila],$parametros['pax'.$num_fila],$parametros['adultos'.$num_fila],
								$parametros['ninos'.$num_fila],$parametros['bebes'.$num_fila],$parametros['novios'.$num_fila],$parametros['observaciones'.$num_fila],
								$parametros['minorista'.$num_fila],$parametros['oficina'.$num_fila],$parametros['busca_agencias'],$parametros['agente'.$num_fila],
								$parametros['referencia_agencia'.$num_fila],$parametros['envio'.$num_fila],$parametros['divisa_actual'.$num_fila],
								$parametros['free'.$num_fila],
								$parametros['modificar'.$num_fila],$parametros['modificar_comisionable'.$num_fila],$parametros['modificar_detalle_comisionable'.$num_fila],$parametros['modificar_no_comisionable'.$num_fila],$parametros['modificar_detalle_no_comisionable'.$num_fila], $parametros['modificar_comision'.$num_fila],$parametros['modificacion_motivo'.$num_fila],$parametros['modificacion_responsable'.$num_fila],$parametros['observaciones_internas'.$num_fila],$parametros['observaciones_hotel'.$num_fila],$parametros['observaciones_clientes'.$num_fila],$parametros['alternativa_aerea'.$num_fila]);

							if($modificaReservas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaReservas;
							}

						}else{

							$anulaReservas = $ClaseReservas->Anular($parametros['localizador'.$num_fila]);

							if(SUBSTR($anulaReservas,0,14) == 'El localizador'){
								$Ntransacciones = $anulaReservas;
							}else{
								$error = $anulaReservas;
							}
						}
					}
				}
				//Actualizamos los precios de la reserva
				/*$actualizar_precio = "CALL `RESERVAS_ACTUALIZA_PRECIOS`('R','".$parametros['localizador0']."')";
				$resultadoactualizar =$conexion->query($actualizar_precio);
					//echo($expandir);
				if ($resultadoactualizar == FALSE){
					$error = 'No se ha podido atualizar el precio de la reserva. '.$conexion->error;
				}*/


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				$num_filas->close();

			//BLOQUEAR/DESBOQUEAR
			}elseif($parametros['actuar'] == 'D'){

				$Ntransacciones = 0;

				$bloquearReservas = $ClaseReservas->Bloquear_desbloquear($parametros['localizador0']);
				if($bloquearReservas == 'La reserva ha sido desbloqueda' or $bloquearReservas == 'La reserva ha sido bloqueda'){
					$Ntransacciones = $bloquearReservas;
				}else{
					$error = $bloquearReservas;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
			//ACTUALIZAR PRECIOS
			}elseif($parametros['actuar'] == 'A'){
				$Ntransacciones = 0;

				$ActualizarPrecio = $ClaseReservas->Actualizar_precios($parametros['localizador0']);
				if($ActualizarPrecio == 'OK'){
					$Ntransacciones = $ActualizarPrecio;
				}else{
					$error = $ActualizarPrecio;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}				
			//ENVAR MAIL AGENCIA
			}elseif($parametros['actuar'] == 'E'){
				$Ntransacciones = 0;

				$EnviarMailagencia = $ClaseReservas->Enviar_mail_agencia($parametros['localizador0']);

				$Ntransacciones = $EnviarMailagencia;


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Se ha enviado la información de la reserva a las siguientes direcciones de correo: ".$Ntransacciones."</b></font></div>";
		
			}elseif($parametros['actuar'] == 'H'){
				$Ntransacciones = 0;

				$EnviarMailhotel = $ClaseReservas->Enviar_mail_hotel($parametros['localizador0']);

				$Ntransacciones = $EnviarMailhotel;


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Se ha enviado la peticion de hotel a las siguientes direcciones de correo: ".$Ntransacciones."</b></font></div>";
		
			}

			if($parametros['grabar_registro'] == 'S'){

				//AÑADIR REGISTROS
				//$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_GESTION' AND USUARIO = '".$usuario."'");
				//$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				$Ntransacciones = 0;				

				$insertaReserva = $ClaseReservas->Insertar($parametros['referencia0'],$parametros['folleto0'],$parametros['cuadro0'],$parametros['ciudad_salida0'],$parametros['paquete0'],$parametros['regimen0'],$parametros['fecha_salida0'],$parametros['nombre_titular0'],$parametros['adultos0'],$parametros['ninos0'],$parametros['bebes0'],$parametros['novios0'],$parametros['observaciones0'],$parametros['minorista0'],$parametros['oficina0'],$parametros['busca_agencias'],$parametros['agente0'],$parametros['referencia_agencia0'],$parametros['envio0'],$parametros['divisa_actual0'],$parametros['free0'],$parametros['observaciones_internas0'],$parametros['observaciones_hotel0'],$parametros['observaciones_clientes0']);

				if(substr($insertaReserva,0,5) == 'No se'){

					$error = $insertaReserva;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					//$recuperaid = $parametros['id'.$num_fila];

					$recuperareferencia = $parametros['referencia0'];
					$recuperafolleto = $parametros['folleto0'];
					$recuperacuadro = $parametros['cuadro0'];
					$recuperaciudad_salida = $parametros['ciudad_salida0'];
					$recuperapaquete = $parametros['paquete0'];
					$recuperaregimen = $parametros['regimen0'];
					$recuperafecha_salida = $parametros['fecha_salida0'];
					$recuperanombre_titular = $parametros['nombre_titular0'];
					$recuperaadultos = $parametros['adultos0'];
					$recuperaninos = $parametros['ninos0'];
					$recuperabebes = $parametros['bebes0'];
					$recuperanovios = $parametros['novios0'];
					$recuperaobservaciones= $parametros['observaciones0'];
					$recuperaagente = $parametros['agente0'];
					$recuperareferencia_agencia = $parametros['referencia_agencia0'];
					$recuperaenvio = $parametros['envio0'];
					$recuperadivisa_actual = $parametros['divisa_actual0'];
					$recuperafree = $parametros['free0'];
					$recuperafree = $parametros['observaciones_internas0'];
					$recuperafree = $parametros['observaciones_hotel0'];
					$recuperafree = $parametros['observaciones_clientes0'];

				}else{

					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_localizador'] = $insertaReserva;
					$parametros['nuevo_registro'] = 'N'; //Para que si pulsa de nuevo grabar no añada otra reserva

					//---------------------------------------
					//---------------------------------------
					//ENVIAMOS MAIL DE AVISO DE NUEVA RESERVA
					$asunto = "NUEVA RESERVA";
					$mensaje_html = "<b>Nueva reserva manual: ".$insertaReserva."</b><br><b>Realizada por: ".$usuario." - ".$nombre."</b>";
					$direccion_correo = "info@panavision-tours.es";
					$nombre_destinatario = "Usuario Interno";
					$envio = enviar_mail($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario);
					//echo($envio);
					//---------------------------------------
					//---------------------------------------

				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

				//Actualizamos los precios de la reserva
				$actualizar_precio = "CALL `RESERVAS_ACTUALIZA_PRECIOS`('R','".$parametros['localizador0']."')";
				$resultadoactualizar =$conexion->query($actualizar_precio);
					//echo($expandir);
				if ($resultadoactualizar == FALSE){
					$error = 'No se ha podido atualizar el precio de la reserva. '.$conexion->error;
				}
			}


			//Llamada a la clase especifica de la pantalla
			$sReservas = $ClaseReservas->Cargar($recuperareferencia,$recuperafolleto,$recuperacuadro,$recuperaciudad_salida,$recuperapaquete,$recuperaregimen,$recuperafecha_salida,$recuperanombre_titular,$recuperaadultos,$recuperaninos,$recuperabebes,$recuperanovios,$recuperajubilados,$recuperaobservaciones,$recuperaagente,$recuperareferencia_agencia,$recuperaenvio,$recuperadivisa_actual,$recuperamodificacion_motivo,$recuperamodificacion_responsable,$recuperafree,$recuperamodificar,$recuperamodificar_comisionable,$recuperamodificar_detalle_comisionable,$recuperamodificar_no_comisionable,$recuperamodificar_detalle_no_comisionable,$recuperamodificar_comision,$recuperamodificar_usuario,$recuperavisa, $recuperaobservaciones_internas, $recuperaobservaciones_hotel, $recuperaobservaciones_clientes);

			$sComboSelectReservas = $ClaseReservas->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$comboSino = $combos->Cargar_combo_SiNo();
			$comboDivisas = $combos->Cargar_combo_Divisas();
			$comboFolletos = $combos->Cargar_combo_Folletos();
			$comboRegimen = $combos->Cargar_combo_Regimen();
			$comboReservas = $combos->Cargar_combo_Reservas($parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
			$comboAlternativas_Aereas = $combos->Cargar_combo_Alternativas_Aereas($parametros['buscar_localizador']);

			//Esto ya no es necesario desde que se hace la busqueda con Ajax
			//$comboAgencias = $oSino->Cargar_combo_Agencias($parametros['buscar_agencia_minorista'], $parametros['buscar_agencia_oficina'],$parametros['buscar_agencia_direccion'], $parametros['buscar_agencia_telefono']);


	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS PASAJEROS ---------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_pasajeros = $parametros['filadesde_pasajeros'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonselec_pasajeros = $ClaseReservas->Botones_selector_pasajeros($filadesde_pasajeros, $parametros['botonSelector_2nivel'], $parametros['paginacion_pasajeros']);
				$filadesde_pasajeros = $botonselec_pasajeros;
			}

			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				if($parametros['paginacion_pasajeros'] == 'S'){
					$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_PASAJEROS' AND USUARIO = '".$usuario."'");
					$Nfilas	 = $num_filas->fetch_assoc();
				}else{
					$num_filas =$conexion->query("SELECT COUNT(*) LINEAS_MODIFICACION FROM hit_reservas_pasajeros WHERE LOCALIZADOR = '".$sReservas[0]['localizador']."'");
					  $Nfilas	 = $num_filas->fetch_assoc();
					//$Nfilas	 = $num_filas->num_rows;		
				}

				$Ntransacciones_pasajeros = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){

							$modificaPasajeros = $ClaseReservas->Modificar_pasajeros($sReservas[0]['localizador'], $parametros['numero'.$num_fila], $parametros['habitacion'.$num_fila],$parametros['apellido'.$num_fila], $parametros['nombre_pax'.$num_fila], $parametros['sexo'.$num_fila],$parametros['tipo'.$num_fila], $parametros['edad'.$num_fila], $parametros['documento_tipo'.$num_fila], $parametros['documento'.$num_fila], $parametros['fecha_nacimiento'.$num_fila], $parametros['pais'.$num_fila], $parametros['telefono'.$num_fila], $parametros['observaciones_pax'.$num_fila]);
							if($modificaPasajeros == 'OK'){
								$Ntransacciones_pasajeros++;
							}else{
								$error_pasajeros = $modificaPasajeros;
							}

						}else{

							$borraPasajeros = $ClaseReservas->Borrar_pasajeros($sReservas[0]['localizador'], $parametros['numero'.$num_fila], $sReservas[0]['pax']);
							if($borraPasajeros == 'OK'){
								$Ntransacciones_pasajeros++;
							}else{
								$error_pasajeros = $borraPasajeros;

							}
						}
					}
				}
				if($Ntransacciones_pasajeros > 0){
						$RevisaNumeroPasajeros = $ClaseReservas->Revisar_numeros_pasajeros($sReservas[0]['localizador']);
						if($RevisaNumeroPasajeros == 'OK'){
							$Ntransacciones_pasajeros++;
						}else{
							$error_pasajeros = $RevisaNumeroPasajeros;

						}
				}

				//Actualizamos los precios de la reserva
				$actualizar_precio = "CALL `RESERVAS_ACTUALIZA_PRECIOS`('R','".$parametros['localizador0']."')";
				$resultadoactualizar =$conexion->query($actualizar_precio);
					//echo($expandir);
				if ($resultadoactualizar == FALSE){
					$error = 'No se ha podido atualizar el precio de la reserva. '.$conexion->error;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_pasajeros = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_pasajeros."</b></font></div>";
				if($error_pasajeros != ''){
					$mensaje2_pasajeros = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_pasajeros."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();

			}


			//Llamada a la clase especifica de la pantalla
			$sPasajeros = $ClaseReservas->Cargar_pasajeros($sReservas[0]['localizador'],$filadesde_pasajeros,$parametros['buscar_apellido'],$parametros['buscar_nombre_pax'],$parametros['paginacion_pasajeros']);	
			$sComboSelectPasajeros = $ClaseReservas->Cargar_combo_selector_pasajeros($sReservas[0]['localizador'],$parametros['buscar_apellido'],$parametros['buscar_nombre_pax'],$parametros['paginacion_pasajeros']);
			//Llamada a la clase general de combos
			//$comboSino = $oSino->Cargar_combo_SiNo();
			$comboPasajerosSexo = $combos->Cargar_combo_Pasajeros_sexo();
			$comboPasajerosTipo = $combos->Cargar_combo_Pasajeros_tipo();
			$comboPasajerosDocumentoTipo = $combos->Cargar_combo_Pasajeros_documento_tipo();
			$comboPaises = $combos->Cargar_combo_Paises_Reserva();


	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS AEREOS ------------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			//$filadesde_aereos = $parametros['filadesde_aereos'];
			
			//-------------------
			//RESERVAS DE AEREOS-
			//-------------------
			if($parametros['actuar_3nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS DE RESERVAS DE AEREOS
				$num_filas =$conexion->query("SELECT count(*) LINEAS FROM hit_reservas_aereos WHERE LOCALIZADOR = '".$sReservas[0]['localizador']."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones_aereos = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS']-1; $num_fila++) {
					if($parametros['selec_3nivel'.$num_fila] == 'S' || $parametros['borra_3nivel'.$num_fila] == 'S'){
						if($parametros['selec_3nivel'.$num_fila] == 'S'){
							
							$modificaAereos = $ClaseReservas->Modificar_aereos($parametros['aereos_clave'.$num_fila], $parametros['aereos_reserva'.$num_fila], $parametros['aereos_tipo_cuerdo'.$num_fila], $parametros['aereos_cia'.$num_fila], $parametros['aereos_plazas'.$num_fila], $parametros['aereos_fecha_reserva'.$num_fila], $parametros['aereos_emitido'.$num_fila], $parametros['aereos_fecha_emision'.$num_fila]);
							if($modificaAereos == 'OK'){
								$Ntransacciones_aereos++;
							}else{
								$error_aereos = $modificaAereos;
							}

						}else{

							$borraAereos = $ClaseReservas->Borrar_aereos($parametros['aereos_clave'.$num_fila]);
							if($borraAereos == 'OK'){
								$Ntransacciones_aereos++;
							}else{
								$error_aereos = $borraAereos;

							}
						}
					}
				}

				//AÑADIR REGISTROS DE RESERVAS DE AEREOS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_AEREOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_3nivel'.$num_fila] == 'S'){
						$hay_reservas_aereos_nuevas = 'S';
						$insertaAereos = $ClaseReservas->Insertar_aereos($sReservas[0]['localizador'], $parametros['Nuevoaereos_orden'.$num_fila], $parametros['Nuevoaereos_reserva'.$num_fila], $parametros['Nuevoaereos_tipo_acuerdo'.$num_fila], $parametros['Nuevoaereos_cia'.$num_fila],$parametros['Nuevoaereos_plazas'.$num_fila],$parametros['Nuevoaereos_fecha_reserva'.$num_fila], $parametros['Nuevoaereos_emitido'.$num_fila], $parametros['Nuevoaereos_fecha_emision'.$num_fila]);
						if($insertaAereos == 'OK'){
							$Ntransacciones_aereos++;
						}else{
							$error_aereos = $insertaAereos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperaaereos_orden = $parametros['Nuevoaereos_orden'.$num_fila]; 
							$recuperaaereos_reserva = $parametros['Nuevoaereos_reserva'.$num_fila]; 
							$recuperaaereos_tipo_cuerdo = $parametros['tipo_acuerdo'.$num_fila]; 
							$recuperaaereos_cia = $parametros['Nuevoaereos_cia'.$num_fila]; 
							$recuperaaereos_plazas = $parametros['Nuevoaereos_plazas'.$num_fila]; 
							$recuperaaereos_fecha_reserva = $parametros['Nuevoaereos_fecha_reserva'.$num_fila]; 
							$recuperaaereos_emitido = $parametros['Nuevoaereos_emitido'.$num_fila]; 
							$recuperaaereos_fecha_emision = $parametros['Nuevoaereos_fecha_emision'.$num_fila]; 
						}
					}
				}

				//Actualizamos los precios de la reserva
				$actualizar_precio = "CALL `RESERVAS_ACTUALIZA_PRECIOS`('R','".$parametros['localizador0']."')";
				$resultadoactualizar =$conexion->query($actualizar_precio);
					//echo($expandir);
				if ($resultadoactualizar == FALSE){
					$error = 'No se ha podido atualizar el precio de la reserva. '.$conexion->error;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_aereos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_aereos."</b></font></div>";
				if($error_aereos != ''){
					$mensaje2_aereos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_aereos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();

			}


			//--------------------
			//PASAJEROS DE AEREOS-
			//--------------------
			if($parametros['actuar_31nivel'] == 'S'){


				//MODIFICAR Y BORRAR REGISTROS DE PASAJEROS DE AEREOS
				$num_filas =$conexion->query("SELECT count(*) LINEAS FROM hit_reservas_aereos_pasajeros WHERE LOCALIZADOR = '".$sReservas[0]['localizador']."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones_aereos_pasajeros = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS']-1; $num_fila++) {
					if($hay_reservas_aereos_nuevas != 'S'){ //Eesto es para que no se intente modificar ni borrar pasajeros mientras se da de alta una nueva reserva de aereos
						if($parametros['selec_31nivel'.$num_fila] == 'S' || $parametros['borra_31nivel'.$num_fila] == 'S'){
							if($parametros['selec_31nivel'.$num_fila] == 'S'){
								
								$modificaAereos_pasajeros = $ClaseReservas->Modificar_aereos_pasajeros($sReservas[0]['localizador'], $parametros['aereos_pasajeros_orden'.$num_fila], $parametros['aereos_pasajeros_numero'.$num_fila], $parametros['aereos_pasajeros_billete'.$num_fila], $parametros['aereos_pasajeros_coste'.$num_fila], $parametros['aereos_pasajeros_tasas'.$num_fila]);
								if($modificaAereos_pasajeros == 'OK'){
									$Ntransacciones_aereos_pasajeros++;
								}else{
									$error_aereos_pasajeros = $modificaAereos_pasajeros;
								}

							}else{

								$borraAereos_pasajeros = $ClaseReservas->Borrar_aereos_pasajeros($sReservas[0]['localizador'], $parametros['aereos_pasajeros_orden'.$num_fila], $parametros['aereos_pasajeros_numero'.$num_fila]);
								if($borraAereos_pasajeros == 'OK'){
									$Ntransacciones_aereos_pasajeros++;
								}else{
									$error_aereos_pasajeros = $borraAereos_pasajeros;

								}
							}
						}
					}

				}

				//AÑADIR REGISTROS DE PASAJEROS DE AEREOS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_AEREOS_PASAJEROS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_31nivel'.$num_fila] == 'S'){

						$insertaAereos_pasajeros = $ClaseReservas->Insertar_aereos_pasajeros($parametros['Nuevoaereos_clave'.$num_fila], $parametros['Nuevoaereos_pasajeros_numero'.$num_fila]);
						if($insertaAereos_pasajeros == 'OK'){
							$Ntransacciones_aereos_pasajeros++;
						}else{
							$error_aereos_pasajeros = $insertaAereos_pasajeros;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperaaereos_clave = $parametros['Nuevoaereos_clave'.$num_fila]; 
							$recuperaaereos_pasajero_numero = $parametros['Nuevoaaereos_pasajero_numero'.$num_fila]; 
						}
					}
				}

				//Actualizamos los precios de la reserva
				$actualizar_precio = "CALL `RESERVAS_ACTUALIZA_PRECIOS`('R','".$parametros['localizador0']."')";
				$resultadoactualizar =$conexion->query($actualizar_precio);
					//echo($expandir);
				if ($resultadoactualizar == FALSE){
					$error = 'No se ha podido atualizar el precio de la reserva. '.$conexion->error;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_aereos_pasajeros = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_aereos_pasajeros."</b></font></div>";
				if($error_aereos_pasajeros != ''){
					$$mensaje2_aereos_pasajeros = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_aereos_pasajeros."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();

			}


			//--------------------
			//TRAYECTOS DE AEREOS-
			//--------------------
			if($parametros['actuar_32nivel'] == 'S'){


				//MODIFICAR Y BORRAR REGISTROS DE TRAYECTOS DE AEREOS
				$num_filas =$conexion->query("SELECT count(*) LINEAS FROM hit_reservas_aereos_trayectos WHERE LOCALIZADOR = '".$sReservas[0]['localizador']."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones_aereos_trayectos = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS']-1; $num_fila++) {
					if($parametros['selec_32nivel'.$num_fila] == 'S' || $parametros['borra_32nivel'.$num_fila] == 'S'){
						if($parametros['selec_32nivel'.$num_fila] == 'S'){
							$modificaAereos_trayectos = $ClaseReservas->Modificar_aereos_trayectos(
								$parametros['aereos_trayectos_clave_aereo'.$num_fila], 
								$parametros['aereos_trayectos_numero'.$num_fila], 
								$parametros['aereos_trayectos_fecha'.$num_fila], 
								$parametros['aereos_trayectos_origen'.$num_fila], 
								$parametros['aereos_trayectos_destino'.$num_fila], 
								$parametros['aereos_trayectos_cia'.$num_fila],
							$parametros['aereos_trayectos_acuerdo'.$num_fila], 
								$parametros['aereos_trayectos_subacuerdo'.$num_fila], 
								$parametros['aereos_trayectos_vuelo'.$num_fila], 
								$parametros['aereos_trayectos_hora_salida'.$num_fila],
							$parametros['aereos_trayectos_hora_llegada'.$num_fila], 
								$parametros['aereos_trayectos_desplazamiento_llegada'.$num_fila], 
								$parametros['aereos_trayectos_clase'.$num_fila], 
								$parametros['aereos_trayectos_situacion'.$num_fila],
								$parametros['aereos_trayectos_pvp_total_trayecto'.$num_fila],
								$parametros['aereos_trayectos_tasas_pvp_total_trayecto'.$num_fila],
								$parametros['aereos_trayectos_tipo_precio'.$num_fila]
								);

							if($modificaAereos_trayectos == 'OK'){
								$Ntransacciones_aereos_trayectos++;
							}else{
								$error_aereos_trayectos = $modificaAereos_trayectos;
							}

						}else{

							$borraAereos_trayectos = $ClaseReservas->Borrar_aereos_trayectos($parametros['aereos_trayectos_clave_aereo'.$num_fila], $parametros['aereos_trayectos_numero'.$num_fila]);
							if($borraAereos_trayectos == 'OK'){
								$Ntransacciones_aereos_trayectos++;
							}else{
								$error_aereos_trayectos = $borraAereos_trayectos;
							}
						}
					}
				}

				//AÑADIR REGISTROS DE TRAYECTOS DE AEREOS MANUAL
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_AEREOS_TRAYECTOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					if($parametros['Nuevoselec_32nivel'.$num_fila] == 'S'){

						$insertaAereos_trayectos = $ClaseReservas->Insertar_aereos_trayectos_Manual($parametros['Nuevoaereos_trayectos_clave_aereo'.$num_fila], 
							$parametros['Nuevoaereos_trayectos_fecha'.$num_fila],
							$parametros['Nuevoaereos_trayectos_origen'.$num_fila],
							$parametros['Nuevoaereos_trayectos_destino'.$num_fila],
							$parametros['Nuevoaereos_trayectos_cia'.$num_fila],
							$parametros['Nuevoaereos_trayectos_vuelo'.$num_fila],
							$parametros['Nuevoaereos_trayectos_hora_salida'.$num_fila],
							$parametros['Nuevoaereos_trayectos_hora_llegada'.$num_fila],
							$parametros['Nuevoaereos_trayectos_desplazamiento_llegada'.$num_fila],
							$parametros['Nuevoaereos_trayectos_clase'.$num_fila],
							$parametros['Nuevoaereos_trayectos_situacion'.$num_fila],
							$parametros['Nuevoaereos_trayectos_acuerdo'.$num_fila],
							$parametros['Nuevoaereos_trayectos_subacuerdo'.$num_fila],
							$parametros['Nuevoaereos_trayectos_pvp_total_trayecto'.$num_fila],
							$parametros['Nuevoaereos_trayectos_tasas_pvp_total_trayecto'.$num_fila],
							$parametros['Nuevoaereos_trayectos_tipo_precio'.$num_fila]);
							
						if($insertaAereos_trayectos == 'OK'){
							$Ntransacciones_aereos_trayectos++;
						}else{
							$error_aereos_trayectos = $insertaAereos_trayectos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperaaereos_trayectos_fecha = $parametros['Nuevoaereos_trayectos_fecha'.$num_fila];	
							$recuperaaereos_trayectos_origen = $parametros['Nuevoaereos_trayectos_origen'.$num_fila];	
							$recuperaaereos_trayectos_destino = $parametros['Nuevoaereos_trayectos_destino'.$num_fila];
							$recuperaaereos_trayectos_cia = $parametros['Nuevoaereos_trayectos_cia'.$num_fila];
							$recuperaaereos_trayectos_vuelo = $parametros['Nuevoaereos_trayectos_vuelo'.$num_fila];	
							$recuperaaereos_trayectos_hora_salida = $parametros['Nuevoaereos_trayectos_hora_salida'.$num_fila];
							$recuperaaereos_trayectos_hora_llegada = $parametros['Nuevoaereos_trayectos_hora_llegada'.$num_fila];	
							$recuperaaereos_trayectos_desplazamiento_llegada = $parametros['Nuevoaereos_trayectos_desplazamiento_llegada'.$num_fila];
							$recuperaaereos_trayectos_clase = $parametros['Nuevoaereos_trayectos_clase'.$num_fila];	
							$recuperaaereos_trayectos_situacion = $parametros['Nuevoaereos_trayectos_situacion'.$num_fila];
							$recuperaaereos_trayectos_acuerdo = $parametros['Nuevoaereos_trayectos_acuerdo'.$num_fila];
							$recuperaaereos_trayectos_subacuerdo = $parametros['Nuevoaereos_trayectos_subacuerdo'.$num_fila];
							$recuperaaereos_trayectos_pvp_total_trayecto = $parametros['Nuevoaereos_trayectos_pvp_total_trayecto'.$num_fila];
							$recuperaaereos_trayectos_tasas_pvp_total_trayecto = $parametros['Nuevoaereos_trayectos_tasas_pvp_total_trayecto'.$num_fila];
							$recuperaaereos_trayectos_tipo_precio = $parametros['Nuevoaereos_trayectos_tipo_precio'.$num_fila];
							
							$recuperaaereos_trayectos_clave_aereo = $parametros['Nuevoaereos_trayectos_clave_aereo'.$num_fila];
						}
					}
				}

				//AÑADIR REGISTROS DE TRAYECTOS DE AEREOS DE CUPO
				if($parametros['Añadir_Reserva_Trayectos_Cupos'] == 'S'){

					if($parametros['nuevo_trayecto_cupo_clave_aereo'] != ''){
						if($parametros['busca_trayectos_Cupos_Ida'] != '' and $parametros['busca_trayectos_Cupos_Vuelta'] != ''){
							$insertaTrayectosCupos = $ClaseReservas->Insertar_aereos_trayectos_Cupos($sReservas[0]['localizador'], $parametros['nuevo_trayecto_cupo_clave_aereo'], $parametros['busca_trayectos_Cupos_Ida'], $parametros['busca_trayectos_Cupos_Vuelta']);
							if($insertaTrayectosCupos == 'OK'){
								$Ntransacciones_aereos_trayectos++;
							}else{
								$error_aereos_trayectos = $insertaTrayectosCupos;
							}
						}elseif($parametros['busca_trayectos_Cupos_Ida'] != '' and $parametros['busca_trayectos_Cupos_Vuelta'] == '' and $parametros['busca_aereos_tipo_trayecto'] == 'OW'){
								$insertaTrayectosCupos = $ClaseReservas->Insertar_aereos_trayectos_Cupos($sReservas[0]['localizador'], $parametros['nuevo_trayecto_cupo_clave_aereo'], $parametros['busca_trayectos_Cupos_Ida'], 0);
								if($insertaTrayectosCupos == 'OK'){
									$Ntransacciones_aereos_trayectos++;
								}else{
									$error_aereos_trayectos = $insertaTrayectosCupos;
								}
						}elseif($parametros['busca_trayectos_Cupos_Ida'] == ''){
								$error_aereos_trayectos = 'Debe seleccionar algun trayecto de Ida';
						}elseif($parametros['busca_trayectos_Cupos_Vuelta'] == '' and $parametros['busca_aereos_tipo_trayecto'] == 'RT'){
								$error_aereos_trayectos = 'Debe seleccionar algun trayecto de vuelta';
						}else{
								$error_aereos_trayectos = 'Debe seleccionar algun trayecto de cupo';
						}

					}else{
						$error_aereos_trayectos = 'Debe seleccionar una reserva de aereos';
					}
				}

				//Actualizamos los precios de la reserva
				$actualizar_precio = "CALL `RESERVAS_ACTUALIZA_PRECIOS`('R','".$parametros['localizador0']."')";
				$resultadoactualizar =$conexion->query($actualizar_precio);
					//echo($expandir);
				if ($resultadoactualizar == FALSE){
					$error = 'No se ha podido atualizar el precio de la reserva. '.$conexion->error;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_aereos_trayectos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_aereos_trayectos."</b></font></div>";
				if($error_aereos_trayectos != ''){
					$mensaje2_aereos_trayectos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_aereos_trayectos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();
			}

			//AEREOS
			//Llamada a la clase especifica de la pantalla
			$sAereos = $ClaseReservas->Cargar_aereos($sReservas[0]['localizador']);	
			$sAereos_nuevos = $ClaseReservas->Cargar_lineas_nuevas_aereos();	

			//Llamada a la clase general de combos
			$comboAcuerdosAereos = $combos->Cargar_combo_Tipos_acuerdo_transportes();

			//PASAJEROS
			//Llamada a la clase especifica de la pantalla
			$sAereos_pasajeros = $ClaseReservas->Cargar_aereos_pasajeros($sReservas[0]['localizador']);	
			$sAereos_pasajerosnuevos = $ClaseReservas->Cargar_lineas_nuevas_aereos_pasajeros();

			//Llamada a la clase general de combos
			$comboReservasAereos = $combos->Cargar_combo_Reservas_Aereos($sReservas[0]['localizador']);
			$comboReservasPasajeros = $combos->Cargar_combo_Reservas_Pasajeros($sReservas[0]['localizador']);

			//TRAYECTOS
			//Llamada a la clase especifica de la pantalla
			$sAereos_trayectos = $ClaseReservas->Cargar_aereos_trayectos($sReservas[0]['localizador']);	
			$sAereos_trayectosnuevos = $ClaseReservas->Cargar_lineas_nuevas_aereos_trayectos();

			//Llamada a la clase general de combos
			$comboTipos_trayecto = $combos->Cargar_Combo_Tipo_Trayecto();


			/*echo('<pre>');
			print_r($sComboSelectGrupos_gestion_comisiones);
			echo('</pre>');*/


	//-----------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS ALOJAMIENTOS ---------
	//-----------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/



			if($parametros['actuar_4nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT count(*) LINEAS FROM hit_reservas_alojamientos WHERE LOCALIZADOR = '".$sReservas[0]['localizador']."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones_alojamientos = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS']-1; $num_fila++) {
					if($parametros['selec_4nivel'.$num_fila] == 'S' || $parametros['borra_4nivel'.$num_fila] == 'S'){
						if($parametros['selec_4nivel'.$num_fila] == 'S'){
							
							$modificaAlojamientos = $ClaseReservas->Modificar_alojamientos($parametros['clave_alojamiento'.$num_fila], $parametros['habitacion_alojamiento'.$num_fila],$parametros['caracteristica_alojamiento'.$num_fila],$parametros['uso_alojamiento'.$num_fila],$parametros['regimen_alojamiento'.$num_fila],$parametros['acuerdo_alojamiento'.$num_fila],$parametros['noches_alojamiento'.$num_fila],$parametros['cantidad_habitaciones_alojamiento'.$num_fila],$parametros['adultos_alojamiento'.$num_fila],$parametros['ninos1_alojamiento'.$num_fila],$parametros['bebes_alojamiento'.$num_fila],$parametros['novios_alojamiento'.$num_fila],$parametros['situacion_alojamiento'.$num_fila],$parametros['orden_alojamiento'.$num_fila],$parametros['pvp_total_alojamiento'.$num_fila],$parametros['tipo_precio_alojamiento'.$num_fila], @$parametros['interfaz_porcentaje_cancelacion_alojamiento'.$num_fila],@$parametros['interfaz_localizador_baja_alojamiento'.$num_fila]);
							if($modificaAlojamientos == 'OK'){
								$Ntransacciones_alojamientos++;
							}else{
								$error_alojamientos = $modificaAlojamientos;
							}

						}else{

							$borraAlojamientos = $ClaseReservas->Borrar_alojamientos($parametros['clave_alojamiento'.$num_fila]);
							if($borraAlojamientos == 'OK'){
								$Ntransacciones_alojamientos++;
							}else{
								$error_alojamientos = $borraAlojamientos;

							}
						}
					}
				}

				//AÑADIR REGISTROS
					
				if($parametros['Añadir_Reserva_Alojamiento'] == 'S'){

					//Aqui decidimos el tipo de inserción qu3e debemos hacer segun la eleccion del usuario para añadir hotel
					if($parametros['busca_alojamiento_consulta'] == 'C'){
						$insertaAlojamientos = $ClaseReservas->Insertar_alojamientos_cupos($sReservas[0]['localizador'], $parametros['busca_alojamiento'], $parametros['nuevo_uso'], $parametros['nuevo_regimen'], $parametros['nuevo_numero_noches'], $parametros['nuevo_cantidad_habitaciones'], $sReservas[0]['adultos'], $sReservas[0]['ninos'], $sReservas[0]['bebes'], $sReservas[0]['novios']);
					}elseif($parametros['busca_alojamiento_consulta'] == 'O'){
						$insertaAlojamientos = $ClaseReservas->Insertar_alojamientos_onrequest($sReservas[0]['localizador'], $parametros['busca_alojamiento_fecha_desde'], $parametros['busca_alojamiento'], $parametros['nuevo_uso'], $parametros['nuevo_regimen'], $parametros['nuevo_numero_noches'], $parametros['nuevo_cantidad_habitaciones'], $sReservas[0]['adultos'], $sReservas[0]['ninos'], $sReservas[0]['bebes'], $sReservas[0]['novios']);
					}else{
						$error_alojamientos = "Debe seleccionar un tipo de consulta";
					}

					if($insertaAlojamientos == 'OK'){
						$Ntransacciones_alojamientos++;
					}else{
						$error_alojamientos = $insertaAlojamientos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							/*$recuperatemporada_prec = $parametros['Nuevotemporada_temp'.$num_fila]; 
							$recuperahabitacion = $parametros['Nuevohabitacion'.$num_fila]; 
							$recuperacaracteristica = $parametros['Nuevocaracteristica'.$num_fila]; 
							$recuperauso = $parametros['Nuevouso'.$num_fila]; 
							$recuperacoste_pax = $parametros['Nuevocoste_pax'.$num_fila]; 
							$recuperacoste_habitacion = $parametros['Nuevocoste_habitacion'.$num_fila]; 
							$recuperacalculo = $parametros['Nuevocalculo'.$num_fila]; 
							$recuperaporcentaje_com = $parametros['Nuevoporcentaje_com'.$num_fila]; 
							$recuperapvp_pax = $parametros['Nuevopvp_pax'.$num_fila]; 	
						$recuperapvp_habitacion = $parametros['Nuevopvp_habitacion'.$num_fila];*/
					}
				}

				//Actualizamos los precios de la reserva
				$actualizar_precio = "CALL `RESERVAS_ACTUALIZA_PRECIOS`('R','".$parametros['localizador0']."')";
				$resultadoactualizar =$conexion->query($actualizar_precio);
					//echo($expandir);
				if ($resultadoactualizar == FALSE){
					$error = 'No se ha podido atualizar el precio de la reserva. '.$conexion->error;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_alojamientos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_alojamientos."</b></font></div>";
				if($error_alojamientos != ''){
					$mensaje2_alojamientos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_alojamientos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();

			}


			//Llamada a la clase especifica de la pantalla
			$sAlojamientos = $ClaseReservas->Cargar_alojamientos($sReservas[0]['localizador']);	

			//Llamada a la clase general de combos
			$comboHabitaciones = $combos->Cargar_combo_Habitaciones();
			$comboHabitaciones_car = $combos->Cargar_combo_Habitaciones_car();
			$comboRegimen = $combos->Cargar_combo_Regimen();
			//$comboProvincias = $combos->Cargar_combo_Provincias();
			//$comboCiudades = $combos->Cargar_combo_Ciudades();
			//$comboCategorias = $combos->Cargar_combo_Categorias();
			//$comboSituacion = $combos->Cargar_combo_Situacion();
			//$comboAlojamientos = $combos->Cargar_combo_Alojamientos();
			$comboTipo_consulta_alojamientos = $combos->Cargar_Tipo_Consulta_Alojamientos();
			$comboSituacion_cupos = $combos->Cargar_Combo_Situacion_Cupos();

			/*echo('<pre>');
			print_r($sComboSelectGrupos_gestion_comisiones);
			echo('</pre>');*/

		//RECUPERAMOS DE NUEVO LOS DATOS DE LA RESERVA PARA ACTUALIZAR VALORES
			/*$sReservas = $oReservas->Cargar($recuperareferencia,$recuperafolleto,$recuperacuadro,$recuperaciudad_salida,$recuperapaquete,$recuperafecha_salida,$recuperanombre_titular,$recuperaadultos,$recuperaninos,$recuperabebes,$recuperanovios,$recuperajubilados,$recuperaobservaciones,$recuperaagente,$recuperareferencia_agencia,$recuperaenvio,$recuperadivisa_actual,$recuperamodificacion_motivo,$recuperamodificacion_responsable,$recuperafree,$recuperamodificar,$recuperamodificar_comisionable,$recuperamodificar_detalle_comisionable,$recuperamodificar_no_comisionable,$recuperamodificar_detalle_no_comisionable,$recuperamodificar_comision,$recuperamodificar_usuario,$recuperavisa);
			*/



	//-----------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS SERVICIOS ---------
	//-----------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/



			if($parametros['actuar_5nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT count(*) LINEAS FROM hit_reservas_servicios WHERE LOCALIZADOR = '".$sReservas[0]['localizador']."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones_servicios = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS']-1; $num_fila++) {
					if($parametros['selec_5nivel'.$num_fila] == 'S' || $parametros['borra_5nivel'.$num_fila] == 'S'){
						if($parametros['selec_5nivel'.$num_fila] == 'S'){
							
							$modificaServicios = $ClaseReservas->Modificar_servicios($parametros['servicios_clave'.$num_fila], $parametros['servicios_orden'.$num_fila], $parametros['servicios_adultos'.$num_fila], $parametros['servicios_ninos'.$num_fila], $parametros['servicios_veces'.$num_fila], $parametros['servicios_situacion'.$num_fila], $parametros['servicios_pvp_total'.$num_fila], $parametros['servicios_tipo_precio'.$num_fila]);
							if($modificaServicios == 'OK'){
								$Ntransacciones_servicios++;
							}else{
								$error_servicios = $modificaServicios;
							}

						}else{

							$borraServicios = $ClaseReservas->Borrar_servicios($parametros['servicios_clave'.$num_fila]);
							if($borraServicios == 'OK'){
								$Ntransacciones_servicios++;
							}else{
								$error_servicios = $borraServicios;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				if($parametros['Añadir_Reserva_Servicio'] == 'S'){

					$insertaServicios = $ClaseReservas->Insertar_servicios($sReservas[0]['localizador'], $parametros['busca_servicio_fecha'], $parametros['busca_servicio'], $parametros['nuevo_pax'], $sReservas[0]['adultos'], $sReservas[0]['ninos'], $sReservas[0]['bebes'], $sReservas[0]['novios']);

					if($insertaServicios == 'OK'){
						$Ntransacciones_servicios++;
					}else{
						$error_servicios = $insertaServicios;
					}
				}

				//Actualizamos los precios de la reserva
				$actualizar_precio = "CALL `RESERVAS_ACTUALIZA_PRECIOS`('R','".$parametros['localizador0']."')";
				$resultadoactualizar =$conexion->query($actualizar_precio);
					//echo($expandir);
				if ($resultadoactualizar == FALSE){
					$error = 'No se ha podido atualizar el precio de la reserva. '.$conexion->error;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_servicios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_servicios."</b></font></div>";
				if($error_servicios != ''){
					$mensaje2_servicios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_servicios."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();

			}


			//Llamada a la clase especifica de la pantalla
			$sServicios = $ClaseReservas->Cargar_servicios($sReservas[0]['localizador']);	

			//Llamada a la clase general de combos
			//$comboCiudades = $combos->Cargar_combo_Ciudades();
			$comboSituacion = $combos->Cargar_combo_Situacion();
			$comboProveedores = $combos->Cargar_combo_Proveedores();
			$comboTipos_servicios = $combos->Cargar_combo_Tipos_Servicio();
			$comboTipo_precios_reserva = $combos->Cargar_Combo_Tipo_Precio_Reserva();



			//-----------------------------------------------------
			//---SECCION DEL CODIGO PARA LAS CONDICIONES ----------
			//-----------------------------------------------------

			//Llamada a la clase especifica de la pantalla
			$sCondiciones = $ClaseReservas->Cargar_condiciones($sReservas[0]['localizador']);	


			//*************IMPORTANTE****************
			//VOLVEMOS A LLAMAR AL METODO QUE CARGA LOS DTOAS DE HIT_RESERVAS PARA ACTUALIZAR LOS CAMBIOS DE PRECIOS Y DEMAS.
			$sReservas = $ClaseReservas->Cargar($recuperareferencia,$recuperafolleto,$recuperacuadro,$recuperaciudad_salida,$recuperapaquete,$recuperaregimen,$recuperafecha_salida,$recuperanombre_titular,$recuperaadultos,$recuperaninos,$recuperabebes,$recuperanovios,$recuperajubilados,$recuperaobservaciones,$recuperaagente,$recuperareferencia_agencia,$recuperaenvio,$recuperadivisa_actual,$recuperamodificacion_motivo,$recuperamodificacion_responsable,$recuperafree,$recuperamodificar,$recuperamodificar_comisionable,$recuperamodificar_detalle_comisionable,$recuperamodificar_no_comisionable,$recuperamodificar_detalle_no_comisionable,$recuperamodificar_comision,$recuperamodificar_usuario,$recuperavisa, $recuperaobservaciones_internas, $recuperaobservaciones_hotel, $recuperaobservaciones_clientes);



	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LAS RESERVAS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#00AF50');
			$smarty->assign('grupo', '» RESERVAS');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» MANTENIMIENTO');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);
			$smarty->assign('usuario', $usuario);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectReservas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('reservas', $sReservas);

			//Indicamos si hay que visualizar o no la seccion DE OBSERVACIONES
			$smarty->assign('seccion_observaciones_display', $parametros['seccion_observaciones_display']);
			
			//Indicamos si hay que visualizar o no la seccion DE MODIFICAR AGENCIA
			$smarty->assign('seccion_buscar_agencia_display', $parametros['seccion_buscar_agencia_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboRegimen', $comboRegimen);
			$smarty->assign('comboReservas', $comboReservas);
			//$smarty->assign('comboAgencias', $comboAgencias);
			$smarty->assign('comboAlternativas_Aereas', $comboAlternativas_Aereas);


			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_localizador', $parametros['buscar_localizador']);
			$smarty->assign('buscar_referencia', $parametros['buscar_referencia']);
			$smarty->assign('buscar_referencia_agencia', $parametros['buscar_referencia_agencia']);
			$smarty->assign('buscar_folleto', $parametros['buscar_folleto']);			
			$smarty->assign('buscar_cuadro', $parametros['buscar_cuadro']);
			$smarty->assign('buscar_fecha_salida', $parametros['buscar_fecha_salida']);
			$smarty->assign('buscar_fecha_reserva', $parametros['buscar_fecha_reserva']);
			$smarty->assign('buscar_minorista', $parametros['buscar_minorista']);
			$smarty->assign('buscar_oficina', $parametros['buscar_oficina']);
			$smarty->assign('buscar_telefono_oficina', $parametros['buscar_telefono_oficina']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);



			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS PASAJEROS---------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_pasajeros', $filadesde_pasajeros);
		

			//Cargar combo selector
			$smarty->assign('combo_pasajeros', $sComboSelectPasajeros);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('pasajeros', $sPasajeros);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboPasajerosSexo', $comboPasajerosSexo);
			$smarty->assign('comboPasajerosTipo', $comboPasajerosTipo);
			$smarty->assign('comboPasajerosDocumentoTipo', $comboPasajerosDocumentoTipo);
			$smarty->assign('comboPaises', $comboPaises);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_pasajeros', $mensaje1_pasajeros);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_pasajeros', $mensaje2_pasajeros);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_apellido', $parametros['buscar_apellido']);
			$smarty->assign('buscar_nombre_pax', $parametros['buscar_nombre_pax']);
			$smarty->assign('paginacion_pasajeros', $parametros['paginacion_pasajeros']);


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS AEREOS ----------
			//---------------------------------------------

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('aereos', $sAereos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAcuerdosAereos', $comboAcuerdosAereos);

			//Indicamos si hay que visualizar o no la seccion DE AÑADIR AEREOS
			$smarty->assign('seccion_anadir_aereos_display', $parametros['seccion_anadir_aereos_display']);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('aereosnuevos', $sAereos_nuevos);	

			$smarty->assign('recuperaaereos_orden', $recuperaaereos_orden);	
			$smarty->assign('recuperaaereos_reserva', $recuperaaereos_reserva);	
			$smarty->assign('recuperaaereos_tipo_cuerdo', $recuperaaereos_tipo_cuerdo);	
			$smarty->assign('recuperaaereos_cia', $recuperaaereos_cia);	
			$smarty->assign('recuperaaereos_plazas', $recuperaaereos_plazas);	
			$smarty->assign('recuperaaereos_fecha_reserva', $recuperaaereos_fecha_reserva);	
			$smarty->assign('recuperaaereos_emitido', $recuperaaereos_emitido);	
			$smarty->assign('recuperaaereos_fecha_emision', $recuperaaereos_fecha_emision);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_aereos', $mensaje1_aereos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_aereos', $mensaje2_aereos);

				
				//----------------------------------------------------------
				//----VISUALIZAR PARTE DE LOS PASAJEROS DE AEREOS ----------
				//Indicamos si hay que visualizar o no la seccion
				$smarty->assign('seccion_modificar_aereos_pasajeros_display', $parametros['seccion_modificar_aereos_pasajeros_display']);

				$smarty->assign('aereos_pasajeros', $sAereos_pasajeros);

				//Cargar combos de las lineas de la tabla
				$smarty->assign('comboReservasAereos', $comboReservasAereos);
				$smarty->assign('comboReservasPasajeros', $comboReservasPasajeros);

				//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
				$smarty->assign('aereos_pasajerosnuevos', $sAereos_pasajerosnuevos);

				$smarty->assign('recuperaaereos_clave', $recuperaaereos_clave);	
				$smarty->assign('recuperaaereos_pasajero_numero', $recuperaaereos_pasajero_numero);	

				//Cargar mensaje de numero de transacciones realizadas
				$smarty->assign('mensaje1_aereos_pasajeros', $mensaje1_aereos_pasajeros);

				//Cargar mensaje de error si se da error
				$smarty->assign('mensaje2_aereos_pasajeros', $mensaje2_aereos_pasajeros);


				//----------------------------------------------------------
				//----VISUALIZAR PARTE DE LOS TRAYECTOS DE AEREOS ----------

				$smarty->assign('aereos_trayectos', $sAereos_trayectos);

				//Indicamos si hay que visualizar o no la seccion DE AÑADIR TRAYECTOS MANUAL
				$smarty->assign('seccion_anadir_trayectos_manual_display', $parametros['seccion_anadir_trayectos_manual_display']);

				//Indicamos si hay que visualizar o no la seccion DE AÑADIR TRAYECTOS CUPOS
				$smarty->assign('seccion_anadir_trayectos_cupos_display', $parametros['seccion_anadir_trayectos_cupos_display']);

				//Cargar combos de las lineas de busqueda
				$smarty->assign('comboTipostrayecto', $comboTipos_trayecto);

				//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
				$smarty->assign('aereos_trayectosnuevos', $sAereos_trayectosnuevos);

				$smarty->assign('recuperaaereos_trayectos_fecha', $recuperaaereos_trayectos_fecha);	
				$smarty->assign('recuperaaereos_trayectos_origen', $recuperaaereos_trayectos_origen);	
				$smarty->assign('recuperaaereos_trayectos_destino', $recuperaaereos_trayectos_destino);	
				$smarty->assign('recuperaaereos_trayectos_cia', $recuperaaereos_trayectos_cia);	
				$smarty->assign('recuperaaereos_trayectos_vuelo', $recuperaaereos_trayectos_vuelo);	
				$smarty->assign('recuperaaereos_trayectos_hora_salida', $recuperaaereos_trayectos_hora_salida);	
				$smarty->assign('recuperaaereos_trayectos_hora_llegada', $recuperaaereos_trayectos_hora_llegada);	
				$smarty->assign('recuperaaereos_trayectos_desplazamiento_llegada', $recuperaaereos_trayectos_desplazamiento_llegada);	
				$smarty->assign('recuperaaereos_trayectos_clase', $recuperaaereos_trayectos_clase);	
				$smarty->assign('recuperaaereos_trayectos_situacion', $recuperaaereos_trayectos_situacion);	
				$smarty->assign('recuperaaereos_trayectos_acuerdo', $recuperaaereos_trayectos_acuerdo);	
				$smarty->assign('recuperaaereos_trayectos_subacuerdo', $recuperaaereos_trayectos_subacuerdo);
				$smarty->assign('recuperaaereos_trayectos_pvp_total_trayecto', $recuperaaereos_trayectos_pvp_total_trayecto);
				$smarty->assign('recuperaaereos_trayectos_tasas_pvp_total_trayecto', $recuperaaereos_trayectos_tasas_pvp_total_trayecto);
				$smarty->assign('recuperaaereos_trayectos_tipo_precio', $recuperaaereos_trayectos_tipo_precio);
				$smarty->assign('recuperaaereos_trayectos_clave_aereo', $recuperaaereos_trayectos_clave_aereo);

				//Cargar mensaje de numero de transacciones realizadas
				$smarty->assign('mensaje1_aereos_trayectos', $mensaje1_aereos_trayectos);

				//Cargar mensaje de error si se da error
				$smarty->assign('mensaje2_aereos_trayectos', $mensaje2_aereos_trayectos);

			
			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS ALOJAMIENTOS ---------
			//---------------------------------------------


			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos', $sAlojamientos);

			//Indicamos si hay que visualizar o no la seccion DE MODIFICAR ALOJAMIENTOS
			$smarty->assign('seccion_anadir_alojamiento_display', $parametros['seccion_anadir_alojamiento_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboHabitaciones', $comboHabitaciones);
			$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car);
			$smarty->assign('comboRegimen', $comboRegimen);
			//$smarty->assign('comboProvincias', $comboProvincias);
			//$smarty->assign('comboCiudades', $comboCiudades);
			//$smarty->assign('comboCategorias', $comboCategorias);
			//$smarty->assign('comboSituacion', $comboSituacion);
			//$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboTipo_consulta_alojamientos', $comboTipo_consulta_alojamientos);
			$smarty->assign('comboSituacion_cupos', $comboSituacion_cupos);

			//Comprobamos si hay que sacar cabecera de Interfaces
			$datos_interfaz=$conexion->query("select count(*) hay_interfaz from hit_reservas_alojamientos where interfaz_codigo is not null and localizador = '".$sReservas[0]['localizador']."'");
			$odatos_interfaz = $datos_interfaz->fetch_assoc();
			$hay_interfaz_alojamientos = $odatos_interfaz['hay_interfaz'];
			//echo($hay_interfaz_alojamientos);

			$smarty->assign('hay_interfaz_alojamientos', $hay_interfaz_alojamientos);


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_alojamientos', $mensaje1_alojamientos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_alojamientos', $mensaje2_alojamientos);


			//-----------------------------------------------
			//----VISUALIZAR PARTE DE LOS SERVICIOS ---------
			//-----------------------------------------------

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('servicios', $sServicios);

			//Indicamos si hay que visualizar o no la seccion DE MODIFICAR ALOJAMIENTOS
			$smarty->assign('seccion_anadir_servicio_display', $parametros['seccion_anadir_servicio_display']);

			//Cargar combos de las lineas de la tabla
			//$smarty->assign('comboCiudades', $comboCiudades);
			$smarty->assign('comboSituacion', $comboSituacion);
			$smarty->assign('comboProveedores', $comboProveedores);
			$smarty->assign('comboTipos_servicios', $comboTipos_servicios);
			$smarty->assign('comboTipo_precios_reserva', $comboTipo_precios_reserva);
			
			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_servicios', $mensaje1_servicios);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_servicios', $mensaje2_servicios);


			//-----------------------------------------------
			//----VISUALIZAR PARTE DE LOS SERVICIOS ---------
			//-----------------------------------------------

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('condiciones', $sCondiciones);


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			if($parametros['manda_posicion']){
				$smarty->assign('posicion', $parametros['manda_posicion']);
			}else{
				$smarty->assign('posicion', 'buscar_localizador');
			}

			$smarty->display('plantillas/Reservas.html');


		}elseif($parametros['ir_a'] == 'F'){

			header("Location: Reservas_borrador.php?localizador=".$parametros['localizador0']);

		}else{
			session_destroy();
			exit;
		}


	}else{

//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
//--------------------------------------------------------------------------------------------------------------------------------

			//Llamada a la clase especifica de la pantalla
			$sReservas = $ClaseReservas->Cargar($recuperareferencia,$recuperafolleto,$recuperacuadro,$recuperaciudad_salida,$recuperapaquete,$recuperaregimen,$recuperafecha_salida,$recuperanombre_titular,$recuperaadultos,$recuperaninos,$recuperabebes,$recuperanovios,$recuperajubilados,$recuperaobservaciones,$recuperaagente,$recuperareferencia_agencia,$recuperaenvio,$recuperadivisa_actual,$recuperamodificacion_motivo,$recuperamodificacion_responsable,$recuperafree,$recuperamodificar,$recuperamodificar_comisionable,$recuperamodificar_detalle_comisionable,$recuperamodificar_no_comisionable,$recuperamodificar_detalle_no_comisionable,$recuperamodificar_comision,$recuperamodificar_usuario,$recuperavisa, $recuperaobservaciones_internas, $recuperaobservaciones_hotel, $recuperaobservaciones_clientes);
			$sComboSelectReservas = $ClaseReservas->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$comboSino = $combos->Cargar_combo_SiNo();
			$comboDivisas = $combos->Cargar_combo_Divisas();
			$comboFolletos = $combos->Cargar_combo_Folletos();
			$comboRegimen = $combos->Cargar_combo_Regimen();
			$comboReservas = $combos->Cargar_combo_Reservas($parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);

			//$comboAgencias = $oSino->Cargar_combo_Agencias($parametros['buscar_agencia_minorista'], $parametros['buscar_agencia_oficina'], $parametros['buscar_agencia_direccion'], $parametros['buscar_agencia_telefono']);

			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//--------------------------------------------------------
			//----VISUALIZAR PARTE DE LA RESERVAS---------------------
			//--------------------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#00AF50');
			$smarty->assign('grupo', '» RESERVAS');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» MANTENIMIENTO');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);
			$smarty->assign('usuario', $usuario);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectReservas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('reservas', $sReservas);

			//Indicamos si hay que visualizar o no la seccion DE OBSERVACIONES
			$smarty->assign('seccion_observaciones_display', 'none');			
			
			//Indicamos si hay que visualizar o no la seccion DE AGENCIAS
			$smarty->assign('seccion_buscar_agencia_display', 'none');

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboRegimen', $comboRegimen);
			$smarty->assign('comboReservas', $comboReservas);
			//$smarty->assign('comboAgencias', $comboAgencias);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_localizador', '');
			$smarty->assign('buscar_referencia', '');
			$smarty->assign('buscar_referencia_agencia', '');
			$smarty->assign('buscar_folleto', '');			
			$smarty->assign('buscar_cuadro', '');
			$smarty->assign('buscar_fecha_salida', '');
			$smarty->assign('buscar_fecha_reserva', '');
			$smarty->assign('buscar_minorista', '');
			$smarty->assign('buscar_oficina', '');
			$smarty->assign('buscar_telefono_oficina', '');
			$smarty->assign('buscar_nombre', '');


		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE PASAJEROS

		$filadesde_pasajeros = 1;
		$parametros['buscar_apellido'] = "";
		$parametros['buscar_nombre_pax'] = "";
		$parametros['paginacion_pasajeros'] = "N";

		//Llamada a la clase especifica de la pantalla
		$sPasajeros = $ClaseReservas->Cargar_pasajeros($sReservas[0]['localizador'],$filadesde_pasajeros,$parametros['buscar_apellido'],$parametros['buscar_nombre_pax'],$parametros['paginacion_pasajeros']);	
		$sComboSelectPasajeros = $ClaseReservas->Cargar_combo_selector_pasajeros($sReservas[0]['localizador'],$parametros['buscar_apellido'],$parametros['buscar_nombre_pax'],$parametros['paginacion_pasajeros']);
		//Llamada a la clase general de combos
		$comboSino = $combos->Cargar_combo_SiNo();
		$comboPasajerosSexo = $combos->Cargar_combo_Pasajeros_sexo();
		$comboPasajerosTipo = $combos->Cargar_combo_Pasajeros_tipo();
		$comboPasajerosDocumentoTipo = $combos->Cargar_combo_Pasajeros_documento_tipo();
		$comboPaises = $combos->Cargar_combo_Paises_Reserva();

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades_pasajeros', $filadesde_pasajeros);
		

		//Cargar combo selector
		$smarty->assign('combo_pasajeros', $sComboSelectPasajeros);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('pasajeros', $sPasajeros);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboPasajerosSexo', $comboPasajerosSexo);
		$smarty->assign('comboPasajerosTipo', $comboPasajerosTipo);
		$smarty->assign('comboPasajerosDocumentoTipo', $comboPasajerosDocumentoTipo);
		$smarty->assign('comboPaises', $comboPaises);

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_pasajeros', '');

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_pasajeros', '');

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_apellido', '');
		$smarty->assign('buscar_nombre_pax', '');
		$smarty->assign('paginacion_pasajeros', 'N');


		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE TRANSPORTES

		//$filadesde_temporadas = 1;
		//$parametros['buscar_temporada'] = "";

		//Llamada a la clase especifica de la pantalla
		$sAereos = $ClaseReservas->Cargar_aereos($sReservas[0]['localizador']);	
		$sAereos_nuevos = $ClaseReservas->Cargar_lineas_nuevas_aereos();
		//Llamada a la clase general de combos
		$comboAcuerdosAereos = $combos->Cargar_combo_Tipos_acuerdo_transportes();

		//Indicamos si hay que visualizar o no la seccion DE AGENCIAS
		$smarty->assign('seccion_anadir_aereos_display', 'none');

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('aereos', $sAereos);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboAcuerdosAereos', $comboAcuerdosAereos);


		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('aereosnuevos', $sAereos_nuevos);
		

		$smarty->assign('recuperaaereos_orden', $recuperaaereos_orden);	
		$smarty->assign('recuperaaereos_reserva', $recuperaaereos_reserva);	
		$smarty->assign('recuperaaereos_tipo_cuerdo', $recuperaaereos_tipo_cuerdo);	
		$smarty->assign('recuperaaereos_cia', $recuperaaereos_cia);	
		$smarty->assign('recuperaaereos_plazas', $recuperaaereos_plazas);	
		$smarty->assign('recuperaaereos_fecha_reserva', $recuperaaereos_fecha_reserva);	
		$smarty->assign('recuperaaereos_emitido', $recuperaaereos_emitido);	
		$smarty->assign('recuperaaereos_fecha_emision', $recuperaaereos_fecha_emision);	

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_aereos', '');

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_aereos', '');


			//PASAJEROS
			//Llamada a la clase especifica de la pantalla
			$sAereos_pasajeros = $ClaseReservas->Cargar_aereos_pasajeros($sReservas[0]['localizador']);	
			$sAereos_pasajerosnuevos = $ClaseReservas->Cargar_lineas_nuevas_aereos_pasajeros();
			//Llamada a la clase general de combos
			$comboReservasAereos = $combos->Cargar_combo_Reservas_Aereos($sReservas[0]['localizador']);
			$comboReservasPasajeros = $combos->Cargar_combo_Reservas_Pasajeros($sReservas[0]['localizador']);

			//Indicamos si hay que visualizar o no la seccion
			$smarty->assign('seccion_modificar_aereos_pasajeros_display', 'none');

			$smarty->assign('aereos_pasajeros', $sAereos_pasajeros);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboReservasAereos', $comboReservasAereos);
			$smarty->assign('comboReservasPasajeros', $comboReservasPasajeros);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('aereos_pasajerosnuevos', $sAereos_pasajerosnuevos);

			$smarty->assign('recuperaaereos_clave', $recuperaaereos_clave);	
			$smarty->assign('recuperaaereos_pasajero_numero', $recuperaaereos_pasajero_numero);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_aereos_pasajeros', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_aereos_pasajeros', '');


			//TRAYECTOS
			//Llamada a la clase especifica de la pantalla
			$sAereos_trayectos = $ClaseReservas->Cargar_aereos_trayectos($sReservas[0]['localizador']);	
			$sAereos_trayectosnuevos = $ClaseReservas->Cargar_lineas_nuevas_aereos_trayectos();

			//Llamada a la clase general de combos
			$comboTipos_trayecto = $combos->Cargar_Combo_Tipo_Trayecto();

			$smarty->assign('aereos_trayectos', $sAereos_trayectos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboReservasAereos', $comboReservasAereos);

			//Cargar combos de las lineas de busqueda
			$smarty->assign('comboTipostrayecto', $comboTipos_trayecto);

			//Indicamos si hay que visualizar o no la seccion DE AÑADIR AEREOS
			$smarty->assign('seccion_anadir_trayectos_manual_display', 'none');

			//Indicamos si hay que visualizar o no la seccion DE AÑADIR AEREOS
			$smarty->assign('seccion_anadir_trayectos_cupos_display', 'none');

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('aereos_trayectosnuevos', $sAereos_trayectosnuevos);

			$smarty->assign('recuperaaereos_trayectos_fecha', $recuperaaereos_trayectos_fecha);	
			$smarty->assign('recuperaaereos_trayectos_origen', $recuperaaereos_trayectos_origen);	
			$smarty->assign('recuperaaereos_trayectos_destino', $recuperaaereos_trayectos_destino);	
			$smarty->assign('recuperaaereos_trayectos_cia', $recuperaaereos_trayectos_cia);	
			$smarty->assign('recuperaaereos_trayectos_vuelo', $recuperaaereos_trayectos_vuelo);	
			$smarty->assign('recuperaaereos_trayectos_hora_salida', $recuperaaereos_trayectos_hora_salida);	
			$smarty->assign('recuperaaereos_trayectos_hora_llegada', $recuperaaereos_trayectos_hora_llegada);	
			$smarty->assign('recuperaaereos_trayectos_desplazamiento_llegada', $recuperaaereos_trayectos_desplazamiento_llegada);	
			$smarty->assign('recuperaaereos_trayectos_clase', $recuperaaereos_trayectos_clase);	
			$smarty->assign('recuperaaereos_trayectos_situacion', $recuperaaereos_trayectos_situacion);	
			$smarty->assign('recuperaaereos_trayectos_acuerdo', $recuperaaereos_trayectos_acuerdo);	
			$smarty->assign('recuperaaereos_trayectos_subacuerdo', $recuperaaereos_trayectos_subacuerdo);	
			$smarty->assign('recuperaaereos_trayectos_pvp_total_trayecto', $recuperaaereos_trayectos_pvp_total_trayecto);
			$smarty->assign('recuperaaereos_trayectos_tasas_pvp_total_trayecto', $recuperaaereos_trayectos_tasas_pvp_total_trayecto);
			$smarty->assign('recuperaaereos_trayectos_tipo_precio', $recuperaaereos_trayectos_tipo_precio);
			$smarty->assign('recuperaaereos_trayectos_clave_aereo', $recuperaaereos_trayectos_clave_aereo);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_aereos_trayectos', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_aereos_trayectos', '');


		//-----------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS ALOJAMIENTOS
			//Llamada a la clase especifica de la pantalla
		$sAlojamientos = $ClaseReservas->Cargar_alojamientos($sReservas[0]['localizador']);	

		//Llamada a la clase general de combos
		/*$comboHabitaciones = $combos->Cargar_combo_Habitaciones();
		$comboHabitaciones_car = $combos->Cargar_combo_Habitaciones_car();
		$comboRegimen = $combos->Cargar_combo_Regimen();
		$comboProvincias = $combos->Cargar_combo_Provincias();
		$comboCiudades = $combos->Cargar_combo_Ciudades();
		$comboCategorias = $combos->Cargar_combo_Categorias();
		$comboSituacion = $combos->Cargar_combo_Situacion();
		$comboAlojamientos = $combos->Cargar_combo_Alojamientos();
		$comboTipo_consulta_alojamientos = $combos->Cargar_Tipo_Consulta_Alojamientos();
		$comboSituacion_cupos = $combos->Cargar_Combo_Situacion_Cupos();*/

		$comboHabitaciones = "";
		$comboHabitaciones_car = "";
		$comboRegimen = "";
		$comboProvincias = "";
		$comboCiudades = "";
		$comboCategorias = "";
		$comboSituacion = "";
		$comboAlojamientos = "";
		$comboTipo_consulta_alojamientos = "";
		$comboSituacion_cupos = "";

		//Llamada a la clase especifica de la pantalla
		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('alojamientos', $sAlojamientos);

		//Indicamos si hay que visualizar o no la seccion DE AÑADIR ALOJAMIENTOS
		$smarty->assign('seccion_anadir_alojamiento_display', 'none');

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboHabitaciones', $comboHabitaciones);
		$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car);
		$smarty->assign('comboRegimen', $comboRegimen);
		//$smarty->assign('comboProvincias', $comboProvincias);
		//$smarty->assign('comboCiudades', $comboCiudades);
		//$smarty->assign('comboCategorias', $comboCategorias);
		//$smarty->assign('comboSituacion', $comboSituacion);
		//$smarty->assign('comboAlojamientos', $comboAlojamientos);
		$smarty->assign('comboTipo_consulta_alojamientos', $comboTipo_consulta_alojamientos);
		$smarty->assign('comboSituacion_cupos', $comboSituacion_cupos);

		$smarty->assign('hay_interfaz_alojamientos', 0);

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_alojamientos', $mensaje1_servicios);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_alojamientos', $mensaje2_servicios);


		//-----------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS SERVICIOS

		//Llamada a la clase especifica de la pantalla
		$sServicios = $ClaseReservas->Cargar_servicios($sReservas[0]['localizador']);	

		//Llamada a la clase general de combos
		//$comboCiudades = $combos->Cargar_combo_Ciudades();
		$comboSituacion = $combos->Cargar_combo_Situacion();
		$comboProveedores = $combos->Cargar_combo_Proveedores();
		$comboTipos_servicios = $combos->Cargar_combo_Tipos_Servicio();
		$comboTipo_precios_reserva = $combos->Cargar_Combo_Tipo_Precio_Reserva();
		
		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('servicios', $sServicios);

		//Indicamos si hay que visualizar o no la seccion DE AÑADIR ALOJAMIENTOS
		$smarty->assign('seccion_anadir_servicio_display', 'none');

		//Cargar combos de las lineas de la tabla
		//$smarty->assign('comboCiudades', $comboCiudades);
		$smarty->assign('comboSituacion', $comboSituacion);
		$smarty->assign('comboProveedores', $comboProveedores);
		$smarty->assign('comboTipos_servicios', $comboTipos_servicios);
		$smarty->assign('comboTipo_precios_reserva', $comboTipo_precios_reserva);


		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_servicios', $mensaje1_servicios);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_servicios', $mensaje2_servicios);


		//-------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS CONDICIONES

		//Llamada a la clase especifica de la pantalla
		$sCondiciones = $ClaseReservas->Cargar_condiciones($sReservas[0]['localizador']);
		
		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('condiciones', $sCondiciones);


		//Visualizar plantilla
		$smarty->assign('posicion', 'buscar_localizador');

		$smarty->display('plantillas/Reservas.html');

	}
	
	$conexion->close();


?>

