<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/alojamientos_servicios.cls.php';


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
			$recuperaid_alojamiento = '';
			 $recuperaacceso_inernet = '';
			  $recuperaaire_acondicionado_comunes = '';
			  $recuperaaparcamiento = '';
			  $recuperaarea_juegos = '';
			  $recuperaascensores = '';
			  $recuperabares = '';
			  $recuperacambio_moneda = '';
			  $recuperacobertura_moviles = '';
			  $recuperaminiclub = '';
			  $recuperapiscinas_numero = '';
			  $recuperapeluqueria = '';
			  $recuperapiscina_aire_libre = '';
			  $recuperapiscina_agua_dulce = '';
			  $recuperapiscina_ninos = '';
			  $recuperarestaurantes = '';
			  $recuperarestaurante_climatizado = '';
			  $recuperasala_conferencias = '';
			  $recuperaservicio_facturacion_24 = '';
			  $recuperaservicio_recepcion_24 = '';
			  $recuperasombrillas = '';
			  $recuperaterraza_solarium = '';
			  $recuperatiendas = '';
			  $recuperatronas = '';
			  $recuperatumbonas = '';
			  $recuperavetibulo_recepcion = '';
			  $recuperaaireacondicionado_central = '';
			  $recuperabalcon = '';
			  $recuperabano = '';
			  $recuperacaja_seguridad = '';
			  $recuperaminibar = '';
			  $recuperasecador = '';
			  $recuperatelefono_linea_directa = '';
			  $recuperatv_satelite_cable = '';

			  $recuperaninos_gratis = '';
			  $recuperaceliacos = '';
			  $recuperadiscapacitados = '';
			  $recuperagolf = '';
			  $recuperagimnasio = '';
			  $recuperasolo_adultos = '';
			  $recuperaspa = '';
			  $recuperatodo_incluido = '';
			  $recuperavista_mar = '';
			  $recuperawifi = '';

			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			$insertaAlojamientos_servicios = '';

	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla de Alojamientos_servicios
		if($parametros['buscar_alojamiento'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_alojamiento'] = $parametrosg['id_alojamiento'];
			}
		}


		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'V'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LOS ALOJAMIENTOS_SERVICIOS----------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonAlojamientos_servicios = new clsAlojamientos_servicios($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento']);
				$botonselec = $botonAlojamientos_servicios->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_SERVICIOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S'){

						if($parametros['id_alojamiento'.$num_fila] == $parametros['buscar_alojamiento']){

							$mAlojamientos_servicios = new clsAlojamientos_servicios($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento']);

							$modificaAlojamientos_servicios = $mAlojamientos_servicios->Modificar($parametros['id_alojamiento'.$num_fila],
																								  $parametros['acceso_internet'.$num_fila],
																								  $parametros['aire_acondicionado_comunes'.$num_fila],
																								  $parametros['aparcamiento'.$num_fila],
																								  $parametros['area_juegos'.$num_fila],
																								  $parametros['ascensores'.$num_fila],
																								  $parametros['bares'.$num_fila],
																								  $parametros['cambio_moneda'.$num_fila],
																								  $parametros['cobertura_moviles'.$num_fila],
																								  $parametros['miniclub'.$num_fila],
																								  $parametros['piscinas_numero'.$num_fila],
																								  $parametros['peluqueria'.$num_fila],
																								  $parametros['piscina_aire_libre'.$num_fila],
																								  $parametros['piscina_agua_dulce'.$num_fila],
																								  $parametros['piscina_ninos'.$num_fila],
																								  $parametros['restaurantes'.$num_fila],
																								  $parametros['restaurante_climatizado'.$num_fila],
																								  $parametros['sala_conferencias'.$num_fila],
																								  $parametros['servicio_facturacion_24'.$num_fila],
																								  $parametros['servicio_recepcion_24'.$num_fila],
																								  $parametros['sombrillas'.$num_fila],
																								  $parametros['terraza_solarium'.$num_fila],
																								  $parametros['tiendas'.$num_fila],
																								  $parametros['tronas'.$num_fila],
																								  $parametros['tumbonas'.$num_fila],
																								  $parametros['vestibulo_recepcion'.$num_fila],
																								  $parametros['aire_acondicionado_central'.$num_fila],
																								  $parametros['balcon'.$num_fila],
																								  $parametros['bano'.$num_fila],
																								  $parametros['caja_seguridad'.$num_fila],
																								  $parametros['minibar'.$num_fila],
																								  $parametros['secador'.$num_fila],
																								  $parametros['telefono_linea_directa'.$num_fila],
																								  $parametros['tv_satelite_cable'.$num_fila],

																								  $parametros['ninos_gratis'.$num_fila],
																								  $parametros['celiacos'.$num_fila],
																								  $parametros['discapacitados'.$num_fila],
																								  $parametros['golf'.$num_fila],
																								  $parametros['gimnasio'.$num_fila],
																								  $parametros['solo_adultos'.$num_fila],
																								  $parametros['spa'.$num_fila],
																								  $parametros['todo_incluido'.$num_fila],
																								  $parametros['vista_mar'.$num_fila],
																								  $parametros['wifi'.$num_fila]);



							if($modificaAlojamientos_servicios == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaAlojamientos_servicios;
							}
						}else{

									$iAlojamientos_servicios = new clsAlojamientos_servicios($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento']);
									$insertaAlojamientos_servicios = $iAlojamientos_servicios->Insertar($parametros['buscar_alojamiento'],
																								  $parametros['acceso_internet'.$num_fila],
																								  $parametros['aire_acondicionado_comunes'.$num_fila],
																								  $parametros['aparcamiento'.$num_fila],
																								  $parametros['area_juegos'.$num_fila],
																								  $parametros['ascensores'.$num_fila],
																								  $parametros['bares'.$num_fila],
																								  $parametros['cambio_moneda'.$num_fila],
																								  $parametros['cobertura_moviles'.$num_fila],
																								  $parametros['miniclub'.$num_fila],
																								  $parametros['piscinas_numero'.$num_fila],
																								  $parametros['peluqueria'.$num_fila],
																								  $parametros['piscina_aire_libre'.$num_fila],
																								  $parametros['piscina_agua_dulce'.$num_fila],
																								  $parametros['piscina_ninos'.$num_fila],
																								  $parametros['restaurantes'.$num_fila],
																								  $parametros['restaurante_climatizado'.$num_fila],
																								  $parametros['sala_conferencias'.$num_fila],
																								  $parametros['servicio_facturacion_24'.$num_fila],
																								  $parametros['servicio_recepcion_24'.$num_fila],
																								  $parametros['sombrillas'.$num_fila],
																								  $parametros['terraza_solarium'.$num_fila],
																								  $parametros['tiendas'.$num_fila],
																								  $parametros['tronas'.$num_fila],
																								  $parametros['tumbonas'.$num_fila],
																								  $parametros['vestibulo_recepcion'.$num_fila],
																								  $parametros['aire_acondicionado_central'.$num_fila],
																								  $parametros['balcon'.$num_fila],
																								  $parametros['bano'.$num_fila],
																								  $parametros['caja_seguridad'.$num_fila],
																								  $parametros['minibar'.$num_fila],
																								  $parametros['secador'.$num_fila],
																								  $parametros['telefono_linea_directa'.$num_fila],
																								  $parametros['tv_satelite_cable'.$num_fila],
																								  $parametros['ninos_gratis'.$num_fila],
																								  $parametros['celiacos'.$num_fila],
																								  $parametros['discapacitados'.$num_fila],
																								  $parametros['golf'.$num_fila],
																								  $parametros['gimnasio'.$num_fila],
																								  $parametros['solo_adultos'.$num_fila],
																								  $parametros['spa'.$num_fila],
																								  $parametros['todo_incluido'.$num_fila],
																								  $parametros['vista_mar'.$num_fila],
																								  $parametros['wifi'.$num_fila]);

									if($insertaAlojamientos_servicios == 'OK'){
										$Ntransacciones++;
										$nuevoregistro = 'N';
										//$parametros['buscar_alojamiento'] = $parametros['id_alojamiento0'];
									}else{
										$error = $insertaAlojamientos_servicios;
										//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
										 $recuperaid_alojamiento = $parametros['recuperaid_alojamiento'.$num_fila];
										 $recuperaacceso_inernet = $parametros['recuperaacceso_inernet'.$num_fila];
										 $recuperaaire_acondicionado_comunes = $parametros['recuperaaire_acondicionado_comunes'.$num_fila];
										 $recuperaaparcamiento = $parametros['recuperaaparcamiento'.$num_fila];
										 $recuperaarea_juegos = $parametros['recuperaarea_juegos'.$num_fila];
										 $recuperaascensores = $parametros['recuperaascensores'.$num_fila];
										 $recuperabares = $parametros['recuperabares'.$num_fila];
										 $recuperacambio_moneda = $parametros['recuperacambio_moneda'.$num_fila];
										 $recuperacobertura_moviles = $parametros['recuperacobertura_moviles'.$num_fila];
										 $recuperaminiclub = $parametros['recuperaminiclub'.$num_fila];
										 $recuperapiscinas_numero = $parametros['recuperapiscinas_numero'.$num_fila];
										 $recuperapeluqueria = $parametros['recuperapeluqueria'.$num_fila];
										 $recuperapiscina_aire_libre = $parametros['recuperapiscina_aire_libre'.$num_fila];
										 $recuperapiscina_agua_dulce = $parametros['recuperapiscina_agua_dulce'.$num_fila];
										 $recuperapiscina_ninos = $parametros['recuperapiscina_ninos'.$num_fila];
										 $recuperarestaurantes = $parametros['recuperarestaurantes'.$num_fila];
										 $recuperarestaurante_climatizado = $parametros['recuperarestaurante_climatizado'.$num_fila];
										 $recuperasala_conferencias = $parametros['recuperasala_conferencias'.$num_fila];
										 $recuperaservicio_facturacion_24 = $parametros['recuperaservicio_facturacion_24'.$num_fila];
										 $recuperaservicio_recepcion_24 = $parametros['recuperaservicio_recepcion_24'.$num_fila];
										 $recuperasombrillas = $parametros['recuperasombrillas'.$num_fila];
										 $recuperaterraza_solarium = $parametros['recuperaterraza_solarium'.$num_fila];
										 $recuperatiendas = $parametros['recuperatiendas'.$num_fila];
										 $recuperatronas = $parametros['recuperatronas'.$num_fila];
										 $recuperatumbonas = $parametros['recuperatumbonas'.$num_fila];
										 $recuperavetibulo_recepcion = $parametros['recuperavetibulo_recepcion'.$num_fila];
										 $recuperaaireacondicionado_central = $parametros['recuperaaireacondicionado_central'.$num_fila];
										 $recuperabalcon = $parametros['recuperabalcon'.$num_fila];
										 $recuperabano = $parametros['recuperabano'.$num_fila];
										 $recuperacaja_seguridad = $parametros['recuperacaja_seguridad'.$num_fila];
										 $recuperaminibar = $parametros['recuperaminibar'.$num_fila];
										 $recuperasecador = $parametros['recuperasecador'.$num_fila];
										 $recuperatelefono_linea_directa = $parametros['recuperatelefono_linea_directa'.$num_fila];
										 $recuperatv_satelite_cable = $parametros['recuperatv_satelite_cable'.$num_fila];

										 $recuperaninos_gratis = $parametros['recuperaninos_gratis'.$num_fila];
										 $recuperaceliacos = $parametros['recuperaceliacos'.$num_fila];
										 $recuperadiscapacitados = $parametros['recuperadiscapacitados'.$num_fila];
										 $recuperagolf = $parametros['recuperagolf'.$num_fila];
										 $recuperagimnasio = $parametros['recuperagimnasio'.$num_fila];
										 $recuperasolo_adultos = $parametros['recuperasolo_adultos'.$num_fila];
										 $recuperaspa = $parametros['recuperaspa'.$num_fila];
										 $recuperatodo_incluido = $parametros['recuperatodo_incluido'.$num_fila];
										 $recuperavista_mar = $parametros['recuperavista_mar'.$num_fila];
										 $recuperawifi = $parametros['recuperawifi'.$num_fila];
									}


						}
					}
				}
				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();
			}


			//Llamada a la clase especifica de la pantalla
			$oAlojamientos_servicios = new clsAlojamientos_servicios($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento']);
			$sAlojamientos_servicios = $oAlojamientos_servicios->Cargar($recuperaid_alojamiento, $recuperaacceso_inernet,$recuperaaire_acondicionado_comunes,$recuperaaparcamiento,$recuperaarea_juegos,$recuperaascensores,
			  $recuperabares,$recuperacambio_moneda,$recuperacobertura_moviles,$recuperaminiclub,$recuperapiscinas_numero,$recuperapeluqueria,$recuperapiscina_aire_libre,
			  $recuperapiscina_agua_dulce,$recuperapiscina_ninos,$recuperarestaurantes, $recuperarestaurante_climatizado,$recuperasala_conferencias,$recuperaservicio_facturacion_24,
			  $recuperaservicio_recepcion_24,$recuperasombrillas,$recuperaterraza_solarium,$recuperatiendas,$recuperatronas,$recuperatumbonas,$recuperavetibulo_recepcion,
			  $recuperaaireacondicionado_central,$recuperabalcon,$recuperabano,$recuperacaja_seguridad,$recuperaminibar,$recuperasecador,$recuperatelefono_linea_directa,
			  $recuperatv_satelite_cable,$recuperaninos_gratis, $recuperaceliacos, $recuperadiscapacitados,$recuperagolf, $recuperagimnasio, $recuperasolo_adultos,
			  $recuperaspa, $recuperatodo_incluido, $recuperavista_mar, $recuperawifi);
			$sComboSelectAlojamientos_servicios = $oAlojamientos_servicios->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo_alojamientos_servicios();
			$comboAlojamientos = $oSino->Cargar_combo_Alojamientos();

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS ALOJAMIENTOS_SERVICIOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
			$smarty->assign('formulario', '» SERVICIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAlojamientos_servicios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos_servicios', $sAlojamientos_servicios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboSino', $comboSino);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_alojamiento', $parametros['buscar_alojamiento']);


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Alojamientos_servicios.html');


		}elseif($parametros['ir_a'] == 'V'){

			header("Location: Alojamientos.php?id=".$parametros['buscar_alojamiento']);

		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS ALOJAMIENTOS_SERVICIOS
		$filadesde = 1;
		if(count($_GET) != 0 and @$parametrosg['id_alojamiento'] != null){
			$parametros['buscar_alojamiento'] = $parametrosg['id_alojamiento'];
		}else{
			$parametros['buscar_alojamiento'] = "";
		}


			//Llamada a la clase especifica de la pantalla
			$oAlojamientos_servicios = new clsAlojamientos_servicios($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento']);
			$sAlojamientos_servicios = $oAlojamientos_servicios->Cargar($recuperaid_alojamiento, $recuperaacceso_inernet,$recuperaaire_acondicionado_comunes,$recuperaaparcamiento,$recuperaarea_juegos,$recuperaascensores,
			$recuperabares,$recuperacambio_moneda,$recuperacobertura_moviles,$recuperaminiclub,$recuperapiscinas_numero,$recuperapeluqueria,$recuperapiscina_aire_libre,
			$recuperapiscina_agua_dulce,$recuperapiscina_ninos,$recuperarestaurantes, $recuperarestaurante_climatizado,$recuperasala_conferencias,$recuperaservicio_facturacion_24,
			$recuperaservicio_recepcion_24,$recuperasombrillas,$recuperaterraza_solarium,$recuperatiendas,$recuperatronas,$recuperatumbonas,$recuperavetibulo_recepcion,
			$recuperaaireacondicionado_central,$recuperabalcon,$recuperabano,$recuperacaja_seguridad,$recuperaminibar,$recuperasecador,$recuperatelefono_linea_directa,
			$recuperatv_satelite_cable,$recuperaninos_gratis, $recuperaceliacos, $recuperadiscapacitados,$recuperagolf, $recuperagimnasio, $recuperasolo_adultos,
			$recuperaspa, $recuperatodo_incluido, $recuperavista_mar, $recuperawifi);
			$sComboSelectAlojamientos_servicios = $oAlojamientos_servicios->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo_alojamientos_servicios();
			$comboAlojamientos = $oSino->Cargar_combo_Alojamientos();

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS ALOJAMIENTOS_SERVICIOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
			$smarty->assign('formulario', '» SERVICIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAlojamientos_servicios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos_servicios', $sAlojamientos_servicios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboSino', $comboSino);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_alojamiento', $parametros['buscar_alojamiento']);

		//Visualizar plantilla
		$smarty->display('plantillas/Alojamientos_servicios.html');
	}

	$conexion->close();


?>

