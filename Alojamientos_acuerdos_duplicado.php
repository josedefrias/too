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
			$recuperaalojamiento_original = '';
			$recuperaacuerdo_original = '';
			$recuperaalojamiento_nuevo = '';
			$recuperaacuerdo_nuevo = '';

			$mensaje1 = '';
			$mensaje2 = '';
			$Mensaje = '';
			$error = '';

	if(count($_POST) != 0){

		if($parametros['ir_a'] != 'S'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LOS ALOJAMIENTOS--------
			//--------------------------------------------------------------------------

			if($parametros['actuar'] == 'S'){

				if($parametros['alojamiento_original'] != '' and $parametros['acuerdo_original'] != '' and $parametros['alojamiento_nuevo'] != '' and $parametros['acuerdo_nuevo'] != ''){

						//DUPLICAR CON PROCEDIMIENTO
						$Mensaje = '';				

						$duplicar = "CALL `PROVEEDORES_DUPLICA_ACUERDO_ALOJAMIENTO2`('".$parametros['alojamiento_original']."', '".$parametros['acuerdo_original']."', '".$parametros['alojamiento_nuevo']."', '".$parametros['acuerdo_nuevo']."')";
						$resultadoduplicar =$conexion->query($duplicar);
							//echo($expandir);
						if ($resultadoduplicar == FALSE){
							$error = 'No se ha podido duplicar el acuerdo: '.$conexion->error;
						}else{
							$Mensaje = 'Acuerdo duplicado correctamente';
						}


						//DUPLICAR CON FUNCION
						/*$Mensaje = '';				

						$duplicar = "SELECT `PROVEEDORES_DUPLICA_ACUERDO_ALOJAMIENTO2`('".$parametros['alojamiento_original']."', '".$parametros['acuerdo_original']."', '".$parametros['alojamiento_nuevo']."', '".$parametros['acuerdo_nuevo']."') LOC";
						$resultadoduplicar =@$conexion->query($duplicar);

							//echo($expandir);
						if ($resultadoduplicar == FALSE){
							$error = 'No se ha podido duplicar el acuerdo: '.$conexion->error;
						}else{
							$localizador = $resultadoduplicar->fetch_assoc();
							$Mensaje = 'Acuerdo duplicado correctamente'.$localizador['LOC'];
						}*/



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
			//----VISUALIZAR PARTE DE LOS ALOJAMIENTOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
			$smarty->assign('formulario', '» DUPLICADO ACUERDOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('alojamiento_original', $parametros['alojamiento_original']);
			$smarty->assign('acuerdo_original', $parametros['acuerdo_original']);
			$smarty->assign('alojamiento_nuevo', $parametros['alojamiento_nuevo']);
			$smarty->assign('acuerdo_nuevo', $parametros['acuerdo_nuevo']);


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Alojamientos_acuerdos_duplicado.html');


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

		$parametros['alojamiento_original'] = "";
		$parametros['acuerdo_original'] = "";
		$parametros['alojamiento_nuevo'] = "";
		$parametros['acuerdo_nuevo'] = "";



			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
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
			//----VISUALIZAR PARTE DE LOS ALOJAMIENTOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
			$smarty->assign('formulario', '» DUPLICADO ACUERDOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('alojamiento_original', '');
			$smarty->assign('acuerdo_original', '');
			$smarty->assign('alojamiento_nuevo', '');
			$smarty->assign('acuerdo_nuevo', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Alojamientos_acuerdos_duplicado.html');
	}

	$conexion->close();


?>

