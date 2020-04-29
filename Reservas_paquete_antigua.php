<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/Reservas.cls.php';

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

	if(count($_POST) != 0){

		if($parametros['ir_a'] != 'S'){

			//---------------------------------------
			//---SECCION PRINCIPAL DEL CODIGO--------
			//---------------------------------------

			if($parametros['reservar'] == 'S'){
				//COMPROAR QUE SE HAN TECLEADO TODOS LOS DATOS PARA PODER REALIZAR LA RESERVA
				//if($parametros['folleto'] != '' and $parametros['cuadro'] != ''){

						//Tratamos las habitaciones para convertirlas en los parametros de la funcion de reserva 

						//Hay que adaptar todos estos parametros antiguos

						/*$parametros['adultos']."', '".$parametros['ninos']."', '".$parametros['bebes']."', '".$parametros['novios']."', '".$parametros['jubilados']."', '".$parametros['habitacion1']."', '".$parametros['caracteristica1']."', '".$parametros['cantidad1']."', '".$parametros['habitacion2']."', '".$parametros['caracteristica2']."', '".$parametros['cantidad2']."', '".$parametros['habitacion3']."', '".$parametros['caracteristica3']."', '".$parametros['cantidad3']."', '".$parametros['habitacion4']."', '".$parametros['caracteristica4']."', '".$parametros['cantidad4']*/

						//a los nuevos parametros de la pantalla que son esto;*/

						/*$parametros['cantidad_habitaciones'],	
						$parametros['habitacion1_caracteristica'],	$parametros['habitacion1_adultos'],	$parametros['habitacion1_ninos'],	$parametros['habitacion1_bebes'],	$parametros['habitacion1_novios'],	$parametros['habitacion1_jubilados'],
						$parametros['habitacion2_caracteristica'],	$parametros['habitacion2_adultos'],	$parametros['habitacion2_ninos'],	$parametros['habitacion2_bebes'],	$parametros['habitacion2_novios'],	$parametros['habitacion2_jubilados'],
						$parametros['habitacion3_caracteristica'],	$parametros['habitacion3_adultos'],	$parametros['habitacion3_ninos'],	$parametros['habitacion3_bebes'],	$parametros['habitacion3_novios'],	$parametros['habitacion3_jubilados'],
						$parametros['habitacion4_caracteristica'],	$parametros['habitacion4_adultos'],	$parametros['habitacion4_ninos'],	$parametros['habitacion4_bebes'],	$parametros['habitacion4_novios'],	$parametros['habitacion4_jubilados']*/

						//Hay que procurar cambiar el procedimiento de la base de datos de realizacion de reserva para adaptarlo a esto.


						//LLAMAR A FUNCION DE RESERVA
						//Nota: Ponemos una '@' delante de la instruccion para evitar que el navegador muestre los warning de mysql.
						$reserva =@$conexion->query("SELECT `RESERVAS_REALIZAR_RESERVA`('".$usuario."', '".$parametros['folleto']."', '".$parametros['cuadro']."', '".$parametros['ciudad']."', '".$parametros['opcion']."', '".date("Y-m-d",strtotime($parametros['fecha']))."', '".$parametros['paquete']."', '".$parametros['regimen']."', '".$parametros['adultos']."', '".$parametros['ninos']."', '".$parametros['bebes']."', '".$parametros['novios']."', '".$parametros['jubilados']."', '".$parametros['habitacion1']."', '".$parametros['caracteristica1']."', '".$parametros['cantidad1']."', '".$parametros['habitacion2']."', '".$parametros['caracteristica2']."', '".$parametros['cantidad2']."', '".$parametros['habitacion3']."', '".$parametros['caracteristica3']."', '".$parametros['cantidad3']."', '".$parametros['habitacion4']."', '".$parametros['caracteristica4']."', '".$parametros['cantidad4']."', 

						'".$parametros['cantidad_habitaciones']."',
						'".$parametros['habitacion1_caracteristica']."', '".$parametros['habitacion1_adultos']."', '".$parametros['habitacion1_ninos']."', '".$parametros['habitacion1_bebes']."', '".$parametros['habitacion1_novios']."', '".$parametros['habitacion1_jubilados']."', 
						'".$parametros['habitacion2_caracteristica']."', '".$parametros['habitacion2_adultos']."', '".$parametros['habitacion2_ninos']."', '".$parametros['habitacion2_bebes']."', '".$parametros['habitacion2_novios']."', '".$parametros['habitacion2_jubilados']."', 
						'".$parametros['habitacion3_caracteristica']."', '".$parametros['habitacion3_adultos']."', '".$parametros['habitacion3_ninos']."', '".$parametros['habitacion3_bebes']."', '".$parametros['habitacion3_novios']."', '".$parametros['habitacion3_jubilados']."', 			
						'".$parametros['habitacion4_caracteristica']."', '".$parametros['habitacion4_adultos']."', '".$parametros['habitacion4_ninos']."', '".$parametros['habitacion4_bebes']."', '".$parametros['habitacion4_novios']."', '".$parametros['habitacion4_jubilados']."',
						
						'".$parametros['clave_agencia']."', '".$parametros['agente']."', '".$parametros['referencia_agencia']."', '".$parametros['observaciones']."') LOC");

						if ($reserva == FALSE){
							$error = 'No se ha podido realizar la reserva. '.$conexion->error;
						}else{
							$resultado_reserva = $reserva->fetch_assoc();
							

							$Mensaje = 'Reserva realizada: '.$resultado_reserva['LOC']; //Esto se sustituira por un enlace a la pantalla de mantenimiento con el parametro localizador.
						}




				/*}else{
						$error = 'Se deben rellenar todos los campos de la pantalla.';
				}*/


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
			}

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboCiudades = $oSino->Cargar_Combo_Ciudades_Origen();
			$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboHabitaciones = $oSino->Cargar_combo_Habitaciones();
			$combohabitaciones_car = $oSino->Cargar_combo_Habitaciones_car();
			$comboRegimen = $oSino->Cargar_combo_Regimen();
			$comboCantidad_Habitaciones = $oSino->Cargar_Combo_Cantidad_Habitaciones();
			$comboHabitaciones_Adultos = $oSino->Cargar_Combo_Habitaciones_Adultos();
			$comboHabitaciones_Ninos = $oSino->Cargar_Combo_Habitaciones_Ninos();
			$comboHabitaciones_Bebes = $oSino->Cargar_Combo_Habitaciones_Bebes();
			$comboHabitaciones_Novios = $oSino->Cargar_Combo_Habitaciones_Novios();
			$comboHabitaciones_Jubilados = $oSino->Cargar_Combo_Habitaciones_Jubilados();


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
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboHabitaciones', $comboHabitaciones);
			$smarty->assign('combohabitaciones_car', $combohabitaciones_car);
			$smarty->assign('comboRegimen', $comboRegimen);
			$smarty->assign('comboCantidad_Habitaciones', $comboCantidad_Habitaciones);
			$smarty->assign('comboHabitaciones_Adultos', $comboHabitaciones_Adultos);
			$smarty->assign('comboHabitaciones_Ninos', $comboHabitaciones_Ninos);
			$smarty->assign('comboHabitaciones_Bebes', $comboHabitaciones_Bebes);
			$smarty->assign('comboHabitaciones_Novios', $comboHabitaciones_Novios);
			$smarty->assign('comboHabitaciones_Jubilados', $comboHabitaciones_Jubilados);


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos tecleados por el usuario para evitar que los tenga que repetir si hay un error o aviso.
			$smarty->assign('recuperafolleto', $parametros['folleto']);
			$smarty->assign('recuperacuadro', $parametros['cuadro']);
			$smarty->assign('recuperaciudad', $parametros['ciudad']);
			$smarty->assign('recuperaopcion', $parametros['opcion']);
			$smarty->assign('recuperafecha', $parametros['fecha']);
			$smarty->assign('recuperapaquete', $parametros['paquete']);
			$smarty->assign('recuperaregimen', $parametros['regimen']);		


			//campos de distribucion antiguos
			$smarty->assign('recuperaadultos', $parametros['adultos']);
			$smarty->assign('recuperaninos', $parametros['ninos']);
			$smarty->assign('recuperabebes', $parametros['bebes']);
			$smarty->assign('recuperanovios', $parametros['novios']);
			$smarty->assign('recuperajubilados', $parametros['jubilados']);
			$smarty->assign('recuperahabitacion1', $parametros['habitacion1']);
			$smarty->assign('recuperacaracteristica1', $parametros['caracteristica1']);
			$smarty->assign('recuperacantidad1', $parametros['cantidad1']);
			$smarty->assign('recuperahabitacion2', $parametros['habitacion2']);
			$smarty->assign('recuperacaracteristica2', $parametros['caracteristica2']);
			$smarty->assign('recuperacantidad2', $parametros['cantidad2']);
			$smarty->assign('recuperahabitacion3', $parametros['habitacion3']);
			$smarty->assign('recuperacaracteristica3', $parametros['caracteristica3']);
			$smarty->assign('recuperacantidad3', $parametros['cantidad3']);

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
			$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboHabitaciones = $oSino->Cargar_combo_Habitaciones();
			$combohabitaciones_car = $oSino->Cargar_combo_Habitaciones_car();
			$comboRegimen = $oSino->Cargar_combo_Regimen();
			$comboCantidad_Habitaciones = $oSino->Cargar_Combo_Cantidad_Habitaciones();
			$comboHabitaciones_Adultos = $oSino->Cargar_Combo_Habitaciones_Adultos();
			$comboHabitaciones_Ninos = $oSino->Cargar_Combo_Habitaciones_Ninos();
			$comboHabitaciones_Bebes = $oSino->Cargar_Combo_Habitaciones_Bebes();
			$comboHabitaciones_Novios = $oSino->Cargar_Combo_Habitaciones_Novios();
			$comboHabitaciones_Jubilados = $oSino->Cargar_Combo_Habitaciones_Jubilados();


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
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboHabitaciones', $comboHabitaciones);
			$smarty->assign('combohabitaciones_car', $combohabitaciones_car);
			$smarty->assign('comboRegimen', $comboRegimen);
			$smarty->assign('comboCantidad_Habitaciones', $comboCantidad_Habitaciones);
			$smarty->assign('comboHabitaciones_Adultos', $comboHabitaciones_Adultos);
			$smarty->assign('comboHabitaciones_Ninos', $comboHabitaciones_Ninos);
			$smarty->assign('comboHabitaciones_Bebes', $comboHabitaciones_Bebes);
			$smarty->assign('comboHabitaciones_Novios', $comboHabitaciones_Novios);
			$smarty->assign('comboHabitaciones_Jubilados', $comboHabitaciones_Jubilados);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboFolletos', $comboFolletos);

			//Cargar campos tecleados por el usuario para evitar que los tenga que repetir si hay un error o aviso.
			$smarty->assign('recuperafolleto', '');
			$smarty->assign('recuperacuadro', '');
			$smarty->assign('recuperaciudad', '');
			$smarty->assign('recuperaopcion', '');
			$smarty->assign('recuperafecha', '');
			$smarty->assign('recuperapaquete', '');
			$smarty->assign('recuperaregimen', '');	
			

			$smarty->assign('recuperaadultos', '');
			$smarty->assign('recuperaninos', '');
			$smarty->assign('recuperabebes', '');
			$smarty->assign('recuperanovios', '');
			$smarty->assign('recuperajubilados', '');
			$smarty->assign('recuperahabitacion1', '');
			$smarty->assign('recuperacaracteristica1', '');
			$smarty->assign('recuperacantidad1', '');
			$smarty->assign('recuperahabitacion2', '');
			$smarty->assign('recuperacaracteristica2', '');
			$smarty->assign('recuperacantidad2', '');
			$smarty->assign('recuperahabitacion3', '');
			$smarty->assign('recuperacaracteristica3', '');
			$smarty->assign('recuperacantidad3', '');

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

