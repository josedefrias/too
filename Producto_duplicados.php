<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';

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
			$recuperafolleto_original = '';
			$recuperacuadro_original = '';
			$recuperafolleto_nuevo = '';
			$recuperacuadro_nuevo = '';
			$recuperanumero_noches = '';
			$recuperadividir = '';
			$recuperamultiplicar = '';

			$mensaje1 = '';
			$mensaje2 = '';
			$Mensaje = '';
			$error = '';

	if(count($_POST) != 0){

		if($parametros['ir_a'] != 'S'){

			//--------------------------------------------------------------------------
			//-- SECCION DEL CODIGO PARA EL DUPLICADO DE CUADROS COMPLETOS -------------
			//--------------------------------------------------------------------------

			if($parametros['actuar'] == 'DC'){

				if($parametros['folleto_original'] != '' and $parametros['cuadro_original'] != '' and $parametros['folleto_nuevo'] != '' and $parametros['cuadro_nuevo'] != ''){

						//DUPLICAR CON PROCEDIMIENTO
						$Mensaje = '';				

						$duplicar = "CALL `PRODUCTO_DUPLICA_CUADRO_COMPLETO`('".$parametros['folleto_original']."', '".$parametros['cuadro_original']."', '".$parametros['folleto_nuevo']."', '".$parametros['cuadro_nuevo']."', '".$parametros['numero_noches']."', '".$parametros['dividir']."', '".$parametros['multiplicar']."')";
						$resultadoduplicar =$conexion->query($duplicar);
							//echo($expandir);
						if ($resultadoduplicar == FALSE){
							$error = 'No se ha podido duplicar el cuadro: '.$conexion->error;
						}else{
							$Mensaje = 'Cuadro duplicado correctamente';
						}

				}else{
						$error = 'Se deben indicar los cuatro campos de la pantalla.';
				}


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
			}

			//--------------------------------------------------------------------------
			//-- SECCION DEL CODIGO PARA EL DUPLICADO DE CUADROS SIN PRECIOS -----------
			//--------------------------------------------------------------------------

			if($parametros['actuar'] == 'DS'){

				if($parametros['folleto_original'] != '' and $parametros['cuadro_original'] != '' and $parametros['folleto_nuevo'] != '' and $parametros['cuadro_nuevo'] != ''){

						//DUPLICAR CON PROCEDIMIENTO
						$Mensaje = '';				

						$duplicar = "CALL `PRODUCTO_DUPLICA_CUADRO_SIN_PRECIOS`('".$parametros['folleto_original']."', '".$parametros['cuadro_original']."', '".$parametros['folleto_nuevo']."', '".$parametros['cuadro_nuevo']."', '".$parametros['numero_noches']."')";
						$resultadoduplicar =$conexion->query($duplicar);
							//echo($expandir);
						if ($resultadoduplicar == FALSE){
							$error = 'No se ha podido duplicar el cuadro: '.$conexion->error;
						}else{
							$Mensaje = 'Cuadro duplicado correctamente';
						}

				}else{
						$error = 'Se deben indicar los cuatro campos de la pantalla.';
				}


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
			}

			//--------------------------------------------------------------------------
			//-- SECCION DEL CODIGO PARA EL DUPLICADO DE CALENDARIOS -------------------
			//--------------------------------------------------------------------------

			if($parametros['actuar'] == 'CA'){

				if($parametros['folleto_original'] != '' and $parametros['cuadro_original'] != '' and $parametros['folleto_nuevo'] != '' and $parametros['cuadro_nuevo'] != ''){

						//DUPLICAR CON PROCEDIMIENTO
						$Mensaje = '';				

						$duplicar = "CALL `PRODUCTO_DUPLICA_CALENDARIO`('".$parametros['folleto_original']."', '".$parametros['cuadro_original']."', '".$parametros['folleto_nuevo']."', '".$parametros['cuadro_nuevo']."')";
						$resultadoduplicar =$conexion->query($duplicar);
							//echo($expandir);
						if ($resultadoduplicar == FALSE){
							$error = 'No se ha podido duplicar el calendario: '.$conexion->error;
						}else{
							$Mensaje = 'Calendario duplicado correctamente';
						}

				}else{
						$error = 'Se deben indicar los cuatro campos de la pantalla.';
				}


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
			}

			//--------------------------------------------------------------------------
			//-- SECCION DEL CODIGO PARA EL DUPLICADO DE CONDICIONES DE CUADRO ---------
			//--------------------------------------------------------------------------

			if($parametros['actuar'] == 'CC'){

				if($parametros['folleto_original'] != '' and $parametros['cuadro_original'] != '' and $parametros['folleto_nuevo'] != '' and $parametros['cuadro_nuevo'] != ''){

						//DUPLICAR CON PROCEDIMIENTO
						$Mensaje = '';				

						$duplicar = "CALL `PRODUCTO_DUPLICA_CONDICIONES_CUADRO`('".$parametros['folleto_original']."', '".$parametros['cuadro_original']."', '".$parametros['folleto_nuevo']."', '".$parametros['cuadro_nuevo']."')";
						$resultadoduplicar =$conexion->query($duplicar);
							//echo($expandir);
						if ($resultadoduplicar == FALSE){
							$error = 'No se han podido duplicar las condiciones: '.$conexion->error;
						}else{
							$Mensaje = 'Condiciones duplicadas correctamente';
						}

				}else{
						$error = 'Se deben indicar los cuatro campos de la pantalla.';
				}


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
			}

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboFolletos = $oSino->Cargar_combo_Folletos();


	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS ALOJAMIENTOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('formulario', 'DUPLICADOS DE PRODUCTO');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboFolletos', $comboFolletos);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('folleto_original', $parametros['folleto_original']);
			$smarty->assign('cuadro_original', $parametros['cuadro_original']);
			$smarty->assign('folleto_nuevo', $parametros['folleto_nuevo']);
			$smarty->assign('cuadro_nuevo', $parametros['cuadro_nuevo']);
			$smarty->assign('numero_noches', $parametros['numero_noches']);
			$smarty->assign('dividir', $parametros['dividir']);
			$smarty->assign('multiplicar', $parametros['multiplicar']);

			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Producto_duplicados.html');


		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA

		$parametros['folleto_original'] = "";
		$parametros['cuadro_original'] = "";
		$parametros['folleto_nuevo'] = "";
		$parametros['cuadro_nuevo'] = "";
		$parametros['numero_noches'] = 0;
		$parametros['dividir'] = 0;
		$parametros['multiplicar'] = 0;


			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboFolletos = $oSino->Cargar_combo_Folletos();


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
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#30A1C7');
			$smarty->assign('grupo', '» PRODUCTO');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» DUPLICADOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboFolletos', $comboFolletos);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('folleto_original', '');
			$smarty->assign('cuadro_original', '');
			$smarty->assign('folleto_nuevo', '');
			$smarty->assign('cuadro_nuevo', '');
			$smarty->assign('numero_noches', 0);
			$smarty->assign('dividir', 1);
			$smarty->assign('multiplicar', 1);

		//Visualizar plantilla
		$smarty->display('plantillas/Producto_duplicados.html');
	}

	$conexion->close();


?>

