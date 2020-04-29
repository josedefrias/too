<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';

	session_start();

	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];
	/*$conexion = conexion_hit();*/

	/*echo('<pre>');
	print_r($usuario);
	echo('-');
	print_r($nombre);
	echo('</pre>');*/

	/*echo('<pre>');
	print_r($_POST);
	echo('</pre>');*/

	/*$parametros = $_POST;
	$parametrosg = $_GET;*/



			//Llamada a la clase general de combos
			/*$oSino = new clsGeneral($conexion);
			$comboFolletos = $oSino->Cargar_combo_Folletos();*/


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
			$smarty->assign('color_opcion', '#4C4C4C');
			$smarty->assign('grupo', '» ADMINISTRACION');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» INFORMES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);



		//Visualizar plantilla
		$smarty->display('plantillas/Administracion_informes.html');


	/*$conexion->close();*/


?>

