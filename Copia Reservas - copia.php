<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/Reservas.cls.php';
	include("class.phpmailer.php"); 
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
			$recuperareferencia = '';
			$recuperafolleto = '';
			$recuperacuadro = '';
			$recuperaciudad_salida = '';
			$recuperapaquete = '';
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

			$recuperaaereos_clave = '';	
			$recuperaaereos_pasajero_numero = '';	
			$mensaje1_aereos_pasajeros = '';
			$mensaje2_aereos_pasajeros = '';
			$error_aereos_pasajeros = '';	

			//VARIABLES PARA LOS ALOJAMIENTOS

			$mensaje1_alojamientos = '';
			$mensaje2_alojamientos = '';
			$error_alojamientos = '';

			//VARIABLES PARA LOS ALOJAMIENTOS

			$mensaje1_servicios = '';
			$mensaje2_servicios = '';
			$error_servicios = '';

	if(count($_POST) != 0){
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
		
		//Si no se ha pulsado el boton Salir ni el de Borrador
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'F'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LA RESERVA--------------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonReservas = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);


				$botonselec = $botonReservas->Botones_selector($filadesde, $parametros['botonSelector']);
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

							$mReservas = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$modificaReservas = $mReservas->Modificar($parametros['localizador'.$num_fila],$parametros['referencia'.$num_fila],
								$parametros['codigo_situacion'.$num_fila],
								$parametros['bloqueada'.$num_fila],$parametros['bloqueada_usuario'.$num_fila],$parametros['folleto'.$num_fila],$parametros['cuadro'.$num_fila],$parametros['ciudad_salida'.$num_fila],$parametros['paquete'.$num_fila],$parametros['fecha_salida'.$num_fila],$parametros['fecha_regreso'.$num_fila],$parametros['fecha_modificacion'.$num_fila],$parametros['usuario_modificacion'.$num_fila],
								$parametros['nombre_titular'.$num_fila],$parametros['pax'.$num_fila],$parametros['adultos'.$num_fila],
								$parametros['ninos'.$num_fila],$parametros['bebes'.$num_fila],$parametros['novios'.$num_fila],$parametros['observaciones'.$num_fila],
								$parametros['minorista'.$num_fila],$parametros['oficina'.$num_fila],$parametros['busca_agencias'],$parametros['agente'.$num_fila],
								$parametros['referencia_agencia'.$num_fila],$parametros['envio'.$num_fila],$parametros['divisa_actual'.$num_fila],
								$parametros['free'.$num_fila],
								$parametros['modificar'.$num_fila],$parametros['modificar_comisionable'.$num_fila],$parametros['modificar_detalle_comisionable'.$num_fila],$parametros['modificar_no_comisionable'.$num_fila],$parametros['modificar_detalle_no_comisionable'.$num_fila], $parametros['modificar_comision'.$num_fila],$parametros['modificacion_motivo'.$num_fila],$parametros['modificacion_responsable'.$num_fila]);

							if($modificaReservas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaReservas;
							}

						}else{

							$mReservas = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$anulaReservas = $mReservas->Anular($parametros['localizador'.$num_fila]);
							if($anulaReservas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $anulaReservas;
							}
						}
					}
				}
				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				$num_filas->close();

			/*}elseif($parametros['actuar'] == 'E'){

				//EXPANDIMOS CUPOS
				$Mensaje = '';
							
				$eAcuerdos = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
				if($expandirCupos == 'OK'){
					$Mensaje = "Se han expandido todos los cupos que no existieran ya";
				}else{
					$error = $expandirCupos;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//$eAcuerdos->close();*/

			}elseif($parametros['actuar'] == 'D'){

				$Ntransacciones = 0;

				$mReservas = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
				$bloquearReservas = $mReservas->Bloquear_desbloquear($parametros['localizador0']);
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
			}

			if($parametros['grabar_registro'] == 'S'){

				//AÑADIR REGISTROS
				//$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_GESTION' AND USUARIO = '".$usuario."'");
				//$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				$Ntransacciones = 0;				

				$iReserva = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
				$insertaReserva = $iReserva->Insertar($parametros['referencia0'],$parametros['folleto0'],$parametros['cuadro0'],$parametros['ciudad_salida0'],$parametros['paquete0'],$parametros['fecha_salida0'],$parametros['nombre_titular0'],$parametros['adultos0'],$parametros['ninos0'],$parametros['bebes0'],$parametros['novios0'],$parametros['observaciones0'],$parametros['minorista0'],$parametros['oficina0'],$parametros['busca_agencias'],$parametros['agente0'],$parametros['referencia_agencia0'],$parametros['envio0'],$parametros['divisa_actual0'],$parametros['free0']);

				if(substr($insertaReserva,0,5) == 'No se'){

					$error = $insertaReserva;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					//$recuperaid = $parametros['id'.$num_fila];

					$recuperareferencia = $parametros['referencia0'];
					$recuperafolleto = $parametros['folleto0'];
					$recuperacuadro = $parametros['cuadro0'];
					$recuperaciudad_salida = $parametros['ciudad_salida0'];
					$recuperapaquete = $parametros['paquete0'];
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

				}else{

					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_localizador'] = $insertaReserva;
					$parametros['nuevo_registro'] = 'N'; //Para que si pulsa de nuevo grabar no añada otra reserva

					//enviamos mail de aviso de nueva reserva
					//FUNCION ANTIGUA PHP
					/*$resultado_mail = mail("jfrias@panavision-tours.es", "NUEVA RESERVA HIT", "se ha realizado una nueva reserva con el loc: ");
					if($resultado_mail){
						echo("Correo de aviso enviado correctamente");
					}else{
						echo("El correo no a podido enviarse");
					}*/
					//CON PHP MAILER (ORIENTADO A OBJETOS)
					/*$mail = new phpmailer();

					$mail->From = ("josefdefrias@gmail.com");
					$mail->FromName = ("Administrador de Hit");
					$mail->Body = ("Nueva reserva realizada");
					$mail->AddAddress = ("jfrias@panavision-tours.es");

					if(!$mail->Send()){
						echo "Correo enviado correctamente";
					}else{
						echo "El correo no se ha podido enviar";
					}*/
					
					//otro ejemplo que si funciona
					$email = new PHPMailer();
					$email->IsSMTP();
					$email->SMTPAuth = true;
					$email->SMTPSecure = "ssl";
					$email->Host = "smtp.gmail.com";
					$email->Port = 465;
					$email->Username = 'josefdefrias@gmail.com';
					$email->From = "josefdefrias@gmail.com";
					$email->Password = "dacasale"; 

					$email->From = "josefdefrias@gmail.com";
					$email->FromName = "it@hit.es";
					$email->Subject = "Nueva reserva realizada: ".$insertaReserva;
					$email->MsgHTML("<b>nueva reserva hecha</b>");
					//$this->email->AltBody("mensaje");
					//AltBody se envía el mensaje en texto plano y 
					//MsgHTML el mensaje en formato HTML
					$email->AddAddress("jfrias@panavision-tours.es", "destinatario");


					$email->IsHTML(true);

					$email->Send();
					//NO DESCOMENTAR ESTO, QUEDA LA PANTALLA EN BLANCO
					/*if(!$email->Send()) {
					   //return "<b>Error:" . $email->ErrorInfo."</b><br/>";
					} 
					else {
					    return "Mensaje enviado correctamente";
					}*/



				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}


			//Llamada a la clase especifica de la pantalla
			$oReservas = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
			$sReservas = $oReservas->Cargar($recuperareferencia,$recuperafolleto,$recuperacuadro,$recuperaciudad_salida,$recuperapaquete,$recuperafecha_salida,$recuperanombre_titular,$recuperaadultos,$recuperaninos,$recuperabebes,$recuperanovios,$recuperajubilados,$recuperaobservaciones,$recuperaagente,$recuperareferencia_agencia,$recuperaenvio,$recuperadivisa_actual,$recuperamodificacion_motivo,$recuperamodificacion_responsable,$recuperafree,$recuperamodificar,$recuperamodificar_comisionable,$recuperamodificar_detalle_comisionable,$recuperamodificar_no_comisionable,$recuperamodificar_detalle_no_comisionable,$recuperamodificar_comision,$recuperamodificar_usuario,$recuperavisa);

			$sComboSelectReservas = $oReservas->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboDivisas = $oSino->Cargar_combo_Divisas();
			$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboReservas = $oSino->Cargar_combo_Reservas($parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);

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
				$botonPasajeros = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
				$botonselec_pasajeros = $botonPasajeros->Botones_selector_pasajeros($filadesde_pasajeros, $parametros['botonSelector_2nivel'], $parametros['paginacion_pasajeros']);
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

							$mPasajeros = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$modificaPasajeros = $mPasajeros->Modificar_pasajeros($sReservas[0]['localizador'], $parametros['numero'.$num_fila], $parametros['apellido'.$num_fila], $parametros['nombre_pax'.$num_fila], $parametros['sexo'.$num_fila],$parametros['tipo'.$num_fila], $parametros['edad'.$num_fila], $parametros['documento_tipo'.$num_fila], $parametros['documento'.$num_fila], $parametros['fecha_nacimiento'.$num_fila], $parametros['pais'.$num_fila], $parametros['telefono'.$num_fila], $parametros['observaciones'.$num_fila]);
							if($modificaPasajeros == 'OK'){
								$Ntransacciones_pasajeros++;
							}else{
								$error_pasajeros = $modificaPasajeros;
							}

						}else{

							$mPasajeros = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$borraPasajeros = $mPasajeros->Borrar_pasajeros($sReservas[0]['localizador'], $parametros['numero'.$num_fila], $sReservas[0]['pax']);
							if($borraPasajeros == 'OK'){
								$Ntransacciones_pasajeros++;
							}else{
								$error_pasajeros = $borraPasajeros;

							}
						}
					}
				}
				if($Ntransacciones_pasajeros > 0){
						$mPasajeros = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
						$RevisaNumeroPasajeros = $mPasajeros->Revisar_numeros_pasajeros($sReservas[0]['localizador']);
						if($RevisaNumeroPasajeros == 'OK'){
							$Ntransacciones_pasajeros++;
						}else{
							$error_pasajeros = $RevisaNumeroPasajeros;

						}
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
			$sPasajeros = $oReservas->Cargar_pasajeros($sReservas[0]['localizador'],$filadesde_pasajeros,$parametros['buscar_apellido'],$parametros['buscar_nombre_pax'],$parametros['paginacion_pasajeros']);	
			$sComboSelectPasajeros = $oReservas->Cargar_combo_selector_pasajeros($sReservas[0]['localizador'],$parametros['buscar_apellido'],$parametros['buscar_nombre_pax'],$parametros['paginacion_pasajeros']);
			//Llamada a la clase general de combos
			//$comboSino = $oSino->Cargar_combo_SiNo();
			$comboPasajerosSexo = $oSino->Cargar_combo_Pasajeros_sexo();
			$comboPasajerosTipo = $oSino->Cargar_combo_Pasajeros_tipo();
			$comboPasajerosDocumentoTipo = $oSino->Cargar_combo_Pasajeros_documento_tipo();
			$comboPaises = $oSino->Cargar_combo_Paises();


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
							
							$mAereos = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$modificaAereos = $mAereos->Modificar_aereos($parametros['aereos_clave'.$num_fila], $parametros['aereos_reserva'.$num_fila], $parametros['aereos_tipo_cuerdo'.$num_fila], $parametros['aereos_cia'.$num_fila], $parametros['aereos_plazas'.$num_fila], $parametros['aereos_fecha_reserva'.$num_fila], $parametros['aereos_emitido'.$num_fila], $parametros['aereos_fecha_emision'.$num_fila]);
							if($modificaAereos == 'OK'){
								$Ntransacciones_aereos++;
							}else{
								$error_aereos = $modificaAereos;
							}

						}else{

							$mAereos = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$borraAereos = $mAereos->Borrar_aereos($parametros['aereos_clave'.$num_fila]);
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

						$iAereos = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
						$insertaAereos = $iAereos->Insertar_aereos($sReservas[0]['localizador'], $parametros['Nuevoaereos_orden'.$num_fila], $parametros['Nuevoaereos_reserva'.$num_fila], $parametros['Nuevoaereos_tipo_acuerdo'.$num_fila], $parametros['Nuevoaereos_cia'.$num_fila],$parametros['Nuevoaereos_plazas'.$num_fila],$parametros['Nuevoaereos_fecha_reserva'.$num_fila], $parametros['Nuevoaereos_emitido'.$num_fila], $parametros['Nuevoaereos_fecha_emision'.$num_fila]);
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
					if($parametros['selec_31nivel'.$num_fila] == 'S' || $parametros['borra_31nivel'.$num_fila] == 'S'){
						if($parametros['selec_31nivel'.$num_fila] == 'S'){
							
							$mAereos_pasajeros = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$modificaAereos_pasajeros = $mAereos_pasajeros->Modificar_aereos_pasajeros($sReservas[0]['localizador'], $parametros['aereos_pasajeros_orden'.$num_fila], $parametros['aereos_pasajeros_numero'.$num_fila], $parametros['aereos_pasajeros_billete'.$num_fila], $parametros['aereos_pasajeros_coste'.$num_fila], $parametros['aereos_pasajeros_tasas'.$num_fila], $parametros['aereos_pasajeros_pvp'.$num_fila]);
							if($modificaAereos_pasajeros == 'OK'){
								$Ntransacciones_aereos_pasajeros++;
							}else{
								$error_aereos_pasajeros = $modificaAereos_pasajeros;
							}

						}else{

							$mAereos_pasajeros = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$borraAereos_pasajeros = $mAereos_pasajeros->Borrar_aereos_pasajeros($sReservas[0]['localizador'], $parametros['aereos_pasajeros_orden'.$num_fila], $parametros['aereos_pasajeros_numero'.$num_fila]);
							if($borraAereos_pasajeros == 'OK'){
								$Ntransacciones_aereos_pasajeros++;
							}else{
								$error_aereos_pasajeros = $borraAereos_pasajeros;

							}
						}
					}
				}

				//AÑADIR REGISTROS DE PASAJEROS DE AEREOS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_AEREOS_PASAJEROS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_31nivel'.$num_fila] == 'S'){

						$iAereos_pasajeros = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
						$insertaAereos_pasajeros = $iAereos_pasajeros->Insertar_aereos_pasajeros($parametros['Nuevoaereos_clave'.$num_fila], $parametros['Nuevoaereos_pasajeros_numero'.$num_fila]);
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

				//Mostramos mensajes y posibles errores
				$mensaje1_aereos_pasajeros = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_aereos_pasajeros."</b></font></div>";
				if($error_aereos_pasajeros != ''){
					$$mensaje2_aereos_pasajeros = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_aereos_pasajeros."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();

			}




			//AEREOS
			//Llamada a la clase especifica de la pantalla
			$sAereos = $oReservas->Cargar_aereos($sReservas[0]['localizador']);	
			$sAereos_nuevos = $oReservas->Cargar_lineas_nuevas_aereos();	

			//Llamada a la clase general de combos
			$comboAcuerdosAereos = $oSino->Cargar_combo_Tipos_acuerdo_transportes();

			//PASAJEROS
			//Llamada a la clase especifica de la pantalla
			$sAereos_pasajeros = $oReservas->Cargar_aereos_pasajeros($sReservas[0]['localizador']);	
			$sAereos_pasajerosnuevos = $oReservas->Cargar_lineas_nuevas_aereos_pasajeros();

			//Llamada a la clase general de combos
			$comboReservasAereos = $oSino->Cargar_combo_Reservas_Aereos($sReservas[0]['localizador']);
			$comboReservasPasajeros = $oSino->Cargar_combo_Reservas_Pasajeros($sReservas[0]['localizador']);

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
							
							$mAlojamientos = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$modificaAlojamientos = $mAlojamientos->Modificar_alojamientos($parametros['clave_alojamiento'.$num_fila], $parametros['habitacion_alojamiento'.$num_fila],$parametros['caracteristica_alojamiento'.$num_fila],$parametros['uso_alojamiento'.$num_fila],$parametros['regimen_alojamiento'.$num_fila],$parametros['acuerdo_alojamiento'.$num_fila],$parametros['noches_alojamiento'.$num_fila],$parametros['cantidad_habitaciones_alojamiento'.$num_fila],$parametros['adultos_alojamiento'.$num_fila],$parametros['ninos1_alojamiento'.$num_fila],$parametros['bebes_alojamiento'.$num_fila],$parametros['novios_alojamiento'.$num_fila],$parametros['situacion_alojamiento'.$num_fila]);
							if($modificaAlojamientos == 'OK'){
								$Ntransacciones_alojamientos++;
							}else{
								$error_alojamientos = $modificaAlojamientos;
							}

						}else{

							$mAlojamientos = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$borraAlojamientos = $mAlojamientos->Borrar_alojamientos($parametros['clave_alojamiento'.$num_fila]);
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

					$iAlojamientos = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
					//Aqui decidimos el tipo de inserción qu3e debemos hacer segun la eleccion del usuario para añadir hotel
					if($parametros['busca_alojamiento_consulta'] == 'C'){
						$insertaAlojamientos = $iAlojamientos->Insertar_alojamientos_cupos($sReservas[0]['localizador'], $parametros['busca_alojamiento'], $parametros['nuevo_uso'], $parametros['nuevo_regimen'], $parametros['nuevo_numero_noches'], $parametros['nuevo_cantidad_habitaciones'], $sReservas[0]['adultos'], $sReservas[0]['ninos'], $sReservas[0]['bebes'], $sReservas[0]['novios']);
					}elseif($parametros['busca_alojamiento_consulta'] == 'O'){
						$insertaAlojamientos = $iAlojamientos->Insertar_alojamientos_onrequest($sReservas[0]['localizador'], $parametros['busca_alojamiento_fecha_desde'], $parametros['busca_alojamiento'], $parametros['nuevo_uso'], $parametros['nuevo_regimen'], $parametros['nuevo_numero_noches'], $parametros['nuevo_cantidad_habitaciones'], $sReservas[0]['adultos'], $sReservas[0]['ninos'], $sReservas[0]['bebes'], $sReservas[0]['novios']);
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


				//Mostramos mensajes y posibles errores
				$mensaje1_alojamientos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_alojamientos."</b></font></div>";
				if($error_alojamientos != ''){
					$mensaje2_alojamientos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_alojamientos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();

			}


			//Llamada a la clase especifica de la pantalla
			$sAlojamientos = $oReservas->Cargar_alojamientos($sReservas[0]['localizador']);	

			//Llamada a la clase general de combos
			$comboHabitaciones = $oSino->Cargar_combo_Habitaciones();
			$comboHabitaciones_car = $oSino->Cargar_combo_Habitaciones_car();
			$comboRegimen = $oSino->Cargar_combo_Regimen();
			$comboProvincias = $oSino->Cargar_combo_Provincias();
			$comboCiudades = $oSino->Cargar_combo_Ciudades();
			$comboCategorias = $oSino->Cargar_combo_Categorias();
			$comboSituacion = $oSino->Cargar_combo_Situacion();
			$comboAlojamientos = $oSino->Cargar_combo_Alojamientos();
			$comboTipo_consulta_alojamientos = $oSino->Cargar_Tipo_Consulta_Alojamientos();
			$comboSituacion_cupos = $oSino->Cargar_Combo_Situacion_Cupos();

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
							
							$mServicios = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$modificaServicios = $mServicios->Modificar_servicios($parametros['servicios_clave'.$num_fila], $parametros['servicios_orden'.$num_fila], $parametros['servicios_adultos'.$num_fila], $parametros['servicios_ninos'.$num_fila], $parametros['servicios_veces'.$num_fila], $parametros['servicios_situacion'.$num_fila]);
							if($modificaServicios == 'OK'){
								$Ntransacciones_servicios++;
							}else{
								$error_servicios = $modificaServicios;
							}

						}else{

							$mServicios = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
							$borraServicios = $mServicios->Borrar_servicios($parametros['servicios_clave'.$num_fila]);
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

					$iServicios = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);

					$insertaServicios = $iServicios->Insertar_servicios($sReservas[0]['localizador'], $parametros['busca_servicio_fecha'], $parametros['busca_servicio'], $parametros['nuevo_pax'], $sReservas[0]['adultos'], $sReservas[0]['ninos'], $sReservas[0]['bebes'], $sReservas[0]['novios']);

					if($insertaServicios == 'OK'){
						$Ntransacciones_servicios++;
					}else{
						$error_servicios = $insertaServicios;
					}
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
			$sServicios = $oReservas->Cargar_servicios($sReservas[0]['localizador']);	

			//Llamada a la clase general de combos
			//$comboCiudades = $oSino->Cargar_combo_Ciudades();
			$comboSituacion = $oSino->Cargar_combo_Situacion();
			$comboProveedores = $oSino->Cargar_combo_Proveedores();
			$comboTipos_servicios = $oSino->Cargar_combo_Tipos_Servicio();


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
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('formulario', 'RESERVAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectReservas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('reservas', $sReservas);

			//Indicamos si hay que visualizar o no la seccion DE MODIFICAR AGENCIA
			$smarty->assign('seccion_buscar_agencia_display', $parametros['seccion_buscar_agencia_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboReservas', $comboReservas);
			//$smarty->assign('comboAgencias', $comboAgencias);

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
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboCiudades', $comboCiudades);
			$smarty->assign('comboCategorias', $comboCategorias);
			$smarty->assign('comboSituacion', $comboSituacion);
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboTipo_consulta_alojamientos', $comboTipo_consulta_alojamientos);
			$smarty->assign('comboSituacion_cupos', $comboSituacion_cupos);


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

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_servicios', $mensaje1_servicios);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_servicios', $mensaje2_servicios);


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
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
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
			//$parametros['buscar_agencia_minorista'] = "";
			//$parametros['buscar_agencia_oficina'] = ""; 
			//$parametros['buscar_agencia_direccion'] = ""; 
			//$parametros['buscar_agencia_telefono'] = "";



		}

			//Llamada a la clase especifica de la pantalla
			$oReservas = new clsReservas($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);
			$sReservas = $oReservas->Cargar($recuperareferencia,$recuperafolleto,$recuperacuadro,$recuperaciudad_salida,$recuperapaquete,$recuperafecha_salida,$recuperanombre_titular,$recuperaadultos,$recuperaninos,$recuperabebes,$recuperanovios,$recuperajubilados,$recuperaobservaciones,$recuperaagente,$recuperareferencia_agencia,$recuperaenvio,$recuperadivisa_actual,$recuperamodificacion_motivo,$recuperamodificacion_responsable,$recuperafree,$recuperamodificar,$recuperamodificar_comisionable,$recuperamodificar_detalle_comisionable,$recuperamodificar_no_comisionable,$recuperamodificar_detalle_no_comisionable,$recuperamodificar_comision,$recuperamodificar_usuario,$recuperavisa);
			$sComboSelectReservas = $oReservas->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboDivisas = $oSino->Cargar_combo_Divisas();
			$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboReservas = $oSino->Cargar_combo_Reservas($parametros['buscar_localizador'], $parametros['buscar_referencia'], $parametros['buscar_referencia_agencia'], $parametros['buscar_folleto'], $parametros['buscar_cuadro'], $parametros['buscar_fecha_salida'], $parametros['buscar_fecha_reserva'], $parametros['buscar_minorista'], $parametros['buscar_oficina'], $parametros['buscar_telefono_oficina'], $parametros['buscar_nombre']);

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
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('formulario', 'RESERVAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectReservas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('reservas', $sReservas);

			//Indicamos si hay que visualizar o no la seccion DE AGENCIAS
			$smarty->assign('seccion_buscar_agencia_display', 'none');

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboFolletos', $comboFolletos);
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
		$sPasajeros = $oReservas->Cargar_pasajeros($sReservas[0]['localizador'],$filadesde_pasajeros,$parametros['buscar_apellido'],$parametros['buscar_nombre_pax'],$parametros['paginacion_pasajeros']);	
		$sComboSelectPasajeros = $oReservas->Cargar_combo_selector_pasajeros($sReservas[0]['localizador'],$parametros['buscar_apellido'],$parametros['buscar_nombre_pax'],$parametros['paginacion_pasajeros']);
		//Llamada a la clase general de combos
		$comboSino = $oSino->Cargar_combo_SiNo();
		$comboPasajerosSexo = $oSino->Cargar_combo_Pasajeros_sexo();
		$comboPasajerosTipo = $oSino->Cargar_combo_Pasajeros_tipo();
		$comboPasajerosDocumentoTipo = $oSino->Cargar_combo_Pasajeros_documento_tipo();
		$comboPaises = $oSino->Cargar_combo_Paises();

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
		$sAereos = $oReservas->Cargar_aereos($sReservas[0]['localizador']);	
		$sAereos_nuevos = $oReservas->Cargar_lineas_nuevas_aereos();
		//Llamada a la clase general de combos
		$comboAcuerdosAereos = $oSino->Cargar_combo_Tipos_acuerdo_transportes();

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
			$sAereos_pasajeros = $oReservas->Cargar_aereos_pasajeros($sReservas[0]['localizador']);	
			$sAereos_pasajerosnuevos = $oReservas->Cargar_lineas_nuevas_aereos_pasajeros();
			//Llamada a la clase general de combos
			$comboReservasAereos = $oSino->Cargar_combo_Reservas_Aereos($sReservas[0]['localizador']);
			$comboReservasPasajeros = $oSino->Cargar_combo_Reservas_Pasajeros($sReservas[0]['localizador']);

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

		//-----------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS ALOJAMIENTOS
			//Llamada a la clase especifica de la pantalla
		$sAlojamientos = $oReservas->Cargar_alojamientos($sReservas[0]['localizador']);	

		//Llamada a la clase general de combos
		$comboHabitaciones = $oSino->Cargar_combo_Habitaciones();
		$comboHabitaciones_car = $oSino->Cargar_combo_Habitaciones_car();
		$comboRegimen = $oSino->Cargar_combo_Regimen();
		$comboProvincias = $oSino->Cargar_combo_Provincias();
		$comboCiudades = $oSino->Cargar_combo_Ciudades();
		$comboCategorias = $oSino->Cargar_combo_Categorias();
		$comboSituacion = $oSino->Cargar_combo_Situacion();
		$comboAlojamientos = $oSino->Cargar_combo_Alojamientos();
		$comboTipo_consulta_alojamientos = $oSino->Cargar_Tipo_Consulta_Alojamientos();
		$comboSituacion_cupos = $oSino->Cargar_Combo_Situacion_Cupos();

		//Llamada a la clase especifica de la pantalla
		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('alojamientos', $sAlojamientos);

		//Indicamos si hay que visualizar o no la seccion DE AÑADIR ALOJAMIENTOS
		$smarty->assign('seccion_anadir_alojamiento_display', 'none');

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboHabitaciones', $comboHabitaciones);
		$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car);
		$smarty->assign('comboRegimen', $comboRegimen);
		$smarty->assign('comboProvincias', $comboProvincias);
		$smarty->assign('comboCiudades', $comboCiudades);
		$smarty->assign('comboCategorias', $comboCategorias);
		$smarty->assign('comboSituacion', $comboSituacion);
		$smarty->assign('comboAlojamientos', $comboAlojamientos);
		$smarty->assign('comboTipo_consulta_alojamientos', $comboTipo_consulta_alojamientos);
		$smarty->assign('comboSituacion_cupos', $comboSituacion_cupos);

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_alojamientos', $mensaje1_servicios);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_alojamientos', $mensaje2_servicios);


		//-----------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS SERVICIOS

		//Llamada a la clase especifica de la pantalla
		$sServicios = $oReservas->Cargar_servicios($sReservas[0]['localizador']);	

		//Llamada a la clase general de combos
		//$comboCiudades = $oSino->Cargar_combo_Ciudades();
		$comboSituacion = $oSino->Cargar_combo_Situacion();
		$comboProveedores = $oSino->Cargar_combo_Proveedores();
		$comboTipos_servicios = $oSino->Cargar_combo_Tipos_Servicio();

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('servicios', $sServicios);

		//Indicamos si hay que visualizar o no la seccion DE AÑADIR ALOJAMIENTOS
		$smarty->assign('seccion_anadir_servicio_display', 'none');

		//Cargar combos de las lineas de la tabla
		//$smarty->assign('comboCiudades', $comboCiudades);
		$smarty->assign('comboSituacion', $comboSituacion);
		$smarty->assign('comboProveedores', $comboProveedores);
		$smarty->assign('comboTipos_servicios', $comboTipos_servicios);


		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_servicios', $mensaje1_servicios);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_servicios', $mensaje2_servicios);



		//Visualizar plantilla
		$smarty->assign('posicion', 'buscar_localizador');

		$smarty->display('plantillas/Reservas.html');

	}
	
	$conexion->close();


?>

